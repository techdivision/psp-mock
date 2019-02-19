<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Controller\Heidelpay\Api;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use TechDivision\PspMock\Entity\Address;
use TechDivision\PspMock\Entity\Heidelpay\Order;
use TechDivision\PspMock\Repository\Heidelpay\OrderRepository;
use TechDivision\PspMock\Service\Heidelpay\OrderToResponseMapper;
use TechDivision\PspMock\Service\Heidelpay\RequestMapper;
use TechDivision\PspMock\Service\Heidelpay\StateIdGenerator;


/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class NgwPostController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var RequestMapper
     */
    private $requestMapper;

    /**
     * @var StateIdGenerator
     */
    private $stateIdGenerator;

    /**
     * @var OrderToResponseMapper
     */
    private $orderToResponseMapper;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @param LoggerInterface $logger
     * @param ObjectManager $objectManager
     * @param RequestMapper $requestMapper
     * @param StateIdGenerator $stateIdGenerator
     * @param OrderToResponseMapper $orderToResponseMapper
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        LoggerInterface $logger,
        ObjectManager $objectManager,
        RequestMapper $requestMapper,
        StateIdGenerator $stateIdGenerator,
        OrderToResponseMapper $orderToResponseMapper,
        OrderRepository $orderRepository
    )
    {
        $this->logger = $logger;
        $this->objectManager = $objectManager;
        $this->requestMapper = $requestMapper;
        $this->stateIdGenerator = $stateIdGenerator;
        $this->orderToResponseMapper = $orderToResponseMapper;
        $this->orderRepository = $orderRepository;

        $this->response = new Response();
        $this->response->headers->set('Content-Type', 'application/json;charset=UTF-8');
        $this->response->headers->set('Transfer-Encoding', 'chunked');
        $this->response->headers->set('Connection', 'close');
        $this->response->headers->set('Keep-Alive', 'timeout=2, max=1000');
        $this->response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $this->response->headers->set('X-Content-Type-Options', 'nosniff');
        $this->response->headers->set('X-XSS-Protection', '1');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request)
    {
        try {
            if ($request->getMethod() === "POST") {
                switch ($request->get(Order::PAYMENT . 'CODE')) {
                    case 'CC.PA':
                        // If preauthorization
                        /** @var Order $order */
                        $order = new Order();
                        $address = new Address();
                        $this->requestMapper->mapRequestToOrder($request, $order, $address);

                        $order->setStateId($this->stateIdGenerator->get());
                        $order->setCreated(new \DateTime());

                        // This is neccessary because the order is being created every time before
                        // the account holder is set
                        $this->removeDuplicatedEntries($order->getTransactionId());

                        $this->objectManager->persist($address);
                        $this->objectManager->persist($order);
                        $this->objectManager->flush();

                        return $this->buildResponse($order);

                    case 'CC.CP':
                        // If capturing/invoice
                        /** @var Order $order */
                        $order = $this->orderRepository->findOneBy(
                            array('transactionId' => $request->get(
                                Order::IDENTIFICATION . 'TRANSACTIONID')));

                        $this->setCapturingParams($request, $order);

                        $this->objectManager->persist($order);
                        $this->objectManager->flush();

                        return $this->buildResponse($order, true);

                    case 'CC.RF':
                        // If refund
                        /** @var Order $order */
                        $order = $this->orderRepository->findOneBy(
                            array('transactionId' => $request->get(
                                Order::IDENTIFICATION . 'TRANSACTIONID')));
                        $order->setCode($request->get(Order::PAYMENT . 'CODE'));

                        $this->objectManager->persist($order);
                        $this->objectManager->flush();

                        return $this->buildResponse($order, true);
                    default:
                        throw new \Exception('No such Payment Code supported: ' . $request->get(Order::PAYMENT . 'CODE'));
                }
            } else {
                throw new \Exception('No such Method supported: ' . $request->getMethod());
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception);
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Order $order
     * @param bool $withCreditCard
     * @return Response
     */
    private function buildResponse(Order $order, bool $withCreditCard = false)
    {
        $this->response->setContent($this->orderToResponseMapper->map($order, $withCreditCard, false));
        return $this->response;
    }

    /**
     * @param Request $request
     * @param Order $order
     */
    private function setCapturingParams(Request $request, Order $order)
    {
        $order->setEnabled($request->get(Order::FRONTEND . 'ENABLED'));
        $order->setCode($request->get(Order::PAYMENT . 'CODE'));
        $order->setReferenceId($request->get(Order::IDENTIFICATION . 'REFERENCEID'));
    }

    /**
     * @param string $transactionId
     */
    private function removeDuplicatedEntries(string $transactionId){
        $order = $this->orderRepository->findOneBy(array('transactionId' => $transactionId));
        if($order !== null){
            $this->objectManager->remove($order);
            $this->objectManager->flush();
        }
    }
}

<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Controller\Heidelpay\ClientApi;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use TechDivision\PspMock\Controller\Interfaces\PspRequestControllerInterface;
use TechDivision\PspMock\Entity\Address;
use TechDivision\PspMock\Entity\Heidelpay\Order;
use TechDivision\PspMock\Repository\ConfigurationRepository;
use TechDivision\PspMock\Repository\Heidelpay\OrderRepository;
use TechDivision\PspMock\Service\EntitySaver;
use TechDivision\PspMock\Service\Heidelpay\ClientApi\OrderToResponseMapper;
use TechDivision\PspMock\Service\Heidelpay\ClientApi\RequestMapper;
use TechDivision\PspMock\Service\RandomStringProvider;


/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class RequestController extends AbstractController implements PspRequestControllerInterface
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
     * @var RandomStringProvider
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
     * @var ConfigurationRepository
     */
    private $configurationRepository;

    /**
     * @var EntitySaver
     */
    private $entitySaver;

    /**
     * @var string
     */
    private $failOnPreauth;

    /**
     * @var string
     */
    private $failOnCapture;

    /**
     * @var string
     */
    private $failOnRefund;

    /**
     * @param LoggerInterface $logger
     * @param ObjectManager $objectManager
     * @param RequestMapper $requestMapper
     * @param RandomStringProvider $stateIdGenerator
     * @param OrderToResponseMapper $orderToResponseMapper
     * @param OrderRepository $orderRepository
     * @param ConfigurationRepository $configurationRepository
     * @param EntitySaver $entitySaver
     */
    public function __construct(
        LoggerInterface $logger,
        ObjectManager $objectManager,
        RequestMapper $requestMapper,
        RandomStringProvider $stateIdGenerator,
        OrderToResponseMapper $orderToResponseMapper,
        OrderRepository $orderRepository,
        ConfigurationRepository $configurationRepository,
        EntitySaver $entitySaver
    )
    {
        $this->logger = $logger;
        $this->objectManager = $objectManager;
        $this->requestMapper = $requestMapper;
        $this->stateIdGenerator = $stateIdGenerator;
        $this->orderToResponseMapper = $orderToResponseMapper;
        $this->orderRepository = $orderRepository;
        $this->configurationRepository = $configurationRepository;
        $this->entitySaver = $entitySaver;

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
            $this->loadSettings();
            if ($request->getMethod() === "POST") {
                switch ($request->get(Order::PAYMENT . 'CODE')) {
                    case 'CC.PA':
                        // If preauthorization
                        /** @var Order $order */
                        $order = new Order();
                        $address = new Address();
                        $this->requestMapper->map($request, $order, $address);

                        $this->removeDuplicatedEntries($order->getTransactionId());

                        // If flag is set return a 'NOK' Message
                        ($this->failOnPreauth === '0') ? $this->setAck($order) : $this->setNok($order);

                        $this->entitySaver->save([$address, $order]);

                        return $this->buildResponse($order);

                    case 'CC.CP':
                        // If capturing/invoice
                        /** @var Order $order */
                        $order = $this->orderRepository->findOneBy(
                            array('transactionId' => $request->get(
                                Order::IDENTIFICATION . 'TRANSACTIONID')));

                        $this->setCapturingParams($request, $order);

                        // If flag is set return a 'NOK' Message
                        ($this->failOnCapture === '0') ? $this->setAck($order) : $this->setNok($order);

                        $this->entitySaver->save($order);

                        return $this->buildResponse($order, true);

                    case 'CC.RF':
                        // If refund
                        /** @var Order $order */
                        $order = $this->orderRepository->findOneBy(
                            array('transactionId' => $request->get(
                                Order::IDENTIFICATION . 'TRANSACTIONID')));
                        $order->setCode($request->get(Order::PAYMENT . 'CODE'));

                        // If flag is set return a 'NOK' Message
                        ($this->failOnRefund === '0') ? $this->setAck($order) : $this->setNok($order);

                        $this->entitySaver->save($order);

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
     * Loads the system settings for Heidelpay requests
     */
    private function loadSettings()
    {
        $this->failOnPreauth = $this->configurationRepository->findOneBy(array('path' => 'fail_on_preauth'))->getValue();
        $this->failOnCapture = $this->configurationRepository->findOneBy(array('path' => 'fail_on_capture'))->getValue();
        $this->failOnRefund = $this->configurationRepository->findOneBy(array('path' => 'fail_on_refund'))->getValue();
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
     * This is necessary because the order is being created every time before
     * the account holder is set
     * @param string $transactionId
     */
    private function removeDuplicatedEntries(string $transactionId)
    {
        $order = $this->orderRepository->findOneBy(array('transactionId' => $transactionId));
        if ($order !== null) {
            $this->objectManager->remove($order);
            $this->objectManager->flush();
        }
    }

    /**
     * @param Order $order
     */
    private function setAck(Order $order)
    {
        $order->setStatus('SUCCESS');
        $order->setStatusCode('00');
        $order->setResult('ACK');
        $order->setValidation('ACK');
        $order->setReturn("Request successfully processed in ''Merchant in Connector Test Mode''");
    }

    /**
     * @param Order $order
     */
    private function setNok(Order $order)
    {
        $order->setStatus('WAITING_BANK');
        $order->setStatusCode('59');
        $order->setResult('NOK');
        $order->setValidation('NOK');
        $order->setReturn("Request processed with errors in ''Merchant in Connector Test Mode''");
    }
}

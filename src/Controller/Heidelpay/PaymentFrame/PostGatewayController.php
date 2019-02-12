<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Controller\Heidelpay\PaymentFrame;


use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use TechDivision\PspMock\Entity\Account;
use TechDivision\PspMock\Entity\Heidelpay\Order;
use TechDivision\PspMock\Repository\Heidelpay\OrderRepository;
use TechDivision\PspMock\Service\Heidelpay\MissingDataGenerator;
use TechDivision\PspMock\Service\Heidelpay\OrderToResponseMapper;
use TechDivision\PspMock\Service\Heidelpay\RequestMapper;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class PostGatewayController extends AbstractController
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
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var RequestMapper
     */
    private $requestToOrderMapper;

    /**
     * @var OrderToResponseMapper
     */
    private $orderToResponseMapper;

    /**
     * @var MissingDataGenerator
     */
    private $missingDataGenerator;


    /**
     * @param LoggerInterface $logger
     * @param ObjectManager $objectManager
     * @param RequestMapper $requestToOrderMapper
     * @param OrderRepository $orderRepository
     * @param OrderToResponseMapper $orderToResponseMapper
     * @param MissingDataGenerator $missingDataGenerator
     */
    public function __construct(
        LoggerInterface $logger,
        ObjectManager $objectManager,
        RequestMapper $requestToOrderMapper,
        OrderRepository $orderRepository,
        OrderToResponseMapper $orderToResponseMapper,
        MissingDataGenerator $missingDataGenerator
    )
    {
        $this->logger = $logger;
        $this->objectManager = $objectManager;
        $this->requestToOrderMapper = $requestToOrderMapper;
        $this->orderRepository = $orderRepository;
        $this->orderToResponseMapper = $orderToResponseMapper;
        $this->missingDataGenerator = $missingDataGenerator;

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


        if ($request->getMethod() === "POST") {
            $account = new Account();
            try {
                $this->requestToOrderMapper->mapRequestToAccount($request, $account);
                $this->objectManager->persist($account);

                $order = $this->orderRepository->findOneBy(array('stateId' => json_decode($request->getContent(), true)['stateId']));
                $order->setAccount($account);

                $this->missingDataGenerator->generate($order);

                $this->objectManager->persist($order);
                $this->objectManager->flush();

                //TODO
                return $this->buildResponse($order);
            } catch (\Exception $exception) {
                $this->logger->error($exception);
                return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        //GET for testing
        $this->response->setContent('test');
        return $this->response;
    }

    /**
     * @param Order $order
     * @return Response
     */
    private function buildResponse(Order $order)
    {
        $this->response->setContent($this->orderToResponseMapper->map($order, true));
        return $this->response;
    }
}

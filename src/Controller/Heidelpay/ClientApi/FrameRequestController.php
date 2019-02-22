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
use TechDivision\PspMock\Controller\Interfaces\PspRequestControllerInterface;
use TechDivision\PspMock\Entity\Account;
use TechDivision\PspMock\Entity\Heidelpay\Order;
use TechDivision\PspMock\Repository\ConfigurationRepository;
use TechDivision\PspMock\Repository\Heidelpay\OrderRepository;
use TechDivision\PspMock\Service\EntitySaver;
use TechDivision\PspMock\Service\Heidelpay\ClientApi\AccountRequestMapper;
use TechDivision\PspMock\Service\Heidelpay\ClientApi\MissingDataProvider;
use TechDivision\PspMock\Service\Heidelpay\ClientApi\OrderToResponseMapper;
use TechDivision\PspMock\Service\Heidelpay\ClientApi\ConfirmQuoteCaller;
use TechDivision\PspMock\Service\Heidelpay\ClientApi\RedirectCaller;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class FrameRequestController extends AbstractController implements PspRequestControllerInterface
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
     * @var EntitySaver
     */
    private $entitySaver;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var ConfigurationRepository
     */
    private $configurationRepository;

    /**
     * @var AccountRequestMapper
     */
    private $accountRequestMapper;

    /**
     * @var OrderToResponseMapper
     */
    private $orderToResponseMapper;

    /**
     * @var MissingDataProvider
     */
    private $missingDataProvider;

    /**
     * @var ConfirmQuoteCaller
     */
    private $quoteConfirmer;

    /**
     * @var RedirectCaller
     */
    private $redirectCaller;

    /**
     * @var string
     */
    private $failOnIframe;

    /**
     * @param LoggerInterface $logger
     * @param EntitySaver $entitySaver
     * @param OrderRepository $orderRepository
     * @param OrderToResponseMapper $orderToResponseMapper
     * @param MissingDataProvider $missingDataProvider
     * @param ConfirmQuoteCaller $quoteConfirmer
     * @param RedirectCaller $redirectCaller
     * @param ConfigurationRepository $configurationRepository
     * @param AccountRequestMapper $accountRequestMapper
     */
    public function __construct(
        LoggerInterface $logger,
        EntitySaver $entitySaver,
        OrderRepository $orderRepository,
        OrderToResponseMapper $orderToResponseMapper,
        MissingDataProvider $missingDataProvider,
        ConfirmQuoteCaller $quoteConfirmer,
        RedirectCaller $redirectCaller,
        ConfigurationRepository $configurationRepository,
        AccountRequestMapper $accountRequestMapper
    ) {
        $this->logger = $logger;
        $this->entitySaver = $entitySaver;
        $this->orderRepository = $orderRepository;
        $this->orderToResponseMapper = $orderToResponseMapper;
        $this->missingDataProvider = $missingDataProvider;
        $this->quoteConfirmer = $quoteConfirmer;
        $this->redirectCaller = $redirectCaller;
        $this->configurationRepository = $configurationRepository;
        $this->accountRequestMapper = $accountRequestMapper;

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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function execute(Request $request)
    {
        try {
            $this->loadSettings();
            if ($request->getMethod() === "POST") {
                $account = new Account();

                $this->accountRequestMapper->map($request, $account);

                /** @var Order $order */
                $order = $this->orderRepository->findOneBy(
                    array('stateId' => json_decode($request->getContent(), true)['stateId']));

                $order->setAccount($account);

                $this->missingDataProvider->get($order);

                // If flag is set return a 'NOK' Message
                ($this->failOnIframe === '0') ? $this->setAck($order) : $this->setNok($order);

                $this->entitySaver->save([$order, $account]);

                return $this->buildResponse($order);
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
     * @return Response
     */
    private function buildResponse(Order $order)
    {
        $this->response->setContent($this->orderToResponseMapper->map($order, true, true));
        return $this->response;
    }

    /**
     * @param Order $order
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function setAck(Order $order)
    {
        $order->setResult('ACK');
        $order->setValidation('ACK');
        $order->setReturn("Request successfully processed in ''Merchant in Connector Test Mode''");

        // Calls 2 API endpoints of the heidelpay module
        $options = [];
        $this->quoteConfirmer->execute($order, $options);
        $this->redirectCaller->execute($order, $options);
    }

    /**
     * @param Order $order
     */
    private function setNok(Order $order)
    {
        $order->setResult('NOK');
        $order->setValidation('NOK');
        $order->setReturn("Request processed with errors in ''Merchant in Connector Test Mode''");
    }

    /**
     * Loads the system settings for Heidelpay requests
     */
    private function loadSettings()
    {
        $this->failOnIframe = $this->configurationRepository->findOneBy(array('path' => 'fail_on_iframe'))->getValue();
    }
}

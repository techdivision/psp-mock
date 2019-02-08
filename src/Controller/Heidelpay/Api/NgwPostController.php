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
     * @param LoggerInterface $logger
     * @param ObjectManager $objectManager
     * @param RequestMapper $requestMapper
     * @param StateIdGenerator $stateIdGenerator
     * @param OrderToResponseMapper $orderToResponseMapper
     */
    public function __construct(
        LoggerInterface $logger,
        ObjectManager $objectManager,
        RequestMapper $requestMapper,
        StateIdGenerator $stateIdGenerator,
        OrderToResponseMapper $orderToResponseMapper
    ) {
        $this->logger = $logger;
        $this->objectManager = $objectManager;
        $this->requestMapper = $requestMapper;
        $this->stateIdGenerator = $stateIdGenerator;
        $this->orderToResponseMapper = $orderToResponseMapper;

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
        if($request->getMethod() === "POST"){
            $order = new Order();
            $address = new Address();
            $this->requestMapper->mapRequestToOrder($request, $order, $address);

            $order->setStateId($this->stateIdGenerator->get());

            $this->objectManager->persist($address);
            $this->objectManager->persist($order);
            $this->objectManager->flush();

            return $this->buildResponse($order);
        }

        //GET for Testing
        $responseData = "FRONTEND.REDIRECT_URL=https%3A%2F%2Ftest-heidelpay.hpcgw.net%2Fngw%2Fwhitelabel%3Fstate%3D299d1509dc333e91a80b7be5%26lang%3DEN&PROCESSING.RESULT=ACK&POST.VALIDATION=ACK&FRONTEND.PAYMENT_FRAME_URL=https%3A%2F%2Ftest-heidelpay.hpcgw.net%2Fngw%2FpaymentFrame%3Fstate%3D53c29d737741c6b586146f91%26lang%3DEN&ADDRESS.CITY=hksadsad&ADDRESS.COUNTRY=US&ADDRESS.STREET=sadasd&ADDRESS.ZIP=84356&CONFIG.BRANDS=%7B%22MASTER%22%3A%22MasterCard%22%2C%22AMEX%22%3A%22American+Express%22%2C%22JCB%22%3A%22JCB%22%2C%22DISCOVERY%22%3A%22Discover%22%2C%22DINERS%22%3A%22Diners%22%2C%22CUP%22%3A%22China+Union+Pay%22%2C%22VISA%22%3A%22Visa%22%7D&CONTACT.EMAIL=admin%40admin.de&CONTACT.IP=127.0.0.1&CRITERION.GUEST=true&CRITERION.PAYMENT_METHOD=CreditCardPaymentMethod&CRITERION.PUSH_URL=https%3A%2F%2Fproject-community-edition.test%2Fhgw%2Findex%2Fpush%2F&CRITERION.SDK_NAME=Heidelpay%5CPhpPaymentApi&CRITERION.SDK_VERSION=v1.6.2&CRITERION.SECRET=80e0b309b201867db8ba86f237e8b58fe8041366c9d3f5aa49c4dd3309bc6d94cb358d0c6fd92028b5c63781560611cf7881ae7a20affb74199d845bfe879984&CRITERION.SHOP.TYPE=Magento+2.1.16-Community&CRITERION.SHOPMODULE.VERSION=Heidelpay+Gateway+18.10.1&FRONTEND.CSS_PATH=http%3A%2F%2Fdemoshops.heidelpay.de%2Fcss%2Fmage2-hpf.css&FRONTEND.ENABLED=TRUE&FRONTEND.LANGUAGE=EN&FRONTEND.MODE=WHITELABEL&FRONTEND.PAYMENT_FRAME_ORIGIN=https%3A%2F%2Fproject-community-edition.test&FRONTEND.PREVENT_ASYNC_REDIRECT=FALSE&FRONTEND.RESPONSE_URL=https%3A%2F%2Fproject-community-edition.test%2Fhgw%2Findex%2Fresponse%2F&IDENTIFICATION.TRANSACTIONID=9&NAME.COMPANY=Frau&NAME.FAMILY=Kiederle&NAME.GIVEN=sadasd&PAYMENT.CODE=CC.PA&PRESENTATION.AMOUNT=27.00&PRESENTATION.CURRENCY=EUR&REQUEST.VERSION=1.0&SECURITY.SENDER=31HA07BC8142C5A171745D00AD63D182&TRANSACTION.CHANNEL=31HA07BC8142C5A171744F3D6D155865&TRANSACTION.MODE=CONNECTOR_TEST&USER.LOGIN=31ha07bc8142c5a171744e5aef11ffd3&USER.PWD=93167DE7";
        $this->response->setContent($responseData);
        return $this->response;
    }


    /**
     * @param $order
     * @return Response
     */
    private function buildResponse(Order $order)
    {
        $this->response->setContent($this->orderToResponseMapper->map($order, false));
        return $this->response;
    }
}

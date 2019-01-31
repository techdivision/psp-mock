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
use TechDivision\PspMock\Service\Payone\ServerApi\RequestToOrderMapper;

class PostGateWayController extends AbstractController
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
     * @var RequestToOrderMapper
     */
    private $requestToOrderMapper;

    /**
     * @param LoggerInterface $logger
     * @param ObjectManager $objectManager
     * @param RequestToOrderMapper $requestToOrderMapper
     */
    public function __construct(
        LoggerInterface $logger,
        ObjectManager $objectManager,
        RequestToOrderMapper $requestToOrderMapper
    )
    {
        $this->logger = $logger;
        $this->objectManager = $objectManager;
        $this->requestToOrderMapper = $requestToOrderMapper;

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
        $requestType = $request->get('request');
        $responseData = "{
  \"NAME.FAMILY\": \"Kiederle\",
  \"CRITERION.SDK_NAME\": \"Heidelpay\\\\PhpPaymentApi\",
  \"IDENTIFICATION.TRANSACTIONID\": \"596\",
  \"ADDRESS.COUNTRY\": \"AT\",
  \"ADDRESS.STREET\": \"Teststraße 1\",
  \"PRESENTATION.AMOUNT\": \"27.00\",
  \"CRITERION.SDK_VERSION\": \"v1.4.1\",
  \"ACCOUNT.EXPIRY_MONTH\": \"06\",
  \"CRITERION.PUSH_URL\": \"https://project-community-edition.test/hgw/index/push/\",
  \"PROCESSING.TIMESTAMP\": \"2019-01-25 08:25:04\",
  \"CONTACT.EMAIL\": \"l.kiederle@techdivision.com\",
  \"CRITERION.SHOP.TYPE\": \"Magento 2.2.7-Enterprise\",
  \"ACCOUNT.BRAND\": \"VISA\",
  \"PROCESSING.STATUS.CODE\": \"90\",
  \"NAME.GIVEN\": \"Lukas\",
  \"IDENTIFICATION.SHORTID\": \"4123.3110.4434\",
  \"ADDRESS.CITY\": \"Testhausen\",
  \"CLEARING.AMOUNT\": \"27.00\",
  \"ACCOUNT.HOLDER\": \"Lukas Kiederle\",
  \"PROCESSING.CODE\": \"CC.PA.90.00\",
  \"PROCESSING.STATUS\": \"NEW\",
  \"PROCESSING.RETURN.CODE\": \"000.100.112\",
  \"CRITERION.PAYMENT_METHOD\": \"CreditCardPaymentMethod\",
  \"PROCESSING.RESULT\": \"ACK\",
  \"CRITERION.SHOPMODULE.VERSION\": \"Heidelpay Gateway 18.6.11\",
  \"CLEARING.CURRENCY\": \"EUR\",
  \"IDENTIFICATION.UNIQUEID\": \"31HA07BC8107D4D1EBBE090A63F698CF\",
  \"CRITERION.SECRET\": \"86eafc5b7de669edd09bff29e9072c27e994c6814a59a7dc50fa6664117a34c185509bb577b42d740267880241bc261c3be90ac8b30c18142cf0de49ee22f3d2\",
  \"ACCOUNT.EXPIRY_YEAR\": \"2024\",
  \"PRESENTATION.CURRENCY\": \"EUR\",
  \"PROCESSING.REASON.CODE\": \"00\",
  \"ACCOUNT.VERIFICATION\": \"***\",
  \"ADDRESS.ZIP\": \"1234\",
  \"ACCOUNT.NUMBER\": \"471110******0000\",
  \"CLEARING.DESCRIPTOR\": \"HSA*Kleen-Text|Kufstein|AT\",
  \"FRONTEND.PREVENT_ASYNC_REDIRECT\": \"FALSE\",
  \"PROCESSING.REASON\": \"SUCCESSFULL\",
  \"CRITERION.GUEST\": \"true\",
  \"PROCESSING.RETURN\": \"Request successfully processed in 'Merchant in Connector Test Mode'\",
  \"PROCESSING.REDIRECT_URL\": \"https://project-community-edition.test/hgw/index/redirect/\",
  \"FRONTEND.LANGUAGE\": \"EN\",
  \"PAYMENT.CODE\": \"CC.PA\"
}";
        return $this->buildResponse($responseData);
    }

    /**
     * @param string|int $txid
     * @return Response
     */
    private function approve($txid)
    {
        $responseData = [
            'status' => 'APPROVED',
            'txid' => $txid,
            'userid' => '123456',
        ];

        return $this->buildResponse($responseData);
    }

    /**
     * @param string $code
     * @param string $message
     * @return Response
     */
    private function error($code, $message)
    {
        $responseData = [
            'status' => 'ERROR',
            'errorcode' => $code,
            'customermessage' => $message,
        ];

        return $this->buildResponse($responseData);
    }

    /**
     * @param $data
     * @return Response
     */
    private function buildResponse($data)
    {
        $this->response->setContent($data);
        return $this->response;
    }
}

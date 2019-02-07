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
use TechDivision\PspMock\Entity\Heidelpay\Order;
use TechDivision\PspMock\Service\Payone\ServerApi\RequestToOrderMapper;

class PostGatwayController extends AbstractController
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

        $responseData = ['test' => 'test'];

        $requestType = $request->get('request');
        if (in_array($requestType, ['authorization', 'preauthorization'])) {
            $order = new Order();
            $this->requestToOrderMapper->map($request, $order);
            $this->objectManager->persist($order);
            $this->objectManager->flush();
            $txId = $order->getTransactionId();
        }

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

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
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var RequestMapper
     */
    private $requestToOrderMapper;

    /**
     * @param LoggerInterface $logger
     * @param ObjectManager $objectManager
     * @param RequestMapper $requestToOrderMapper
     * @param EntityManager $entityManager
     */
    public function __construct(
        LoggerInterface $logger,
        ObjectManager $objectManager,
        RequestMapper $requestToOrderMapper
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
        $responseData = 'test';

        $content = $request->getContent();

        if(isset($content)){
            $account = new Account();

            $this->requestToOrderMapper->mapRequestToAccount($request, $account);
            $this->objectManager->persist($account);
            //$this->entityManager->find('Order', 1);

            $this->objectManager->flush();
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

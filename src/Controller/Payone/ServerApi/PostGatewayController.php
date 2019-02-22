<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Controller\Payone\ServerApi;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TechDivision\PspMock\Controller\Interfaces\PspAbstractController;
use TechDivision\PspMock\Controller\Interfaces\PspRequestControllerInterface;
use TechDivision\PspMock\Entity\Address;
use TechDivision\PspMock\Entity\Customer;
use TechDivision\PspMock\Entity\Payone\Order;
use TechDivision\PspMock\Service\EntitySaver;
use TechDivision\PspMock\Service\RequestMapper;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Controller
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class PostGatewayController extends PspAbstractController implements PspRequestControllerInterface
{
    /**
     * @var Response
     */
    private $response;

    /**
     * @var EntitySaver
     */
    private $entitySaver;

    /**
     * @var RequestMapper
     */
    private $requestMapper;

    /**
     * @param LoggerInterface $logger
     * @param EntitySaver $entitySaver
     * @param RequestMapper $requestMapper
     */
    public function __construct(
        LoggerInterface $logger,
        EntitySaver $entitySaver,
        RequestMapper $requestMapper
    ) {
        parent::__construct($logger);
        $this->entitySaver = $entitySaver;
        $this->requestMapper = $requestMapper;

        $this->response = new Response();
        $this->response->headers->set('Content-Type', 'text/plain');
        $this->response->headers->set('Keep-Alive', 'timeout=2, max=1000');
        $this->response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $this->response->headers->set('X-Content-Type-Options', 'nosniff');
        $this->response->headers->set('X-XSS-Protection', '1; mode=block');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request)
    {
        try {
            $txId = 0;
            $requestType = $request->get('request');
            if (in_array($requestType, ['authorization', 'preauthorization'])) {
                $order = new Order();
                $address = new Address();
                $customer = new Customer();
                $this->requestMapper->map($request, $order, $address, $customer);
                $this->entitySaver->save([$order, $address, $address]);
                $txId = $order->getTransactionId();
            }
            return $this->approve($txId);
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());
            $this->logger->debug($exception->getTraceAsString());
            $this->logger->debug(var_export($request->request->all(), true));
            return $this->error('X10', $exception->getMessage());
        }
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
     * @param array $data
     * @return Response
     */
    private function buildResponse(array $data)
    {
        $body = [];
        foreach ($data as $key => $value) {
            $body[] = $key . '=' . $value;
        }

        $this->response->setContent(implode(PHP_EOL, $body));
        return $this->response;
    }
}

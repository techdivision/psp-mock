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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TechDivision\PspMock\Controller\Interfaces\PspAbstractController;
use TechDivision\PspMock\Controller\Interfaces\PspRequestControllerInterface;
use TechDivision\PspMock\Entity\Heidelpay\Order;
use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;
use TechDivision\PspMock\Service\Heidelpay\ClientApi\Handler\CaptureHandler;
use TechDivision\PspMock\Service\Heidelpay\ClientApi\Handler\PreauthHandler;
use TechDivision\PspMock\Service\Heidelpay\ClientApi\Handler\RefundHandler;
use TechDivision\PspMock\Service\Heidelpay\ClientApi\OrderToResponseMapper;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class RequestController extends PspAbstractController implements PspRequestControllerInterface
{
    /**
     * @var Response
     */
    private $response;

    /**
     * @var OrderToResponseMapper
     */
    private $orderToResponseMapper;

    /**
     * @var PreauthHandler
     */
    private $preauthHandler;

    /**
     * @var CaptureHandler
     */
    private $captureHandler;

    /**
     * @var RefundHandler
     */
    private $refundHandler;

    /**
     * @param LoggerInterface $logger
     * @param OrderToResponseMapper $orderToResponseMapper
     * @param PreauthHandler $preauthHandler
     * @param CaptureHandler $captureHandler
     * @param RefundHandler $refundHandler
     */
    public function __construct(
        LoggerInterface $logger,
        OrderToResponseMapper $orderToResponseMapper,
        PreauthHandler $preauthHandler,
        CaptureHandler $captureHandler,
        RefundHandler $refundHandler
    ) {
        parent::__construct($logger);
        $this->orderToResponseMapper = $orderToResponseMapper;
        $this->preauthHandler = $preauthHandler;
        $this->captureHandler = $captureHandler;
        $this->refundHandler = $refundHandler;

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
                        return $this->buildResponse(
                            $this->preauthHandler->handle($request));
                    case 'CC.CP':
                        // If capturing/invoice
                        return $this->buildResponse(
                            $this->captureHandler->handle($request), true);
                    case 'CC.RF':
                        // If refund
                        return $this->buildResponse(
                            $this->refundHandler->handle($request), true);
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
     * @param PspEntityInterface $order
     * @param bool $withCreditCard
     * @return Response
     */
    private function buildResponse(PspEntityInterface $order, bool $withCreditCard = false)
    {
        /** @var Order $order */
        $this->response->setContent($this->orderToResponseMapper->map($order, $withCreditCard, false));
        return $this->response;
    }
}

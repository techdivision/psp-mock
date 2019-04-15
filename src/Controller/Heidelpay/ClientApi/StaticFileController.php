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
use TechDivision\PspMock\Controller\Interfaces\PspRequestStaticControllerInterface;
use TechDivision\PspMock\Entity\Heidelpay\Order;
use TechDivision\PspMock\Repository\Heidelpay\OrderRepository;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class StaticFileController extends PspAbstractController implements PspRequestStaticControllerInterface
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * FrameController constructor.
     * @param OrderRepository $orderRepository
     * @param LoggerInterface $logger
     */
    public function __construct(OrderRepository $orderRepository, LoggerInterface $logger)
    {
        parent::__construct($logger);
        $this->orderRepository = $orderRepository;
    }

    /**
     * Returns the Payment frame with a stateId in order to reference the payment order
     *
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request): Response
    {
        try {
            /** @var Order $order */
            $order = $this->orderRepository->findOneBy(
                array('stateId' => $request->get('state')));

            return $this->render('heidelpay/payment/frame.html.twig', [
                'state' => $request->get('state'),
                'paymentFrameOrigin' => $order->getPaymentFrameOrigin(),
                'baseUrl' => 'https://' . $_SERVER['SERVER_NAME']
            ]);
        } catch (\Exception $exception) {
            $this->logger->error($exception);
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}

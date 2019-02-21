<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Controller\Gui;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TechDivision\PspMock\Controller\Interfaces\PspGuiListControllerInterface;
use TechDivision\PspMock\Entity\Payone\Order;
use TechDivision\PspMock\Repository\Payone\OrderRepository as PayoneOrderRepository;
use TechDivision\PspMock\Service\StatusManager;
use TechDivision\PspMock\Repository\Heidelpay\OrderRepository as HeidelpayOrderRepository;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Controller
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class OrderController extends AbstractController implements PspGuiListControllerInterface
{
    /**
     * @var PayoneOrderRepository
     */
    private $payoneOrderRepository;

    /**
     * @var HeidelpayOrderRepository
     */
    private $heidelpayOrderRepository;

    /**
     * @var StatusManager
     */
    private $statusManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param PayoneOrderRepository $payoneOrderRepository
     * @param HeidelpayOrderRepository $heidelpayOrderRepository
     * @param StatusManager $statusManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        PayoneOrderRepository $payoneOrderRepository,
        HeidelpayOrderRepository $heidelpayOrderRepository,
        StatusManager $statusManager,
        LoggerInterface $logger
    )
    {
        $this->payoneOrderRepository = $payoneOrderRepository;
        $this->heidelpayOrderRepository = $heidelpayOrderRepository;
        $this->statusManager = $statusManager;
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function list(Request $request)
    {
        try {
            switch ($request->get('type')) {
                case '':
                    // default is payone
                case 'payone':
                    return $this->render('gui/order/payone/list.html.twig', [
                        'orders' => $this->payoneOrderRepository->findBy([], ['created' => 'DESC'])
                    ]);
                case 'heidelpay':
                    return $this->render('gui/order/heidelpay/list.html.twig', [
                        'orders' => $this->heidelpayOrderRepository->findBy([], ['created' => 'DESC'])
                    ]);
                default:
                    throw new \Exception('No such type supported: ' . $request->get('type'));
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
    public function detail(Order $order)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');
        $response->setContent(var_export(json_decode($order->getRequestData(), true), true));
        return $response;
    }
}

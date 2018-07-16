<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Controller\Gui;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use TechDivision\PspMock\Entity\Order;
use TechDivision\PspMock\Repository\OrderRepository;
use TechDivision\PspMock\Service\StatusManager;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Controller
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class OrderController extends AbstractController
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;
    /**
     * @var StatusManager
     */
    private $statusManager;

    /**
     * @param OrderRepository $orderRepository
     * @param StatusManager $statusManager
     */
    public function __construct(
        OrderRepository $orderRepository,
        StatusManager $statusManager
    ) {
        $this->orderRepository = $orderRepository;
        $this->statusManager = $statusManager;
    }

    /**
     * @return Response
     */
    public function list()
    {
        return $this->render('gui/order/list.html.twig', [
            'orders' => $this->orderRepository->findBy([], ['created' => 'DESC'])
        ]);
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
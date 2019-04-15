<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Heidelpay\ClientApi\Handler;

use Symfony\Component\HttpFoundation\Request;
use TechDivision\PspMock\Entity\Heidelpay\Order;
use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;
use TechDivision\PspMock\Entity\Interfaces\PspOrderInterface;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class CaptureHandler extends AbstractHandler
{

    /**
     * @param Request $request
     * @return PspOrderInterface
     */
    public function handle(Request $request): PspOrderInterface
    {
        /** @var Order $order */
        $order = $this->orderRepository->findOneBy(
            array('transactionId' => $request->get(
                Order::IDENTIFICATION . 'TRANSACTIONID')));

        $this->setCapturingParams($request, $order);

        // If flag is set return a 'NOK' Message
        ($this->failOnCapture === '0')
            ? $this->ackProvider->get($order)
            : $this->nokProvider->get($order);
        $this->entitySaver->save($order);

        return $order;
    }

    /**
     * @param Request $request
     * @param Order $order
     */
    private function setCapturingParams(Request $request, Order $order): void
    {
        $order->setEnabled($request->get(Order::FRONTEND . 'ENABLED'));
        $order->setCode($request->get(Order::PAYMENT . 'CODE'));
        $order->setReferenceId($request->get(Order::IDENTIFICATION . 'REFERENCEID'));
    }
}

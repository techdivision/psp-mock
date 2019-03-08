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

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class RefundHandler extends AbstractHandler
{
    /**
     * @param Request $request
     * @return PspEntityInterface
     */
    public function handle(Request $request): PspEntityInterface
    {
        /** @var Order $order */
        $order = $this->orderRepository->findOneBy(
            array('transactionId' => $request->get(
                Order::IDENTIFICATION . 'TRANSACTIONID')));
        $order->setCode($request->get(Order::PAYMENT . 'CODE'));

        // If flag is set return a 'NOK' Message
        ($this->failOnRefund === '0')
            ? $this->ackProvider->get($order)
            : $this->nokProvider->get($order);
        $this->entitySaver->save($order);

        return $order;
    }
}

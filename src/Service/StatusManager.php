<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service;

use TechDivision\PspMock\Entity\Order;
use TechDivision\PspMock\Service\Payone\ServerApi\Callback\Action\Appoint;
use TechDivision\PspMock\Service\Payone\ServerApi\Callback\Action\PayFull;
use TechDivision\PspMock\Service\Payone\ServerApi\Callback\Action\PayPartial;
use TechDivision\PspMock\Service\Payone\ServerApi\Callback\Action\Refund;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Service
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class StatusManager
{
    /**
     * @var array
     */
    private $statusActions = [
        Order::STATUS_NEW => [
            [
                'action' => Appoint::ACTION_KEY,
                'label' => 'Appointed',
            ],
        ],
        Order::STATUS_APPOINTED => [
            [
                'action' => PayFull::ACTION_KEY,
                'label' => 'Paid',
            ],
        ],
        Order::STATUS_UNDERPAID => [
            [
                'action' => PayFull::ACTION_KEY,
                'label' => 'Paid',
            ],
            [
                'action' => Refund::ACTION_KEY,
                'label' => 'Debit',
            ],
        ],
        Order::STATUS_COMPLETE => [
            [
                'action' => Refund::ACTION_KEY,
                'label' => 'Debit',
            ],
        ],
        Order::STATUS_REFUNDED => [],
    ];

    /**
     * @var Order
     */
    private $order;

    /**
     * @return string
     */
    public function getProviderKey(): string
    {
        return 'payone';
    }

    /**
     * @return array
     */
    public function getActions(): array
    {
        if (isset($this->statusActions[$this->order->getStatus()])) {
            return $this->statusActions[$this->order->getStatus()];
        }
        return [];
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }
}
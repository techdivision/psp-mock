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
    const STATUS_NEW = 'NEW';
    const STATUS_APPOINTED = 'APPOINTED';
    const STATUS_PAID = 'PAID';
    const STATUS_DEBIT = 'DEBIT';

    const ACTION_APPOINT = 'appoint';
    const ACTION_PAY = 'pay';
    const ACTION_RETURN = 'return';

    /**
     * @var array
     */
    private $statusActions = [
        StatusManager::STATUS_NEW => [
            [
                'action' => StatusManager::ACTION_APPOINT,
                'status' => StatusManager::STATUS_APPOINTED,
                'remote_action' => 'appointed',
                'label' => 'Send Appointed',
            ],
        ],
        StatusManager::STATUS_APPOINTED => [
            [
                'action' => StatusManager::ACTION_PAY,
                'status' => StatusManager::STATUS_PAID,
                'remote_action' => 'paid',
                'label' => 'Send Paid',
            ],
        ],
        StatusManager::STATUS_PAID => [
            [
                'action' => StatusManager::ACTION_RETURN,
                'status' => StatusManager::STATUS_DEBIT,
                'remote_action' => 'debit',
                'label' => 'Send Debit',
            ],
        ],
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
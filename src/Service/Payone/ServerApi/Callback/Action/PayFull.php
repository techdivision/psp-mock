<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone\ServerApi\Callback\Action;

use TechDivision\PspMock\Entity\Order;
use TechDivision\PspMock\Service\Payone\ServerApi\Callback\ActionInterface;
use TechDivision\PspMock\Service\Payone\ServerApi\Callback\DataProvider;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Service
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class PayFull implements ActionInterface
{
    const ACTION_KEY = 'pay_full';
    const REMOTE_ACTION = 'paid';

    /**
     * @var DataProvider
     */
    private $dataProvider;

    /**
     * @param DataProvider $dataProvider
     */
    public function __construct(DataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * @param Order $order
     * @return ResultInterface
     */
    public function apply(Order $order): ResultInterface
    {
        $price = $order->getAmount() / 100;
        $balance = 0;
        $receivable = $price;

        $order->setBalance($balance);
        $order->setStatus(Order::STATUS_COMPLETE);

        $data = $this->dataProvider->get($order);
        $data = array_merge($data, [
            'txaction' => self::REMOTE_ACTION,
            'price' => $price,
            'balance' => $balance,
            'receivable' => $receivable,
        ]);

        return new Result(
            'https://test-psp-mock.test/payone/transactionstatus',
            'POST',
            [
                'form_params' => $data
            ]
        );
    }
}
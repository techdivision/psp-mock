<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone\ServerApi;

use Symfony\Component\HttpFoundation\Request;
use TechDivision\PspMock\Entity\Address;
use TechDivision\PspMock\Entity\Payone\Order;
use TechDivision\PspMock\Service\TransactionIdProvider;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Service
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class RequestOrderMapper
{
    /**
     * @var TransactionIdProvider
     */
    private $transactionIdProvider;

    /**
     * @param TransactionIdProvider $transactionIdProvider
     */
    public function __construct(TransactionIdProvider $transactionIdProvider)
    {
        $this->transactionIdProvider = $transactionIdProvider;
    }

    /**
     * @param Request $request
     * @param Order $order
     */
    public function map(Request $request, Order $order): void
    {

        $order->setAmount((string)$request->get('amount'));
        $order->setBalance($order->getAmount());
        $order->setCurrency((string)$request->get('currency'));
        $order->setClearingType((string)$request->get('clearingtype'));
        $order->setRequestType((string)$request->get('request'));
        $order->setReference((string)$request->get('reference'));
        $order->setTransactionId($this->transactionIdProvider->get());

        $order->setSuccessUrl((string)$request->get('successurl'));
        $order->setBackUrl((string)$request->get('backurl'));
        $order->setErrorUrl((string)$request->get('errorurl'));

        $order->setRequestData((string)json_encode($request->request->all()));

        $order->setStatus(Order::STATUS_NEW);
        $order->setCreated(new \DateTime());

        $order->setSequence(0);
    }
}

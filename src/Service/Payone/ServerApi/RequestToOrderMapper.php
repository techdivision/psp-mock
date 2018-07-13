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
use TechDivision\PspMock\Entity\Order;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Service
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class RequestToOrderMapper
{
    /**
     * @param Request $request
     * @param $order
     */
    public function map(Request $request, Order $order): void
    {
        $order->setAmount((string)$request->get('amount'));
        $order->setCurrency((string)$request->get('currency'));
        $order->setClearingType((string)$request->get('clearingtype'));
        $order->setRequestType((string)$request->get('request'));
        $order->setReference((string)$request->get('reference'));
        $order->setFirstName((string)$request->get('firstname'));
        $order->setLastName((string)$request->get('lastname'));
        $order->setStreet((string)$request->get('street'));
        $order->setZip((string)$request->get('zip'));
        $order->setCity((string)$request->get('city'));
        $order->setCountry((string)$request->get('country'));
        $order->setSuccessUrl((string)$request->get('successurl'));
        $order->setBackUrl((string)$request->get('backurl'));
        $order->setErrorUrl((string)$request->get('errorurl'));
        $order->setRequestData((string)json_encode($request->request->all()));
    }
}
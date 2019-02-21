<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone\ServerApi\Callback;

use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;
use TechDivision\PspMock\Entity\Payone\Order;
use TechDivision\PspMock\Service\Interfaces\PspEntityDataProviderInterface;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Service
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class DataProvider implements PspEntityDataProviderInterface
{
    /**
     * @param PspEntityInterface $order
     * @return array
     */
    public function get(PspEntityInterface $order)
    {
        /** @var Order $order */
        $data = [
            'reference' => $order->getReference(),
            'txid' => $order->getTransactionId(),
            'txaction' => '',
            'sequencenumber' => $order->getSequence(),
            'clearingtype' => $this->getParam($order, 'clearingtype'),
            'txtime' => time(),
            'price' => $order->getAmount() / 100,
            'balance' => $order->getAmount() / 100,
            'receivable' => $order->getAmount() / 100,
            'currency' => $order->getCurrency(),
            'aid' => $this->getParam($order, 'aid'),
            'portalid' => $this->getParam($order, 'portalid'),
            'key' => $this->getParam($order, 'key'),
            'mode' => $this->getParam($order, 'mode'),
            'userid' => 123456879,
            'customerid' => $this->getParam($order, 'customerid'),
            'company' => $this->getParam($order, 'company'),
            'firstname' => $this->getParam($order, 'firstname'),
            'lastname' => $this->getParam($order, 'lastname'),
            'street' => $this->getParam($order, 'street'),
            'zip' => $this->getParam($order, 'zip'),
            'city' => $this->getParam($order, 'city'),
            'email' => $this->getParam($order, 'email'),
            'country' => $this->getParam($order, 'country'),
            'shipping_company' => $this->getParam($order, 'shipping_company'),
            'shipping_firstname' => $this->getParam($order, 'shipping_firstname'),
            'shipping_lastname' => $this->getParam($order, 'shipping_lastname'),
            'shipping_street' => $this->getParam($order, 'shipping_street'),
            'shipping_zip' => $this->getParam($order, 'shipping_zip'),
            'shipping_city' => $this->getParam($order, 'shipping_city'),
            'shipping_country' => $this->getParam($order, 'shipping_country'),
            'param' => $this->getParam($order, 'param'),
            'accessname' => $this->getParam($order, 'accessname'),
            'accesscode' => $this->getParam($order, 'accesscode'),
            'bankcountry' => $this->getParam($order, 'bankcountry'),
            'bankaccount' => $this->getParam($order, 'bankaccount'),
            'bankcode' => $this->getParam($order, 'bankcode'),
            'bankaccountholder' => $this->getParam($order, 'bankaccountholder'),
            'cardexpiredate' => $this->getParam($order, 'cardexpiredate'),
            'cardtype' => $this->getParam($order, 'cardtype'),
            'cardpan' => $this->getParam($order, 'cardpan'),
            'clearing_bankaccountholder' => $this->getParam($order, 'clearing_bankaccountholder'),
            'clearing_bankaccount' => $this->getParam($order, 'clearing_bankaccount'),
            'clearing_bankcode' => $this->getParam($order, 'clearing_bankcode'),
            'clearing_bankname' => $this->getParam($order, 'clearing_bankname'),
            'clearing_bankbic' => $this->getParam($order, 'clearing_bankbic'),
            'clearing_bankiban' => $this->getParam($order, 'clearing_bankiban'),
            'clearing_legalnote' => $this->getParam($order, 'clearing_legalnote'),
            'clearing_duedate' => $this->getParam($order, 'clearing_duedate'),
            'clearing_reference' => $this->getParam($order, 'clearing_reference'),
            'clearing_instructionnote' => $this->getParam($order, 'clearing_instructionnote'),
        ];

        return $data;
    }

    /**
     * @param Order $order
     * @param string $paramKey
     * @param string $default
     * @return string
     */
    private function getParam(Order $order, $paramKey, $default = '')
    {
        $data = json_decode($order->getRequestData(), true);
        return $data[$paramKey] ?? $default;
    }
}

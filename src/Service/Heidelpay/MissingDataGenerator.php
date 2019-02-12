<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Heidelpay;


use TechDivision\PspMock\Entity\Heidelpay\Order;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class MissingDataGenerator
{
    const REDIRECT_URL = '/hgw/index/response';
    const RANDOM_STRING_LENGTH = 32;

    /**
     * @param Order $order
     */
    public function generate(Order $order)
    {
        $shortId = $this->generateShortId();

        //$order->setRedirectUrl('https://' . getenv('SERVICE_DOMAIN') . self::REDIRECT_URL);
        $order->setRedirectUrl($order->getResponseUrl());
        $order->setCCurrency($order->getPCurrency());
        $order->setCAmount($order->getPAmount());

        $order->setShortId($shortId);
        $order->setDescriptor($this->generateDescriptor($shortId));

        $order->setUniqueId($this->generateUniqueId());

        $order->setReturnCode("90");
        $order->setReasonCode("00");
        $order->setReason("SUCCESSFULL");
        $order->setReturn("Request successfully processed in 'Merchant in Connector Test Mode'");
        $order->setProcessingCode($order->getCode() . '.' . $order->getReturnCode() . '.' . "00");
    }

    /**
     * @return string
     */
    private function generateShortId()
    {
        return rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999);
    }

    /**
     * @param string $shortId
     * @return string
     */
    private function generateDescriptor(string $shortId){
        return $shortId . ' ' . rand(1000,9999) . '-Standard-Test-Merchant';
    }

    /**
     * @return mixed|null|string|string[]
     */
    private function generateUniqueId(){
        return mb_strtoupper(substr(md5(uniqid(rand(), true)), 0, self::RANDOM_STRING_LENGTH));
    }
}

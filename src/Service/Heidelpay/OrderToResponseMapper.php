<?php


namespace TechDivision\PspMock\Service\Heidelpay;

use TechDivision\PspMock\Entity\Heidelpay\Order;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class OrderToResponseMapper
{
    const HEIDELPAY_URL = 'https://test-heidelpay.hpcgw.net/ngw/whitelabel';
    const PAYMENT_FRAME = '/heidelpay/payment/frame';
    const SEPERATOR = '=';
    const CONNECTOR = '&';


    /**
     * Maps the order to a key-value based string with an urlencoded value
     *
     * @param Order $order
     * @return string
     */
    public function map(Order $order)
    {
        $data = [
            "FRONTEND.REDIRECT_URL" => self::HEIDELPAY_URL . '?state=' . $order->getStateId()
                . '&lang=' . $order->getLanguage(),

            "PROCESSING.RESULT" => $order->getResult(),

            "POST.VALIDATION" => $order->getValidation(),

            "FRONTEND.PAYMENT_FRAME_URL" => 'https://' . getenv('SERVICE_DOMAIN') . self::PAYMENT_FRAME
                . '?state=' . $order->getStateId() . '&lang' . $order->getLanguage(),

            "ADDRESS.CITY" => $order->getAddress()->getCity(),
            "ADDRESS.COUNTRY" => $order->getAddress()->getCountry(),
            "ADDRESS.STREET" => $order->getAddress()->getStreet(),
            "ADDRESS.ZIP" => $order->getAddress()->getZip(),

            "CONFIG.BRANDS" => $order->getBrands(),

            "CONTACT.EMAIL" => $order->getEmail(),
            "CONTACT.IP" => $order->getIp(),

            "CRITERION.GUEST" => $order->getGuest(),
            "CRITERION.PAYMENT_METHOD" => $order->getPaymentMethod(),
            "CRITERION.PUSH_URL" => $order->getPushUrl(),
            "CRITERION.SDK_NAME" => $order->getSdkName(),
            "CRITERION.SDK_VERSION" => $order->getSdkVersion(),
            "CRITERION.SECRET" => $order->getSecret(),
            "CRITERION.SHOP_TYPE" => $order->getShopType(),
            "CRITERION.SHOPMODULE_VERSION" => $order->getShopmoduleVerison(),

            "FRONTEND.CSS_PATH" => $order->getCssPath(),
            "FRONTEND.ENABLED" => $order->getEnabled(),
            "FRONTEND.LANGUAGE" => $order->getLanguage(),
            "FRONTEND.MODE" => $order->getFMode(),
            "FRONTEND.PAYMENT_FRAME_ORIGIN" => $order->getPaymentFrameOrigin(),
            "FRONTEND.PREVENT_ASYNC_REDIRECT" => $order->getPreventAsyncRedirect(),
            "FRONTEND.RESPONSE_URL" => $order->getResponseUrl(),

            "IDENTIFICATION.TRANSACTIONID" => $order->getTransactionId(),

            "NAME.COMPANY" => $order->getCompany(),
            "NAME.GIVEN" => $order->getFirstname(),
            "NAME.FAMILY" => $order->getLastname(),

            "PAYMENT.CODE" => $order->getCode(),

            "PRESENTATION.AMOUNT" => $order->getPAmount(),
            "PRESENTATION.CURRENCY" => $order->getPCurrency(),

            "REQUEST.VERSION" => $order->getVersion(),

            "SECURITY.SENDER" => $order->getSender(),

            "TRANSACTION.CHANNEL" => $order->getChannel(),
            "TRANSACTION.MODE" => $order->getMode(),

            "USER.LOGIN" => $order->getLogin(),
            "USER.PWD" => $order->getPwd(),
        ];

        return $this->mapArrayToString($data);
    }

    /**
     * @param $data
     * @return string
     */
    private function mapArrayToString($data)
    {

        $result = '';

        foreach ($data as $key => $value) {

            if ($result === '') {
                $result = $key . self::SEPERATOR . urlencode($value);
            }
            $result = $result . self::CONNECTOR . $key . self::SEPERATOR . urlencode($value);
        }

        return $result;
    }
}

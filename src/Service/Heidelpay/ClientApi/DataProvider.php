<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Heidelpay\ClientApi;


use TechDivision\PspMock\Entity\Heidelpay\Order;
use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http=>//www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class DataProvider
{
    /**
     * @param PspEntityInterface $order
     * @return array
     */
    public function get(PspEntityInterface $order)
    {
        /** @var Order $order */
        $data = [
            "__store" => $order->getStore(),
            "NAME_FAMILY" => $order->getLastname(),
            "CRITERION_SDK_NAME" => $order->getSdkName(),
            "CRITERION_SDK_VERSION" => $order->getSdkVersion(),
            "IDENTIFICATION_TRANSACTIONID" => $order->getTransactionId(),
            "ADDRESS_COUNTRY" => $order->getAddress()->getCountry(),
            "ADDRESS_STREET" => $order->getAddress()->getStreet(),
            "CONFIG_BRANDS" => $order->getBrands(),
            "FRONTEND_ENABLED" => $order->getEnabled(),
            "PRESENTATION_AMOUNT" => $order->getPAmount(),
            "TRANSACTION_MODE" => $order->getMode(),
            "CONTACT_IP" => $order->getIp(),
            "ACCOUNT_EXPIRY_MONTH" => $order->getAccount()->getExpiryMonth(),
            "CRITERION_PUSH_URL" => $order->getPushUrl(),
            "PROCESSING_TIMESTAMP" => $order->getTimestamp(),
            "CONTACT_EMAIL" => $order->getEmail(),
            "CRITERION_SHOP_TYPE" => $order->getShopType(),
            "FRONTEND_RESPONSE_URL" => $order->getResponseUrl(),
            "REQUEST_VERSION" => $order->getVersion(),
            "ACCOUNT_BRAND" => $order->getAccount()->getBrand(),
            "PROCESSING_STATUS_CODE" => $order->getStatusCode(),
            "NAME_GIVEN" => $order->getFirstname(),
            "FRONTEND_PAYMENT_FRAME_ORIGIN" => $order->getPaymentFrameOrigin(),
            "IDENTIFICATION_SHORTID" => $order->getShortId(),
            "ADDRESS_CITY" => $order->getAddress()->getCity(),
            "CLEARING_AMOUNT" => $order->getCAmount(),
            "ACCOUNT_HOLDER" => $order->getAccount()->getHolder(),
            "PROCESSING_CODE" => $order->getProcessingCode(),
            "PROCESSING_STATUS" => $order->getStatus(),
            "SECURITY_SENDER" => $order->getSender(),
            "USER_LOGIN" => $order->getLogin(),
            "FRONTEND_CSS_PATH" => $order->getCssPath(),
            "USER_PWD" => $order->getPwd(),
            "PROCESSING_RETURN_CODE" => $order->getReturnCode(),
            "CRITERION_PAYMENT_METHOD" => $order->getPaymentMethod(),
            "PROCESSING_RESULT" => $order->getResult(),
            "CRITERION_SHOPMODULE_VERSION" => $order->getShopmoduleVerison(),
            "CLEARING_CURRENCY" => $order->getCCurrency(),
            "FRONTEND_MODE" => $order->getFMode(),
            "IDENTIFICATION_UNIQUEID" => $order->getUniqueId(),
            "CRITERION_SECRET" => $order->getSecret(),
            "ACCOUNT_EXPIRY_YEAR" => $order->getAccount()->getExpiryYear(),
            "PRESENTATION_CURRENCY" => $order->getPCurrency(),
            "NAME_COMPANY" => $order->getCompany(),
            "PROCESSING_REASON_CODE" => $order->getReasonCode(),
            "ACCOUNT_VERIFICATION" => $order->getAccount()->getVerification(),
            "ADDRESS_ZIP" => $order->getAddress()->getZip(),
            "ACCOUNT_NUMBER" => $order->getAccount()->getNumber(),
            "CLEARING_DESCRIPTOR" => $order->getDescriptor(),
            "FRONTEND_PREVENT_ASYNC_REDIRECT" => $order->getPreventAsyncRedirect(),
            "PROCESSING_REASON" => $order->getReason(),
            "CRITERION_GUEST" => $order->getGuest(),
            "PROCESSING_RETURN" => $order->getReturn(),
            "TRANSACTION_CHANNEL" => $order->getChannel(),
            "FRONTEND_LANGUAGE" => $order->getLanguage(),
            "PAYMENT_CODE" => $order->getCode()
        ];

        return $data;
    }
}

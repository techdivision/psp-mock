<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Heidelpay\ClientApi;

use DateTime;
use Symfony\Component\HttpFoundation\Request;
use TechDivision\PspMock\Entity\Heidelpay\Order;
use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;
use TechDivision\PspMock\Service\Interfaces\PspRequestToEntityMapperInterface;
use TechDivision\PspMock\Service\RandomStringProvider;
use TechDivision\PspMock\Service\TransactionIdProvider;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class OrderRequestMapper implements PspRequestToEntityMapperInterface
{
    /**
     * @var TransactionIdProvider
     */
    private $transactionIdProvider;

    /**
     * @var RandomStringProvider
     */
    private $randomStringProvider;

    /**
     * @param TransactionIdProvider $transactionIdProvider
     * @param RandomStringProvider $randomStringProvider
     */
    public function __construct(
        TransactionIdProvider $transactionIdProvider,
        RandomStringProvider $randomStringProvider
    )
    {
        $this->transactionIdProvider = $transactionIdProvider;
        $this->randomStringProvider = $randomStringProvider;
    }

    /**
     * @param Request $request
     * @param PspEntityInterface $order
     */
    public function map(Request $request, PspEntityInterface $order): void
    {
        /** @var Order $order */
        $order->setTransactionId($request->get(Order::IDENTIFICATION . 'TRANSACTIONID'));

        $order->setEmail($request->get(Order::CONTACT . 'EMAIL'));
        $order->setIp($request->get(Order::CONTACT . 'IP'));

        $order->setPaymentMethod($request->get(Order::CRITERION . 'PAYMENT_METHOD'));
        $order->setSecret($request->get(Order::CRITERION . 'SECRET'));
        $order->setSdkName($request->get(Order::CRITERION . 'SDK_NAME'));
        $order->setSdkVersion($request->get(Order::CRITERION . 'SDK_VERSION'));
        $order->setGuest($request->get(Order::CRITERION . 'GUEST'));
        $order->setShopType($request->get(Order::CRITERION . 'SHOP_TYPE'));
        $order->setShopmoduleVerison($request->get(Order::CRITERION . 'SHOPMODULE_VERSION'));
        $order->setPushUrl($request->get(Order::CRITERION . 'PUSH_URL'));

        $order->setCssPath($request->get(Order::FRONTEND . 'CSS_PATH'));
        $order->setEnabled($request->get(Order::FRONTEND . 'ENABLED'));
        $order->setLanguage($request->get(Order::FRONTEND . 'LANGUAGE'));
        $order->setFMode($request->get(Order::FRONTEND . 'MODE'));
        $order->setPaymentFrameOrigin($request->get(Order::FRONTEND . 'PAYMENT_FRAME_ORIGIN'));
        $order->setPreventAsyncRedirect($request->get(Order::FRONTEND . 'PREVENT_ASYNC_REDIRECT'));
        $order->setResponseUrl($request->get(Order::FRONTEND . 'RESPONSE_URL'));

        $order->setCompany($request->get(Order::NAME . 'COMPANY'));
        $order->setFirstname($request->get(Order::NAME . 'GIVEN'));
        $order->setLastname($request->get(Order::NAME . 'FAMILY'));

        $order->setCode($request->get(Order::PAYMENT . 'CODE'));

        $order->setPCurrency((string)$request->get(Order::PRESENTATION . 'CURRENCY'));
        $order->setPAmount((string)($request->get(Order::PRESENTATION . 'AMOUNT') * 100));

        $order->setVersion($request->get(Order::REQUEST . 'VERSION'));

        $order->setSender($request->get(Order::SECURITY . 'SENDER'));

        $order->setChannel($request->get(Order::TRANSACTION . 'CHANNEL'));
        $order->setMode($request->get(Order::TRANSACTION . 'MODE'));

        $order->setLogin($request->get(Order::USER . 'LOGIN'));
        $order->setPwd($request->get(Order::USER . 'PWD'));

        $order->setInitialRequestData((string)json_encode($request->request->all()));

        $order->setStatus(Order::STATUS_NEW);
        $date = new DateTime();
        $order->setTimestamp($date->getTimestamp());

        $order->setStateId($this->randomStringProvider->get(Order::STATE_ID_LENGTH));
        $order->setCreated(new \DateTime());
    }
}

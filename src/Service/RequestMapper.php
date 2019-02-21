<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service;

use Symfony\Component\HttpFoundation\Request;
use TechDivision\PspMock\Entity\Address;
use TechDivision\PspMock\Entity\Customer;
use TechDivision\PspMock\Entity\Heidelpay\Order as HeidelpayOrder;
use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;
use TechDivision\PspMock\Entity\Payone\Order as PayoneOrder;
use TechDivision\PspMock\Service\Heidelpay\RequestMapper as HeidelpayRequestMapper;
use TechDivision\PspMock\Service\Interfaces\PspRequestMapperInterface;
use TechDivision\PspMock\Service\Payone\RequestMapper as PayoneRequestMapper;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class RequestMapper implements PspRequestMapperInterface
{
    /**
     * @var PayoneRequestMapper
     */
    private $payoneRequestMapper;

    /**
     * @var HeidelpayRequestMapper
     */
    private $heidelpayRequestMapper;

    /**
     * RequestMapper constructor.
     * @param PayoneRequestMapper $payoneRequestMapper
     * @param HeidelpayRequestMapper $heidelpayRequestMapper
     */
    public function __construct(
        PayoneRequestMapper $payoneRequestMapper,
        HeidelpayRequestMapper $heidelpayRequestMapper
    ) {
        $this->payoneRequestMapper = $payoneRequestMapper;
        $this->heidelpayRequestMapper = $heidelpayRequestMapper;
    }

    /**
     * @param Request $request
     * @param PspEntityInterface $order
     * @param PspEntityInterface $address
     * @param PspEntityInterface $customer
     * @throws \Exception
     */
    public function map(
        Request $request,
        PspEntityInterface $order,
        PspEntityInterface $address,
        PspEntityInterface $customer
    ): void {

        switch (true) {
            case $order instanceof HeidelpayOrder:
                $this->heidelpayRequestMapper->map($request, $order, $address, $customer);
                break;
            case $order instanceof PayoneOrder:
                $this->payoneRequestMapper->map($request, $order, $address, $customer);
                break;
            default:
                throw new \Exception('Could not map class: ' . get_class($order));
        }

        /** @var Address $address */
        /** @var Customer $customer */
        $order->setAddress($address);
        $order->setCustomer($customer);
    }
}

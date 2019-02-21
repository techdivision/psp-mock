<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Heidelpay\ClientApi;

use Symfony\Component\HttpFoundation\Request;
use TechDivision\PspMock\Entity\Address;
use TechDivision\PspMock\Entity\Customer;
use TechDivision\PspMock\Entity\Heidelpay\Order;
use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class RequestMapper implements RequestMapperInterface
{
    /**
     * @var OrderRequestMapper
     */
    private $orderRequestMapper;

    /**
     * @var AddressRequestMapper
     */
    private $addressRequestMapper;

    /**
     * @var CustomerRequestMapper
     */
    private $customerRequestMapper;

    /**
     * RequestMapper constructor.
     * @param OrderRequestMapper $orderRequestMapper
     * @param AddressRequestMapper $addressRequestMapper
     * @param CustomerRequestMapper $customerRequestMapper
     */
    public function __construct(
        OrderRequestMapper $orderRequestMapper,
        AddressRequestMapper $addressRequestMapper,
        CustomerRequestMapper $customerRequestMapper
    )
    {
        $this->orderRequestMapper = $orderRequestMapper;
        $this->addressRequestMapper = $addressRequestMapper;
        $this->customerRequestMapper = $customerRequestMapper;
    }

    /**
     * @param Request $request
     * @param PspEntityInterface $order
     * @param PspEntityInterface $address
     * @param PspEntityInterface $customer
     */
    public function map(
        Request $request,
        PspEntityInterface $order,
        PspEntityInterface $address,
        PspEntityInterface $customer
    ): void
    {
        $this->orderRequestMapper->map($request, $order);
        $this->addressRequestMapper->map($request, $address);
        $this->customerRequestMapper->map($request, $customer);

        /** @var Order $order */
        /** @var Address $address */
        /** @var Customer $customer */
        $order->setAddress($address);
        $order->setCustomer($customer);
    }
}

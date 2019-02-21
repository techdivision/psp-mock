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

    public function __construct(OrderRequestMapper $orderRequestMapper, AddressRequestMapper $addressRequestMapper)
    {
        $this->orderRequestMapper = $orderRequestMapper;
        $this->addressRequestMapper = $addressRequestMapper;
    }

    /**
     * @param Request $request
     * @param PspEntityInterface $order
     * @param PspEntityInterface $address
     */
    public function map(Request $request, PspEntityInterface $order, PspEntityInterface $address): void
    {
        $this->orderRequestMapper->map($request, $order);
        $this->addressRequestMapper->map($request, $address);

        /** @var Order $order */
        /** @var Address $address */
        $order->setAddress($address);
    }
}

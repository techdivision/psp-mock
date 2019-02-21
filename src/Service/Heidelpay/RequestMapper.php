<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Heidelpay;

use Symfony\Component\HttpFoundation\Request;
use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;
use TechDivision\PspMock\Entity\Payone\Order;
use TechDivision\PspMock\Service\Heidelpay\ClientApi\RequestAddressMapper as HeidelpayRequestAddressMapper;
use TechDivision\PspMock\Service\Heidelpay\ClientApi\RequestCustomerMapper as HeidelpayRequestCustomerMapper;
use TechDivision\PspMock\Service\Heidelpay\ClientApi\RequestOrderMapper as HeidelpayOrderRequestMapper;
use TechDivision\PspMock\Service\Interfaces\PspRequestMapperInterface;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class RequestMapper implements PspRequestMapperInterface
{
    /**
     * @var HeidelpayOrderRequestMapper
     */
    private $heidelpayRequestOrderMapper;

    /**
     * @var HeidelpayRequestAddressMapper
     */
    private $heidelpayRequestAddressMapper;

    /**
     * @var HeidelpayRequestCustomerMapper
     */
    private $heidelpayRequestCustomerMapper;

    /**
     * RequestMapper constructor.
     * @param HeidelpayOrderRequestMapper $heidelpayRequestOrderMapper
     * @param HeidelpayRequestAddressMapper $heidelpayRequestAddressMapper
     * @param HeidelpayRequestCustomerMapper $heidelpayRequestCustomerMapper
     */
    public function __construct(
        HeidelpayOrderRequestMapper $heidelpayRequestOrderMapper,
        HeidelpayRequestAddressMapper $heidelpayRequestAddressMapper,
        HeidelpayRequestCustomerMapper $heidelpayRequestCustomerMapper
    ) {
        $this->heidelpayRequestOrderMapper = $heidelpayRequestOrderMapper;
        $this->heidelpayRequestAddressMapper = $heidelpayRequestAddressMapper;
        $this->heidelpayRequestCustomerMapper = $heidelpayRequestCustomerMapper;
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
        /** @var Order $order */
        $this->heidelpayRequestOrderMapper->map($request, $order);
        $this->heidelpayRequestAddressMapper->map($request, $address);
        $this->heidelpayRequestCustomerMapper->map($request, $customer);
    }
}

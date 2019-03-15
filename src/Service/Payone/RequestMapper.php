<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone;

use Symfony\Component\HttpFoundation\Request;
use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;
use TechDivision\PspMock\Entity\Interfaces\PspOrderInterface;
use TechDivision\PspMock\Entity\Payone\Order;
use TechDivision\PspMock\Service\Interfaces\PspRequestMapperInterface;
use TechDivision\PspMock\Service\Payone\ServerApi\RequestAddressMapper as PayoneRequestAddressMapper;
use TechDivision\PspMock\Service\Payone\ServerApi\RequestCustomerMapper as PayoneRequestCustomerMapper;
use TechDivision\PspMock\Service\Payone\ServerApi\RequestOrderMapper as PayoneRequestOrderMapper;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class RequestMapper implements PspRequestMapperInterface
{
    /**
     * @var PayoneRequestOrderMapper
     */
    private $payoneRequestOrderMapper;

    /**
     * @var PayoneRequestAddressMapper
     */
    private $payoneRequestAddressMapper;

    /**
     * @var PayoneRequestCustomerMapper
     */
    private $payoneRequestCustomerMapper;

    /**
     * RequestMapper constructor.
     * @param PayoneRequestOrderMapper $payoneRequestOrderMapper
     * @param PayoneRequestAddressMapper $payoneRequestAddressMapper
     * @param PayoneRequestCustomerMapper $payoneRequestCustomerMapper
     */
    public function __construct(
        PayoneRequestOrderMapper $payoneRequestOrderMapper,
        PayoneRequestAddressMapper $payoneRequestAddressMapper,
        PayoneRequestCustomerMapper $payoneRequestCustomerMapper
    ) {
        $this->payoneRequestOrderMapper = $payoneRequestOrderMapper;
        $this->payoneRequestAddressMapper = $payoneRequestAddressMapper;
        $this->payoneRequestCustomerMapper = $payoneRequestCustomerMapper;
    }

    /**
     * @param Request $request
     * @param PspOrderInterface $order
     * @param PspEntityInterface $address
     * @param PspEntityInterface $customer
     */
    public function map(
        Request $request,
        PspOrderInterface $order,
        PspEntityInterface $address,
        PspEntityInterface $customer
    ): void {
        /** @var Order $order */
        $this->payoneRequestOrderMapper->map($request, $order);
        $this->payoneRequestAddressMapper->map($request, $address);
        $this->payoneRequestCustomerMapper->map($request, $customer);
    }
}

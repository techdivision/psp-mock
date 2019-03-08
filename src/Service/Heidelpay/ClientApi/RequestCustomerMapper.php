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
use TechDivision\PspMock\Entity\Customer;
use TechDivision\PspMock\Entity\Heidelpay\Order;
use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;
use TechDivision\PspMock\Service\Interfaces\PspRequestToEntityMapperInterface;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class RequestCustomerMapper implements PspRequestToEntityMapperInterface
{
    /**
     * @param Request $request
     * @param PspEntityInterface $customer
     */
    public function map(Request $request, PspEntityInterface $customer): void
    {
        /** @var Customer $customer */
        $customer->setFirstName($request->get(Order::NAME . 'GIVEN'));
        $customer->setLastname($request->get(Order::NAME . 'FAMILY'));
    }
}

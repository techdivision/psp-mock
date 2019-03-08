<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone\ServerApi;

use Symfony\Component\HttpFoundation\Request;
use TechDivision\PspMock\Entity\Address;
use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;
use TechDivision\PspMock\Service\Interfaces\PspRequestToEntityMapperInterface;

/**
 * @copyright  Copyright (c) 2018 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class RequestAddressMapper implements PspRequestToEntityMapperInterface
{
    /**
     * @param Request $request
     * @param PspEntityInterface $address
     */
    public function map(Request $request, PspEntityInterface $address): void
    {
        /** @var Address $address */
        $address->setStreet((string)$request->get('street'));
        $address->setZip((string)$request->get('zip'));
        $address->setCity((string)$request->get('city'));
        $address->setCountry((string)$request->get('country'));
    }
}

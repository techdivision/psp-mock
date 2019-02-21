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
use TechDivision\PspMock\Entity\Account;
use TechDivision\PspMock\Entity\Address;
use TechDivision\PspMock\Entity\Heidelpay\Order;
use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;
use TechDivision\PspMock\Service\Interfaces\PspRequestToEntityMapperInterface;
use TechDivision\PspMock\Service\TransactionIdProvider;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class RequestAddressMapper implements PspRequestToEntityMapperInterface
{
    /**
     * @var TransactionIdProvider
     */
    private $transactionIdProvider;

    /**
     * @param TransactionIdProvider $transactionIdProvider
     */
    public function __construct(TransactionIdProvider $transactionIdProvider)
    {
        $this->transactionIdProvider = $transactionIdProvider;
    }

    /**
     * @param Request $request
     * @param PspEntityInterface $address
     */
    public function map(Request $request, PspEntityInterface $address): void
    {
        /** @var Address $address */
        $address->setCity((string)$request->get(Order::ADDRESS . 'CITY'));
        $address->setCountry((string)$request->get(Order::ADDRESS . 'COUNTRY'));
        $address->setStreet((string)$request->get(Order::ADDRESS . 'STREET'));
        $address->setZip((string)$request->get(Order::ADDRESS . 'ZIP'));
    }
}

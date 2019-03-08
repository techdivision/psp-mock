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
use TechDivision\PspMock\Entity\Account;
use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;
use TechDivision\PspMock\Service\Interfaces\PspRequestToEntityMapperInterface;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class AccountRequestMapper implements PspRequestToEntityMapperInterface
{
    /**
     * @param Request $request
     * @param PspEntityInterface $account
     */
    public function map(Request $request, PspEntityInterface $account): void
    {
        /** @var Account $account */
        $requestArray = json_decode($request->getContent(), true);
        $account->setBrand($requestArray["account.brand"]);
        $account->setExpiryMonth($requestArray['account.expiry_month']);
        $account->setExpiryYear($requestArray['account.expiry_year']);
        $account->setHolder($requestArray['account.holder']);
        $account->setNumber($requestArray['account.number']);
        $account->setVerification($requestArray['account.verification']);
    }
}

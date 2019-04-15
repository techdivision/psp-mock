<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use TechDivision\PspMock\Entity\Interfaces\PspOrderInterface;

/**
 * @ORM\Entity(repositoryClass="TechDivision\PspMock\Repository\Payone\OrderRepository")
 * @ORM\Table(name="order")
 *
 * @copyright  Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class Order implements PspOrderInterface
{
    const STATUS_NEW = 'NEW';
    const STATUS_APPOINTED = 'APPOINTED';
    const STATUS_UNDERPAID = 'UNDERPAID';
    const STATUS_COMPLETE = 'COMPLETE';
    const STATUS_REFUNDED = 'REFUNDED';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\SequenceGenerator(sequenceName="id", initialValue=250000)
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $transactionId;

    /**
     * @var Customer
     *
     * One Order has One Account.
     * @ORM\ManyToOne(targetEntity="TechDivision\PspMock\Entity\Customer", cascade={"persist"})
     */
    private $customer;

    /**
     * @var address
     *
     * One Order has One Address.
     * @ORM\ManyToOne(targetEntity="TechDivision\PspMock\Entity\Address", cascade={"persist"})
     */
    private $address;

    /**
     * @var account
     *
     * One Order has One Account.
     * @ORM\ManyToOne(targetEntity="TechDivision\PspMock\Entity\Account", cascade={"persist"}, nullable=true)
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $requestType;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $clearingType;

    /**
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $currency;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $requestData;

    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default":"NEW"})
     * @Assert\NotBlank
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     */
    public function setTransactionId(string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return address
     */
    public function getAddress(): address
    {
        return $this->address;
    }

    /**
     * @param address $address
     */
    public function setAddress(address $address): void
    {
        $this->address = $address;
    }

    /**
     * @return account
     */
    public function getAccount(): account
    {
        return $this->account;
    }

    /**
     * @param account $account
     */
    public function setAccount(account $account): void
    {
        $this->account = $account;
    }

    /**
     * @return string
     */
    public function getRequestType(): string
    {
        return $this->requestType;
    }

    /**
     * @param string $requestType
     */
    public function setRequestType(string $requestType): void
    {
        $this->requestType = $requestType;
    }

    /**
     * @return string
     */
    public function getClearingType(): string
    {
        return $this->clearingType;
    }

    /**
     * @param string $clearingType
     */
    public function setClearingType(string $clearingType): void
    {
        $this->clearingType = $clearingType;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getRequestData(): string
    {
        return $this->requestData;
    }

    /**
     * @param string $requestData
     */
    public function setRequestData(string $requestData): void
    {
        $this->requestData = $requestData;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created): void
    {
        $this->created = $created;
    }
}

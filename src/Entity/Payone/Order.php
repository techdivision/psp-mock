<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Entity\Payone;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use TechDivision\PspMock\Entity\Address;
use TechDivision\PspMock\Entity\Customer;
use TechDivision\PspMock\Entity\Interfaces\PspOrderInterface;
use TechDivision\PspMock\Service\StatusManager;

/**
 * @ORM\Entity(repositoryClass="TechDivision\PspMock\Repository\Payone\OrderRepository")
 * @ORM\Table(name="payone_order")
 *
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Entity
 * @copyright  Copyright (c) 2018 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
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
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $reference;

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
     * @ORM\Column(type="string", nullable=true)
     */
    private $successUrl;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $errorUrl;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $backUrl;

    /**
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $balance;

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
     * @var integer
     *
     * @ORM\Column(options={"default":0})
     * @Assert\NotBlank
     */
    private $sequence;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @var StatusManager
     */
    private $statusManager;

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
     * @return string
     */
    public function getLastName()
    {
        return ($this->customer != null) ? $this->customer->getLastName() : 'n/a';
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return ($this->customer) ? $this->customer->getFirstName() : 'n/a';
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address): void
    {
        $this->address = $address;
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
    public function getBackUrl(): string
    {
        return $this->backUrl;
    }

    /**
     * @param string $backUrl
     */
    public function setBackUrl(string $backUrl): void
    {
        $this->backUrl = $backUrl;
    }

    /**
     * @return string
     */
    public function getErrorUrl(): string
    {
        return $this->errorUrl;
    }

    /**
     * @param string $errorUrl
     */
    public function setErrorUrl(string $errorUrl): void
    {
        $this->errorUrl = $errorUrl;
    }

    /**
     * @return string
     */
    public function getSuccessUrl(): string
    {
        return $this->successUrl;
    }

    /**
     * @param string $successUrl
     */
    public function setSuccessUrl(string $successUrl): void
    {
        $this->successUrl = $successUrl;
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
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

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
    public function getTotal(): string
    {
        $amount = number_format($this->getAmount() / 100, 2);
        return $amount . ' ' . $this->getCurrency();
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

    /**
     * @return StatusManager
     */
    public function getStatusManager(): StatusManager
    {
        if (!isset($this->statusManager)) {
            $this->statusManager = new StatusManager();
            $this->statusManager->setOrder($this);
        }
        return $this->statusManager;
    }

    /**
     * @return string
     */
    public function getBalance(): string
    {
        return $this->balance;
    }

    /**
     * @param string $balance
     */
    public function setBalance(string $balance): void
    {
        $this->balance = $balance;
    }

    /**
     * @return int
     */
    public function getSequence(): int
    {
        return $this->sequence;
    }

    /**
     * @param int $sequence
     */
    public function setSequence(int $sequence): void
    {
        $this->sequence = $sequence;
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
}

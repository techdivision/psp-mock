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
use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;

/**
 * @ORM\Entity(repositoryClass="TechDivision\PspMock\Repository\AccountRepository")
 * @ORM\Table(name="payment_account")
 *
 * @copyright  Copyright (c) 2018 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class Account implements PspEntityInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\SequenceGenerator(sequenceName="id")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $expiryMonth;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $expiryYear;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $verification;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $holder;

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
    public function getExpiryMonth(): string
    {
        return $this->expiryMonth;
    }

    /**
     * @param string $expiryMonth
     */
    public function setExpiryMonth(string $expiryMonth): void
    {
        $this->expiryMonth = $expiryMonth;
    }

    /**
     * @return string
     */
    public function getExpiryYear(): string
    {
        return $this->expiryYear;
    }

    /**
     * @param string $expiryYear
     */
    public function setExpiryYear(string $expiryYear): void
    {
        $this->expiryYear = $expiryYear;
    }

    /**
     * @return string
     */
    public function getVerification(): string
    {
        return $this->verification;
    }

    /**
     * @param string $verification
     */
    public function setVerification(string $verification): void
    {
        $this->verification = $verification;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     */
    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    /**
     * @return string
     */
    public function getHolder(): string
    {
        return $this->holder;
    }

    /**
     * @param string $holder
     */
    public function setHolder(string $holder): void
    {
        $this->holder = $holder;
    }
}

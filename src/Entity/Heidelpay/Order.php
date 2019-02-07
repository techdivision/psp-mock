<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Entity;

/**
 * @ORM\Entity(repositoryClass="TechDivision\PspMock\Repository\OrderRepository")
 * @ORM\Table(name="heidelpay_order")
 *
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class HeidelpayOrder
{
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
    private $store;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $sdkName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $brands;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $frontendEnabled;

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
    private $mode;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $ip;
    
}

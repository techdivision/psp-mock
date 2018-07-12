<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Entity\Creditcard;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="TechDivision\PspMock\Repository\Creditcard\WhitelistRepository")
 * @ORM\Table(name="creditcard_whitelist")
 *
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Entity
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class Whitelist
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $cardtype;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $cardpan;

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
    public function getCardtype(): string
    {
        return $this->cardtype;
    }

    /**
     * @param string $cardtype
     */
    public function setCardtype(string $cardtype): void
    {
        $this->cardtype = $cardtype;
    }

    /**
     * @return string
     */
    public function getCardpan(): string
    {
        return $this->cardpan;
    }

    /**
     * @param string $cardpan
     */
    public function setCardpan(string $cardpan): void
    {
        $this->cardpan = $cardpan;
    }
}

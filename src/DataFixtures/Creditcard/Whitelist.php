<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\DataFixtures\Creditcard;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use TechDivision\PspMock\Entity\Creditcard\Whitelist as WhitelistedCard;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage DataFixtures
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class Whitelist extends Fixture
{
    /**
     * @var array
     */
    private $defaultCards = [
        [
            'type' => 'V',
            'pan' => '4012001037141112',
        ],
        [
            'type' => 'V',
            'pan' => '4111111111111111',
        ],
        [
            'type' => 'V',
            'pan' => '4111131010111111',
        ],
        [
            'type' => 'V',
            'pan' => '4111121011111111',
        ],
        [
            'type' => 'M',
            'pan' => '5453010000080200',
        ],
        [
            'type' => 'M',
            'pan' => '5500000000000004',
        ],
    ];

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->defaultCards as $data) {
            $card = new WhitelistedCard();
            $card->setCardtype($data['type']);
            $card->setCardpan($data['pan']);
            $manager->persist($card);
        }

        $manager->flush();
    }
}

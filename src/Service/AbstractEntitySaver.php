<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
abstract class AbstractEntitySaver
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * EntitySaver constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param $input
     */
    public abstract function save($input);
}

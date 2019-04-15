<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone\ServerApi;


use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;

/**
 * @copyright  Copyright (c) 2018 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
interface CallbackExecutorInterface
{
    /**
     * @param PspEntityInterface $entity
     * @param string $action
     */
   public function execute(PspEntityInterface $entity, string $action):void;

}

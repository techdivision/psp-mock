<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class EntitySaver extends AbstractEntitySaver
{
    /**
     * @param $input
     */
    public function save($input)
    {
        is_array($input) ? $this->persistArray($input) : $this->persistSingle($input);
    }

    /**
     * @param $inputArray
     */
    private function persistArray($inputArray)
    {
        foreach ($inputArray as $inputObject) {
            $this->objectManager->persist($inputObject);
        }
        $this->objectManager->flush();
    }

    /**
     * @param $inputObject
     */
    private function persistSingle($inputObject)
    {
        $this->objectManager->persist($inputObject);
        $this->objectManager->flush();
    }
}

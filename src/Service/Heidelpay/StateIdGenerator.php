<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Heidelpay;


/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class StateIdGenerator
{
    const RANDOM_STRING_LENGTH = 24;

    /**
     * Returns a random String with the specified length
     *
     * @return bool|string
     */
    public function get(){
        return substr(md5(uniqid(rand(), true)), 0, self::RANDOM_STRING_LENGTH);
    }
}

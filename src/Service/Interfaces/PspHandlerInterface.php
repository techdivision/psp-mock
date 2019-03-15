<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use TechDivision\PspMock\Entity\Interfaces\PspOrderInterface;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
interface PspHandlerInterface
{
    /**
     * @param Request $request
     * @return PspOrderInterface
     */
    public function handle(Request $request): PspOrderInterface;
}

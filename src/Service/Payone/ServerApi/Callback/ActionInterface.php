<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone\ServerApi\Callback;

use TechDivision\PspMock\Entity\Payone\Order;
use TechDivision\PspMock\Service\Payone\ServerApi\Callback\Action\ResultInterface;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Service
 * @copyright  Copyright (c) 2018 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
interface ActionInterface
{
    /**
     * @param Order $order
     * @return ResultInterface
     */
    public function apply(Order $order): ResultInterface;
}

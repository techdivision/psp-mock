<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone\ServerApi\Callback;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Service
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class ActionFactory
{
    /**
     * @var array
     */
    private $actions;

    /**
     * @param ActionInterface[] $actions
     * @throws \Exception
     */
    public function __construct(
        array $actions
    ) {
        foreach ($actions as $action) {
            if (!($action instanceof ActionInterface)) {
                throw new \Exception(sprintf(
                    'Processors need to be instance of %s',
                    ActionInterface::class
                ));
            }
        }
        $this->actions = $actions;
    }

    /**
     * @param string $key
     * @return ActionInterface
     * @throws \Exception
     */
    public function create(string $key): ActionInterface
    {
        if (isset($this->actions[$key])) {
            return $this->actions[$key];
        }

        throw new \Exception(sprintf('There is no action for key "%s" defined', $key));
    }
}
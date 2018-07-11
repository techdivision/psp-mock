<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone\ClientApi\Request;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Service
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class ProcessorFactory
{
    /**
     * @var array
     */
    private $processors;

    /**
     * @param ProcessorInterface[] $processors
     * @throws ProcessorFactoryException
     */
    public function __construct(
        array $processors
    ) {
        foreach ($processors as $processor) {
            if (!($processor instanceof ProcessorInterface)) {
                throw new ProcessorFactoryException(sprintf(
                    'Processors need to be instance of %s',
                    ProcessorInterface::class
                ));
            }
        }
        $this->processors = $processors;
    }

    /**
     * @param string $key
     * @return ProcessorInterface
     * @throws ProcessorFactoryException
     */
    public function create($key): ProcessorInterface
    {
        if (isset($this->processors[$key])) {
            return $this->processors[$key];
        }

        throw new ProcessorFactoryException(sprintf('There is no processor for "%s" defined', $key));
    }
}
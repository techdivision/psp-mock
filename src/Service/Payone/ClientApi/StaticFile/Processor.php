<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone\ClientApi\StaticFile;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Service
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class Processor implements ProcessorInterface
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @param OutputInterface $response
     */
    public function __construct(
        OutputInterface $output
    ) {
        $this->output = $output;
    }

    /**
     * @param InputInterface $request
     * @return OutputInterface
     */
    public function execute(InputInterface $request): OutputInterface
    {
        $body = file_get_contents($request->getUri());
        $this->output->setContent($body);
        return $this->output;
    }
}
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
 * @copyright  Copyright (c) 2018 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class Output implements OutputInterface
{
    /**
     * @var string
     */
    private $content = '';

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $body
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }
}

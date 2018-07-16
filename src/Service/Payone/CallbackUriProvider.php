<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use TechDivision\PspMock\Entity\Order;
use TechDivision\PspMock\Service\Payone\ServerApi\Callback\ActionFactory;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Service
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class CallbackUriProvider
{
    /**
     * @var string
     */
    private $callbackUri = 'https://test-psp-mock.test/payone/transactionstatus';

    /**
     * @param string $callbackUri
     */
    public function __construct(string $callbackUri)
    {
        $this->callbackUri = $callbackUri;
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->callbackUri;
    }
}
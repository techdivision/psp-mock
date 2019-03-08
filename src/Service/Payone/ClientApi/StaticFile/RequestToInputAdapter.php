<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone\ClientApi\StaticFile;

use Symfony\Component\HttpFoundation\Request;
use TechDivision\PspMock\Service\DomainProvider;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Service
 * @copyright  Copyright (c) 2018 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class RequestToInputAdapter
{
    /**
     * @var InputInterface
     */
    private $secureDataRequest;

    /**
     * @var DomainProvider
     */
    private $domainProvider;

    /**
     * @param InputInterface $secureDataRequest
     * @param DomainProvider $domainProvider
     */
    public function __construct(
        InputInterface $secureDataRequest,
        DomainProvider $domainProvider
    ) {
        $this->secureDataRequest = $secureDataRequest;
        $this->domainProvider = $domainProvider;
    }

    /**
     * @inheritdoc
     */
    public function convert(Request $request): InputInterface
    {
        $uri = $request->getUri();
        $uri = str_replace(
            sprintf('%s/payone/client-api', $this->domainProvider->get()),
            'secure.pay1.de/client-api',
            $uri
        );
        $this->secureDataRequest->setUri($uri);

        return $this->secureDataRequest;
    }
}

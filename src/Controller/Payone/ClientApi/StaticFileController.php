<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Controller\Payone\ClientApi;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TechDivision\PspMock\Interfaces\Controller\PspRequestControllerInterface;
use TechDivision\PspMock\Service\Payone\ClientApi\StaticFile\ProcessorInterface;
use TechDivision\PspMock\Service\Payone\ClientApi\StaticFile\RequestToInputAdapter;
use TechDivision\PspMock\Service\Payone\ClientApi\StaticFile\OutputToResponseAdapter;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Controller
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class StaticFileController extends AbstractController implements PspRequestControllerInterface
{
    /**
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RequestToInputAdapter
     */
    private $requestToInputAdapter;

    /**
     * @var OutputToResponseAdapter
     */
    private $outputToResponseAdapter;

    /**
     * @param RequestToInputAdapter $requestToInputAdapter
     * @param OutputToResponseAdapter $outputToResponseAdapter
     * @param ProcessorInterface $processor
     * @param LoggerInterface $logger
     */
    public function __construct(
        RequestToInputAdapter $requestToInputAdapter,
        OutputToResponseAdapter $outputToResponseAdapter,
        ProcessorInterface $processor,
        LoggerInterface $logger
    ) {
        $this->requestToInputAdapter = $requestToInputAdapter;
        $this->outputToResponseAdapter = $outputToResponseAdapter;
        $this->processor = $processor;
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request)
    {
        $apiRequest = $this->requestToInputAdapter->convert($request);
        $apiResponse = $this->processor->execute($apiRequest);
        return $this->outputToResponseAdapter->convert($apiResponse);
    }
}

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
use TechDivision\PspMock\Service\Payone\ClientApi\Request\OutputToResponseAdapter;
use TechDivision\PspMock\Service\Payone\ClientApi\Request\ProcessorFactory;
use TechDivision\PspMock\Service\Payone\ClientApi\Request\ProcessorFactoryException;
use TechDivision\PspMock\Service\Payone\ClientApi\Request\RequestToInputAdapter;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Controller
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class RequestController extends AbstractController implements PspRequestControllerInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ProcessorFactory
     */
    private $processorFactory;

    /**
     * @var RequestToInputAdapter
     */
    private $requestToInputAdapter;

    /**
     * @var OutputToResponseAdapter
     */
    private $outputToResponseAdapter;

    /**
     * @param LoggerInterface $logger
     * @param ProcessorFactory $processorFactory
     * @param RequestToInputAdapter $requestToInputAdapter
     * @param OutputToResponseAdapter $outputToResponseAdapter
     */
    public function __construct(
        LoggerInterface $logger,
        ProcessorFactory $processorFactory,
        RequestToInputAdapter $requestToInputAdapter,
        OutputToResponseAdapter $outputToResponseAdapter
    ) {
        $this->logger = $logger;
        $this->processorFactory = $processorFactory;
        $this->requestToInputAdapter = $requestToInputAdapter;
        $this->outputToResponseAdapter = $outputToResponseAdapter;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request)
    {
        try {
            $processor = $this->processorFactory->create((string)$request->get('request'));
            $input = $this->requestToInputAdapter->convert($request);
            $output = $processor->execute($input);
            return $this->outputToResponseAdapter->convert($output);
        } catch (ProcessorFactoryException $exception) {
            $this->logger->error($exception);
            return new Response($exception->getMessage(), Response::HTTP_BAD_GATEWAY);
        }
    }
}

<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Controller\Help;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use TechDivision\PspMock\Controller\Interfaces\PspAbstractController;
use TechDivision\PspMock\Controller\Interfaces\PspGuiIndexControllerInterface;
use TechDivision\PspMock\Service\DomainProvider;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Controller
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class IndexController extends PspAbstractController implements PspGuiIndexControllerInterface
{
    /**
     * @var DomainProvider
     */
    private $domainProvider;

    /**
     * @param DomainProvider $domainProvider
     * @param LoggerInterface $logger
     */
    public function __construct(
        DomainProvider $domainProvider,
        LoggerInterface $logger
    ) {
        parent::__construct($logger);
        $this->domainProvider = $domainProvider;
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        try {
            return $this->render('help/index.html.twig', ['settings' => [
                'domain' => $this->domainProvider->get(),
            ]]);
        } catch (\Exception $exception) {
            $this->logger->error($exception);
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}

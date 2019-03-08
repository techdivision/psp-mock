<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Controller\Gui;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use TechDivision\PspMock\Controller\Interfaces\PspAbstractController;
use TechDivision\PspMock\Controller\Interfaces\PspGuiIndexControllerInterface;
use TechDivision\PspMock\Service\ConfigProvider;

/**
 * Renders all registered Psps into the navbar
 *
 * @copyright  Copyright (c) 2018 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class IndexPspController extends PspAbstractController implements PspGuiIndexControllerInterface
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var array
     */
    private $options = [];

    public function __construct(LoggerInterface $logger, ConfigProvider $configProvider)
    {
        parent::__construct($logger);
        $this->configProvider = $configProvider;
        $this->options['asObjects'] = false;
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        try {
            $psps = explode(',', $this->configProvider->get($this->options)['general/registered_psps']);

            return $this->render('navbar.html.twig', [
                'psps' => $psps
            ]);
        } catch (\Exception $exception) {
            $this->logger->error($exception);
        }
    }
}

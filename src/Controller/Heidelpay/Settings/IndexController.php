<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Controller\Heidelpay\Settings;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use TechDivision\PspMock\Controller\Interfaces\PspAbstractController;
use TechDivision\PspMock\Controller\Interfaces\PspGuiIndexControllerInterface;
use TechDivision\PspMock\Service\ConfigProvider;

/**
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class IndexController extends PspAbstractController implements PspGuiIndexControllerInterface
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var array
     */
    private $options = [];

    /**
     * ConfigController constructor.
     * @param ConfigProvider $configProvider
     * @param LoggerInterface $logger
     */
    public function __construct(ConfigProvider $configProvider, LoggerInterface $logger)
    {
        parent::__construct($logger);
        $this->configProvider = $configProvider;

        $this->options['asObjects'] = true;
    }

    /**
     * @return Response
     */
    public function index()
    {
        try {
            $configArray = $this->configProvider->get($this->options);

            return $this->render('settings/heidelpay/index.html.twig', ['configArray' => $configArray]);

        } catch (\Exception $exception) {
            $this->logger->error($exception);
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}

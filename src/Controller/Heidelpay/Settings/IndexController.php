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
use TechDivision\PspMock\Repository\ConfigurationRepository;

/**
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class IndexController extends PspAbstractController implements PspGuiIndexControllerInterface
{
    /**
     * @var ConfigurationRepository
     */
    private $configurationRepository;

    /**
     * ConfigController constructor.
     * @param ConfigurationRepository $configurationRepository
     * @param LoggerInterface $logger
     */
    public function __construct(ConfigurationRepository $configurationRepository, LoggerInterface $logger)
    {
        parent::__construct($logger);
        $this->configurationRepository = $configurationRepository;
    }

    /**
     * @return Response
     */
    public function index()
    {
        try {
            $configArray = [
                'failOnPreauth' => $this->configurationRepository->findOneBy(array('path' => 'fail_on_preauth')),
                'failOnIframe' => $this->configurationRepository->findOneBy(array('path' => 'fail_on_iframe')),
                'failOnCapture' => $this->configurationRepository->findOneBy(array('path' => 'fail_on_capture')),
                'failOnRefund' => $this->configurationRepository->findOneBy(array('path' => 'fail_on_refund')),
            ];

            return $this->render('settings/heidelpay/index.html.twig', ['configArray' => $configArray]);

        } catch (\Exception $exception) {
            $this->logger->error($exception);
        }
    }
}

<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Controller\Heidelpay\Settings;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use TechDivision\PspMock\Entity\Configuration;
use TechDivision\PspMock\Repository\ConfigurationRepository;

/**
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class IndexController extends AbstractController
{
    /**
     * @var ConfigurationRepository
     */
    private $configurationRepository;

    /**
     * ConfigController constructor.
     * @param ConfigurationRepository $configurationRepository
     */
    public function __construct(ConfigurationRepository $configurationRepository)
    {
        $this->configurationRepository = $configurationRepository;
    }

    /**
     * @return Response
     */
    public function index()
    {
        /**
         * @var Configuration
         */
        $failOnPreauth = $this->configurationRepository->findOneBy(array('path' => 'fail_on_preauth'));

        /**
         * @var Configuration
         */
        $failOnCapture = $this->configurationRepository->findOneBy(array('path' => 'fail_on_capture'));

        /**
         * @var Configuration
         */
        $failOnRefund = $this->configurationRepository->findOneBy(array('path' => 'fail_on_refund'));

        return $this->render('settings/heidelpay/index.html.twig', [
                'failOnPreauth' => $failOnPreauth->getValue(),
                'failOnCapture' => $failOnCapture->getValue(),
                'failOnRefund' => $failOnRefund->getValue(),
            ]
        );
    }
}

<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Controller\Heidelpay\Settings;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use TechDivision\PspMock\Entity\Configuration;
use TechDivision\PspMock\Repository\ConfigurationRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class ConfigController extends AbstractController
{
    //TODO Setupscript needed with this query:
    // INSERT INTO core_config (path, value) VALUES ('fail_on_preauth', '0'), ('fail_on_capture', '0'), ('fail_on_refund', '0')

    /**
     * @var ConfigurationRepository
     */
    private $configurationRepository;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * ConfigController constructor.
     * @param ConfigurationRepository $configurationRepository
     * @param ObjectManager $objectManager
     */
    public function __construct(ConfigurationRepository $configurationRepository, ObjectManager $objectManager)
    {
        $this->configurationRepository = $configurationRepository;
        $this->objectManager = $objectManager;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request)
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

        if ($request->getContent() !== "") {
            if ($request->get('failOnPreauth') === 'on') {
                $failOnPreauth->setValue('1');
                $this->objectManager->persist($failOnPreauth);
            } else {
                $failOnPreauth->setValue('0');
                $this->objectManager->persist($failOnPreauth);
            }

            if ($request->get('failOnCapture') === 'on') {
                $failOnCapture->setValue('1');
                $this->objectManager->persist($failOnCapture);
            } else {
                $failOnCapture->setValue('0');
                $this->objectManager->persist($failOnCapture);
            }

            if ($request->get('failOnRefund') === 'on') {
                $failOnRefund->setValue('1');
                $this->objectManager->persist($failOnRefund);
            } else {
                $failOnRefund->setValue('0');
                $this->objectManager->persist($failOnRefund);
            }

            $this->objectManager->flush();
        }

        return $this->render('settings/heidelpay/index.html.twig', [
                'failOnPreauth' => $failOnPreauth->getValue(),
                'failOnCapture' => $failOnCapture->getValue(),
                'failOnRefund' => $failOnRefund->getValue(),
            ]
        );
    }
}

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
use Symfony\Component\HttpFoundation\Request;
use TechDivision\PspMock\Controller\Interfaces\PspAbstractController;
use TechDivision\PspMock\Controller\Interfaces\PspRequestControllerInterface;
use TechDivision\PspMock\Repository\ConfigurationRepository;
use Symfony\Component\HttpFoundation\Response;
use TechDivision\PspMock\Service\EntitySaver;

/**
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class ConfigController extends PspAbstractController implements PspRequestControllerInterface
{
    //TODO Setupscript needed with this query:
    // INSERT INTO core_config (path, value) VALUES ('fail_on_preauth', '0'), ('fail_on_capture', '0'), ('fail_on_refund', '0'), ('fail_on_iframe', '0')

    /**
     * @var ConfigurationRepository
     */
    private $configurationRepository;

    /**
     * @var EntitySaver
     */
    private $entitySaver;

    /**
     * ConfigController constructor.
     * @param ConfigurationRepository $configurationRepository
     * @param EntitySaver $entitySaver
     * @param LoggerInterface $logger
     */
    public function __construct(
        ConfigurationRepository $configurationRepository,
        EntitySaver $entitySaver,
        LoggerInterface $logger
    ) {
        parent::__construct($logger);
        $this->configurationRepository = $configurationRepository;
        $this->entitySaver = $entitySaver;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request)
    {
        try {
            $configArray = [
                'failOnPreauth' => $this->configurationRepository->findOneBy(array('path' => 'fail_on_preauth')),
                'failOnIframe' => $this->configurationRepository->findOneBy(array('path' => 'fail_on_iframe')),
                'failOnCapture' => $this->configurationRepository->findOneBy(array('path' => 'fail_on_capture')),
                'failOnRefund' => $this->configurationRepository->findOneBy(array('path' => 'fail_on_refund')),
            ];

            foreach ($configArray as $key => $value) {
                ($request->get($key) === 'on')
                    ? $configArray[$key]->setValue('1')
                    : $configArray[$key]->setValue('0');
            }
            $this->entitySaver->save($configArray);
            return $this->render('settings/heidelpay/index.html.twig', ['configArray' => $configArray]);
        } catch (\Exception $exception) {
            $this->logger->error($exception);
        }
    }
}

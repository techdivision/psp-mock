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
use Symfony\Component\HttpFoundation\Response;
use TechDivision\PspMock\Entity\Configuration;
use TechDivision\PspMock\Service\ConfigProvider;
use TechDivision\PspMock\Service\EntitySaver;

/**
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class ConfigController extends PspAbstractController implements PspRequestControllerInterface
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var EntitySaver
     */
    private $entitySaver;

    /**
     * @var array
     */
    private $options = [];

    /**
     * ConfigController constructor.
     * @param ConfigProvider $configProvider
     * @param EntitySaver $entitySaver
     * @param LoggerInterface $logger
     */
    public function __construct(
        ConfigProvider $configProvider,
        EntitySaver $entitySaver,
        LoggerInterface $logger
    ) {
        parent::__construct($logger);
        $this->configProvider = $configProvider;
        $this->entitySaver = $entitySaver;

        $this->options['asObjects'] = true;
        $this->options['namespace'] = 'heidelpay/%';
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request)
    {
        try {
            $configArray = $this->configProvider->get($this->options);

            foreach ($configArray as $key => $value) {
                /** @var Configuration $value */
                ($request->get($value->getPath()) === 'on')
                    ? $configArray[$key]->setValue('1')
                    : $configArray[$key]->setValue('0');
            }
            $this->entitySaver->save($configArray);
            return $this->render('settings/heidelpay/index.html.twig', ['configArray' => $this->configProvider->get($this->options)]);
        } catch (\Exception $exception) {
            $this->logger->error($exception);
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}

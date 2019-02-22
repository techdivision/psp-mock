<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Heidelpay\ClientApi\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use TechDivision\PspMock\Repository\Heidelpay\OrderRepository;
use TechDivision\PspMock\Service\ConfigProvider;
use TechDivision\PspMock\Service\EntitySaver;
use TechDivision\PspMock\Service\Heidelpay\ClientApi\AckProvider;
use TechDivision\PspMock\Service\Heidelpay\ClientApi\NokProvider;
use TechDivision\PspMock\Service\Interfaces\PspHandlerInterface;
use TechDivision\PspMock\Service\RandomStringProvider;
use TechDivision\PspMock\Service\RequestMapper;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
abstract class AbstractHandler implements PspHandlerInterface
{
    /**
     * @var EntitySaver
     */
    protected $entitySaver;

    /**
     * @var AckProvider
     */
    protected $ackProvider;

    /**
     * @var NokProvider
     */
    protected $nokProvider;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var RequestMapper
     */
    protected $requestMapper;

    /**
     * @var RandomStringProvider
     */
    protected $stateIdGenerator;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var ConfigProvider
     */
    protected $configProvider;

    /**
     * @var string
     */
    protected $failOnPreauth;

    /**
     * @var string
     */
    protected $failOnCapture;

    /**
     * @var string
     */
    protected $failOnRefund;

    /**
     * @param ObjectManager $objectManager
     * @param RequestMapper $requestMapper
     * @param RandomStringProvider $stateIdGenerator
     * @param OrderRepository $orderRepository
     * @param ConfigProvider $configProvider
     * @param EntitySaver $entitySaver
     * @param AckProvider $ackProvider
     * @param NokProvider $nokProvider
     */
    public function __construct(
        ObjectManager $objectManager,
        RequestMapper $requestMapper,
        RandomStringProvider $stateIdGenerator,
        OrderRepository $orderRepository,
        ConfigProvider $configProvider,
        EntitySaver $entitySaver,
        AckProvider $ackProvider,
        NokProvider $nokProvider
    ) {
        $this->objectManager = $objectManager;
        $this->requestMapper = $requestMapper;
        $this->stateIdGenerator = $stateIdGenerator;
        $this->orderRepository = $orderRepository;
        $this->configProvider = $configProvider;
        $this->entitySaver = $entitySaver;
        $this->ackProvider = $ackProvider;
        $this->nokProvider = $nokProvider;

        $this->loadSettings();
    }

    /**
     * Loads the system settings for Heidelpay requests
     */
    private function loadSettings()
    {
        $this->failOnPreauth = $this->configProvider->get()['heidelpay/fail_on_preauth'];
        $this->failOnCapture = $this->configProvider->get()['heidelpay/fail_on_capture'];
        $this->failOnRefund = $this->configProvider->get()['heidelpay/fail_on_refund'];
    }
}

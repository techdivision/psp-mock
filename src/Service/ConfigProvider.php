<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service;


use TechDivision\PspMock\Repository\ConfigurationRepository;
use TechDivision\PspMock\Service\Interfaces\PspDataProviderInterface;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class ConfigProvider implements PspDataProviderInterface
{
    /**
     * @var ConfigurationRepository
     */
    private $configurationRepository;

    /**
     * ConfigProvider constructor.
     * @param ConfigurationRepository $configurationRepository
     */
    public function __construct(ConfigurationRepository $configurationRepository)
    {
        $this->configurationRepository = $configurationRepository;
    }

    /**
     * Info: if namespace isset return specific result by namespace
     *
     * @param array $options
     * @return array
     */
    public function get(array $options): array
    {
        if (array_key_exists('namespace', $options)) {
            $settings = $this->configurationRepository->findAllConfigurationsByWildcard($options['namespace']);
            return ($options['asObjects'] == true) ? $settings : $this->convert($settings);
        } else {
            $settings = $this->configurationRepository->findAll();
            return ($options['asObjects'] == true) ? $settings : $this->convert($settings);
        }
    }

    /**
     * Converts an Array of Configuration objects to an array with this format:
     *
     * [path => value]
     *
     * @param array $settings
     * @return array
     */
    private function convert(array $settings): array
    {
        $convertedSettings = [];

        foreach ($settings as $setting) {
            $convertedSettings[$setting->getPath()] = $setting->getValue();
        }
        return $convertedSettings;
    }
}

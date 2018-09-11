<?php

declare(strict_types = 1);

namespace App\Service\Plugin;

use App\Exception\PluginConfigNotFoundException;
use Psr\Container\ContainerInterface;

class PluginConfigLoader implements PluginConfigLoaderInterface {
	protected const PLUGIN_CONFIGS_NAME = 'plugins';

	/**
	 * @var array
	 */
	protected $applicationConfig;

	/**
	 * @var string
	 */
	protected $pluginPath;

	/**
	 * @var string
	 */
	protected $pluginName;

	/**
	 * @var PluginConfigValidatorInterface|null
	 */
	protected $pluginConfigValidator;

	/**
	 * @param string $pluginPath
	 * @param string $pluginName
	 * @param ContainerInterface $container
	 */
	public function __construct(string $pluginPath, string $pluginName, ContainerInterface $container) {
		$this->applicationConfig = $container->config;
		$this->pluginPath        = $pluginPath;
		$this->pluginName        = $pluginName;
	}

	/**
	 * @param PluginConfigValidatorInterface|null $pluginConfigValidator
	 *
	 * @return void
	 */
	public function setPluginConfigValidator(?PluginConfigValidatorInterface $pluginConfigValidator): void {
		$this->pluginConfigValidator = $pluginConfigValidator;
	}

	/**
	 * @inheritDoc
	 */
	public function loadPluginConfig(): array {
		$pluginConfig = $this->getDefaultPluginConfig();

		if ($this->isPluginConfigAvailable()) {
			$pluginConfig = array_merge_recursive($pluginConfig, $this->getPluginConfig());
		}

		if ($this->pluginConfigValidator !== null) {
			$this->pluginConfigValidator->validate($pluginConfig);
		}

		return $pluginConfig;
	}

	/**
	 * @return array
	 */
	protected function getPluginConfigs(): array {
		return $this->applicationConfig[self::PLUGIN_CONFIGS_NAME];
	}

	/**
	 * @return array
	 */
	protected function getPluginConfig(): array {
		return $this->getPluginConfigs()[$this->pluginName];
	}

	/**
	 * @return array
	 */
	protected function getDefaultPluginConfig(): array {
		$defaultConfigPath = $this->pluginPath . '/config/plugin.config.php';

		if (!file_exists($defaultConfigPath)) {
			throw new PluginConfigNotFoundException(
				sprintf('Could not find config for plugin "%s"', $this->pluginName)
			);
		}

		$defaultConfig = require $defaultConfigPath;

		return $defaultConfig;
	}

	/**
	 * @return bool
	 */
	protected function isPluginConfigAvailable(): bool {
		return array_key_exists($this->pluginName, $this->getPluginConfigs());
	}
}

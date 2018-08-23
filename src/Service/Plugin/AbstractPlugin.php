<?php

declare(strict_types = 1);

namespace App\Service\Plugin;

use App\Service\ConfigLoader\ConfigLoader;
use Psr\Container\ContainerInterface;
use Slim\App;

abstract class AbstractPlugin implements PluginInterface {
	/**
	 * @inheritDoc
	 */
	abstract public function load(ContainerInterface $container): void;

	/**
	 * @inheritDoc
	 */
	abstract public function registerMiddlewares(App $app, ContainerInterface $container): void;

	/**
	 * @inheritDoc
	 */
	abstract public function registerRoutes(App $app, ContainerInterface $container): void;

	/**
	 * @param string $pluginPath
	 * @param ContainerInterface $container
	 *
	 * @return void
	 */
	protected function loadConfiguration(string $pluginPath, ContainerInterface $container): void {
		$configFilePath = $pluginPath . '/config/config.php';

		$configLoader = new ConfigLoader();
		$configLoader->registerConfig($configFilePath);
		$configLoader->loadConfigs($container);
	}
}

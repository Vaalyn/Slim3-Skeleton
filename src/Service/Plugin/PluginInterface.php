<?php

declare(strict_types = 1);

namespace App\Service\Plugin;

use Psr\Container\ContainerInterface;
use Slim\App;

interface PluginInterface {
	/**
	 * Initialize the plugin here
	 *
	 * @param ContainerInterface $container
	 *
	 * @return void
	 */
	public function load(ContainerInterface $container): void;

	/**
	 * Register all middlewares here
	 *
	 * @param App $app
	 * @param ContainerInterface $container
	 *
	 * @return void
	 */
	public function registerMiddlewares(App $app, ContainerInterface $container): void;

	/**
	 * Register all routes here
	 *
	 * @param App $app
	 *
	 * @param ContainerInterface $container
	 */
	public function registerRoutes(App $app, ContainerInterface $container): void;
}

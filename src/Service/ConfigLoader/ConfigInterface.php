<?php

declare(strict_types = 1);

namespace App\Service\ConfigLoader;

interface ConfigInterface {
	/**
	 * @return array
	 */
	public function getConfig(): array;

	/**
	 * @param array $config
	 *
	 * @return ConfigInterface
	 */
	public function setConfig(array $config): ConfigInterface;
}

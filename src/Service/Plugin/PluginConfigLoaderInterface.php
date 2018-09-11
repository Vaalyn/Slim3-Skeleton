<?php

declare(strict_types = 1);

namespace App\Service\Plugin;

use App\Exception\PluginConfigNotFoundException;

interface PluginConfigLoaderInterface {
	/**
	 * @return array
	 *
	 * @throws PluginConfigNotFoundException
	 */
	public function loadPluginConfig(): array;
}

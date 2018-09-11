<?php

declare(strict_types = 1);

namespace App\Service\Plugin;

use App\Exception\PluginConfigValidationException;

interface PluginConfigValidatorInterface {
	/**
	 * @param array $config
	 *
	 * @return void
	 *
	 * @throws PluginConfigValidationException
	 */
	public function validate(array $config): void;
}

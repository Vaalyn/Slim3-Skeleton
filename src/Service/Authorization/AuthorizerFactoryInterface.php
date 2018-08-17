<?php

declare(strict_types = 1);

namespace App\Service\Authorization;

use App\Service\Authorization\Authorizer\AuthorizerInterface;

interface AuthorizerFactoryInterface {
	/**
	 * @param string $authorizerType
	 *
	 * @return AuthorizerInterface
	 */
	public function create(string $authorizerType): AuthorizerInterface;
}

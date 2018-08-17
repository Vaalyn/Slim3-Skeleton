<?php

declare(strict_types = 1);

namespace App\Service\Authorization;

use App\Model\User;

interface AuthorizationInterface {
	/**
	 * @param string $routeName
	 *
	 * @return bool
	 */
	public function needsAuthorizationForRoute(string $routeName): bool;

	/**
	 * @param User $user
	 * @param string $routeName
	 *
	 * @return bool
	 */
	public function hasAuthorizationForRoute(User $user, string $routeName): bool;
}

<?php

declare(strict_types = 1);

namespace App\Service\Authorization\Authorizer;

use App\Model\User;

interface AuthorizerInterface {
	/**
	 * @param User $user
	 *
	 * @return bool
	 */
	public function isAuthorized(User $user): bool;
}

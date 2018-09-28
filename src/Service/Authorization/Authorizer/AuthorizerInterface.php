<?php

declare(strict_types = 1);

namespace App\Service\Authorization\Authorizer;

use App\Model\User;
use Slim\Http\Request;

interface AuthorizerInterface {
	/**
	 * @param User|null $user
	 * @param Request|null $request
	 *
	 * @return bool
	 */
	public function isAuthorized(?User $user, ?Request $request): bool;
}

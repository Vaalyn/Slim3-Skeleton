<?php

declare(strict_types = 1);

namespace App\Service\Authorization\Authorizer;

use App\Model\User;
use Slim\Http\Request;

class AdminAuthorizer implements AuthorizerInterface {
	/**
	 * @inheritDoc
	 */
	public function isAuthorized(?User $user, ?Request $request): bool {
		if ($user->is_admin) {
			return true;
		}

		return false;
	}
}

<?php

declare(strict_types = 1);

namespace App\Service\Authorization\Authorizer;

use App\Model\User;

class AdminAuthorizer implements AuthorizerInterface {
	/**
	 * @inheritDoc
	 */
	public function isAuthorized(User $user): bool {
		if ($user->is_admin) {
			return true;
		}

		return false;
	}
}

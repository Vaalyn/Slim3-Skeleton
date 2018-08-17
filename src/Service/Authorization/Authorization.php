<?php

declare(strict_types = 1);

namespace App\Service\Authorization;

use App\Model\User;
use Psr\Container\ContainerInterface;

class Authorization implements AuthorizationInterface {
	/**
	 * @var array
	 */
	protected $routesWithAuthorization;

	/**
	 * @var AuthorizerFactory
	 */
	protected $authorizerFactory;

	/**
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container) {
		$this->routesWithAuthorization = $container->config['authorization']['routes'];
		$this->authorizerFactory       = new AuthorizerFactory($container->config['authorization']['authorizers']);
	}

	/**
	 * @inheritDoc
	 */
	public function needsAuthorizationForRoute(string $routeName): bool {
		return array_key_exists($routeName, $this->routesWithAuthorization);
	}

	/**
	 * @inheritDoc
	 */
	public function hasAuthorizationForRoute(User $user, string $routeName): bool {
		if (!$this->needsAuthorizationForRoute($routeName)) {
			return true;
		}

		foreach ($this->routesWithAuthorization[$routeName] as $authorizerType) {
			$authorizer = $this->authorizerFactory->create($authorizerType);
		}

		return $authorizer->isAuthorized($user);
	}
}
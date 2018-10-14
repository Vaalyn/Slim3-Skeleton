<?php

declare(strict_types = 1);

namespace App\Routes\Frontend;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Interfaces\RouterInterface;
use Vaalyn\AuthenticationService\AuthenticationInterface;

class LogoutController {
	/**
	 * @var AuthenticationInterface
	 */
	protected $authentication;

	/**
	 * @var RouterInterface
	 */
	protected $router;

	/**
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container) {
		$this->authentication = $container->authentication;
		$this->router         = $container->router;
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 *
	 * @return Response
	 */
	public function __invoke(Request $request, Response $response, array $args): Response {
		$this->authentication->logout();

		return $response->withRedirect($this->router->pathFor('login'));
	}
}

<?php

declare(strict_types = 1);

namespace App\Routes\Frontend;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;
use Vaalyn\AuthenticationService\AuthenticationInterface;

class DashboardController {
	/**
	 * @var AuthenticationInterface
	 */
	protected $authentication;

	/**
	 * @var PhpRenderer
	 */
	protected $renderer;

	/**
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container) {
		$this->authentication = $container->authentication;
		$this->renderer       = $container->renderer;
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 *
	 * @return Response
	 */
	public function __invoke(Request $request, Response $response, array $args): Response {
		return $this->renderer->render($response, '/dashboard/dashboard.php', [
			'authentication' => $this->authentication,
			'request' => $request
		]);
	}
}

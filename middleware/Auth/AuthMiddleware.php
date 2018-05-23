<?php

namespace Middleware\Auth;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddleware {
	/**
	 * @var \Psr\Container\ContainerInterface
	 */
	private $container;

	/**
	 * @param \Psr\Container\ContainerInterface $container
	 *
	 * @return void
	 */
	public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

	/**
	 * @param \Slim\Http\Request  $request
	 * @param \Slim\Http\Response $response
	 * @param callable $next
	 *
	 * @return Response
	 */
	public function __invoke(Request $request, Response $response, callable $next): Response {
		$currentRoute = $request->getAttribute('route');

		if ($currentRoute !== null) {
			$currentRouteName = $currentRoute->getName();
			$protectedRoutes  = $this->container->config['auth']['routes'];
			$localRoutes      = $this->container->config['auth']['local'];

			if (!empty($currentRoute) && (in_array($currentRouteName, $protectedRoutes) || array_key_exists($currentRouteName, $protectedRoutes))) {
				if (!$this->container->auth->check()) {
					return $response->withRedirect($this->container->router->pathFor('login'));
				}

				if (isset($protectedRoutes[$currentRouteName]) && in_array('admin', $protectedRoutes[$currentRouteName]) && !$this->container->auth->isAdmin()) {
					return $response->withStatus(400)
								->withHeader('Content-Type', 'application/json')
								->write(json_encode(array(
									'status' => 'error',
									'message' => 'the requested resource is not available for you'
								)));
				}
			}

			if (!empty($currentRoute) && in_array($currentRoute->getName(), $localRoutes)) {
				if ($request->getAttribute('ip_address') != $this->container->config['host']['ip']) {
					return $response->withStatus(400)
								->withHeader('Content-Type', 'application/json')
								->write(json_encode(array(
									'status' => 'error',
									'message' => 'the requested resource is not available for you'
								)));
				}
			}
		}

		$response = $next($request, $response);
		return $response;
	}
}

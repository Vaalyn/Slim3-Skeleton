<?php

namespace App\Service\NotFoundHandler;

use Closure;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class NotFoundHandler {
	/**
	 * @var ContainerInterface
	 */
	protected $container;

	/**
	 * @param ContainerInterface $container
	 *
	 * @return Closure
	 */
	public function __invoke(ContainerInterface $container): Closure {
		$this->container = $container;

		return function(Request $request, Response $response) {
			return $this->createNotFoundResponse($request, $response);
		};
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 *
	 * @return Response
	 */
	private function createNotFoundResponse(Request $request, Response $response): Response {
		$message = sprintf(
			'Die Seite "%s" wurde nicht gefunden',
			$request->getUri()->getPath()
		);

		return $response
			->withStatus(404)
			->withHeader('Content-Type', 'text/html')
			->write($message);
	}
}
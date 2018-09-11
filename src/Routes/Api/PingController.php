<?php

namespace App\Routes\Api;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class PingController {
	/**
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container) {
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 *
	 * @return Response
	 */
	public function __invoke(Request $request, Response $response, array $args): Response {
		$response = $response->withStatus(200)->withHeader('Content-Type', 'application/json');

		return $response->write(json_encode(array(
			'status' => 'success',
			'message' => 'Pong'
		)));
	}
}

<?php

declare(strict_types = 1);

namespace App\Service\ErrorHandler;

use Closure;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ErrorHandler {
	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @param LoggerInterface $logger
	 */
	public function __construct(LoggerInterface $logger) {
		$this->logger = $logger;
	}

	/**
	 * @param ContainerInterface $container
	 *
	 * @return Closure
	 */
	public function __invoke(ContainerInterface $container): Closure {
		$this->settings = $container->settings;

		return function(Request $request, Response $response, \Throwable $error) {
			return $this->createErrorResponse($response, $error);
		};
	}

	/**
	 * @param Response $response
	 * @param \Throwable $error
	 *
	 * @return Response
	 */
	protected function createErrorResponse(Response $response, \Throwable $error): Response {
		$message = 'Beim Verarbeiten der Anfrage ist ein Fehler aufgetreten.';

		if ($this->settings['displayErrorDetails']) {
			$message .= '<pre>' . $error . '</pre>';
		}

		$this->logger->error(
			$error->getMessage(),
			$error->getTrace()
		);

		return $response
			->withStatus(500)
			->withHeader('Content-Type', 'text/html')
			->write($message);
	}
}

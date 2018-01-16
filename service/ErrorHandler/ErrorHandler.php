<?php
	namespace Service\ErrorHandler;

	use Psr\Container\ContainerInterface;

	class ErrorHandler {

		/**
		 * @var ContainerInterface
		 */
		protected $container;

		public function __invoke(ContainerInterface $container) {
			$this->container = $container;

			return function($request, $response, $error) {
				return $this->createErrorResponse($response, $error);
			};
		}

		private function createErrorResponse($response, $error) {
			$message = 'Beim Verarbeiten der Anfrage ist ein Fehler aufgetreten.';

			if ($this->container->settings['displayErrorDetails']) {
				$message .= '<pre>' . $error . '</pre>';
			}

			return $response
				->withStatus(500)
				->withHeader('Content-Type', 'text/html')
				->write($message);
		}
	}

?>

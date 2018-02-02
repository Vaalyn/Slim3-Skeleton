<?php
	namespace Routes\Api;

	use Psr\Container\ContainerInterface;
	use Slim\Http\Request;
	use Slim\Http\Response;

	class PingController {
		/**
		 * @var \Psr\Container\ContainerInterface
		 */
		protected $container;

		/**
		 * @param \Psr\Container\ContainerInterface $container
		 */
		public function __construct(ContainerInterface $container) {
			$this->container = $container;
		}

		/**
		 * @param \Slim\Http\Request $request
		 * @param \Slim\Http\Response $response
		 * @param array $args
		 *
		 * @return void
		 */
		public function __invoke(Request $request, Response $response, array $args) {
			$response = $response->withStatus(200)->withHeader('Content-Type', 'application/json');

			return $response->write(json_encode(array(
				'status' => 'success',
				'message' => 'Pong'
			)));
		}
	}
?>

<?php
	namespace Routes\Api;

	use Psr\Container\ContainerInterface;
	use Slim\Http\Request;
	use Slim\Http\Response;

	class PingController {
		/**
		 * @var ContainerInterface
		 */
		protected $container;

		/**
		 * @param ContainerInterface $container
		 */
		public function __construct(ContainerInterface $container) {
			$this->container = $container;
		}

		/**
		 * @param  Request $request
		 * @param  Response $response
		 * @param  array $args
		 *
		 * @return void
		 */
		public function __invoke($request, $response, $args) {
			$response = $response->withStatus(200)->withHeader('Content-Type', 'application/json');

			return $response->write(json_encode(array(
				'status' => 'success',
				'message' => 'Pong'
			)));
		}
	}
?>

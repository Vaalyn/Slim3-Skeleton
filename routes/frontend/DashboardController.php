<?php
	namespace Routes\Frontend;

	use Psr\Container\ContainerInterface;
	use Slim\Http\Request;
	use Slim\Http\Response;

	class DashboardController {

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
			return $this->container->renderer->render($response, '/dashboard/dashboard.php', [
				'request' => $request,
				'response' => $response,
				'database' => $this->container->database,
				'auth' => $this->container->auth,
				'flashMessages' => $this->container->flash->getMessages()
			]);
		}
	}
?>

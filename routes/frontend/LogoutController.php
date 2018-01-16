<?php
	namespace Routes\Frontend;

	use Psr\Container\ContainerInterface;
	use Slim\Http\Request;
	use Slim\Http\Response;

	class LogoutController {

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
			$this->container->auth->logout();
			return $response->withRedirect($this->container->router->pathFor('login'));
		}
	}
?>

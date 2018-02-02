<?php
	namespace Routes\Frontend;

	use Psr\Container\ContainerInterface;
	use Slim\Http\Request;
	use Slim\Http\Response;

	class LoginController {

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
		public function getLoginAction(Request $request, Response $response, array $args) {
			if ($this->container->auth->check()) {
				if (count($request->getHeader('HTTP_REFERER'))) {
					return $response->withRedirect($request->getHeader('HTTP_REFERER')[0]);
				}

				return $response->withRedirect($this->container->router->pathFor('dashboard'));
			}

			return $this->container->renderer->render($response, '/login/login.php', [
				'request' => $request,
				'response' => $response,
				'database' => $this->container->database,
				'auth' => $this->container->auth,
				'flashMessages' => $this->container->flash->getMessages()
			]);
		}

		/**
		 * @param \Slim\Http\Request $request
		 * @param \Slim\Http\Response $response
		 * @param array $args
		 *
		 * @return void
		 */
		public function loginAction(Request $request, Response $response, array $args) {
			$username = $request->getParsedBody()['username'] ?? null;
			$password = $request->getParsedBody()['password'] ?? null;
			$referer = $request->getParsedBody()['referer'] ?? null;

			if (!$this->container->auth->attempt($username, $password)) {
				$this->container->flash->addMessage('Login error', 'Username or password incorrect');
				return $response->withRedirect($this->container->router->pathFor('login'));
			}

			if ($referer !== null && substr_compare($referer, 'login', -5) !== 0) {
				return $response->withRedirect($referer);
			}

			return $response->withRedirect($this->container->router->pathFor('dashboard'));
		}
	}
?>

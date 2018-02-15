<?php
	namespace Service\Auth;

	use Carbon\Carbon;
	use Model\AuthToken;
	use Model\User;
	use Psr\Container\ContainerInterface;
	use Ramsey\Uuid\Uuid;

	class Auth implements AuthInterface {
		/**
		 * @var \Psr\Container\ContainerInterface
		 */
		private $container;

		/**
		 * @param \Psr\Container\ContainerInterface $container
		 */
		public function __construct(ContainerInterface $container) {
			$this->container = $container;
		}

		/**
		 * @return null|\Model\User
		 */
		public function user(): User {
			return $user = User::where('user_id', '=', $_SESSION['user_id'])->first();
		}

		/**
		 * @return bool
		 */
		public function check(): bool {
			if (!isset($_SESSION['user_id'])) {
				$this->checkLoginCookie();
			}

			if (!User::where('user_id', '=', $_SESSION['user_id'])->exists()) {
				$this->logout();
			}

			return isset($_SESSION['user_id']);
		}

		/**
		 * @return bool
		 */
		public function isAdmin(): bool {
			if ($this->user()->is_admin === 1) {
				return true;
			}

			return false;
		}

		/**
		 * @param string $username
		 * @param string $password
		 * @param bool $rememberMe
		 *
		 * @return bool
		 */
		public function attempt(string $username, string $password, bool $rememberMe = false): bool {
			$user = User::where('username', '=', $username)->first();

			if (!isset($user)) {
				return false;
			}

			if (password_verify($password, $user->password)) {
				$_SESSION['user_id'] = $user->user_id;

				if ($rememberMe) {
					$this->setLoginCookie($user);
				}

				return true;
			}

			return false;
		}

		/**
		 * @param \Model\User $user
		 *
		 * @return void
		 */
		private function setLoginCookie(User $user): void {
			setcookie(
				$this->container->config['auth']['cookie']['name'],
				json_encode([
					'username' => $user->username,
					'token' => $this->generateLoginCookieToken($user)
				]),
				time() + $this->container->config['auth']['cookie']['expire'],
				'/',
				$this->container->config['auth']['cookie']['domain'],
				$this->container->config['auth']['cookie']['secure'],
				$this->container->config['auth']['cookie']['httponly']
			);
		}

		/**
		 * @param \Model\User $user
		 *
		 * @return string
		 */
		private function generateLoginCookieToken(User $user): string {
			$token = bin2hex(random_bytes(16));

			$authToken                = new AuthToken();
			$authToken->auth_token_id = Uuid::uuid4()->toString();
			$authToken->user_id       = $user->user_id;
			$authToken->token         = password_hash($token, PASSWORD_DEFAULT);
			$authToken->save();

			$_SESSION['auth_token_id'] = $authToken->auth_token_id;

			return $token;
		}

		/**
		 * @return void
		 */
		public function invalidateAuthTokens(): void {
			$invalidationDateTime = new Carbon();
			$invalidationDateTime->subSeconds($this->container->config['auth']['cookie']['expire']);

			$authTokens = AuthToken::where('created_at', '<', $invalidationDateTime->toDateTimeString())->get();

			foreach ($authTokens as $authToken) {
				$this->invalidateAuthToken($authToken);
			}
		}

		/**
		 * @param \Model\AuthToken $authToken
		 *
		 * @return void
		 */
		public function invalidateAuthToken(AuthToken $authToken): void {
			$authToken->delete();
		}

		/**
		 * @return void
		 */
		private function checkLoginCookie(): void {
			$this->invalidateAuthTokens();

			if (isset($_COOKIE[$this->container->config['auth']['cookie']['name']])) {
				$cookie     = json_decode($_COOKIE[$this->container->config['auth']['cookie']['name']]);
				$authTokens = User::where('username', '=', $cookie->username)->first()->authTokens;

				foreach ($authTokens as $authToken) {
					if (password_verify($cookie->token, $authToken->token)) {
						$_SESSION['user_id'] = $authToken->user->user_id;
						$_SESSION['auth_token_id'] = $authToken->auth_token_id;

						break;
					}
				}
			}
		}

		/**
		 * @return void
		 */
		public function logout(): void {
			unset($_SESSION['user_id']);
			unset($_SESSION['auth_token_id']);
			unset($_COOKIE[$this->container->config['auth']['cookie']['name']]);
			setcookie(
				$this->container->config['auth']['cookie']['name'],
				'',
				time() - 3600,
				'/',
				$this->container->config['auth']['cookie']['domain'],
				$this->container->config['auth']['cookie']['secure'],
				$this->container->config['auth']['cookie']['httponly']
			);
		}
	}
?>

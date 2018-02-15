<?php
	namespace Service\Auth;

	use Model\User;
	use Psr\Container\ContainerInterface;

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
			return $user = User::where('username', '=', $_SESSION['username'])->first();
		}

		/**
		 * @return bool
		 */
		public function check(): bool {
			if (!isset($_SESSION['username'])) {
				$this->checkLoginCookie();
			}

			if (!User::where('username', '=', $_SESSION['username'])->exists()) {
				$this->logout();
			}

			return isset($_SESSION['username']);
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
				$_SESSION['username'] = $user->username;

				if ($rememberMe) {
					$this->setLoginCookie($user->username, password_hash($user->username . $user->password, PASSWORD_DEFAULT));
				}

				return true;
			}

			return false;
		}

		/**
		 * @param string $username
		 * @param string $identificationHash
		 *
		 * @return void
		 */
		private function setLoginCookie(string $username, string $identificationHash): void {
			setcookie(
				$this->container->get('config')['auth']['cookie']['name'],
				json_encode(['username' => $username, 'identificationHash' => $identificationHash]),
				time() + $this->container->get('config')['auth']['cookie']['expire'],
				'/',
				$this->container->get('config')['auth']['cookie']['domain'],
				$this->container->get('config')['auth']['cookie']['secure'],
				$this->container->get('config')['auth']['cookie']['httponly']
			);
		}

		/**
		 * @return void
		 */
		private function checkLoginCookie(): void {
			if (isset($_COOKIE[$this->container->get('config')['auth']['cookie']['name']])) {
				$cookie = json_decode($_COOKIE[$this->container->get('config')['auth']['cookie']['name']]);
				$user   = User::where('username', '=', $cookie->username)->first();

				if (isset($user->password)) {
					if (password_verify($user->username . $user->password, $cookie->identificationHash)) {
						$_SESSION['username'] = $user->username;
					}
				}
			}
		}

		/**
		 * @return void
		 */
		public function logout(): void {
			unset($_SESSION['username']);
			unset($_COOKIE[$this->container->get('config')['auth']['cookie']['name']]);
			setcookie(
				$this->container->get('config')['auth']['cookie']['name'],
				'',
				time() - 3600,
				'/',
				$this->container->get('config')['auth']['cookie']['domain'],
				$this->container->get('config')['auth']['cookie']['secure'],
				$this->container->get('config')['auth']['cookie']['httponly']
			);
		}
	}
?>

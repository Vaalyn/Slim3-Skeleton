<?php
	namespace Service\Auth;

	class Auth {
		private $container;

		public function __construct($container) {
			$this->container = $container;
		}

		public function user() {
			return $this->container->database->table('user')->where('username', $_SESSION['username'])->first();
		}

		public function check() {
			if (!isset($_SESSION['username'])) {
				$this->checkLoginCookie();
			}

			return isset($_SESSION['username']);
		}

		public function isAdmin() {
			if ($this->user()->is_admin === 1) {
				return true;
			}

			return false;
		}

		public function attempt($username, $password) {
			$user = $this->container->database->table('user')->where('username', $username)->first();

			if (empty($user)) {
				return false;
			}

			if (password_verify($password, $user->password)) {
				$_SESSION['username'] = $user->username;
				$this->setLoginCookie($user->username, password_hash($user->username . $user->password, PASSWORD_DEFAULT));
				return true;
			}

			return false;
		}

		private function setLoginCookie($username, $identificationHash) {
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

		private function checkLoginCookie() {
			if (isset($_COOKIE[$this->container->get('config')['auth']['cookie']['name']])) {
				$cookie = json_decode($_COOKIE[$this->container->get('config')['auth']['cookie']['name']]);
				$user   = $this->container->database->table('user')->where('username', $cookie->username)->first();

				if (isset($user->password)) {
					if (password_verify($user->username . $user->password, $cookie->identificationHash)) {
						$_SESSION['username'] = $user->username;
					}
				}
			}
		}

		public function logout() {
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

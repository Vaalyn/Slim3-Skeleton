<?php

namespace App\Service\Authentication;

use App\Model\AuthToken;
use App\Model\User;
use App\Service\Authorization\AuthorizationInterface;
use App\Service\Session\Session;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;

class Authentication implements AuthenticationInterface {
	/**
	 * @var Session
	 */
	protected $session;

	/**
	 * @var AuthorizationInterface
	 */
	protected $authorization;

	/**
	 * @var array
	 */
	protected $cookieConfig;

	/**
	 * @var string[]
	 */
	protected $routesWithAuthentication;

	/**
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container) {
		$this->session                  = $container->session;
		$this->authorization            = $container->authorization;
		$this->cookieConfig             = $container->config['authentication']['cookie'];
		$this->routesWithAuthentication = $container->config['authentication']['routes'];
	}

	/**
	 * @inheritDoc
	 */
	public function user(): ?User {
		return User::find($this->session->get('user_id'));
	}

	/**
	 * @inheritDoc
	 */
	public function check(): bool {
		if (!$this->session->exists('user_id')) {
			$this->checkLoginCookie();
		}

		if (!User::where('user_id', '=', $this->session->get('user_id'))->exists()) {
			$this->logout();
		}

		return $this->session->exists('user_id');
	}

	/**
	 * @inheritDoc
	 */
	public function isAdmin(): bool {
		if ($this->user()->is_admin === 1) {
			return true;
		}

		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function attempt(string $username, string $password, bool $rememberMe = false): bool {
		$user = User::where('username', '=', $username)->first();

		if (!isset($user)) {
			return false;
		}

		if (password_verify($password, $user->password)) {
			$this->session->set('user_id', $user->user_id);

			if ($rememberMe) {
				$this->setLoginCookie($user);
			}

			return true;
		}

		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function invalidateAuthTokens(): void {
		$invalidationDateTime = new Carbon();
		$invalidationDateTime->subSeconds($this->cookieConfig['expire']);

		$authTokens = AuthToken::where('created_at', '<', $invalidationDateTime->toDateTimeString())->get();

		foreach ($authTokens as $authToken) {
			$this->invalidateAuthToken($authToken);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function invalidateAuthToken(AuthToken $authToken): void {
		$authToken->delete();
	}

	/**
	 * @inheritDoc
	 */
	public function logout(): void {
		unset($_COOKIE[$this->cookieConfig['name']]);
		setcookie(
			$this->cookieConfig['name'],
			'',
			time() - 3600,
			'/',
			$this->cookieConfig['domain'],
			$this->cookieConfig['secure'],
			$this->cookieConfig['httponly']
		);

		$this->session->destroy();
	}

	/**
	 * @inheritDoc
	 */
	public function routeNeedsAuthentication(string $routeName): bool {
		$needsAuthentication = in_array($routeName, $this->routesWithAuthentication);

		if ($needsAuthentication) {
			return true;
		}

		return $this->authorization->needsAuthorizationForRoute($routeName);
	}

	/**
	 * @param User $user
	 *
	 * @return void
	 */
	protected function setLoginCookie(User $user): void {
		setcookie(
			$this->cookieConfig['name'],
			json_encode([
				'username' => $user->username,
				'token' => $this->generateLoginCookieToken($user)
			]),
			time() + $this->cookieConfig['expire'],
			'/',
			$this->cookieConfig['domain'],
			$this->cookieConfig['secure'],
			$this->cookieConfig['httponly']
		);
	}

	/**
	 * @param User $user
	 *
	 * @return string
	 */
	protected function generateLoginCookieToken(User $user): string {
		$token = bin2hex(random_bytes(16));

		$browserParser = new Agent();

		$browser = sprintf(
			"Browser: %s\nVersion: %s\nOS: %s\nVersion: %s\nGerät: %s\n",
			$browserParser->browser() ?? '',
			$browserParser->version($browserParser->browser()) ?? '',
			$browserParser->platform() ?? '',
			$browserParser->version($browserParser->platform()) ?? '',
			$browserParser->device() ?? ''
		);

		$authToken                = new AuthToken();
		$authToken->auth_token_id = Uuid::uuid4()->toString();
		$authToken->user_id       = $user->user_id;
		$authToken->token         = password_hash($token, PASSWORD_DEFAULT);
		$authToken->browser       = $browser;
		$authToken->save();

		$this->session->set('auth_token_id', $authToken->auth_token_id);

		return $token;
	}

	/**
	 * @return void
	 */
	protected function checkLoginCookie(): void {
		$this->invalidateAuthTokens();

		if (isset($_COOKIE[$this->cookieConfig['name']])) {
			$cookie     = json_decode($_COOKIE[$this->cookieConfig['name']]);
			$authTokens = User::where('username', '=', $cookie->username)->first()->authTokens;

			foreach ($authTokens as $authToken) {
				if (password_verify($cookie->token, $authToken->token)) {
					$this->session
						->set('user_id', $authToken->user->user_id)
						->set('auth_token_id', $authToken->auth_token_id);

					break;
				}
			}
		}
	}
}

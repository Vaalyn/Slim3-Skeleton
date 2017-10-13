<?php
	$app->get('/login', function ($request, $response, $args) {

	})->setName('login');

	$app->post('/login', function ($request, $response, $args) {
		$username = $request->getParsedBody()['username'];
		$password = $request->getParsedBody()['password'];

		if (!$this->auth->attempt($username, $password)) {
			$this->flash->addMessage('Login fehlgeschlagen', 'Der Username oder das Password sind nicht korrekt');
			return $response->withRedirect($this->router->pathFor('login'));
		}

		return $response->withRedirect($this->router->pathFor('dashboard'));
	});
?>

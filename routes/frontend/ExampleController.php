<?php
	$app->get('/example', function ($request, $response, $args) {
		return $this->renderer->render($response, "/example/example.php", [
			'request' => $request,
			'response' => $response,
			'database' => $this->database
		]);
	});
?>
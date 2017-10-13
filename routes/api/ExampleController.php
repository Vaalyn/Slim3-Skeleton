<?php
	$app->get('/api/ping', function ($request, $response, $args) {
		return $response->withStatus(200)
				        ->withHeader('Content-Type', 'application/json')
				        ->write(json_encode(array(
				        	'status' => 'success',
				        	'message' => 'pong'
						)));
	});
?>

<?php

return [
	'settings' => [
		'determineRouteBeforeAppMiddleware' => true,
		'displayErrorDetails' => false
	],
	'config' => [
		'session' => [
			'name'     => 'SLIM3_SKELETON_SESSID',
			'lifetime' => 1200,
			'path'     => '/',
			'domain'   => 'localhost',
			'secure'   => false,
			'httponly' => true
		],
		'database' => [
			'driver'    => '',
			'host' 	    => '',
			'database' 	=> '',
			'username' 	=> '',
			'password' 	=> '',
			'charset'   => 'utf8mb4',
			'collation' => 'utf8mb4_unicode_ci',
			'prefix'    => '',
			'port'      => 3306
		],
		'auth' => [
			'cookie' => [
				'name'     => 'remember',
				'expire'   => 2592000,
				'domain'   => '',
				'secure'   => false,
				'httponly' => true
			],
			'routes' => require_once __DIR__ . '/routePermissions.php',
			'local' => []
		]
	]
];

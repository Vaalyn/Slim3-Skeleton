<?php

return [
	'config' => [
		'authentication' => [
			'cookie' => [
				'domain'   => '',
				'expire'   => 2592000,
				'httponly' => true,
				'name'     => 'remember',
				'secure'   => false
			],
			'host' => [
				'ip' => '127.0.0.1'
			],
			'local' => require_once __DIR__ . '/routes/local.php',
			'routes' => require_once __DIR__ . '/routes/authenticated.php'
		],
		'authorization' => [
			'authorizers' => require_once __DIR__ . '/authorizers.php',
			'routes' => require_once __DIR__ . '/routes/authorized.php'
		],
		'database' => [
			'charset'   => 'utf8mb4',
			'collation' => 'utf8mb4_unicode_ci',
			'database' 	=> '',
			'driver'    => '',
			'host' 	    => '',
			'password' 	=> '',
			'port'      => 3306,
			'prefix'    => '',
			'username' 	=> ''
		],
		'menu' => require_once __DIR__ . '/menu.php',
		'plugins' => [],
		'session' => [
			'domain'   => 'localhost',
			'httponly' => true,
			'lifetime' => 1200,
			'name'     => 'SLIM3_SKELETON_SESSID',
			'path'     => '/',
			'secure'   => false
		],
		'template' => [
			'path' => __DIR__ . '/../template'
		]
	],
	'settings' => [
		'determineRouteBeforeAppMiddleware' => true,
		'displayErrorDetails' => false
	]
];

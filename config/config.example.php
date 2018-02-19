<?php
	return array(
		'settings' => array(
			'determineRouteBeforeAppMiddleware' => true,
			'displayErrorDetails' => false
		),
		'config' => array(
			'session' => array(
				'name'     => 'SLIM3_SKELETON_SESSID',
				'lifetime' => 1200,
				'path'     => '/',
				'domain'   => 'localhost',
				'secure'   => false,
				'httponly' => true
			),
			'database' => array(
				'driver'    => '',
				'host' 	    => '',
				'database' 	=> '',
				'username' 	=> '',
				'password' 	=> '',
				'charset'   => 'utf8mb4',
				'collation' => 'utf8mb4_unicode_ci',
				'prefix'    => '',
				'port'      => 3306
			),
			'auth' => array(
				'cookie' => array(
					'name'     => 'remember',
					'expire'   => 2592000,
					'domain'   => '',
					'secure'   => false,
					'httponly' => true
				),
				'routes' => array(
					'dashboard'
				),
				'local' => array(
				)
			)
		)
	);
?>

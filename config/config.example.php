<?php
	return array(
		'settings' => array(
			'determineRouteBeforeAppMiddleware' => true,
			'displayErrorDetails' => false
		),
		'config' => array(
			'database' => array(
				'driver'    => '',
				'host' 	    => '',
				'database' 	=> '',
				'username' 	=> '',
				'password' 	=> '',
				'charset'   => 'utf8',
				'collation' => 'utf8_unicode_ci',
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

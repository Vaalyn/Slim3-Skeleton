<?php

return [
	'navbar_pages' => [
			'is_left' => true,
			'is_right' => false,
			'classes' => [
				'hide-on-med-and-down'
			],
			'menu_items' => []
	],
	'navbar_system_pages' => [
		'is_left' => false,
		'is_right' => true,
		'classes' => [],
		'menu_items' => [
			'login' => [
				'display_name' => 'Login',
				'icon' => 'lock_open',
				'url' => null,
				'route_name' => 'login',
				'classes' => [],
				'hide_when_authenticated' => true
			],
			'options' => [
				'display_name' => 'Optionen',
				'icon' => 'settings',
				'url' => '#!',
				'route_name' => null,
				'classes' => [],
				'menu_items' => [
					'logout' => [
						'display_name' => 'Logout',
						'icon' => 'lock_outline',
						'url' => null,
						'route_name' => 'logout',
						'classes' => []
					]
				]
			]
		]
	]
];

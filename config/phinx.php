<?php

$config = require_once __DIR__ . '/config.php';

return [
	'paths' => [
		'migrations' => __DIR__ . '/../phinx/migrations',
	    'seeds' => __DIR__ . '/../phinx/seeds'
	],
	'environments' => [
		'default_migration_table' => 'phinxlog',
	    'default_database' => 'production',
	    'production' => [
			'adapter' => $config['config']['database']['driver'],
	        'host' => $config['config']['database']['host'],
	        'name' => $config['config']['database']['database'],
	        'user' => $config['config']['database']['username'],
	        'pass' => $config['config']['database']['password'],
	        'port' => $config['config']['database']['port'],
	        'charset' => $config['config']['database']['charset'],
			'collation' => $config['config']['database']['collation'],
			'table_prefix' => $config['config']['database']['prefix']
		]
	],
	'version_order' => 'creation'
];

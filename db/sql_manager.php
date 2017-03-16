<?php

	class SQL_Manager {
		public $connection;
		
		function __construct($config) {
			$this->connect($config['server'], $config['username'], $config['password'], $config['database']);
		}
		
		function connect($server, $user, $pw, $db) {
			$mysqli = new mysqli($server, $user, $pw, $db);
			$mysqli->set_charset('utf8');
			
		   	if ($mysqli->connect_error) {
		   		die($mysqli->connect_error);
		   	}
		   	else {
				$this->connection = $mysqli;
		   	}
		}
		
		function disconnect() {
			$this->connection->close();
		}
	}
	
?>
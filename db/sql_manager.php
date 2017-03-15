<?php

	class SQL_Manager {
		public $connection;
		
		function __construct() {
			require __DIR__ . '/../config/db.config.php';
			$this->connect($DB_SERVER, $DB_USER, $DB_PASSWORD, $DB_DB);
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
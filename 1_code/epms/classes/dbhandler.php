<?php

	/**
	*	PDO Wrapper. Connects to database when initiated and provides helper function
	*	for running queries.
	**/

	class DBHandler{
		public $pdo;
		
		// Constructor
		public function __construct($db, $user = NULL, $pass = NULL, $host = '127.0.0.1'){
			$options = [
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES   => false,
			];
			
			$dsn = "mysql:host=$host;dbname=$db";
			
			 try {
				$this->pdo = new \PDO($dsn, $user, $pass, $options);
			} catch (\PDOException $e) {
				throw new \PDOException($e->getMessage(), (int)$e->getCode());
			}
		}
		
		// Helper function.
		public function run($sql, $args = NULL){
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute($args);
			return $stmt;
		}
	}

?>
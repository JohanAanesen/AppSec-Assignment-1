<?php

/**
 * @property PDO db
 */
class Database {

	private $hostname;
	private $username;
	private $password;
	private $database;
	private $charset;

	private $db;

	public function __construct( $hostname, $username, $password, $database, $charset = 'utf8' ) {
		$this->hostname = $hostname;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
		$this->charset = $charset;

		try {
			$dns = sprintf("mysql:host=%s;dbname=%s;charset=%s;", $this->hostname, $this->database, $this->charset);
			$this->db = new PDO($dns, $this->username, $this->password);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function getDB() {
		return $this->db;
	}

}
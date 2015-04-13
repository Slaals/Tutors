<?php

class Database {

	private static $instance = NULL;

	private function __construct() {
		
	}

	private function __clone() {
		
	}

	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new PDO('mysql:host=' . __DB_HOST . ';dbname=' . __DB_NAME, __DB_USERNAME, __DB_PASSWORD);
			self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			self::$instance->exec('SET NAMES utf8');
		}
		return self::$instance;
	}

}

?>

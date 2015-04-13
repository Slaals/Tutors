<?php

class RegisteredModel {

	private static $instance = NULL;

	private function __construct() {
		
	}

	private function __clone() {
		
	}

	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new RegisteredModel();
		}
		return self::$instance;
	}

	public static function getToken($user, $password) {
		$db = Database::getInstance();
		$query = $db->query('SELECT s.libelle as statut, p.libelle as programme FROM compte as c '
				. 'LEFT JOIN liste_statut as s ON c.id_statut = s.id '
				. 'LEFT JOIN resp_programme as resp ON resp.identifiant = c.login '
				. 'LEFT JOIN liste_programme as p ON resp.id_programme = p.id '
				. 'WHERE login="' . $user . '" AND password=MD5("' . $password . '")');
		$row = $query->fetch();
		if ($row) {
			return array('statut' => $row->statut, 'programme' => $row->programme);
		}
		return false;
	}

}

?>
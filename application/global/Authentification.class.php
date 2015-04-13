<?php

class Authentification extends BaseComponent {

	private $registry;

	public function __construct($registry) {
		$this->registry = $registry;
		session_start();
	}

	public function getSession($val) {
		if (isset($_SESSION[$val])) {
			return $_SESSION[$val];
		}
		return false;
	}

	public function signin($user, $password, $token) {
		$_SESSION['user'] = $user;
		$_SESSION['password'] = $password;
		$_SESSION['date'] = time();
		foreach ($token as $key => $val) {
			$_SESSION[$key] = $val;
		}
	}

	public function signout() {
		session_destroy();
	}

	public function goHome() {
		if (isset($_SESSION['statut'])) {
			$statut = $_SESSION['statut'];
			header('Location:' . __SITE_ROOT . $this->registry->json_data->links->$statut->home);
		} else {
			print('Erreur fatale : le statut n\'a pas été renseigné');
		}
	}

	public function isLogOn() {
		if (isset($_SESSION['user']) && isset($_SESSION['password'])) {
			return true;
		}
		return false;
	}

	public function connexionTime() {
		$time_logon = time() - $_SESSION['date'];
		return date('H:i:s', $time_logon);
	}

}

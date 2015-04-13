<?php

class Router {

	private $registry;
	private $controller_path;
	private $controller_name;
	private $action;

	function __construct($registry) {
		$this->registry = $registry;
	}

	public function route() {
		$this->getController();
		if (!is_readable($this->controller_path) || !is_callable(array($this->read($this->controller_name), $this->action))) {
			$this->call('ErrorController', 'e404');
		} else {
			$this->applyRouteWithSecurity();
		}
	}

	private function read($controller_name) {
		require_once __SITE_PATH . '/controller/' . $controller_name . '.class.php';
		$controller = new $controller_name($this->registry);
		return $controller;
	}

	private function call($controller_name, $action_name) {
		require_once __SITE_PATH . '/controller/' . $controller_name . '.class.php';
		$front_controller = new FrontController();
		$front_controller->load($this->registry);
		$controller = new $controller_name($this->registry);
		$controller->$action_name();
	}

	private function applyRouteWithSecurity() {
		$authentification = $this->registry->Authentification;
		$controller_object = $this->read($this->controller_name);

		if ($controller_object->isSecure() && !$authentification->isLogOn()) {
			$this->call('ErrorController', 'restricted');
		} else {
			$this->call($this->controller_name, $this->action);
		}
	}

	private function getController() {
		$route = (empty($_GET['route'])) ? '' : $_GET['route'];
		if (!empty($route)) {
			$parts = explode('/', $route);
			$controller = ucfirst($parts[0]);
			if (isset($parts[1])) {
				$this->action = $parts[1];
			}
		}

		if (empty($controller)) {
			$controller = 'index';
		}

		if (empty($this->action)) {
			$this->action = 'index';
		}

		$this->controller_name = ucwords($controller) . 'Controller';
		$this->controller_path = 'controller/' . ucwords($controller) . 'Controller.class.php';
	}

}

?>

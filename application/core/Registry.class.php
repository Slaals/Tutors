<?php

class Registry {

	private $vars = array();

	public function newComponent($name) {
		$name = ucwords($name) . 'Component';
		$file = __SITE_PATH . '/application/component/' . $name . '.class.php';
		if (is_readable($file) == false) {
			throw new Exception('Le composant ' . $name . ' n\'existe pas');
		} else {
			require_once $file;
			return new $name;
		}
	}

	public function newModel($name) {
		$name = ucwords($name) . 'Model';
		$file = __SITE_PATH . '/model/' . $name . '.class.php';
		if (!is_readable($name) == false) {
			throw new Exception('Le modèle ' . $name . ' n\'existe pas');
		} else {
			require_once $file;
			if (method_exists($name, 'getInstance')) {
				return $name::getInstance();
			} else {
				return new $name;
			}
		}
	}

	public function __set($index, $value) {
		$this->vars[$index] = $value;
	}

	public function __get($index) {
		return $this->vars[$index];
	}

}

?>

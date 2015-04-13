<?php

abstract class BaseController {

	protected $registry;
	protected $secure = false;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function isSecure() {
		return $this->secure;
	}

	abstract function index();
}

?>

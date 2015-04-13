<?php

abstract class BaseComponent {

	protected $vars = array();

	public function __set($index, $value) {
		$this->vars[$index] = $value;
	}

	public function __get($index) {
		return $this->vars[$index];
	}

	public function createView($template) {
		ob_start();
		foreach ($this->vars as $key => $val) {
			$$key = $val;
		}
		include(__SITE_PATH . '/view/' . $template . '.php');
		$data = ob_get_contents();
		ob_end_clean();
		return $data;
	}

}

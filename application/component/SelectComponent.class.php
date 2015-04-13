<?php

class SelectComponent extends BaseComponent {

	private $select_option = array();

	public function setName($name) {
		$this->name = $name;
	}

	public function setClass($class) {
		$this->class = $class;
	}

	public function setOption($arr = array()) {
		$this->select_option = $arr;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setEvent($event) {
		$this->event = htmlspecialchars($event);
	}

	public function setValue($value) {
		$this->value = $value;
	}

	public function createView($template = 'select_default') {
		$this->option = $this->select_option;

		return parent::createView($template);
	}

}

?>
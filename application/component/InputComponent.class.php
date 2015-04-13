<?php

class InputComponent extends BaseComponent {

	private $input_type = "text";

	public function setType($type) {
		$this->input_type = $type;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setClass($class) {
		$this->class = $class;
	}

	public function setEvent($event) {
		$this->event = $event;
	}

	public function setPlaceHolder($value) {
		$this->placeHolder = $value;
	}

	public function setValue($value) {
		$this->value = $value;
	}

	public function createView($template = 'input_default') {
		$this->type = $this->input_type;

		return parent::createView($template);
	}

}

?>

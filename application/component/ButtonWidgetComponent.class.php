<?php

class ButtonWidgetComponent extends BaseComponent {
	
	private $events_array = array();

	public function setAction($action) {
		$this->action = urlencode($action);
	}

	public function setLabel($label) {
		$this->label = $label;
	}

	public function setClass($class) {
		$this->class = $class;
	}

	public function addEvent($type, $action) {
		$this->events_array[] = $type . '="' . htmlspecialchars($action) . '"';
	}

	public function createView($template = 'widget_button_default') {
		$this->events = $this->events_array;
		return parent::createView($template);
	}

}

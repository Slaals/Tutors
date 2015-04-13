<?php

class DivWidgetComponent extends BaseComponent {

	public function setContent($html) {
		$this->html = $html;
	}

	public function setClass($class) {
		$this->class = $class;
	}

	public function createView($template = 'div_default') {
		return parent::createView($template);
	}

}

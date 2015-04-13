<?php

class MenuWidgetComponent extends BaseComponent {

	public $links_data = array();

	public function addlink($data) {
		$this->links_data[] = $data;
	}

	public function createView($template) {
		$this->links = $this->links_data;
		return parent::createView($template);
	}

}

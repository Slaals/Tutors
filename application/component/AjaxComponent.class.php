<?php

class AjaxComponent extends BaseComponent {

	public function interceptData($type = 'post') {
		if (!empty($_POST)) {
			foreach ($_POST as $key => $val) {
				$request[$key] = $val;
			}
		}
		return $request;
	}

}

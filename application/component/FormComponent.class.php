<?php

class FormComponent extends BaseComponent {

	private $fields_data = array();
	private $request = array();
	private $syntax_errors = array();
	private $common_errors = array();

	public function init($method, $action, $data = '') {
		$this->method = $method;
		$this->action = $action;
		$this->data_form = $data;
		return $this;
	}

	public function addField($label, $name, $data = array()) {
		$this->fields_data[$name]['data'] = $data;
		$this->fields_data[$name]['label'] = $label;
		$this->fields_data[$name]['name'] = $name;
		return $this;
	}

	public function addFieldRule($name, $data = array()) {
		$this->fields_data[$name]['rules'][] = $data;
		return $this;
	}

	public function getFieldValue($name) {
		return $this->request[$name];
	}

	private function addSyntaxError($field_label, $reason) {
		$this->syntax_errors[] = array('field_label' => $field_label, 'reason' => $reason);
	}

	public function addCommonError($error) {
		$this->common_errors[] = $error;
	}

	public function setFile($file) {
		$this->file = $file;
	}

	public function getFile() {
		return $this->file;
	}

	public function isValid() {
		if (!empty($_POST) || !empty($_FILES)) {
			foreach ($_POST as $key => $val) {
				$this->request[$key] = $val;
			}
			foreach ($this->fields_data as $field) {
				foreach ($field['rules'] as $rule) {
					$field_value = $this->getFieldValue($field['name']);
					if ($rule['rule_type'] == 'regex') {
						$condition = preg_match($rule['rule_value'], $field_value);
						if (!$rule['rule_bool']) {
							$condition = !$condition;
						}
						if (!$condition) {
							$this->addSyntaxError($field['label'], 'Le champ est syntaxiquement mauvais');
						}
					} elseif ($rule['rule_type'] == 'operator') {
						switch ($rule['rule_value']) {
							case 'empty':
								$condition = empty($field_value);
								if (!$rule['rule_bool']) {
									$condition = !$condition;
								}
								if (!$condition) {
									$this->addSyntaxError($field['label'], 'Le champ ne doit pas être vide');
								}
								break;
							case 'file_added':
								if ($_FILES['file']['error'] == 4) {
									$this->addSyntaxError($field['label'], 'Aucun fichier n\'a été ajouté');
								} else {
									$this->setFile($_FILES);
								}
								break;
							default:
								break;
						}
					}
				}
			}
			if (empty($this->syntax_errors)) {
				return true;
			}
		}
		return false;
	}

	public function createView($template = 'form_default') {
		$temp_fields = array();
		foreach ($this->fields_data as $key => $val) {
			$label = '<label for="' . $this->fields_data[$key]['name'] . '">' . $this->fields_data[$key]['label'] . '</label>';
			$field = '<input name="' . $this->fields_data[$key]['name'] . '"';
			foreach ($this->fields_data[$key]['data'] as $key2 => $val) {
				$field .= ' ' . $key2 . '="' . $val . '"';
			}
			$field .= ' value="' . $this->getFieldValue($this->fields_data[$key]['name']) . '"';
			array_push($temp_fields, array('label' => $label, 'field' => $field));
		}
		$this->fields = $temp_fields;
		foreach ($this->syntax_errors as $val) {
			$this->errors .= 'Erreur au niveau du champ <b>' . $val['field_label'] . '</b> -> ' . $val['reason'] . '<br/>';
		}
		foreach ($this->common_errors as $val) {
			$this->errors .= $val;
		}
		return parent::createView($template);
	}

}

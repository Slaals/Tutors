<?php

class ProgramManagerController extends BaseController {

	public function __construct($registry) {
		$this->secure = true;
		parent::__construct($registry);
	}

	public function index() {
		$this->manageHabilitation();
	}

	public function authorizeAllAcademicResearcher() {
		$program_manager = $this->registry->newModel('ProgramManager');

		$program = $this->registry->Authentification->getSession('programme');

		$this->registry->template->page_first_title = "Habiliter tous les conseillés au programme " . $program;

		$json_ajax_data = json_encode(array('program' => $program));

		$view = 'Souhaitez vous habiliter tous les enseignants chercheurs au programme ' . $program . ' ?<br/>';
		$button = $this->registry->newComponent('ButtonWidget');
		$button->setAction('ajax_send(\'' . __SITE_ROOT . '/ProgramManager/AuthorizeAllAcademicResearcherAjax/\',\'' . $json_ajax_data . '\',\'.ajax-return\');');
		$button->setLabel('Oui, je souhaite habiliter tous les enseignants au programme <b>' . $program . '</b>');
		$view .= $button->createView('widget_button_classic');

		$ajax_content = $this->registry->newComponent('DivWidget');
		$ajax_content->setClass('ajax-return');

		$view .= $ajax_content->createView();

		$this->registry->template->content = $view;
		$this->registry->template->show();
	}

	public function showStudentAndCounsellor() {
		$program_manager = $this->registry->newModel('ProgramManager');

		$data = $program_manager->getStudentAndCounsellor($this->registry->Authentification->getSession('programme'));

		$table = $this->registry->newComponent('Table');
		$table->setDataHeader(array('Prenom', 'Nom', 'Formation', 'Conseillé assigné'));
		$table->setDataRow($data);

		$table_view = $table->createView('table_manage_data');

		$this->registry->template->content = $table_view;
		$this->registry->template->show();
	}

	public function manageHabilitation() {

		$this->registry->template->page_first_title = "Gestion des habilitations des conseillers";

		$program_manager = $this->registry->newModel('ProgramManager');
		$data = $program_manager->getData();

		$table = $this->registry->newComponent('Table');
		$programme = $this->registry->Authentification->getSession('programme');
		$table->setDataHeader(array('Prenom', 'Nom', 'Bureau', 'Habilité ' . $programme));
		foreach ($data as $key => $val) {
			$button = $this->registry->newComponent('ButtonWidget');
			if (in_array($programme, $val->libelle)) {
				$json_ajax_data = json_encode(array('name' => $val->nom, 'surname' => $val->prenom, 'action' => 'remove', 'line_number' => $key));
				$button->setClass('check');
				$button->addEvent('onmouseover', 'toggleClass(\'check\',\'delete-button\',this);');
				$button->addEvent('onmouseout', 'toggleClass(\'delete-button\',\'check\',this);');
				$button->setAction('ajax_send(\'' . __SITE_ROOT . '/ProgramManager/ToggleAuthorizarionAjax/\',\'' . $json_ajax_data . '\',\'.ajax-return-' . $key . '\');');
			} else {
				$json_ajax_data = json_encode(array('name' => $val->nom, 'surname' => $val->prenom, 'action' => 'add', 'line_number' => $key));
				$button->setClass('none');
				$button->setAction('ajax_send(\'' . __SITE_ROOT . '/ProgramManager/ToggleAuthorizarionAjax/\',\'' . $json_ajax_data . '\',\'.ajax-return-' . $key . '\');');
			}
			$ajaxContent = $this->registry->newComponent('DivWidget');
			$ajaxContent->setClass('ajax-return-' . $key);
			$ajaxContent->setContent($button->createView());
			$data[$key]->libelle = $ajaxContent->createView();
		}
		$table->setDataRow($data);
		$table_view = $table->createView('table_default');

		$this->registry->template->content = $table_view;

		$this->registry->template->show();
	}

	/* Méthodes appellées via ajax */
	/*	 * **************************** */

	public function toggleAuthorizarionAjax() {
		if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
			$ajax = $this->registry->newComponent('Ajax');
			$data = $ajax->interceptData();
			if (isset($data['name']) && isset($data['surname'])) {
				$program_manager = $this->registry->newModel('ProgramManager');
				$button = $this->registry->newComponent('ButtonWidget');
				if($data['action'] == 'add'){
					$json_ajax_data = json_encode(array('name' => $data['name'], 'surname' => $data['surname'], 'action' => 'remove', 'line_number' => $data['line_number']));
					$program_manager->addAuthorization($data['name'], $data['surname'], $this->registry->Authentification->getSession('programme'));
					$button->setClass('check');
					$button->addEvent('onmouseover', 'toggleClass(\'check\',\'delete-button\',this);');
					$button->addEvent('onmouseout', 'toggleClass(\'delete-button\',\'check\',this);');
					$button->setAction('ajax_send(\'' . __SITE_ROOT . '/ProgramManager/ToggleAuthorizarionAjax/\',\'' . $json_ajax_data . '\',\'.ajax-return-' . $data['line_number'] . '\');');
				} 
				elseif($data['action'] == 'remove'){
					$json_ajax_data = json_encode(array('name' => $data['name'], 'surname' => $data['surname'], 'action' => 'add', 'line_number' => $data['line_number']));
					$program_manager->deleteAuthorization($data['name'], $data['surname'], $this->registry->Authentification->getSession('programme'));
					$button->setClass('none');
					$button->setAction('ajax_send(\'' . __SITE_ROOT . '/ProgramManager/ToggleAuthorizarionAjax/\',\'' . $json_ajax_data . '\',\'.ajax-return-' . $data['line_number'] . '\');');
				}
				echo $button->createView();
			}
		}
	}

	public function AuthorizeAllAcademicResearcherAjax() {
		if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
			$ajax = $this->registry->newComponent('Ajax');
			$data = $ajax->interceptData();
			if (isset($data['program'])) {
				$program_manager = $this->registry->newModel('ProgramManager');

				$return_data = $this->registry->newComponent('DivWidget');
				$return_data->setClass('form-successful');
				$return_data->setContent('Tous les enseignants ont été habilité pour conseiller les étudiants en ' . $data['program']);

				$program_manager->addHabilitationByProgram($data['program']);
				
				echo $return_data->createView();
;			}
		}
	}

}

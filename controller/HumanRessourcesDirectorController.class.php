<?php

class HumanRessourcesDirectorController extends BaseController {

	public function __construct($registry) {
		$this->secure = true;
		parent::__construct($registry);
	}

	public function index() {
		$this->manageAcademicResearcher();
	}

	public function manageAcademicResearcher() {
		$this->registry->template->page_first_title = 'Gestion des enseignants chercheurs';
		$human_ressources_director = $this->registry->newModel('HumanRessourcesDirector');

		$button = $this->registry->newComponent('ButtonWidget');
		$button->setClass('add-button-extend');
		$button->setAction('showHideElement(\'#table-hidden-row\')');
		$button->setLabel('Ajouter enseignant');
		$content .= $button->createView() . '<br/><br/>';

		$data = $human_ressources_director->getData();
		$this->registry->template->content = $content . $this->buildAcademicResearcherTable($data);
		$this->registry->template->show();
	}

	public function addAcademicResearchers() {
		$this->registry->template->page_first_title = 'Ajouter une liste d\'enseignants';

		$form = $this->registry->newComponent('Form');
		$form->init('post', '', 'enctype="multipart/form-data"');
		$form->addField('Fichier', 'file', array('type' => 'file', 'maxlength' => '20', 'id' => 'fichier-CSV'))
				->addFieldRule('file', array('rule_type' => 'operator', 'rule_value' => 'file_added', 'rule_bool' => false));

		if ($form->isValid()) {
			$human_ressources_director = $this->registry->newModel('HumanRessourcesDirector');
			$file = $form->getFile();

			$csv_header = array('prenom', 'nom', 'bureau', 'pole');

			if (!file_exists($file['file']['tmp_name']) || !is_readable($file['file']['tmp_name'])) {
				$form->addCommonError('Un problème a été rencontré à la lecture du fichier');
			} else {
				$header = NULL;
				$data = array();
				$error = false;
				if (($handle = fopen($file['file']['tmp_name'], 'r')) !== FALSE) {
					while (($row = fgetcsv($handle, 1000, ';')) !== FALSE) {
						foreach ($row as $key => $val) {
					       	if (!mb_detect_encoding($row[$key], 'UTF-8', true)) {
					        	$row[$key] = utf8_encode($row[$key]);
					       	}
					    }
						if (!$header) {
							if ($row == $csv_header) {
								$header = $row;
							} else {
								$form->addCommonError('Ce fichier ne correspond pas au format attendu');
								$error = true;
								break;
							}
						} else {
							if (count($header) != count($row)) {
								$form->addCommonError('Une erreur a été rencontré pendant la lectures des données, vérifiez leur formalisme et recommencez');
								$error = true;
							} else {
								$data[] = array_combine($header, $row);
							}
						}
					}
					fclose($handle);
				}

				if (!$error) {
					$affected_data = $human_ressources_director->addAcademicResearchers($data);
					$content .= '<br/>';

					$content .= count($affected_data) . ' données à ajouter :';

					$content .= '<br/>';

					foreach ($affected_data as $key => $val) {
						if($val['affected']) {
							$content .= '* Ajout de l\'enseignant <b>' . $val['prenom'] . ' ' . $val['nom'] . '</b><br/>';
						} else {
							$index = $key + 2;
							$content .= '- Erreur lors de la tentative d\'ajout de <b>' . $val['prenom'] . ' ' . $val['nom'] . '</b> : <i>ligne <b>' . $index . '</b></i><br />';
						}
					}
				}
			}
		}

		$this->registry->template->content = $form->createView() . $content;
		$this->registry->template->show();
	}

	public function showCounsellor() {
		$this->registry->template->page_first_title = 'Présentation des conseillés';
		$human_ressources_director = $this->registry->newModel('HumanRessourcesDirector');

		$data = $human_ressources_director->getCounsellor();

		$table = $this->registry->newComponent('Table');
		$table->setDataHeader(array('Nombre d\'étudiant', 'Nom', 'Bureau', 'Pole'));
		$table->setDataRow($data);

		$this->registry->template->content = $table->createView();
		$this->registry->template->show();
	}

	public function showCounsellorWithStudent() {
		$this->registry->template->page_first_title = 'Présentation des conseillés avec leurs étudiants';
		$human_ressources_director = $this->registry->newModel('HumanRessourcesDirector');

		$data = $human_ressources_director->getCounsellorWithStudent();

		$content = '';

		if(empty($data)) {
			$content = 'Il n\'y a aucun étudiant conseillé dans la base de données';
		} else {
			foreach ($data as $key => $value) {
				$table = $this->registry->newComponent('Table');
				$table->setCaption($key);
				$table->setDataHeader(array('Prénom', 'Nom', 'Formation'));
				$table->setDataRow($value);
				$table->setStructureClass('multi-table-structure');
				$content .= $table->createView();
			}
		}

		$this->registry->template->content = $content;

		$this->registry->template->show();
	}

	public function showDescNumberCounsellor() {
		$this->registry->template->page_first_title = 'Présentation des enseignants nombre d\'étudiant décroissant';
		$human_ressources_director = $this->registry->newModel('HumanRessourcesDirector');

		$data = $human_ressources_director->getDataDesc();

		$table = $this->registry->newComponent('Table');
		$table->setDataHeader(array('Nombre d\'étudiant', 'Nom', 'Prenom', 'Bureau', 'Pole'));
		$table->setDataRow($data);

		$this->registry->template->content = $table->createView();

		$this->registry->template->show();
	}

	public function purgeAcademicResearcher() {
		$this->registry->template->page_first_title = 'Effacer tous les enseignants';

		$view = 'Êtes vous sûr de supprimer tous les enseignants de la base de données impliquant le retrait de tous les conseillés ?<br/>';
		$button = $this->registry->newComponent('ButtonWidget');
		$button->setAction('ajax_send(\'' . __SITE_ROOT . '/HumanRessourcesDirector/PurgeAcademicResearcherAjax/\',\'\',\'.ajax-return\');');
		$button->setLabel('Oui, je souhaite effacer tous les enseignants de la base de données');
		$view .= $button->createView('widget_button_classic');

		$ajax_content = $this->registry->newComponent('DivWidget');
		$ajax_content->setClass('ajax-return');

		$view .= $ajax_content->createView();

		$this->registry->template->content = $view;
		$this->registry->template->show();
	}

	public function buildAcademicResearcherTable($data) {
		$this->registry->template->page_first_title = 'Gestion des enseignants chercheurs';

		$json_ajax_add_data = json_encode(array('surname' => '\'+$(\'#academic-rechearcher-surname\').val()+\'',
												'name' => '\'+$(\'#academic-rechearcher-name\').val()+\'',
												'office' => '\'+$(\'#office-researcher\').val()+\''
		));

		$human_ressources_director = $this->registry->newModel('HumanRessourcesDirector');
		$data = $human_ressources_director->getData();
		$button_delete = $this->registry->newComponent('ButtonWidget');
		$button_delete->setClass('delete-button');

		foreach ($data as &$val) {
			$json_ajax_delete_data = json_encode(array('name' => $val->nom, 'surname' => $val->prenom));
			$button_delete->setAction('ajax_send(\'' . __SITE_ROOT . '/HumanRessourcesDirector/DeleteAcademicResearcherAjax/\',\'' .
					$json_ajax_delete_data . '\',\'.table-manage-data\');');
			$val->del_button = $button_delete->createView();
		}

		$ajax_content = $this->registry->newComponent('DivWidget');
		$ajax_content->setClass('ajax-return');

		$ajax_function = htmlspecialchars('ajax_send(\'' . __SITE_ROOT . '/HumanRessourcesDirector/ControlAvailability/\',\'' . $json_ajax_add_data . '\',\'.ajax-return\');');

		$input_name = $this->registry->newComponent('Input');
		$input_name->setId('academic-rechearcher-name');
		$input_name->setEvent('onblur="' . $ajax_function . '"');

		$input_surname = $this->registry->newComponent('Input');
		$input_surname->setId('academic-rechearcher-surname');
		$input_surname->setEvent('onblur="' . $ajax_function . '"');

		$input_office = $this->registry->newComponent('Input');
		$input_office->setId('office-researcher');
		$input_office->setEvent('onblur="' . $ajax_function . '"');

		$select_area = $this->registry->newComponent('Select');
		$select_area->setOption($human_ressources_director->getArea());
		$select_area->setId('area-researcher');

		$button_add = $this->registry->newComponent('ButtonWidget');
		$button_add->setClass('add-inactive');

		$button_cancel = $this->registry->newComponent('ButtonWidget');
		$button_cancel->setClass('cancel-button');
		$button_cancel->setAction('showHideElement(\'#table-hidden-row\')');

		$ajax_content->setContent($button_add->createView());

		$table = $this->registry->newComponent('Table');
		$table->setDataHeader(array('Prenom', 'Nom', 'Bureau', 'Pole'));
		$table->setDataRow($data);
		$table->setHiddenRow(array($input_surname->createView(),
			$input_name->createView(),
			$input_office->createView(),
			$select_area->createView(),
			$ajax_content->createView(),
			$button_cancel->createView()
		));
		$table->setRowClass('deletable-row');

		$content .= $table->createView('table_manage_data');

		return $content;
	}

	/* Méthodes appellées via ajax */
	/*	 * **************************** */

	public function purgeAcademicResearcherAjax() {
		if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
			$human_ressources_director = $this->registry->newModel('HumanRessourcesDirector');
			$data = $human_ressources_director->purgeAcademicResearcher();

			$return_data = $this->registry->newComponent('DivWidget');
			$return_data->setClass('form-successful');
			$return_data->setContent('Tous les enseignants ont été effacé');
			
			echo $return_data->createView();
		}
	}

	public function controlAvailability() {
		if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
			$ajax = $this->registry->newComponent('Ajax');
			$data = $ajax->interceptData();
			if (isset($data['name']) && isset($data['surname'])) {
				$human_ressources_director = $this->registry->newModel('HumanRessourcesDirector');
				$ajax_content = $this->registry->newComponent('DivWidget');
				$ajax_content->setClass('ajax-return');
				if ($human_ressources_director->conformValues($data['name'], $data['surname'], $data['office']) && !$human_ressources_director->alreadyExists($data['name'], $data['surname'])) {
					$json_ajax_data = json_encode(array('surname' => '\'+$(\'#academic-rechearcher-surname\').val()+\'',
						'name' => '\'+$(\'#academic-rechearcher-name\').val()+\'',
						'office' => '\'+$(\'#office-researcher\').val()+\'',
						'area' => '\'+$(\'#area-researcher\').val()+\''
					));
					
					$button_add = $this->registry->newComponent('ButtonWidget');
					$button_add->setClass('add-active');
					$button_add->setAction('ajax_send(\'' . __SITE_ROOT . '/HumanRessourcesDirector/addAcademicResearcherAjax/\',\'' . $json_ajax_data . '\',\'.table-manage-data\');');

					$ajax_content->setContent($button_add->createView());

					echo $ajax_content->createView();
				} else {
					$button_add = $this->registry->newComponent('ButtonWidget');
					$button_add->setClass('add-inactive');

					$ajax_content->setContent($button_add->createView());

					echo $ajax_content->createView();
				}
			}
		}
	}

	public function addAcademicResearcherAjax() {
		if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
			$ajax = $this->registry->newComponent('Ajax');
			$data = $ajax->interceptData();
			if (isset($data['name']) && isset($data['surname']) && isset($data['office']) && isset($data['area'])) {
				$human_ressources_director = $this->registry->newModel('HumanRessourcesDirector');
				$human_ressources_director->addAcademicResearcher($data['name'], $data['surname'], $data['office'], $data['area']);
				$data = $human_ressources_director->getData();
				echo $this->buildAcademicResearcherTable($data);
			}
		}
	}

	public function deleteAcademicResearcherAjax() {
		if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
			$ajax = $this->registry->newComponent('Ajax');
			$data = $ajax->interceptData();
			if (isset($data['name']) && isset($data['surname'])) {
				$human_ressources_director = $this->registry->newModel('HumanRessourcesDirector');
				$human_ressources_director->deleteAcademicResearcher($data['name'], $data['surname']);

				$data = $human_ressources_director->getData();
				echo $this->buildAcademicResearcherTable($data);
			}
		}
	}

}

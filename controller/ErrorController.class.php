<?php

class ErrorController extends BaseController {

	public function __construct($registry) {
		parent::__construct($registry);
		$this->registry->template->page_first_title = 'Oops ! Une erreur s\'est produite...';
	}

	public function index() {
		$this->registry->template->page_second_title = 'Erreur inconnue';
		$this->registry->template->content = 'Une erreur inconnue c\'est produite';
		$this->registry->template->show();
	}

	public function e404() {
		$this->registry->template->page_second_title = 'Erreur 404';
		$this->registry->template->content = 'La page spécifiée est introuvable';
		$this->registry->template->show();
	}

	public function restricted() {
		$this->registry->template->page_second_title = 'Restriction d\'accès';
		$this->registry->template->content = 'La page spécifiée est sécurisée par authentification';
		$this->registry->template->show();
	}

}

?>

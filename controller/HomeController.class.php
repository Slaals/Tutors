<?php

class HomeController extends BaseController {

	public function index() {
		$this->registry->template->show();
	}

	public function signin() {
		$this->registry->template->page_second_title = 'Authentification';
		if ($this->registry->Authentification->isLogOn()) {
			$this->registry->Authentification->goHome();
		} else {
			$form = $this->registry->newComponent('Form');
			$form->init('post', '');
			$form->addField('Nom d\'utilisateur', 'user', array('type' => 'text', 'maxlength' => '20'))
					->addField('Mot de passe', 'password', array('type' => 'password', 'maxlength' => '20'))
					->addFieldRule('user', array('rule_type' => 'operator', 'rule_value' => 'empty', 'rule_bool' => false))
					->addFieldRule('password', array('rule_type' => 'operator', 'rule_value' => 'empty', 'rule_bool' => false));

			if ($form->isValid()) {
				$auth = $this->registry->newModel('Registered');
				$user = $form->getFieldValue('user');
				$password = $form->getFieldValue('password');
				$token = $auth->getToken($user, $password);
				if ($token) {
					$this->registry->Authentification->signin($user, $password, $token);
					$this->registry->Authentification->goHome();
				} else {
					$form->addCommonError('Ce couple utilisateur/mot de passe n\'a pas permis de vous authentifier');
				}
			}

			$form_view = $form->createView('form_default');
			$this->registry->template->content = $form_view;
			$this->registry->template->show();
		}
	}

	public function signout() {
		$this->registry->Authentification->signout();
		header('Location:' . __SITE_ROOT . '/');
	}

}

?>

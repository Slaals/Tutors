<?php

class IndexController extends BaseController {

	public function index() {
		if($this->registry->Authentification->isLogon()) {
			$log = $this->registry->Authentification->getSession('user');
			$statut = $this->registry->Authentification->getSession('statut');
			$logon_time = $this->registry->Authentification->connexionTime();
			$statut = str_replace('_', ' ', $statut);
			$content .= 'Vous êtes connecté en tant que <b> ' . $log . '</b><br />';
			$content .= 'Statut : <b>' . $statut . '</b><br />';
			$content .= 'Temps de connexion : <b>' . $logon_time . ' </b>';
		} else {
			$content = 'Vous n\'êtes pas authentifié, merci de vous identifier afin d\'utiliser l\'application';
		}
		$this->registry->template->content = $content;
		$this->registry->template->show();
	}

}

?>
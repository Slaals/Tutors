<?php

class FrontController {

	private $registry;

	public function load($registry) {
		$this->registry = $registry;

		//Menu principal
		$menu_main = $this->registry->newComponent('MenuWidget');
		$menu_main->addLink(array('value' => 'accueil', 'href' => '/'));
		if ($this->registry->Authentification->isLogOn()) {
			$menu_main->addLink(array('value' => 'déconnexion', 'href' => '/home/signout', 'class' => 'signout'));
		} else {
			$menu_main->addLink(array('value' => 'identification', 'href' => '/home/signin'));
		}
		$this->registry->template->widget_menu_main = $menu_main->createView('widget_menu_main');

		//Menu fonctionnalités
		$menu_feature = $this->registry->newComponent('MenuWidget');
		if ($this->registry->Authentification->isLogOn()) {
			$statut = $this->registry->Authentification->getSession('statut');
			if (!empty($this->registry->json_data->links->$statut->features)) {

				foreach ($this->registry->json_data->links->$statut->features as $key => $val) {
					$menu_feature->addLink(array('value' => $key, 'href' => $val));
				}
			}
			$this->registry->template->widget_menu_feature = $menu_feature->createView('widget_menu_feature');
		}
	}

}

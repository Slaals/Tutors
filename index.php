<?php

// Error reporting on
error_reporting(E_ALL ^ E_NOTICE);

// Charset
header('Content-Type: text/html; charset=utf-8');
// Constantes statiques
$site_path = realpath(dirname(__FILE__));
define('__SITE_PATH', $site_path);

/* * * include the registry class ** */
include __SITE_PATH . '/application/core/Registry.class.php';

// Fichiers d'inclusion
$include_files = array_diff(scandir('include/'), array('.', '..'));
foreach ($include_files as $val) {
	include(__SITE_PATH . '/include/' . $val);
}

$registry = new Registry();

// Constantes dynamiques
$conf_files = array_diff(scandir('config/'), array('.', '..'));
$json_data = new StdClass;
foreach ($conf_files as $val) {
	$filename = explode('.', $val)[0];
	$extention = explode('.', $val)[1];

	if ($extention == "ini") {
		$parse = parse_ini_file('config/' . $val);
		foreach ($parse as $key => $val2) {
			define('__' . strtoupper($key), $val2);
		}
	} elseif ($extention == "json") {
		$json_data->$filename = new stdClass;
		$json_data->$filename = json_decode(file_get_contents('config/' . $val));
	}
}

$registry->json_data = $json_data;

//Instanciation des classes du core
$registry->router = new Router($registry);
$registry->template = new Template($registry);

//Instanciation des classes global
$registry->Authentification = new Authentification($registry);

//Appel et déclanchement du routage de l'URL vers les controller
$registry->router->route();
?>

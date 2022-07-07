<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

if (!defined('CUTTER_DOMAIN_PATH')) {

	// $pasta_atual = ''; // prducao
	$pasta_atual = DIRECTORY_SEPARATOR.'cutter'.DIRECTORY_SEPARATOR; // desenvoliemnto
	define('CUTTER_DOMAIN_PATH', $_SERVER["DOCUMENT_ROOT"].$pasta_atual);
}

include CUTTER_DOMAIN_PATH.'sanborn'.DIRECTORY_SEPARATOR.'Sanborn.php';

// $autor = 'Rocha, Davi Samuel de Oliveira';
// $obra = 'vida e obra';
$autor = $_POST['autor'];
$obra = $_POST['obra'];
$usar_obra = isset($_POST['usar_obra']);

$sanborn = new Sanborn();
$sanborn->setAutor($autor);
if($usar_obra) {
	$sanborn->setTituloObra($obra);
	$sanborn->setConfiguracao(array('usar_titulo_obra' => true));
}
$cutter = $sanborn->gerar();

print_r($cutter);
<?php

session_start();

spl_autoload_register('ChargementClasses');

function ChargementClasses($nomClasse)
{
	$chemin = './php/Classes/';
	require_once($chemin.$nomClasse.'.php');
}

$maBD = new bdService();

?>
<?php

define ("TPS", 0.05);
define ("TVQ", 0.09975);
define ("FRAIS_CODE", 3.00);

spl_autoload_register('ChargementClasses');

session_start();

function ChargementClasses($nomClasse)
{
	$chemin = './php/Classes/';
	require_once($chemin.$nomClasse.'.php');
}

$maBD = new bdService();

?>
<?php
require_once("./php/biblio/foncCommunes.php");

if(!empty($_GET['nomUtilisateur']))
{
	global $maBD;

	$nomUtilisateur = desinfecte($_GET['nomUtilisateur']);

	$client = $maBD->selectClient($nomUtilisateur);

	if (isset($client))
		echo 0;
	else
		echo 1;
}

?>
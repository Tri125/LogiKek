<?php
require_once("./php/biblio/foncCommunes.php");

if(!empty($_GET['nomUtilisateur']))
{
	global $maBD;

	$nomUtilisateur = desinfecte($_GET['nomUtilisateur']);

	try
	{
		$client = $maBD->selectClient($nomUtilisateur);
	}
	catch (Exception $e)
	{
		echo 'Erreur du test d\'unicité';
		exit();
	}

	if (isset($client))
		echo 0;
	else
		echo 1;
}

?>
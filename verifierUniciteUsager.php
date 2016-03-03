<?php

//-----------------------------
// Script qui vérifie l'unicité en base de données d'un nom
// d'utilisateur passé par une variable GET
//
// Retourne 0 à un doublon, 1 si le nom est disponible,
// un message d'erreur dans le cas d'un erreur.
//-----------------------------
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
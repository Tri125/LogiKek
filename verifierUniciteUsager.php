<?php

//-----------------------------
// Script qui vérifie l'unicité en base de données d'un nom
// d'utilisateur passé par une variable GET
//
// Retourne 0 à un doublon, 1 si le nom est disponible,
// un message d'erreur dans le cas d'un erreur.
//-----------------------------
require_once("./php/biblio/foncCommunes.php");

//S'il reçoit nomUtilisateur dans le GET
if(!empty($_GET['nomUtilisateur']))
{
	global $maBD;
	//Désinfecte l'entré de l'utilisateur.
	$nomUtilisateur = desinfecte($_GET['nomUtilisateur']);

	try
	{
		//Select en base de données sur le nom de l'utilisateur.
		$client = $maBD->selectClient($nomUtilisateur);
	}
	catch (Exception $e)
	{
		echo 'Erreur du test d\'unicité';
		exit();
	}
	//Si $client est set, il y a donc déjà une entré dans la base de données avec le nom d'utilisateur.
	if (isset($client))
		//False pour non-libre/non unique.
		echo 0;
	//Not set, donc aucun retour du select.
	else
		//Libre/Unique
		echo 1;
}

?>
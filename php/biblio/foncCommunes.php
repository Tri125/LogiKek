<?php

define ("NOM_SESSION", "LogiKek");
define ("TPS", 0.05);
define ("TVQ", 0.09975);
//Frais du magasin par transaction.
define ("FRAIS_CODE", 3.00);

define ("ROOT_DIR", realpath(__DIR__.'/../..'));

$CSS_DIR = './css/';
$JS_DIR = './js/';
$IMG_DIR = './img/';

//Enregistre la fonction ChargementClasses pour activé la queue de chargement des classes.
spl_autoload_register('ChargementClasses');

session_name(NOM_SESSION);
//Commence la session pour enregistrer des variables persistantes entre fenêtres de navigations.
session_start();

//-----------------------------
//Fonction de spl_autoload pour 
//charger automatiquement les scripts situé sous le chemin spécifié
//-----------------------------
function ChargementClasses($nomClasse)
{
	//chemin où nos classes sont situés.
	$chemin = realpath(__DIR__.'/..').'/Classes/';
	require_once($chemin.$nomClasse.'.php');
}



//-----------------------------
//Fonction pour désinfecte les entrés utilisateur
//-----------------------------
function desinfecte($string)
{
	$string = trim($string);
	$string = stripslashes($string);
	$string = htmlspecialchars($string);
	//$string = htmlentities($string, ENT_QUOTES);
	return $string;
}

//-----------------------------
//Fonction pour déconnecter un utilisateur de son compte client.
//-----------------------------
function deconnexionUsager()
{
	session_name(NOM_SESSION);
	//Récupère la session/commence une nouvelle
	session_start();
	//Met à jour l'id de session à une nouvelle valeur pour mitiger les attaques.
	//Supprime l'ancien fichier de session associé.
	session_regenerate_id(true);
	$_SESSION = array();
	header("location:./");
	exit();
}

//-----------------------------
//Fonction pour calculer les taxes d'achats 
//et les frais associés à partir d'un prix passé en paramètre
//-----------------------------
function calculTaxeFrais($prix)
{
	$taxe = ($prix * TVQ) + ($prix * TPS);

	return $prix + $taxe + FRAIS_CODE;
}


//Un seul objet bdService nécessaire, on l'instancie donc dans ce script pour être utilisé ailleur.
$maBD = new bdService();


?>
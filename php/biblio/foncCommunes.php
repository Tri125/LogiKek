<?php

define ("TPS", 0.05);
define ("TVQ", 0.09975);
//Frais du magasin par transaction.
define ("FRAIS_CODE", 3.00);

//Enregistre la fonction ChargementClasses pour activé la queue de chargement des classes.
spl_autoload_register('ChargementClasses');

session_name('LogiKek');
//Commence la session pour enregistrer des variables persistantes entre fenêtres de navigations.
session_start();

//-----------------------------
//Fonction de spl_autoload pour 
//charger automatiquement les scripts situé sous le chemin spécifié
//-----------------------------
function ChargementClasses($nomClasse)
{
	//chemin où nos classes sont situés.
	$chemin = './php/Classes/';
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

//Un seul objet bdService nécessaire, on l'instancie donc dans ce script pour être utilisé ailleur.
$maBD = new bdService();

?>
<?php 

//-----------------------------
// Page principal du site web, le catalogue.
//-----------------------------

require_once(realpath(__DIR__.'/..').'/php/biblio/foncCommunes.php');

$CSS_DIR = '../css/';
$JS_DIR = '../js/';
$IMG_DIR = '../img/';

//Variable pour que header.php charge un javascript spécifique à la page index.php
$js = array();
$js[] = 'index.js';
//Variable pour que header.php charge une feuille de style spécifique à la page index.php
$css = array();
$css[] = 'index.css';
//Variable pour que header.php donne un titre de page spécifique à index.php
$titre = 'LogiKek - Administration';
//Variable pour que header.php donne une description spécifique à la page index.php
$description = 'Site de vente de système d\'exploitation';
//Variable pour que header.php donne des mots clés spécifique à la page index.php
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';


if(!isset($_SESSION['authentification']))
{
	header('location:../authentification.php?prov=admin');
	exit();
}

if(isset($_SESSION['authentification']) && !$_SESSION['client']->getEstAdmin())
{
	header('location:../');
	exit();
}


//Charge les scripts
require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-7 -->
<div class="col-md-7" id="centre">
	<div class="pub">
		<img class="img-responsive" src="../img/LogiKek2.png" alt="Logo LogiKek">
	</div>
	<!-- Début des produits -->
	<div class="row">
		<div class="jumbotron">
  			<div class="container"> <!-- Contenant avec nos politiques d'entreprise -->
  				<h2>Administration</h2>
  				<p>Utiliser les liens de navigation situé à gauche pour faire la gestion du site web.</p>
  				<p>Vous pouvez également consulter des rapports d'inventaires et de ventes.</p>
  			</div> <!-- Fin contenant de nos politiques -->
		</div> <!-- Fin du jumbotron -->
	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-7 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('../footer.php'); ?>
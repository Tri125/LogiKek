<?php 
require_once(realpath(__DIR__.'/..').'/php/biblio/foncCommunes.php');

$js = array();

$css = array();
$css[] = 'formulaire.css';
$titre = 'LogiKek - Rapport Factures';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';


$CSS_DIR = '../css/';
$JS_DIR = '../js/';
$IMG_DIR = '../img/';

require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début des produits -->
	<div class="row">



<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('../footer.php'); ?>
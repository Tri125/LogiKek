<?php

require_once("./php/biblio/foncCommunes.php");

$panier = new Panier();

if(isset($_GET['quoiFaire']))
{
	switch ($_GET['quoiFaire'])
	{
		case "ajout":
			$panier->ajouter($_GET['noProduit']);
			break;
		case "modification":
			break;
		case "supression":
			break;
		case "vider":
			break;
		default: 
			break;
	}
}


$js = 'panierGestion.js';
$css = 'panierGestion.css';
$titre = 'LogiKek - Panier';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

require_once("./header.php");
require_once("./sectionGauche.php");

?>


<!-- Début section central col-md-9 -->
<div class="col-md-8" id="centre">
	<div class="pub">
		<h2>Panier</h2>
	</div>
	<!-- Début des produits -->
	<div class="row">



<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
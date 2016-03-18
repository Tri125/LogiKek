<?php 
require_once("./php/biblio/foncCommunes.php");

$js = array();

$css = array();
$css[] = 'panierGestion.css';
$titre = 'LogiKek - Facture';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

$panier = new Panier();
$client = $_SESSION['client'];

global $maBD;

try
{
	$resultat = $maBD->PasserCommande($client, $panier);
}
catch (Exception $e)
{
	var_dump($e->getMessage());
	exit();
}

unset($_SESSION['panier']);
unset($_SESSION['panier-item']);

require_once("./header.php");
require_once("./sectionGauche.php");

var_dump($resultat);
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


<?php require_once('./footer.php'); ?>
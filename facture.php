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
	//Modifie la quantité en inventaire des articles commandés.
	$nbrLigneJour =	$maBD->updateQteStock($panier);
	//Crée une commande et l'associe au client.
	$numCommande = $maBD->insertCommande($client);
}
catch (Exception $e)
{
	var_dump($e->getMessage());
	exit();
}

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


<?php require_once('./footer.php'); ?>
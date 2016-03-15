<?php

//-----------------------------
// Script d'authentification qui g�re la connexion au compte client
// et la circulation entre certaines pages
//-----------------------------

require_once("./php/biblio/foncCommunes.php");

$js = array();

$css = array();
//$css[] = 'formulaire.css';
$titre = 'LogiKek - Commander';
$description = 'Site de vente de syst�me d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

$panier = new Panier();

global $maBD;

if (!isset($_SESSION['authentification']))
{
	//Redirection � la page d'authentification.
	header("location:./authentification.php?prov=commande");
	exit();
}
else
{
	var_dump($panier);
	require_once("./header.php");
	require_once("./sectionGauche.php");
}

?>


<!-- D�but section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- D�but des produits -->
	<div class="row">
		<?php if($panier->isEmpty()) : ?>
			<div class="alert alert-warning" role="alert"> <!-- Message indiquant un panier vide. -->
				<i class="fa fa-info-circle"></i>
				Votre panier est vide.
				<a href="./">Continuer votre magasinage</a>
			</div>
		<?php endif; ?>


<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- D�but Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
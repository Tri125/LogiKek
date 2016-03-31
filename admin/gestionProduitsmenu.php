<?php 
require_once(realpath(__DIR__.'/..').'/php/biblio/foncCommunes.php');

$js = array();

$css = array();
$css[] = 'index.css';
$titre = 'LogiKek - Gestion Produits Menu';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';


$CSS_DIR = '../css/';
$JS_DIR = '../js/';
$IMG_DIR = '../img/';

global $maBD;

$catalogue = new Catalogue(0, '');


require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début des produits -->
	<div class="row">
		<form id='formProduit' method='POST' action='./gestionProduits.php'>
			<select name='choix'>
				<option value="nouveau">Nouveau Produit</option>
				<?php foreach ($catalogue->getCatalogue() as $value): ?>
				<option value="<?php echo $value->getNom(); ?>"><?php echo $value->getNom(); ?></option>
				<?php endforeach; ?>
			</select>
			<input type="submit" value="Continuer">
		</form>
<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('../footer.php'); ?>
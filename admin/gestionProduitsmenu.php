<?php 

//-----------------------------
//Page du menu pour sélectionner si on modifie un produit ou crée un nouveau.
//-----------------------------

require_once(realpath(__DIR__.'/..').'/php/biblio/foncCommunes.php');

$js = array();

$css = array();
$css[] = 'index.css';
$titre = 'LogiKek - Gestion Produits Menu';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

//Path relatif vers les dossiers ressources.
$CSS_DIR = '../css/';
$JS_DIR = '../js/';
$IMG_DIR = '../img/';

global $maBD;

//Récupère le Catalogue de produits sans utilisé la recherche.
$catalogue = new Catalogue(0, '');


require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début des produits -->
	<div class="row">
	<?php if(isset($_GET['success'])): //Message de succès?>
		<div class="alert alert-success" role="alert">
			<i class="fa fa fa-check"></i>
				Opération fait avec succès.
		</div>
	<?php endif; ?>
		<!-- Formulaire de liste déroulante -->
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
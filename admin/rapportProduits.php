<?php 
require_once(realpath(__DIR__.'/..').'/php/biblio/foncCommunes.php');

$js = array();

$css = array();
$css[] = 'formulaire.css';
$titre = 'LogiKek - Rapport Produits';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';


$CSS_DIR = '../css/';
$JS_DIR = '../js/';
$IMG_DIR = '../img/';

$produits;

global $maBD;

try
{
	$produits = $maBD->selectRapportProduit();
}
catch (Exception $e)
{
	exit();
}

require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début des produits -->
	<div class="row">
	<?php if(isset($produits) && count($produits) == 0): ?>
		<div class="alert alert-success" role="alert">
			<i class="fa fa fa-check"></i>
				Aucun produit à commander.
		</div>
	<?php endif; ?>
		<table>
			<tr>
				<td colspan='3'>
					<h3>Produits à commander</h3>
				</td>
			</tr>
			<tr>
				<td><label>Nom</label></td>
				<td><label>Qté en stock</label></td>
				<td><label>Qté minimale</label></td>
			</tr>
			<?php foreach($produits as $value): ?>
			<tr>
				<td><?php echo $value['nom']; ?></td>
				<td><?php echo $value['quantite']; ?></td>
				<td><?php echo $value['quantiteMin']; ?></td>
			</tr>
			<?php endforeach; ?>
		</table>


<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('../footer.php'); ?>
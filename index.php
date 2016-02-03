<?php 
require_once("php/mysqli.php");
require_once("php/Classes/Categorie.php"); 
require_once("php/Classes/Produit.php"); 
require_once("php/Classes/Catalogue.php"); 

$titre = 'LogiKek';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

require_once("header.php");
require_once("sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-8" id="centre">
	<div class="pub">
		<img class="img-responsive" src="LogiKek/img/DealArchEdit.png" alt="Publicité de Arch Linux.">
	</div>
	<!-- Début des produits -->
	<div class="row">
		<?php foreach($liste->catalogue as $value): ?>
			<div class="col-md-4 panel panel-default">
				<h4><?php echo $value->nom ?></h4>
				<a href="#" class="thumbnail imgProduitPetit">
					<img src="LogiKek/img/produits/<?php echo $value->codeProduit ?>_small.png" alt="<?php echo $value->nom ?>" onError="this.onerror=null;this.src='LogiKek/img/produits/nonDispo_small.png';">
				</a>
				<?php foreach($value->categories as $categorie): ?>
					<span class="label label-info"><?php echo $categorie ?></span>
				<?php endforeach; ?>
				<h4>
					<?php echo $value->prix ?>$ 
					<a href="#">
						<i class="fa fa-shopping-cart"></i>
					</a>
				</h4>
			</div>
		<?php endforeach; ?>
	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('footer.php'); ?>
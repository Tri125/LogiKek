<?php 
require_once("./php/biblio/foncCommunes.php");

$js = 'index.js';
$css = 'index.css';
$titre = 'LogiKek';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<div class="pub">
		<img class="img-responsive" src="./img/DealArchEdit.png" alt="Publicité de Arch Linux.">
	</div>
	<!-- Début des produits -->
	<div class="row">
		<?php if(count($liste->getCatalogue()) > 0) : ?>
			<?php foreach($liste->getCatalogue() as $value): ?>
				<div class="col-md-4 panel panel-default produit">
					<h4><?php echo $value->getNom(); ?></h4>
					<a class="thumbnail imgProduitPetit" data-noProduit="<?php echo $value->getCodeProduit() ?>">
						<img src="./img/produits/<?php echo $value->getCodeProduit(); ?>_small.png" alt="<?php echo $value->getNom(); ?>" onError="this.onerror=null;this.src='./img/produits/nonDispo_small.png';">
					</a>
					<?php foreach($value->getCategories() as $categorie): ?>
						<span class="label label-info proCategorie"><?php echo $categorie; ?></span>
					<?php endforeach; ?>
					<h4 class="proPrix">
						<?php echo number_format($value->getPrix(), 2); ?>$ 
						<a href="./panierGestion.php?quoiFaire=ajout&noProduit=<?php echo $value->getCodeProduit(); ?>" class="proCart">
							<i class="fa fa-shopping-cart"></i>
						</a>
					</h4>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<h1 id="aucunProduit">Aucun produit</h1>
		<?php endif; ?>
	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">

  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php require_once('./footer.php'); ?>
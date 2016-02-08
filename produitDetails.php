<?php 
require_once("./php/biblio/foncCommunes.php");

//$css = 'index.css';
//$titre = 'LogiKek';
//$description = 'Site de vente de système d\'exploitation';
//$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

global $maBD;
$produit;

if(isset($_GET['noProduit']))
{
	$codeProduit = $_GET['noProduit'];
	$resultat = $maBD->select("SELECT p.idProduit, p.nom, p.description, p.prix, p.codeProduit, p.quantite, p.quantiteMin, 
    GROUP_CONCAT(c.nom SEPARATOR ',') categories
	FROM Produits p
		INNER JOIN ProduitsCategories pc ON pc.idProduit = p.idProduit
		INNER JOIN Categories c ON c.idCategorie = pc.idCategorie 
	GROUP BY p.idProduit
	HAVING p.idProduit = $codeProduit");

	$produit = new Produit($resultat[0]);
}

?>

<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title"><?php echo $produit->getNom(); ?></h4>
	</div>
	<div class="modal-body">
		<a class="thumbnail imgProduitGrand">
			<img src="./img/produits/<?php echo $produit->getCodeProduit(); ?>_big.png" alt="<?php echo $produit->getNom(); ?>" onError="this.onerror=null;this.src='./img/produits/nonDispo_big.png';">
		</a>
		<p><?php echo $produit->getDescription(); ?></p>
	</div>
	<div class="modal-footer">
		<p>Quantité disponible: <?php echo $produit->getQuantite(); ?></p>
	</div>
</div><!-- /.modal-content -->
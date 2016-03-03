<?php 

//-----------------------------
// Page qui récupère un produit dans la base de donnée et affiche ses informations détaillés.
//-----------------------------
require_once("./php/biblio/foncCommunes.php");


global $maBD;
$produit;

//Récupère le code du produit du paramètre GET que nous voulons afficher en détail.
if(isset($_GET['noProduit']))
{
	$codeProduit = $_GET['noProduit'];
	
	//Récupère le produit avec le service bd.
	try
	{
		$resultat = $maBD->select("SELECT p.idProduit, p.nom, p.description, p.prix, p.quantite, p.quantiteMin, 
    	GROUP_CONCAT(c.nom SEPARATOR ',') categories
		FROM Produits p
			INNER JOIN ProduitsCategories pc ON pc.idProduit = p.idProduit
			INNER JOIN Categories c ON c.idCategorie = pc.idCategorie 
		GROUP BY p.idProduit
		HAVING p.idProduit = $codeProduit");
	}
	catch (Exception $e)
	{
		die();
	}
	//Le code est unique, donc le résultat est à l'index 0
	$produit = new Produit($resultat[0]);
}

?>

<div class="modal-content"> <!-- Fenêtre modal de jQueryUI -->
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
</div><!-- /.Fin fenêtre modal -->
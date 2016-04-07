<?php 

//-----------------------------
//Page du rapport des factures.
//Affiche toutes les Commandes passé par nos clients.
//-----------------------------

require_once(realpath(__DIR__.'/..').'/php/biblio/foncCommunes.php');

$js = array();

$css = array();
$css[] = 'formulaire.css';
$titre = 'LogiKek - Rapport Factures';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

//Path relatif vers les dossiers ressources.
$CSS_DIR = '../css/';
$JS_DIR = '../js/';
$IMG_DIR = '../img/';

$commandes;

global $maBD;


try
{
	//Récupère les données de l'historique des commandes du magasin.
	$commandes = $maBD->selectRapportFacture();
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
		<?php if(isset($commandes) && count($commandes) == 0): //Message s'il n'y a aucune commande.?>
		<div class="alert alert-warning" role="alert">
			<i class="fa fa-exclamation-triangle"></i>
				Aucune facture à afficher.
		</div>
		<?php endif; ?>
		<table>
			<thead>
				<tr>
					<td colspan=4>
						<h3>Historiques des factures</h3>
					</td>
				</tr>
				<tr>
					<td><label>Client</label></td>
					<td><label>Date commande</label></td>
					<td><label>Produit</label></td>
					<td><label>Quantité</label></td>
				</tr>
			</thead>
			<tbody>
			<?php foreach($commandes as $commande): ?>
				<tr>
					<td>
						<?php echo $commande['prenom'].' '.$commande['nom']; ?>
					</td>
					<td>
						<?php echo $commande['dateCommande']; ?>
					</td>
				<?php foreach($commande['tabAchats'] as $key => $produit): ?>
				<?php if($key != 0): ?>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				<?php endif; ?>
					<td>
						<?php echo $produit['nom']; ?>
					</td>
					<td>
						<?php echo $produit['nombre']; ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan=4><hr></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('../footer.php'); ?>
<?php 
require_once("./php/biblio/foncCommunes.php");

$js = array();

$css = array();
$css[] = 'index.css';
$titre = 'LogiKek';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';


if (!isset($_SESSION['authentification']))
{
	//Redirection ?la page d'authentification.
	header("location:./authentification.php?prov=histCommande");
	exit();
}
else
{
	global $maBD;
	$commandes = array();

	require_once("./header.php");
	require_once("./sectionGauche.php");

	try
	{
		$resultat = $maBD->selectCommandeDetails($_SESSION['authentification']);
	}
	catch (Exception $e)
	{
		exit();
	}

	if (isset($resultat))
	{
		foreach ($resultat as $value) 
		{
			$commandes[] = new Commande($value);
		}
	}
}
?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début des produits -->
	<div class="row">
		<table>
			<tr>
				<td>
					<label>Vous avez passé un total de <?php echo count($commandes); ?> commandes</label>
				</td>
			</tr>
			<?php foreach ($commandes as $key => $value): ?>
			<tr>
				<td>
					<label>Commande : <?php echo $value->getNumCommande(); ?></label>
				</td>
				<td>
					<label>
						Date : <?php echo $value->getDateCommande(); ?>
						<br>
						<?php echo number_format($value->Total(), 2); ?>$
					</label>
				</td>
				<td>
					<a href="#">Annuler</a>
				</td>
			</tr>
			<tr>
				<td>
					<label>Produit</label>
				</td>
				<td>
					<label>Quantité</label>
				</td>
				<td>
					<label>Prix</label>
				</td>
			</tr>
			<?php foreach ($value->getTabAchats() as $keyAchat => $valueAchat): ?>
			<tr>
				<td><?php echo $valueAchat->getNom(); ?></td>
				<td><?php echo $valueAchat->getNombre(); ?></td>
			<td><?php echo number_format($valueAchat->getPrix(), 2); ?>$</td>
				<?php endforeach; ?>
			</tr>
			<?php endforeach; ?>
		</table>

		<br>
	


<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
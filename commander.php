<?php

//-----------------------------
// Script d'authentification qui gère la connexion au compte client
// et la circulation entre certaines pages
//-----------------------------

require_once("./php/biblio/foncCommunes.php");

$js = array();

$css = array();
$css[] = 'panierGestion.css';
$titre = 'LogiKek - Commander';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

$panier = new Panier();
$client = $_SESSION['client'];
$achat = false;

if (isset($_SESSION['achat']))
{
	$achat = $_SESSION['achat'];
	unset($_SESSION['achat']);
}

$sousTotal = 0.00;
$tvq = 0.00;
$tps = 0.00;
$total = 0.00;


function Commander($client, $panier)
{
	global $maBD;

	try
	{
		$resultat = $maBD->PasserCommande($client, $panier);
	}
	catch (Exception $e)
	{
		exit();
	}

	unset($_SESSION['panier']);
	unset($_SESSION['panier-item']);
	return $resultat['idCommande'];
}

if (!isset($_SESSION['authentification']))
{
	//Redirection ?la page d'authentification.
	header("location:./authentification.php?prov=commande");
	exit();
}
else
{
	if (!empty($achat))
	{
		$numCommande = Commander($client, $panier);
	}

	require_once("./header.php");
	require_once("./sectionGauche.php");

}

?>


<!-- D?ut section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- D?ut des produits -->
	<div class="row">
	<?php if($panier->isEmpty()) : ?>
		<div class="alert alert-warning" role="alert"> <!-- Message indiquant un panier vide. -->
			<i class="fa fa-info-circle"></i>
			Aucun article dans votre commande.
			<a href="./">Continuer votre magasinage</a>
		</div>
	<?php else: ?>
		<?php if (!empty($achat)) : ?>
		<h2>Facture</h2>
		<table class="pull-left">
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo "<label>", $client->getPrenom(), " ", $client->getNom(), "</label>"; ?></td>
			</tr>
			<tr>
				<td><?php echo "<label>", $client->getAdresse(), ", ", $client->getVille(), "</label>"; ?></td>
			</tr>
			<tr>
				<td><?php echo "<label>", $client->getProvince(), "</label>"; ?></td>
			</tr>
			<tr>
				<td><?php echo "<label>", $client->getcodePostal(), "</label>"; ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>
		<?php else: ?>
		<h2>Votre commande</h2>
		<?php endif; ?>
		<table class="table">
			<thead>
				<?php if (empty($achat)) : ?>
				<tr>
					<td colspan="4">
						<a href="./panierGestion.php">Retourner au panier</a>
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td class="nomProduit">Produit</td>
					<td class="nomProduit">Quantité</td>
					<td class="nomProduit">Prix unit.</td>
					<td class="nomProduit prix-group">Prix</td>
				</tr>
			</thead>
			<tbody>
			<?php foreach($panier->getTabAchats() as $key=>$value):
				$sousTotal += ($value->getPrix() * $value->getNombre()); 
			?>
				<tr>
					<td><?php echo $value->getNom(); ?></td>
					<td><?php echo $value->getNombre(); ?></td>
					<td><?php echo number_format($value->getPrix(), 2); ?>$</td>
					<td class="prix-group"><?php echo number_format($value->getPrix() * $value->getNombre(), 2) ?>$</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
				<tfoot class="fraisPrix"> <!-- Pied du tableau contenant le sous total, taxes, total, etc. -->
					<tr>
						<td colspan="4">
							<span class="label">Sous total:</span>
							<span><?php echo number_format($sousTotal, 2) ?>$</span>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<span class="label">TPS:</span>
							<span><?php echo $tps = number_format($sousTotal * TPS, 2); //Assigne et affiche en une ligne ?>$</span>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<span class="label">TVQ:</span>
							<span><?php echo $tvq = number_format($sousTotal * TVQ, 2); //Assigne et affiche en une ligne ?>$</span>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<span class="label">Frais de génération de codes:</span>
							<span><?php echo number_format(FRAIS_CODE, 2); ?>$</span>
						</td>
					</tr>
					<tr>
						<td id="total" colspan="4">
							<span class="label">Total:</span>
							<span><?php echo number_format($total = $sousTotal + $tvq + $tps + FRAIS_CODE, 2) //Assigne et affiche en une ligne ?>$</span>
						</td>
					</tr>
				</tfoot>
		</table>
		<?php if (!empty($achat)) : ?>
		<div class="btn-toolbar pull-right" role="group" aria-label="...">
			<form id="confirmerForm" method="POST" action="./creditGestion.php"> <!-- Formulaire commander -->
				<input type="hidden" class="btn" name="confirmer" value="Confirmer"> 
			</form>
		</div>
		<div>
			<p>
				Votre numéro de commande est le <label><?php echo $numCommande; ?></label>.
			</p>
			<p>Pour retourner au catalogue cliquez <a href="./">ici</a>.</p>
		</div>
		<?php else: ?>
		<div class="btn-toolbar pull-right" role="group" aria-label="...">
			<form id="confirmerForm" method="POST" action="./creditGestion.php"> <!-- Formulaire commander -->
				<input type="submit" class="btn" name="confirmer" value="Confirmer"> 
			</form>
		</div>
		<?php endif; ?>
	<?php endif; ?>
<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- D?ut Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
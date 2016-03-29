<?php

//-----------------------------
// Script pour afficher la confirmation d'une commande et l'affichage de la facture
//-----------------------------

require_once(realpath(__DIR__).'/php/biblio/foncCommunes.php');

$js = array();

$css = array();
$css[] = 'panierGestion.css';
$titre = 'LogiKek - Commander';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

$panier = new Panier();
$client = $_SESSION['client'];
$achat = false;

//Si nous voulons confirmer un achat
if (isset($_SESSION['achat']))
{
	$achat = $_SESSION['achat'];
	//Enlève pour qu'au rafraichissement on retourne dans le contexte d'affichage de commande.
	unset($_SESSION['achat']);
}

$sousTotal = 0.00;
$tvq = 0.00;
$tps = 0.00;
$total = 0.00;

//-----------------------------
// Fonction pour passer la commande
// Client et panier passé en paramètres
//-----------------------------
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
	//Vide le panier et unset les variables de session associées.
	$panier->vider();

	return $resultat['idCommande'];
}

if (!isset($_SESSION['authentification']))
{
	//Redirection à la page d'authentification.
	header("location:./authentification.php?prov=commande");
	exit();
}
else
{
	//Si false, contexte d'affichage de la commande.
	if (!empty($achat))
	{
		//Fait une deep copy de l'objet panier pour pouvoir garder les données une fois le panier vider.
		$tmp = unserialize(serialize($panier));
		$numCommande = Commander($client, $panier);
		$panier = $tmp;
		//Actualise l'inventaire pour avoir la quantité en inventaire actuel pour les achats du panier.
		$panier->actualiseQteInventaire();

		//On récupère l'objet avec une copie de cette façon l'affichage facture/commande reste semblable.
	}

	require_once("./header.php");
	require_once("./sectionGauche.php");

}

?>


<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début des produits -->
	<div class="row">
	<?php if($panier->isEmpty()) : ?>
		<div class="alert alert-warning" role="alert"> <!-- Message indiquant un panier vide. -->
			<i class="fa fa-info-circle"></i>
			Aucun article dans votre commande.
			<a href="./">Continuer votre magasinage</a>
		</div>
	<?php else: ?>
		<?php if (!empty($achat)) : //Si achat est true, alors contexte facture?>
		<h2>Facture</h2>
		<!-- Informations du client. -->
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
		<?php else: //Sinon, contexte de la commande?>
		<h2>Votre commande</h2>
		<?php endif; ?>
		<!-- Information des produits du panier -->
		<table class="table">
			<thead>
				<?php if (empty($achat)) : //Si dans le contexte de la commande?>
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
			<!-- Les achats du panier -->
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
			<!-- Informations sur les taxes, sous total, total et frais. -->
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
		<?php if (!empty($achat)) : //Si dans le contexte de la facture?>
			<?php foreach($panier->getTabAchats() as $value) : ?>
				<?php if ($value->getQuantite() < 0) : ?>
					<div class="alert alert-warning" role="alert"> <!-- Message indiquant une rupture de stock. -->
						<i class="fa fa-exclamation-triangle"></i>
							<?php echo "<label>", $value->getNom(), "</label>"; ?> est en rupture de stock. Votre commande sera envoyé aussi tôt que possible.
					</div>
				<?php endif; ?>
			<?php endforeach; ?>	
		<!-- Numéro de commande -->		
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

	</div> 
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
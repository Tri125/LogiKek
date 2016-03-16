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

$sousTotal = 0.00;
$tvq = 0.00;
$tps = 0.00;
$total = 0.00;

global $maBD;

if (!isset($_SESSION['authentification']))
{
	//Redirection ?la page d'authentification.
	header("location:./authentification.php?prov=commande");
	exit();
}
else
{
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
				Votre panier est vide.
				<a href="./">Continuer votre magasinage</a>
			</div>
		<?php endif; ?>
		<h2>Votre commande</h2>
		<table class="table">
			<thead>
				<tr>
					<td class="nomProduit">Titre</td>
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
<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- D?ut Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
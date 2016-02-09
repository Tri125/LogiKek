<?php

require_once("./php/biblio/foncCommunes.php");

$panier = new Panier();

$sousTotal = 0.00;
$tvq = 0.00;
$tps = 0.00;
$total = 0.00;

if(isset($_GET['quoiFaire']))
{
	switch ($_GET['quoiFaire'])
	{
		case "ajout":
			$panier->ajouter($_GET['noProduit']);
			break;
		case "modification":
			break;
		case "suppression":
			$panier->suppression($_GET['no']);
			break;
		case "vider":
			break;
		default: 
			break;
	}
}


//$js = 'panierGestion.js';
$css = 'panierGestion.css';
$titre = 'LogiKek - Panier';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

require_once("./header.php");
require_once("./sectionGauche.php");

?>


<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">

	<!-- Début des produits -->
	<div class="row">
		<?php if(!$panier->isEmpty()) : ?>
			<table class="table">
				<thead>
					<tr>
						<td colspan="3">
							<h2>Panier</h2>
						</td>
					</tr>
				</thead>
				<tbody>
					<?php foreach($panier->getTabAchats() as $key=>$value): 
						$sousTotal += ($value->getPrix() * $value->getNombre());
					?>
					<tr>
						<td> <!-- L'article -->
							<a class="left supprimer" href="./panierGestion.php?quoiFaire=suppression&no=<?php echo $key; ?>">
								<i class="fa fa-trash"></i>
							</a>
							<div class="produit">
								<img class="img-responsive left" src="./img/produits/<?php echo $value->getCodeProduit(); ?>_small.png" alt="<?php echo $value->getNom(); ?>" onError="this.onerror=null;this.src='./img/produits/nonDispo_small.png';">
								<div class="wrapper">
									<p class="nomProduit"><?php echo $value->getNom(); ?></p>
								</div>
							</div>
						</td>
						<td class="quantite"> <!-- Quantite -->
							<input type="text" size="3" name="<?php echo ("quantite").$key ?>" value="<?php echo $value->getNombre(); ?>" maxlength="3"/>
							<div>Quantité</div>
						</td>
						<td class="prix-group"> <!-- Prix -->
							<ul>
								<li class="prix">
									<strong><?php echo intval($value->getPrix() * $value->getNombre()) ?></strong>
									<sup>.<?php $tmp = explode('.', number_format($value->getPrix() * $value->getNombre(), 2)); 
											echo $tmp[1];?>
									</sup>
									$
								</li>
								<li>
									<span>(<?php echo number_format($value->getPrix(), 2); ?>$ chaq.)</span>
								</li>
							</ul>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot class="fraisPrix">
					<tr>
						<td colspan="3">
							<span class="label">Sous total:</span>
							<span><?php echo number_format($sousTotal, 2) ?>$</span>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<span class="label">TPS:</span>
							<span><?php echo $tps = number_format($sousTotal * TPS, 2); ?>$</span>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<span class="label">TVQ:</span>
							<span><?php echo $tvq = number_format($sousTotal * TVQ, 2); ?>$</span>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<span class="label">Frais de génération de codes:</span>
							<span><?php echo number_format(FRAIS_CODE, 2); ?>$</span>
						</td>
					</tr>
					<tr>
						<td id="total" colspan="3">
							<span class="label">Total:</span>
							<span><?php echo number_format($total = $sousTotal + $tvq + $tps + FRAIS_CODE, 2) ?>$</span>
						</td>
					</tr>
				</tfoot>
			</table>
	<?php else : ?>
		<div class="alert alert-warning" role="alert">
			<i class="fa fa-info-circle"></i>
			Votre panier est vide.
		</div>
	<?php endif; ?>

<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
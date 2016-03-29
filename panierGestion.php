<?php

//-----------------------------
// Page qui gère le panier de commande du client et affiche le prix.
//-----------------------------

require_once(realpath(__DIR__).'/php/biblio/foncCommunes.php');

$panier = new Panier();

$sousTotal = 0.00;
$tvq = 0.00;
$tps = 0.00;
$total = 0.00;

//Swich sur le paramètre GET quoiFaire pour les actions possible au panier d'achat.
if(isset($_GET['quoiFaire']))
{
	switch ($_GET['quoiFaire'])
	{
		case "ajout":
			$panier->ajouter($_GET['noProduit']);
			//Redirige vers la page. Enlève les paramètres de l'URL pour empêcher un double de l'action au rafraichissement.
			header('location:panierGestion.php');
			exit();
			break;
		case "modification":
			if(isset($_POST))
			{
				//Le paramètre POST est un tableau correspondant aux articles du panier et leur nouvelles quantités à modifier.
				$_SESSION['panier-erreur'] = $panier->modifier($_POST);
			}
			//Redirige vers la page. Enlève les paramètres de l'URL pour empêcher un double de l'action au rafraichissement.
			header('location:panierGestion.php');
			exit();
			break;
		case "suppression":
			$panier->suppression($_GET['no']);
			//Redirige vers la page. Enlève les paramètres de l'URL pour empêcher un double de l'action au rafraichissement.
			header('location:panierGestion.php');
			exit();
			break;
		case "vider":
			$panier->vider();
			//Redirige vers la page. Enlève les paramètres de l'URL pour empêcher un double de l'action au rafraichissement.
			header('location:panierGestion.php');
			exit();
			break;
		default: 
			//Redirige vers la page. Enlève les paramètres de l'URL pour empêcher un double de l'action au rafraichissement.
			header('location:panierGestion.php');
			exit();
			break;
	}
}

$js = array();
$css = array();
$css[] = 'panierGestion.css';

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
			<!-- Tableau pour représenté le panier -->
			<table class="table">
				<!-- En-tête avec titre et boutons modifié qté. et vider. -->
				<thead>
					<tr>
						<td colspan="3">
							<h2>Panier</h2>
							<div class="btn-toolbar" role="toolbar" aria-label="...">
								<form id="modifierForm" method="POST" action="./panierGestion.php?quoiFaire=modification">
									<input type="submit" class="btn" value="Modifier qté."> 
								</form>
								<form id="viderForm" method="POST" action="./panierGestion.php?quoiFaire=vider">
									<input type="submit" class="btn" value="Vider"> 
								</form>
							</div>
							<?php if(isset($_SESSION['panier-erreur']) && $_SESSION['panier-erreur'] === TRUE) : ?>
							<!-- Message d'erreur si la quantité modifié n'est pas un entier positif. -->
							<div class="alert alert-danger" role="alert">
								<i class="fa fa-exclamation-triangle"></i>
								La quantité doit être numérique et positive. Pour supprimer un article du panier utilisez le bouton de corbeille.
							</div>
							<?php unset($_SESSION['panier-erreur']); endif; //unset la variable de session pour qu'au rafraichissement/changement de page, l'erreur ne soit plus affiché
							?>
						</td>
					</tr>
				</thead>
				<!-- Corps du tableau contenant tout les achats du panier. -->
				<tbody>
					<?php foreach($panier->getTabAchats() as $key=>$value): 
						//On calcul le sous total dans l'itération du foreach.
						$sousTotal += ($value->getPrix() * $value->getNombre());
					?>
					<tr>
						<td> <!-- L'article -->
							<a class="left supprimer" href="./panierGestion.php?quoiFaire=suppression&no=<?php echo $key; ?>"> <!-- Image de corbeille pour supprimer l'article du panier. -->
								<i class="fa fa-trash"></i>
							</a>
							<div class="produit"> <!-- Image et nom du produit -->
								<img class="img-responsive left" src="./img/produits/<?php echo $value->getCodeProduit(); ?>_small.png" alt="<?php echo $value->getNom(); ?>" onError="this.onerror=null;this.src='./img/produits/nonDispo_small.png';">
								<div class="wrapper">
									<p class="nomProduit"><?php echo $value->getNom(); ?></p>
								</div>
							</div>
						</td>
						<td class="quantite"> <!-- Quantite -->
							<input type="text" form="modifierForm" size="3" name="<?php echo ("quantite").$key ?>" value="<?php echo $value->getNombre(); ?>" maxlength="3"/> <!-- Champs pour modifier la quantité commandé.-->
							<div>Quantité</div>
						</td>
						<td class="prix-group"> <!-- Prix -->
							<ul>
								<li class="prix"> <!-- Prix total de ce produit (avec qté.) -->
									<strong><?php echo intval($value->getPrix() * $value->getNombre()) //Affiche la partie entier ?></strong>
									<sup>.<?php $tmp = explode('.', number_format($value->getPrix() * $value->getNombre(), 2)); //Crée un tableau avec le prix affiché sur deux décimal où l'index 0 correspond à la partie entière (avant le décimal) et l'index 1 est la partie décimal. 
											echo $tmp[1];?>
									</sup>
									$
								</li>
								<li> <!-- Prix unitaire -->
									<span>(<?php echo number_format($value->getPrix(), 2); ?>$ chaq.)</span>
								</li>
							</ul>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot class="fraisPrix"> <!-- Pied du tableau contenant le sous total, taxes, total, etc. -->
					<tr>
						<td colspan="3">
							<span class="label">Sous total:</span>
							<span><?php echo number_format($sousTotal, 2) ?>$</span>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<span class="label">TPS:</span>
							<span><?php echo $tps = number_format($sousTotal * TPS, 2); //Assigne et affiche en une ligne ?>$</span>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<span class="label">TVQ:</span>
							<span><?php echo $tvq = number_format($sousTotal * TVQ, 2); //Assigne et affiche en une ligne ?>$</span>
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
							<span><?php echo number_format($total = $sousTotal + $tvq + $tps + FRAIS_CODE, 2) //Assigne et affiche en une ligne ?>$</span>
						</td>
					</tr>
				</tfoot>
			</table>
			<div class="btn-toolbar pull-right" role="group" aria-label="...">
				<form id="commanderForm" method="POST" action="./commander.php"> <!-- Formulaire commander -->
					<input type="submit" class="btn" value="Commander"> 
				</form>
			</div>
	<?php else : //Si le panier est vide ?>
		<h2>Panier</h2>
		<div class="alert alert-warning" role="alert"> <!-- Message indiquant un panier vide. -->
			<i class="fa fa-info-circle"></i>
			Votre panier est vide.
		</div>
	<?php endif; ?>
	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
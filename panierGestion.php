<?php

require_once("./php/biblio/foncCommunes.php");

$panier = new Panier();

if(isset($_GET['quoiFaire']))
{
	switch ($_GET['quoiFaire'])
	{
		case "ajout":
			$panier->ajouter($_GET['noProduit']);
			break;
		case "modification":
			break;
		case "supression":
			break;
		case "vider":
			break;
		default: 
			break;
	}
}


$js = 'panierGestion.js';
$css = 'panierGestion.css';
$titre = 'LogiKek - Panier';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

require_once("./header.php");
require_once("./sectionGauche.php");

?>


<!-- Début section central col-md-9 -->
<div class="col-md-8" id="centre">
	<div class="pub">
		<h2>Panier</h2>
	</div>
	<!-- Début des produits -->
	<div class="row">
		<table class="table">
			<thead>
				<tr>
					<td colspan="3">
						<h2>Panier</h2>
					</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td> <!-- L'article -->
						<input class="left" type="checkbox" name="chkProduit"></input>
						<div class="produit">
							<img class="img-responsive left" src="https://ssl-images.newegg.com/ProductImageCompressAll/35-608-026-02.jpg"></img>
							<div class="wrapper">
								<p class="nomProduit">Noctua NF</p>
							</div>
						</div>
					</td>
					<td width="50px"> <!-- Quantite -->
						<input class="" type="text" size="3" maxlength="3"></input>
					</td>
					<td width="220px" align="right"> <!-- Prix -->
						<p class="prix">$
							<strong>34</strong>
							<sup>.99</sup>
						</p>
					</td>
				</tr>
			</tbody>
			<tfoot>
			</tfoot>
		</table>


<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
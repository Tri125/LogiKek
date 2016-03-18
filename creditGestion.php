<?php 
require_once("./php/biblio/foncCommunes.php");

$js = array();
$js[] = 'validationAchat.js';
$css = array();
$css[] = 'creditGestion.css';
$titre = 'LogiKek - Saisie carte de crédit';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';


function MoisSelect()
{
	echo "<select id='mois' name='mois'>";
	for ($i = 1; $i <= 12; $i++)
	{
		$numFormat = sprintf("%02d", $i);
		echo "<option value='$i' label='$numFormat'>";
	}
	echo "</select>";
}

function AnneeSelect()
{
	$annee = date("Y");

	echo "<select id='annee' name='annee'>";
	for ($i = $annee; $i < ($annee + 8); $i++)
	{
		echo "<option value='$i' label='$i'>";
	}
	echo "</select>";
}

require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début des produits -->
	<div class="row">
		<h3>Complétez les informations</h3>
		<form method="POST" action="#" onsubmit="return ValiderCarte();">
		<table>
			<tr>
				<td id="cartes">
					<input type="radio" name="carte" value="visa">
					<img src="./img/cartes/visa.svg" alt="Image Visa">
					<input type="radio" name="carte" value="mastercard">
					<img src="./img/cartes/mastercard.svg" alt="Image Mastercard">
					<input type="radio" name="carte" value="amex">
					<img src="./img/cartes/amex.svg" alt="Image Amex">
				</td>
			</tr>
			<tr>
				<td>
					<label for="txtNumero">Saisissez le numéro sans espace</label>
				</td>
			</tr>
			<tr>
				<td>
					<input id="txtNumero" type="text" name="numero">
				</td>
			</tr>
			<tr>
				<td>
					<label>Date d'expiration</label>
				</td>
			</tr>
			<tr>
				<td>
					<label>Mois:</label>
					<?php MoisSelect(); ?>
					<label>Année:</label>
					<?php AnneeSelect(); ?>
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" value="Confirmer">
				</td>
			</tr>
		</table>
		</form>


<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
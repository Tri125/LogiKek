<?php 

//---------------------------------------------
// Page pour saisir les informations d'une carte de crédit
// pour confirmer un achat.
//---------------------------------------------
require_once(realpath(__DIR__).'/php/biblio/foncCommunes.php');

$js = array();
$js[] = 'validationAchat.js';
$css = array();
$css[] = 'creditGestion.css';
$titre = 'LogiKek - Saisie carte de crédit';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';


//---------------------------------------------
// Génère le HTML pour le champ select dans le formulaire du mois d'expiration.
//---------------------------------------------
function MoisSelect()
{
	echo "<select id='mois' name='mois'>";
	for ($i = 1; $i <= 12; $i++)
	{
		//Pour afficher le mois sous le format: 01, 02, [...], 12.
		$numFormat = sprintf("%02d", $i);
		echo "<option value='$i' label='$numFormat'>";
	}
	echo "</select>";
}

//---------------------------------------------
// Génère le HTML pour le champ select dans le formulaire de l'année d'expiration.
//---------------------------------------------
function AnneeSelect()
{
	//Récupère l'année actuelle.
	$annee = date("Y");

	echo "<select id='annee' name='annee'>";
	for ($i = $annee; $i < ($annee + 8); $i++)
	{
		echo "<option value='$i' label='$i'>";
	}
	echo "</select>";
}

//Si le script s'appel lui même avec le paramètre POST 'achat'
if(isset($_POST['achat']))
{
	//Le met à vrai (sera utilisé dans commander.php)
	$_SESSION['achat'] = true;
	//Retourne vers la page commander.php pour l'affichage de la facture.
	header("location:./commander.php");
	exit();
}

if (isset($_SESSION['authentification']) && isset($_POST['confirmer']))
{

	require_once("./header.php");
	require_once("./sectionGauche.php");
}
else
{
	header("location:./commander.php");
	exit();
}

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début du formulaire -->
	<div class="row">
		<h3>Complétez les informations</h3>
		<!-- Message d'erreur de validité de la carte de crédit -->
		<div id="messageErreur" class="alert alert-danger" role="alert">
			<i class="fa fa-exclamation-triangle"></i>
			Numéro de carte de crédit ou date d'expiration invalide.
		</div>
		<form method="POST" action="./creditGestion.php" onsubmit="return ValiderCarte();">
			<table>
				<!-- Sélection du type de cartes-->
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
					<!-- Champs d'expiration du mois et de l'année -->
					<td>
						<label>Mois:</label>
						<?php MoisSelect(); ?>
						<label>Année:</label>
						<?php AnneeSelect(); ?>
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="achat" value="Confirmer">
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
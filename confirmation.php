<?php 


//-----------------------------
// Script pour présenté une confirmation des données d'un compte client
// avant de sa création/modification.
//-----------------------------

require_once(realpath(__DIR__).'/php/biblio/foncCommunes.php');

$js = array();

$css = array();
$css[] = 'formulaire.css';
$titre = 'LogiKek - Confirmation';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

$client;

//Si nous avons un client dans la variable de session on le récupère.
if (isset($_SESSION['client']))
	$client = $_SESSION['client'];
//Sinon, c'est qu'il a navigué directement à la page et sans être authentifié.
else
{
	//Redirection vers la page d'inscription/dossier.
	header("location:./inscription.php");
	exit();
}

require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">

	<!-- Début du formulaire -->
	<div class="row">
		<table>
			<tr>
				<td class="centrer" colspan="2">
					<h3>Voici les données fournies</h3>
				</td>
			</tr>
			<!-- Genre -->
			<tr>
				<td class="droit">
					<label>Genre:</label>
				</td>
				<td>
					<span class="label"><?php echo $client->getSexe(); ?></span>
				</td>
			</tr>
			<!-- Nom -->
			<tr>
				<td class="droit">
					<label>Nom:</label>
				</td>
				<td>
					<span class="label"><?php echo $client->getNom(); ?></span>
				</td>
			</tr>
			<!-- Prénom -->
			<tr>
				<td class="droit">
					<label>Prénom:</label>
				</td>
				<td>
					<span class="label"><?php echo $client->getPrenom(); ?></span>
				</td>
			</tr>
			<!-- Courriel -->
			<tr>
				<td class="droit">
					<label>Courriel:</label>
				</td>
				<td>
					<span class="label"><?php echo $client->getCourriel(); ?></span>
				</td>
			</tr>
			<!-- Adresse -->
			<tr>
				<td class="droit">
					<label>Adresse:</label>
				</td>
				<td>
					<span class="label"><?php echo $client->getAdresse(); ?></span>
				</td>
			</tr>
			<!-- Ville -->
			<tr>
				<td class="droit">
					<label>Ville:</label>			
				</td>
				<td>
					<span class="label"><?php echo $client->getVille(); ?></span>
				</td>
			</tr>
			<!-- Code postal -->
			<tr>
				<td class="droit">
					<label>Code postal:</label>
				</td>
				<td>
					<span class="label"><?php echo strToUpper($client->getCodePostal()); ?></span>
				</td>
			</tr>
			<!-- Province -->
			<tr>
				<td class="droit">
					<label>Province:</label>
				</td>
				<td>
					<span class="label"><?php echo $client->getProvince(); ?></span>
				</td>
			</tr>
			<!-- Téléphone -->
			<tr>
				<td class="droit">
					<label>Téléphone:</label>
				</td>
				<td>
					<span class="label"><?php echo $client->getTelephone(); ?></span>
				</td>
			</tr>
			<!-- Nom d'utilisateur -->
			<tr>
				<td class="droit">
					<label>Nom d'utilisateur:</label>
				</td>
				<td>
					<span class="label"><?php echo $client->getNomUtilisateur(); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<!-- Bouton pour soumettre -->
			<tr>
				<td class="centrer" colspan="2">
					<form id="confirmerForm" method="POST" action="./creationDossier.php">
						<input type="submit" name="valider" value="Confirmer">
					</form>
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<!-- Lien pour retourner à la page d'inscription pour modifier le compte client. -->
			<tr>
				<td class="centrer" colspan="2">
					<a href="./inscription.php">Retour à la page d'inscription</a>
				</td>
			</tr>
		</table>


<!-- Contenu principal -->


	</div> 	<!-- Fin du formulaire -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
<?php 
require_once("./php/biblio/foncCommunes.php");

$js = array();

$css = array();
$css[] = 'confirmation.css';
$titre = 'LogiKek';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

$client;

if (isset($_SESSION['client']))
	$client = $_SESSION['client'];

require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">

	<!-- Début des produits -->
	<div class="row">
		<h3>Voici les données fournies</h3>
		<table>
			<tr>
				<td colspan="2">
					<span class="label">Genre:</span>
					<span class="label"><?php echo $client->getSexe(); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<span class="label">Nom:</span>
					<span class="label"><?php echo $client->getNom(); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<span class="label">Prénom:</span>
					<span class="label"><?php echo $client->getPrenom(); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<span class="label">Courriel:</span>
					<span class="label"><?php echo $client->getCourriel(); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<span class="label">Adresse:</span>
					<span class="label"><?php echo $client->getAdresse(); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<span class="label">Ville:</span>
					<span class="label"><?php echo $client->getVille(); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<span class="label">Code postal:</span>
					<span class="label"><?php echo strToUpper($client->getCodePostal()); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<span class="label">Province:</span>
					<span class="label"><?php echo $client->getProvince(); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<span class="label">Téléphone:</span>
					<span class="label"><?php echo $client->getTelephone(); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<span class="label">Nom d'utilisateur:</span>
					<span class="label"><?php echo $client->getNomUtilisateur(); ?></span>
				</td>
			</tr>
		</table>
		<div class="btn-toolbar" role="group" aria-label="...">
			<form id="confirmerForm" method="POST" action="./creationDossier.php">
				<input type="submit" class="btn" value="Confirmer"> 
			</form>
		</div>
		<div>
			<a href="./inscription.php">Retour à la page d'inscription</a>
		</div>


<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
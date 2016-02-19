<?php 
require_once("./php/biblio/foncCommunes.php");

$js = array();

$css = array();
$css[] = 'index.css';
$titre = 'LogiKek - inscription';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

require_once("./header.php");
require_once("./sectionGauche.php");

$valide = true;
$client;

if (isset($_POST['valider'])) //On arrive du bouton Valider, inscription à valider.
{
	$tabClient = array();
	//Retire les champs de mot de passe et le champ valider (le bouton submit) du POST.
	$tabClient = array_slice($_POST, 0, 10);
	$client = new Client($tabClient);
}
elseif (isset($_SESSION['client'])) //Client déjà authentifié
{
	$client = $_SESSION['client'];
}
else //Un nouveau client désire s'inscrire
{
	$client = new Client(array());
}
?>

<!-- Début section central col-md-9 -->
<div class="col-md-8" id="centre">

	<!-- Début des produits -->
	<div class="row">
		<form id="formInscription" action="inscription.php" method="POST">
			<input type="radio" name="sexe" value="F" <?php echo ( ($client->getSexe() == 'F') ? 'checked' : '' );?>>Femme<br>
			<input type="radio" name="sexe" value="M" <?php echo ( ($client->getSexe() == 'M') ? 'checked' : '' );?>>Homme<br>
			<input type="text" name="nom" value="<?php echo $client->getNom(); ?>">Nom<br>
			<input type="text" name="prenom" value="<?php echo $client->getPrenom(); ?>">Prenom<br>
			<input type="text" name="courriel" value="<?php echo $client->getCourriel(); ?>">Courriel<br>
			<input type="text" name="adresse" value="<?php echo $client->getAdresse(); ?>">Adresse<br>
			<input type="text" name="ville" value="<?php echo $client->getVille(); ?>">Ville<br>
			<input type="text" name="codePostal" value="<?php echo $client->getCodePostal(); ?>">Code Postal<br>
			<select name="province">
				<option value="QC" <?php echo ( ($client->getProvince() == 'QC') ? 'selected' : '' );?>>Québec</option>
				<option value="ON" <?php echo ( ($client->getProvince() == 'ON') ? 'selected' : '' );?>>Ontario</option>
				<option value="AB" <?php echo ( ($client->getProvince() == 'AB') ? 'selected' : '' );?>>Alberta</option>
				<option value="BC" <?php echo ( ($client->getProvince() == 'BC') ? 'selected' : '' );?>>Colombie-Britannique</option>
				<option value="PE" <?php echo ( ($client->getProvince() == 'PE') ? 'selected' : '' );?>>Ile-du-Prince-Édouard</option>
				<option value="MB" <?php echo ( ($client->getProvince() == 'MB') ? 'selected' : '' );?>>Manitoba</option>
				<option value="NB" <?php echo ( ($client->getProvince() == 'NB') ? 'selected' : '' );?>>Nouveau-Brunswick</option>
				<option value="NS" <?php echo ( ($client->getProvince() == 'NS') ? 'selected' : '' );?>>Nouvelle-Écosse</option>
				<option value="NU" <?php echo ( ($client->getProvince() == 'NU') ? 'selected' : '' );?>>Nunavut</option>
				<option value="SK" <?php echo ( ($client->getProvince() == 'SK') ? 'selected' : '' );?>>Saskatchewan</option>
				<option value="NL" <?php echo ( ($client->getProvince() == 'NL') ? 'selected' : '' );?>>Terre-Neuve et Labrador</option>
				<option value="NT" <?php echo ( ($client->getProvince() == 'NT') ? 'selected' : '' );?>>Territoires du Nord-Ouest</option>
				<option value="YU" <?php echo ( ($client->getProvince() == 'YU') ? 'selected' : '' );?>>Yukon</option>
             </select>Province<br>
			<input type="text" name="telephone" value="<?php echo $client->getTelephone(); ?>">Numéro de téléphone<br>
			<input type="text" name="nomUtilisateur" value="<?php echo $client->getNomUtilisateur(); ?>">Nom d'utilisateur<br>
			<input type="password" name="motDePasse" maxlength="15">Mot de passe<br>
			<input type="password" name="confirm" maxlength="15">Confirmation du mot de passe<br>
			<input type="submit" name="valider" value="S'inscrire">
		</form>


<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
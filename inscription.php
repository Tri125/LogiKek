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

//-----------------------------
//Function pour afficher la liste déroulante des provinces
//-----------------------------
function afficherProvince($provParam)
{
	$provinces = array(
		'QC' => 'Québec', 'ON' => 'Ontario', 'AB' => 'Alberta'
		, 'BC' => 'Colombie-Britannique', 'PE' => 'Ile-du-Prince-Édouard'
		, 'MB' => 'Manitoba', 'NB' => 'Nouveau-Brunswick'
		, 'NS' => 'Nouvelle-Écosse', 'NU' => 'Nunavut'
		, 'SK' => 'Saskatchewan', 'NL' => 'Terre-Neuve et Labrador'
		, 'NT' => 'Territoires du Nord-Ouest', 'YU' => 'Yukon');
		
		echo "<select name='province'>";
		foreach ($provinces as $cle => $valeur)
		{
			echo "<option value='$cle'";
			//Si le client à déjà une province, on le sélectionne
			if ($provParam == $cle)
				echo "selected";
			echo ">$valeur</option>";
		}
		echo "</select>";
}


function validation()
{
	
}
?>

<!-- Début section central col-md-9 -->
<div class="col-md-8" id="centre">

	<!-- Début des produits -->
	<div class="row">
		<form id="formInscription" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
			<input type="radio" name="sexe" value="F" <?php echo ( ($client->getSexe() == 'F') ? 'checked' : '' );?>>Femme<br>
			<input type="radio" name="sexe" value="M" <?php echo ( ($client->getSexe() == 'M') ? 'checked' : '' );?>>Homme<br>
			<input type="text" name="nom" value="<?php echo $client->getNom(); ?>">Nom<br>
			<input type="text" name="prenom" value="<?php echo $client->getPrenom(); ?>">Prenom<br>
			<input type="text" name="courriel" value="<?php echo $client->getCourriel(); ?>">Courriel<br>
			<input type="text" name="adresse" value="<?php echo $client->getAdresse(); ?>">Adresse<br>
			<input type="text" name="ville" value="<?php echo $client->getVille(); ?>">Ville<br>
			<input type="text" name="codePostal" value="<?php echo $client->getCodePostal(); ?>">Code Postal<br>
			<?php afficherProvince($client->getProvince()); ?>
			Province<br>
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
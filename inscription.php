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

//Messages d'erreur
$msgRequis = "Champ requis";
$msgSexe = "";
$msgNom = "";
$msgPrenom = "";
$msgCourriel = "";
$msgAdresse = "";
$msgVille = "";
$msgCodePostal = "";
//$msgProvince = "";
$msgTelephone = "";
$msgNomUtilisateur = "";
$msgMotDePasse = "";
$msgConfirm = "";

if (isset($_POST['valider'])) //On arrive du bouton Valider, inscription à valider.
{
	$tabClient = array();

	foreach ($_POST as $cle => $valeur)
	{
		$tabClient[$cle] = desinfecte($valeur);
	}

	if (empty($tabClient['sexe']))
		$msgSexe = $msgRequis;

	if (empty($tabClient['nom']))
		$msgNom = $msgRequis;
	else
	{
		//Nom et prénom: au moins 2 caractères parmi lettres, tiret, espace, apostrophe et point
		if (!preg_match("/^[a-zA-Z -'`.]{2,}$/", $tabClient['nom']))
			$msgNom = 'Min. 2 caractères valides';
	}

	if (empty($tabClient['prenom']))
		$msgPrenom = $msgRequis;
	else
	{
		//Nom et prénom: au moins 2 caractères parmi lettres, tiret, espace, apostrophe et point
		if (!preg_match("/^[a-zA-Z -'`.]{2,}$/", $tabClient['prenom']))
			$msgPrenom = 'Min. 2 caractères valides';
	}

	if (empty($tabClient['courriel']))
		$msgCourriel = $msgRequis;
	else
	{
		if (!filter_var($tabClient['courriel'], FILTER_VALIDATE_EMAIL))
			$msgCourriel = 'Courriel invalide';
	}

	if (empty($tabClient['adresse']))
		$msgAdresse = $msgRequis;
	else
	{
		//Adresse et ville: au moins 3 caractères parmi lettres, chiffres, tiret, espace, apostrophe et point
		if (!preg_match("/^[a-zA-Z0-9 -'`.]{3,}$/", $tabClient['adresse']))
			$msgAdresse = 'Min. 3 caractères valides';
	}

	if (empty($tabClient['ville']))
		$msgVille = $msgRequis;
	else
	{
		//Adresse et ville: au moins 3 caractères parmi lettres, chiffres, tiret, espace, apostrophe et point
		if (!preg_match("/^[a-zA-Z0-9 -'`.]{3,}$/", $tabClient['ville']))
			$msgVille = 'Min. 3 caractères valides';
	}

	if (empty($tabClient['codePostal']))
		$msgCodePostal = $msgRequis;
	else
	{
		//Code postal selon le modèle A9A9A9. Ne doit contenir aucune des lettres DFIOQU
		if (!preg_match("/^((?![DFIOQU])([A-Z][0-9])){3}$/", $tabClient['codePostal']))
			$msgCodePostal = 'Sans espace et modèle A9A9A9';
	}

	if (empty($tabClient['telephone']))
		$msgTelephone = $msgRequis;
	else
	{
		//Numéro de téléphone: 10 chiffres, optionnellement encadrés ainsi: (xxx)yyy-zzzz
		/* Explication: Parenthèse et tiret optionnel sur tout le champ, avec espace optionnel après l'indicatif régionnal. (?(1)\) Regarde s'il y avait un match dans le premier group (première parenthèse ouvrante optionnel)
		si oui, on fait un match sur la parenthèse fermante (IF ?(1) THEN \) ). Ensuite un espace optionnel après ce groupe et on écrit le reste du pattern.
		À partir de la bar vertical "|" c'est la partie ELSE du conditionnel. Le deuxième groupe est déjà présent (le premier [0-9]), mais l'espace optionnel après la parenthèse fermante fait partie du conditionnel, que nous n'avons pas passé le teste. Donc, il faut mettre l'espace conditionnel au début cette fois. Après, match trivial de numéro et tiret optionnel.

		*/
		if (!preg_match("/^(\()?[0-9]{3}(?(1)\)( )?[0-9]{3}(-)?[0-9]{4}|(( )?)[0-9]{3}(-)?[0-9]{4})$/", $tabClient['telephone']))
			$msgTelephone = 'Modèle invalide (xxx)yyy-zzzz)';
	}

	if (empty($tabClient['nomUtilisateur']))
		$msgNomUtilisateur = $msgRequis;
	else
	{
		//Nom d'usager et mot de passe: au moins 5 caractères parmi lettres et chiffres
		if (!preg_match("/^[A-Za-z0-9]{5,}$/", $tabClient['nomUtilisateur']))
			$msgNomUtilisateur = 'Min. 5 caractères valides';
	}

	if (empty($tabClient['motDePasse']))
	{
		$msgMotDePasse = $msgRequis;
	}
	else
	{
		//Nom d'usager et mot de passe: au moins 5 caractères parmi lettres et chiffres
		if (!preg_match("/^[A-Za-z0-9]{5,}$/", $tabClient['motDePasse']))
			$msgMotDePasse = 'Min. 5 caractères valides';
	}

	if (empty($tabClient['confirm']))
	{
		$msgConfirm = $msgRequis;
	}
	else
	{
		//Nom d'usager et mot de passe: au moins 5 caractères parmi lettres et chiffres
		if (!preg_match("/^[A-Za-z0-9]{5,}$/", $tabClient['confirm']))
			$msgConfirm = 'Min. 5 caractères valides';
	}


	//Sans mot de passe
	$tabClient = array_slice($tabClient, 0, -3);

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
//Fonction pour afficher la liste déroulante des provinces
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


?>

<!-- Début section central col-md-9 -->
<div class="col-md-8" id="centre">

	<!-- Début des produits -->
	<div class="row">
		<form id="formInscription" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
			<span class="error">* <?php echo $msgSexe;?></span><br>
			<input type="radio" name="sexe" value="F" <?php echo ( ($client->getSexe() == 'F') ? 'checked' : '' );?>>Femme<br>
			<input type="radio" name="sexe" value="M" <?php echo ( ($client->getSexe() == 'M') ? 'checked' : '' );?>>Homme<br>
			<span class="error">* <?php echo $msgNom;?></span><br>
			<input type="text" name="nom" value="<?php echo $client->getNom(); ?>">Nom<br>
			<span class="error">* <?php echo $msgPrenom;?></span><br>
			<input type="text" name="prenom" value="<?php echo $client->getPrenom(); ?>">Prenom<br>
			<span class="error">* <?php echo $msgCourriel;?></span><br>
			<input type="text" name="courriel" value="<?php echo $client->getCourriel(); ?>">Courriel<br>
			<span class="error">* <?php echo $msgAdresse;?></span><br>
			<input type="text" name="adresse" value="<?php echo $client->getAdresse(); ?>">Adresse<br>
			<span class="error">* <?php echo $msgVille;?></span><br>
			<input type="text" name="ville" value="<?php echo $client->getVille(); ?>">Ville<br>
			<span class="error">* <?php echo $msgCodePostal;?></span><br>
			<input type="text" name="codePostal" value="<?php echo $client->getCodePostal(); ?>">Code Postal<br>
			<?php afficherProvince($client->getProvince()); ?>
			Province<br>
			<span class="error">* <?php echo $msgTelephone;?></span><br>
			<input type="text" name="telephone" value="<?php echo $client->getTelephone(); ?>">Numéro de téléphone<br>
			<span class="error">* <?php echo $msgNomUtilisateur;?></span><br>
			<input type="text" name="nomUtilisateur" value="<?php echo $client->getNomUtilisateur(); ?>">Nom d'utilisateur<br>
			<span class="error">* <?php echo $msgMotDePasse;?></span><br>
			<input type="password" name="motDePasse" maxlength="15">Mot de passe<br>
			<span class="error">* <?php echo $msgConfirm;?></span><br>
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
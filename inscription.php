<?php 
require_once("./php/biblio/foncCommunes.php");

$js = array();

$css = array();
$css[] = 'inscription.css';
$titre = 'LogiKek - inscription';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

require_once("./header.php");
require_once("./sectionGauche.php");

$valide = true;
$client;

$messages = array(
	'sexe' => '', 'nom' => '', 'prenom' => ''
	, 'courriel' => '', 'adresse' => ''
	, 'ville' => '', 'codePostal' => ''
	, 'telephone' => '', 'nomUtilisateur' => ''
	, 'motDePasse' => '', 'confirm' => ''
);

//Messages d'erreur affiché pour un champs vide qui est requis.
$msgRequis = "Champ requis";

if (isset($_POST['valider'])) //On arrive du bouton Valider, inscription à valider.
{
	$tabClient = array();

	foreach ($_POST as $cle => $valeur)
	{
		$tabClient[$cle] = desinfecte($valeur);
	}

	//Vérifie manuellement si le champs genre est set et non vide, car il n'est pas transmit dans le POST si l'utilisateur ne le choisit pas.
	//Les autres champs sont vérifiés dans un foreach
	if (empty($tabClient['sexe']))
	{
		$messages['sexe'] = $msgRequis;
		$valide = false;
	}
	else
	{
		//N'est pas F ou M
		if (!preg_match("/^[FM]$/", $tabClient['sexe']))
		{
			$messages['sexe'] = 'Option invalide';
			$valide = false;
		}
	}


	//Nom et prénom: au moins 2 max 20 caractères parmi lettres, tiret, espace, apostrophe et point
	if (!preg_match("/^[a-zA-Z \-\'.]{2,20}$/", $tabClient['nom']))
	{
		$messages['nom'] = 'Min. 2 caractères valides';
		$valide = false;
	}


	//Nom et prénom: au moins 2 max 20 caractères parmi lettres, tiret, espace, apostrophe et point
	if (!preg_match("/^[a-zA-Z \-'.]{2,20}$/", $tabClient['prenom']))
	{
		$messages['prenom'] = 'Min. 2 caractères valides';
		$valide = false;
	}


	if (!filter_var($tabClient['courriel'], FILTER_VALIDATE_EMAIL))
	{
		$messages['courriel'] = 'Courriel invalide';
		$valide = false;
	}


	//Adresse et ville: au moins 3 caractères parmi lettres, chiffres, tiret, espace, apostrophe et point, maximum 40.
	if (!preg_match("/^[a-zA-Z0-9 \-'.]{3,40}$/", $tabClient['adresse']))
	{
		$messages['adresse'] = 'Min. 3 caractères valides';
		$valide = false;
	}


	//Adresse et ville: au moins 2 caractères max 20 parmi lettres, chiffres, tiret, espace, apostrophe et point
	if (!preg_match("/^[a-zA-Z0-9 \-'.]{2,20}$/", $tabClient['ville']))
	{
		$messages['ville'] = 'Min. 2 caractères valides';
		$valide = false;
	}


	//Code postal selon le modèle A9A9A9. Ne doit contenir aucune des lettres DFIOQU
	//Look ahead negatif "?!" du groupe [DFIOQU], regarde si le prochain group ne contient pas un match de ce groupe.
	if (!preg_match("/^((?![DdFfIiOoQqUu])([A-Za-z][0-9])){3}$/", $tabClient['codePostal']))
	{
		$messages['codePostal'] = 'Sans espace et modèle A9A9A9';
		$valide = false;
	}


	//Numéro de téléphone: 10 chiffres, optionnellement encadrés ainsi: (xxx)yyy-zzzz

	/* Explication: Parenthèse et tiret optionnel sur tout le champ, avec espace optionnel après l'indicatif régionnal. (?(1)\) Regarde s'il y avait un match dans le premier group (première parenthèse ouvrante optionnel)
	si oui, on fait un match sur la parenthèse fermante (IF ?(1) THEN \) ). Ensuite un espace optionnel après ce groupe et on écrit le reste du pattern.
	À partir de la bar vertical "|" c'est la partie ELSE du conditionnel. Le deuxième groupe est déjà présent (le premier [0-9]), mais l'espace optionnel après la parenthèse fermante fait partie du conditionnel, que nous n'avons pas passé le teste. Donc, il faut mettre l'espace conditionnel au début cette fois. Après, match trivial de numéro et tirets et espaces optionnels.
	*/
	if (!preg_match("/^(\()?[0-9]{3}(?(1)\)[ -]?[0-9]{3}[- ]?[0-9]{4}|([ -]?)[0-9]{3}[ -]?[0-9]{4})$/", $tabClient['telephone']) || 
		preg_match("/^[0-9]{6}[ -]{1}[0-9]{4}$/", $tabClient['telephone'])) 
		//Plus simple d'avoir une autre regex pour vérifier le cas DDDDDD DDDD et DDDDDD-DDDD qu'on ne veux pas accepter.
	{
		$messages['telephone'] = 'Modèle invalide (xxx)yyy-zzzz)';
		$valide = false;
	}


	//Nom d'usager et mot de passe: au moins 5 caractères max 10 parmi lettres et chiffres
	if (!preg_match("/^[A-Za-z0-9]{5,10}$/", $tabClient['nomUtilisateur']))
	{
		$messages['nomUtilisateur'] = 'Min. 5 caractères valides';
		$valide = false;
	}


	//Nom d'usager et mot de passe: au moins 5 caractères max 10 parmi lettres et chiffres
	if (!preg_match("/^[A-Za-z0-9]{5,10}$/", $tabClient['motDePasse']))
	{
		$messages['motDePasse'] = 'Min. 5 caractères valides';
		$valide = false;
	}


	if (!preg_match("/^[A-Za-z0-9]{5,10}$/", $tabClient['confirm']))
	{
		//Nom d'usager et mot de passe: au moins 5 caractères max 10 parmi lettres et chiffres
		$messages['confirm'] = 'Min. 5 caractères valides';
		$valide = false;
	}
	else
	{
		//Mot de passe de confirmation n'est pas le même que le mot de passe
		if ($tabClient['confirm'] != $tabClient['motDePasse'])
		{
			$messages['confirm'] = 'N\'est pas identique au mot de passe.';
			$valide = false;
		}
	}

	//Vérifie si chacun des champs est set et non vide.
	//Si oui, on met le message correspondant qui signal un champs requis.
	foreach ($tabClient as $key => $value) 
	{
		if (empty($value))
		{
			$messages[$key] = $msgRequis;
			$valide = false;
		}
	}

	$tabClient['codePostal'] = strToUpper($tabClient['codePostal']);
	//Sans champs de confirmation du mot de passe et sans le bouton valider dans le POST.
	$tabClient = array_slice($tabClient, 0, -2);

	$client = new Client($tabClient);

	if ($valide)
	{
		$_SESSION['client'] = $client;	
		header("location:confirmation.php");
		exit();
		//echo "<meta http-equiv='Refresh' content='0;url=confirmation.php' />";
	}
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
	<?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'doublon'): ?>
		<div class="alert alert-danger" role="alert">
			<i class="fa fa-exclamation-triangle"></i>
			Nom d'utilisateur déjà existant.
		</div>
	<?php endif; ?>
		<form id="formInscription" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
			<span class="erreur">* <?php echo $messages['sexe'];?></span><br>
			<input type="radio" name="sexe" value="F" <?php echo ( ($client->getSexe() == 'F') ? 'checked' : '' );?>>Femme<br>
			<input type="radio" name="sexe" value="M" <?php echo ( ($client->getSexe() == 'M') ? 'checked' : '' );?>>Homme<br>
			<span class="erreur">* <?php echo $messages['nom'];?></span><br>
			<input type="text" name="nom" value="<?php echo $client->getNom(); ?>">Nom<br>
			<span class="erreur">* <?php echo $messages['prenom'];?></span><br>
			<input type="text" name="prenom" value="<?php echo $client->getPrenom(); ?>">Prenom<br>
			<span class="erreur">* <?php echo $messages['courriel'];?></span><br>
			<input type="text" name="courriel" value="<?php echo $client->getCourriel(); ?>">Courriel<br>
			<span class="erreur">* <?php echo $messages['adresse'];?></span><br>
			<input type="text" name="adresse" value="<?php echo $client->getAdresse(); ?>">Adresse<br>
			<span class="erreur">* <?php echo $messages['ville'];?></span><br>
			<input type="text" name="ville" value="<?php echo $client->getVille(); ?>">Ville<br>
			<span class="erreur">* <?php echo $messages['codePostal'];?></span><br>
			<input type="text" name="codePostal" value="<?php echo $client->getCodePostal(); ?>">Code Postal<br>
			<?php afficherProvince($client->getProvince()); ?>
			Province<br>
			<span class="erreur">* <?php echo $messages['telephone'];?></span><br>
			<input type="text" name="telephone" value="<?php echo $client->getTelephone(); ?>">Numéro de téléphone<br>
		<?php if (isset($_SESSION['client'])): ?>
			<span class="label"><?php echo $client->getNomUtilisateur(); ?></span>Nom d'utilisateur<br>
			<input type="hidden" name="nomUtilisateur" value="<?php echo $client->getNomUtilisateur(); ?>">
			<input type="hidden" name="motDePasse" maxlength="15" value="<?php echo $client->getMotDePasse(); ?>">
			<input type="hidden" name="confirm" maxlength="15" value="<?php echo $client->getMotDePasse(); ?>">
			<input type="submit" name="valider" value="Modifier">
		<?php else: ?>
			<span class="erreur">* <?php echo $messages['nomUtilisateur'];?></span><br>
			<input type="text" name="nomUtilisateur" value="<?php echo $client->getNomUtilisateur(); ?>">Nom d'utilisateur<br>
			<span class="erreur">* <?php echo $messages['motDePasse'];?></span><br>
			<input type="password" name="motDePasse" maxlength="15">Mot de passe<br>
			<span class="erreur">* <?php echo $messages['confirm'];?></span><br>
			<input type="password" name="confirm" maxlength="15">Confirmation du mot de passe<br>
			<input type="submit" name="valider" value="S'inscrire">
		<?php endif; ?>
		</form>


<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
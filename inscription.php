<?php 

//-----------------------------
// Script contenant le formulaire de création et de modification d'un compte client.
// Procède au diverses validations nécessaires.
//-----------------------------
require_once(realpath(__DIR__).'/php/biblio/foncCommunes.php');

$js = array();

$js[] = 'inscription.js';
$css = array();
$css[] = 'formulaire.css';
$titre = 'LogiKek - Inscription';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';


$valide = true;
$client;

$messages = array(
	'sexe' => '', 'nom' => '', 'prenom' => ''
	, 'courriel' => '', 'adresse' => ''
	, 'ville' => '', 'codePostal' => ''
	, 'telephone' => '', 'nomUtilisateur' => ''
	, 'motDePasse' => '', 'confirm' => ''
);




//-----------------------------
// Fonction qui valide des données selon le patron
// spécifié par une regex.
// 
// $regex est la regex, $donne la donnée à validé, $donne spécifie pour quel champ du formulaire,
// $message un message d'erreur à afficher lorsque la validation échoue
//
// Retourne false si la validation échoue, true dans le cas contraire. 
//-----------------------------
function validationChamp($regex, $donne, $champ, $message)
{
	//Teste la donnée avec la regex, s'il échoue:
	if (!preg_match($regex, $donne))
	{	
		global $messages;
		global $valide;
		//Assigne le message d'erreur au array des messages d'erreur au bon contrôle
		//du formulaire.
		$messages[$champ] = $message;
		//La validation du formulaire à échoué.
		$valide = false;
		return false;
	}
	return true;
}

//Messages d'erreur affiché pour un champs vide qui est requis.
$msgRequis = "Champ requis";

//On arrive du bouton Valider, inscription à valider.
if (isset($_POST['valider']))
{
	$tabClient = array();

	//Récupère les champs du POST et désinfecte les données de l'utilisateur.
	foreach ($_POST as $cle => $valeur)
	{
		$tabClient[$cle] = desinfecte($valeur);
	}

	//Vérifie manuellement si le champs genre est set et non vide, car il n'est pas transmit dans le POST si l'utilisateur ne le choisit pas.
	//Les autres champs sont vérifiés dans un foreach
	if (empty($tabClient['sexe']))
	{
		$messages['sexe'] = $msgRequis;
		//Le formulaire à échoué la validation.
		$valide = false;
	}
	else
	{
		//N'est pas F ou M
		validationChamp("/^[FM]$/", $tabClient['sexe'], 'sexe', 'Option invalide');
	}


	//Nom et prénom: au moins 2 max 20 caractères parmi lettres, tiret, espace, apostrophe et point
	validationChamp("/^[a-zàáâéèêîíìôòóùúûç \-\'.]{2,20}$/iu", $tabClient['nom'], 'nom', 'Entre 2 et 20 lettres');


	//Nom et prénom: au moins 2 max 20 caractères parmi lettres, tiret, espace, apostrophe et point
	validationChamp("/^[a-zàáâéèêîíìôòóùúûç \-\'.]{2,20}$/iu", $tabClient['prenom'], 'prenom', 'Entre 2 et 20 lettres');

	//Valide le champs de courriel selon la syntaxe spécifié par RFC 822.
	if (!filter_var($tabClient['courriel'], FILTER_VALIDATE_EMAIL))
	{
		$messages['courriel'] = 'Courriel invalide: adresse@domaine.com';
		$valide = false;
	}


	//Adresse et ville: au moins 3 caractères parmi lettres, chiffres, tiret, espace, apostrophe et point, maximum 40.
	validationChamp("/^[a-zàáâéèêîíìôòóùúûç0-9 \-\'.]{3,40}$/iu", $tabClient['adresse'], 'adresse', 'Entre 3 et 40 caractères.');

	//Adresse et ville: au moins 2 caractères max 20 parmi lettres, chiffres, tiret, espace, apostrophe et point
	validationChamp("/^[a-zàáâéèêîíìôòóùúûç \-\'.]{2,20}$/iu", $tabClient['ville'], 'ville', 'Entre 2 et 20 lettres');

	//Code postal selon le modèle A9A9A9. Ne doit contenir aucune des lettres DFIOQU
	//Look ahead negatif "?!" du groupe [DFIOQU], regarde si le prochain group ne contient pas un match de ce groupe.
	validationChamp("/^((?![DdFfIiOoQqUu])([A-Za-z][0-9])){3}$/", $tabClient['codePostal'], 'codePostal', 'Sans espace: A9A9A9');

	//Numéro de téléphone: 10 chiffres, optionnellement encadrés ainsi: (xxx)yyy-zzzz

	/* Explication: Parenthèse et tiret optionnel sur tout le champ, avec espace optionnel après l'indicatif régionnal. (?(1)\) Regarde s'il y avait un match dans le premier group (première parenthèse ouvrante optionnel)
	si oui, on fait un match sur la parenthèse fermante (IF ?(1) THEN \) ). Ensuite un espace optionnel après ce groupe et on écrit le reste du pattern.
	À partir de la bar vertical "|" c'est la partie ELSE du conditionnel. Le deuxième groupe est déjà présent (le premier [0-9]), mais l'espace optionnel après la parenthèse fermante fait partie du conditionnel, que nous n'avons pas passé le teste. Donc, il faut mettre l'espace conditionnel au début cette fois. Après, match trivial de numéro et tirets et espaces optionnels.
	*/

	if (!preg_match("/^(\()?[0-9]{3}(?(1)\)[ -]?[0-9]{3}[- ]?[0-9]{4}|([ -]?)[0-9]{3}[ -]?[0-9]{4})$/", $tabClient['telephone']) || 
		preg_match("/^[0-9]{6}[ -]{1}[0-9]{4}$/", $tabClient['telephone'])) 
		//Plus simple d'avoir une autre regex pour vérifier le cas DDDDDD DDDD et DDDDDD-DDDD qu'on ne veux pas accepter.
	{
		$messages['telephone'] = 'Parenthèse, tiret et espace optionnels: (xxx)yyy-zzzz)';
		$valide = false;
	}


	//Nom d'usager et mot de passe: au moins 5 caractères max 10 parmi lettres et chiffres
	validationChamp("/^[a-zàáâéèêîíìôòóùúûç0-9]{5,10}$/iu", $tabClient['nomUtilisateur'], 'nomUtilisateur', 'Entre 5 et 10 caractères. Lettre et chiffres seulement.');

	//Nom d'usager et mot de passe: au moins 5 caractères max 10 parmi lettres et chiffres
	validationChamp("/^[a-zàáâéèêîíìôòóùúûç0-9]{5,10}$/iu", $tabClient['motDePasse'], 'motDePasse', 'Entre 5 et 10 caractères. Lettre et chiffres seulement.');

	//Nom d'usager et mot de passe: au moins 5 caractères max 10 parmi lettres et chiffres
	if(validationChamp("/^[a-zàáâéèêîíìôòóùúûç0-9]{5,10}$/iu", $tabClient['confirm'], 'confirm', 'Entre 5 et 10 caractères. Lettre et chiffres seulement.'))
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

	//Rend en majuscule le code postal
	$tabClient['codePostal'] = strToUpper($tabClient['codePostal']);
	//Sans champs de confirmation du mot de passe et sans le bouton valider dans le POST.
	$tabClient = array_slice($tabClient, 0, -2);

	//Instanciation d'un nouveau objet client.
	$client = new Client($tabClient);

	//Si le formulaire à passé les validations
	if ($valide)
	{
		//Enregistre l'objet client dans la variable de session
		$_SESSION['client'] = $client;	
		//Redirection vers la page de confirmation des informations du compte.
		header("location:confirmation.php");
		exit();
		//echo "<meta http-equiv='Refresh' content='0;url=confirmation.php' />";
	}
}
//Client déjà authentifié
elseif (isset($_SESSION['client']))
{
	//On le récupère
	$client = $_SESSION['client'];
}
//Un nouveau client désire s'inscrire
else
{
	$client = new Client(array());
}

//-----------------------------
// Fonction pour afficher la liste déroulante des provinces
// Le paramètre $provParam est le code de la province déjà sélectionné
//-----------------------------
function afficherProvince($provParam)
{
	//Array comme clé le code des provinces et le nom complet comme valeur
	$provinces = array(
		'QC' => 'Québec', 'ON' => 'Ontario', 'AB' => 'Alberta'
		, 'BC' => 'Colombie-Britannique', 'PE' => 'Ile-du-Prince-Édouard'
		, 'MB' => 'Manitoba', 'NB' => 'Nouveau-Brunswick'
		, 'NS' => 'Nouvelle-Écosse', 'NU' => 'Nunavut'
		, 'SK' => 'Saskatchewan', 'NL' => 'Terre-Neuve et Labrador'
		, 'NT' => 'Territoires du Nord-Ouest', 'YU' => 'Yukon');
		//Début de la combobox
		echo "<select id='province' name='province'>";
		foreach ($provinces as $cle => $valeur)
		{
			//Une province dans la combobox
			echo "<option value='$cle'";
			//Si le client à déjà une province, on le sélectionne
			if ($provParam == $cle)
				echo " selected";
			echo ">$valeur</option>";
		}
		//Fin de la combobox
		echo "</select>";
}


require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">

	<!-- Début du formulaire -->
	<div class="row">
	<?php if (isset($_GET['erreur'])): //Si le drapeau d'erreur a été envoyé dans le GET?>
		<!-- Message d'erreur -->
		<div class="alert alert-danger" role="alert">
			<i class="fa fa-exclamation-triangle"></i>
			<?php if($_GET['erreur'] == 'doublon'): //Erreur de doublon en base de données?>
			Nom d'utilisateur déjà existant.
			<?php else: //Pour tout autre erreur?>
			Erreur lors du traitement.
			<?php endif; ?>
		</div>
	<?php endif; ?>
		<form id="formInscription" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
			<table>
				<tbody>
					<tr class="centrer">
						<td colspan="3">
							<h3>Création de compte</h3>
						</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<!-- Genre -->
					<tr>
						<td class="droit">
							<label>Genre:</label>				
						</td>
						<td>
							<input type="radio" name="sexe" value="F" <?php echo ( ($client->getSexe() == 'F') ? 'checked' : '' ); //Ternary pour la valeur sélectionné?>>Femme
							<input type="radio" name="sexe" value="M" <?php echo ( ($client->getSexe() == 'M') ? 'checked' : '' ); //Ternary pour la valeur sélectionné?>>Homme			
						</td>
						<td>
							<span class="erreur"><?php echo $messages['sexe'];?></span>
						</td>
					</tr>
					<!-- Nom -->
					<tr>
						<td class="droit">
							<label for="nom">Nom:</label>
						</td>
						<td>
							<input id="nom" type="text" name="nom" value="<?php echo $client->getNom(); ?>">
						</td>
						<td>
							<span class="erreur"><?php echo $messages['nom'];?></span>
						</td>
					</tr>
					<!-- Prénom -->
					<tr>
						<td class="droit">
							<label for="prenom">Prénom:</label>
						</td>
						<td>
							<input id="prenom" type="text" name="prenom" value="<?php echo $client->getPrenom(); ?>">
						</td>
						<td>
							<span class="erreur"><?php echo $messages['prenom'];?></span>
						</td>
					</tr>
					<!-- Courriel -->
					<tr>
						<td class="droit">
							<label for="courriel">Courriel:</label>
						</td>
						<td>
							<input id="courriel" type="text" name="courriel" value="<?php echo $client->getCourriel(); ?>">
						</td>
						<td>
							<span class="erreur"><?php echo $messages['courriel'];?></span>
						</td>
					</tr>
					<!-- Adresse -->
					<tr>
						<td class="droit">
							<label for="adresse">Adresse:</label>
						</td>
						<td>
							<input id="adresse" type="text" name="adresse" value="<?php echo $client->getAdresse(); ?>">
						</td>
						<td>
							<span class="erreur"><?php echo $messages['adresse'];?></span>
						</td>
					</tr>
					<!-- Ville -->
					<tr>
						<td class="droit">
							<label for="ville">Ville:</label>
						</td>
						<td>
							<input id="ville" type="text" name="ville" value="<?php echo $client->getVille(); ?>">
						</td>
						<td>
							<span class="erreur"><?php echo $messages['ville'];?></span>
						</td>
					</tr>
					<!-- Code postal -->
					<tr>
						<td class="droit">
							<label for="codePostal">Code postal:</label>
						</td>
						<td>
							<input id="codePostal" type="text" name="codePostal" value="<?php echo $client->getCodePostal(); ?>">
						</td>
						<td>
							<span class="erreur"><?php echo $messages['codePostal'];?></span>
						</td>
					</tr>
					<!-- Province -->
					<tr>
						<td class="droit">
							<label for="province">Province:</label>
						</td>
						<td colspan="2">
							<?php afficherProvince($client->getProvince()); //Fonction pour générer la combobox des provinces?>
						</td>
					</tr>
					<!-- Téléphone -->
					<tr>
						<td class="droit">
							<label for="telephone">Numéro de téléphone:</label>
						</td>
						<td>
							<input id="telephone" type="text" name="telephone" value="<?php echo $client->getTelephone(); ?>">
						</td>
						<td>
							<span class="erreur"><?php echo $messages['telephone'];?></span>
						</td>
					</tr>
				<?php if (isset($_SESSION['authentification'])): //Si déjà authentifié, contexte de modification du profil?>
					<!-- Nom d'utilisateur -->
					<tr>
						<td class="droit">
							<label>Nom d'utilisateur:</label>
						</td>
						<td colspan="2">
							<!-- Champs d'entré caché -->
							<input type="hidden" name="nomUtilisateur" value="<?php echo $client->getNomUtilisateur(); ?>">
							<span class="label"><?php echo $client->getNomUtilisateur(); ?></span>
						</td>
					</tr>
					<!-- Mot de passe caché -->
					<tr>
						<td colspan="3">
							<input type="hidden" name="motDePasse" value="<?php echo $client->getMotDePasse(); ?>">
						</td>
					</tr>
					<!-- Confirmation du mot de passe caché-->
					<tr>
						<td colspan="3">
							<input type="hidden" name="confirm" value="<?php echo $client->getMotDePasse(); ?>">
						</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<!-- Boutton de modification -->
					<tr>
						<td class="centrer" colspan="3">
							<input type="submit" name="valider" value="Modifier">
						</td>
					</tr>
				<?php else: //Si dans le contexte de création de nouveau compte?>	
					<!-- Nom d'utilisateur -->
					<tr>
						<td class="droit">
							<label for="nomUtilisateur">Nom d'utilisateur:</label>
						</td>
						<td>
							<input id="nomUtilisateur" type="text" name="nomUtilisateur" value="<?php echo $client->getNomUtilisateur(); ?>">
						</td>
						<!-- Message indiquant si le nom d'utilisateur est libre ou non -->
						<td>
							<i id="utilisateurUnique" class="fa fa-check-circle vert">Nom libre</i>
							<i id="utilisateurNonUnique" class="fa fa-exclamation-triangle rouge">Nom déjà utilisé</i>
							<span class="erreur"><?php echo $messages['nomUtilisateur'];?></span>
						</td>
					</tr>	
					<!-- Mot de passe -->
					<tr>
						<td class="droit">
							<label for="motDePasse">Mot de passe:</label>
						</td>
						<td>
							<input id="motDePasse" type="password" name="motDePasse" value="<?php echo $client->getMotDePasse(); ?>">
						</td>
						<td>
							<span class="erreur"><?php echo $messages['motDePasse'];?></span>
						</td>
					</tr>
					<!-- Confirmation du mot de passe -->
					<tr>
						<td class="droit">
							<label for="confirm">Confirmation du mot de passe:</label>
						</td>
						<td>
							<input id="confirm" type="password" name="confirm">
						</td>
						<td>
							<span class="erreur"><?php echo $messages['confirm'];?></span>
						</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<!-- Boutton d'inscription -->
					<tr>
						<td class="centrer" colspan="3">
							<input type="submit" name="valider" value="S'inscrire">
						</td>
					</tr>	
				<?php endif; ?>	
				</tbody>
			</table>
		</form>
	</div> 	<!-- Fin du formulaire d'inscription -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
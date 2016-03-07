<?php

//-----------------------------
// Script d'authentification qui gère la connexion au compte client
// et la circulation entre certaines pages
//-----------------------------

require_once("./php/biblio/foncCommunes.php");

$js = array();

$css = array();
$css[] = 'formulaire.css';
$titre = 'LogiKek - authentification';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

require_once("./header.php");
require_once("./sectionGauche.php");


global $maBD;

//Si l'utilisateur est déjà connecté
if (isset($_SESSION['authentification']))
{
	//Redirection vers sa page de dossier.
	header("location:./inscription.php");
	exit();
}

//Si on a redirigé un usagé vers authentification.php, la variable 'prov' du GET
//note la page d'origine.
if (isset($_GET['prov']))
{
	//On l'enregistre dans la variable de session pour pouvoir la garder lors d'un refraichissement.
	$_SESSION['prov'] = $_GET['prov'];
}

//Si on arrive à la page après le bouton submit 'valider' du formulaire a été cliqué.
if (isset($_POST['valider']))
{
	$tabFormulaire = array();

	//Le POST contient toutes les données entré par l'utilisateur dans le formulaire.
	//On désinfecte les données et les enregistres dans le tableau.
	foreach ($_POST as $cle => $valeur)
	{
		$tabFormulaire[$cle] = desinfecte($valeur);
	}

	//Retourne un tableau contenant les données du compte client si le nom d'utilisateur et 
	//le mot de passe correspond à un compte client dans la base de données.
	$tmp = $maBD->selectClientMotDePasse($tabFormulaire['nomUtilisateur'], $tabFormulaire['motDePasse']);
	//Si le tableau est set, c'est qu'il y a un compte retourné.
	if (isset($tmp))
	{
		//Enlève le champs id qui se situe à l'index 0.
		array_shift($tmp);
		$client = new Client($tmp);

		//Enregistre l'objet client dans une variable de session et le nom d'utilisateur dans un autre.
		$_SESSION['client'] = $client;
		$_SESSION['authentification'] = $client->getNomUtilisateur();
		//Met à jour l'id de session à une nouvelle valeur pour mitiger les attaques.
		session_regenerate_id();
		//Si à l'origine on provient d'une autre page, nous voulons y retourner.
		if (isset($_SESSION['prov']))
		{
			switch ($_SESSION['prov'])
			{
				case 'dossier':
					unset($_SESSION['prov']);
					header("location:./authentification.php");
					break;

				case 'commander':
					unset($_SESSION['prov']);
					header("location:./commander.php");
					break;

				case 'changerMotDePasse':
					unset($_SESSION['prov']);
					header("location:./changerMotDePasse.php");
					break;

				default:
					unset($_SESSION['prov']);
					header("location:./");
					break;
			}
			exit();
		}
		header("location:./");
		exit();
	}
	//Mauvais mot de passe ou nom d'utilisateur
	else
	{
		header("location:./authentification.php?erreur=auth");
		exit();
	}
}

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début du formulaire de connexion -->
	<div class="row">
	<?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'auth'): //Si le mot de passe/nom d'utilisateur est incorrect?>
		<!-- Message d'erreur affiché à l'utilisateur -->
		<div class="alert alert-danger" role="alert">
			<i class="fa fa-exclamation-triangle"></i>
			Nom d'utilisateur ou mot de passe incorrect.
		</div>
	<?php endif; ?>
		<!-- Formulaire pour la connexion au compte client -->
		<form id="formConnexion" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
			<table>
				<tbody>
					<tr>
						<td class="centrer" colspan="2">
							<h3>Authentification</h3>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<!-- Nom d'utilisateur -->
					<tr>
						<td class="droit">
							<label for="nomUtilisateur">Nom d'utilisateur:</label>
						</td>
						<td>
							<input id="nomUtilisateur" type="text" name="nomUtilisateur">
						</td>
					</tr>
					<!-- Mot de passe -->
					<tr>
						<td class="droit">
							<label for="motDePasse">Mot de passe:</label>
						</td>
						<td>
							<input id="motDePasse" type="password" name="motDePasse">
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<!-- Bouton pour envoyer le formulaire -->
					<tr>
						<td class="centrer" colspan="2">
							<input type="submit" name="valider" value="Connecter">
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<!-- Lien pour aller à la page de création d'un nouveau compte -->
					<tr>
						<td class="centrer" colspan="2">
							<a href="./inscription.php">Créer un nouveau compte</a>
						</td>
					</tr>
				</tbody>
			</table>
		</form>

<!-- Contenu principal -->


	</div> 	<!-- Fin du formulaire -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
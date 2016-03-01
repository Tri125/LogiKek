<?php 
require_once("./php/biblio/foncCommunes.php");

$js = array();

$css = array();
$css[] = 'index.css';
$titre = 'LogiKek';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

require_once("./header.php");
require_once("./sectionGauche.php");


global $maBD;

if (isset($_SESSION['authentification']))
{
	header("location:./inscription.php");
	exit();
}

if (isset($_GET['prov']))
{
	$_SESSION['prov'] = $_GET['prov'];
}

if (isset($_POST['valider']))
{
	$tabClient = array();

	foreach ($_POST as $cle => $valeur)
	{
		$tabClient[$cle] = desinfecte($valeur);
	}

	$tmp = $maBD->selectClient($tabClient['nomUtilisateur']);

	if (isset($tmp) && $tmp['motDePasse'] == $tabClient['motDePasse'])
	{
		//Enlève le champs id qui se situe à l'index 0.
		array_shift($tmp);
		$client = new Client($tmp);

		$_SESSION['client'] = $client;
		$_SESSION['authentification'] = $_POST['nomUtilisateur'];
		//Ne sera jamais exécuté
		if (isset($_SESSION['prov']) && $_SESSION['prov'] == 'dossier')
		{
			unset($_SESSION['prov']);
			header("location:./authentification.php");
		}
		elseif (isset($_SESSION['prov']) && $_SESSION['prov'] == 'commander')
		{
			unset($_SESSION['prov']);
			header("location:./commander.php");
		}
		elseif (isset($_SESSION['prov']) && $_SESSION['prov'] == 'changerMotDePasse')
		{
			unset($_SESSION['prov']);
			header("location:./changerMotDePasse.php");
		}
		else
		{
			unset($_SESSION['prov']);
			header("location:./");
		}
		exit();
	}
	else
	{
		header("location:./authentification.php?erreur=auth");
		exit();
	}
}

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début des produits -->
	<div class="row">
	<?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'auth'): ?>
		<div class="alert alert-danger" role="alert">
			<i class="fa fa-exclamation-triangle"></i>
			Nom d'utilisateur ou mot de passe incorrect.
		</div>
	<?php endif; ?>
		<form id="formConnexion" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
			<table>
				<tbody>
					<tr>
						<td>
							<label for="nomUtilisateur">Nom d'utilisateur:</label>
						</td>
						<td>
							<input type="text" name="nomUtilisateur">
						</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td>
							<label for="motDePasse">Mot de passe:</label>
						</td>
						<td>
							<input type="password" name="motDePasse" maxlength="15">
						</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="3">
							<input type="submit" name="valider" value="Connecter">
						</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="3">
							<a href="./inscription.php">Créer un nouveau compte</a>
						</td>
					</tr>
				</tbody>
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
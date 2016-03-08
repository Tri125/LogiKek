<?php 

//-----------------------------
// Script pour le changement de mot de passe d'un compte d'utilisateur.
//-----------------------------

require_once("./php/biblio/foncCommunes.php");

$js = array();

$css = array();
$css[] = 'formulaire.css';
$js[] = 'changerMotDePasse.js';
$titre = 'LogiKek - Changement de mot de passe';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

$valide = true;
global $maBD;

//Tableau contenant les différents messages d'erreur qui seront affichés.
$messages = array();

//-----------------------------
// Fonction qui valide des données selon le patron
// spécifié par une regex.
// 
// $regex est la regex, $champ la donnée à validé
// $message un message d'erreur à afficher lorsque la validation échoue
//
// Retourne false si la validation échoue, true dans le cas contraire. 
//-----------------------------
function validationChamp($regex, $champ, $message)
{
	//Teste le champ avec la regex, s'il échoue:
	if (!preg_match($regex, $champ))
	{	
		global $messages;
		global $valide;
		//Rajoute un message d'erreur à l'array messages.
		$messages[] = $message;
		//La validation du formulaire à échoué.
		$valide = false;
		return false;
	}
	return true;
}

//Si l'utilisateur est déjà authentifié
if (isset($_SESSION['authentification']))
{
	//On peut assumé qu'un objet client est enregistré sous la variable de SESSION et on le récupère.
	$client = $_SESSION['client'];
	
	//On arrive du bouton valider, changement du mot de passe à valider.
	if (isset($_POST['valider']))
	{
		//Tableau pour contenir les données entré par l'utilisateur
		$tabMotPasse = array();
	
		//Désinfecte les données de l'utilisateur.
		foreach ($_POST as $cle => $valeur)
		{
			$tabMotPasse[$cle] = desinfecte($valeur);
		}
		
		//Mot de passe: au moins 5 caractères max 10 parmi lettres et chiffres
		validationChamp("/^[a-zàáâéèêîíìôòóùúû0-9]{5,10}$/iu", $tabMotPasse['mdpNouveau'], 'Nouveau mot de passe: entre 5 et 10 caractères. Lettre et chiffres seulement.');

		if(validationChamp("/^[a-zàáâéèêîíìôòóùúû0-9]{5,10}$/iu", $tabMotPasse['mdpConfirmer'], 'Confirmation du nouveau mot de passe: entre 5 et 10 caractères. Lettre et chiffres seulement.'))
		{
			//Mot de passe de confirmation n'est pas le même que le mot de passe
			if ($tabMotPasse['mdpConfirmer'] != $tabMotPasse['mdpNouveau'])
			{
				$messages[] = 'Confirmation du nouveau mot de passe: N\'est pas identique au nouveau mot de passe.';
				$valide = false;
			}
		}
	
		//Mot de passe actuel est le même que le nouveau mot de passe.
		//On se fit à la valeur des champs plutôt que de vérifier en BD.
		if ($tabMotPasse['mdpActuel'] == $tabMotPasse['mdpNouveau'])
		{
			$messages[] = 'Le nouveau mot de passe et le mot de passe actuel sont les mêmes.';
			$valide = false;
		}
	
		//Récupère un array avec les données du compte client si le nom d'utilisateur et le mot de passe actuel concorde.
		$resultat = $maBD->selectClientMotDePasse($client->getNomUtilisateur(), $tabMotPasse['mdpActuel']);

		//Si not set, alors ne concorde pas.
		if (!isset($resultat))
		{
			$messages[] = 'Le mot de passe actuel est incorrect.';
			$valide = false;
		}

		//Vérifie si chacun des champs est set et non vide.
		//Si oui, on met le message correspondant qui signal un champs requis.
		foreach ($tabMotPasse as $key => $value) 
		{
			if (empty($value))
			{
				//Inutile d'afficher tout les messages d'erreur si c'est un cas où un champ est vide.
				//Vide messages et la réinitialise.
				unset($messages);
				$messages = array();
				
				$messages[] = 'Un ou plusieurs champs sont vides.';
				$valide = false;
				break;
			}
		}

		//Si le chamgement de mot de passe est valide, on procède à la modification en BD.
		if ($valide)
		{
			//Modifie le mot de passe sur l'objet client.
			$client->setMotDePasse($tabMotPasse['mdpNouveau']);
			//Enregistre client dans la variable de SESSION
			$_SESSION['client'] = $client;
		
			$nbrLigneJour;
		
			try
			{
				//Modifie le mot de passe du compte client.
				$nbrLigneJour =	$maBD->updateMotDePasse($client);
			}
			catch (Exception $e)
			{
				exit();
			}
		}
	}
}
//Si nous tentons de naviguer à la page de changerMotDePasse sans être authentifié
else
{
	//Redirection à la page d'authentification.
	header("location:./authentification.php?prov=changerMotDePasse");
	exit();
}

require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début du formulaire -->
	<div class="row">
	<?php if (!$valide): //Affiche tout les messages d'erreur si la validation a échoué.?>
		<div class="alert alert-danger" role="alert">
			<i class="fa fa-exclamation-triangle"></i>
			<?php foreach ($messages as $message):?>
				<p><?php echo $message; ?></p>
			<?php endforeach; ?>
		</div>
	<?php elseif(isset($_POST['valider'])): //Si la validation a réussis et que le formulaire à déjà été envoyé, alors affiche un message de succès.?>
		<div class="alert alert-success" role="alert">
			<i class="fa fa fa-check"></i>
			<p>Changement de mot de passe fait avec succès.</p>
		</div>
	<?php endif; ?>	
		<form id="formChangementMotDePasse" onsubmit="return validerChamps(this);" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
			<table>
				<tbody>
					<tr>
						<td class="centrer" colspan="2">
							<h3>Saisissez les données</h3>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<!-- Mot de passe actuel -->
					<tr>
						<td class="droit">
							<label for="mdpActuel">Mot de passe actuel:</label>
						</td>
						<td>
							<input type="password" id="mdpActuel" name="mdpActuel">
						</td>
					</tr>
					<!-- Nouveau mot de passe -->
					<tr>
						<td class="droit">
							<label for="mdpNouveau">Nouveau mot de passe:</label>
						</td>
						<td>
							<input type="password" id="mdpNouveau" name="mdpNouveau">
						</td>
					</tr>
					<!-- Confirmation du nouveau mot de passe -->
					<tr>
						<td class="droit">
							<label for="mdpConfirmer">Confirmation du mot de passe:</label>
						</td>
						<td>
							<input type="password" id="mdpConfirmer" name="mdpConfirmer">
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td class="centrer" colspan="2">
							<input type="submit" name="valider" value="Changer le mot de passe">
						</td>
					</tr>
				</tbody>
			</table>

		</form>


		<!-- Fenêtre modal pour afficher les messages d'erreur de validation côté client. -->
		<div id="modalErreur" class="modal fade" role="dialog">
  			<div class="modal-dialog">
    			<!-- Contenu-->
    			<div class="modal-content">
      				<div class="modal-header">
        				<button type="button" class="close" data-dismiss="modal">&times;</button>
        				<!-- Titre -->
        				<h4 class="modal-title">Erreur</h4>
      				</div>
      				<div class="modal-body"></div>
      				<div class="modal-footer">
      					<!-- Bouton de fermeture -->
        				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
      				</div>
    			</div>
  			</div>
		</div>


<!-- Contenu principal -->
	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
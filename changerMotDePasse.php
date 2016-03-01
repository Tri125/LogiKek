<?php 
require_once("./php/biblio/foncCommunes.php");

$js = array();

$css = array();
$css[] = 'inscription.css';
$js[] = 'changerMotDePasse.js';
$titre = 'LogiKek - Changement de mot de passe';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

require_once("./header.php");
require_once("./sectionGauche.php");

$valide = true;
global $maBD;

if (isset($_SESSION['client']))
{
	$client = $_SESSION['client'];
	
	if (isset($_POST['valider'])) //On arrive du bouton Valider, changement du mot de passe à valider.
	{
		$tabMotPasse = array();
		$messages = array();
	
		foreach ($_POST as $cle => $valeur)
		{
			$tabMotPasse[$cle] = desinfecte($valeur);
		}
	
	
		//Nom d'usager et mot de passe: au moins 5 caractères max 10 parmi lettres et chiffres
		if (!preg_match("/^[A-Za-z0-9]{5,10}$/", $tabMotPasse['mdpNouveau']))
		{
			$messages[] = 'Nouveau mot de passe: Min. 5 caractères valides';
			$valide = false;
		}


		if (!preg_match("/^[A-Za-z0-9]{5,10}$/", $tabMotPasse['mdpConfirmer']))
		{
			//Nom d'usager et mot de passe: au moins 5 caractères max 10 parmi lettres et chiffres
			$messages[] = 'Confirmation du nouveau mot de passe: Min. 5 caractères valides';
			$valide = false;
		}
		else
		{
			//Mot de passe de confirmation n'est pas le même que le mot de passe
			if ($tabMotPasse['mdpConfirmer'] != $tabMotPasse['mdpNouveau'])
			{
				$messages[] = 'Confirmation du nouveau mot de passe: N\'est pas identique au mot de passe.';
				$valide = false;
			}
		}
	
		if ($tabMotPasse['mdpActuel'] == $tabMotPasse['mdpNouveau'])
		{
			$messages[] = 'Le nouveau mot de passe et le mot de passe actuel sont les mêmes.';
			$valide = false;
		}
	
		if ($tabMotPasse['mdpActuel'] != $client->getMotDePasse())
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
				unset($messages);
				$messages = array();
				
				$messages[] = 'Un ou plusieurs champs sont vides.';
				$valide = false;
				break;
			}
		}

		if ($valide)
		{
			$client->setMotDePasse($tabMotPasse['mdpNouveau']);
			$_SESSION['client'] = $client;
		
			$nbrLigneJour;
		
			try
			{
				$nbrLigneJour =	$maBD->updateMotDePasse($client);
			}
			catch (Exception $e)
			{
				exit();
			}
		}
	}
}
else
{
	header("location:./authentification.php?prov=changerMotDePasse");
	exit();
}
?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début des produits -->
	<div class="row">
	<?php if (!$valide): ?>
		<div class="alert alert-danger" role="alert">
			<i class="fa fa-exclamation-triangle"></i>
			<?php foreach ($messages as $message):?>
				<p><?php echo $message; ?></p>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>	
		<form id="formChangementMotDePasse" onsubmit="return validerChamps(this);" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
			<table>
				<tbody>
					<tr>
						<td colspan="2">
							<label>Saisissez les données</label>
						</td>
					</tr>
					<tr>
						<td>
							<label for="mdpActuel">Mot de passe actuel:</label>
						</td>
						<td>
							<input type="password" id="mdpActuel" name="mdpActuel">
						</td>
					</tr>
					<tr>
						<td>
							<label for="mdpNouveau">Nouveau mot de passe:</label>
						</td>
						<td>
							<input type="password" id="mdpNouveau" name="mdpNouveau">
						</td>
					</tr>
					<tr>
						<td>
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
						<td colspan="2">
							<input type="submit" name="valider" value="Changer le mot de passe">
						</td>
					</tr>
				</tbody>
			</table>

		</form>


<!-- Modal -->
<div id="modalErreur" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Erreur</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
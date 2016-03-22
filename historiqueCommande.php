<?php 

//------------------------------------------------
// Page affichant l'historique des commandes du client
// et permettant l'annulation d'une commande si le délais n'est pas dépassé.
//------------------------------------------------


require_once("./php/biblio/foncCommunes.php");

$js = array();
$js[] = 'rafraichissement.js';
$css = array();
$css[] = 'historiqueCommande.css';
$titre = 'LogiKek - Historique de commandes';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';


//------------------------------------------------
//Fonction pour afficher le temps restant avant de ne plus
//pouvoir annuler une commande.
//Prend un paramètre une string en format dateTime.
//------------------------------------------------
function tempsRestant($dateAchatString)
{

	$maintenant = new DateTime();
	$dateAchat = new DateTime($dateAchatString);
	//Crée un objet date représentant la date limite pour une annulation.
	//P pour période. 2 jours
	$dateAnnulationMax = $dateAchat->add(new DateInterval('P2D'));
	//Le temps restant.
	$interval = $dateAnnulationMax->diff($maintenant);

	//Logique de l'affichage du format.
	if ($interval->d >= 1)
		return $interval->format('%d jour(s) et %h heure(s)');
	if ($interval->h >= 1)
		return $interval->format('%h heure(s) et %i minute(s)');
	if ($interval->i >= 1)
		return $interval->format('%i minute(s) et %s seconde(s)');
	if ($interval->i < 1)
		return $interval->format('%s seconde(s)');
	return "erreur";
}


//------------------------------------------------
// Fonction pour s'avoir si la date limite d'annulation est dépassé.
// Retourne vrai ou faux.
//------------------------------------------------
function depaseDateLimite($dateAchatString)
{
	$maintenant = new DateTime();
	$dateAchat = new DateTime($dateAchatString);
	//Crée un objet date représentant la date limite pour une annulation.
	//P pour période. 2 jours
	$dateAnnulationMax = $dateAchat->add(new DateInterval('P2D'));

	return $maintenant > $dateAnnulationMax;
}

if (!isset($_SESSION['authentification']))
{
	//Redirection à la page d'authentification.
	header("location:./authentification.php?prov=histCommande");
	exit();
}
else
{
	global $maBD;
	$commandes = array();

	try
	{
		//Sélectionne l'historique de commandes en BD.
		$resultat = $maBD->selectCommandeDetails($_SESSION['authentification']);
	}
	catch (Exception $e)
	{
		exit();
	}
	//Si il y a au moins une commande dans l'historique.
	if (isset($resultat))
	{
		//Pour chacun des commandes
		foreach ($resultat as $value) 
		{
			//Crée un objet Commande et l'enregistre dans le tableau de commandes.
			$commandes[] = new Commande($value);
		}

		//Si le paramètre GET 'annule' est set
		if (isset($_GET['annule']))
		{
			//Récupère la valeur de l'index de la commande que nous voulons annuler.
			$numCommande = $_GET['annule'];

			//Vérifie le type et les bornes
			if (is_numeric($numCommande) && $numCommande >= 0 && $numCommande <= count($commandes) -1)
			{
				try
				{
					//Annule la commande.
					$resultat = $maBD->annulationCommande($commandes[$numCommande]);
				}
				catch (Exception $e)
				{
					exit();
				}
				//Redirection vers le script pour enlever le paramètre GET et actualiser l'affichage de l'historique.
				header("location:./historiqueCommande.php");
				exit();
			}
		}
	}


	require_once("./header.php");
	require_once("./sectionGauche.php");
}
?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début de l'historique -->
	<div class="row">
		<table>
			<tr>
				<!-- Total du nombres de commandes. -->
				<td colspan="5">
					<label>Vous avez passé un total de <?php echo count($commandes); ?> commandes</label>
				</td>
			</tr>
			<tr>
				<!-- Ligne de séparation -->
				<td colspan="5">
					<hr class="noir">
				</td>
			</tr>
			<?php foreach ($commandes as $key => $value): ?>
			<!-- Commande -->
			<tr>
				<td>
					<label>Commande : <?php echo $value->getNumCommande(); ?></label>
				</td>
				<!-- Date et montant de la commande -->
				<td class="petit">
					<label>
						Date : <?php echo $value->getDateCommande(); ?>
						<br>
						Montant : <?php echo number_format(calculTaxeFrais($value->Total()) , 2); ?>$
					</label>
				</td>
			<?php if (!depaseDateLimite($value->getdateCommande())): //S'il reste encore du temps pour l'annulation?>
				<!-- Lien d'annulation de la commande -->
				<td>
					<a href="./historiqueCommande.php?annule=<?php echo $key ?>">Annuler</a>
				</td>
				<td>&nbsp;</td>
				<!-- Compte à rebours du temps restant pour l'annulation de la commande. -->
				<td>
					<?php echo 'Encore ', tempsRestant($value->getdateCommande()), ' pour annuler.' ?>
				</td>
			<?php else: ?>
				<td colspan="3">&nbsp;</td>
			<?php endif; ?>
			</tr>
			<tr>
				<td>
					<label>Produit</label>
				</td>
				<td>
					<label>Quantité</label>
				</td>
				<td colspan="3">
					<label>Prix</label>
				</td>
			</tr>
			<?php foreach ($value->getTabAchats() as $keyAchat => $valueAchat): ?>
			<!-- Achat -->
			<tr>
				<td><?php echo $valueAchat->getNom(); ?></td>
				<td><?php echo $valueAchat->getNombre(); ?></td>
				<td colspan="3"><?php echo number_format($valueAchat->getPrix(), 2); ?>$</td>
			<?php endforeach; ?>
			</tr>
			<?php if( count($commandes) - 1 != $key): //Si nous ne sommes pas au dernier Achat de la commande?>
				<!-- Ligne de séparation -->
				<tr>
					<td colspan="5">
						<hr class="noir">
					</td>
				</tr>
			<?php endif; ?>
			<?php endforeach; ?>
		</table>
	</div>
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
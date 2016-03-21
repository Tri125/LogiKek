<?php 
require_once("./php/biblio/foncCommunes.php");

$js = array();
$js[] = 'rafraichissement.js';
$css = array();
$css[] = 'historiqueCommande.css';
$titre = 'LogiKek - Historique de commandes';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';


function tempsRestant($dateAchatString)
{
	$maintenant = new DateTime();
	$dateAchat = new DateTime($dateAchatString);
	//P pour période. 2 jours
	$dateAnnulationMax = $dateAchat->add(new DateInterval('P2D'));

	$interval = $dateAnnulationMax->diff($maintenant);

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

function depaseDateLimite($dateAchatString)
{
	$maintenant = new DateTime();
	$dateAchat = new DateTime($dateAchatString);
	//P pour période. 2 jours
	$dateAnnulationMax = $dateAchat->add(new DateInterval('P2D'));

	return $maintenant > $dateAnnulationMax;
}

if (!isset($_SESSION['authentification']))
{
	//Redirection ?la page d'authentification.
	header("location:./authentification.php?prov=histCommande");
	exit();
}
else
{
	global $maBD;
	$commandes = array();

	try
	{
		$resultat = $maBD->selectCommandeDetails($_SESSION['authentification']);
	}
	catch (Exception $e)
	{
		exit();
	}

	if (isset($resultat))
	{
		foreach ($resultat as $value) 
		{
			$commandes[] = new Commande($value);
		}

		if (isset($_GET['annule']))
		{
			$numCommande = $_GET['annule'];

			if (is_numeric($numCommande) && $numCommande >= 0 && $numCommande <= count($commandes) -1)
			{
				try
				{
					$resultat = $maBD->annulationCommande($commandes[$numCommande]);
				}
				catch (Exception $e)
				{
					var_dump($e->getMessage());
					exit();
				}
				header("location:./historiqueCommande.php");
			}
		}
	}


	require_once("./header.php");
	require_once("./sectionGauche.php");
}
?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début des produits -->
	<div class="row">
		<table>
			<tr>
				<td colspan="5">
					<label>Vous avez passé un total de <?php echo count($commandes); ?> commandes</label>
				</td>
			</tr>
			<tr>
				<td colspan="5">
					<hr class="noir">
				</td>
			</tr>
			<?php foreach ($commandes as $key => $value): ?>
			<tr>
				<td>
					<label>Commande : <?php echo $value->getNumCommande(); ?></label>
				</td>
				<td class="petit">
					<label>
						Date : <?php echo $value->getDateCommande(); ?>
						<br>
						Montant : <?php echo number_format(calculTaxeFrais($value->Total()) , 2); ?>$
					</label>
				</td>
			<?php if (!depaseDateLimite($value->getdateCommande())): ?>
				<td>
					<a href="./historiqueCommande.php?annule=<?php echo $key ?>">Annuler</a>
				</td>
				<td>&nbsp;</td>
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
			<tr>
				<td><?php echo $valueAchat->getNom(); ?></td>
				<td><?php echo $valueAchat->getNombre(); ?></td>
				<td colspan="3"><?php echo number_format($valueAchat->getPrix(), 2); ?>$</td>
			<?php endforeach; ?>
			</tr>
			<?php if( (count($commandes) > 1) && (count($commandes) - 1 != $key)) :?>
				<tr>
					<td colspan="5">
						<hr class="noir">
					</td>
				</tr>
			<?php endif; ?>
			<?php endforeach; ?>
		</table>

		<br>
	


<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
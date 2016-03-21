<?php 
require_once("./php/biblio/foncCommunes.php");

$js = array();

$css = array();
$css[] = 'index.css';
$titre = 'LogiKek';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';


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

	require_once("./header.php");
	require_once("./sectionGauche.php");

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
	}
	//var_dump($commandes);
}
?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début des produits -->
	<div class="row">
	<?php foreach ($commandes as $key => $value): ?>
		<label>Num : <?php echo $value->getNumCommande(); ?></label>
		<label>Date: <?php echo $value->getDateCommande(); ?></label>
		<?php foreach ($value->getTabAchats() as $keya => $valuea): ?>
			<label>Nom: <?php echo $valuea['nom']; ?></label>
			<label>Quantite: <?php echo $valuea['quantite']; ?></label>
			<label>Prix: <?php echo $valuea['prix']; ?>$</label>
			<br>
		<?php endforeach; ?>
		<br>
	<?php endforeach; ?>


<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
<?php 
require_once("./php/biblio/foncCommunes.php");

$js = array();

$css = array();
//$css[] = 'index.css';
$titre = 'LogiKek - création dossier';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';



global $maBD;

if (isset($_SESSION['client']))
{
	$client = $_SESSION['client'];
	try
	{
 		$maBD->insertClient($client);
	}
	catch(Exception $e)
	{
		//Doublon
		if ($e->getMessage() == 1062)
		{
			header("location:inscription.php?erreur=doublon");
			exit;
		}
		else
			exit;
	}
	$_SESSION['authentification'] = $client->getNomUtilisateur();
}
else
{
	header("location:inscription.php");
	exit;
}

require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-8" id="centre">
	<!-- Début des produits -->
	<div class="row">
		<div class="container">
		<?php if (isset($_SESSION['authentification'])): ?>
			<h3><?php echo $client->getPrenom().' '.$client->getNom(); ?></h3>
  			<p>Votre dossier a été créé avec succès, vous pouvez dorénavant commander des produits.</p>
  			<p>À votre prochain visite, il vous suffira de vous authentifier avec votre nom d'usager: <?php echo $client->getNomUtilisateur(); ?> et votre mot de passe. </p>
  			<p>Pour cette session, vous êtes déjà authentifiée.</p>
  			<p>Cliquez pour retourner au <a href="./" >catalogue</a></p>
  		<?php else: ?>
  			<h3>Une erreur est survenue.</h3>
  			<p>Veuillez créer votre dossier à partir de la page d'inscription.</p>
  		<?php endif; ?>
  		</div>


<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
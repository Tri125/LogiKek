<?php 

/*
Script qui crée/modifie le compte client sur la base de données et affiche un message à l'utilisateur concernant
le succès de l'opération.
*/

require_once("./php/biblio/foncCommunes.php");

$js = array();

$css = array();
//$css[] = 'index.css';
$titre = 'LogiKek - Création dossier';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';



global $maBD;
//Variable qui contiendra le message de statut à afficher à l'utilisateur.
$message = '';

$nbrLigneJour;

//Si la variable de SESSION est set, alors nous pouvons opéré deçue. 
if (isset($_SESSION['client']))
{	
	$client = $_SESSION['client'];
	
	//Si l'utilisateur est déjà authentifié, donc nous voulons mettre à jour le dossier sur la base de données.
	if (isset($_SESSION['authentification']))
	{	
		try
		{
			//Envois la requête à la base de données et retourne le nombre de ligne mit à jour en BD (le nbr d'entré modifié).
			$nbrLigneJour =	$maBD->updateClient($client);
		}
		catch (Exception $e)
		{
			exit();
		}
		//Si égal à 0, donc aucune modification puisque le profil est le même.
		if ($nbrLigneJour == 0)
			$message = 'Vous n\'avez fait aucun changement';
	}
	//Utilisateur non authentifié, donc nouveau compte à créer.
	else
	{
		try
		{
			//Insertion dans la base de données.
 			$maBD->insertClient($client);
		}
		catch (Exception $e)
		{
			//Exception d'insertion d'un doublon dans la base de données.
			if ($e->getMessage() == 1062)
				header("location:inscription.php?erreur=doublon");
			//Autre exception
			else
				header("location:inscription.php?erreur=".$e->getMessage());
			exit();
		}
		//Met le nom d'utilisateur dans la variable de session.
		$_SESSION['authentification'] = $client->getNomUtilisateur();
	}
}
//Usager à lui même été sur la page sans créer un objet client par la page inscription.php
else
{
	//Redirection vers la page d'inscription.
	header("location:inscription.php");
	exit();
}

require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-8" id="centre">
	<!-- Début de la section de messages -->
	<div class="row">
		<div class="container">
		<?php if (isset($nbrLigneJour)): //S'il y a eu une tentative de modification, nbrLigneJour contient une valeur.?>
			<?php if(!empty($message)): //S'il y a un message d'erreur.?>
				<!-- Message d'erreur -->
				<div class="alert alert-warning" role="alert">
					<i class="fa fa-info-circle"></i>
					<?php echo $message;?>
				</div>
			<?php else: //Il n'y a pas de message d'erreur, donc mise à jour avec succès.?>
			<!-- Message de modification avec succès -->
			<h3><?php echo $client->getPrenom().' '.$client->getNom(); ?></h3>
  			<p>Votre dossier a été mit à jour avec succès.</p>
  			<?php endif; ?>
		<?php elseif (isset($_SESSION['authentification'])): //Opération terminé.?>
			<!-- Message de création de compte client avec succès. -->
			<h3><?php echo $client->getPrenom().' '.$client->getNom(); ?></h3>
  			<p>Votre dossier a été créé avec succès, vous pouvez dorénavant commander des produits.</p>
  			<p>À votre prochain visite, il vous suffira de vous authentifier avec votre nom d'usager: <?php echo $client->getNomUtilisateur(); ?> et votre mot de passe. </p>
  			<p>Pour cette session, vous êtes déjà authentifiée.</p>
  			<p>Cliquez pour retourner au <a href="./" >catalogue</a></p>
  		<?php else: //Code qui ne sera jamais exécuté.?>
		  <h3>Une erreur est survennue</h3>
  		<?php endif; ?>
  		</div>
<!-- Contenu principal -->


	</div> 	<!-- Fin de la section de messages -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
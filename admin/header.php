<?php 

//---------------------------------------
// Page de la section d'entête de la section administrateur du site web.
//---------------------------------------

require_once(realpath(__DIR__.'/..').'/php/biblio/foncCommunes.php');

//Si non authentifié, redirection vers la page d'authentification.
if(!isset($_SESSION['authentification']))
{
	header('location:../authentification.php?prov=admin');
	exit();
}

//Si authentifié, mais pas administrateur on redirige vers la section usager du site web.
if(isset($_SESSION['authentification']) && !$_SESSION['client']->getEstAdmin())
{
	header('location:../');
	exit();
}

//Paramètre GET pour l'action de déconnexion du compte client.
if(isset($_GET['deconnexion']))
	deconnexionUsager();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title><?php echo $titre; ?></title>
	<meta name="description" content="<?php echo $description; ?>">
	<meta name="keywords" content="<?php echo $motCle; ?>">
	<meta name="author" content="Tristan S.">

	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<!-- CSS contenant plusieurs icones vectorielles communes aux sites webs -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="<?php echo $CSS_DIR.'global.css';?>">
	<link rel="stylesheet" href="<?php echo $CSS_DIR.'dirty.css';?>">
	<?php foreach($css as $value) : //Chaque page inclus sont propre css/js avec les variable $css et $js. Si le tableau est vide, alors l'html n'est pas généré. ?>
		<link rel="stylesheet" href="<?php echo $CSS_DIR.$value; ?>">
	<?php endforeach; ?>
	
	<?php foreach($js as $value) : ?>
	<script src="<?php echo $JS_DIR.$value; ?>"></script>
	<?php endforeach; ?>
</head>
<body>
	<div class="row" id="tete">
		<div class="col-md-4" id="nomLogiKek"> <!-- Nom de la compagnie -->
			<a href="./index.php"><h1>LogiKek</h1></a>
		</div> <!-- Fin du nom de la compagnie -->
	</div> <!-- Fin row page-header -->
<?php 
require_once("./php/biblio/foncCommunes.php");

$categorie = 0;
$recherche = '';
$nbrArticle = 0;

if(isset($_GET['listeCategorie']))
	$categorie = $_GET['listeCategorie'];

if(isset($_GET['recherche']))
	$recherche = $_GET['recherche'];

$liste = new Catalogue($categorie, $recherche);

if (isset($_SESSION['panier-item']))
	$nbrArticle	= $_SESSION['panier-item'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title><?php echo $titre; ?></title>
	<meta name="description" content="<?php echo $description; ?>">
	<meta name="keywords" content="<?php echo $motCle; ?>">
	<meta name="author" content="Tristan S.">

	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<!-- Bootstrap -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="./css/global.css">
	<?php if(isset($css)) : ?>
		<link rel="stylesheet" href="./css/<?php echo $css; ?>">
	<?php endif; ?>
	<link rel="stylesheet" href="./css/dirtyIndex.css">
	<?php if(isset($js)) : ?>
	<script src="./js/<?php echo $js; ?>"></script>
	<?php endif; ?>
</head>
<body>
	<div class="row" id="tete">
		<div class="col-md-4" id="nomLogiKek">
			<a href="./?"><h1>LogiKek</h1></a>
		</div>
		<div class="col-md-4">
			<form action='./' method='GET'>
				<div class="input-group" id="groupeRecherche">
					<input type="text" required name="recherche" class="form-control" placeholder="Rechercher...">
					<div class="input-group-btn">
						<select class="btn dropdown-toggle" name="listeCategorie">
							<option value="0" selected>Tout les produits</option>
							<?php 
							foreach(Categorie::fetchAll() as $value): ?>
							<option value="<?php echo $value->getCodeCategorie(); ?>"><?php echo $value->getNom(); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
					</span>
				</div><!-- /input-group -->
			</form>
		</div><!-- /.col-mg-4 -->

	<div class="col-md-3 text-right" id="logCart">
		<div class="row">
			<a href="#">
				<i class="fa fa-user">
					Se connecter
				</i>
			</a>
		</div>
		<div class="row">
			<a href="./panierGestion.php">
				<i class="fa fa-shopping-cart">
					<ins><?php echo $nbrArticle ?> Articles</ins>
				</i>
			</a>
		</div>
	</div>
</div> <!-- Fin row page-header -->
<div class="row">
	<nav class="navbar navbar-left navbar-static-top">
		<div class="container">
			<ul class="nav navbar-nav">
				<li><a href="#">Spéciaux<span class="sr-only">(current)</span></a></li>
				<li><a href="#">Aide<span class="sr-only">(current)</span></a></li>
			</ul>
		</div>
		<div class="panel panel-success"></div>
	</nav>
</div> <!-- Fin row navbar -->
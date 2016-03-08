<?php 
require_once("./php/biblio/foncCommunes.php");

//code de catégorie pour la recherche. Par défault à 0 pour tout inclure.
$categorie = 0;
//Champ de texte pour la recherche. Par défault vide pour tout inclure.
$recherche = '';
//Lors de la première visite, il y aura 0 article dans le panier.
//Utiliser pour l'affichage du nbr d'article dans le panier.
$nbrArticle = 0;

//Pour savoir si nous sommes à la page index.php (le catalogue) pour afficher les éléments de recherche.
$estIndex = preg_match('@^[a-z/]*index.php$@', htmlspecialchars($_SERVER['PHP_SELF']));

//Paramètre GET pour l'action de déconnexion du compte client.
if(isset($_GET['deconnexion']))
	deconnexionUsager();

if(isset($_GET['changerMotDePasse']))
{
	header('location:./changerMotDePasse.php');
	exit();
}

//Paramètre GET pour le code de catégorie pour la recherche.
if(isset($_GET['listeCategorie']))
	$categorie = $_GET['listeCategorie'];

//Paramètre GET pour le texte pour la recherche.
if(isset($_GET['recherche']))
	$recherche = $_GET['recherche'];

//Catalogue qui contient les Produits à afficher sur la page selon les paramètres GET.
$liste = new Catalogue($categorie, $recherche);

//Vérifie s'il y a une valeur dans la variable de session pour le nbr d'article dans le panier.
if (isset($_SESSION['panier-item']))
	$nbrArticle	= $_SESSION['panier-item'];


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
	
	<link rel="stylesheet" href="./css/global.css">
	<link rel="stylesheet" href="./css/dirty.css">
	<?php foreach($css as $value) : //Chaque page inclus sont propre css/js avec les variable $css et $js. Si le tableau est vide, alors l'html n'est pas généré. ?>
		<link rel="stylesheet" href="./css/<?php echo $value; ?>">
	<?php endforeach; ?>
	
	<?php foreach($js as $value) : ?>
	<script src="./js/<?php echo $value; ?>"></script>
	<?php endforeach; ?>
</head>
<body>
	<div class="row" id="tete">
		<div class="col-md-4" id="nomLogiKek"> <!-- Nom de la compagnie -->
			<a href="./index.php"><h1>LogiKek</h1></a>
		</div> <!-- Fin du nom de la compagnie -->
		<div class="col-md-4"> <!-- Début de la section du form de recherche -->
			<?php if($estIndex) : //Cache le form de recherche si nous sommes pas à l'index ?>
			<form action='./' method='GET'>
				<div class="input-group" id="groupeRecherche">
					<input type="text" required name="recherche" class="form-control" placeholder="Rechercher...">
					<div class="input-group-btn">
						<select class="btn dropdown-toggle" name="listeCategorie"> <!-- Dropdown contenant toutes les catégories -->
							<option value="0" selected>Tout les produits</option>
							<?php 
							foreach(Categorie::fetchAll() as $value): //Récupère toutes les catégories en BD et crée les éléments option html ?>
							<option value="<?php echo $value->getCodeCategorie(); ?>"><?php echo $value->getNom(); ?></option>
							<?php endforeach; ?>
						</select> <!-- Fin du dropdown -->
					</div>
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
					</span>
				</div><!-- Fin div input-group -->
			</form>
			<?php endif; ?>
		</div><!-- Fin col-md-4 de la section du form de recherche -->

		<div class="col-md-3 text-right" id="logCart"> <!-- Section de connexion et navigation vers le panier -->
			<div class="row"> <!-- Lien connexion avec image symbolique -->
			<?php if (isset($_SESSION['authentification'])): ?>
				<a href="./authentification.php">
					<i class="fa fa-user">
						<?php echo $_SESSION['authentification']; ?>
					</i>
				</a>
				<a href="./?changerMotDePasse">
					<i class="fa fa-lock">
						Changer de mot de passe
					</i>
				</a>
				<a href="./?deconnexion">
					<i class="fa fa-user">
						Déconnexion
					</i>
				</a>
			<?php else: ?>
				<a href="./authentification.php">
					<i class="fa fa-user">
						Se connecter
					</i>
				</a>
			<?php endif; ?>
				<a href="./authentification.php?prov=dossier">
					<i class="fa fa-file">
						Mon Dossier
					</i>
				</a>
			</div>
			<div class="row"> <!-- Lien vers le panier avec image symbolique -->
				<a href="./panierGestion.php">
					<i class="fa fa-shopping-cart">
						<ins><?php echo $nbrArticle ?> Articles</ins>
					</i>
				</a>
			</div>
			<div class="row"> <!-- Lien vers la page commander -->
				<a href="#">
					<i class="fa fa-truck">
						Commander
					</i>
				</a>
			</div>
		</div> <!-- Fin section connexion et navigation vers le panier -->
	</div> <!-- Fin row page-header -->
	<div class="row"> <!-- Section pour des liens de navigation spéciaux -->
		<nav class="navbar navbar-left navbar-static-top">
			<div class="container">
				<ul class="nav navbar-nav"> <!-- Pour accéder aux spéciaux du magasin et au support technique -->
					<li><a href="">Spéciaux<span class="sr-only">(current)</span></a></li>
					<li><a href="">Aide<span class="sr-only">(current)</span></a></li>
					<li><a href="./politiques.php">Politiques<span class="sr-only">(current)</span></a></li>
				</ul>
			</div>
			<div class="panel panel-success"></div>
		</nav>
	</div> <!-- Fin section des liens spéciaux -->
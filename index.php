<?php 
require_once("php/mysqli.php");
require_once("php/Classes/Categorie.php"); 
require_once("php/Classes/Produit.php"); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>Bootstrap 101 Template</title>

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
	<link rel="stylesheet" href="LogiKek/css/index.css">
	<link rel="stylesheet" href="LogiKek/css/dirtyIndex.css">

	<script src="LogiKek/js/app.js"></script>
	<script src="LogiKek/js/categorie.js"></script>
</head>
<body>
	<div class="row" id="tete">
		<div class="col-md-4" id="nomLogiKek">
			<h1>LogiKek</h1>
		</div>
		<div class="col-md-4">
			<div class="input-group" id="groupeRecherche">
				<input type="text" class="form-control" placeholder="Search for...">
				<div class="input-group-btn">
					<select class="btn dropdown-toggle">
						<option selected>Tout les produits</option>
						<?php 
						foreach(Categorie::fetchAll() as $value): ?>
						<option><?php echo $value->nom; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<span class="input-group-btn">
				<button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
			</span>
		</div><!-- /input-group -->
	</div><!-- /.col-mg-4 -->

	<div class="col-md-3 text-right" id="logCart">
		<div class="row">
			<a href="#">
				<i class="fa fa-user">
					Log in
				</i>
			</a>
		</div>
		<div class="row">
			<a href="#">
				<i class="fa fa-shopping-cart">
					<ins>0 Items</ins>
				</i>
			</a>
		</div>
	</div>
</div> <!-- Fin row page-header -->
<div class="row">
	<nav class="navbar navbar-left navbar-static-top">
		<div class="container">
			<ul class="nav navbar-nav">
				<li><a href="#">Deals <span class="sr-only">(current)</span></a></li>
				<li><a href="#">Help <span class="sr-only">(current)</span></a></li>
			</ul>
		</div>
		<div class="panel panel-success"></div>
	</nav>
</div> <!-- Fin row navbar -->
<div class="row">
	<div class="col-md-2">
		<ul>
			<?php 
			foreach(Categorie::fetchAll() as $value): ?>
			<li><a href="#"><?php echo $value->nom ?></a></li>
		<?php endforeach; ?>


	</ul>
</div>
<!-- Début section central col-md-9 -->
<div class="col-md-8">
	<div class="pub">
		<img class="img-responsive" src="LogiKek/img/DealArchEdit.png" alt="Publicité de Arch Linux.">
	</div>
	<!-- Début des produits -->
	<div class="row">
		<?php foreach(Produit::fetchAll() as $value): ?>
			<div class="col-md-4 panel panel-default">
				<h4><?php echo $value->nom ?></h4>
				<a href="#" class="thumbnail imgProduitPetit">
					<img src="LogiKek/img/produits/<?php echo $value->codeProduit ?>_small.png" alt="<?php echo $value->nom ?>" onError="this.onerror=null;this.src='LogiKek/img/produits/nonDispo_small.png';">
				</a>
				<?php foreach($value->categories as $categorie): ?>
					<span class="label label-info"><?php echo $categorie ?></span>
				<?php endforeach; ?>
				<h4>
					<?php echo $value->prix ?>$ 
					<a href="#">
						<i class="fa fa-shopping-cart"></i>
					</a>
				</h4>
			</div>
		<?php endforeach; ?>
	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>

<footer class="footer">
	<div class="container">
		<a href="#"><p class="text-muted">Politiques de LogiKek</p><a/>
		</div>
	</footer>
</body>
</html>
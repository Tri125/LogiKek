<?php 
require_once("./php/biblio/foncCommunes.php");

//Variable pour que header.php charge une feuille de style spécifique à la page politiques.php
$css = 'politiques.css';
//Variable pour que header.php donne un titre de page spécifique à politiques.php
$titre = 'LogiKek - Politiques';
//Variable pour que header.php donne une description spécifique à la page politiques.php
$description = 'Site de vente de système d\'exploitation';
//Variable pour que header.php donne des mots clés spécifique à la page politiques.php
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

//Charge le script header.php
require_once("./header.php");
//Charge le script sectionGauche.php
require_once("./sectionGauche.php");

?>

	<div class="col-md-8" id="centre"> <!-- Début section central col-md-8 -->
		<div class="row"> 	<!-- Début de la ligne des politiques -->
			<div class="jumbotron">
  				<div class="container"> <!-- Contenant avec nos politiques d'entreprise -->
  					<h2>Politiques d'achat</h2>
  					<p>Vente final.</p>
  					<p>Aucun remboursement ou échange.</p>
  					<p>Un code d'accès unique vous seras envoyé pour télécharger le produit acheté.</p>
  					<p>Un code égaré ne vous seras pas ré-envoyé.</p>
  					<p>Un frais de génération de codes de 3.00$ est ajouté par commande.</p>
  				</div> <!-- Fin contenant de nos politiques -->
			</div> <!-- Fin du jumbotron -->
		</div> 	<!-- Fin de la ligne des politiques -->
	</div>	<!-- Fin section central col-md-8 -->
	<div class="col-md-1"> 	<!-- Début Section de droite central. Vide. Juste pour centrer le reste. -->
	</div><!-- Fin section de droite central -->
</div> <!-- Fin ligne du contenu central -->


<?php require_once('./footer.php'); //Charge le script footer.php ?>
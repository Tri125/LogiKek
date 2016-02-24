<?php 
require_once("./php/biblio/foncCommunes.php");

$js = array();

$css = array();
//$css[] = 'index.css';
$titre = 'LogiKek';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

$client = $_SESSION['client'];

global $maBD;


$maBD->insertClient($client);

require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-8" id="centre">
	<!-- Début des produits -->
	<div class="row">



<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>
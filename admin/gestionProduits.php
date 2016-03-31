<?php 
require_once(realpath(__DIR__.'/..').'/php/biblio/foncCommunes.php');

$js = array();

$css = array();
$css[] = 'index.css';
$titre = 'LogiKek';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';


$CSS_DIR = '../css/';
$JS_DIR = '../js/';
$IMG_DIR = '../img/';

global $maBD;

$nomChamps = array();
$catalogue = new Catalogue(0, '');
$produitData = null;
$valide = false;

try
{
	$nomChamps = $maBD->columnsName('Produits');
	if (isset($_GET['choix']) && $_GET['choix'] != 'nouveau')
	{
		$produitData = $maBD->selectProduit($_GET['choix']);
	}
}
catch (Exception $e)
{
	exit();
}


function genereForm($nomChamps, $produit)
{
	foreach ($nomChamps as $key => $value) 
	{
		//Si c'est une colonne de clé primaire, nous ne voulons pas afficher le champs.
		if (isset($value['COLUMN_KEY']) && $value['COLUMN_KEY'] == 'PRI')
			continue;

		echo "<label>".$value['COLUMN_NAME'].": </label>";
		echo "<br>";

		if (isset($value['CHARACTER_MAXIMUM_LENGTH']) && $value['CHARACTER_MAXIMUM_LENGTH'] > 60)
		{
			echo "<textarea rows='4' cols='50' name='".$value['COLUMN_NAME']."'>";
			if(isset($produit))
			{
				echo $produit[$value['COLUMN_NAME']];
			}
			elseif(isset($value['COLUMN_DEFAULT']))
			{
				echo $value['COLUMN_DEFAULT'];
			}
			echo "</textarea>";
		}
		else
		{
			echo "<input type='text' name='".$value['COLUMN_NAME']."'";

			if(isset($produit))
			{
				echo " value='".$produit[$value['COLUMN_NAME']]."'";
				echo " size='".strlen($produit[$value['COLUMN_NAME']])."'";
			}
			elseif(isset($value['COLUMN_DEFAULT']))
			{
				echo " value='".$value['COLUMN_DEFAULT']."'";
			}
			echo ">";
		}

		echo "<br>";
	}
	echo "<hr>";

	echo "<input type='hidden' name='MAX_FILE_SIZE' value='100000'>";

	echo "<label>Petite photo: </label>";
	echo "<input type='file' name='petitePhoto'>";
	echo "<br>";

	echo "<label>Grande photo: </label>";
	echo "<input type='file' name='grandePhoto'>";
	echo "<br>";

	echo "<input type='submit' name='valider' value='Valider'>";
}


function valideForm($nomChamps, $data)
{
	var_dump($data);

	//Vérifie si chacun des champs est set et non vide.
	foreach ($data as $key => $value) 
	{
		if (empty($value))
		{
			return false;
		}
	}

	foreach ($nomChamps as $key => $value) 
	{
		if (isset($value['CHARACTER_MAXIMUM_LENGTH']))
		{
			$maxLen = $value['CHARACTER_MAXIMUM_LENGTH'];
			$input = $data[$value['COLUMN_NAME']];

			if(strlen($input) > $maxLen || strlen($input) < 2)
				return false;
		}
	}

	return true;
}

if (isset($_POST['valider']))
{
	$tabData = array();

	//Récupère les champs du POST et désinfecte les données de l'utilisateur.
	foreach ($_POST as $cle => $valeur)
	{
		$tabData[$cle] = desinfecte($valeur);
	}

	$valide = valideForm($nomChamps, $tabData);
}

require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début des produits -->
	<div class="row">
	<?php if(!isset($_GET['choix'])): ?>
	<form id='formProduit' method='GET' action='./gestionProduits.php'>
		<select name='choix'>
			<option value="nouveau">Nouveau Produit</option>
			<?php foreach ($catalogue->getCatalogue() as $value): ?>
			<option value="<?php echo $value->getNom(); ?>"><?php echo $value->getNom(); ?></option>
			<?php endforeach; ?>
		</select>
		<input type="submit" value="Continuer">
	<?php else: ?>
		<form id="formProduit" method="POST" action="./gestionProduits.php" enctype="multipart/form-data">
			<?php genereForm($nomChamps, $produitData); ?>
	<?php endif; ?>
	</form>
<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('../footer.php'); ?>
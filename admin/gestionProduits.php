<?php 
require_once(realpath(__DIR__.'/..').'/php/biblio/foncCommunes.php');

$js = array();

$css = array();
$css[] = 'formulaire.css';
$titre = 'LogiKek - Gestion Produits';
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
$choix = null;
$messagesErreur = array();

if(isset($_POST['choix']))
{
	$choix = $_POST['choix'];
}



if ((!isset($_POST['choix']) && !isset($_POST['valider'])) || isset($_POST['annuler']))
{
	header('location:./gestionProduitsmenu.php');
	exit();
}

try
{
	$nomChamps = $maBD->columnsName('Produits');

	$messagesErreur = genereMessages($nomChamps);
	if (isset($choix) && $choix != 'nouveau')
	{
		$produitData = $maBD->selectProduit($choix);
	}
}
catch (Exception $e)
{
	exit();
}

function genereMessages($nomChamps)
{
	$messagesErreur = array();

	foreach ($nomChamps as $key => $value) 
	{
		$messagesErreur[$value['COLUMN_NAME']] = '';
	}
	$messagesErreur['categories'] = '';
	$messagesErreur['petitePhoto'] = '';
	$messagesErreur['grandePhoto'] = '';

	return $messagesErreur;
}

function genereForm($nomChamps, $produit)
{
	global $messagesErreur;

	foreach ($nomChamps as $key => $value) 
	{
		$nomCol = $value['COLUMN_NAME'];
		//Si c'est une colonne de clé primaire, nous ne voulons pas afficher le champs.
		if (isset($value['COLUMN_KEY']) && $value['COLUMN_KEY'] != 'PRI')
		{
			echo "<span class='erreur'>".$messagesErreur[$nomCol]."</span>";
			echo "<br>";
			echo "<label>".ucfirst($nomCol).": </label>";
		}

		if (isset($produit) && isset($value['COLUMN_KEY']) && $value['COLUMN_KEY'] == 'PRI')
		{
			echo "<input type='hidden' name='".$nomCol."' value='";
			if(isset($produit[$nomCol]))
				echo $produit[$nomCol];
			echo "'>";
			continue;
		}
		elseif(isset($value['COLUMN_KEY']) && $value['COLUMN_KEY'] == 'PRI')
			continue;

		if (isset($value['CHARACTER_MAXIMUM_LENGTH']) && $value['CHARACTER_MAXIMUM_LENGTH'] > 60)
		{
			echo "<br>";
			echo "<textarea rows='4' cols='50' name='".$value['COLUMN_NAME']."'>";
			if(isset($produit))
			{
				echo $produit[$nomCol];
			}
			elseif(isset($value['COLUMN_DEFAULT']))
			{
				echo $value['COLUMN_DEFAULT'];
			}
			echo "</textarea>";
		}
		else
		{
			echo "<input type='text' name='".$nomCol."'";

			if(isset($produit))
			{
				if (strlen($produit[$nomCol]) < 10)
					$size = 10;
				else
					$size = strlen($produit[$nomCol]);

				echo " value='".$produit[$nomCol]."'";
				echo " size='".$size."'";

			}
			elseif(isset($value['COLUMN_DEFAULT']))
			{
				echo " value='".$value['COLUMN_DEFAULT']."'";
			}
			echo ">";
		}
		echo "<br>";
		echo "<br>";
	}

	echo "<span class='erreur'>".$messagesErreur['categories']."</span>";
	echo "<br>";
	echo "<label>Catégories: </label>";
	echo "<br>";
	genereCheckBoxCategorie($produit);
	echo "<hr>";

	echo "<input type='hidden' name='MAX_FILE_SIZE' value='100000'>";
	echo "<span class='erreur'>".$messagesErreur['petitePhoto']."</span>";
	echo "<br>";
	echo "<label>Petite photo: </label>";
	echo "<input type='file' name='petitePhoto'>";
	echo "<br>";
	echo "<span class='erreur'>".$messagesErreur['grandePhoto']."</span>";
	echo "<br>";
	echo "<label>Grande photo: </label>";
	echo "<input type='file' name='grandePhoto'>";
	echo "<br>";

	echo "<input type='submit' name='valider' value='Valider'>";
	echo "<input type='submit' name='annuler' value='Annuler'>";

	var_dump($messagesErreur);
}

function genereCheckBoxCategorie($produit = null)
{
	$categories = Categorie::fetchAll();
	if(isset($produit))
		$tabCategorieSelected = explode(",", $produit['categories']);
	foreach ($categories as $key => $value) 
	{
		if ($key % 5 == 0)
			echo "<br>";
		echo "<input type='checkbox' name='categories[]' value='".$value->getNom()."'";
		if (isset($produit) && in_array($value->getNom(), $tabCategorieSelected))
			echo " checked";
		echo ">".$value->getNom();
	}
}

function valideForm($nomChamps, $data)
{
	global $messagesErreur;

	$estValide = true;


	if (!isset($data['categories']))
	{
		$messagesErreur['categories'] = "Veuillez choisir au moins une catégorie.";
		$estValide = false;
	}
	//Vérifie si chacun des champs est set et non vide.
	foreach ($data as $key => $value) 
	{
		if ($key == 'categories')
			continue;
		if (isset($value) && strlen($value) == 0)
		{
			$estValide = true;
			$messagesErreur[$key] = "Champs vide.";
		}
	}

	foreach ($nomChamps as $key => $value) 
	{
		$colName = $value['COLUMN_NAME'];
		//Si c'est une colonne de clé primaire, nous ne voulons pas afficher le champs.
		if (isset($value['COLUMN_KEY']) && $value['COLUMN_KEY'] == 'PRI')
			continue;

		$input = $data[$colName];

		if (isset($value['CHARACTER_MAXIMUM_LENGTH']))
		{
			$maxLen = $value['CHARACTER_MAXIMUM_LENGTH'];

			if(strlen($input) > $maxLen || strlen($input) < 2)
			{
				$messagesErreur[$colName] = "Le champs dois être entre 2 et $maxLen caractères.";
				$estValide = false;
			}
		}
		$type = $value['DATA_TYPE'];

		if ($type == 'float' || $type == 'int')
		{
			if (is_numeric($input))
			{
				if( ($input * 1) < 0)
				{
					$messagesErreur[$colName] = "Le champs dois être numérique et au moins 0.";
					$estValide = false;
				}

				if ($type == 'float')
					if(!preg_match( "/^[0-9]*[.,][0-9]{2}$/", $input ))
					{
						$messagesErreur[$colName] = "Le champs dois être écris avec deux décimals (10.00).";
						$estValide = false;
					}
			}
			else
			{
				$messagesErreur[$colName] = "Le champs dois être numérique.";
				$estValide = false;
			}
		}
	}

	return $estValide;
}

if (isset($_POST['valider']))
{
	$postData = array();

	//Récupère les champs du POST et désinfecte les données de l'utilisateur.
	foreach ($_POST as $cle => $valeur)
	{
		if ($cle == 'categories')
		{
			$tmp = array();
			foreach ($valeur as $codeCategorie) 
			{
				$tmp[] = desinfecte($codeCategorie);
			}
			$postData[$cle] = $tmp;
		}
		else
			$postData[$cle] = desinfecte($valeur);
	}

	$valide = valideForm($nomChamps, $postData);

	$produitData = array_splice($postData,0, -2);
	if(isset($produitData['categories']))
	{
		$produitData['categories'] = implode(',',$produitData['categories']);
	}
	else
	{
		$produitData['categories'] = '';
	}
	var_dump($produitData);

	if($valide)
	{
		unset($_SESSION['choix']);
		header('location:./gestionProduitsmenu.php?success');
		exit();
	}

}

require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début des produits -->
	<div class="row">
		<form id="formProduit" method="POST" action="./gestionProduits.php" enctype="multipart/form-data">
			<?php genereForm($nomChamps, $produitData); ?>
		</form>
<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('../footer.php'); ?>
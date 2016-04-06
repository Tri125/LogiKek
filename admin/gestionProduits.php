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
$messageErreurBD;

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

		if (isset($value['COLUMN_KEY']) && $value['COLUMN_KEY'] == 'PRI')
		{
			echo "<input type='hidden' name='".$nomCol."' value='";
			if(isset($produit[$nomCol]))
				echo $produit[$nomCol];
			else
				echo -1;
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
				switch($type)
				{
					case 'int':
						if(!preg_match( "/^[0-9]{1,}$/", $input ))
						{
							$messagesErreur[$colName] = "Le champs dois être un entier positif.";
							$estValide = false;
						}
						break;
						
					case 'float':
						if(!preg_match( "/^[0-9]+[.,][0-9]{2}$/", $input ))
						{
							$messagesErreur[$colName] = "Le champs dois être un réel positif avec deux décimals (10.00).";
							$estValide = false;
						}
						break;
						
					default:
						throw new Exception('Validation de ce type de champs non défini.');
						break;
				}
			}
			else
			{
				$messagesErreur[$colName] = "Le champs dois être numérique.";
				$estValide = false;
			}
		}
	}
	
	//Vérifie si chacun des champs est set et non vide.
	foreach ($data as $key => $value) 
	{
		if ($key == 'categories')
			continue;
		if (isset($value) && strlen($value) == 0)
		{
			$estValide = false;
			$messagesErreur[$key] = "Champs vide.";
		}
	}

	return $estValide;
}


function validationImage($file, $idProduit = 0)
{
	global $messagesErreur;
	$valide = true;
	$uploadDir = ROOT_DIR.'/img/produits/';
	$types = array('image/jpeg', 'image/png');
	
	foreach($file as $key => $imageForm)
	{
		if ($imageForm['error'] == UPLOAD_ERR_OK)
		{
			if (!in_array($imageForm['type'], $types))
			{
				$messagesErreur[$key] = "Type de fichier refusé.";
				$valide = false;
				continue;
			}
			$info = pathinfo($imageForm['name']);
			$extension = $info['extension'];
			
			switch($key)
			{
				case 'petitePhoto':
					$nomPhoto = $idProduit.'_small';
				break;
				
				case 'grandePhoto':
					$nomPhoto = $idProduit.'_big';
				break;
				
				default:
					$nomPhoto = $idProduit.'_mystere';
				break;
			}
			
			$uploadLocation = $uploadDir.basename($nomPhoto.'.'.'png');
			var_dump($uploadLocation);
			
			if (is_uploaded_file($imageForm['tmp_name']))
			{
				if (!move_uploaded_file($imageForm['tmp_name'], $uploadLocation))
				{
					$messagesErreur[$key] = "Problème de copie du fichier temporaire.";
					$valide = false;
					continue;
				}
			}
			else
			{
				$messagesErreur[$key] = "Tentative de piratage.";
				$valide = false;
				continue;
			}

		}
		elseif ($imageForm['error'] == UPLOAD_ERR_FORM_SIZE || $imageForm['error'] == UPLOAD_ERR_INI_SIZE)
		{
			$messagesErreur[$key] = "La taille de l'image excède 100 KB.";
			$valide = false;
			continue;
		}
		elseif ($imageForm['error'] != UPLOAD_ERR_NO_FILE)
		{
			$messagesErreur[$key] = "Erreur lors de l'envoi de l'image.";
			$valide = false;
			continue;
		}
	}

	return $valide;
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

	if($valide)
	{
		//var_dump($_FILES);
		if ($valide)
		{
			$produit = new Produit($produitData);
			$idProduit;
			try
			{
				if(!$maBD->autoCommit(false))
					throw new Exception('Problème autocommit false');

				if ($produit->getcodeProduit() == -1)
				{
					$reponse = $maBD->creeProduit($produit);
					$idProduit = $reponse['idProduit'];
				}
				else
				{
					$idProduit = $produit->getcodeProduit();
					$reponse = $maBD->ModifProduit($produit);					
				}
				$valide = validationImage($_FILES, $idProduit);
				
				if ($valide)
				{
					if(!$maBD->commit())
						throw new Exception('Impossible de faire un commit');	
				}
				else
				{
					if(!$maBD->rollback())
						throw new Exception('Rollback a échoué');			
				}
				if(!$maBD->autoCommit(true))
					throw new Exception('Problème autocommit true');
			}
			catch (Exception $e)
			{
				$valide = false;
				
				if ($e->getMessage() == 1062)
					$messageErreurBD = 'Le nom du produit est déjà utilisé.';
				else
					$messageErreurBD = 'Erreur de traitement BD: '. $e->getMessage();
			}
			
			if($valide)
			{
				header('location:./gestionProduitsmenu.php?success');
				exit();
			}
		}
	}

}

require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début des produits -->
	<div class="row">
		<?php if(isset($messageErreurBD)): ?>
		<div class="alert alert-danger" role="alert">
			<i class="fa fa-exclamation-triangle"></i>
				<?php echo $messageErreurBD; ?>
		</div>
		<?php endif; ?>
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
<?php 
require_once(realpath(__DIR__.'/..').'/php/biblio/foncCommunes.php');

$js = array();

$css = array();
$css[] = 'index.css';
$titre = 'LogiKek - Gestion Catégories';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';


$CSS_DIR = '../css/';
$JS_DIR = '../js/';
$IMG_DIR = '../img/';


$categories = array();

$categories = recupereCategorie();

function cmp($a, $b)
{
	if ($a->getCodeCategorie() == $b->getCodeCategorie()) 
	{
		return 0;
	}
	return ($a->getCodeCategorie() < $b->getCodeCategorie()) ? -1 : 1;
}


function compare_categories($a, $b) 
{
	if ($a->getNom() < $b->getNom()) 
	{
		return -1;
	} 
	elseif ($a->getNom() > $b->getNom()) 
	{
		return 1;
	} 
	else 
	{
		return 0;
	}
}

function recupereCategorie()
{
	$categories = Categorie::fetchAll();
	usort($categories, "cmp");
	return $categories;
}

function getCat($tab)
{
	$resultats = array();

	foreach ($tab as $key => $value) 
	{
		$pos = strpos($key, 'cat');
		if ($pos === false)
			continue;
		else
		{
			$categorie = array();
			$id = substr($key, $pos+3);
			$categorie['idCategorie'] = $id;
			$categorie['nom'] = $value;
			
			$resultats[] = new Categorie($categorie);
		}
	}
	return $resultats;
}

//Si on arrive à la page après le bouton submit 'valider' du formulaire a été cliqué.
if (isset($_POST['valider']))
{
	$tabFormulaire = array();

	//Le POST contient toutes les données entré par l'utilisateur dans le formulaire.
	//On désinfecte les données et les enregistres dans le tableau.
	foreach ($_POST as $cle => $valeur)
	{
		$tabFormulaire[$cle] = desinfecte($valeur);
	}
	$resultats = getCat($tabFormulaire);
	
	$diff = array_udiff($resultats, $categories, 'compare_categories');
	
	$doitRafraichir = false;
	
	if (count($diff) > 0)
	{
		try
		{
			foreach($diff as $value)
			{
				//Update les catégories en BD.
				$resultat = $maBD->updateCategorie($value);
				if ($resultat >= 1)
					$doitRafraichir = true;
			}
		}
		catch (Exception $e)
		{
			exit();
		}
	}

	if (!empty($tabFormulaire['nouvelle']))
	{
		try
		{
			//insert la nouvelle catégorie en BD.
			$resultat = $maBD->insertCategorie($tabFormulaire['nouvelle']);
			
			$doitRafraichir = true;
		}
		catch (Exception $e)
		{
			//Exception d'insertion d'un doublon dans la base de données.
			if ($e->getMessage() == 1062)
				header("location:./gestionCategories.php?erreur=doublon");
			//Autre exception
			else
				header("location:./gestionCategories.php?erreur=".$e->getMessage());
			exit();
		}
	}
	
	if ($doitRafraichir)
		$categories = recupereCategorie();
}

require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début des produits -->
	<div class="row">
		<form id='formModif' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
			<h3>Modifier des catégories existantes</h3>
			<?php foreach($categories as $value): ?>
				<label><?php echo $value->getCodeCategorie(); ?>:</label>
				<input type="text" name="<?php echo 'cat'.$value->getCodeCategorie(); ?>" value="<?php echo $value->getNom(); ?>">
				<br>
			<?php endforeach; ?>
			<hr>
			<h3>Ajouter une catégorie</h3>
			<label>Catégorie:</label>
			<input type="text" name="nouvelle">
			<hr>
			<input type="submit" name="valider" value="Soumettre">
		</form>


<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('../footer.php'); ?>
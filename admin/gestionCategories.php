<?php 

//-----------------------------
//Page pour rajouter et modifier des catégories.
//-----------------------------

require_once(realpath(__DIR__.'/..').'/php/biblio/foncCommunes.php');

$js = array();

$css = array();
$css[] = 'index.css';
$titre = 'LogiKek - Gestion Catégories';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

//Path relatif vers les dossiers ressources.
$CSS_DIR = '../css/';
$JS_DIR = '../js/';
$IMG_DIR = '../img/';

global $maBD;

$categories = array();

$categories = recupereCategorie();

//-----------------------------
//Fonction de comparaison pour ordonné deux objets 
//Catégorie selon leur code de catégorie
//-----------------------------
function cmp($a, $b)
{
	if ($a->getCodeCategorie() == $b->getCodeCategorie()) 
	{
		return 0;
	}
	return ($a->getCodeCategorie() < $b->getCodeCategorie()) ? -1 : 1;
}

//-----------------------------
//Fonction de comparaison pour deux objets Catégories
//selon leur nom.
//-----------------------------
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

//-----------------------------
//Fonction pour récupérer les données de Catégories de produit de la BD
//et ordonner les résultats.
//Retourne un array.
//-----------------------------
function recupereCategorie()
{
	$categories = Categorie::fetchAll();
	usort($categories, "cmp");
	return $categories;
}

//-----------------------------
//Fonction qui crée un tableau d'objets Categorie à partir
// d'un tableau passé en paramètre des données de formulaire.
//-----------------------------
function getCat($tab)
{
	$resultats = array();

	foreach ($tab as $key => $value) 
	{
		//Récupère la position du string 'cat'.
		//Chaque input à un name du format 'cat#'
		$pos = strpos($key, 'cat');
		//Précaution
		if ($pos === false)
			continue;
		else
		{
			$categorie = array();
			//Retire 'cat' du key pour récupéré le numéro de catégorie.
			$id = substr($key, $pos+3);
			$categorie['idCategorie'] = $id;
			$categorie['nom'] = $value;
			//Crée l'objet Catégorie.
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
	
	//Obtien un tableau d'objets Catégories avec les données du POST.
	$resultats = getCat($tabFormulaire);
	
	//Avec le tableau de Catégories contenu dans la BD, les Catégories du POST et une fonction de comparaison
	//obtien un tableau contenant les catégories modifiées.
	$diff = array_udiff($resultats, $categories, 'compare_categories');
	
	//Indique si une modification a eu lieu. Pour rafraichir les données lorsque la BD est à jour.
	$doitRafraichir = false;
	
	//Si au moins une catégorie a été modifié.
	if (count($diff) > 0)
	{
		try
		{
			foreach($diff as $value)
			{
				//Update les catégories en BD.
				$resultat = $maBD->updateCategorie($value);
				//Si au moins une row a été modifié en BD.
				if ($resultat >= 1)
					$doitRafraichir = true;
			}
		}
		catch (Exception $e)
		{
			exit();
		}
	}
	//Si une nouvelle catégorie doit être créé.
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
	//Si on doit rafraichir, récupère les nouvelles Catégorie en BD.
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
		<?php if (isset($_GET['erreur'])): //Si le drapeau d'erreur a été envoyé dans le GET?>
		<!-- Message d'erreur -->
		<div class="alert alert-danger" role="alert">
			<i class="fa fa-exclamation-triangle"></i>
			<?php if($_GET['erreur'] == 'doublon'): //Erreur de doublon en base de données?>
			Nom de catégorie déjà existant.
			<?php else: //Pour tout autre erreur?>
			Erreur lors du traitement.
			<?php endif; ?>
		</div>
		<?php endif; ?>
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
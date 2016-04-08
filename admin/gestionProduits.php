<?php 

//-----------------------------
//Page pour modifier et créer un nouveau produit.
//-----------------------------

require_once(realpath(__DIR__.'/..').'/php/biblio/foncCommunes.php');

$js = array();

$css = array();
$css[] = 'formulaire.css';
$titre = 'LogiKek - Gestion Produits';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

//Path relatif vers les dossiers ressources.
$CSS_DIR = '../css/';
$JS_DIR = '../js/';
$IMG_DIR = '../img/';

global $maBD;

//Tableau qui contiendra les caractéristiques de la table Produits en BD.
$nomChamps = array();

//Récupère tout les produits dans notre catalogue.
$catalogue = new Catalogue(0, '');

//Contiendra les données d'un produit en BD.
$produitData = null;

//Flag de validation des données entrés par l'usager.
$valide = false;

//Mode de fonctionnement: nouveau produit ou modification d'un produit existant.
$choix = null;

//Tableau de messages d'erreur de validation.
$messagesErreur = array();

//Message d'erreur lors des opérations en BD.
$messageErreurBD;

//Enregistre la valeur du POST.
if(isset($_POST['choix']))
{
	$choix = $_POST['choix'];
}


//Si le POST ne contient pas choix ET valider (donc, aucune opération sur le formulaire de la page, donc navigation directe)
//Ou si le POST contient 'annuler', alors redirection vers la page gestionProduitsmenu pour sélectionner la modif ou la création.
if ((!isset($_POST['choix']) && !isset($_POST['valider'])) || isset($_POST['annuler']))
{
	header('location:./gestionProduitsmenu.php');
	exit();
}

try
{
	//Récupère les caractéristiques de la table Produits en BD.
	$nomChamps = $maBD->columnsName('Produits');

	//Génère les clée du tableau messagesErreur selon nomChamps.
	$messagesErreur = genereMessages($nomChamps);
	
	//Si le choix est set et qu'il n'est pas 'nouveau', alors mode modification de produit.
	//Contient le nom du produit.
	if (isset($choix) && $choix != 'nouveau')
	{
		//Récupère les données du produit en BD.
		$produitData = $maBD->selectProduit($choix);
	}
}
catch (Exception $e)
{
	exit();
}


//-----------------------------
//Fonction qui crée un tableau avec comme clée les nom des colonnes de nomChamps passé en paramètre.
//Retourne un tableau.
//Utiliser pour l'affichage de message d'erreur.
//-----------------------------
function genereMessages($nomChamps)
{
	$messagesErreur = array();

	//Pour chaque colonne de nomChamps.
	foreach ($nomChamps as $key => $value) 
	{
		//Insert comme clée le nom de la colonne dans le tableau
		$messagesErreur[$value['COLUMN_NAME']] = '';
	}
	//Rajoute les clées pour nos champs de formulaire categories et photos.
	$messagesErreur['categories'] = '';
	$messagesErreur['petitePhoto'] = '';
	$messagesErreur['grandePhoto'] = '';

	return $messagesErreur;
}


//-----------------------------
//Fonction qui crée un formulaire html5 de façon dynamique selon les caractéristiques de la table
//que nous voulons crée le formulaire pour.
//$nomChamps est les caractéristiques de la table en BD, $produit est un produit spécifique, null si en mode création.
//-----------------------------
function genereForm($nomChamps, $produit)
{
	global $messagesErreur;

	//Pour chaque colonne de la table
	foreach ($nomChamps as $key => $value) 
	{
		//Récupère le nom de la colonne.
		$nomCol = $value['COLUMN_NAME'];
		//Affiche un label de la colonne s'il n'est pas une clée primaire.
		if (isset($value['COLUMN_KEY']) && $value['COLUMN_KEY'] != 'PRI')
		{
			//Message d'erreur de validation
			echo "<span class='erreur'>".$messagesErreur[$nomCol]."</span>";
			echo "<br>";
			//Nom du champs avec première lettre en majuscule.
			echo "<label>".ucfirst($nomCol).": </label>";
		}
		//Si c'est une colonne de clée primaire.
		elseif(isset($value['COLUMN_KEY']) && $value['COLUMN_KEY'] == 'PRI')
		{
			//input hidden de la clée.
			echo "<input type='hidden' name='".$nomCol."' value='";
			//Si on modifie un produit, met comme value l'id, sinon -1 pour indiquer un nouveau produit (clée non existante).
			if(isset($produit[$nomCol]))
				echo $produit[$nomCol];
			else
				echo -1;
			echo "'>";
			continue;
		}
		//Si la colonne à un maximum de caractère et qu'il est plus grand que 60.
		if (isset($value['CHARACTER_MAXIMUM_LENGTH']) && $value['CHARACTER_MAXIMUM_LENGTH'] > 60)
		{
			//Génère un textarea pour écrire le texte.
			echo "<br>";
			echo "<textarea rows='4' cols='50' name='".$value['COLUMN_NAME']."'>";
			//Si un produit est set, met son contenu dans le textarea.
			if(isset($produit))
			{
				echo $produit[$nomCol];
			}
			//Sinon, utilise la valeur par défault de la colonne.
			elseif(isset($value['COLUMN_DEFAULT']))
			{
				echo $value['COLUMN_DEFAULT'];
			}
			echo "</textarea>";
		}
		//Pas un long texte ni une clé primaire.
		else
		{
			//Génère un input de type text.
			echo "<input type='text' name='".$nomCol."'";
			//Si le produit est set.
			if(isset($produit))
			{
				//Détermine la longueur du texte contenu dans l'objet produit pour ce champ
				//$size est minimalement 10.
				if (strlen($produit[$nomCol]) < 10)
					$size = 10;
				else
					$size = strlen($produit[$nomCol]);
				//Valeur et taille de l'input.
				echo " value='".$produit[$nomCol]."'";
				echo " size='".$size."'";

			}
			//Sinon, utilise la valeur par défault de la colonne.
			elseif(isset($value['COLUMN_DEFAULT']))
			{
				echo " value='".$value['COLUMN_DEFAULT']."'";
			}
			echo ">";
		}
		echo "<br>";
		echo "<br>";
	}
	//Section hardcoded pour les champs additionnels requis tel que le téléchargement d'image
	//et la sélection de catégories.

	//Message d'erreur de validation pour la sélection de catégories.
	echo "<span class='erreur'>".$messagesErreur['categories']."</span>";
	echo "<br>";
	echo "<label>Catégories: </label>";
	echo "<br>";
	//Génère les checkbox pour le choix de catégories.
	genereCheckBoxCategorie($produit);
	echo "<hr>";
	//Limitation de la taille des fichiers.
	echo "<input type='hidden' name='MAX_FILE_SIZE' value='100000'>";
	//Message d'erreur de validation pour la petite photo.
	echo "<span class='erreur'>".$messagesErreur['petitePhoto']."</span>";
	echo "<br>";
	echo "<label>Petite photo: </label>";
	//input de fichier.
	echo "<input type='file' name='petitePhoto'>";
	echo "<br>";
	//Message d'erreur de validation pour la grande photo.
	echo "<span class='erreur'>".$messagesErreur['grandePhoto']."</span>";
	echo "<br>";
	echo "<label>Grande photo: </label>";
	//input de fichier.
	echo "<input type='file' name='grandePhoto'>";
	echo "<br>";
	//submit pour valider et annuler.
	//Annuler retourne vers le menu.
	echo "<input type='submit' name='valider' value='Valider'>";
	echo "<input type='submit' name='annuler' value='Annuler'>";
}


//-----------------------------
//Fonction qui crée des input de checkbox pour la sélection de catégorie d'un produit.
//Si un tableau représentant les données d'un produit est passé en paramètre, les catégories du produit seront sélectionnées.
//-----------------------------
function genereCheckBoxCategorie($produit = null)
{
	//Récupère un tableau d'objet Catégories de toutes les Catégories en BD.
	$categories = Categorie::fetchAll();
	//Si un produit est passé en paramètre.
	if(isset($produit))
		//Lorsqu'on récupère un produit en BD, les catégories sont présentes dans une colonne et sont séparé par une virgule.
		//Explode le champ categories pour avoir un tableau de toutes les catégories du produit (sans les virgules).
		$tabCategorieSelected = explode(",", $produit['categories']);
	//Pour chaque Catégories en BD.
	foreach ($categories as $key => $value) 
	{
		//À chaque 5 catégories, change de ligne.
		if ($key % 5 == 0)
			echo "<br>";
		//Input de type checkbox. Value est le nom de la catégorie, car nos requête utilise le nom plutôt que l'id.
		//Noté '[]' pour le nom. De cette manière le POST sera un tableau de toute les sélections.
		echo "<input type='checkbox' name='categories[]' value='".$value->getNom()."'";
		//Si produit est set et que la catégorie actuel est contenu dans les catégorie du produit.
		if (isset($produit) && in_array($value->getNom(), $tabCategorieSelected))
			//On la sélectionne.
			echo " checked";
		echo ">".$value->getNom();
	}
}


//-----------------------------
//Fonction qui valide dynamiquement un tableau de donné $data, selon les caractéristiques
//d'une table en BD passé en paramètre ($nomChmaps).
//
//A certaines limitation.
//-----------------------------
function valideForm($nomChamps, $data)
{
	global $messagesErreur;
	//Flag de validation. Assume valide au départ.
	$estValide = true;

	//S'il n'y a pas de clée categories dans les données, alors il n'y a aucune catégorie de sélectionner.
	if (!isset($data['categories']))
	{
		//Message d'erreur de validation pour le champ catégories du formulaire.
		$messagesErreur['categories'] = "Veuillez choisir au moins une catégorie.";
		$estValide = false;
	}

	//Pour chacun des colonnes de la table en BD.
	foreach ($nomChamps as $key => $value) 
	{
		//Récupère le nom de la colonne.
		$colName = $value['COLUMN_NAME'];

		//Si c'est une colonne de clé primaire, il n'y a rien à valider.
		//Techniquement l'utilisateur pourrait modifier l'html du formulaire et briser cette assumption.
		if (isset($value['COLUMN_KEY']) && $value['COLUMN_KEY'] == 'PRI')
			continue;
		//Récupère la donnée à valider pour cette colonne.
		$input = $data[$colName];

		//Si la colonne à une longueur de caractères maximales (généralement un champ texte).
		if (isset($value['CHARACTER_MAXIMUM_LENGTH']))
		{
			//Récupère la longueur maximale.
			$maxLen = $value['CHARACTER_MAXIMUM_LENGTH'];

			//Si la donnée est plus longue que le maximum ou plus petit que 2.
			if(strlen($input) > $maxLen || strlen($input) < 2)
			{
				//Message d'erreur de validation pour ce champ du formulaire.
				$messagesErreur[$colName] = "Le champs dois être entre 2 et $maxLen caractères.";
				$estValide = false;
			}
		}
		//Récupère le type de donnée
		$type = $value['DATA_TYPE'];

		//Si le type est float ou int.
		//Limitation, car beaucoup plus de type numérique dans MySql, mais ce sont les types utilisés
		//dans les champs de notre BD pour LogiKek.
		if ($type == 'float' || $type == 'int')
		{
			//Vérifie que la donnée est numérique.
			if (is_numeric($input))
			{
				//Switch selon le type pour utiliser des règles de validation différente.
				switch($type)
				{
					case 'int':
						//Si ne match pas la regex: Commence et fini par un caractère de chiffre, 1 caractères à plusieurs.
						// ie: 10
						if(!preg_match( "/^[0-9]{1,}$/", $input ))
						{
							//Déjà confirmé que la donnée est numérique, mais si la regex échoue il est sans doute négatif ou avec des décimals.
							//Message d'erreur de validation pour ce champ.
							$messagesErreur[$colName] = "Le champs dois être un entier positif.";
							$estValide = false;
						}
						break;
						
					case 'float':
						//Si ne match pas la regex: Commence et fini par au moins 1 caractère de chiffre, suivi d'un point ou d'une virgule, puis de 2 caractère de chiffre.
						// ie: 12.22
						if(!preg_match( "/^[0-9]+[.,][0-9]{2}$/", $input ))
						{
							//Message d'erreur de validation pour ce champ.
							$messagesErreur[$colName] = "Le champs dois être un réel positif avec deux décimals (10.00).";
							$estValide = false;
						}
						break;
					//Erreur de type, cette fonction devrait être modifié.
					default:
						throw new Exception('Validation de ce type de champs non défini.');
						break;
				}
			}
			//Si contient un caractère de texte.
			else
			{
				//Message d'erreur de validation pour ce champ.
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
		//Validation spécial, car un champ numérique contenant 0 est interprété comme un false (vide) pour la fonction empty().
		if (isset($value) && strlen($value) == 0)
		{
			$estValide = false;
			//Message d'erreur de validation pour ce champ.
			$messagesErreur[$key] = "Champs vide.";
		}
	}
	//Retourne le flag de validation.
	return $estValide;
}



//-----------------------------
//Fonction pour valider les images téléchargé par POST et les sauvegarder sur le serveur web.
//$_FILES est passé en paramètre, ainsi que l'id de produit qui est nécessaire
//pour renommer le fichier une fois sauvegarder sur le server.
//-----------------------------
function validationImage($file, $idProduit = 0)
{
	global $messagesErreur;
	//Flag de validation.
	$valide = true;
	//Dossier cible de sauvegarde des images sur le serveur.
	$uploadDir = ROOT_DIR.'/img/produits/';
	//Tableau de types d'images acceptées.
	$types = array('image/jpeg', 'image/png');
	
	//Pour chaque image. Key est le name de l'input dans le formulaire.
	foreach($file as $key => $image)
	{
		//Si le téléchargement de l'image a réussis.
		if ($image['error'] == UPLOAD_ERR_OK)
		{
			//Si le type de l'image n'est pas un des type accepté.
			if (!in_array($image['type'], $types))
			{
				//Message d'erreur de validation de l'image.
				$messagesErreur[$key] = "Type de fichier refusé.";
				$valide = false;
				continue;
			}
			//Récupère des informations sur le fichier téléchargé.
			$info = pathinfo($image['name']);
			//Obtient l'extension du fichier.
			$extension = $info['extension'];
			
			//switch sur le name de l'input du formulaire.
			//Pour créer le nom de l'image sous laquelle elle sera sauvegardé sur le serveur.
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
			
			//Path (et nom) où l'image sera sauvegardé.
			//Sauvegardé en png, car le catalogue du site web charge des fichiers .png dans les balises img.
			$uploadLocation = $uploadDir.basename($nomPhoto.'.'.'png');
			
			//Pour vérifier que le fichier que l'on traite est véritablement un fichier provenant d'un téléchargement
			//et non un utilisateur qui a truqué le serveur à traiter un fichier local. Mesure de sécurité.
			if (is_uploaded_file($image['tmp_name']))
			{
				//Essaie de sauvegarder le fichier sur le serveur. False s'il y a une erreur.
				if (!move_uploaded_file($image['tmp_name'], $uploadLocation))
				{
					//Message d'erreur de validation de l'image.
					$messagesErreur[$key] = "Problème de copie du fichier temporaire.";
					$valide = false;
					continue;
				}
			}
			//Le fichier passé en paramètre n'est pas un fichier qui viend d'un téléchargement.
			else
			{
				//Message d'erreur de validation de l'image.
				$messagesErreur[$key] = "Tentative de piratage.";
				$valide = false;
				continue;
			}

		}
		//Erreur de téléchargement qui ne respecte pas la taille.
		elseif ($image['error'] == UPLOAD_ERR_FORM_SIZE || $image['error'] == UPLOAD_ERR_INI_SIZE)
		{
			//Message d'erreur de validation de l'image.
			$messagesErreur[$key] = "La taille de l'image excède 100 KB.";
			$valide = false;
			continue;
		}
		//Aucune image téléchargé.
		elseif ($image['error'] != UPLOAD_ERR_NO_FILE)
		{
			//Message d'erreur de validation de l'image.
			$messagesErreur[$key] = "Erreur lors de l'envoi de l'image.";
			$valide = false;
			continue;
		}
	}

	return $valide;
}

//Si on navigue à la page à partir du bouton valider du formulaire.
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
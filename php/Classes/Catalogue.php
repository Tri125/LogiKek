<?php

//-----------------------------
//Classe Catalogue représentant des Produits
//-----------------------------
class Catalogue
{
	protected $catalogue = array();

	//-----------------------------
	//Rajoute le produit $prod au tableau catalogue
	//-----------------------------
	private function ajouterProduit($prod)
	{
		$this->catalogue[] = $prod;
	}

	//-----------------------------
	//Constructeur...
	//$categorie est le code de catégorie si nous voulons un filtre (0 sinon)
	//$critere est une string de recherche, vide si non utilisé
	//-----------------------------
	public function __construct($categorie, $critere)
	{
		global $maBD;
		$condition = "";
		$requeteProduits = "";
		//Nous voulons filtrer avec une catégorie spécifique
		if ($categorie != 0)
		{
			$requeteProduits = "SELECT nom FROM Categories WHERE idCategorie = $categorie";
			try
			{
				//Récupère tout les Catégories de la bd qui correspond aux critères.
				//1 puisque idCategorie est unique
				$tmp = $maBD->select($requeteProduits);
			}
			catch (Exception $e)
			{
				die();
			}
			//Récupère le nom de la catégorie qui sera utilisé pour filtrer les produits.
			//Index à 0, car un seul résultat
			if (isset($tmp[0]))
				$nomCategorie = $tmp[0]['nom'];
			else //Erreur, la catégorie n'existe pas. Données erronées.
				return; 

			$condition = " AND categories LIKE '%$nomCategorie%'";
		}
		//Utilise un GROUP_CONCAT pour avoir les catégories des produits, donc si on fait une recherche
		//avec une catégorie spécifique, on peut juste utiliser un LIKE avec le nom.
		//Idem pour le string de recherche $critere. S'ils ne sont pas utilisé, les chaînes vides permet de tout récupérer.
		$requeteProduits = "SELECT p.idProduit, p.nom, p.description, p.prix, p.quantite, p.quantiteMin, 
    GROUP_CONCAT(c.nom SEPARATOR ',') categories
	FROM Produits p
		INNER JOIN ProduitsCategories pc ON pc.idProduit = p.idProduit
		INNER JOIN Categories c ON c.idCategorie = pc.idCategorie 
	GROUP BY p.idProduit
	HAVING (p.nom LIKE '%$critere%' OR p.description LIKE '%$critere%') $condition 
	ORDER BY p.nom, c.nom ASC;";
		
		try
		{
			foreach($maBD->select($requeteProduits) as $value)
			{
				//Crée un nouveau objet produit pour chaque résultat de la requête
				$produit = new Produit($value);
				$this->ajouterProduit($produit);
			}
		}
		catch (Exception $e)
		{
			die();
		}
	}

	//-----------------------------
	//`Retourne le tableau catalogue
	//-----------------------------
	public function getCatalogue()
	{
		return $this->catalogue;
	}


}

?>

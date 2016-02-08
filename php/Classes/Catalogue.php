<?php


class Catalogue
{
	protected $catalogue = array();

	private function ajouterProduit($prod)
	{
		$this->catalogue[] = $prod;
	}

	public function __construct($categorie, $critere)
	{
		global $maBD;
		$condition = "";
		$requeteProduits = "";

		if ($categorie != 0)
		{
			$requeteProduits = "SELECT nom FROM Categories WHERE idCategorie = $categorie";
			try
			{
				$tmp = $maBD->select($requeteProduits);
			}
			catch (Exception $e)
			{
				die();
			}
			$nomCategorie = $tmp[0]['nom'];

			$condition = " AND categories LIKE '%$nomCategorie%'";
		}
		
		$requeteProduits = "SELECT p.idProduit, p.nom, p.description, p.prix, p.codeProduit, p.quantite, p.quantiteMin, 
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
				$produit = new Produit($value);
				$this->ajouterProduit($produit);
			}
		}
		catch (Exception $e)
		{
			die();
		}
	}

	public function getCatalogue()
	{
		return $this->catalogue;
	}


}

?>
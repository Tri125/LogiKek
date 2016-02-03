<?php

class Catalogue
{
	public $catalogue = array();

	private function ajouterProduit($prod)
	{
		$this->catalogue[] = $prod;
	}

	public function __construct($categorie, $critere)
	{
		global $mysqli;
		$condition = "";
		$requeteProduits = "";

		if ($categorie != 0)
		{
			$requeteProduits = "SELECT nom FROM Categories WHERE idCategorie = $categorie";
			$nomCategorie = $mysqli->query($requeteProduits)->fetch_object()->nom;

			$condition = "WHERE categories LIKE '%$nomCategorie%'";
		}

		$requeteProduits = "SELECT * FROM FetchAllProduits $condition";

		foreach($mysqli->query($requeteProduits) as $value)
		{
			$produit = new Produit($value);
			$this->ajouterProduit($produit);
		}
	}



}

?>
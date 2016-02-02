<?php

class Produit
{
	public $nom;
	public $codeProduit;
	public $prix;
	public $description;
	public $quantite;
	public $quantiteMin;
	public $categories = array();


	public function __construct($tableau)
	{
		$this->nom = $tableau['nom'];
		$this->codeProduit = $tableau['codeProduit'];
		$this->prix = number_format($tableau['prix'], 2);
		$this->description = $tableau['description'];
		$this->quantite = $tableau['quantite'];
		$this->quantiteMin = $tableau['quantiteMin'];
		$this->categories = explode(",", $tableau['categories']);
	}

	public static function fetchAll()
	{
		global $mysqli;
		$produits = array();

		$requeteProduits = 'SELECT * FROM FetchAllProduits';
		foreach($mysqli->query($requeteProduits) as $value)
		{
			$tmp = new Produit($value);
			$produits[] = $tmp;
		}

		return $produits;
	}



}

?>
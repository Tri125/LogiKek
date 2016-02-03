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
		$this->codeProduit = $tableau['idProduit'];
		$this->prix = number_format($tableau['prix'], 2);
		$this->description = $tableau['description'];
		$this->quantite = $tableau['quantite'];
		$this->quantiteMin = $tableau['quantiteMin'];
		$this->categories = explode(",", $tableau['categories']);
	}
}

?>
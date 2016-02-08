<?php

class Produit
{
	protected $nom;
	protected $codeProduit;
	protected $prix;
	protected $description;
	protected $quantite;
	protected $quantiteMin;
	protected $categories = array();


	public function __construct($tableau)
	{
		$this->nom = $tableau['nom'];
		$this->codeProduit = $tableau['idProduit'];
		$this->prix = $tableau['prix'];
		$this->description = $tableau['description'];
		$this->quantite = $tableau['quantite'];
		$this->quantiteMin = $tableau['quantiteMin'];
		$this->categories = explode(",", $tableau['categories']);
	}

	public function getNom()
	{
		return $this->nom;
	}

	public function getcodeProduit()
	{
		return $this->codeProduit;
	}

	public function getPrix()
	{
		return $this->prix;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getQuantite()
	{
		return $this->quantite;
	}

	public function getQuantiteMin()
	{
		return $this->quantiteMin;
	}

	public function getCategories()
	{
		return $this->categories;
	}
}

?>
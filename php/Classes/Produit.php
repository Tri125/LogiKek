<?php

class Produit
{
	private $nom;
	private $codeProduit;
	private $prix;
	private $description;
	private $quantite;
	private $quantiteMin;
	private categories = array();
}

public function __construct($tableau)
{
	$this->nom = $tableau['nom'];
	$this->codeProduit = $tableau['code'];
	$this->prix = $tableau['prix'];
	$this->description = $tableau['description'];
	$this->quantite = $tableau['quantite'];
	$this->quantiteMin = $tableau['quantiteMin'];
	$this->categories = $tableau['categories'];
}

?>
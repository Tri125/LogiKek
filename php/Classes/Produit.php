<?php

//-----------------------------
// Classe qui représente un Produit qu'on vend.
//-----------------------------
class Produit
{
	protected $nom;
	protected $codeProduit;
	protected $prix;
	protected $description;
	//Quantité en inventaire
	protected $quantite;
	protected $quantiteMin;
	//Un produit a plusieurs catégories.
	protected $categories = array();

	//-----------------------------
	//Constructeur...
	//-----------------------------
	public function __construct($tableau)
	{
		$this->nom = $tableau['nom'];
		$this->codeProduit = $tableau['idProduit'];
		$this->prix = $tableau['prix'];
		$this->description = $tableau['description'];
		$this->quantite = $tableau['quantite'];
		$this->quantiteMin = $tableau['quantiteMin'];
		//Les catégories d'un produit reviend de la requête BD dans un GROUP_CONCAT avec "," comme séparateur.
		//Donc, il y a un champ texte nommé categories avec toutes les catégories du produit séparé par une virgule.
		//Utilise la fonction explode pour récupérer les catégories individuellement et les enregistrer dans le tableau categories de l'objet produit. 
		$this->categories = explode(",", $tableau['categories']);
	}

	//-----------------------------
	//Retourne le nom du produit
	//-----------------------------
	public function getNom()
	{
		return $this->nom;
	}

	//-----------------------------
	//Retourne le code du produit
	//-----------------------------
	public function getcodeProduit()
	{
		return $this->codeProduit;
	}

	//-----------------------------
	//Retourne le prix du produit
	//-----------------------------
	public function getPrix()
	{
		return $this->prix;
	}

	//-----------------------------
	//Retourne la description du produit
	//-----------------------------
	public function getDescription()
	{
		return $this->description;
	}

	//-----------------------------
	//Retourne la quantité en inventaire du produit
	//-----------------------------
	public function getQuantite()
	{
		return $this->quantite;
	}

	//-----------------------------
	//Retourne la quantité minimum du produit avant de passer une commande
	//-----------------------------
	public function getQuantiteMin()
	{
		return $this->quantiteMin;
	}

	//-----------------------------
	//Retourne le tableau de catégories du produit
	//-----------------------------
	public function getCategories()
	{
		return $this->categories;
	}
}

?>
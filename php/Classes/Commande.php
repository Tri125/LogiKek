<?php

//-----------------------------
// Classe qui représente une Commande acheté par un client.
//-----------------------------
class Commande
{
	protected $dateCommande;
	protected $idCommande;
	protected $tabArticles;

	//-----------------------------
	//Constructeur...
	//-----------------------------
	public function __construct($tableau)
	{
		$this->tabAchats = array();

		foreach ($tableau as $cle => $valeur)
			$this->$cle = $valeur;
	}

	public function getDateCommande()
	{
		return $this->dateCommande;
	}

	public function getNumCommande()
	{
		return $this->idCommande;
	}

	public function getTabAchats()
	{
		return $this->tabAchats;
	}

}

?>
<?php

//-----------------------------
// Classe qui représente une Commande acheté par un client.
//-----------------------------
class Commande
{
	protected $dateCommande;
	protected $idCommande;
	protected $tabAchats;

	//-----------------------------
	//Constructeur...
	//-----------------------------
	public function __construct($tableau)
	{
		$this->tabAchats = array();

		foreach ($tableau as $cle => $valeur)
			if ($cle == 'tabAchats')
			{
				//Nous voulons crée des objets Achat à partir du sous tableau 'tabAchats' passé en paramètre.
				foreach($tableau[$cle] as $cleAchat => $valeurAchat)
				{
					$tmp = new Achat($valeurAchat);
					//Réajuste la quantité acheté après la construction, constructeur met 1 par default.
					$tmp->setNombre($tableau[$cle][$cleAchat]['nombre']);
					//Rajoute l'objet Achat au tableau tabAchats de la Commande.
					$this->tabAchats[] = $tmp;
				}
			}
			else
				$this->$cle = $valeur;
	}

	//-----------------------------
	//Retourne la date où la commande a été passé
	//-----------------------------
	public function getDateCommande()
	{
		return $this->dateCommande;
	}

	//-----------------------------
	//Retourne le numéro de la commande
	//-----------------------------
	public function getNumCommande()
	{
		return $this->idCommande;
	}

	//-----------------------------
	//Retourne le tableau contenant tout les Achats de la commande
	//-----------------------------
	public function getTabAchats()
	{
		return $this->tabAchats;
	}
	
	//-----------------------------
	//Retourne le Total de la Commande.
	//Soit pour chaque article: leur prix * leur quantité.
	//-----------------------------
	public function Total()
	{
		$total = 0;
		
		foreach($this->tabAchats as $value)
		{
			$total += ($value->Total());
		}
		
		return $total;
	}

}

?>
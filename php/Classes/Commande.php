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
				foreach($tableau[$cle] as $cleAchat => $valeurAchat)
				{
					$tmp = new Achat($valeurAchat);
					$tmp->setNombre($tableau[$cle][$cleAchat]['nombre']);
					$this->tabAchats[] = $tmp;
				}
			}
			else
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
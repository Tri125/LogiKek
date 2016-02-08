<?php

Class Panier
{

	private $tabAchats;

	public function __construct()
	{
		$this->tabAchats = array();
		if (isset($_SESSION['panier'][0]))
			$this->tabAchats = $_SESSION['panier'];

		if (! isset($_SESSION['panier-item']))
			$_SESSION['panier-item'] = 0;
	}

	public function ajouter($id)
	{
		global $maBD;
		$dejaPresent = false;
		$nbAchats = count($this->tabAchats);

		if ($nbAchats > 0)
		{
			for ($i = 0; $i < $nbAchats; $i++)
			{
				if ($id == $this->tabAchats[$i]->getNoProduit())
				{
					$dejaPresent = true;
					break;
				}
			}
		}

		if ($dejaPresent)
		{
			$quantite = $this->tabAchats[$i]->getQuantite();
			$this->tabAchats[$i]->setQuantite($quantite + 1);
		}
		else
		{

			$requeteProduits = "SELECT p.idProduit, p.nom, p.description, p.prix, p.codeProduit, p.quantite, p.quantiteMin, 
    		GROUP_CONCAT(c.nom SEPARATOR ',') categories
			FROM Produits p
				INNER JOIN ProduitsCategories pc ON pc.idProduit = p.idProduit
				INNER JOIN Categories c ON c.idCategorie = pc.idCategorie 
			GROUP BY p.idProduit
			HAVING (p.idProduit = '$id') 
			LIMIT 1;";


			$tmp = $maBD->select($requeteProduits);

			$produit = $tmp[0];

			$achat = new Achat($produit);

			$this->tabAchats[] = $achat;

		}

		$_SESSION['panier'] = $this->tabAchats;
		$_SESSION['panier-item'] += 1;
	}

	public function isEmpty()
	{
		return count($this->tabAchats) == 0;
	}

	public function getTabAchats()
	{
		return $this->tabAchats;
	}
}

?>
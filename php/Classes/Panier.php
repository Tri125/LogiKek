<?php

//-----------------------------
//Classe représentant le panier d'achat d'un client.
//-----------------------------
Class Panier
{
	//tableau des Achats formant le panier.
	private $tabAchats;

	//-----------------------------
	//Constructeur...
	//-----------------------------
	public function __construct()
	{
		$this->tabAchats = array();
		//Vérifie s'il y a quelque chose en mémoire à l'index 0 de la variable de session 'panier'
		if (isset($_SESSION['panier'][0]))
			//Si oui, on récupère l'array pour avoir le contenu de notre Panier persistant.
			$this->tabAchats = $_SESSION['panier'];
		//Si la variable de session panier-item n'est pas set, la crée et la met égal à 0.
		if (! isset($_SESSION['panier-item']))
			//Variable de session panier-item utilisé pour garder le nombre d'article dans le panier persistant entre les fenêtres
			//sans garder une référence à l'objet Panier.
			$_SESSION['panier-item'] = 0;
	}

	//-----------------------------
	//Fonction récupère un Produit de la bd correspondant au critère $id
	//, instancie l'objet et le rajoute au panier.
	//-----------------------------
	public function ajouter($id)
	{
		global $maBD;
		$dejaPresent = false;
		$nbAchats = count($this->tabAchats);

		//Si on a déjà un achat, peut-être qu'on rajoute le même achat.
		if ($nbAchats > 0)
		{
			for ($i = 0; $i < $nbAchats; $i++)
			{
				if ($id == $this->tabAchats[$i]->getCodeProduit())
				{
					$dejaPresent = true;
					break;
				}
			}
		}
		//Si l'achat qu'on veut rajouter est déjà présent
		if ($dejaPresent)
		{
			//Récupère le nombre de fois que l'achat est dans le panier 
			$quantite = $this->tabAchats[$i]->getNombre();
			//L'augmente de 1
			$this->tabAchats[$i]->setNombre($quantite + 1);
		}
		//Achat n'est pas déjà présent
		else
		{

			$requeteProduits = "SELECT p.idProduit, p.nom, p.description, p.prix, p.quantite, p.quantiteMin, 
    		GROUP_CONCAT(c.nom SEPARATOR ',') categories
			FROM Produits p
				INNER JOIN ProduitsCategories pc ON pc.idProduit = p.idProduit
				INNER JOIN Categories c ON c.idCategorie = pc.idCategorie 
			GROUP BY p.idProduit
			HAVING (p.idProduit = '$id') 
			LIMIT 1;";

			try
			{
				$tmp = $maBD->select($requeteProduits);
			}
			catch (Exception $e)
			{
				die();
			}
			//Un select en BD retourne un tableau de résultat, notre requête est assuré d'avoir un seul résultat, donc on prend la variable à l'index 0.
			$produit = $tmp[0];
			//instancie un objet Achat
			$achat = new Achat($produit);
			//Le rajoute à la fin du tableau du Panier.
			$this->tabAchats[] = $achat;

		}
		//Enregistre tabAchats du Panier dans la variable de session 'panier'
		$_SESSION['panier'] = $this->tabAchats;
		//Augmente de 1 la variable de session 'panier-item' pour le nombre de Produit dans le panier
		$_SESSION['panier-item'] += 1;
	}

	//-----------------------------
	//Function suppression qui supprime l'Article à l'index $num de tabAchats
	//-----------------------------
	public function suppression($num)
	{
		//Vérifie si le tableau se rend à l'index
		if (isset($this->tabAchats[$num]))
		{
			$nbAchats = count($this->tabAchats);
			$quantite = $this->tabAchats[$num]->getNombre();
			//Copie les objets du tableau vers la gauche
			for ($i = $num+1; $i < $nbAchats; $i++)
			{
				$this->tabAchats[$i-1] = $this->tabAchats[$i];
			}
			//unset la dernière position du tableau, car il a déjà été copié/effacé vers la gauche
			unset($this->tabAchats[$nbAchats-1]);
			//Enregistre tabAchats du Panier dans la variable de session 'panier'
			$_SESSION['panier'] = $this->tabAchats;
			//Réduit du nombre de produits supprimés du Panier la variable de session 'panier-item'.
			$_SESSION['panier-item'] -= $quantite;
		}
		else
			return;
	}

	//-----------------------------
	//Retourne vrai si tabAchats est égal à 0
	//-----------------------------
	public function isEmpty()
	{
		return count($this->tabAchats) == 0;
	}

	//-----------------------------
	//Retourne le tableau tabAchats
	//-----------------------------
	public function getTabAchats()
	{
		return $this->tabAchats;
	}
}

?>
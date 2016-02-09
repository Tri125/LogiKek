<?php

//Classe utilisé pour avoir une combinaison de Produit et du nombre de fois qui sera acheté. 
class Achat extends Produit
{
	//Nombre de fois que le produit est inclus/acheté.
	//De préférence on l'aurait appelé quantite, mais la classe parent utilise déjà la variable pour la quantité en inventaire.
	protected $nombre;
	
	public function __construct($tableau)
	{
		$this->nombre = 1;
		parent::__construct($tableau);
	}

	public function getNombre()
	{
		return $this->nombre;
	}

	public function setNombre($qt)
	{
		//Dans le domaine défini, il est impossible d'avoir une quantite négative et l'avoir à 0 signifie qu'il n'y en a pas.
		//Du coup l'objet est inutile par définition...bref set uniquement si > 0.
		if ($qt > 0)
			$this->nombre = $qt;
	}

}

?>
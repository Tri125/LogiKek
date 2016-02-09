<?php

//-----------------------------
// Classe Catégorie juste pour avoir toutes les catégories
//-----------------------------
class Categorie
{
	protected $nom;
	protected $codeCategorie;

	//-----------------------------
	// Constructeur...
	//-----------------------------
	public function __construct($tableau)
	{
		$this->nom = $tableau['nom'];
		$this->codeCategorie = $tableau['idCategorie'];
	}
	//-----------------------------
	// Function statique de la classe qui retourne
	// un tableau d'objet Categorie avec toutes les catégories trouvées en BD
	//-----------------------------
	public static function fetchAll()
	{
		global $maBD;
		$categories = array();

		$tabResultat = $maBD->select("SELECT * FROM Categories ORDER BY nom ASC");

		
		foreach($tabResultat as $value)
		{
			//Pour chaque élément de résultat, crée un objet Categorie et le rajoute à la fin
			//du tableau $categories
			$categories[] = new Categorie($value);
		}
		return $categories;
	}

	//-----------------------------
	//Retourne le nom de la catégorie
	//-----------------------------
	public function getNom()
	{
		return $this->nom;
	}

	//-----------------------------
	//Retourne le code de la catégorie
	//-----------------------------
	public function getCodeCategorie()
	{
		return $this->codeCategorie;
	}
}

?>
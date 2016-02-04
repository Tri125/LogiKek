<?php

class Categorie
{
	public $nom;
	public $codeCategorie;


	public function __construct($tableau)
	{
		$this->nom = $tableau['nom'];
		$this->codeCategorie = $tableau['idCategorie'];
	}

	public static function fetchAll()
	{
		global $maBD;
		$categories = array();

		$tabResultat = $maBD->select("SELECT * FROM Categories ORDER BY nom ASC");

		
		foreach($tabResultat as $value)
		{
			$categories[] = new Categorie($value);
		}
		return $categories;
	}
}

?>
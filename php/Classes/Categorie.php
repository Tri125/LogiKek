<?php

class Categorie
{
	public $nom;
	public $codeCategorie;


	public function __construct($tableau)
	{
		$this->nom = $tableau['nom'];
		$this->codeCategorie = $tableau['codeCategorie'];
	}

	public static function fetchAll()
	{
		global $mysqli;
		$categories = array();

		$requeteCategories = 'SELECT * FROM FetchAllCategories';
		foreach($mysqli->query($requeteCategories) as $value)
		{
			$tmp = new Categorie($value);
			$categories[] = $tmp;
		}

		return $categories;
	}
}

?>
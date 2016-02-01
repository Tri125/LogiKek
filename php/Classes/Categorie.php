<?php

class Categorie
{
	private $nom;
	private $codeCategorie;
}

public function __construct($tableau)
{
	$this->nom = $tableau['nom'];
	$this->codeCategorie = $tableau['code'];
}

?>
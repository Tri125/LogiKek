<?php

class Achat extends Produit
{
	protected $nombre;
	
	public function __construct($tableau)
	{
		$this->nombre = 1;
		parent::__construct($tableau);
	}

	public function getQuantite()
	{
		return $this->nombre;
	}

	public function setQuantite($qt)
	{
		if ($qt > 0)
			$this->nombre = $qt;
	}

	public function getNoProduit()
	{
		return $this->codeProduit;
	}
}

?>
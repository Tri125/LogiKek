<?php

class Achat extends Produit
{
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
		if ($qt > 0)
			$this->nombre = $qt;
	}

}

?>
<?php
class bdService
{
	private $BDInterne;
	//-----------------------------
	//
	//-----------------------------
	function __construct()
	{
		$config = parse_ini_file('./config.ini');

		$this->BDInterne = new mysqli($config['ip'], $config['username'], $config['password'], $config['dbname']);
		if (!mysqli_connect_errno())
			echo "connexion OK <br>";
		else
		{
			throw(new Exception("�chec de connexion."));
		}
	}
	//-----------------------------
	//
	//-----------------------------
	function insert($ins)
	{
		$resultat = $this->BDInterne->query($ins);
		
		if (empty($resultat))
			throw(new Exception("Erreur d'insertion ".$this->BDInterne->errno));
		return $this->BDInterne->insert_id;
	}
	
	//-----------------------------
	//
	//-----------------------------
	function select($sel)
	{
		$tabRetour = array();
		$Res = $this->BDInterne->query($sel);
		// ASSOC pour le nom des champs
		// NUM pour les index
		// BOTH pour les deux � la fois
		if (empty($Res))
		{
			throw(new Exception("Erreur de s�lection ".$this->BDInterne->errno));
		}
		while($ligne = $Res->fetch_array(MYSQL_ASSOC))
		{
			$tabRetour[] = $ligne;
		}
		return $tabRetour;
	}
}

?>
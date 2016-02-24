<?php
class bdService
{
	private $BDInterne;
	//-----------------------------
	//Constructeur...
	//-----------------------------
	function __construct()
	{
		//Parse le fichier de configuration de bd config.ini
		$config = parse_ini_file('./config.ini');
		//Cr�e un objet mysqli selon les param�tres du fichier de config.
		$this->BDInterne = new mysqli($config['ip'], $config['username'], $config['password'], $config['dbname']);
		if (!mysqli_connect_errno())
		{
			//echo "connexion OK <br>";
		}
		else
		{
			throw(new Exception("�chec de connexion."));
		}
		//Sp�cifie le charset pour ne pas avoir de probl�me si Apache est configur� diff�rament sous un autre syst�me.
		if (!$this->BDInterne->set_charset("utf8")) {
    		printf("Error loading character set utf8: %s\n", $this->BDInterne->error);
    	}
	}
	//-----------------------------
	//Lance la requ�te $ins comme insert en bd et retourne le r�sultat (l'id du dernier �l�ment rajout�)
	//-----------------------------
	function insert($ins)
	{
		$resultat = $this->BDInterne->query($ins);
		
		if (empty($resultat))
			throw(new Exception("Erreur d'insertion ".$this->BDInterne->errno));
		return $this->BDInterne->insert_id;
	}

	//-----------------------------
	//Insert un client pass� en param�tre dans la BD � l'aide d'une d�claration pr�par�
	//-----------------------------
	function insertClient($client)
	{
		//Requ�te qui sera pr�par�
		$requete = "INSERT INTO Clients (sexe, nom, prenom, courriel, adresse, ville, province, codePostal, telephone, nomUtilisateur, motDePasse) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		//retourne faux si une erreur c'est produit
		if($stmt = $this->BDInterne->prepare($requete))
		{
			//Binding des param�tres de la d�claration pr�par� aux param�tres du client. s pour type string
			$stmt->bind_param("sssssssssss", $client->getSexe(), $client->getNom(), $client->getPrenom()
				, $client->getCourriel(), $client->getAdresse(), $client->getVille()
				, $client->getProvince(), $client->getCodePostal(), $client->getTelephone()
				, $client->getNomUtilisateur(), $client->getMotDePasse());
			
			//Ex�cute la requ�te. Si vrai, r�ussite, sinon faux.
			if($stmt->execute())
			{
				$nouveau_id = $this->BDInterne->insert_id;
			}
			//Lance une exception avec le code d'erreur.
			else
				throw(new Exception($this->BDInterne->errno));
			//Ferme la d�claration pr�par� pour permettre les requ�tes avenir.
			$stmt->close();

			return $nouveau_id;
		}
		else
		{
			throw (new Exception("Erreur lors de l'envois de la d�claration pr�par� au serveur"));
		}
	}

	//-----------------------------
	//Update un client pass� en param�tre dans la BD � l'aide d'une d�claration pr�par�
	//-----------------------------
	function updateClient($client)
	{
		//Requ�te qui sera pr�par�
		$requete = "UPDATE Clients SET sexe = ?, nom = ?, prenom = ?, courriel = ?, adresse = ?, ville = ?, province = ?, codePostal = ?, telephone = ?, nomUtilisateur = ?, motDePasse = ? WHERE nomUtilisateur = ?";

		//retourne faux si une erreur c'est produit
		if($stmt = $this->BDInterne->prepare($requete))
		{
			//Binding des param�tres de la d�claration pr�par� aux param�tres du client. s pour type string
			$stmt->bind_param("ssssssssssss", $client->getSexe(), $client->getNom(), $client->getPrenom()
				, $client->getCourriel(), $client->getAdresse(), $client->getVille()
				, $client->getProvince(), $client->getCodePostal(), $client->getTelephone()
				, $client->getNomUtilisateur(), $client->getMotDePasse() , $client->getNomUtilisateur());
			
			//Ex�cute la requ�te. Si vrai, r�ussite, sinon faux.
			if($stmt->execute())
			{
				$nouveau_id = $this->BDInterne->insert_id;

				$nbrLigneModifier = $stmt->affected_rows;
			}
			//Lance une exception avec le code d'erreur.
			else
				throw(new Exception($this->BDInterne->errno));
			//Ferme la d�claration pr�par� pour permettre les requ�tes avenir.
			$stmt->close();

			return $nbrLigneModifier;
		}
		else
		{
			throw (new Exception("Erreur lors de l'envois de la d�claration pr�par� au serveur"));
		}
	}
	
	//-----------------------------
	//Lance la requ�te de select $sel et retourne le r�sultat comme tableau.
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
<?php

//-----------------------------
// Classe de service de base de données pour les diverses opérations en BD.
//-----------------------------
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
		//Crée un objet mysqli selon les paramètres du fichier de config.
		$this->BDInterne = new mysqli($config['ip'], $config['username'], $config['password'], $config['dbname']);
		if (!mysqli_connect_errno())
		{
			//echo "connexion OK <br>";
		}
		else
		{
			throw(new Exception("Échec de connexion."));
		}
		//Spécifie le charset pour ne pas avoir de problème si Apache est configuré différament sous un autre système.
		if (!$this->BDInterne->set_charset("utf8")) {
    		printf("Error loading character set utf8: %s\n", $this->BDInterne->error);
    	}
	}
	//-----------------------------
	//Lance la requète $ins comme insert en bd et retourne le résultat (l'id du dernier élément rajouté)
	//-----------------------------
	function insert($ins)
	{
		$resultat = $this->BDInterne->query($ins);
		
		if (empty($resultat))
			throw(new Exception("Erreur d'insertion ".$this->BDInterne->errno));
		return $this->BDInterne->insert_id;
	}

	//-----------------------------
	//Insert en BD avec une déclaration préparé
	//
	// $requete est la requête qui sera préparé
	// $args est un array contenant le type de données de la requête
	// ie: 'ss'
	// $values Est un array des données à modifier.
	// Retourne l'id de la nouvelle insertion.
	//-----------------------------
	private function insertPrepared($requete, $args, $values)
	{
		//Fusionne les arrays en un seul
		$params = array_merge($args, $values);
 
		//Transforme les éléments du tableau en référence plutôt qu'en valeur
		foreach($params as $key => &$value)
		{
			$params[$key] = &$value;
		}
		//retourne faux si une erreur c'est produit
		if($stmt = $this->BDInterne->prepare($requete))
		{
			//Par réflection, obtient une référence à la classe stmt de mysqli
			$ref = new ReflectionClass('mysqli_stmt');
			//Par réflection, obtient une référence à la méthode bind_param.
			$method = $ref->getMethod("bind_param");
			
			//Invoque la méthode bind_param par réflection sur l'objet $stmt et la tableau $params comme paramètre.
			$method->invokeArgs($stmt, $params); 
			
			//call_user_func_array(array($stmt, 'bind_param'), $params);
			
			//Exécute la requête. Si vrai, réussite, sinon faux.
			if($stmt->execute())
			{
				$nouveau_id = $this->BDInterne->insert_id;
			}
			//Lance une exception avec le code d'erreur.
			else
				throw(new Exception($this->BDInterne->errno));
			//Ferme la déclaration préparé pour permettre les requêtes avenir.
			$stmt->close();

			return $nouveau_id;
		}
		else
		{
			throw (new Exception("Erreur lors de l'envois de la déclaration préparé au serveur"));
		}
	}	
	
	//-----------------------------
	//Insert un client passé en paramètre dans la BD à l'aide d'une déclaration préparé
	//-----------------------------
	function insertClient($client)
	{
		//Requête qui sera préparé
		$requete = "INSERT INTO Clients (sexe, nom, prenom, courriel, adresse, ville, province, codePostal, telephone, nomUtilisateur, motDePasse) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$args = array('sssssssssss');
		
		$values = array($client->getSexe(), $client->getNom(), $client->getPrenom()
				, $client->getCourriel(), $client->getAdresse(), $client->getVille()
				, $client->getProvince(), $client->getCodePostal(), $client->getTelephone()
				, $client->getNomUtilisateur(), $client->getMotDePasse());

		return $this->insertPrepared($requete, $args, $values);
	}


	//-----------------------------
	//Update en BD avec une déclaration préparé
	//
	// $requete est la requête qui sera préparé
	// $args est un array contenant le type de données de la requête
	// ie: 'ss'
	// $values Est un array des données à modifier.
	// Retourne le nombre de ligne modifié.
	//-----------------------------
	private function updatePrepared($requete, $args, $values)
	{
		//Fusionne les arrays en un seul
		$params = array_merge($args, $values);
 
		//Transforme les éléments du tableau en référence plutôt qu'en valeur
		foreach($params as $key => &$value)
		{
			$params[$key] = &$value;
		}
		//retourne faux si une erreur c'est produit
		if($stmt = $this->BDInterne->prepare($requete))
		{
			//Par réflection, obtient une référence à la classe stmt de mysqli
			$ref = new ReflectionClass('mysqli_stmt');
			//Par réflection, obtient une référence à la méthode bind_param.
			$method = $ref->getMethod("bind_param");
			
			//Invoque la méthode bind_param par réflection sur l'objet $stmt et la tableau $params comme paramètre.
			$method->invokeArgs($stmt, $params); 
			
			//call_user_func_array(array($stmt, 'bind_param'), $params);
			
			//Exécute la requête. Si vrai, réussite, sinon faux.
			if($stmt->execute())
			{
				$nbrLigneModifier = $stmt->affected_rows;
			}
			//Lance une exception avec le code d'erreur.
			else
				throw(new Exception($this->BDInterne->errno));
			//Ferme la déclaration préparé pour permettre les requêtes avenir.
			$stmt->close();

			return $nbrLigneModifier;
		}
		else
		{
			throw (new Exception("Erreur lors de l'envois de la déclaration préparé au serveur"));
		}
	}

	//-----------------------------
	//Update un client passé en paramètre dans la BD à l'aide d'une déclaration préparé
	//-----------------------------
	function updateClient($client)
	{
		//Requête qui sera préparé
		$requete = "UPDATE Clients SET sexe = ?, nom = ?, prenom = ?, courriel = ?, adresse = ?, ville = ?, province = ?, codePostal = ?, telephone = ?, nomUtilisateur = ?, motDePasse = ? WHERE nomUtilisateur = ?";
		$args = array('ssssssssssss');
		
		$values = array($client->getSexe(), $client->getNom(), $client->getPrenom()
				, $client->getCourriel(), $client->getAdresse(), $client->getVille()
				, $client->getProvince(), $client->getCodePostal(), $client->getTelephone()
				, $client->getNomUtilisateur(), $client->getMotDePasse() , $client->getNomUtilisateur());

		return $this->updatePrepared($requete, $args, $values);
	}
	
	//-----------------------------
	//Update le mot de passe du client passé en paramètre dans la BD à l'aide d'une déclaration préparé
	//-----------------------------
	function updateMotDePasse($client)
	{
		$requete = "UPDATE Clients SET motDePasse = ? WHERE nomUtilisateur = ?";
		$args = array('ss');
		
		$values = array($client->getMotDePasse(), $client->getNomUtilisateur());
		
		return $this->updatePrepared($requete, $args, $values);
	}


	//-----------------------------
	//Select en BD avec une déclaration préparé
	//
	// $requete est la requête qui sera préparé
	// $args est un array contenant le type de données de la requête
	// ie: 'ss'
	// $values Est un array des données à modifier.
	// Retourne le résultat de la requête.
	//-----------------------------
	private function selectPrepared($requete, $args, $values)
	{
		//Fusionne les arrays en un seul
		$params = array_merge($args, $values);
 
		//Transforme les éléments du tableau en référence plutôt qu'en valeur
		foreach($params as $key => &$value)
		{
			$params[$key] = &$value;
		}
		//retourne faux si une erreur c'est produit
		if($stmt = $this->BDInterne->prepare($requete))
		{
			//Par réflection, obtient une référence à la classe stmt de mysqli
			$ref = new ReflectionClass('mysqli_stmt');
			//Par réflection, obtient une référence à la méthode bind_param.
			$method = $ref->getMethod("bind_param");
			
			//Invoque la méthode bind_param par réflection sur l'objet $stmt et la tableau $params comme paramètre.
			$method->invokeArgs($stmt, $params); 
			
			//call_user_func_array(array($stmt, 'bind_param'), $params);
			
			//Exécute la requête. Si vrai, réussite, sinon faux.
			if($stmt->execute())
			{
				$tabRetour = array();

				$resultat = $stmt->get_result();
				if ($resultat == false)
					throw(new Exception($this->BDInterne->errno));

				while($ligne = $resultat->fetch_array(MYSQL_ASSOC))
				{
					$tabRetour[] = $ligne;
				}

				if (count($tabRetour) == 0)
					return null;
			}
			//Lance une exception avec le code d'erreur.
			else
				throw(new Exception($this->BDInterne->errno));
			//Ferme la déclaration préparé pour permettre les requêtes avenir.
			$stmt->close();

			return $tabRetour;
		}
		else
		{
			throw (new Exception("Erreur lors de l'envois de la déclaration préparé au serveur"));
		}
	}


	//-----------------------------
	//Select un client avec son nom d'utilisateur passé en paramètre dans la BD à l'aide d'une déclaration préparé
	//-----------------------------
	function selectClient($nomUtilisateur)
	{
		//Requête qui sera préparé
		$requete = "SELECT * FROM Clients WHERE nomUtilisateur = ?";
		$args = array('s');
		
		$values = array($nomUtilisateur);
		$resultats = $this->selectPrepared($requete, $args, $values);
		return $resultats[0];
	}
	
	
	function selectClientMotDePasse($nomUtilisateur, $motDePasse)
	{
		//Requête qui sera préparé
		$requete = "SELECT * FROM Clients WHERE nomUtilisateur = ? AND motDePasse = ?";
		$args = array('ss');
		
		$values = array($nomUtilisateur, $motDePasse);
		$resultats = $this->selectPrepared($requete, $args, $values);
		return $resultats[0];
	}
	
	//-----------------------------
	//Lance la requête de select $sel et retourne le résultat comme tableau.
	//-----------------------------
	function select($sel)
	{
		$tabRetour = array();
		$Res = $this->BDInterne->query($sel);
		// ASSOC pour le nom des champs
		// NUM pour les index
		// BOTH pour les deux à la fois
		if (empty($Res))
		{
			throw(new Exception("Erreur de sélection ".$this->BDInterne->errno));
		}
		while($ligne = $Res->fetch_array(MYSQL_ASSOC))
		{
			$tabRetour[] = $ligne;
		}
		return $tabRetour;
	}

	public function neutralise($info)
	{
		return $this->BDInterne->real_escape_string($info);
	}
}

?>
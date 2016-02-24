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
		//Crée un objet mysqli selon les paramêtres du fichier de config.
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
	//Lance la requête $ins comme insert en bd et retourne le résultat (l'id du dernier élément rajouté)
	//-----------------------------
	function insert($ins)
	{
		$resultat = $this->BDInterne->query($ins);
		
		if (empty($resultat))
			throw(new Exception("Erreur d'insertion ".$this->BDInterne->errno));
		return $this->BDInterne->insert_id;
	}

	//-----------------------------
	//Insert un client passé en paramètre dans la BD à l'aide d'une déclaration préparé
	//-----------------------------
	function insertClient($client)
	{
		//Requête qui sera préparé
		$requete = "INSERT INTO Clients (sexe, nom, prenom, courriel, adresse, ville, province, codePostal, telephone, nomUtilisateur, motDePasse) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		//retourne faux si une erreur c'est produit
		if($stmt = $this->BDInterne->prepare($requete))
		{
			//Binding des paramètres de la déclaration préparé aux paramètres du client. s pour type string
			$stmt->bind_param("sssssssssss", $client->getSexe(), $client->getNom(), $client->getPrenom()
				, $client->getCourriel(), $client->getAdresse(), $client->getVille()
				, $client->getProvince(), $client->getCodePostal(), $client->getTelephone()
				, $client->getNomUtilisateur(), $client->getMotDePasse());
			
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
	//Update un client passé en paramètre dans la BD à l'aide d'une déclaration préparé
	//-----------------------------
	function updateClient($client)
	{
		//Requête qui sera préparé
		$requete = "UPDATE Clients SET sexe = ?, nom = ?, prenom = ?, courriel = ?, adresse = ?, ville = ?, province = ?, codePostal = ?, telephone = ?, nomUtilisateur = ?, motDePasse = ? WHERE nomUtilisateur = ?";

		//retourne faux si une erreur c'est produit
		if($stmt = $this->BDInterne->prepare($requete))
		{
			//Binding des paramètres de la déclaration préparé aux paramètres du client. s pour type string
			$stmt->bind_param("ssssssssssss", $client->getSexe(), $client->getNom(), $client->getPrenom()
				, $client->getCourriel(), $client->getAdresse(), $client->getVille()
				, $client->getProvince(), $client->getCodePostal(), $client->getTelephone()
				, $client->getNomUtilisateur(), $client->getMotDePasse() , $client->getNomUtilisateur());
			
			//Exécute la requête. Si vrai, réussite, sinon faux.
			if($stmt->execute())
			{
				$nouveau_id = $this->BDInterne->insert_id;

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
}

?>
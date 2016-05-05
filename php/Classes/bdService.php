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
		$config = parse_ini_file(ROOT_DIR.'/config.ini');
		//Crée un objet mysqli selon les paramètres du fichier de config.
		$this->BDInterne = new mysqli($config['ip'], $config['username'], $config['password'], $config['dbname']);
		if (mysqli_connect_errno())
		{
			throw new Exception("Échec de connexion." . mysqli_connect_error());	
		}
		//Spécifie le charset pour ne pas avoir de problème si Apache est configuré différament sous un autre système.
		if (!$this->BDInterne->set_charset("utf8")) 
		{
    			printf("Error loading character set utf8: %s\n", $this->BDInterne->error);
    		}
	}

	//-----------------------------
	//Commence une transaction
	//-----------------------------
	function transaction($flag)
	{
		return $this->BDInterne->begin_transaction($flag);
	}


	//-----------------------------
	//Commit une transaction
	//-----------------------------
	function commit()
	{
		return $this->BDInterne->commit();
	}

	//-----------------------------
	//Rollback une transaction
	//-----------------------------
	function rollback()
	{
		return $this->BDInterne->rollback();
	}

	//-----------------------------
	//Change le mode d'autocommit
	//-----------------------------
	function autoCommit($flag)
	{
		return $this->BDInterne->autocommit($flag);
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
	//Retourne les caractéristiques de la table en BD d'on le nom est passé en paramètre.
	//-----------------------------
	function columnsName($nomTable)
	{
		$nomTable = $this->neutralise($nomTable);
		
		$tabResultat = $this->select("SELECT `COLUMN_NAME`, `CHARACTER_MAXIMUM_LENGTH` , `COLUMN_KEY`, `COLUMN_DEFAULT`, `DATA_TYPE`
										FROM `INFORMATION_SCHEMA`.`COLUMNS` 
										WHERE `TABLE_NAME`='$nomTable'");
		return $tabResultat;
	}
	
	//-----------------------------
	//Select les produits dont la quantité en stock 
	// est inférieure à la quantité minimale.
	//-----------------------------
	function selectRapportProduit()
	{
		//Requête
		$requete = "SELECT nom, quantite, quantiteMin
					FROM Produits
					WHERE quantite < quantiteMin
					ORDER BY nom ASC";
		$resultats = $this->select($requete);
		return $resultats;		
	}
	
	
	//-----------------------------
	//Select les commandes/commandesProduits passé.
	//-----------------------------
	function selectRapportFacture()
	{
		//Requête
		$requete = "SELECT c.idCommande, cl.prenom, cl.nom, c.dateCommande 
					FROM Commandes AS c
					INNER JOIN Clients AS cl ON c.idClient = cl.idClient
					ORDER BY dateCommande DESC";

		//Sélectionne les commandes des clients
		$commandes = $this->select($requete);

		if (isset($commandes))
		{
			//Pour chaque commande
			foreach ($commandes as $key => $value) 
			{
				$requete = "SELECT p.idProduit, p.nom, p.description, ROUND(p.prix, 2) as prix, p.quantite, p.quantiteMin, 
							GROUP_CONCAT(c.nom SEPARATOR ',') categories, cp.quantite AS nombre
							FROM CommandesProduits AS cp
							INNER JOIN Produits AS p ON p.idProduit = cp.idProduit
							INNER JOIN ProduitsCategories pc ON pc.idProduit = p.idProduit
							INNER JOIN Categories c ON c.idCategorie = pc.idCategorie 
							WHERE cp.idCommande =".$value['idCommande'].
							" GROUP BY p.idProduit 
							ORDER BY p.nom ASC";

				//Sélectionne les informations détaillés de la commande tel que les noms des produits, la quantité, etc.
				$produits = $this->select($requete);
				//Rajoute un tableau tabAchats contenant ces informations sous la clé de la commande actuelle.
				$commandes[$key]['tabAchats'] = $produits;
			}
		}
		return $commandes;		
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
	//Insert un produit passé en paramètre dans la BD à l'aide d'une déclaration préparé
	//-----------------------------
	private function insertProduit($produit)
	{
		//Requête qui sera préparé
		$requete = "INSERT INTO Produits (nom, prix, description, quantite, quantiteMin) VALUES (?, ?, ?, ?, ?)";
		$args = array('sdsii');
		
		$values = array($produit->getNom(), $produit->getPrix(), $produit->getDescription()
				, $produit->getQuantite(), $produit->getQuantiteMin());

		return $this->insertPrepared($requete, $args, $values);
	}

	//-----------------------------
	//Insert un produitCatégorie passé en paramètre dans la BD à l'aide d'une déclaration préparé
	//-----------------------------
	private function insertProduitCategorie($produit)
	{
		$reponse = array();
		foreach ($produit->getCategories() as $categorie)
		{
			//Requête qui sera préparé
			$requete = "INSERT INTO ProduitsCategories (idProduit, idCategorie) VALUES ((SELECT idProduit FROM Produits WHERE nom = ?), (SELECT idCategorie FROM Categories WHERE nom = ?))";
			$args = array('ss');
		
			$values = array($produit->getNom(), $categorie);
			$reponse[] = $this->insertPrepared($requete, $args, $values);
		}

		return $reponse;
	}

	//-----------------------------
	//Crée un produit passé en paramètre dans la BD à l'aide d'une déclaration préparé
	//-----------------------------
	function creeProduit($produit)
	{
		$reponse = array();

		$reponse['idProduit'] = $this->insertProduit($produit);
		$reponse['idCategories']= $this->insertProduitCategorie($produit);

		return $reponse;
	}

	//-----------------------------
	//Insert une commande avec un client passé en paramètre dans la BD à l'aide d'une déclaration préparé
	//-----------------------------
	private function insertCommande($client)
	{
		//Requête qui sera préparé
		$requete = "INSERT INTO Commandes (idClient) VALUES ( (SELECT idClient From Clients WHERE nomUtilisateur = ?) )";
		$args = array('s');
		
		$values = array($client->getNomUtilisateur());

		return $this->insertPrepared($requete, $args, $values);
	}


	//-----------------------------
	//Insert une commandeProduit avec un achat passé en paramètre dans la BD à l'aide d'une déclaration préparé
	//-----------------------------
	private function insertCommandeProduit($idCommande, $achat)
	{
		//Requête qui sera préparé
		$requete = "INSERT INTO CommandesProduits (idCommande, idProduit, quantite) VALUES ( ?, (SELECT idProduit From Produits WHERE nom = ?), ? )";
		$args = array('isi');
		
		$values = array($idCommande, $achat->getNom(), $achat->getNombre());

		return $this->insertPrepared($requete, $args, $values);
	}
	
	//-----------------------------
	//Insert une catégorie de produit dans la BD à partir de son nom passé en paramètre à l'aide d'une déclaration préparé
	//-----------------------------
	function insertCategorie($nom)
	{
		//Requête qui sera préparé
		$requete = "INSERT INTO Categories (nom) VALUES (?)";
		$args = array('s');
		
		$values = array($nom);

		return $this->insertPrepared($requete, $args, $values);
	}	
	

	//-----------------------------
	//Responsable de compléter une transaction d'une commande en BD avec un client et un panier passé en paramètre
	//-----------------------------
	function PasserCommande($client, $panier)
	{
		//Tableau des résultats des requêtes.
		$resultat = array();

		//Crée un sous tableau pour contenir les id des insertions dans la table CommandesProduits.
		$resultat['idCommandeProduit'] = array();

		//Met à jour les quantités en inventaire
		$resultat['updateQte'] = $this->updateQteStock($panier);
		//Fait l'insertion d'une commande
		$resultat['idCommande'] = $this->insertCommande($client);

		//Pour chaque achat dans le panier
		foreach ($panier->getTabAchats() as $achat)
		{
			//Insertion dans la table CommandesProduits, détaillant les produits faisant parties de la commande.
			$resultat['idCommandeProduit'][] = $this->insertCommandeProduit($resultat['idCommande'], $achat);
		}

		return $resultat;
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
	//Update une catégorie de produit passé en paramètre dans la BD à l'aide d'une déclaration préparé
	//-----------------------------
	function updateCategorie($categorie)
	{
		//Requête qui sera préparé
		$requete = "UPDATE Categories SET nom = ? WHERE idCategorie = ?";
		$args = array('si');
		
		$values = array($categorie->getNom(), $categorie->getCodeCategorie());

		return $this->updatePrepared($requete, $args, $values);
	}

	//-----------------------------
	//Update un produit passé en paramètre dans la BD à l'aide d'une déclaration préparé
	//-----------------------------
	private function updateProduit($produit)
	{
		//Requête qui sera préparé
		$requete = "UPDATE Produits SET nom = ?, prix = ?, description = ?, quantite = ?, quantiteMin = ? WHERE idProduit = ?";
		$args = array('sdsiii');
		
		$values = array($produit->getNom(), $produit->getPrix(), $produit->getDescription(), $produit->getQuantite(), $produit->getQuantiteMin(), $produit->getcodeProduit());

		return $this->updatePrepared($requete, $args, $values);
	}

	//-----------------------------
	//Modifie un produit passé en paramètre dans la BD à l'aide d'une déclaration préparé
	//-----------------------------
	function ModifProduit($produit)
	{
		$reponse = array();

		$reponse[] = $this->updateProduit($produit);

		$reponse[] = $this->deleteProduitCategorie($produit);

		$reponse[] = $this->insertProduitCategorie($produit);

		return $reponse;
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
	//Update la quantité en inventaire en BD des articles commandé dans un objet Panier passé en paramètre à l'aide d'une déclaration préparé
	//Retrait la quantité. 
	//TODO: ajuster la query pour faire negatif et positif.
	//-----------------------------
	private function updateQteStock($panier)
	{
		//Requête qui sera préparé
		$requete = "UPDATE Produits SET quantite = quantite - ? WHERE nom = ?";
		$args = array('is');
		$nbrLigneModifier = 0;

		foreach ($panier->getTabAchats() as $achat)
		{
			$values = array($achat->getNombre(), $achat->getNom());
			$nbrLigneModifier += $this->updatePrepared($requete, $args, $values);
		}

		return $nbrLigneModifier;
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

				while($ligne = $resultat->fetch_array(MYSQLI_ASSOC))
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
	//Pour savoir si le compte existe.
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
	

	//-----------------------------
	//Select un client avec son nom d'utilisateur passé en paramètre et son mot de passe dans la BD à l'aide d'une déclaration préparé
	// pour la connexion.
	//-----------------------------
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
	//Select un produit avec son nom passé en paramètre dans la BD à l'aide d'une déclaration préparé.
	//-----------------------------
	function selectProduit($nom)
	{
		//Requête qui sera préparé
		$requete = "SELECT p.idProduit, p.nom, p.description, REPLACE(FORMAT(ROUND(p.prix, 2),2), ',', '') as prix, p.quantite, p.quantiteMin, 
							GROUP_CONCAT(c.nom SEPARATOR ',') categories
							FROM Produits p
							INNER JOIN ProduitsCategories pc ON pc.idProduit = p.idProduit
							INNER JOIN Categories c ON c.idCategorie = pc.idCategorie 
							GROUP BY p.idProduit
							HAVING p.nom = ?
							ORDER BY p.nom, c.nom ASC";
		$args = array('s');
		
		$values = array($nom);
		$resultats = $this->selectPrepared($requete, $args, $values);
		return $resultats[0];		
	}


	//-----------------------------
	//Select une commande avec les produits associé d'un client dans la BD à l'aide d'une déclaration préparé.
	//-----------------------------
	function selectCommandeDetails($nomUtilisateur)
	{

		//Requête qui sera préparé
		$requete = "SELECT idCommande, dateCommande 
					FROM Commandes
					WHERE idClient = (SELECT idClient FROM Clients WHERE nomUtilisateur = ?)
					ORDER BY dateCommande DESC";
		$args = array('s');
		
		$values = array($nomUtilisateur);

		//Sélectionne les commandes du client
		$commandes = $this->selectPrepared($requete, $args, $values);
		if (isset($commandes))
		{
			//Pour chaque commande
			foreach ($commandes as $key => $value) 
			{
				$requete = "SELECT p.idProduit, p.nom, p.description, ROUND(p.prix, 2) as prix, p.quantite, p.quantiteMin, 
							GROUP_CONCAT(c.nom SEPARATOR ',') categories, cp.quantite AS nombre
							FROM CommandesProduits AS cp
							INNER JOIN Produits AS p ON p.idProduit = cp.idProduit
							INNER JOIN ProduitsCategories pc ON pc.idProduit = p.idProduit
							INNER JOIN Categories c ON c.idCategorie = pc.idCategorie 
							WHERE cp.idCommande = ?
							GROUP BY p.idProduit
							ORDER BY p.nom ASC";
				$args = array('i');
				$values = array($value['idCommande']);
				//Sélectionne les informations détaillés de la commande tel que les noms des produits, la quantité, etc.
				$produits = $this->selectPrepared($requete, $args, $values);
				//Rajoute un tableau tabAchats contenant ces informations sous la clé de la commande actuelle.
				$commandes[$key]['tabAchats'] = $produits;
			}
		}
		return $commandes;		
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
		while($ligne = $Res->fetch_array(MYSQLI_ASSOC))
		{
			$tabRetour[] = $ligne;
		}
		return $tabRetour;
	}

	//-----------------------------
	//Annule la commande passé en paramètre en supprimant les entrés en BD et 
	//réajuster la quantité des produits en inventaire.
	//Objet Commande passé en paramètre.
	//-----------------------------
	function annulationCommande($commande)
	{
		$requete = "UPDATE Produits AS p
					SET p.quantite = p.quantite + ?
					WHERE p.nom = ?";
		$args = array('is');

		//Pour tout les achats de la commande
		foreach ($commande->getTabAchats() as $value) 
		{
			$values = array($value->getNombre(), $value->getNom());
			//Réajuste le nombre en inventaire.
			$this->updatePrepared($requete, $args, $values);
		}

		$requete = "DELETE cp.*, c.*
					FROM CommandesProduits AS cp
					INNER JOIN Commandes AS c
					ON c.idCommande = cp.idCommande
					WHERE c.idCommande = ?";
		$args = array('i');
		
		$values = array($commande->getNumCommande());
		//Supression des entrées de la table CommandesProduits et Commandes avec idCommande correspondant.
		//La suppression ce fait en cascade.
		$resultats = $this->deletePrepared($requete, $args, $values);

		return $resultats;
	}

	//-----------------------------
	//Delete en BD avec une déclaration préparé
	//
	// $requete est la requête qui sera préparé
	// $args est un array contenant le type de données de la requête
	// ie: 'ss'
	// $values Est un array des données à modifier.
	// Retourne le résultat de la requête.
	//-----------------------------
	private function deletePrepared($requete, $args, $values)
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
	//Supprime les produitsCategories associé à un produit passé en paramètre par un requête préparé
	//-----------------------------
	private function deleteProduitCategorie($produit)
	{
		$requete = "DELETE pc.*
					FROM ProduitsCategories AS pc
					WHERE pc.idProduit = ?";
		$args = array('i');
		
		$values = array($produit->getcodeProduit());
		//Supression des entrées de la table ProduitsCategories idProduit correspondant.
		$resultats = $this->deletePrepared($requete, $args, $values);

		return $resultats;
	}

	//-----------------------------
	// Fonction qui neutralise le texte passé en paramètre
	// pour une base de donnée mysql et le retourne.
	//-----------------------------
	public function neutralise($info)
	{
		return $this->BDInterne->real_escape_string($info);
	}
}

?>

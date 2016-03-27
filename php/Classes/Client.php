<?php
//-----------------------------
//Classe utilisé pour représenté un compte client
//-----------------------------
class Client
{
	
	protected $sexe;
	protected $nom;
	protected $prenom;
	protected $courriel;
	protected $adresse;
	protected $ville;
	protected $province;
	protected $codePostal;
	protected $telephone;
	protected $nomUtilisateur;
	protected $motDePasse;
	protected $motDePasseExpire;
	
	public function __construct($tableau)
	{
		foreach ($tableau as $cle => $valeur)
			$this->$cle = $valeur;

		//$this->setCryptoPassword($this->motDePasse);
	}

	//-----------------------------
	// Retourne le sexe de l'utilisateur
	//-----------------------------
	public function getSexe()
	{
		return $this->sexe;
	}
	
	//-----------------------------
	// Met à jour le sexe de l'utilisateur
	//-----------------------------
	public function setSexe($sexe)
	{
		$this->sexe = $sexe;
	}

	//-----------------------------
	// Retourne le nom de l'utilisateur
	//-----------------------------
	public function getNom()
	{
		return $this->nom;
	}

	//-----------------------------
	// Met à jour le nom de l'utilisateur
	//-----------------------------
	public function setNom($nom)
	{
		$this->nom = $nom;
	}

	//-----------------------------
	// Retourne le prénom de l'utilisateur
	//-----------------------------
	public function getPrenom()
	{
		return $this->prenom;
	}

	//-----------------------------
	// Met à jour le prénom de l'utilisateur
	//-----------------------------
	public function setPrenom($prenom)
	{
		$this->prenom = $prenom;
	}

	//-----------------------------
	// Retourne le courriel de l'utilisateur
	//-----------------------------
	public function getCourriel()
	{
		return $this->courriel;
	}

	//-----------------------------
	// Met à jour le courriel de l'utilisateur
	//-----------------------------
	public function setCourriel($courriel)
	{
		$this->courriel = $courriel;
	}

	//-----------------------------
	// Retourne l'adresse civique de l'utilisateur
	//-----------------------------
	public function getAdresse()
	{
		return $this->adresse;
	}

	//-----------------------------
	// Met à jour l'adresse civique de l'utilisateur
	//-----------------------------
	public function setAdresse($adresse)
	{
		$this->adresse = $adresse;
	}

	//-----------------------------
	// Retourne la ville de l'utilisateur
	//-----------------------------
	public function getVille()
	{
		return $this->ville;
	}

	//-----------------------------
	// Met à jour la ville de l'utilisateur
	//-----------------------------
	public function setVille($ville)
	{
		$this->ville = $ville;
	}

	//-----------------------------
	// Retourne la province de l'utilisateur
	//-----------------------------
	public function getProvince()
	{
		return $this->province;
	}

	//-----------------------------
	// Met à jour la province de l'utilisateur
	//-----------------------------
	public function setProvince($province)
	{
		$this->province = $province;
	}

	//-----------------------------
	// Retourne le code postal de l'utilisateur
	//-----------------------------
	public function getCodePostal()
	{
		return $this->codePostal;
	}

	//-----------------------------
	// Met à jour le code postal de l'utilisateur
	//-----------------------------
	public function setCodePostal($codePostal)
	{
		$this->codePostal = $codePostal;
	}

	//-----------------------------
	// Retourne le numéro de téléphone de l'utilisateur
	//-----------------------------
	public function getTelephone()
	{
		return $this->telephone;
	}

	//-----------------------------
	// Met à jour le numéro de téléphone de l'utilisateur
	//-----------------------------
	public function setTelephone($telephone)
	{
		$this->telephone = $telephone;
	}

	//-----------------------------
	// Retourne le nom d'utilisateur de l'utilisateur
	//-----------------------------
	public function getNomUtilisateur()
	{
		return $this->nomUtilisateur;
	}

	//-----------------------------
	// Met à jour le nom d'utilisateur de l'utilisateur
	//-----------------------------
	public function setNomUtilisateur($nomUtilisateur)
	{
		$this->nomUtilisateur = $nomUtilisateur;
	}
	
	//-----------------------------
	// Retourne le mot de passe de l'utilisateur
	//-----------------------------
	public function getMotDePasse()
	{
		return $this->motDePasse;
	}
	
	//-----------------------------
	// Met à jour le mot de passe de l'utilisateur
	//-----------------------------
	public function setMotDePasse($motDePasse)
	{
		$this->motDePasse = $motDePasse;
	}


	//-----------------------------
	//Retourne le statut du mot de passe
	//-----------------------------	
	public function getMotDePasseExpire()
	{
		return $this->motDePasseExpire;
	}

	//-----------------------------
	//Met a jour le statut du mot de passe
	//-----------------------------	
	public function setMotDePasseExpire($motDePasseExpire)
	{
		$this->motDePasseExpire = $motDePasseExpire;
	}


	//-----------------------------
	// Retourne vrai si le mot de passe (plaintext) en paramètre correspond au hash du mot de passe du client
	//-----------------------------
	public function isPasswordMatch($motDePasse)
	{
		return password_verify($this->motDePasse, $motDePasse);
	}

	public function setCryptoPassword($motDePasse)
	{
		$this->motDePasse = password_hash($motDePasse, PASSWORD_BCRYPT);
	}

}

?>
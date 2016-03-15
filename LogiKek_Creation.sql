CREATE DATABASE IF NOT EXISTS `1081849_LogiKek`
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE `1081849_LogiKek`;

DROP TABLE IF EXISTS CommandesProduits;
DROP TABLE IF EXISTS Commandes;
DROP TABLE IF EXISTS ProduitsCategories;
DROP TABLE IF EXISTS Produits;
DROP TABLE IF EXISTS Categories;
DROP TABLE IF EXISTS Clients;


CREATE TABLE IF NOT EXISTS Produits
(
	idProduit INT PRIMARY KEY AUTO_INCREMENT
    , nom VARCHAR(50) NOT NULL
    , prix float
    , description TEXT
    , quantite INT DEFAULT 0
    , quantiteMin INT NOT NULL DEFAULT 1
)
CHARACTER SET utf8 COLLATE utf8_unicode_ci;


ALTER TABLE Produits
ADD CONSTRAINT Produits_nom_UK
UNIQUE (nom);

CREATE TABLE IF NOT EXISTS Categories
(
	idCategorie INT PRIMARY KEY AUTO_INCREMENT
    , nom VARCHAR (20) NOT NULL
)
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE Categories
ADD CONSTRAINT Categories_nom_UK
UNIQUE (nom);


CREATE TABLE IF NOT EXISTS ProduitsCategories
(
	idProduitCategorie INT PRIMARY KEY AUTO_INCREMENT
    , idProduit INT NOT NULL
    , idCategorie INT NOT NULL
)
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE ProduitsCategories
ADD CONSTRAINT ProduitsCategories_idProduit_idCategorie
UNIQUE (idProduit, idCategorie);

ALTER TABLE ProduitsCategories
ADD CONSTRAINT ProduitsCategories_Produits_FK
FOREIGN KEY (idProduit) REFERENCES Produits (idProduit);

ALTER TABLE ProduitsCategories
ADD CONSTRAINT ProduitsCategories_Categories_FK
FOREIGN KEY (idCategorie) REFERENCES Categories (idCategorie);

/*
CREATE TABLE IF NOT EXISTS Sexes
(
	idSexe INT PRIMARY KEY AUTO_INCREMENT
    , nom VARCHAR(1) NOT NULL
)
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE Sexes
ADD CONSTRAINT Sexes_nom_UK
Unique (nom);
*/

CREATE TABLE IF NOT EXISTS Clients
(
	idClient INT PRIMARY KEY AUTO_INCREMENT
    , sexe VARCHAR(1) NOT NULL
    , nom VARCHAR(20) NOT NULL
    , prenom VARCHAR(20) NOT NULL
    , courriel VARCHAR(255) NOT NULL
    , adresse VARCHAR(40) NOT NULL
    , ville VARCHAR(30) NOT NULL
    , province VARCHAR(2) NOT NULL
    , codePostal VARCHAR(6) NOT NULL
    , telephone VARCHAR(20) NOT NULL
    , nomUtilisateur VARCHAR(15) BINARY NOT NULL
    , motDePasse VARCHAR(255) BINARY NOT NULL
    , cryptMotDePasse VARCHAR(255) BINARY
)
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE Clients
ADD CONSTRAINT Clients_usager_UK
UNIQUE (nomUtilisateur);


CREATE TABLE IF NOT EXISTS Commandes
(
	idCommande INT PRIMARY KEY AUTO_INCREMENT
    , idClient INT NOT NULL
    , dateCommande TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
)
CHARACTER SET utf8 COLLATE utf8_unicode_ci;


ALTER TABLE Commandes
ADD CONSTRAINT Commandes_Clients_FK
FOREIGN KEY (idClient) REFERENCES Clients (idClient);


CREATE TABLE IF NOT EXISTS CommandesProduits
(
	idCommandeProduit INT PRIMARY KEY AUTO_INCREMENT
    , idCommande INT NOT NULL
    , idProduit INT NOT NULL
)
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE CommandesProduits
ADD CONSTRAINT CommandesProduits_Commandes_FK
FOREIGN KEY (idCommande) REFERENCES Commandes (idCommande);

ALTER TABLE CommandesProduits
ADD CONSTRAINT CommandesProduits_Produits_FK
FOREIGN KEY (idProduit) REFERENCES Produits (idProduit);

/*
ALTER TABLE Clients
ADD CONSTRAINT Clients_Sexes_FK
FOREIGN KEY (idSexe) REFERENCES Sexes (idSexe);
*/

/*
DELIMITER //

CREATE TRIGGER 1081849_LogiKek.salt_generation 
BEFORE INSERT 
	ON Clients FOR EACH ROW
BEGIN
	SET NEW.salt := (SELECT UUID());
END;//

DELIMITER ;

*/

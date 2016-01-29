CREATE DATABASE IF NOT EXISTS `LogiKek`
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE `LogiKek`;

DROP TABLE IF EXISTS ProduitsCategories;
DROP TABLE IF EXISTS Produits;
DROP TABLE IF EXISTS Categories;


CREATE TABLE IF NOT EXISTS Produits
(
	idProduit INT PRIMARY KEY AUTO_INCREMENT
    , codeProduit VARCHAR(20) NOT NULL
    , nom VARCHAR(50) NOT NULL
    , prix float NOT NULL
    , description VARCHAR(255) NOT NULL DEFAULT "Description non disponible"
    , quantite INT DEFAULT 0
    , quantiteMin INT NOT NULL DEFAULT 1
)
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE Produits
ADD CONSTRAINT Produits_codeProduit_UK
UNIQUE (codeProduit);

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
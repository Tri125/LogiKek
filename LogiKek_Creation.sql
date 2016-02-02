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
    , prix float
    , description TEXT
    , quantite INT DEFAULT 0
    , quantiteMin INT NOT NULL DEFAULT 1
)
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE Produits
ADD CONSTRAINT Produits_codeProduit_UK
UNIQUE (codeProduit);

ALTER TABLE Produits
ADD CONSTRAINT Produits_nom_UK
UNIQUE (nom);

CREATE TABLE IF NOT EXISTS Categories
(
	idCategorie INT PRIMARY KEY AUTO_INCREMENT
    , codeCategorie INT NOT NULL
    , nom VARCHAR (20) NOT NULL
)
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE Categories
ADD CONSTRAINT Categories_nom_UK
UNIQUE (nom);

ALTER TABLE Categories
ADD CONSTRAINT Categories_codeCategoie_UK
UNIQUE (codeCategorie);

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


CREATE OR REPLACE VIEW FetchAllCategories AS
	SELECT codeCategorie, nom 
    FROM Categories
    ORDER  BY nom ASC;
    
    
CREATE OR REPLACE VIEW Catalog AS
	SELECT nom, prix, quantite 
    FROM Produits
    ORDER  BY nom DESC;
    
CREATE OR REPLACE VIEW FetchAllProduits AS
	SELECT p.nom, p.description, p.prix, p.codeProduit, p.quantite, p.quantiteMin, c.codeCategorie, 
    GROUP_CONCAT(c.nom SEPARATOR ',') categories
	FROM Produits p
		INNER JOIN ProduitsCategories pc ON pc.idProduit = p.idProduit
		INNER JOIN Categories c ON c.idCategorie = pc.idCategorie 
	GROUP BY p.idProduit
	ORDER BY p.nom, c.nom ASC;
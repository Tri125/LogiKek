#Insertion Categories

INSERT INTO Categories (nom)
VALUES ('Microsoft');

INSERT INTO Categories (nom)
VALUES ('Linux');

INSERT INTO Categories (nom)
VALUES ('BSD');

INSERT INTO Categories (nom)
VALUES ('RHEL');

INSERT INTO Categories (nom)
VALUES ('Apple');

INSERT INTO Categories (nom)
VALUES ('Stable');

INSERT INTO Categories (nom)
VALUES ('Fine pointe');

INSERT INTO Categories (nom)
VALUES ('Oracle');

INSERT INTO Categories (nom)
VALUES ('32-bit');

INSERT INTO Categories (nom)
VALUES ('64-bit');

INSERT INTO Categories (nom)
VALUES ('Google');




#Insertion Produits

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('Arch Linux 32-bit', 10, 'Simple, moderne, pragmatique, centré sur l\'utilisateur et versatile. Arch Linux est une distribution minimaliste avec les touts derniers développement technologiques.',
10, 1);


INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('Arch Linux 64-bit', 15, 'Simple, moderne, pragmatique, centré sur l\'utilisateur et versatile. Arch Linux est une distribution minimaliste avec les touts derniers développement technologiques.',
10, 1);


INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('Windows 7 Home Premium 64-bit', 199.99, 'Inclus toutes les fonctionalités de Windows 7 Home Basic. Inclus Windows Media Center. Windows Aero. Supporte jusqu\'à 16 GB de mémoire vive.',
10, 1);


INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('Windows 7 Professionnel 64-bit', 569.99, 'Inclus toutes les fonctionalités de Windows 7 Home. Exécuter des applications de Windows XP dans le mode Windows XP. Vous permet de joindre des domaines. Faites vos sauvegarde de secours à la maison ou sur le réseau corporatif.',
10, 1);

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('Ubuntu 15.10 (Wily Werewolf)', 30.99, 'Inclus la version 32-bit et 64-bit. Ubuntu est une platforme logiciel à source ouverte qui roule dans le Cloud, du téléphone intelligent, à toute vos choses. Interface intuitive. Rapide et sécuritaire avec des milliers d\'application disponibles. Ubuntu à ce que vous avez besoin.',
10, 1);

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('FreeNAS 9.3 32-bit', 25.99, 'FreeNAS est un système d\'exploitation qui peut être installer sur n\'importe quel platforme matériel pour partager le stockage de données informatiques sur un réseau informatique.',
10, 1);

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('FreeNAS 9.3 64-bit', 29.99, 'FreeNAS est un système d\'exploitation qui peut être installer sur n\'importe quel platforme matériel pour partager le stockage de données informatiques sur un réseau informatique.',
10, 1);

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('CentOS-7 64-bit', 50.99, 'La distribution de Linux CentOS est stable, prévisible, gérable et reproductible dérivé des sources de Red Hat Enterprise Linux (RHEL).',
10, 1);

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('CentOS-7 32-bit', 50.99, 'La distribution de Linux CentOS est stable, prévisible, gérable et reproductible dérivé des sources de Red Hat Enterprise Linux (RHEL).',
10, 1);

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('Red Star OS', 0.00, 'La distribution de choix du chef suprême. Version 32-bit et 64-bit inclus.',
10, 1);

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('Hannah Montana Linux 32-bit', 13.00, 'Linux pour les fans de la pop star.',
10, 1);

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('Hannah Montana Linux 64-bit', 36.99, 'Linux pour les fans de la pop star.',
10, 1);

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('Linux Mint 64-bit', 26.99, 'Distribution de GNU/Linux idéal pour les nouveaux utilisateurs. Environnement familier aux utilisateur de système Windows pour facilité la transition dans le monde de GNU/Linux.',
10, 1);

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('Linux Mint 32-bit', 26.99, 'Distribution de GNU/Linux idéal pour les nouveaux utilisateurs. Environnement familier aux utilisateur de système Windows pour facilité la transition dans le monde de GNU/Linux.',
10, 1);

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('OS X v10.11 El Capitan', 1025.99, 'Système stable et éprouvé. Pour système Macintosh et Hackintosh.',
10, 1);

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('Gentoo', 3.99, 'Compiler votre sysème en entier avec Gentoo. Expérience éducative incontournable.',
10, 1);

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('Windows 10', 0.00, 'Inclus plusieurs fonctionalités de surveillances. Parfait pour monsieurs et madame tout le monde, inutile pour le power user.',
10, 1);

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('Solaris 11', 215.99, 'Le système d\'exploitation corporatif le plus avancé. Il livre sécurité, vitesse et simplicité aux entreprises.',
10, 1);

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('Kali Linux 32-bit', 22.95, 'Inclus tout les outils nécessaire pour mettre en oeuvre des testes de pénétration système. Idéal lorsqu\'utilisé en liveBoot. Plusieurs dictionnaires inclus, parfais pour des attaques dictionnaires.',
10, 1);

INSERT INTO Produits (nom, prix, description, quantite, quantiteMin)
VALUES ('Kali Linux 64-bit', 22.95, 'Inclus tout les outils nécessaire pour mettre en oeuvre des testes de pénétration système. Idéal lorsqu\'utilisé en liveBoot. Plusieurs dictionnaires inclus, parfais pour des attaques dictionnaires.',
10, 1);


#Insertion ProduitsCategories

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Arch Linux 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Linux')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Arch Linux 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = '32-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Arch Linux 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Fine pointe')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Arch Linux 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Linux')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Arch Linux 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Fine pointe')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Arch Linux 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = '64-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Windows 7 Home Premium 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = '64-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Windows 7 Home Premium 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Microsoft')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Windows 7 Professionnel 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Microsoft')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Windows 7 Professionnel 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = '64-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Ubuntu 15.10 (Wily Werewolf)'),
(SELECT idCategorie FROM Categories WHERE nom = 'Linux')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Ubuntu 15.10 (Wily Werewolf)'),
(SELECT idCategorie FROM Categories WHERE nom = '64-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Ubuntu 15.10 (Wily Werewolf)'),
(SELECT idCategorie FROM Categories WHERE nom = '32-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Ubuntu 15.10 (Wily Werewolf)'),
(SELECT idCategorie FROM Categories WHERE nom = 'Stable')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'FreeNAS 9.3 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'BSD')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'FreeNAS 9.3 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = '32-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'FreeNAS 9.3 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = '64-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'FreeNAS 9.3 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'BSD')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'CentOS-7 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'RHEL')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'CentOS-7 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Linux')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'CentOS-7 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = '64-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'CentOS-7 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Stable')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'CentOS-7 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = '32-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'CentOS-7 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Stable')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'CentOS-7 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Linux')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'CentOS-7 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'RHEL')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Red Star OS'),
(SELECT idCategorie FROM Categories WHERE nom = '32-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Red Star OS'),
(SELECT idCategorie FROM Categories WHERE nom = '64-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Red Star OS'),
(SELECT idCategorie FROM Categories WHERE nom = 'Linux')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Hannah Montana Linux 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = '32-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Hannah Montana Linux 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Linux')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Hannah Montana Linux 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Linux')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Hannah Montana Linux 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = '64-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Linux Mint 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = '64-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Linux Mint 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Linux')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Linux Mint 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Stable')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Linux Mint 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = '32-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Linux Mint 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Linux')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Linux Mint 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Stable')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'OS X v10.11 El Capitan'),
(SELECT idCategorie FROM Categories WHERE nom = 'Stable')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'OS X v10.11 El Capitan'),
(SELECT idCategorie FROM Categories WHERE nom = 'Apple')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'OS X v10.11 El Capitan'),
(SELECT idCategorie FROM Categories WHERE nom = '64-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Gentoo'),
(SELECT idCategorie FROM Categories WHERE nom = 'Linux')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Gentoo'),
(SELECT idCategorie FROM Categories WHERE nom = '64-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Gentoo'),
(SELECT idCategorie FROM Categories WHERE nom = '32-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Windows 10'),
(SELECT idCategorie FROM Categories WHERE nom = '64-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Windows 10'),
(SELECT idCategorie FROM Categories WHERE nom = 'Microsoft')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Solaris 11'),
(SELECT idCategorie FROM Categories WHERE nom = 'Oracle')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Solaris 11'),
(SELECT idCategorie FROM Categories WHERE nom = '64-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Kali Linux 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Linux')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Kali Linux 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = '32-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Kali Linux 32-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Stable')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Kali Linux 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Linux')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Kali Linux 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = '64-bit')
);

INSERT INTO ProduitsCategories (idProduit, idCategorie)
VALUES(
(SELECT idProduit FROM Produits WHERE nom = 'Kali Linux 64-bit'),
(SELECT idCategorie FROM Categories WHERE nom = 'Stable')
);


COMMIT;
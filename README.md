# LogiKek
Site web d'achat de logiciel.

Projet dans le cadre du cours 420-4B6 Programmation Web.
Code de backend écrit en PHP et le frontend en HTML, CSS et Javascript.

# Déploiement

Pour déployer le site web, exécutez les deux scripts de base de données: *LogiKek_Creation.sql* et *LogiKek_Insertion.sql*.
Pour le bon fonctionnement du site, assurez-vous que le projet se situe à la racine sans sous dossier. 
Par example sous: *C:/xampp/htdocs* et non *C:/xampp/htdocs/LogiKek*.

# Logins

## Comptes clients

| Nom de compte | Mot de passe  |
| ------------- |:-------------:|
| tristan       | 1081849       |
| jeff          | salt          |

## Administrateurs

Les comptes administrateurs peuvent uniquement êtres accédés à partir du panneau d'administration situé sous */admin*.

| Nom de compte | Mot de passe  |
| ------------- |:-------------:|
| admin         | admin         |

# Fonctionnalités

* Navigation par catégorie de produits.
* Recherche par nom avec filtration par catégorie de produit.
* Affichage détaillé d'un produit en cliquant sur l'image du produit (description et quantité disponible).
* Création de comptes clients.
  * Validation côté serveur.
  * Mot de passe hashé et salé pour assurer la sécurité.
* Historiques de commandes.
* Gestion de profil.
* Changement de mot de passe.
  * Validation côté client et serveur.
* Panier d'achat avec facture et validation de carte de crédit.
* Panneau d'administration à partir de /admin.
  * Gestion des catégories de produits.
    * Changement du nom d'une catégorie.
    * Nouvelle catégorie.
  * Gestion des produits.
    * Mise à jour des informations d'un produit.
    * Nouveau produit.
   * Rapport concernant les produits hors stock.
   * Rapport concernant les ventes.
   
# Démonstration

![Page principale](http://imgur.com/QNbk63H)

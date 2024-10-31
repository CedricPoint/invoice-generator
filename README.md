# Générateur de Devis et Factures - Informaclique

Bienvenue dans le générateur de devis et factures d'Informaclique, un projet développé par [Cédric FRANK](https://informaclique.fr). Ce site permet aux utilisateurs de gérer facilement leurs clients, entreprises, articles, devis, et comptes bancaires. Ce guide vous accompagne dans la configuration, l'utilisation des fonctionnalités et la personnalisation du projet.

---

## Table des Matières
- [Présentation](#Présentation)
- [Installation et Configuration](#installation-et-configuration)
- [Fonctionnalités du Site](#fonctionnalités-du-site)
- [Personnalisation](#personnalisation)
- [Démo Vidéo](#démo-vidéo)
- [Contact et Support](#contact-et-support)

---

## Présentation

Ce générateur de devis et factures est conçu pour une gestion simple et efficace des devis personnalisés. Grâce aux options de configuration avancées et aux calculs automatiques pour les marges et la TVA, ce projet permet de créer des documents professionnels pour les clients.

---

## Installation et Configuration

### 1. Cloner le dépôt GitHub  
   Commencez par cloner le dépôt GitHub dans votre répertoire local :

   ```bash
   git clone https://github.com/CedricPoint/invoice-generator.git
   cd invoice-generator
```

## Base de Données
Importez le fichier SQL (fourni dans le dépôt) dans votre gestionnaire de base de données pour créer les tables nécessaires.

## Configuration de la Base de Données (db.php)
Modifiez le fichier db.php avec vos informations de connexion. Voici un exemple de configuration :

php
Copier le code
```bash 
<?php
$dbHost = 'votre_hôte';
$dbName = 'nom_de_la_base_de_données';
$dbUser = 'votre_utilisateur';
$dbPass = 'votre_mot_de_passe';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
```

## Installation des Dépendances
Assurez-vous d'avoir un serveur local (comme WAMP, MAMP ou XAMPP) ou utilisez un serveur distant prenant en charge PHP et MySQL.

## Fonctionnalités du Site
1. Authentification et Sécurité
Inscription et Connexion : Chaque utilisateur peut créer un compte, se connecter, et voir uniquement ses propres informations.
Protection des Données : Les données sont sécurisées pour que chaque utilisateur accède uniquement à ses informations (entreprises, clients, items, etc.).
2. Gestion des Clients
Ajout de Clients : Ajoutez un client avec des informations comme le nom, l'adresse, le téléphone, l'email, et un numéro de SIRET (facultatif).
Mise à jour des Informations : Les informations peuvent être modifiées à tout moment.
Affichage des Clients : Accédez rapidement à la liste de tous les clients via le tableau de bord.
3. Gestion des Entreprises
Création d'Entreprises : Ajoutez une entreprise avec son nom, adresse, téléphone, email, SIRET, logo, marge et TVA.
Personnalisation : Les informations d'entreprise, ainsi que les taux de marge et de TVA, peuvent être mises à jour.
Affichage des Entreprises : Visualisez toutes les entreprises liées à un utilisateur spécifique.
4. Gestion des Items
Ajout d'Items : Les utilisateurs peuvent ajouter des items avec une description et un prix unitaire pour les inclure dans les devis.
Modification et Suppression : Chaque item peut être modifié ou supprimé directement via le tableau de bord.
5. Création et Gestion des Devis
Génération de Devis : Créez des devis en ajoutant la marge et la TVA spécifique à chaque entreprise. Choisissez le client, sélectionnez les items, et définissez les quantités.
Calcul des Totaux : Le total HT, la TVA et le total TTC sont calculés automatiquement.
Téléchargement PDF : Les devis peuvent être visualisés et téléchargés en PDF, avec le logo, les informations client, et un espace pour la signature.
6. Gestion des Informations Bancaires
Ajout des Comptes Bancaires : Les utilisateurs peuvent ajouter des informations bancaires (Banque, IBAN, SWIFT/BIC) pour leurs entreprises.
Affichage sur le Devis : Les informations bancaires apparaissent en bas de chaque devis pour faciliter le paiement.
7. Tableau de Bord Personnalisé
Navigation Rapide : Accédez rapidement à toutes les fonctionnalités via le tableau de bord, avec des onglets et des boutons de navigation bien organisés.
Personnalisation
Les utilisateurs peuvent personnaliser les informations de l'entreprise, ajuster les taux de TVA et de marge, et gérer les informations bancaires directement depuis le tableau de bord.

Pour une personnalisation avancée du style, les CSS sont disponibles dans le fichier style.css.

Démo Vidéo
Visionnez notre vidéo de démonstration pour voir comment utiliser toutes les fonctionnalités du générateur de devis et factures :



Contact et Support
Pour toute question ou assistance, contactez l'équipe d'Informaclique :

Email : contact@informaclique.fr
Site Web : Informaclique.fr
© 2024 Informaclique - Tous droits réservés. Développé par Cédric FRANK

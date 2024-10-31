-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 31 oct. 2024 à 14:26
-- Version du serveur : 8.0.39-0ubuntu0.22.04.1
-- Version de PHP : 8.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `informaclique_invoicedb`
--

-- --------------------------------------------------------

--
-- Structure de la table `BankDetails`
--

CREATE TABLE `BankDetails` (
  `bank_id` int NOT NULL,
  `user_id` int NOT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `iban` varchar(34) DEFAULT NULL,
  `swift_bic` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Clients`
--

CREATE TABLE `Clients` (
  `client_id` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `adresse` text,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `user_id` int NOT NULL,
  `siret` varchar(14) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Devis`
--

CREATE TABLE `Devis` (
  `devis_id` int NOT NULL,
  `client_id` int NOT NULL,
  `date_creation` date NOT NULL DEFAULT (curdate()),
  `total` decimal(10,2) DEFAULT '0.00',
  `entreprise_id` int DEFAULT NULL,
  `marge` decimal(5,2) DEFAULT '0.00',
  `tva` decimal(5,2) DEFAULT '0.00',
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Devis_Items`
--

CREATE TABLE `Devis_Items` (
  `devis_id` int NOT NULL,
  `item_id` int NOT NULL,
  `quantite` int DEFAULT '1',
  `prix_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Entreprises`
--

CREATE TABLE `Entreprises` (
  `entreprise_id` int NOT NULL,
  `nom_entreprise` varchar(255) NOT NULL,
  `adresse` text,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tva_incluse` tinyint(1) DEFAULT '1',
  `taux_tva` decimal(5,2) DEFAULT '20.00',
  `marge` decimal(5,2) DEFAULT '0.00',
  `logo` varchar(255) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `siret` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Items`
--

CREATE TABLE `Items` (
  `item_id` int NOT NULL,
  `description` text NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Settings`
--

CREATE TABLE `Settings` (
  `id` int NOT NULL,
  `nom_entreprise` varchar(255) DEFAULT NULL,
  `adresse` text,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tva_incluse` tinyint(1) DEFAULT '1',
  `taux_tva` decimal(5,2) DEFAULT '20.00',
  `marge` decimal(5,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

CREATE TABLE `Users` (
  `user_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(255) NOT NULL,
  `entreprise` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `BankDetails`
--
ALTER TABLE `BankDetails`
  ADD PRIMARY KEY (`bank_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `Clients`
--
ALTER TABLE `Clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Index pour la table `Devis`
--
ALTER TABLE `Devis`
  ADD PRIMARY KEY (`devis_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `entreprise_id` (`entreprise_id`);

--
-- Index pour la table `Devis_Items`
--
ALTER TABLE `Devis_Items`
  ADD PRIMARY KEY (`devis_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Index pour la table `Entreprises`
--
ALTER TABLE `Entreprises`
  ADD PRIMARY KEY (`entreprise_id`);

--
-- Index pour la table `Items`
--
ALTER TABLE `Items`
  ADD PRIMARY KEY (`item_id`);

--
-- Index pour la table `Settings`
--
ALTER TABLE `Settings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `BankDetails`
--
ALTER TABLE `BankDetails`
  MODIFY `bank_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `Clients`
--
ALTER TABLE `Clients`
  MODIFY `client_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `Devis`
--
ALTER TABLE `Devis`
  MODIFY `devis_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `Entreprises`
--
ALTER TABLE `Entreprises`
  MODIFY `entreprise_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `Items`
--
ALTER TABLE `Items`
  MODIFY `item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `Settings`
--
ALTER TABLE `Settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `BankDetails`
--
ALTER TABLE `BankDetails`
  ADD CONSTRAINT `BankDetails_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Devis`
--
ALTER TABLE `Devis`
  ADD CONSTRAINT `Devis_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `Clients` (`client_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Devis_ibfk_2` FOREIGN KEY (`entreprise_id`) REFERENCES `Entreprises` (`entreprise_id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `Devis_Items`
--
ALTER TABLE `Devis_Items`
  ADD CONSTRAINT `Devis_Items_ibfk_1` FOREIGN KEY (`devis_id`) REFERENCES `Devis` (`devis_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Devis_Items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `Items` (`item_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

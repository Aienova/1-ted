-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : sam. 07 mars 2026 à 22:04
-- Version du serveur : 11.4.9-MariaDB-cll-lve
-- Version de PHP : 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `c2248065c_1-ted_france`
--

-- --------------------------------------------------------

--
-- Structure de la table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `customer_number` varchar(100) DEFAULT NULL,
  `nation_register_number` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `hq_location` varchar(255) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `recruiting` tinyint(1) DEFAULT 1,
  `website` varchar(255) DEFAULT NULL COMMENT 'Site web de l''entreprise',
  `media_code` varchar(12) DEFAULT NULL COMMENT 'Code unique pour identifier les médias de l''entreprise (12 caractères)',
  `contact_id` int(11) DEFAULT NULL,
  `banned` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indique si l''entreprise est bannie (0 = non, 1 = oui)',
  `banlength` int(11) DEFAULT NULL COMMENT 'Durée du bannissement en jours (-1 = permanent, NULL = non bannie)',
  `ban_end_date` date DEFAULT NULL COMMENT 'Date de fin du bannissement (9999-12-31 = permanent)',
  `ban_reason` text DEFAULT NULL COMMENT 'Raison du bannissement',
  `date_creation` date DEFAULT NULL COMMENT 'Date de création de l''entreprise (YYYY-MM-DD)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `country_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `company`
--

INSERT INTO `company` (`id`, `name`, `customer_number`, `nation_register_number`, `description`, `domain`, `capacity`, `hq_location`, `country`, `recruiting`, `website`, `media_code`, `contact_id`, `banned`, `banlength`, `ban_end_date`, `ban_reason`, `date_creation`, `created_at`, `updated_at`, `country_id`) VALUES
(32, 'Aienova', NULL, '91092579100014', 'Aienova Nothing to Something', 'Informatique/Numérique', 0, 'Mulhouse', NULL, 1, '', '177282738802', 30, 0, NULL, NULL, NULL, NULL, '2026-03-06 20:03:07', '2026-03-06 20:11:04', NULL),
(36, 'EA Sport', NULL, '34513048800017', 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Informatique/Numérique', 50, 'Paris', NULL, 1, NULL, '177284089730', 34, 0, NULL, NULL, NULL, NULL, '2026-03-06 23:48:17', '2026-03-06 23:48:46', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_company_contact` (`contact_id`),
  ADD KEY `idx_company_name` (`name`),
  ADD KEY `idx_country_id` (`country_id`),
  ADD KEY `idx_media_code` (`media_code`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `fk_company_contact` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

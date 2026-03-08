-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 06 mars 2026 à 23:47
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `1ted`
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
  `media_code` varchar(50) NOT NULL,
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
(1, 'Aienova', NULL, '91092579100014545', 'Entreprise technologique innovante', 'Informatique', 20, 'Paris', 0, 1, 'https://aienova.com/fr/', 'f3daa2001202', 1, 0, NULL, NULL, NULL, NULL, '2026-02-03 22:59:40', '2026-03-02 12:49:54', NULL),
(2, 'BuildPro', NULL, NULL, 'Spécialiste en construction', 'BTP', 200, 'Lyon', 0, 1, NULL, '7d70ed0024bc', 2, 0, NULL, NULL, NULL, NULL, '2026-02-03 22:59:40', '2026-03-02 12:49:54', NULL),
(3, '', NULL, NULL, 'Description de l\'entreprise...', NULL, NULL, NULL, NULL, 1, NULL, '50fb0900341b', NULL, 0, NULL, NULL, NULL, NULL, '2026-02-05 00:23:39', '2026-03-02 12:49:54', NULL),
(4, '', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', 0, 1, NULL, 'b10f400041ad', NULL, 0, NULL, NULL, NULL, NULL, '2026-02-05 00:23:42', '2026-03-02 12:49:54', NULL),
(5, 'Aienova', NULL, NULL, 'Aienova est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 10, 'Paris', 0, 1, NULL, '704b02005a2f', NULL, 0, NULL, NULL, NULL, NULL, '2026-02-05 00:24:24', '2026-03-02 12:49:54', NULL),
(6, 'Aienova', NULL, NULL, 'Aienova est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 10, 'Paris', 0, 1, NULL, '0d0dea0066eb', NULL, 0, NULL, NULL, NULL, NULL, '2026-02-05 00:24:24', '2026-03-02 12:49:54', NULL),
(7, '', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', 0, 1, NULL, '465f6d0072b2', NULL, 0, NULL, NULL, NULL, NULL, '2026-02-07 01:03:49', '2026-03-02 12:49:54', NULL),
(8, '', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', 0, 1, NULL, '3dbcfb0082ce', NULL, 0, NULL, NULL, NULL, NULL, '2026-02-14 16:27:06', '2026-03-02 12:49:54', NULL),
(9, 'SOFF & CO', NULL, NULL, 'SOFF & Co est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 22, 'Paris', 0, 1, NULL, 'f7f0b40096ac', NULL, 0, NULL, NULL, NULL, NULL, '2026-02-18 17:54:54', '2026-03-02 12:49:54', NULL),
(10, '', NULL, NULL, 'Description de l\'entreprise...', NULL, NULL, NULL, NULL, 1, NULL, '70a42d010b93', NULL, 0, NULL, NULL, NULL, NULL, '2026-02-18 18:37:50', '2026-03-02 12:49:54', NULL),
(11, '', NULL, NULL, 'Description de l\'entreprise...', NULL, NULL, NULL, NULL, 1, NULL, 'c3b7d2011d45', NULL, 0, NULL, NULL, NULL, NULL, '2026-02-18 18:37:52', '2026-03-02 12:49:54', NULL),
(12, '', NULL, NULL, 'Description de l\'entreprise...', NULL, NULL, NULL, NULL, 1, NULL, '84cfbc01270d', NULL, 0, NULL, NULL, NULL, NULL, '2026-02-18 18:49:04', '2026-03-02 12:49:54', NULL),
(13, 'TechCorp Solutions', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 10, 'Paris', 0, 1, NULL, '7437830134e9', NULL, 0, NULL, NULL, NULL, NULL, '2026-02-18 19:06:55', '2026-03-02 12:49:54', NULL),
(14, 'Tech tech', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', NULL, 'Paris', 0, 1, NULL, '72edb501422c', NULL, 0, NULL, NULL, NULL, NULL, '2026-02-18 19:16:37', '2026-03-02 12:49:54', NULL),
(15, 'Tech tech', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', NULL, 'Paris', 0, 1, NULL, 'a0de5c0150e9', NULL, 0, NULL, NULL, NULL, NULL, '2026-02-18 19:16:57', '2026-03-02 12:49:54', NULL),
(16, 'Test test', NULL, '12345678910124', 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', NULL, 'Paris', 0, 1, NULL, '16f8ae016d8d', NULL, 0, NULL, NULL, NULL, NULL, '2026-02-18 19:19:17', '2026-03-02 12:49:54', NULL),
(17, 'Aienova', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', NULL, 'Paris', 0, 1, NULL, '8e7f6d0175a2', NULL, 0, NULL, NULL, NULL, NULL, '2026-02-28 20:29:03', '2026-03-02 12:49:54', NULL),
(18, 'Euro Express', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, NULL, '177255586687', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-03 16:37:46', '2026-03-03 16:37:46', NULL),
(19, 'Euro Express', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 17, 'Paris', NULL, 1, NULL, '177255612084', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-03 16:42:00', '2026-03-03 16:42:00', NULL),
(20, 'Euro Express', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, NULL, '177255632152', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-03 16:45:21', '2026-03-03 16:45:21', NULL),
(21, 'Euro Express', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, NULL, '177255678993', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-03 16:53:09', '2026-03-03 16:53:09', NULL),
(22, 'Euro Express', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, NULL, '177255709198', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-03 16:58:11', '2026-03-03 16:58:11', NULL),
(23, 'Euro Express', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, NULL, '177255793307', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-03 17:12:13', '2026-03-03 17:12:13', NULL),
(24, 'DS Break', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, NULL, '177255802543', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-03 17:13:45', '2026-03-03 17:13:45', NULL),
(25, 'Euro Express', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, NULL, '177255854215', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-03 17:22:22', '2026-03-03 17:24:02', NULL),
(26, 'Euro Express', NULL, '', 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, '', '177255887963', 23, 0, NULL, NULL, NULL, NULL, '2026-03-03 17:27:59', '2026-03-03 18:06:55', NULL),
(27, 'TechCorp Solutions', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, NULL, '177256452746', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-03 19:02:07', '2026-03-03 19:02:07', NULL),
(28, 'TechCorp Solutions', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, NULL, '177256492788', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-03 19:08:47', '2026-03-03 19:08:47', NULL),
(29, 'TechCorp Solutions', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, NULL, '177256533106', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-03 19:15:31', '2026-03-03 19:15:31', NULL),
(30, 'TechCorp Solutions', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, NULL, '177256535694', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-03 19:15:56', '2026-03-03 19:15:56', NULL),
(31, 'Dante System', NULL, '34513048800017', 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Marketing/Communication', 15, 'Paris', NULL, 1, NULL, '177283322898', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-06 21:40:29', '2026-03-06 21:41:57', NULL),
(32, 'Concil', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, NULL, '177283352238', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-06 21:45:22', '2026-03-06 21:45:22', NULL),
(33, 'Le repère', NULL, '34513048800017', 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Marketing/Communication', 50, 'Paris', NULL, 1, NULL, '177283431012', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-06 21:58:30', '2026-03-06 21:58:59', NULL),
(34, 'TechCorp Solutions', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, NULL, '177283439915', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-06 21:59:59', '2026-03-06 21:59:59', NULL),
(35, 'Swatch', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, NULL, '177283447683', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-06 22:01:16', '2026-03-06 22:01:16', NULL),
(36, 'Allianz', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, NULL, '177283527999', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-06 22:14:39', '2026-03-06 22:14:39', NULL),
(37, 'Feedback Models', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', NULL, 1, NULL, '177283537269', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-06 22:16:12', '2026-03-06 22:16:12', NULL),
(38, 'DS Break', NULL, '34513048800017', 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Informatique/Numérique', 50, 'Paris', NULL, 1, NULL, '177283579198', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-06 22:23:11', '2026-03-06 22:24:28', NULL),
(39, 'Euro Expresso', NULL, '34513048800017', 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Logistique/Transport', 50, 'Paris', NULL, 1, NULL, '1772837162665-1ld6xlvvpd9', NULL, 0, NULL, NULL, NULL, NULL, '2026-03-06 22:46:02', '2026-03-06 22:46:38', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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

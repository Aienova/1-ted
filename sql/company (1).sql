-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 02 mars 2026 à 12:54
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

INSERT INTO `company` (`id`, `name`, `customer_number`, `nation_register_number`, `description`, `domain`, `capacity`, `hq_location`, `country`, `recruiting`, `contact_id`, `banned`, `banlength`, `ban_end_date`, `ban_reason`, `date_creation`, `created_at`, `updated_at`, `country_id`) VALUES
(1, 'Aienova', NULL, '91092579100014545', 'Entreprise technologique innovante', 'Informatique', 20, 'Paris', 0, 1, 1, 0, NULL, NULL, NULL, NULL, '2026-02-03 22:59:40', '2026-03-02 11:42:17', NULL),
(2, 'BuildPro', NULL, NULL, 'Spécialiste en construction', 'BTP', 200, 'Lyon', 0, 1, 2, 0, NULL, NULL, NULL, NULL, '2026-02-03 22:59:40', '2026-02-03 22:59:40', NULL),
(3, '', NULL, NULL, 'Description de l\'entreprise...', NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-05 00:23:39', '2026-02-05 00:23:39', NULL),
(4, '', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', 0, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-05 00:23:42', '2026-02-05 00:23:42', NULL),
(5, 'Aienova', NULL, NULL, 'Aienova est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 10, 'Paris', 0, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-05 00:24:24', '2026-02-05 00:24:24', NULL),
(6, 'Aienova', NULL, NULL, 'Aienova est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 10, 'Paris', 0, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-05 00:24:24', '2026-02-05 00:24:24', NULL),
(7, '', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', 0, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-07 01:03:49', '2026-02-07 01:03:49', NULL),
(8, '', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', 0, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-14 16:27:06', '2026-02-14 16:27:06', NULL),
(9, 'SOFF & CO', NULL, NULL, 'SOFF & Co est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 22, 'Paris', 0, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-18 17:54:54', '2026-02-18 17:54:54', NULL),
(10, '', NULL, NULL, 'Description de l\'entreprise...', NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-18 18:37:50', '2026-02-18 18:37:50', NULL),
(11, '', NULL, NULL, 'Description de l\'entreprise...', NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-18 18:37:52', '2026-02-18 18:37:52', NULL),
(12, '', NULL, NULL, 'Description de l\'entreprise...', NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-18 18:49:04', '2026-02-18 18:49:04', NULL),
(13, 'TechCorp Solutions', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 10, 'Paris', 0, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-18 19:06:55', '2026-02-18 19:06:55', NULL),
(14, 'Tech tech', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', NULL, 'Paris', 0, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-18 19:16:37', '2026-02-18 19:16:37', NULL),
(15, 'Tech tech', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', NULL, 'Paris', 0, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-18 19:16:57', '2026-02-18 19:16:57', NULL),
(16, 'Test test', NULL, '12345678910124', 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', NULL, 'Paris', 0, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-18 19:19:17', '2026-02-18 19:20:05', NULL),
(17, 'Aienova', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', NULL, 'Paris', 0, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-28 20:29:03', '2026-02-28 20:29:03', NULL);

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
  ADD KEY `idx_country_id` (`country_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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

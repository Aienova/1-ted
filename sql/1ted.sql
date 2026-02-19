-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 15 fév. 2026 à 18:15
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
-- Structure de la table `candidate`
--

CREATE TABLE `candidate` (
  `id` int(11) NOT NULL,
  `identity_id` int(11) DEFAULT NULL,
  `skill_ids` text DEFAULT NULL COMMENT 'JSON array of skill IDs',
  `personality_ids` text DEFAULT NULL COMMENT 'JSON array of personality IDs',
  `social_id` int(11) DEFAULT NULL,
  `demand_id` int(11) DEFAULT NULL,
  `experience_ids` text DEFAULT NULL COMMENT 'JSON array of experience IDs',
  `formation_ids` text DEFAULT NULL COMMENT 'JSON array of formation IDs',
  `mission_ids` text DEFAULT NULL COMMENT 'JSON array of mission IDs',
  `media_code` varchar(12) NOT NULL COMMENT 'Unique code for secure media access (photos, QR codes)',
  `approved` tinyint(1) DEFAULT NULL COMMENT 'NULL=En attente, 1=Approuvé, 0=Refusé',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `candidate`
--

INSERT INTO `candidate` (`id`, `identity_id`, `skill_ids`, `personality_ids`, `social_id`, `demand_id`, `experience_ids`, `formation_ids`, `mission_ids`, `media_code`, `approved`, `created_at`, `updated_at`) VALUES
(1, 1, '[\"1\",\"2\",\"3\"]', '[1,2,3]', 1, 1, '[1]', '[1]', '[]', '8w2H1q0U5Z3m', 1, '2026-02-03 14:23:13', '2026-02-09 12:34:11'),
(3, 6, NULL, NULL, NULL, 3, NULL, NULL, NULL, '', 1, '2026-02-04 19:32:14', '2026-02-07 19:10:18'),
(8, 11, NULL, NULL, NULL, 8, NULL, NULL, NULL, '9h3C6H7Rd07b', NULL, '2026-02-04 20:23:04', '2026-02-04 20:23:04'),
(9, 12, NULL, NULL, NULL, 9, NULL, NULL, NULL, 'ON73MRN71E14', NULL, '2026-02-04 20:46:24', '2026-02-04 20:46:24'),
(10, 13, NULL, '[\"4\",\"5\"]', NULL, 10, NULL, NULL, NULL, '17S092iQ9QJk', NULL, '2026-02-04 21:23:13', '2026-02-04 21:23:55'),
(11, 14, NULL, '[\"6\",\"7\",\"8\"]', NULL, 11, '[\"3\",\"4\"]', '[\"2\",\"3\"]', NULL, 'r6r11cS0x4q7', NULL, '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(12, 15, NULL, '[\"9\",\"10\"]', NULL, 12, NULL, NULL, NULL, 'AS1aJ7072Sn1', NULL, '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(13, 16, NULL, '[\"11\",\"12\"]', NULL, 13, NULL, NULL, NULL, 'iUpSq11082W7', NULL, '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(14, 17, NULL, '[\"13\",\"14\"]', NULL, 14, NULL, NULL, NULL, '2430LZ7oR7xn', NULL, '2026-02-04 21:43:32', '2026-02-04 21:43:32'),
(15, 18, '[\"4\",\"5\"]', '[\"15\",\"16\"]', NULL, 15, NULL, NULL, NULL, '4YmO0wF945z7', 1, '2026-02-04 22:00:57', '2026-02-07 19:08:44'),
(16, 19, NULL, '[\"17\",\"18\"]', NULL, 16, NULL, NULL, NULL, 'DH4K03Q8Ja53', NULL, '2026-02-04 22:15:12', '2026-02-04 22:15:12'),
(17, 20, '[\"6\",\"7\",\"8\",\"9\",\"10\"]', NULL, NULL, 17, '[\"5\",\"6\"]', '[\"4\",\"5\"]', NULL, '30GC78XW40Fl', NULL, '2026-02-04 22:16:40', '2026-02-04 22:16:40'),
(18, 21, '[\"11\",\"12\",\"13\",\"14\"]', '[\"19\",\"20\"]', NULL, 18, '[\"7\",\"8\"]', '[\"6\"]', NULL, 'U242zc0c1dS4', NULL, '2026-02-04 22:34:07', '2026-02-04 22:34:07'),
(19, 22, '[\"15\",\"16\",\"17\",\"18\",\"19\"]', NULL, NULL, 19, '[\"9\",\"10\"]', '[\"7\",\"8\"]', NULL, 'zc7r59xJ3I60', NULL, '2026-02-04 22:53:13', '2026-02-04 22:53:13'),
(20, 23, '[\"20\",\"21\",\"22\",\"23\",\"25\"]', NULL, NULL, 20, '[\"11\",\"13\"]', '[\"9\",\"10\"]', NULL, 'Lv1214YCX1K0', NULL, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(21, 24, '[\"24\",\"26\",\"27\",\"28\",\"29\"]', NULL, NULL, 21, '[\"12\",\"14\"]', '[\"11\",\"12\"]', NULL, '06257cjY4GGW', NULL, '2026-02-04 23:01:55', '2026-02-04 23:01:55'),
(22, 25, '[\"30\",\"31\",\"32\",\"33\",\"34\",\"35\"]', NULL, NULL, 22, '[\"15\",\"16\"]', '[\"13\",\"14\"]', NULL, '167pp0Wu5tp3', NULL, '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(23, 26, '[\"36\",\"37\",\"38\",\"39\",\"40\"]', NULL, NULL, 23, '[\"17\",\"18\"]', '[\"15\",\"16\"]', NULL, 'um46L0e57w7W', NULL, '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(24, 27, '[\"41\",\"42\",\"43\",\"44\",\"45\"]', NULL, NULL, 24, '[\"19\",\"20\"]', '[\"17\",\"18\"]', NULL, 'W6suZ897kF17', NULL, '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(25, 28, '[\"46\",\"47\",\"48\",\"49\",\"50\"]', NULL, NULL, 25, '[\"21\",\"22\"]', '[\"19\",\"20\"]', NULL, 'OG7Il8R6140i', 0, '2026-02-04 23:23:06', '2026-02-07 19:08:33'),
(26, 29, '[\"51\",\"52\",\"53\",\"54\",\"55\"]', NULL, NULL, 26, '[\"23\",\"24\"]', '[\"21\",\"22\"]', NULL, '31Na7tJ74c7p', NULL, '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(27, 30, '[\"56\",\"57\",\"58\",\"59\",\"60\"]', NULL, NULL, 27, '[\"25\",\"26\"]', '[\"23\",\"24\"]', NULL, '2t17gK714QEL', NULL, '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(28, 31, NULL, NULL, NULL, 28, NULL, NULL, NULL, '4RJmD847KK12', NULL, '2026-02-04 23:36:12', '2026-02-04 23:36:12'),
(29, 32, '[\"61\",\"62\",\"63\",\"64\",\"65\"]', NULL, NULL, 29, '[\"27\",\"28\"]', '[\"25\",\"26\"]', NULL, '2GmD17W2t07p', NULL, '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(30, 33, '[\"66\",\"67\",\"68\",\"69\",\"70\"]', NULL, NULL, 30, '[\"29\",\"30\"]', '[\"27\",\"28\"]', NULL, '2I4GfG3Lz977', NULL, '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(31, 34, '[\"71\",\"72\",\"73\",\"74\",\"75\"]', NULL, NULL, 31, '[\"31\",\"32\"]', '[\"29\",\"30\"]', NULL, '20r3yV6t4xr4', 1, '2026-02-04 23:40:23', '2026-02-07 19:09:35'),
(32, 35, '[\"76\",\"77\",\"78\",\"79\",\"80\"]', NULL, NULL, 32, '[\"33\",\"34\"]', '[\"31\",\"32\"]', NULL, 'j07kUO3l52w8', NULL, '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(33, 36, '[\"81\",\"82\",\"83\",\"84\",\"85\"]', NULL, NULL, 33, '[\"35\",\"36\"]', '[\"33\",\"34\"]', NULL, 'g217PX4IGC04', NULL, '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(34, 37, '[\"86\",\"87\",\"88\",\"89\",\"90\"]', NULL, NULL, 34, '[\"37\",\"38\"]', '[\"35\",\"36\"]', NULL, '79or0WH2qi15', NULL, '2026-02-04 23:58:30', '2026-02-04 23:58:30'),
(35, 38, '[\"91\",\"92\",\"93\",\"94\",\"95\"]', NULL, NULL, 35, '[\"39\",\"40\"]', '[\"37\",\"38\"]', NULL, 'ZDc857J1Gm47', NULL, '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(36, 39, '[\"96\",\"97\",\"98\",\"99\",\"100\"]', NULL, NULL, 36, '[\"41\",\"42\"]', '[\"39\",\"40\"]', NULL, 'L0z1P71UUF18', NULL, '2026-02-14 16:24:11', '2026-02-14 16:24:11'),
(37, 40, '[\"101\",\"102\",\"103\",\"104\",\"105\"]', NULL, NULL, 37, '[\"43\",\"44\"]', '[\"41\",\"42\"]', NULL, 'wL77x3NVR190', NULL, '2026-02-14 16:25:06', '2026-02-14 16:25:06'),
(38, 41, '[\"106\",\"107\",\"108\",\"109\",\"110\"]', NULL, NULL, 38, '[\"45\",\"46\"]', '[\"43\",\"44\"]', NULL, 'v5S9zA8S1b17', NULL, '2026-02-14 16:26:39', '2026-02-14 16:26:39');

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
  `country` varchar(100) DEFAULT NULL,
  `recruiting` tinyint(1) DEFAULT 1,
  `contact_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `company`
--

INSERT INTO `company` (`id`, `name`, `customer_number`, `nation_register_number`, `description`, `domain`, `capacity`, `hq_location`, `country`, `recruiting`, `contact_id`, `created_at`, `updated_at`) VALUES
(1, 'Aienova', NULL, NULL, 'Entreprise technologique innovante', 'Informatique', 50, 'Paris', 'France', 1, 1, '2026-02-03 22:59:40', '2026-02-03 23:20:22'),
(2, 'BuildPro', NULL, NULL, 'Spécialiste en construction', 'BTP', 200, 'Lyon', 'France', 1, 2, '2026-02-03 22:59:40', '2026-02-03 22:59:40'),
(3, '', NULL, NULL, 'Description de l\'entreprise...', NULL, NULL, NULL, NULL, 1, NULL, '2026-02-05 00:23:39', '2026-02-05 00:23:39'),
(4, '', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', 'France', 1, NULL, '2026-02-05 00:23:42', '2026-02-05 00:23:42'),
(5, 'Aienova', NULL, NULL, 'Aienova est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 10, 'Paris', 'France', 1, NULL, '2026-02-05 00:24:24', '2026-02-05 00:24:24'),
(6, 'Aienova', NULL, NULL, 'Aienova est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 10, 'Paris', 'France', 1, NULL, '2026-02-05 00:24:24', '2026-02-05 00:24:24'),
(7, '', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', 'France', 1, NULL, '2026-02-07 01:03:49', '2026-02-07 01:03:49'),
(8, '', NULL, NULL, 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Technologies de l\'information', 50, 'Paris', 'France', 1, NULL, '2026-02-14 16:27:06', '2026-02-14 16:27:06');

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contact`
--

INSERT INTO `contact` (`id`, `first_name`, `last_name`, `email`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Marie', 'Dupont', 'marie.dupont@example.com', '0123456789', '2026-02-03 22:59:40', '2026-02-03 22:59:40'),
(2, 'Jean', 'Martin', 'jean.martin@example.com', '0987654321', '2026-02-03 22:59:40', '2026-02-03 22:59:40'),
(3, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-02-05 00:23:42', '2026-02-05 00:23:42'),
(4, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-02-05 00:24:24', '2026-02-05 00:24:24'),
(5, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-02-05 00:24:24', '2026-02-05 00:24:24'),
(6, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-02-07 01:03:49', '2026-02-07 01:03:49'),
(7, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-02-14 16:27:06', '2026-02-14 16:27:06');

-- --------------------------------------------------------

--
-- Structure de la table `degree`
--

CREATE TABLE `degree` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `degree`
--

INSERT INTO `degree` (`id`, `title`, `created_at`, `updated_at`) VALUES
(1, 'Bac', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(2, 'Bac+2', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(3, 'Bac+3 (Licence)', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(4, 'Bac+5 (Master)', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(5, 'Bac+8 (Doctorat)', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(6, 'Master 1', '2026-02-03 14:23:13', '2026-02-03 14:23:13');

-- --------------------------------------------------------

--
-- Structure de la table `demand`
--

CREATE TABLE `demand` (
  `id` int(11) NOT NULL,
  `contract_type` varchar(50) DEFAULT NULL COMMENT 'CDI, CDD, Freelance, etc.',
  `salary` decimal(10,2) DEFAULT 0.00,
  `work_condition` text DEFAULT NULL COMMENT 'Remote, Hybrid, On-site, etc.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `demand`
--

INSERT INTO `demand` (`id`, `contract_type`, `salary`, `work_condition`, `created_at`, `updated_at`) VALUES
(1, 'CDI', 45000.00, 'Hybrid - 2 jours en remote', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(3, 'Freelance', 800.00, NULL, '2026-02-04 19:32:14', '2026-02-04 19:32:14'),
(4, 'Stage', 0.00, NULL, '2026-02-04 20:12:53', '2026-02-04 20:12:53'),
(5, 'Stage', 0.00, NULL, '2026-02-04 20:12:53', '2026-02-04 20:12:53'),
(6, 'CDD', 10.00, NULL, '2026-02-04 20:13:06', '2026-02-04 20:13:06'),
(7, 'CDD', 10.00, NULL, '2026-02-04 20:13:06', '2026-02-04 20:13:06'),
(8, 'CDD', 50000.00, NULL, '2026-02-04 20:23:03', '2026-02-04 20:23:03'),
(9, 'CDI', 450000.00, NULL, '2026-02-04 20:46:24', '2026-02-04 20:46:24'),
(10, 'Freelance', 50000.00, NULL, '2026-02-04 21:23:13', '2026-02-04 21:23:13'),
(11, 'CDI', 50000.00, NULL, '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(12, 'CDD', 500000.00, NULL, '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(13, 'CDD', 500000.00, NULL, '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(14, 'CDD', 500000.00, NULL, '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(15, 'CDI', 45000.00, NULL, '2026-02-04 22:00:57', '2026-02-04 22:00:57'),
(16, 'CDD', 99999999.99, NULL, '2026-02-04 22:15:12', '2026-02-04 22:15:12'),
(17, 'CDI', 50000.00, NULL, '2026-02-04 22:16:40', '2026-02-04 22:16:40'),
(18, 'CDD', 550000.00, NULL, '2026-02-04 22:34:06', '2026-02-04 22:34:06'),
(19, 'CDI', 50000.00, NULL, '2026-02-04 22:53:13', '2026-02-04 22:53:13'),
(20, 'CDI', 50000.00, NULL, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(21, 'CDI', 50000.00, NULL, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(22, 'CDI', 50000.00, NULL, '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(23, 'CDI', 50000.00, NULL, '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(24, 'CDI', 50000.00, NULL, '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(25, 'CDI', 50000.00, NULL, '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(26, 'CDI', 50000.00, NULL, '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(27, 'CDI', 50000.00, NULL, '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(28, NULL, 0.00, NULL, '2026-02-04 23:36:12', '2026-02-04 23:36:12'),
(29, 'CDI', 50000.00, NULL, '2026-02-04 23:36:55', '2026-02-04 23:36:55'),
(30, 'CDI', 50000.00, NULL, '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(31, 'CDI', 50000.00, NULL, '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(32, 'CDI', 50000.00, NULL, '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(33, 'CDI', 50000.00, NULL, '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(34, 'CDI', 50000.00, NULL, '2026-02-04 23:58:29', '2026-02-04 23:58:29'),
(35, 'CDI', 50000.00, NULL, '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(36, 'CDI', 50000.00, NULL, '2026-02-14 16:24:11', '2026-02-14 16:24:11'),
(37, 'CDI', 50000.00, NULL, '2026-02-14 16:25:06', '2026-02-14 16:25:06'),
(38, 'CDI', 50000.00, NULL, '2026-02-14 16:26:39', '2026-02-14 16:26:39');

-- --------------------------------------------------------

--
-- Structure de la table `experience`
--

CREATE TABLE `experience` (
  `id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `current` tinyint(1) DEFAULT 0,
  `position` varchar(200) NOT NULL,
  `company` varchar(200) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `missions` text DEFAULT NULL COMMENT 'JSON array of mission IDs',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `experience`
--

INSERT INTO `experience` (`id`, `start_date`, `end_date`, `current`, `position`, `company`, `location`, `missions`, `created_at`, `updated_at`) VALUES
(1, '2020-01-01', '2023-12-31', 0, 'Développeur Full Stack', 'Aienova', 'Mulhouse', '[1,2,3]', '2026-02-03 14:23:13', '2026-02-03 14:57:36'),
(2, '2025-10-08', NULL, 1, 'Développeur Web Full Stack', 'Aienova', 'Mulhouse', '[\"5\"]', '2026-02-04 15:58:28', '2026-02-04 15:58:28'),
(3, '0000-00-00', NULL, 1, 'Lead Developer', '', '', '[\"6\",\"7\",\"8\"]', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(4, '0000-00-00', '0000-00-00', 0, '', 'TechCorp', '', '[\"9\",\"10\",\"11\"]', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(5, '0000-00-00', NULL, 0, '', 'TechCorp', NULL, NULL, '2026-02-04 22:16:40', '2026-02-04 22:16:40'),
(6, '0000-00-00', NULL, 0, '', 'WebAgency', NULL, NULL, '2026-02-04 22:16:40', '2026-02-04 22:16:40'),
(7, '0000-00-00', NULL, 0, '', 'Al Nassr', NULL, NULL, '2026-02-04 22:34:06', '2026-02-04 22:34:06'),
(8, '0000-00-00', NULL, 0, '', 'Bayern Munich', NULL, NULL, '2026-02-04 22:34:06', '2026-02-04 22:34:06'),
(9, '0000-00-00', NULL, 0, '', 'TechCorp', NULL, NULL, '2026-02-04 22:53:13', '2026-02-04 22:53:13'),
(10, '0000-00-00', NULL, 0, '', 'WebAgency', NULL, NULL, '2026-02-04 22:53:13', '2026-02-04 22:53:13'),
(11, '0000-00-00', NULL, 0, 'Lead Developer', 'TechCorp', 'Paris', NULL, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(12, '0000-00-00', NULL, 0, 'Lead Developer', 'TechCorp', 'Paris', NULL, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(13, '0000-00-00', '0000-00-00', 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', NULL, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(14, '0000-00-00', '0000-00-00', 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', NULL, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(15, '2024-02-20', NULL, 0, 'Lead Developer', 'TechCorp', 'Paris', NULL, '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(16, '2020-02-02', '2024-05-04', 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', NULL, '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(17, '2024-01-01', NULL, 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', NULL, '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(18, '0000-00-00', NULL, 0, 'Lead Developer', 'TechCorp', 'Paris', NULL, '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(19, '2024-01-01', NULL, 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', '[\"35\",\"36\",\"37\"]', '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(20, '0000-00-00', NULL, 0, 'Lead Developer', 'TechCorp', 'Paris', '[\"38\",\"39\",\"40\"]', '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(21, '2024-01-01', NULL, 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', '[\"41\",\"42\",\"43\"]', '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(22, '2020-01-01', '2023-01-01', 0, 'Lead Developer', 'TechCorp', 'Paris', '[\"44\",\"45\",\"46\"]', '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(23, '2025-01-01', NULL, 0, 'Lead Developer', 'TechCorp', 'Paris', '[\"47\",\"48\",\"49\"]', '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(24, '2021-01-01', '2023-01-01', 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', '[\"50\",\"51\",\"52\"]', '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(25, '2021-01-01', NULL, 0, 'Lead Developer', 'TechCorp', 'Paris', '[\"53\",\"54\",\"55\"]', '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(26, '0000-00-00', '0000-00-00', 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', '[\"56\",\"57\",\"58\"]', '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(27, '0000-00-00', NULL, 0, 'Lead Developer', 'TechCorp', 'Paris', '[\"59\",\"60\",\"61\"]', '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(28, '0000-00-00', '0000-00-00', 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', '[\"62\",\"63\",\"64\"]', '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(29, '0000-00-00', NULL, 0, 'Lead Developer', 'TechCorp', 'Paris', '[\"65\",\"66\",\"67\"]', '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(30, '2017-01-01', '2019-01-01', 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', '[\"68\",\"69\",\"70\"]', '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(31, '2019-01-01', NULL, 0, 'Lead Developer', 'TechCorp', 'Paris', '[\"71\",\"72\",\"73\"]', '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(32, '2017-01-01', '2018-01-01', 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', '[\"74\",\"75\",\"76\"]', '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(33, '0000-00-00', NULL, 0, 'Lead Developer', 'TechCorp', 'Paris', '[\"77\",\"78\",\"79\"]', '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(34, '0000-00-00', '0000-00-00', 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', '[\"80\",\"81\",\"82\"]', '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(35, '2021-01-01', NULL, 0, 'Lead Developer', 'TechCorp', 'Paris', '[\"83\",\"84\",\"85\"]', '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(36, '2018-01-01', '2020-01-01', 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', '[\"86\",\"87\",\"88\"]', '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(37, '0000-00-00', NULL, 1, 'Lead Developer', 'TechCorp', 'Paris', '[\"89\",\"90\",\"91\"]', '2026-02-04 23:58:29', '2026-02-04 23:58:30'),
(38, '2017-01-01', '2019-01-01', 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', '[\"92\",\"93\",\"94\"]', '2026-02-04 23:58:30', '2026-02-04 23:58:30'),
(39, '2021-01-01', NULL, 1, 'Lead Developer', 'TechCorp', 'Paris', '[\"95\",\"96\",\"97\"]', '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(40, '2017-01-01', '2019-01-01', 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', '[\"98\",\"99\",\"100\"]', '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(41, '0000-00-00', NULL, 1, 'Lead Developer', 'TechCorp', 'Paris', NULL, '2026-02-14 16:24:11', '2026-02-14 16:24:11'),
(42, '0000-00-00', '0000-00-00', 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', NULL, '2026-02-14 16:24:11', '2026-02-14 16:24:11'),
(43, '0000-00-00', NULL, 1, 'Lead Developer', 'TechCorp', 'Paris', NULL, '2026-02-14 16:25:06', '2026-02-14 16:25:06'),
(44, '0000-00-00', '0000-00-00', 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', NULL, '2026-02-14 16:25:06', '2026-02-14 16:25:06'),
(45, '0000-00-00', NULL, 1, 'Lead Developer', 'TechCorp', 'Paris', '[\"101\",\"102\",\"103\"]', '2026-02-14 16:26:39', '2026-02-14 16:26:39'),
(46, '0000-00-00', '0000-00-00', 0, 'Développeur Full Stack', 'WebAgency', 'Lyon', '[\"104\",\"105\",\"106\"]', '2026-02-14 16:26:39', '2026-02-14 16:26:39');

-- --------------------------------------------------------

--
-- Structure de la table `formation`
--

CREATE TABLE `formation` (
  `id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `current` tinyint(1) DEFAULT 0,
  `title` varchar(300) NOT NULL,
  `school` varchar(200) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `knowledge` text DEFAULT NULL COMMENT 'JSON array of knowledge IDs',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `formation`
--

INSERT INTO `formation` (`id`, `start_date`, `end_date`, `current`, `title`, `school`, `location`, `knowledge`, `created_at`, `updated_at`) VALUES
(1, '2015-09-01', '2018-06-30', 0, 'Licence Informatique', 'EEMI', 'Paris', '[1,2,3]', '2026-02-03 14:23:13', '2026-02-03 14:57:36'),
(2, '0000-00-00', '0000-00-00', 0, 'Master Informatique', '', '', '[\"4\",\"5\",\"6\"]', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(3, '0000-00-00', '0000-00-00', 0, '', 'Université Paris-Saclay', '', '[\"7\",\"8\",\"9\"]', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(4, '0000-00-00', NULL, 0, 'Master Informatique', 'Université Paris-Saclay', NULL, NULL, '2026-02-04 22:16:40', '2026-02-04 22:16:40'),
(5, '0000-00-00', NULL, 0, 'Licence Informatique', 'Université de Lyon', NULL, NULL, '2026-02-04 22:16:40', '2026-02-04 22:16:40'),
(6, '0000-00-00', NULL, 0, 'Formation football', 'Centre de formation Metz', NULL, NULL, '2026-02-04 22:34:06', '2026-02-04 22:34:06'),
(7, '0000-00-00', NULL, 0, 'Master Informatique', 'Université Paris-Saclay', NULL, NULL, '2026-02-04 22:53:13', '2026-02-04 22:53:13'),
(8, '0000-00-00', NULL, 0, 'Licence Informatique', 'Université de Lyon', NULL, NULL, '2026-02-04 22:53:13', '2026-02-04 22:53:13'),
(9, '0000-00-00', '0000-00-00', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', NULL, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(10, '0000-00-00', '0000-00-00', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', NULL, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(11, '0000-00-00', '0000-00-00', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', NULL, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(12, '0000-00-00', '0000-00-00', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', NULL, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(13, '2015-05-10', '2017-05-10', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', NULL, '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(14, '2010-01-01', '2013-01-01', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', NULL, '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(15, '0000-00-00', '0000-00-00', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', NULL, '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(16, '2010-01-01', '2013-01-01', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', NULL, '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(17, '2024-01-01', NULL, 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', NULL, '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(18, '0000-00-00', '0000-00-00', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', NULL, '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(19, '2015-01-01', '0000-00-00', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', NULL, '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(20, '2010-01-01', '2013-01-01', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', NULL, '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(21, '0000-00-00', '0000-00-00', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', NULL, '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(22, '2010-01-01', '2013-01-01', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', NULL, '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(23, '2014-01-01', '2016-01-01', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', NULL, '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(24, '0000-00-00', '0000-00-00', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', NULL, '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(25, '0000-00-00', '0000-00-00', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', '[\"10\",\"11\",\"12\"]', '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(26, '0000-00-00', '0000-00-00', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', '[\"13\",\"14\",\"15\"]', '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(27, '2015-01-01', '2017-01-01', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', '[\"16\",\"17\",\"18\"]', '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(28, '2012-01-01', '2015-01-01', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', '[\"19\",\"20\",\"21\"]', '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(29, '0000-00-00', '0000-00-00', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', '[\"22\",\"23\",\"24\"]', '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(30, '0000-00-00', '0000-00-00', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', '[\"25\",\"26\",\"27\"]', '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(31, '0000-00-00', '0000-00-00', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', '[\"28\",\"29\",\"30\"]', '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(32, '0000-00-00', '0000-00-00', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', '[\"31\",\"32\",\"33\"]', '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(33, '2015-01-01', '2018-01-01', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', '[\"34\",\"35\",\"36\"]', '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(34, '2010-01-01', '2013-01-01', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', '[\"37\",\"38\",\"39\"]', '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(35, '2013-01-01', '2015-01-01', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', '[\"40\",\"41\",\"42\"]', '2026-02-04 23:58:30', '2026-02-04 23:58:30'),
(36, '2010-01-01', '2013-01-01', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', '[\"43\",\"44\",\"45\"]', '2026-02-04 23:58:30', '2026-02-04 23:58:30'),
(37, '0000-00-00', '0000-00-00', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', '[\"46\",\"47\",\"48\"]', '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(38, '0000-00-00', '0000-00-00', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', '[\"49\",\"50\",\"51\"]', '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(39, '0000-00-00', '0000-00-00', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', NULL, '2026-02-14 16:24:11', '2026-02-14 16:24:11'),
(40, '0000-00-00', '0000-00-00', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', NULL, '2026-02-14 16:24:11', '2026-02-14 16:24:11'),
(41, '0000-00-00', '0000-00-00', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', NULL, '2026-02-14 16:25:06', '2026-02-14 16:25:06'),
(42, '0000-00-00', '0000-00-00', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', NULL, '2026-02-14 16:25:06', '2026-02-14 16:25:06'),
(43, '0000-00-00', '0000-00-00', 0, 'Master Informatique', 'Université Paris-Saclay', 'Paris', '[\"52\",\"53\",\"54\"]', '2026-02-14 16:26:39', '2026-02-14 16:26:39'),
(44, '0000-00-00', '0000-00-00', 0, 'Licence Informatique', 'Université de Lyon', 'Lyon', '[\"55\",\"56\",\"57\"]', '2026-02-14 16:26:39', '2026-02-14 16:26:39');

-- --------------------------------------------------------

--
-- Structure de la table `hobbie`
--

CREATE TABLE `hobbie` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `hobbie`
--

INSERT INTO `hobbie` (`id`, `title`, `created_at`, `updated_at`) VALUES
(1, 'Football', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(2, 'Lecture', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(3, 'Photographie', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(4, 'Array', '2026-02-04 20:46:24', '2026-02-04 20:46:24'),
(5, 'Array', '2026-02-04 20:46:24', '2026-02-04 20:46:24'),
(6, 'CQC', '2026-02-04 21:23:55', '2026-02-04 21:23:55'),
(7, 'Développement Open Source', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(8, 'Course à pied', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(9, 'Photographie', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(10, 'newId', '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(11, 'newId', '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(12, 'newId', '2026-02-04 21:43:32', '2026-02-04 21:43:32'),
(13, 'newId', '2026-02-04 21:43:32', '2026-02-04 21:43:32'),
(14, 'newId', '2026-02-04 21:43:32', '2026-02-04 21:43:32'),
(15, 'newId', '2026-02-04 21:43:32', '2026-02-04 21:43:32'),
(16, 'Football', '2026-02-04 22:34:07', '2026-02-04 22:34:07'),
(17, 'Lecture du coran', '2026-02-04 22:34:07', '2026-02-04 22:34:07');

-- --------------------------------------------------------

--
-- Structure de la table `identity`
--

CREATE TABLE `identity` (
  `id` int(11) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `degree_id` int(11) DEFAULT NULL,
  `job_title` varchar(200) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `vehicule` tinyint(1) DEFAULT 0,
  `description` text DEFAULT NULL,
  `language_ids` text DEFAULT NULL COMMENT 'JSON array of language IDs',
  `hobbie_ids` text DEFAULT NULL COMMENT 'JSON array of hobbie IDs',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `identity`
--

INSERT INTO `identity` (`id`, `last_name`, `first_name`, `birthdate`, `degree_id`, `job_title`, `phone`, `location`, `email`, `vehicule`, `description`, `language_ids`, `hobbie_ids`, `created_at`, `updated_at`) VALUES
(1, 'Konda', 'Eudes', '1992-06-09', 6, 'Développeur Full Stack', '+33 6 12 34 56 78', 'Mulhouse', 'jean.dupont@example.com', 0, 'Développeur passionné avec 5 ans d\'expérience', '[1,2]', '[1,2]', '2026-02-03 14:23:13', '2026-02-06 19:23:08'),
(6, 'da Silva', 'Jessy', '1993-02-09', 4, 'Développeur Back-End', '0756464148', 'Paris, France', 'djessy@aienova.com', 0, 'Description du candidat...', NULL, NULL, '2026-02-04 19:32:14', '2026-02-04 19:32:14'),
(11, 'de Brito', 'Jessy', '1991-05-12', 3, 'Développeur Back-End', '0699073011', 'Paris, France', 'eudes.konda@aienova.com', 0, 'Description du candidat...', NULL, NULL, '2026-02-04 20:23:03', '2026-02-04 20:23:03'),
(12, 'Konda', 'Lyse Léonie', '2001-01-01', 4, 'Pédiatre', '0699073011', 'Paris, France', 'lyse@gmail.com', 0, 'Description du candidat...', '[3,4]', '[\"4\",\"5\"]', '2026-02-04 20:46:24', '2026-02-04 21:02:53'),
(13, 'Snake', 'Solid', '1972-01-01', 5, 'Militaire', '05656566', 'Shadow Moses', 'solidsnake@gmail.com', 0, 'Keep you waiting huh !', '[\"5\",\"6\"]', '[\"6\"]', '2026-02-04 21:23:13', '2026-02-04 21:23:55'),
(14, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', '[\"7\",\"8\",\"9\"]', '[\"7\",\"8\",\"9\"]', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(15, 'Test', 'Testeur', '1972-01-01', 4, 'testeur', '0699073011', 'Paris, France', 'eudes.konda@aienova.com', 0, 'Description du candidat...', '[\"10\",\"11\"]', '[\"10\",\"11\"]', '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(16, 'Test', 'Testeur', '1972-01-01', 4, 'testeur', '0699073011', 'Paris, France', 'eudes.konda@aienova.com', 0, 'Description du candidat...', '[\"12\",\"13\"]', '[\"12\",\"13\"]', '2026-02-04 21:43:29', '2026-02-04 21:43:32'),
(17, 'Test', 'Testeur', '1972-01-01', 4, 'testeur', '0699073011', 'Paris, France', 'eudes.konda@aienova.com', 0, 'Description du candidat...', '[\"14\",\"15\"]', '[\"14\",\"15\"]', '2026-02-04 21:43:29', '2026-02-04 21:43:32'),
(18, 'Abondi', 'Josias', '1997-07-19', 4, 'Comptable', '0677451236--', 'Paris, France', 'abondi@gmail.com', 0, 'Il est interdit de pleurer', '[\"16\",\"17\",\"18\"]', NULL, '2026-02-04 22:00:57', '2026-02-04 22:00:57'),
(19, 'da Silva Santos Júnior', 'Neymar', '1992-03-03', 1, 'Footballeur', '0659859595', 'Rio de Janeiro', 'ney@gmail.com', 0, 'Description du candidat...', '[\"19\"]', NULL, '2026-02-04 22:15:12', '2026-02-04 22:15:12'),
(20, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 22:16:40', '2026-02-04 22:16:40'),
(21, 'Mané', 'Sadio', '1992-02-01', 1, 'Footballeur', '0565656', 'Riyad', 'sadio@gmail.com', 0, 'Description du candidat... Fort(e) de X années d\'expérience Professionnel(le) passionné(e) et motivé(e)', '[\"20\",\"21\",\"22\"]', '[\"16\",\"17\"]', '2026-02-04 22:34:06', '2026-02-04 22:34:07'),
(22, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 22:53:13', '2026-02-04 22:53:13'),
(23, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(24, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(25, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(26, 'Dupont', 'Jeanne', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(27, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(28, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(29, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(30, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(31, 'Nom', 'Prénom', NULL, NULL, 'Titre du poste', NULL, NULL, NULL, 0, 'Description du candidat...', NULL, NULL, '2026-02-04 23:36:12', '2026-02-04 23:36:12'),
(32, 'Dupont', 'Jean', '2000-01-01', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 23:36:55', '2026-02-04 23:36:55'),
(33, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(34, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(35, 'Konda', 'Eudes', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(36, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(37, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 23:58:29', '2026-02-04 23:58:29'),
(38, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(39, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-14 16:24:11', '2026-02-14 16:24:11'),
(40, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-14 16:25:06', '2026-02-14 16:25:06'),
(41, 'Dupont', 'Jean', '0000-00-00', 3, 'Développeur Full Stack Senior', '06 12 34 56 78', 'Paris, France', 'jean.dupont@example.com', 0, 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.', NULL, NULL, '2026-02-14 16:26:39', '2026-02-14 16:26:39');

-- --------------------------------------------------------

--
-- Structure de la table `job`
--

CREATE TABLE `job` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `salary` varchar(100) DEFAULT NULL,
  `contract_type` varchar(50) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `length` varchar(50) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `work_condition` text DEFAULT NULL,
  `need_vehicle` tinyint(1) DEFAULT 0,
  `contact_id` int(11) DEFAULT NULL,
  `requirement_id` int(11) DEFAULT NULL,
  `open` tinyint(1) DEFAULT 1,
  `approved` tinyint(1) DEFAULT NULL COMMENT 'NULL=En attente, 1=Approuvé, 0=Refusé',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `job`
--

INSERT INTO `job` (`id`, `title`, `description`, `location`, `salary`, `contract_type`, `start_date`, `length`, `company_id`, `work_condition`, `need_vehicle`, `contact_id`, `requirement_id`, `open`, `approved`, `created_at`, `updated_at`) VALUES
(1, 'Développeur Full Stack', 'Développement d\'applications web modernesssss', 'Paris', '35000-45000€', 'CDI', '2026-03-01', 'Indéterminée', 1, 'Télétravail partiel possible', 0, 1, 1, 1, 1, '2026-02-03 22:59:40', '2026-02-09 14:53:03'),
(2, 'Chef de Projet BTP', 'Gestion de projets de construction', 'Lyon', '45000-55000€', 'CDI', '2026-04-01', 'Indéterminée', 2, 'Déplacements fréquents', 1, 2, 2, 1, 1, '2026-02-03 22:59:40', '2026-02-03 23:56:08'),
(3, 'Développeur Full Stack Senior', 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants utilisant les dernières technologies web.\n\nResponsabilités principales :\n- Développer et maintenir des applications web complexes\n- Collaborer avec l\'équipe produit et design\n- Participer aux revues de code\n- Mentorer les développeurs juniors', 'Paris, France', '50000 - 65000 € par an', 'CDI', '2024-03-01', 'Temps plein', 4, 'Conditions de travail :\n- Télétravail partiel (2-3 jours par semaine)\n- Horaires flexibles\n- Environnement de travail moderne\n- Équipe jeune et dynamique\n- Opportunités de formation continue\n- Avantages sociaux : tickets restaurant, mutuelle, RTT', 0, 3, 3, 1, NULL, '2026-02-05 00:23:42', '2026-02-05 00:23:42'),
(4, 'Développeur Full Stack Senior', 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants utilisant les dernières technologies web.\n\nResponsabilités principales :\n- Développer et maintenir des applications web complexes\n- Collaborer avec l\'équipe produit et design\n- Participer aux revues de code\n- Mentorer les développeurs juniors', 'Paris, France', '50000 - 65000 € par an', 'CDI', '2024-03-01', 'Temps plein', 5, 'Conditions de travail :\n- Télétravail partiel (2-3 jours par semaine)\n- Horaires flexibles\n- Environnement de travail moderne\n- Équipe jeune et dynamique\n- Opportunités de formation continue\n- Avantages sociaux : tickets restaurant, mutuelle, RTT', 1, 4, 4, 1, NULL, '2026-02-05 00:24:24', '2026-02-05 00:25:17'),
(5, 'Développeur Full Stack Senior', 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants utilisant les dernières technologies web.\n\nResponsabilités principales :\n- Développer et maintenir des applications web complexes\n- Collaborer avec l\'équipe produit et design\n- Participer aux revues de code\n- Mentorer les développeurs juniors', 'Paris, France', '50000 - 65000 € par an', 'CDI', '2024-03-01', 'Temps plein', 6, 'Conditions de travail :\n- Télétravail partiel (2-3 jours par semaine)\n- Horaires flexibles\n- Environnement de travail moderne\n- Équipe jeune et dynamique\n- Opportunités de formation continue\n- Avantages sociaux : tickets restaurant, mutuelle, RTT', 0, 5, 5, 1, NULL, '2026-02-05 00:24:25', '2026-02-05 00:24:25'),
(6, 'Développeur Full Stack Senior', 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants utilisant les dernières technologies web.\n\nResponsabilités principales :\n- Développer et maintenir des applications web complexes\n- Collaborer avec l\'équipe produit et design\n- Participer aux revues de code\n- Mentorer les développeurs juniors', 'Paris, France', '50000 - 65000 € par an', 'CDI', '2024-03-01', 'Temps plein', 7, 'Conditions de travail :\n- Télétravail partiel (2-3 jours par semaine)\n- Horaires flexibles\n- Environnement de travail moderne\n- Équipe jeune et dynamique\n- Opportunités de formation continue\n- Avantages sociaux : tickets restaurant, mutuelle, RTT', 0, 6, 6, 1, 1, '2026-02-07 01:03:49', '2026-02-09 14:53:14'),
(7, 'Développeur Full Stack Senior', 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants utilisant les dernières technologies web.\n\nResponsabilités principales :\n- Développer et maintenir des applications web complexes\n- Collaborer avec l\'équipe produit et design\n- Participer aux revues de code\n- Mentorer les développeurs juniors', 'Paris, France', '50000 - 65000 € par an', 'CDI', '2024-03-01', 'Temps plein', 8, 'Conditions de travail :\n- Télétravail partiel (2-3 jours par semaine)\n- Horaires flexibles\n- Environnement de travail moderne\n- Équipe jeune et dynamique\n- Opportunités de formation continue\n- Avantages sociaux : tickets restaurant, mutuelle, RTT', 0, 7, 7, 1, NULL, '2026-02-14 16:27:06', '2026-02-14 16:27:06');

-- --------------------------------------------------------

--
-- Structure de la table `knowledge`
--

CREATE TABLE `knowledge` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `knowledge`
--

INSERT INTO `knowledge` (`id`, `title`, `created_at`, `updated_at`) VALUES
(1, 'Programmation orientée objet', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(2, 'Bases de données', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(3, 'Algorithmique', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(4, 'Algorithmique avancée', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(5, 'Bases de données', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(6, 'Architecture logicielle', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(7, 'Programmation orientée objet', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(8, 'Réseaux', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(9, 'Systèmes d\'exploitation', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(10, 'Algorithmique avancée', '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(11, 'Bases de données', '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(12, 'Architecture logicielle', '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(13, 'Programmation orientée objet', '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(14, 'Réseaux', '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(15, 'Systèmes d\'exploitation', '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(16, 'Algorithmique avancée', '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(17, 'Bases de données', '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(18, 'Architecture logicielle', '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(19, 'Programmation orientée objet', '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(20, 'Réseaux', '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(21, 'Systèmes d\'exploitation', '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(22, 'Algorithmique avancée', '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(23, 'Bases de données', '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(24, 'Architecture logicielle', '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(25, 'Programmation orientée objet', '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(26, 'Réseaux', '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(27, 'Systèmes d\'exploitation', '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(28, 'Algorithmique avancée', '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(29, 'Bases de données', '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(30, 'Architecture logicielle', '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(31, 'Programmation orientée objet', '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(32, 'Réseaux', '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(33, 'Systèmes d\'exploitation', '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(34, 'Algorithmique avancée', '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(35, 'Bases de données', '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(36, 'Architecture logicielle', '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(37, 'Programmation orientée objet', '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(38, 'Réseaux', '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(39, 'Systèmes d\'exploitation', '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(40, 'Algorithmique avancée', '2026-02-04 23:58:30', '2026-02-04 23:58:30'),
(41, 'Bases de données', '2026-02-04 23:58:30', '2026-02-04 23:58:30'),
(42, 'Architecture logicielle', '2026-02-04 23:58:30', '2026-02-04 23:58:30'),
(43, 'Programmation orientée objet', '2026-02-04 23:58:30', '2026-02-04 23:58:30'),
(44, 'Réseaux', '2026-02-04 23:58:30', '2026-02-04 23:58:30'),
(45, 'Systèmes d\'exploitation', '2026-02-04 23:58:30', '2026-02-04 23:58:30'),
(46, 'Algorithmique avancée', '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(47, 'Bases de données', '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(48, 'Architecture logicielle', '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(49, 'Programmation orientée objet', '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(50, 'Réseaux', '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(51, 'Systèmes d\'exploitation', '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(52, 'Algorithmique avancée', '2026-02-14 16:26:39', '2026-02-14 16:26:39'),
(53, 'Bases de données', '2026-02-14 16:26:39', '2026-02-14 16:26:39'),
(54, 'Architecture logicielle', '2026-02-14 16:26:39', '2026-02-14 16:26:39'),
(55, 'Programmation orientée objet', '2026-02-14 16:26:39', '2026-02-14 16:26:39'),
(56, 'Réseaux', '2026-02-14 16:26:39', '2026-02-14 16:26:39'),
(57, 'Systèmes d\'exploitation', '2026-02-14 16:26:39', '2026-02-14 16:26:39');

-- --------------------------------------------------------

--
-- Structure de la table `language`
--

CREATE TABLE `language` (
  `id` int(11) NOT NULL,
  `language_name` varchar(100) NOT NULL,
  `level_id` int(11) DEFAULT NULL,
  `is_native` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `language`
--

INSERT INTO `language` (`id`, `language_name`, `level_id`, `is_native`, `created_at`, `updated_at`) VALUES
(1, 'Français', 7, 1, '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(2, 'Anglais', 5, 0, '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(3, 'Array', NULL, 0, '2026-02-04 20:46:24', '2026-02-04 20:46:24'),
(4, 'Array', NULL, 0, '2026-02-04 20:46:24', '2026-02-04 20:46:24'),
(5, 'Allemand', NULL, 0, '2026-02-04 21:23:55', '2026-02-04 21:23:55'),
(6, 'Français', NULL, 0, '2026-02-04 21:23:55', '2026-02-04 21:23:55'),
(7, 'Français', NULL, 0, '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(8, 'Anglais', NULL, 0, '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(9, 'Espagnol', NULL, 0, '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(10, 'newId', NULL, 0, '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(11, 'newId', NULL, 0, '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(12, 'newId', NULL, 0, '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(13, 'newId', NULL, 0, '2026-02-04 21:43:32', '2026-02-04 21:43:32'),
(14, 'newId', NULL, 0, '2026-02-04 21:43:32', '2026-02-04 21:43:32'),
(15, 'newId', NULL, 0, '2026-02-04 21:43:32', '2026-02-04 21:43:32'),
(16, 'newId', NULL, 0, '2026-02-04 22:00:57', '2026-02-04 22:00:57'),
(17, 'newId', NULL, 0, '2026-02-04 22:00:57', '2026-02-04 22:00:57'),
(18, 'newId', NULL, 0, '2026-02-04 22:00:57', '2026-02-04 22:00:57'),
(19, 'Portuguais', NULL, 0, '2026-02-04 22:15:12', '2026-02-04 22:15:12'),
(20, 'Français', NULL, 0, '2026-02-04 22:34:07', '2026-02-04 22:34:07'),
(21, 'Anglais', NULL, 0, '2026-02-04 22:34:07', '2026-02-04 22:34:07'),
(22, 'Bambara', NULL, 0, '2026-02-04 22:34:07', '2026-02-04 22:34:07');

-- --------------------------------------------------------

--
-- Structure de la table `language_level`
--

CREATE TABLE `language_level` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL COMMENT 'A1, A2, B1, B2, C1, C2, Native',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `language_level`
--

INSERT INTO `language_level` (`id`, `title`, `created_at`, `updated_at`) VALUES
(1, 'A1', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(2, 'A2', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(3, 'B1', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(4, 'B2', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(5, 'C1', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(6, 'C2', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(7, 'Native', '2026-02-03 14:23:13', '2026-02-03 14:23:13');

-- --------------------------------------------------------

--
-- Structure de la table `mission`
--

CREATE TABLE `mission` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `mission`
--

INSERT INTO `mission` (`id`, `title`, `created_at`, `updated_at`) VALUES
(1, 'Développement d\'applications web', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(2, 'Maintenance du code existant', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(3, 'Formation des juniors', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(4, 'Dev POO', '2026-02-04 15:58:28', '2026-02-04 15:58:28'),
(5, 'Dev POO', '2026-02-04 15:58:28', '2026-02-04 15:58:28'),
(6, 'Développement d\'une plateforme SaaS', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(7, 'Management d\'une équipe de 5 développeurs', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(8, 'Architecture microservices', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(9, 'Développement de sites web', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(10, 'Intégration d\'APIs REST', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(11, 'Maintenance et optimisation', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(12, 'Développement d\'une plateforme SaaS', '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(13, 'Management d\'une équipe de 5 développeurs', '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(14, 'Architecture microservices', '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(15, 'Développement d\'une plateforme SaaS', '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(16, 'Management d\'une équipe de 5 développeurs', '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(17, 'Développement de sites web', '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(18, 'Architecture microservices', '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(19, 'Intégration d\'APIs REST', '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(20, 'Maintenance et optimisation', '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(21, 'Développement de sites web', '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(22, 'Intégration d\'APIs REST', '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(23, 'Maintenance et optimisation', '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(24, 'Développement d\'une plateforme SaaS', '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(25, 'Management d\'une équipe de 5 développeurs', '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(26, 'Architecture microservices', '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(27, 'Développement de sites web', '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(28, 'Intégration d\'APIs REST', '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(29, 'Maintenance et optimisation', '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(30, 'Développement de sites web', '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(31, 'Dev POO', '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(32, 'Développement d\'une plateforme SaaS', '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(33, 'Management d\'une équipe de 5 développeurs', '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(34, 'Architecture microservices', '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(35, 'Développement de sites web', '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(36, 'Intégration d\'APIs REST', '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(37, 'Maintenance et optimisation', '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(38, 'Développement d\'une plateforme SaaS', '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(39, 'Management d\'une équipe de 5 développeurs', '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(40, 'Architecture microservices', '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(41, 'Développement de sites web', '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(42, 'Intégration d\'APIs REST', '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(43, 'Maintenance et optimisation', '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(44, 'Développement d\'une plateforme SaaS', '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(45, 'Management d\'une équipe de 5 développeurs', '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(46, 'Architecture microservices', '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(47, 'Développement d\'une plateforme SaaS', '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(48, 'Management d\'une équipe de 5 développeurs', '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(49, 'Architecture microservices', '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(50, 'Développement de sites web', '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(51, 'Intégration d\'APIs REST', '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(52, 'Maintenance et optimisation', '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(53, 'Développement d\'une plateforme SaaS', '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(54, 'Management d\'une équipe de 5 développeurs', '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(55, 'Architecture microservices', '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(56, 'Développement de sites web', '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(57, 'Intégration d\'APIs REST', '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(58, 'Maintenance et optimisation', '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(59, 'Développement d\'une plateforme SaaS', '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(60, 'Management d\'une équipe de 5 développeurs', '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(61, 'Architecture microservices', '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(62, 'Développement de sites web', '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(63, 'Intégration d\'APIs REST', '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(64, 'Maintenance et optimisation', '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(65, 'Développement d\'une plateforme SaaS', '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(66, 'Management d\'une équipe de 5 développeurs', '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(67, 'Architecture microservices', '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(68, 'Développement de sites web', '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(69, 'Intégration d\'APIs REST', '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(70, 'Maintenance et optimisation', '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(71, 'Développement d\'une plateforme SaaS', '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(72, 'Management d\'une équipe de 5 développeurs', '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(73, 'Architecture microservices', '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(74, 'Développement de sites web', '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(75, 'Intégration d\'APIs REST', '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(76, 'Maintenance et optimisation', '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(77, 'Développement d\'une plateforme SaaS', '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(78, 'Management d\'une équipe de 5 développeurs', '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(79, 'Architecture microservices', '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(80, 'Développement de sites web', '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(81, 'Intégration d\'APIs REST', '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(82, 'Maintenance et optimisation', '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(83, 'Développement d\'une plateforme SaaS', '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(84, 'Management d\'une équipe de 5 développeurs', '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(85, 'Architecture microservices', '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(86, 'Développement de sites web', '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(87, 'Intégration d\'APIs REST', '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(88, 'Maintenance et optimisation', '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(89, 'Développement d\'une plateforme SaaS', '2026-02-04 23:58:29', '2026-02-04 23:58:29'),
(90, 'Management d\'une équipe de 5 développeurs', '2026-02-04 23:58:29', '2026-02-04 23:58:29'),
(91, 'Architecture microservices', '2026-02-04 23:58:30', '2026-02-04 23:58:30'),
(92, 'Développement de sites web', '2026-02-04 23:58:30', '2026-02-04 23:58:30'),
(93, 'Intégration d\'APIs REST', '2026-02-04 23:58:30', '2026-02-04 23:58:30'),
(94, 'Maintenance et optimisation', '2026-02-04 23:58:30', '2026-02-04 23:58:30'),
(95, 'Développement d\'une plateforme SaaS', '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(96, 'Management d\'une équipe de 5 développeurs', '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(97, 'Architecture microservices', '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(98, 'Développement de sites web', '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(99, 'Intégration d\'APIs REST', '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(100, 'Maintenance et optimisation', '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(101, 'Développement d\'une plateforme SaaS', '2026-02-14 16:26:39', '2026-02-14 16:26:39'),
(102, 'Management d\'une équipe de 5 développeurs', '2026-02-14 16:26:39', '2026-02-14 16:26:39'),
(103, 'Architecture microservices', '2026-02-14 16:26:39', '2026-02-14 16:26:39'),
(104, 'Développement de sites web', '2026-02-14 16:26:39', '2026-02-14 16:26:39'),
(105, 'Intégration d\'APIs REST', '2026-02-14 16:26:39', '2026-02-14 16:26:39'),
(106, 'Maintenance et optimisation', '2026-02-14 16:26:39', '2026-02-14 16:26:39');

-- --------------------------------------------------------

--
-- Structure de la table `personality`
--

CREATE TABLE `personality` (
  `id` int(11) NOT NULL,
  `trait` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `personality`
--

INSERT INTO `personality` (`id`, `trait`, `created_at`, `updated_at`) VALUES
(1, 'Autonome', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(2, 'Créatif', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(3, 'Équipe', '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(4, 'Leader', '2026-02-04 21:23:55', '2026-02-04 21:23:55'),
(5, 'Combatitif', '2026-02-04 21:23:55', '2026-02-04 21:23:55'),
(6, 'Créatif', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(7, 'Rigoureux', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(8, 'Autonome', '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(9, 'newId', '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(10, 'newId', '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(11, 'newId', '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(12, 'newId', '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(13, 'newId', '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(14, 'newId', '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(15, 'newId', '2026-02-04 22:00:57', '2026-02-04 22:00:57'),
(16, 'newId', '2026-02-04 22:00:57', '2026-02-04 22:00:57'),
(17, 'Leader', '2026-02-04 22:15:12', '2026-02-04 22:15:12'),
(18, 'Dynamique', '2026-02-04 22:15:12', '2026-02-04 22:15:12'),
(19, 'Leader', '2026-02-04 22:34:07', '2026-02-04 22:34:07'),
(20, 'Tehcnique', '2026-02-04 22:34:07', '2026-02-04 22:34:07');

-- --------------------------------------------------------

--
-- Structure de la table `requirement`
--

CREATE TABLE `requirement` (
  `id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `degree` varchar(100) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `requirement`
--

INSERT INTO `requirement` (`id`, `description`, `degree`, `experience_years`, `created_at`, `updated_at`) VALUES
(1, 'Connaissance en développement web', 'Bac+3', 2, '2026-02-03 22:59:40', '2026-02-03 22:59:40'),
(2, 'Expérience en gestion de projet', 'Bac+5', 5, '2026-02-03 22:59:40', '2026-02-03 22:59:40'),
(3, 'Compétences techniques requises :\n- JavaScript, TypeScript, Node.js\n- React, Vue.js ou Angular\n- SQL et NoSQL\n- Git, Docker\n- API REST et GraphQL\n\nCompétences souhaitées :\n- Expérience avec des méthodologies Agile\n- Bonnes capacités de communication\n- Autonomie et esprit d\'équipe\n- Anglais professionnel', 'Bac +5 (Master, diplôme d\'ingénieur)', 5, '2026-02-05 00:23:42', '2026-02-05 00:23:42'),
(4, 'Compétences techniques requises :\n- JavaScript, TypeScript, Node.js\n- React, Vue.js ou Angular\n- SQL et NoSQL\n- Git, Docker\n- API REST et GraphQL\n\nCompétences souhaitées :\n- Expérience avec des méthodologies Agile\n- Bonnes capacités de communication\n- Autonomie et esprit d\'équipe\n- Anglais professionnel', 'Bac +5 (Master, diplôme d\'ingénieur)', 5, '2026-02-05 00:24:24', '2026-02-05 00:24:24'),
(5, 'Compétences techniques requises :\n- JavaScript, TypeScript, Node.js\n- React, Vue.js ou Angular\n- SQL et NoSQL\n- Git, Docker\n- API REST et GraphQL\n\nCompétences souhaitées :\n- Expérience avec des méthodologies Agile\n- Bonnes capacités de communication\n- Autonomie et esprit d\'équipe\n- Anglais professionnel', 'Bac +5 (Master, diplôme d\'ingénieur)', 5, '2026-02-05 00:24:25', '2026-02-05 00:24:25'),
(6, 'Compétences techniques requises :\n- JavaScript, TypeScript, Node.js\n- React, Vue.js ou Angular\n- SQL et NoSQL\n- Git, Docker\n- API REST et GraphQL\n\nCompétences souhaitées :\n- Expérience avec des méthodologies Agile\n- Bonnes capacités de communication\n- Autonomie et esprit d\'équipe\n- Anglais professionnel', 'Bac +5 (Master, diplôme d\'ingénieur)', 5, '2026-02-07 01:03:49', '2026-02-07 01:03:49'),
(7, 'Compétences techniques requises :\n- JavaScript, TypeScript, Node.js\n- React, Vue.js ou Angular\n- SQL et NoSQL\n- Git, Docker\n- API REST et GraphQL\n\nCompétences souhaitées :\n- Expérience avec des méthodologies Agile\n- Bonnes capacités de communication\n- Autonomie et esprit d\'équipe\n- Anglais professionnel', 'Bac +5 (Master, diplôme d\'ingénieur)', 5, '2026-02-14 16:27:06', '2026-02-14 16:27:06');

-- --------------------------------------------------------

--
-- Structure de la table `skill`
--

CREATE TABLE `skill` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `year` varchar(50) DEFAULT NULL,
  `favorite` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `skill`
--

INSERT INTO `skill` (`id`, `title`, `year`, `favorite`, `created_at`, `updated_at`) VALUES
(1, 'PHP', '5 ans', 1, '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(2, 'JavaScript', '3 ans', 0, '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(3, 'MySQL', '4 ans', 0, '2026-02-03 14:23:13', '2026-02-03 14:23:13'),
(4, 'Excel 5 ans d\'exp', NULL, 0, '2026-02-04 22:00:57', '2026-02-04 22:00:57'),
(5, 'Comptable 7 ans d\'exp', NULL, 0, '2026-02-04 22:00:57', '2026-02-04 22:00:57'),
(6, 'JavaScript 8 ans d\'exp', NULL, 0, '2026-02-04 22:16:40', '2026-02-04 22:16:40'),
(7, 'React 5 ans d\'exp', NULL, 0, '2026-02-04 22:16:40', '2026-02-04 22:16:40'),
(8, 'Node.js 6 ans d\'exp', NULL, 0, '2026-02-04 22:16:40', '2026-02-04 22:16:40'),
(9, 'PHP 7 ans d\'exp', NULL, 0, '2026-02-04 22:16:40', '2026-02-04 22:16:40'),
(10, 'Python 4 ans d\'exp', NULL, 0, '2026-02-04 22:16:40', '2026-02-04 22:16:40'),
(11, 'Technique de balle 7 ans d\'exp', NULL, 0, '2026-02-04 22:34:06', '2026-02-04 22:34:06'),
(12, 'passe 5 ans d\'exp', NULL, 0, '2026-02-04 22:34:06', '2026-02-04 22:34:06'),
(13, 'Tir 10 ans d\'exp', NULL, 0, '2026-02-04 22:34:06', '2026-02-04 22:34:06'),
(14, 'Centre 10 ans d\'exp', NULL, 0, '2026-02-04 22:34:06', '2026-02-04 22:34:06'),
(15, 'JavaScript', '8', 0, '2026-02-04 22:53:13', '2026-02-04 22:53:13'),
(16, 'React', '5', 0, '2026-02-04 22:53:13', '2026-02-04 22:53:13'),
(17, 'Node.js', '6', 0, '2026-02-04 22:53:13', '2026-02-04 22:53:13'),
(18, 'PHP', '7', 0, '2026-02-04 22:53:13', '2026-02-04 22:53:13'),
(19, 'Python', '4', 0, '2026-02-04 22:53:13', '2026-02-04 22:53:13'),
(20, 'JavaScript', '8', 0, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(21, 'React', '5', 0, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(22, 'Node.js', '6', 0, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(23, 'PHP', '7', 0, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(24, 'JavaScript', '8', 0, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(25, 'Python', '4', 0, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(26, 'React', '5', 0, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(27, 'Node.js', '6', 0, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(28, 'PHP', '7', 0, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(29, 'Python', '4', 0, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(30, 'JavaScript', '8', 1, '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(31, 'React', '5', 1, '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(32, 'Node.js', '6', 1, '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(33, 'PHP', '7', 0, '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(34, 'Python', '4', 0, '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(35, 'Comptable', '4', 0, '2026-02-04 23:05:12', '2026-02-04 23:05:12'),
(36, 'JavaScript', '8', 0, '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(37, 'React', '5', 0, '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(38, 'Node.js', '6', 0, '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(39, 'PHP', '7', 0, '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(40, 'Python', '4', 0, '2026-02-04 23:07:27', '2026-02-04 23:07:27'),
(41, 'JavaScript', '8', 0, '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(42, 'React', '5', 0, '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(43, 'Node.js', '6', 0, '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(44, 'PHP', '7', 0, '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(45, 'Python', '4', 0, '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(46, 'JavaScript', '8', 0, '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(47, 'React', '5', 0, '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(48, 'Node.js', '6', 0, '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(49, 'PHP', '7', 0, '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(50, 'Python', '4', 0, '2026-02-04 23:23:06', '2026-02-04 23:23:06'),
(51, 'JavaScript', '8', 0, '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(52, 'React', '5', 0, '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(53, 'Node.js', '6', 0, '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(54, 'PHP', '7', 0, '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(55, 'Python', '4', 0, '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(56, 'JavaScript', '8', 0, '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(57, 'React', '5', 0, '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(58, 'Node.js', '6', 0, '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(59, 'PHP', '7', 0, '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(60, 'Python', '4', 0, '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(61, 'JavaScript', '8', 0, '2026-02-04 23:36:55', '2026-02-04 23:36:55'),
(62, 'React', '5', 0, '2026-02-04 23:36:55', '2026-02-04 23:36:55'),
(63, 'Node.js', '6', 0, '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(64, 'PHP', '7', 0, '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(65, 'Python', '4', 0, '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(66, 'JavaScript', '8', 0, '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(67, 'React', '5', 0, '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(68, 'Node.js', '6', 0, '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(69, 'PHP', '7', 0, '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(70, 'Python', '4', 0, '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(71, 'JavaScript', '8', 0, '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(72, 'React', '5', 0, '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(73, 'Node.js', '6', 0, '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(74, 'PHP', '7', 0, '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(75, 'Python', '4', 0, '2026-02-04 23:40:23', '2026-02-04 23:40:23'),
(76, 'JavaScript', '8', 0, '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(77, 'React', '5', 0, '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(78, 'Node.js', '6', 0, '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(79, 'PHP', '7', 0, '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(80, 'Python', '4', 0, '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(81, 'JavaScript', '8', 0, '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(82, 'React', '5', 0, '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(83, 'Node.js', '6', 0, '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(84, 'PHP', '7', 0, '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(85, 'Python', '4', 0, '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(86, 'JavaScript', '8', 0, '2026-02-04 23:58:29', '2026-02-04 23:58:29'),
(87, 'React', '5', 0, '2026-02-04 23:58:29', '2026-02-04 23:58:29'),
(88, 'Node.js', '6', 0, '2026-02-04 23:58:29', '2026-02-04 23:58:29'),
(89, 'PHP', '7', 0, '2026-02-04 23:58:29', '2026-02-04 23:58:29'),
(90, 'Python', '4', 0, '2026-02-04 23:58:29', '2026-02-04 23:58:29'),
(91, 'JavaScript', '8', 0, '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(92, 'React', '5', 0, '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(93, 'Node.js', '6', 0, '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(94, 'PHP', '7', 0, '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(95, 'Python', '4', 0, '2026-02-04 23:59:44', '2026-02-04 23:59:44'),
(96, 'JavaScript', '8', 0, '2026-02-14 16:24:11', '2026-02-14 16:24:11'),
(97, 'React', '5', 0, '2026-02-14 16:24:11', '2026-02-14 16:24:11'),
(98, 'Node.js', '6', 0, '2026-02-14 16:24:11', '2026-02-14 16:24:11'),
(99, 'PHP', '7', 0, '2026-02-14 16:24:11', '2026-02-14 16:24:11'),
(100, 'Python', '4', 0, '2026-02-14 16:24:11', '2026-02-14 16:24:11'),
(101, 'JavaScript', '8', 0, '2026-02-14 16:25:06', '2026-02-14 16:25:06'),
(102, 'React', '5', 0, '2026-02-14 16:25:06', '2026-02-14 16:25:06'),
(103, 'Node.js', '6', 0, '2026-02-14 16:25:06', '2026-02-14 16:25:06'),
(104, 'PHP', '7', 0, '2026-02-14 16:25:06', '2026-02-14 16:25:06'),
(105, 'Python', '4', 0, '2026-02-14 16:25:06', '2026-02-14 16:25:06'),
(106, 'JavaScript', '8', 0, '2026-02-14 16:26:39', '2026-02-14 16:26:39'),
(107, 'React', '5', 0, '2026-02-14 16:26:39', '2026-02-14 16:26:39'),
(108, 'Node.js', '6', 0, '2026-02-14 16:26:39', '2026-02-14 16:26:39'),
(109, 'PHP', '7', 0, '2026-02-14 16:26:39', '2026-02-14 16:26:39'),
(110, 'Python', '4', 0, '2026-02-14 16:26:39', '2026-02-14 16:26:39');

-- --------------------------------------------------------

--
-- Structure de la table `social`
--

CREATE TABLE `social` (
  `id` int(11) NOT NULL,
  `linkedin` varchar(500) DEFAULT NULL,
  `twitter` varchar(500) DEFAULT NULL,
  `github` varchar(500) DEFAULT NULL,
  `website` varchar(500) DEFAULT NULL,
  `instagram` varchar(500) DEFAULT NULL,
  `behance` varchar(500) DEFAULT NULL,
  `facebook` varchar(500) DEFAULT NULL,
  `other` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `social`
--

INSERT INTO `social` (`id`, `linkedin`, `twitter`, `github`, `website`, `instagram`, `behance`, `facebook`, `other`, `created_at`, `updated_at`) VALUES
(1, 'https://linkedin.com/in/jeandupont', NULL, 'https://github.com/jeandupont', 'https://jeandupont.dev', NULL, NULL, NULL, NULL, '2026-02-03 14:23:13', '2026-02-03 14:23:13');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `candidate`
--
ALTER TABLE `candidate`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_code` (`media_code`),
  ADD KEY `fk_candidate_social` (`social_id`),
  ADD KEY `fk_candidate_demand` (`demand_id`),
  ADD KEY `idx_candidate_identity` (`identity_id`),
  ADD KEY `idx_candidate_media_code` (`media_code`),
  ADD KEY `idx_candidate_approved` (`approved`);

--
-- Index pour la table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_company_contact` (`contact_id`),
  ADD KEY `idx_company_name` (`name`);

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contact_email` (`email`);

--
-- Index pour la table `degree`
--
ALTER TABLE `degree`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demand`
--
ALTER TABLE `demand`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `experience`
--
ALTER TABLE `experience`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_experience_dates` (`start_date`,`end_date`);

--
-- Index pour la table `formation`
--
ALTER TABLE `formation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_formation_dates` (`start_date`,`end_date`);

--
-- Index pour la table `hobbie`
--
ALTER TABLE `hobbie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `identity`
--
ALTER TABLE `identity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_identity_degree` (`degree_id`),
  ADD KEY `idx_identity_email` (`email`),
  ADD KEY `idx_identity_name` (`last_name`,`first_name`);

--
-- Index pour la table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_job_company` (`company_id`),
  ADD KEY `fk_job_contact` (`contact_id`),
  ADD KEY `fk_job_requirement` (`requirement_id`),
  ADD KEY `idx_job_title` (`title`),
  ADD KEY `idx_job_location` (`location`),
  ADD KEY `idx_job_open` (`open`),
  ADD KEY `idx_job_approved` (`approved`);

--
-- Index pour la table `knowledge`
--
ALTER TABLE `knowledge`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_language_level` (`level_id`);

--
-- Index pour la table `language_level`
--
ALTER TABLE `language_level`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mission`
--
ALTER TABLE `mission`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `personality`
--
ALTER TABLE `personality`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `requirement`
--
ALTER TABLE `requirement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `skill`
--
ALTER TABLE `skill`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `social`
--
ALTER TABLE `social`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `candidate`
--
ALTER TABLE `candidate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `degree`
--
ALTER TABLE `degree`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `demand`
--
ALTER TABLE `demand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `experience`
--
ALTER TABLE `experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pour la table `formation`
--
ALTER TABLE `formation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT pour la table `hobbie`
--
ALTER TABLE `hobbie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `identity`
--
ALTER TABLE `identity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `job`
--
ALTER TABLE `job`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `knowledge`
--
ALTER TABLE `knowledge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT pour la table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `language_level`
--
ALTER TABLE `language_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `mission`
--
ALTER TABLE `mission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT pour la table `personality`
--
ALTER TABLE `personality`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `requirement`
--
ALTER TABLE `requirement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `skill`
--
ALTER TABLE `skill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT pour la table `social`
--
ALTER TABLE `social`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `candidate`
--
ALTER TABLE `candidate`
  ADD CONSTRAINT `fk_candidate_demand` FOREIGN KEY (`demand_id`) REFERENCES `demand` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_candidate_identity` FOREIGN KEY (`identity_id`) REFERENCES `identity` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_candidate_social` FOREIGN KEY (`social_id`) REFERENCES `social` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `fk_company_contact` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `identity`
--
ALTER TABLE `identity`
  ADD CONSTRAINT `fk_identity_degree` FOREIGN KEY (`degree_id`) REFERENCES `degree` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `fk_job_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_job_contact` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_job_requirement` FOREIGN KEY (`requirement_id`) REFERENCES `requirement` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `language`
--
ALTER TABLE `language`
  ADD CONSTRAINT `fk_language_level` FOREIGN KEY (`level_id`) REFERENCES `language_level` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

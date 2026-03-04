-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 24 fév. 2026 à 17:50
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
  `country_id` int(11) DEFAULT NULL,
  `activity_id` int(11) DEFAULT NULL,
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

INSERT INTO `candidate` (`id`, `identity_id`, `country_id`, `activity_id`, `skill_ids`, `personality_ids`, `social_id`, `demand_id`, `experience_ids`, `formation_ids`, `mission_ids`, `media_code`, `approved`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, '[\"1\",\"2\",\"3\"]', '[1,2,3]', 1, 1, '[1]', '[1]', '[]', '8w2H1q0U5Z3m', 1, '2026-02-03 14:23:13', '2026-02-09 12:34:11'),
(3, 6, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, '', 1, '2026-02-04 19:32:14', '2026-02-07 19:10:18'),
(8, 11, NULL, NULL, NULL, NULL, NULL, 8, NULL, NULL, NULL, '9h3C6H7Rd07b', NULL, '2026-02-04 20:23:04', '2026-02-04 20:23:04'),
(9, 12, NULL, NULL, NULL, NULL, NULL, 9, NULL, NULL, NULL, 'ON73MRN71E14', NULL, '2026-02-04 20:46:24', '2026-02-04 20:46:24'),
(10, 13, NULL, NULL, NULL, '[\"4\",\"5\"]', NULL, 10, NULL, NULL, NULL, '17S092iQ9QJk', NULL, '2026-02-04 21:23:13', '2026-02-04 21:23:55'),
(11, 14, NULL, NULL, NULL, '[\"6\",\"7\",\"8\"]', NULL, 11, '[\"3\",\"4\"]', '[\"2\",\"3\"]', NULL, 'r6r11cS0x4q7', NULL, '2026-02-04 21:40:24', '2026-02-04 21:40:24'),
(12, 15, NULL, NULL, NULL, '[\"9\",\"10\"]', NULL, 12, NULL, NULL, NULL, 'AS1aJ7072Sn1', NULL, '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(13, 16, NULL, NULL, NULL, '[\"11\",\"12\"]', NULL, 13, NULL, NULL, NULL, 'iUpSq11082W7', NULL, '2026-02-04 21:43:29', '2026-02-04 21:43:29'),
(14, 17, NULL, NULL, NULL, '[\"13\",\"14\"]', NULL, 14, NULL, NULL, NULL, '2430LZ7oR7xn', NULL, '2026-02-04 21:43:32', '2026-02-04 21:43:32'),
(15, 18, NULL, NULL, '[\"4\",\"5\"]', '[\"15\",\"16\"]', NULL, 15, NULL, NULL, NULL, '4YmO0wF945z7', 1, '2026-02-04 22:00:57', '2026-02-07 19:08:44'),
(16, 19, NULL, NULL, NULL, '[\"17\",\"18\"]', NULL, 16, NULL, NULL, NULL, 'DH4K03Q8Ja53', NULL, '2026-02-04 22:15:12', '2026-02-04 22:15:12'),
(17, 20, NULL, NULL, '[\"6\",\"7\",\"8\",\"9\",\"10\"]', NULL, NULL, 17, '[\"5\",\"6\"]', '[\"4\",\"5\"]', NULL, '30GC78XW40Fl', NULL, '2026-02-04 22:16:40', '2026-02-04 22:16:40'),
(18, 21, NULL, NULL, '[\"11\",\"12\",\"13\",\"14\"]', '[\"19\",\"20\"]', NULL, 18, '[\"7\",\"8\"]', '[\"6\"]', NULL, 'U242zc0c1dS4', 1, '2026-02-04 22:34:07', '2026-02-16 10:51:23'),
(19, 22, NULL, NULL, '[\"15\",\"16\",\"17\",\"18\",\"19\"]', NULL, NULL, 19, '[\"9\",\"10\"]', '[\"7\",\"8\"]', NULL, 'zc7r59xJ3I60', NULL, '2026-02-04 22:53:13', '2026-02-04 22:53:13'),
(20, 23, NULL, NULL, '[\"20\",\"21\",\"22\",\"23\",\"25\"]', NULL, NULL, 20, '[\"11\",\"13\"]', '[\"9\",\"10\"]', NULL, 'Lv1214YCX1K0', NULL, '2026-02-04 23:01:54', '2026-02-04 23:01:54'),
(21, 24, NULL, NULL, '[\"24\",\"26\",\"27\",\"28\",\"29\"]', NULL, NULL, 21, '[\"12\",\"14\"]', '[\"11\",\"12\"]', NULL, '06257cjY4GGW', NULL, '2026-02-04 23:01:55', '2026-02-04 23:01:55'),
(22, 25, NULL, NULL, '[\"30\",\"31\",\"32\",\"33\",\"34\",\"35\"]', NULL, NULL, 22, '[\"15\",\"16\"]', '[\"13\",\"14\"]', NULL, '167pp0Wu5tp3', 1, '2026-02-04 23:05:12', '2026-02-16 11:42:43'),
(23, 26, NULL, NULL, '[\"36\",\"37\",\"38\",\"39\",\"40\"]', NULL, NULL, 23, '[\"17\",\"18\"]', '[\"15\",\"16\"]', NULL, 'um46L0e57w7W', 0, '2026-02-04 23:07:27', '2026-02-16 10:51:42'),
(24, 27, NULL, NULL, '[\"41\",\"42\",\"43\",\"44\",\"45\"]', NULL, NULL, 24, '[\"19\",\"20\"]', '[\"17\",\"18\"]', NULL, 'W6suZ897kF17', NULL, '2026-02-04 23:14:42', '2026-02-04 23:14:42'),
(25, 28, NULL, NULL, '[\"46\",\"47\",\"48\",\"49\",\"50\"]', NULL, NULL, 25, '[\"21\",\"22\"]', '[\"19\",\"20\"]', NULL, 'OG7Il8R6140i', 0, '2026-02-04 23:23:06', '2026-02-07 19:08:33'),
(26, 29, NULL, NULL, '[\"51\",\"52\",\"53\",\"54\",\"55\"]', NULL, NULL, 26, '[\"23\",\"24\"]', '[\"21\",\"22\"]', NULL, '31Na7tJ74c7p', NULL, '2026-02-04 23:25:23', '2026-02-04 23:25:23'),
(27, 30, NULL, NULL, '[\"56\",\"57\",\"58\",\"59\",\"60\"]', NULL, NULL, 27, '[\"25\",\"26\"]', '[\"23\",\"24\"]', NULL, '2t17gK714QEL', NULL, '2026-02-04 23:30:14', '2026-02-04 23:30:14'),
(28, 31, NULL, NULL, NULL, NULL, NULL, 28, NULL, NULL, NULL, '4RJmD847KK12', NULL, '2026-02-04 23:36:12', '2026-02-04 23:36:12'),
(29, 32, NULL, NULL, '[\"61\",\"62\",\"63\",\"64\",\"65\"]', NULL, NULL, 29, '[\"27\",\"28\"]', '[\"25\",\"26\"]', NULL, '2GmD17W2t07p', NULL, '2026-02-04 23:36:56', '2026-02-04 23:36:56'),
(30, 33, NULL, NULL, '[\"66\",\"67\",\"68\",\"69\",\"70\"]', NULL, NULL, 30, '[\"29\",\"30\"]', '[\"27\",\"28\"]', NULL, '2I4GfG3Lz977', NULL, '2026-02-04 23:38:30', '2026-02-04 23:38:30'),
(31, 34, NULL, NULL, '[\"71\",\"72\",\"73\",\"74\",\"75\"]', NULL, NULL, 31, '[\"31\",\"32\"]', '[\"29\",\"30\"]', NULL, '20r3yV6t4xr4', 1, '2026-02-04 23:40:23', '2026-02-07 19:09:35'),
(32, 35, NULL, NULL, '[\"76\",\"77\",\"78\",\"79\",\"80\"]', NULL, NULL, 32, '[\"33\",\"34\"]', '[\"31\",\"32\"]', NULL, 'j07kUO3l52w8', NULL, '2026-02-04 23:50:58', '2026-02-04 23:50:58'),
(33, 36, NULL, NULL, '[\"81\",\"82\",\"83\",\"84\",\"85\"]', NULL, NULL, 33, '[\"35\",\"36\"]', '[\"33\",\"34\"]', NULL, 'g217PX4IGC04', NULL, '2026-02-04 23:52:34', '2026-02-04 23:52:34'),
(34, 37, NULL, NULL, '[\"86\",\"87\",\"88\",\"89\",\"90\"]', NULL, NULL, 34, '[\"37\",\"38\"]', '[\"35\",\"36\"]', NULL, '79or0WH2qi15', NULL, '2026-02-04 23:58:30', '2026-02-04 23:58:30'),
(35, 38, NULL, NULL, '[\"91\",\"92\",\"93\",\"94\",\"95\"]', NULL, NULL, 35, '[\"39\",\"40\"]', '[\"37\",\"38\"]', NULL, 'ZDc857J1Gm47', 1, '2026-02-04 23:59:44', '2026-02-17 13:08:35'),
(36, 39, NULL, NULL, '[\"96\",\"97\",\"98\",\"99\",\"100\"]', NULL, NULL, 36, '[\"41\",\"42\"]', '[\"39\",\"40\"]', NULL, 'L0z1P71UUF18', 1, '2026-02-14 16:24:11', '2026-02-16 10:51:14'),
(37, 40, NULL, NULL, '[\"101\",\"102\",\"103\",\"104\",\"105\"]', NULL, NULL, 37, '[\"43\",\"44\"]', '[\"41\",\"42\"]', NULL, 'wL77x3NVR190', 0, '2026-02-14 16:25:06', '2026-02-16 10:51:09'),
(38, 41, NULL, NULL, '[\"106\",\"107\",\"108\",\"109\",\"110\"]', NULL, NULL, 38, '[\"45\",\"46\"]', '[\"43\",\"44\"]', NULL, 'v5S9zA8S1b17', 0, '2026-02-14 16:26:39', '2026-02-16 10:51:05'),
(39, 42, NULL, NULL, '[\"111\",\"112\",\"113\",\"114\",\"115\"]', '[\"21\",\"22\",\"23\"]', NULL, 39, '[\"47\",\"48\"]', '[\"45\",\"46\"]', NULL, '74BtCpde0131', NULL, '2026-02-17 16:12:23', '2026-02-17 16:12:23'),
(40, 43, NULL, NULL, '[\"116\",\"117\",\"118\",\"119\",\"120\"]', '[\"24\",\"25\",\"26\"]', NULL, 40, '[\"49\",\"50\"]', '[\"47\",\"48\"]', NULL, '31c7E1t70GDn', NULL, '2026-02-17 16:38:10', '2026-02-17 16:38:10'),
(41, 44, NULL, NULL, '[\"121\",\"122\",\"123\",\"124\",\"126\"]', '[\"27\",\"28\",\"29\"]', NULL, 41, '[\"51\",\"53\"]', '[\"49\",\"51\"]', NULL, 'CUE7PV10816M', NULL, '2026-02-17 16:44:28', '2026-02-17 16:44:28'),
(42, 45, NULL, NULL, '[\"125\",\"127\",\"128\",\"129\",\"130\"]', '[\"30\",\"31\",\"32\"]', NULL, 42, '[\"52\",\"54\"]', '[\"50\",\"52\"]', NULL, 'o7I167Om1s1N', NULL, '2026-02-17 16:44:28', '2026-02-17 16:44:28'),
(43, 46, NULL, NULL, '[\"131\",\"132\",\"133\",\"134\",\"135\"]', '[\"33\",\"34\",\"35\"]', NULL, 43, '[\"55\",\"56\"]', '[\"53\",\"54\"]', NULL, '1Jg1irD67R04', NULL, '2026-02-17 16:44:32', '2026-02-17 16:44:32'),
(44, 47, NULL, NULL, '[\"136\",\"137\",\"138\",\"139\",\"140\"]', '[\"36\",\"37\",\"38\"]', NULL, 44, '[\"57\",\"58\"]', '[\"55\",\"56\"]', NULL, '7Ws411u4E7nG', NULL, '2026-02-17 16:44:33', '2026-02-17 16:44:33'),
(45, 48, NULL, NULL, '[\"141\",\"142\",\"143\",\"144\",\"145\"]', '[\"39\",\"40\",\"41\"]', NULL, 45, '[\"59\",\"60\"]', '[\"57\",\"58\"]', NULL, 'L3H6t6Jw3q14', NULL, '2026-02-17 16:44:33', '2026-02-17 16:44:33'),
(46, 49, NULL, NULL, '[\"146\",\"147\",\"148\",\"149\",\"150\"]', '[\"42\",\"43\",\"44\"]', NULL, 46, '[\"61\",\"62\"]', '[\"59\",\"60\"]', NULL, 'iUZ8hM7E1242', NULL, '2026-02-17 16:53:44', '2026-02-17 16:53:44'),
(47, 50, NULL, NULL, '[\"151\",\"152\",\"153\",\"154\",\"155\"]', '[\"45\",\"46\",\"47\"]', NULL, 47, '[\"63\",\"64\"]', '[\"61\",\"62\"]', NULL, 'W3w34SNU76l6', NULL, '2026-02-17 16:53:46', '2026-02-17 16:53:46'),
(48, 51, NULL, NULL, '[\"156\",\"157\",\"158\",\"159\",\"160\"]', '[\"48\",\"49\",\"50\"]', NULL, 48, '[\"65\",\"66\"]', '[\"63\",\"64\"]', NULL, '47EGgdb1b227', NULL, '2026-02-17 16:54:12', '2026-02-17 16:54:12'),
(49, 52, NULL, NULL, '[\"161\",\"162\",\"163\",\"164\",\"165\"]', '[\"51\",\"52\",\"53\"]', NULL, 49, '[\"67\",\"69\"]', '[\"65\",\"67\"]', NULL, '432bJ9RN1g1b', NULL, '2026-02-17 16:54:12', '2026-02-17 16:54:12'),
(50, 53, NULL, NULL, '[\"166\",\"167\",\"168\",\"169\",\"170\"]', '[\"54\",\"55\",\"56\"]', NULL, 50, '[\"68\",\"70\"]', '[\"66\",\"68\"]', NULL, 'ma23182WhK5I', NULL, '2026-02-17 16:54:12', '2026-02-17 16:54:12'),
(51, 54, NULL, NULL, '[\"171\",\"172\",\"173\",\"174\",\"175\"]', '[\"57\",\"58\",\"59\"]', NULL, 51, '[\"71\",\"72\"]', '[\"69\",\"70\"]', NULL, 'hR77m7u47oc1', NULL, '2026-02-17 16:54:37', '2026-02-17 16:54:37'),
(54, 57, NULL, NULL, '[\"186\",\"187\",\"188\",\"189\",\"190\"]', '[\"66\",\"67\",\"68\"]', NULL, 54, '[\"77\",\"78\"]', '[\"75\",\"76\"]', NULL, '3GZF338g1Ib1', NULL, '2026-02-17 17:12:11', '2026-02-17 17:12:11'),
(57, 60, NULL, NULL, '[\"201\",\"202\",\"203\",\"204\",\"205\"]', '[\"75\",\"76\",\"77\"]', NULL, 57, '[\"83\",\"84\"]', '[\"81\",\"82\"]', NULL, 'S74pS10Hdj27', NULL, '2026-02-17 17:18:01', '2026-02-17 17:18:01'),
(59, 62, NULL, NULL, '[\"211\",\"212\",\"213\",\"214\",\"215\"]', '[20,15,11]', NULL, 59, '[87,88]', '[85,86]', NULL, 'RK14cNp0W799', NULL, '2026-02-17 20:14:04', '2026-02-17 21:48:36');

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
  ADD KEY `idx_candidate_approved` (`approved`),
  ADD KEY `idx_country_id` (`country_id`),
  ADD KEY `idx_activity_id` (`activity_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `candidate`
--
ALTER TABLE `candidate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

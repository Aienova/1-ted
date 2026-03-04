-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 26 fév. 2026 à 20:49
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
-- Structure de la table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `login` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_siret` varchar(14) DEFAULT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `activity_sector` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('administrator','candidate','company') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_connection` timestamp NULL DEFAULT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `forget_code` varchar(255) DEFAULT NULL,
  `aiecoins` int(11) NOT NULL DEFAULT 0 COMMENT 'Crédits pour consulter les CV',
  `verification_code` varchar(12) DEFAULT NULL COMMENT 'Code de vérification email (12 caractères)',
  `verification_expire_date` datetime DEFAULT NULL COMMENT 'Date d\'expiration du code de vérification',
  `verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indique si l\'email a été vérifié'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `account`
--

INSERT INTO `account` (`id`, `firstname`, `lastname`, `login`, `telephone`, `company_name`, `company_siret`, `activity_id`, `country_id`, `activity_sector`, `email`, `password_hash`, `role`, `created_at`, `last_connection`, `candidate_id`, `company_id`, `token`, `forget_code`) VALUES
(1, 'Admin', 'System', 'admin', '0123456789', NULL, NULL, NULL, NULL, NULL, 'eudes.konda@aienova.com', '$2y$10$86PkAk7Lkv56jnRsXrUhnOzdd163AKTd/7MjPerMfpw5KoIFfFGra', 'administrator', '2026-01-01 09:00:00', '2026-02-21 14:29:14', NULL, NULL, NULL, 'zZ6055W1AlK8'),
(2, 'Jean', 'Dupont', 'jean.dupont', '0601020304', NULL, NULL, NULL, NULL, 'Informatique', 'jean.dupont@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-03 13:23:13', NULL, 1, NULL, NULL, NULL),
(3, 'Marie', 'Martin', 'marie.martin', '0602030405', NULL, NULL, NULL, NULL, 'Commerce', 'marie.martin@email.com', '$2y$10$86PkAk7Lkv56jnRsXrUhnOzdd163AKTd/7MjPerMfpw5KoIFfFGra', 'candidate', '2026-02-04 18:32:14', '2026-02-21 14:30:39', 3, NULL, '0ec7bc2fc99eddc0ca619d64162df2ad062e86980ea25d50131f831adccad1e6', NULL),
(4, 'Pierre', 'Bernard', 'pierre.bernard', '0603040506', NULL, NULL, NULL, NULL, 'Finance', 'pierre.bernard@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 19:23:04', NULL, 8, NULL, NULL, NULL),
(5, 'Sophie', 'Dubois', 'sophie.dubois', '0604050607', NULL, NULL, NULL, NULL, 'Marketing', 'sophie.dubois@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 19:46:24', NULL, 9, NULL, NULL, NULL),
(6, 'Luc', 'Thomas', 'luc.thomas', '0605060708', NULL, NULL, NULL, NULL, 'RH', 'luc.thomas@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 20:23:13', NULL, 10, NULL, NULL, NULL),
(7, 'Julie', 'Robert', 'julie.robert', '0606070809', NULL, NULL, NULL, NULL, 'Design', 'julie.robert@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 20:40:24', NULL, 11, NULL, NULL, NULL),
(8, 'Marc', 'Richard', 'marc.richard', '0607080910', NULL, NULL, NULL, NULL, 'Production', 'marc.richard@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 20:43:29', NULL, 12, NULL, NULL, NULL),
(9, 'Anne', 'Petit', 'anne.petit', '0608091011', NULL, NULL, NULL, NULL, 'Logistique', 'anne.petit@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 20:43:29', NULL, 13, NULL, NULL, NULL),
(10, 'François', 'Durand', 'francois.durand', '0609101112', NULL, NULL, NULL, NULL, 'Qualité', 'francois.durand@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 20:43:32', NULL, 14, NULL, NULL, NULL),
(11, 'Claire', 'Leroy', 'claire.leroy', '0610111213', NULL, NULL, NULL, NULL, 'Communication', 'claire.leroy@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 21:00:57', NULL, 15, NULL, NULL, NULL),
(12, 'David', 'Moreau', 'david.moreau', '0611121314', NULL, NULL, NULL, NULL, 'Vente', 'david.moreau@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 21:15:12', NULL, 16, NULL, NULL, NULL),
(13, 'Emma', 'Simon', 'emma.simon', '0612131415', NULL, NULL, NULL, NULL, 'Développement', 'emma.simon@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 21:16:40', NULL, 17, NULL, NULL, NULL),
(14, 'Thomas', 'Laurent', 'thomas.laurent', '0613141516', NULL, NULL, NULL, NULL, 'Support', 'thomas.laurent@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 21:34:07', NULL, 18, NULL, NULL, NULL),
(15, 'Laura', 'Lefebvre', 'laura.lefebvre', '0614151617', NULL, NULL, NULL, NULL, 'Administration', 'laura.lefebvre@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 21:53:13', NULL, 19, NULL, NULL, NULL),
(16, 'Alexandre', 'Michel', 'alexandre.michel', '0615161718', NULL, NULL, NULL, NULL, 'Comptabilité', 'alexandre.michel@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:01:54', NULL, 20, NULL, NULL, NULL),
(17, 'Camille', 'Garcia', 'camille.garcia', '0616171819', NULL, NULL, NULL, NULL, 'Juridique', 'camille.garcia@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:01:55', NULL, 21, NULL, NULL, NULL),
(18, 'Nicolas', 'Roux', 'nicolas.roux', '0617181920', NULL, NULL, NULL, NULL, 'Achat', 'nicolas.roux@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:05:12', NULL, 22, NULL, NULL, NULL),
(19, 'Léa', 'Girard', 'lea.girard', '0618192021', NULL, NULL, NULL, NULL, 'Export', 'lea.girard@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:07:27', NULL, 23, NULL, NULL, NULL),
(20, 'Julien', 'Bonnet', 'julien.bonnet', '0619202122', NULL, NULL, NULL, NULL, 'SAV', 'julien.bonnet@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:14:42', NULL, 24, NULL, NULL, NULL),
(21, 'Manon', 'Francois', 'manon.francois', '0620212223', NULL, NULL, NULL, NULL, 'Formation', 'manon.francois@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:23:06', NULL, 25, NULL, NULL, NULL),
(22, 'Maxime', 'Blanc', 'maxime.blanc', '0621222324', NULL, NULL, NULL, NULL, 'Innovation', 'maxime.blanc@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:25:23', NULL, 26, NULL, NULL, NULL),
(23, 'Sarah', 'Dupuis', 'sarah.dupuis', '0622232425', NULL, NULL, NULL, NULL, 'Recherche', 'sarah.dupuis@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:30:14', NULL, 27, NULL, NULL, NULL),
(24, 'Antoine', 'Lambert', 'antoine.lambert', '0623242526', NULL, NULL, NULL, NULL, 'Maintenance', 'antoine.lambert@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:36:12', NULL, 28, NULL, NULL, NULL),
(25, 'Chloé', 'Fontaine', 'chloe.fontaine', '0624252627', NULL, NULL, NULL, NULL, 'Sécurité', 'chloe.fontaine@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:36:56', NULL, 29, NULL, NULL, NULL),
(26, 'Hugo', 'Rousseau', 'hugo.rousseau', '0625262728', NULL, NULL, NULL, NULL, 'Audit', 'hugo.rousseau@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:38:30', NULL, 30, NULL, NULL, NULL),
(27, 'Inès', 'Vincent', 'ines.vincent', '0626272829', NULL, NULL, NULL, NULL, 'Stratégie', 'ines.vincent@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:40:23', NULL, 31, NULL, NULL, NULL),
(28, 'Lucas', 'Fournier', 'lucas.fournier', '0627282930', NULL, NULL, NULL, NULL, 'Conseil', 'lucas.fournier@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:50:58', NULL, 32, NULL, NULL, NULL),
(29, 'Zoé', 'Morel', 'zoe.morel', '0628293031', NULL, NULL, NULL, NULL, 'Expertise', 'zoe.morel@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:52:34', NULL, 33, NULL, NULL, NULL),
(30, 'Nathan', 'Giraud', 'nathan.giraud', '0629303132', NULL, NULL, NULL, NULL, 'Gestion', 'nathan.giraud@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:58:30', NULL, 34, NULL, NULL, NULL),
(31, 'Océane', 'Andre', 'oceane.andre', '0630313233', NULL, NULL, NULL, NULL, 'Direction', 'oceane.andre@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:59:44', NULL, 35, NULL, NULL, NULL),
(32, 'Louis', 'Mercier', 'louis.mercier', '0631323334', NULL, NULL, NULL, NULL, 'Pilotage', 'louis.mercier@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-14 15:24:11', NULL, 36, NULL, NULL, NULL),
(33, 'Ambre', 'Blanc', 'ambre.blanc', '0632333435', NULL, NULL, NULL, NULL, 'Coordination', 'ambre.blanc@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-14 15:25:06', NULL, 37, NULL, NULL, NULL),
(34, 'Mathis', 'Guerin', 'mathis.guerin', '0633343536', NULL, NULL, NULL, NULL, 'Animation', 'mathis.guerin@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-14 15:26:39', NULL, 38, NULL, NULL, NULL),
(35, 'Robert', 'Dupont', 'aienova', '0140123456', 'Aienova', '12345678901234', NULL, NULL, 'Informatique', 'contact@aienova.com', '$2y$10$86PkAk7Lkv56jnRsXrUhnOzdd163AKTd/7MjPerMfpw5KoIFfFGra', 'company', '2026-02-03 21:59:40', '2026-02-19 12:21:48', NULL, 1, NULL, NULL),
(36, 'Jean', 'Martin', 'buildpro', '0472345678', 'BuildPro', '23456789012345', NULL, NULL, 'BTP', 'contact@buildpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'company', '2026-02-03 21:59:40', NULL, NULL, 2, NULL, NULL),
(37, 'Sophie', 'Bernard', 'company3', '0140234567', 'Entreprise 3', '34567890123456', NULL, NULL, 'Services', 'contact@company3.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'company', '2026-02-04 23:23:39', NULL, NULL, 3, NULL, NULL),
(38, 'Michel', 'Leroy', 'techcorp', '0140345678', 'TechCorp Solutions', '45678901234567', NULL, NULL, 'Technologies de l\'information', 'contact@techcorp.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'company', '2026-02-04 23:23:42', NULL, NULL, 4, NULL, NULL),
(39, 'Pierre', 'Moreau', 'aienova2', '0140456789', 'Aienova', '56789012345678', NULL, NULL, 'Technologies de l\'information', 'contact2@aienova.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'company', '2026-02-04 23:24:24', NULL, NULL, 5, NULL, NULL),
(40, 'Marie', 'Dubois', 'aienova3', '0140567890', 'Aienova', '67890123456789', NULL, NULL, 'Technologies de l\'information', 'contact3@aienova.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'company', '2026-02-04 23:24:24', NULL, NULL, 6, NULL, NULL),
(41, 'François', 'Rousseau', 'techcorp2', '0140678901', 'TechCorp Solutions', '78901234567890', NULL, NULL, 'Technologies de l\'information', 'contact2@techcorp.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'company', '2026-02-07 00:03:49', NULL, NULL, 7, NULL, NULL),
(42, 'Jean', 'Dupont', 'jean', '06 12 34 56 78', NULL, NULL, NULL, NULL, NULL, 'jean.dupont@example.com', '$2y$10$c5TDxhLVp1SsXrXAmHFczu41EUCAIfsHeu3cGHBVFEC8Ik8kRHfF.', 'candidate', '2026-02-17 17:00:03', NULL, 52, NULL, NULL, NULL),
(43, 'Leon', 'Perrault', 'leon', '06 12 34 56 78', NULL, NULL, NULL, NULL, NULL, 'leon.perrault@example.com', '$2y$10$/0psPgp1Y.3iIKZKrsNKGOBWOi//Q23Y7npw2uCUJAdWljudlL5i2', 'candidate', '2026-02-17 17:12:25', NULL, 54, NULL, NULL, NULL),
(44, 'Flora', 'Lunaire', 'flora', '06 12 34 56 78', NULL, NULL, NULL, NULL, NULL, 'flora.lunaire@example.com', '$2y$10$L3toqaoV/Y1G1jFjpozleudC0PqeMz4sSH9LNnhU4F3rF8bnPmLM2', 'candidate', '2026-02-17 17:18:21', '2026-02-17 17:18:51', 57, NULL, '15313cd835a600ad3981b7535cca5985307a4cefc58ad9a22453cdc3c024c133', NULL),
(45, 'Jean', 'Bebel', 'jean.bebel', '06 12 34 56 78', NULL, NULL, NULL, NULL, NULL, 'jean.bebel@example.com', '$2y$10$Ocp10RVo/6wZ3uZKSwbAaOXM4DRhGcqGpiTeowxunfuIx4yA8dutG', 'candidate', '2026-02-17 20:14:25', '2026-02-17 21:51:40', 59, NULL, '66e7002c52f10ae9d7a026eeba79c059ee26ada0c56fecc76915d0895a6310e7', NULL),
(46, 'Marie', 'Dupont', 'tech', '+33 1 23 45 67 89', NULL, NULL, NULL, NULL, NULL, 'marie.dupont@techcorp.com', '$2y$10$cql/j306tja8kazTkMOeP.KrwRp1Vjf.Vm0qhLaFQ9Ra5V.0hfDKq', 'company', '2026-02-18 19:20:05', '2026-02-18 19:26:19', NULL, 16, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_candidate_id` (`candidate_id`),
  ADD KEY `idx_company_id` (`company_id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_login` (`login`),
  ADD KEY `idx_activity_id` (`activity_id`),
  ADD KEY `idx_country_id` (`country_id`),
  ADD KEY `idx_forget_code` (`forget_code`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

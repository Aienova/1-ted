-- Structure et données pour le système de comptes
-- Tables: account, preference, application, suggestion

-- --------------------------------------------------------
-- Structure de la table `account`
-- --------------------------------------------------------

CREATE TABLE `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `login` varchar(255) NOT NULL UNIQUE,
  `telephone` varchar(20) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_siret` varchar(14) DEFAULT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('administrator', 'candidate', 'recruiter') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_connection` timestamp NULL DEFAULT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_candidate_id` (`candidate_id`),
  KEY `idx_company_id` (`company_id`),
  KEY `idx_activity_id` (`activity_id`),
  KEY `idx_country_id` (`country_id`),
  KEY `idx_email` (`email`),
  KEY `idx_login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure de la table `preference`
-- --------------------------------------------------------

CREATE TABLE `preference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `keywords` text DEFAULT NULL,
  `maximum_age` int(11) DEFAULT NULL,
  `minimum_experience` int(11) DEFAULT NULL,
  `minimum_salary` decimal(10,2) DEFAULT NULL,
  `maximum_salary` decimal(10,2) DEFAULT NULL,
  `locations` text DEFAULT NULL,
  `suggestion_activated` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_account_id` (`account_id`),
  FOREIGN KEY (`account_id`) REFERENCES `account`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure de la table `application`
-- --------------------------------------------------------

CREATE TABLE `application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `candidate_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `recruiter_validation` tinyint(1) DEFAULT 0,
  `candidate_validation` tinyint(1) DEFAULT 0,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_candidate_id` (`candidate_id`),
  KEY `idx_job_id` (`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure de la table `suggestion`
-- --------------------------------------------------------

CREATE TABLE `suggestion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_job_id` (`job_id`),
  KEY `idx_candidate_id` (`candidate_id`),
  KEY `idx_company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Insertion des données - Compte administrateur
-- --------------------------------------------------------

INSERT INTO `account` (`id`, `firstname`, `lastname`, `login`, `telephone`, `company_name`, `company_siret`, `activity_id`, `country_id`, `email`, `password_hash`, `role`, `created_at`, `last_connection`, `candidate_id`, `company_id`, `token`) VALUES
(1, 'Admin', 'System', 'admin', '0123456789', NULL, NULL, NULL, NULL, 'admin@1ted.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'administrator', '2026-01-01 10:00:00', '2026-02-15 08:00:00', NULL, NULL, NULL);

-- --------------------------------------------------------
-- Insertion des données - Comptes candidats
-- --------------------------------------------------------

INSERT INTO `account` (`firstname`, `lastname`, `login`, `telephone`, `company_name`, `company_siret`, `activity_id`, `country_id`, `email`, `password_hash`, `role`, `created_at`, `candidate_id`) VALUES
('Jean', 'Dupont', 'jean.dupont', '0601020304', NULL, NULL, 1, 1, 'jean.dupont@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-03 14:23:13', 1),
('Marie', 'Martin', 'marie.martin', '0602030405', NULL, NULL, 4, 1, 'marie.martin@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 19:32:14', 3),
('Pierre', 'Bernard', 'pierre.bernard', '0603040506', NULL, NULL, 5, 1, 'pierre.bernard@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 20:23:04', 8),
('Sophie', 'Dubois', 'sophie.dubois', '0604050607', NULL, NULL, 6, 1, 'sophie.dubois@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 20:46:24', 9),
('Luc', 'Thomas', 'luc.thomas', '0605060708', NULL, NULL, 7, 1, 'luc.thomas@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 21:23:13', 10),
('Julie', 'Robert', 'julie.robert', '0606070809', NULL, NULL, 20, 1, 'julie.robert@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 21:40:24', 11),
('Marc', 'Richard', 'marc.richard', '0607080910', NULL, NULL, 8, 1, 'marc.richard@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 21:43:29', 12),
('Anne', 'Petit', 'anne.petit', '0608091011', NULL, NULL, 9, 1, 'anne.petit@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 21:43:29', 13),
('François', 'Durand', 'francois.durand', '0609101112', NULL, NULL, 1, 1, 'francois.durand@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 21:43:32', 14),
('Claire', 'Leroy', 'claire.leroy', '0610111213', NULL, NULL, 6, 1, 'claire.leroy@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:00:57', 15),
('David', 'Moreau', 'david.moreau', '0611121314', NULL, NULL, 4, 1, 'david.moreau@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:15:12', 16),
('Emma', 'Simon', 'emma.simon', '0612131415', NULL, NULL, 1, 1, 'emma.simon@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:16:40', 17),
('Thomas', 'Laurent', 'thomas.laurent', '0613141516', NULL, NULL, 1, 1, 'thomas.laurent@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:34:07', 18),
('Laura', 'Lefebvre', 'laura.lefebvre', '0614151617', NULL, NULL, 28, 1, 'laura.lefebvre@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 22:53:13', 19),
('Alexandre', 'Michel', 'alexandre.michel', '0615161718', NULL, NULL, 5, 1, 'alexandre.michel@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 23:01:54', 20),
('Camille', 'Garcia', 'camille.garcia', '0616171819', NULL, NULL, 16, 1, 'camille.garcia@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 23:01:55', 21),
('Nicolas', 'Roux', 'nicolas.roux', '0617181920', NULL, NULL, 9, 1, 'nicolas.roux@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 23:05:12', 22),
('Léa', 'Girard', 'lea.girard', '0618192021', NULL, NULL, 4, 1, 'lea.girard@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 23:07:27', 23),
('Julien', 'Bonnet', 'julien.bonnet', '0619202122', NULL, NULL, 9, 1, 'julien.bonnet@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 23:14:42', 24),
('Manon', 'Francois', 'manon.francois', '0620212223', NULL, NULL, 10, 1, 'manon.francois@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 23:23:06', 25),
('Maxime', 'Blanc', 'maxime.blanc', '0621222324', NULL, NULL, 22, 1, 'maxime.blanc@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 23:25:23', 26),
('Sarah', 'Dupuis', 'sarah.dupuis', '0622232425', NULL, NULL, 22, 1, 'sarah.dupuis@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 23:30:14', 27),
('Antoine', 'Lambert', 'antoine.lambert', '0623242526', NULL, NULL, 8, 1, 'antoine.lambert@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 23:36:12', 28),
('Chloé', 'Fontaine', 'chloe.fontaine', '0624252627', NULL, NULL, 24, 1, 'chloe.fontaine@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 23:36:56', 29),
('Hugo', 'Rousseau', 'hugo.rousseau', '0625262728', NULL, NULL, 5, 1, 'hugo.rousseau@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 23:38:30', 30),
('Inès', 'Vincent', 'ines.vincent', '0626272829', NULL, NULL, 21, 1, 'ines.vincent@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 23:40:23', 31),
('Lucas', 'Fournier', 'lucas.fournier', '0627282930', NULL, NULL, 21, 1, 'lucas.fournier@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 23:50:58', 32),
('Zoé', 'Morel', 'zoe.morel', '0628293031', NULL, NULL, 21, 1, 'zoe.morel@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 23:52:34', 33),
('Nathan', 'Giraud', 'nathan.giraud', '0629303132', NULL, NULL, 21, 1, 'nathan.giraud@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 23:58:30', 34),
('Océane', 'Andre', 'oceane.andre', '0630313233', NULL, NULL, 21, 1, 'oceane.andre@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-04 23:59:44', 35),
('Louis', 'Mercier', 'louis.mercier', '0631323334', NULL, NULL, 21, 1, 'louis.mercier@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-14 16:24:11', 36),
('Ambre', 'Blanc', 'ambre.blanc', '0632333435', NULL, NULL, 21, 1, 'ambre.blanc@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-14 16:25:06', 37),
('Mathis', 'Guerin', 'mathis.guerin', '0633343536', NULL, NULL, 10, 1, 'mathis.guerin@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', '2026-02-14 16:26:39', 38);

-- --------------------------------------------------------
-- Insertion des données - Comptes recruteurs (entreprises)
-- --------------------------------------------------------

INSERT INTO `account` (`firstname`, `lastname`, `login`, `telephone`, `company_name`, `company_siret`, `activity_id`, `country_id`, `email`, `password_hash`, `role`, `created_at`, `company_id`) VALUES
('Robert', 'Dupont', 'aienova', '0140123456', 'Aienova', '12345678901234', 1, 1, 'contact@aienova.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'recruiter', '2026-02-03 22:59:40', 1),
('Jean', 'Martin', 'buildpro', '0472345678', 'BuildPro', '23456789012345', 2, 1, 'contact@buildpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'recruiter', '2026-02-03 22:59:40', 2),
('Sophie', 'Bernard', 'company3', '0140234567', 'Entreprise 3', '34567890123456', 30, 1, 'contact@company3.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'recruiter', '2026-02-05 00:23:39', 3),
('Michel', 'Leroy', 'techcorp', '0140345678', 'TechCorp Solutions', '45678901234567', 1, 1, 'contact@techcorp.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'recruiter', '2026-02-05 00:23:42', 4),
('Pierre', 'Moreau', 'aienova2', '0140456789', 'Aienova', '56789012345678', 1, 1, 'contact2@aienova.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'recruiter', '2026-02-05 00:24:24', 5),
('Marie', 'Dubois', 'aienova3', '0140567890', 'Aienova', '67890123456789', 1, 1, 'contact3@aienova.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'recruiter', '2026-02-05 00:24:24', 6),
('François', 'Rousseau', 'techcorp2', '0140678901', 'TechCorp Solutions', '78901234567890', 1, 1, 'contact2@techcorp.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'recruiter', '2026-02-07 01:03:49', 7);

-- --------------------------------------------------------
-- Insertion de quelques préférences de recherche
-- --------------------------------------------------------

INSERT INTO `preference` (`account_id`, `keywords`, `maximum_age`, `minimum_experience`, `minimum_salary`, `maximum_salary`, `locations`, `suggestion_activated`) VALUES
(2, 'développeur, web, javascript, react', 40, 2, 35000.00, 55000.00, 'Paris, Lyon, Nantes', 1),
(3, 'commerce, vente, relation client', 45, 3, 30000.00, 45000.00, 'France', 1),
(11, 'design, ux, ui, figma', 35, 1, 32000.00, 48000.00, 'Paris, Bordeaux', 1),
(39, 'développeur, senior, php, symfony', 50, 5, 40000.00, 65000.00, 'Paris, Toulouse', 1),
(40, 'ingénieur, btp, construction', 45, 3, 38000.00, 58000.00, 'Lyon, Marseille', 1);

-- Note: Le mot de passe haché correspond à 'password' (à des fins de test uniquement)
-- En production, les utilisateurs doivent définir leurs propres mots de passe sécurisés

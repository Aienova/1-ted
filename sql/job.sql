-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 16 fév. 2026 à 16:53
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
-- Structure de la table `job`
--

CREATE TABLE `job` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
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

INSERT INTO `job` (`id`, `title`, `description`, `location`, `activity_id`, `country_id`, `salary`, `contract_type`, `start_date`, `length`, `company_id`, `work_condition`, `need_vehicle`, `contact_id`, `requirement_id`, `open`, `approved`, `created_at`, `updated_at`) VALUES
(1, 'Développeur Full Stack', 'Développement d\'applications web modernesssss', 'Paris', NULL, NULL, '35000-45000€', 'CDI', '2026-03-01', 'Indéterminée', 1, 'Télétravail partiel possible', 0, 1, 1, 1, 1, '2026-02-03 22:59:40', '2026-02-09 14:53:03'),
(2, 'Chef de Projet BTP', 'Gestion de projets de construction', 'Lyon', NULL, NULL, '45000-55000€', 'CDI', '2026-04-01', 'Indéterminée', 2, 'Déplacements fréquents', 1, 2, 2, 1, 1, '2026-02-03 22:59:40', '2026-02-03 23:56:08'),
(3, 'Développeur Full Stack Senior', 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants utilisant les dernières technologies web.\n\nResponsabilités principales :\n- Développer et maintenir des applications web complexes\n- Collaborer avec l\'équipe produit et design\n- Participer aux revues de code\n- Mentorer les développeurs juniors', 'Paris, France', NULL, NULL, '50000 - 65000 € par an', 'CDI', '2024-03-01', 'Temps plein', 4, 'Conditions de travail :\n- Télétravail partiel (2-3 jours par semaine)\n- Horaires flexibles\n- Environnement de travail moderne\n- Équipe jeune et dynamique\n- Opportunités de formation continue\n- Avantages sociaux : tickets restaurant, mutuelle, RTT', 0, 3, 3, 1, NULL, '2026-02-05 00:23:42', '2026-02-05 00:23:42'),
(4, 'Développeur Full Stack Senior', 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants utilisant les dernières technologies web.\n\nResponsabilités principales :\n- Développer et maintenir des applications web complexes\n- Collaborer avec l\'équipe produit et design\n- Participer aux revues de code\n- Mentorer les développeurs juniors', 'Paris, France', NULL, NULL, '50000 - 65000 € par an', 'CDI', '2024-03-01', 'Temps plein', 5, 'Conditions de travail :\n- Télétravail partiel (2-3 jours par semaine)\n- Horaires flexibles\n- Environnement de travail moderne\n- Équipe jeune et dynamique\n- Opportunités de formation continue\n- Avantages sociaux : tickets restaurant, mutuelle, RTT', 1, 4, 4, 1, NULL, '2026-02-05 00:24:24', '2026-02-05 00:25:17'),
(5, 'Développeur Full Stack Senior', 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants utilisant les dernières technologies web.\n\nResponsabilités principales :\n- Développer et maintenir des applications web complexes\n- Collaborer avec l\'équipe produit et design\n- Participer aux revues de code\n- Mentorer les développeurs juniors', 'Paris, France', NULL, NULL, '50000 - 65000 € par an', 'CDI', '2024-03-01', 'Temps plein', 6, 'Conditions de travail :\n- Télétravail partiel (2-3 jours par semaine)\n- Horaires flexibles\n- Environnement de travail moderne\n- Équipe jeune et dynamique\n- Opportunités de formation continue\n- Avantages sociaux : tickets restaurant, mutuelle, RTT', 0, 5, 5, 1, NULL, '2026-02-05 00:24:25', '2026-02-05 00:24:25'),
(6, 'Développeur Full Stack Senior', 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants utilisant les dernières technologies web.\n\nResponsabilités principales :\n- Développer et maintenir des applications web complexes\n- Collaborer avec l\'équipe produit et design\n- Participer aux revues de code\n- Mentorer les développeurs juniors', 'Paris, France', NULL, NULL, '50000 - 65000 € par an', 'CDI', '2024-03-01', 'Temps plein', 7, 'Conditions de travail :\n- Télétravail partiel (2-3 jours par semaine)\n- Horaires flexibles\n- Environnement de travail moderne\n- Équipe jeune et dynamique\n- Opportunités de formation continue\n- Avantages sociaux : tickets restaurant, mutuelle, RTT', 0, 6, 6, 1, 1, '2026-02-07 01:03:49', '2026-02-09 14:53:14'),
(7, 'Développeur Full Stack Senior', 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants utilisant les dernières technologies web.\n\nResponsabilités principales :\n- Développer et maintenir des applications web complexes\n- Collaborer avec l\'équipe produit et design\n- Participer aux revues de code\n- Mentorer les développeurs juniors', 'Paris, France', NULL, NULL, '50000 - 65000 € par an', 'CDI', '2024-03-01', 'Temps plein', 8, 'Conditions de travail :\n- Télétravail partiel (2-3 jours par semaine)\n- Horaires flexibles\n- Environnement de travail moderne\n- Équipe jeune et dynamique\n- Opportunités de formation continue\n- Avantages sociaux : tickets restaurant, mutuelle, RTT', 0, 7, 7, 1, NULL, '2026-02-14 16:27:06', '2026-02-14 16:27:06');

--
-- Index pour les tables déchargées
--

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
  ADD KEY `idx_job_approved` (`approved`),
  ADD KEY `idx_activity_id` (`activity_id`),
  ADD KEY `idx_country_id` (`country_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `job`
--
ALTER TABLE `job`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `fk_job_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_job_contact` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_job_requirement` FOREIGN KEY (`requirement_id`) REFERENCES `requirement` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

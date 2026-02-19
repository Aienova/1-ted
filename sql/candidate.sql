-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 31 jan. 2026 à 17:58
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
  `skill_id` int(11) DEFAULT NULL,
  `social_id` int(11) DEFAULT NULL,
  `demand_id` int(11) DEFAULT NULL,
  `experience_ids` text DEFAULT NULL COMMENT 'JSON array of experience IDs',
  `formation_ids` text DEFAULT NULL COMMENT 'JSON array of formation IDs',
  `mission_ids` text DEFAULT NULL COMMENT 'JSON array of mission IDs',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `candidate`
--

INSERT INTO `candidate` (`id`, `identity_id`, `skill_id`, `social_id`, `demand_id`, `experience_ids`, `formation_ids`, `mission_ids`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, '[1]', '[1]', '[]', '2026-01-31 16:53:18', '2026-01-31 16:53:18');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `candidate`
--
ALTER TABLE `candidate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_candidate_skill` (`skill_id`),
  ADD KEY `fk_candidate_social` (`social_id`),
  ADD KEY `fk_candidate_demand` (`demand_id`),
  ADD KEY `idx_candidate_identity` (`identity_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `candidate`
--
ALTER TABLE `candidate`
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
  ADD CONSTRAINT `fk_candidate_skill` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_candidate_social` FOREIGN KEY (`social_id`) REFERENCES `social` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

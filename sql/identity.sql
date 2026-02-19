-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 31 jan. 2026 à 19:17
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
-- Structure de la table `identity`
--

CREATE TABLE `identity` (
  `id` int(11) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `degree` varchar(200) DEFAULT NULL,
  `job_title` varchar(200) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `language_ids` text DEFAULT NULL COMMENT 'JSON array of language IDs',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `identity`
--

INSERT INTO `identity` (`id`, `last_name`, `first_name`, `birthdate`, `degree`, `job_title`, `phone`, `email`, `description`, `language_ids`, `created_at`, `updated_at`) VALUES
(1, 'Dupont', 'Jean', NULL, NULL, 'Développeur Full Stack', '+33 6 12 34 56 78', 'jean.dupont@example.com', 'Développeur passionné avec 5 ans d\'expérience', '[]', '2026-01-31 16:53:18', '2026-01-31 16:53:18');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `identity`
--
ALTER TABLE `identity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_identity_email` (`email`),
  ADD KEY `idx_identity_name` (`last_name`,`first_name`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `identity`
--
ALTER TABLE `identity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

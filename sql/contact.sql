-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 06 mars 2026 à 23:49
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
(7, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-02-14 16:27:06', '2026-02-14 16:27:06'),
(8, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-02-18 17:54:54', '2026-02-18 17:54:54'),
(9, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-02-18 19:06:55', '2026-02-18 19:06:55'),
(10, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-02-18 19:16:37', '2026-02-18 19:16:37'),
(11, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-02-18 19:16:57', '2026-02-18 19:16:57'),
(12, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-02-18 19:19:17', '2026-02-18 19:19:17'),
(13, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-02-28 20:29:03', '2026-02-28 20:29:03'),
(14, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-03-03 16:37:46', '2026-03-03 16:37:46'),
(15, 'Eudes', 'Konda', 'e2venceslas@gmail.com', '+33 1 23 45 67 89', '2026-03-03 16:42:00', '2026-03-03 16:42:00'),
(16, 'Eudes', 'Konda', 'e2venceslas@gmail.com', '+33 1 23 45 67 89', '2026-03-03 16:45:21', '2026-03-03 16:45:21'),
(17, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-03-03 16:53:10', '2026-03-03 16:53:10'),
(18, 'Eudes', 'Konda', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-03-03 16:58:12', '2026-03-03 16:58:12'),
(19, 'Eudes', 'Konda', 'e2venceslas@gmail.com', '+33 1 23 45 67 89', '2026-03-03 17:12:13', '2026-03-03 17:12:13'),
(20, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-03-03 17:13:45', '2026-03-03 17:13:45'),
(21, 'Eudes', 'Konda', 'eudes.konda@aienova.com', '+33 1 23 45 67 89', '2026-03-03 17:22:22', '2026-03-03 17:24:02'),
(22, 'Eudes', 'Konda', 'e2venceslas@gmail.com', '+33 1 23 45 67 89', '2026-03-03 17:27:59', '2026-03-03 17:32:11'),
(23, '', '', '', '', '2026-03-03 17:35:10', '2026-03-03 17:35:10'),
(24, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-03-03 19:02:07', '2026-03-03 19:02:07'),
(25, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-03-03 19:08:47', '2026-03-03 19:08:47'),
(26, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-03-03 19:15:31', '2026-03-03 19:15:31'),
(27, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-03-03 19:15:56', '2026-03-03 19:15:56'),
(28, 'Marie', 'Dupont', 'marifrfrfe.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-03-06 21:40:29', '2026-03-06 21:40:29'),
(29, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-03-06 21:45:22', '2026-03-06 21:45:22'),
(30, 'Marie', 'Dupont', 'marie.duponedededt@techcorp.com', '+33 1 23 45 67 89', '2026-03-06 21:58:30', '2026-03-06 21:58:30'),
(31, 'Marie', 'Dupont', 'marie.dupont@techcorp.com', '+33 1 23 45 67 89', '2026-03-06 21:59:59', '2026-03-06 21:59:59'),
(32, 'Marie', 'Dupont', 'marie.dupont@swatch.com', '+33 1 23 45 67 89', '2026-03-06 22:01:16', '2026-03-06 22:01:16'),
(33, 'Marie', 'Dupont', 'marie.dfrffrfrfrupont@techcorp.com', '+33 1 23 45 67 89', '2026-03-06 22:14:40', '2026-03-06 22:14:40'),
(34, 'Marie', 'Dupont', 'marie.dupodeednt@techcorp.com', '+33 1 23 45 67 89', '2026-03-06 22:16:12', '2026-03-06 22:16:12'),
(35, 'Marie', 'Dupont', 'marie.duededepont@techcorp.com', '+33 1 23 45 67 89', '2026-03-06 22:23:12', '2026-03-06 22:23:12'),
(36, 'Marie', 'Dupont', 'marie.dudedepont@techcorp.com', '+33 1 23 45 67 89', '2026-03-06 22:46:02', '2026-03-06 22:46:02');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contact_email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

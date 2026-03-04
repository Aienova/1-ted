-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 04 mars 2026 à 17:12
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
-- Structure de la table `country`
--

CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(3) DEFAULT NULL,
  `flag_url` varchar(255) DEFAULT NULL,
  `default_language` varchar(50) DEFAULT NULL,
  `currency_symbol` varchar(10) DEFAULT NULL,
  `fr_name` varchar(100) DEFAULT NULL,
  `en_name` varchar(100) DEFAULT NULL,
  `de_name` varchar(100) DEFAULT NULL,
  `es_name` varchar(100) DEFAULT NULL,
  `it_name` varchar(100) DEFAULT NULL,
  `nl_name` varchar(100) DEFAULT NULL,
  `pt_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `country`
--

INSERT INTO `country` (`id`, `name`, `code`, `flag_url`, `default_language`, `currency_symbol`, `fr_name`, `en_name`, `de_name`, `es_name`, `it_name`, `nl_name`, `pt_name`) VALUES
(1, 'France', 'FR', '/media/image/icon/flag/france.jpg', 'French', '€', 'France', 'France', 'Frankreich', 'Francia', 'Francia', 'Frankrijk', 'França'),
(2, 'Allemagne', 'DE', NULL, 'German', '€', 'Allemagne', 'Germany', 'Deutschland', 'Alemania', 'Germania', 'Duitsland', 'Alemanha'),
(3, 'Italie', 'IT', NULL, 'Italian', '€', 'Italie', 'Italy', 'Italien', 'Italia', 'Italia', 'Italië', 'Itália'),
(4, 'Espagne', 'ES', NULL, 'Spanish', '€', 'Espagne', 'Spain', 'Spanien', 'España', 'Spagna', 'Spanje', 'Espanha'),
(5, 'Pays-Bas', 'NL', NULL, 'Dutch', '€', 'Pays-Bas', 'Netherlands', 'Niederlande', 'Países Bajos', 'Paesi Bassi', 'Nederland', 'Países Baixos'),
(6, 'Belgique', 'BE', NULL, 'Dutch', '€', 'Belgique', 'Belgium', 'Belgien', 'Bélgica', 'Belgio', 'België', 'Bélgica'),
(7, 'Portugal', 'PT', NULL, 'Portuguese', '€', 'Portugal', 'Portugal', 'Portugal', 'Portugal', 'Portogallo', 'Portugal', 'Portugal'),
(8, 'Autriche', 'AT', NULL, 'German', '€', 'Autriche', 'Austria', 'Österreich', 'Austria', 'Austria', 'Oostenrijk', 'Áustria'),
(9, 'Grèce', 'GR', NULL, 'Greek', '€', 'Grèce', 'Greece', 'Griechenland', 'Grecia', 'Grecia', 'Griekenland', 'Grécia'),
(10, 'Pologne', 'PL', NULL, 'Polish', 'zł', 'Pologne', 'Poland', 'Polen', 'Polonia', 'Polonia', 'Polen', 'Polónia'),
(11, 'Roumanie', 'RO', NULL, 'Romanian', 'lei', 'Roumanie', 'Romania', 'Rumänien', 'Rumania', 'Romania', 'Roemenië', 'Roménia'),
(12, 'République tchèque', 'CZ', NULL, 'Czech', 'Kč', 'République tchèque', 'Czech Republic', 'Tschechien', 'República Checa', 'Repubblica Ceca', 'Tsjechië', 'República Checa'),
(13, 'Hongrie', 'HU', NULL, 'Hungarian', 'Ft', 'Hongrie', 'Hungary', 'Ungarn', 'Hungría', 'Ungheria', 'Hongarije', 'Hungria'),
(14, 'Suède', 'SE', NULL, 'Swedish', 'kr', 'Suède', 'Sweden', 'Schweden', 'Suecia', 'Svezia', 'Zweden', 'Suécia'),
(15, 'Bulgarie', 'BG', NULL, 'Bulgarian', 'лв', 'Bulgarie', 'Bulgaria', 'Bulgarien', 'Bulgaria', 'Bulgaria', 'Bulgarije', 'Bulgária'),
(16, 'Danemark', 'DK', NULL, 'Danish', 'kr', 'Danemark', 'Denmark', 'Dänemark', 'Dinamarca', 'Danimarca', 'Denemarken', 'Dinamarca'),
(17, 'Finlande', 'FI', NULL, 'Finnish', '€', 'Finlande', 'Finland', 'Finnland', 'Finlandia', 'Finlandia', 'Finland', 'Finlândia'),
(18, 'Slovaquie', 'SK', NULL, 'Slovak', '€', 'Slovaquie', 'Slovakia', 'Slowakei', 'Eslovaquia', 'Slovacchia', 'Slowakije', 'Eslováquia'),
(19, 'Irlande', 'IE', NULL, 'English', '€', 'Irlande', 'Ireland', 'Irland', 'Irlanda', 'Irlanda', 'Ierland', 'Irlanda'),
(20, 'Croatie', 'HR', NULL, 'Croatian', '€', 'Croatie', 'Croatia', 'Kroatien', 'Croacia', 'Croazia', 'Kroatië', 'Croácia'),
(21, 'Lituanie', 'LT', NULL, 'Lithuanian', '€', 'Lituanie', 'Lithuania', 'Litauen', 'Lituania', 'Lituania', 'Litouwen', 'Lituânia'),
(22, 'Slovénie', 'SI', NULL, 'Slovenian', '€', 'Slovénie', 'Slovenia', 'Slowenien', 'Eslovenia', 'Slovenia', 'Slovenië', 'Eslovénia'),
(23, 'Lettonie', 'LV', NULL, 'Latvian', '€', 'Lettonie', 'Latvia', 'Lettland', 'Letonia', 'Lettonia', 'Letland', 'Letónia'),
(24, 'Estonie', 'EE', NULL, 'Estonian', '€', 'Estonie', 'Estonia', 'Estland', 'Estonia', 'Estonia', 'Estland', 'Estónia'),
(25, 'Chypre', 'CY', NULL, 'Greek', '€', 'Chypre', 'Cyprus', 'Zypern', 'Chipre', 'Cipro', 'Cyprus', 'Chipre'),
(26, 'Luxembourg', 'LU', NULL, 'French', '€', 'Luxembourg', 'Luxembourg', 'Luxemburg', 'Luxemburgo', 'Lussemburgo', 'Luxemburg', 'Luxemburgo'),
(27, 'Malte', 'MT', NULL, 'Maltese', '€', 'Malte', 'Malta', 'Malta', 'Malta', 'Malta', 'Malta', 'Malta'),
(28, 'Royaume-Uni', 'GB', '/media/image/icon/flag/uk.jpg', 'English', '£', 'Royaume-Uni', 'United Kingdom', 'Vereinigtes Königreich', 'Reino Unido', 'Regno Unito', 'Verenigd Koninkrijk', 'Reino Unido'),
(29, 'Suisse', 'CH', NULL, 'German', 'CHF', 'Suisse', 'Switzerland', 'Schweiz', 'Suiza', 'Svizzera', 'Zwitserland', 'Suíça');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_code` (`code`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

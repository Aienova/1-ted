-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : sam. 07 mars 2026 à 20:30
-- Version du serveur : 11.4.9-MariaDB-cll-lve
-- Version de PHP : 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `c2248065c_1-ted_france`
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
  `verification_code` varchar(12) DEFAULT NULL COMMENT 'Code de vérification email (12 caractères)',
  `verification_expire_date` datetime DEFAULT NULL COMMENT 'Date d''expiration du code de vérification',
  `verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indique si l''email a été vérifié',
  `aiecoins` int(11) NOT NULL DEFAULT 0 COMMENT 'Crédits pour consulter les CV'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `account`
--

INSERT INTO `account` (`id`, `firstname`, `lastname`, `login`, `telephone`, `company_name`, `company_siret`, `activity_id`, `country_id`, `activity_sector`, `email`, `password_hash`, `role`, `created_at`, `last_connection`, `candidate_id`, `company_id`, `token`, `forget_code`, `verification_code`, `verification_expire_date`, `verified`, `aiecoins`) VALUES
(1, 'Admin', 'System', 'admin.1-ted', '0123456789', NULL, NULL, NULL, NULL, NULL, 'admin@1-ted.com', '$2y$10$iFabhGD9ciZyERTgaemlse8SQ0o61R4g.sfHzX75nD2k2NCoMA08a', 'administrator', '2026-01-01 09:00:00', '2026-03-07 10:45:29', NULL, NULL, NULL, '2V005o3K4DlX', NULL, NULL, 0, 300),
(53, 'Eudes', 'Konda', 'eudes', '+33 7 53 47 59 13', NULL, NULL, NULL, NULL, NULL, 'aienovanothing2something@gmail.com', '$2y$12$HZ6vuZRwfUl1k2jB70qQzebqAA0gxEbCz9mJxAQMAchjImahJINc6', 'candidate', '2026-03-05 20:44:47', '2026-03-07 19:02:27', 71, NULL, '6d040d48aa515cc5991d1f4598ebc5d2d80e034a63f4449edb5a8c233ef92d0f', NULL, 'xe03Kta3kGXi', '2026-03-05 21:54:50', 0, 0),
(54, 'Jessy', 'Da Silva', 'dasilva.jessy7@gmail.com', '+33669993592', NULL, NULL, 1, 1, NULL, 'dasilva.jessy7@gmail.com', '$2y$12$aeSy.oAp8/RIIr5Za7wIX.vWC29k6HV3tHf.4xepel.cO0Q13VPAa', 'candidate', '2026-03-05 23:01:35', '2026-03-06 14:29:10', 72, NULL, NULL, NULL, 'T9ZNbDYMbug9', '2026-03-06 00:11:39', 0, 0),
(55, 'Eudes', 'Konda', 'aienova', '+33 7 53 47 59 13', NULL, NULL, NULL, NULL, NULL, 'e2venceslas@gmail.com', '$2y$12$UB/d6ABHQPxGYsBOQvXYSeB1yj4xE0W41Q.zZRhbCOU.aYSZcog.2', 'company', '2026-03-06 20:04:24', '2026-03-06 23:46:39', NULL, 32, NULL, NULL, NULL, NULL, 1, 0),
(59, 'Marie', 'Dupont', 'easport', '+33 1 23 45 67 89', NULL, NULL, NULL, NULL, NULL, 'eudes.konda@aienova.com', '$2y$12$OMfUtn4l0B6Hk6G/lCaCHeogm8.msnzyhtrZH9u/uKR4qexbG7c2S', 'company', '2026-03-06 23:48:46', '2026-03-06 23:49:03', NULL, 36, NULL, NULL, 'aBAxEe23qkqk', '2026-03-07 00:58:48', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `action`
--

CREATE TABLE `action` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Nom de l''action',
  `description` text DEFAULT NULL COMMENT 'Description de l''action',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Action active ou non',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `action`
--

INSERT INTO `action` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'candidate_community_advice', 'Demander des conseils ou avis à la communauté', 1, '2026-02-28 15:35:33', '2026-02-28 15:35:33'),
(2, 'candidate_direct_message', 'Envoyer un message direct à l\'entreprise', 1, '2026-02-28 15:35:33', '2026-02-28 15:35:33'),
(3, 'candidate_boost_active', 'Propulser sa candidature en haut de la liste (recherche activement)', 1, '2026-02-28 15:35:33', '2026-02-28 15:35:33'),
(4, 'candidate_view_job_details', 'Afficher les informations complètes d\'une offre d\'emploi', 1, '2026-02-28 15:35:33', '2026-02-28 15:35:33'),
(5, 'candidate_boost_important', 'Propulser sa candidature en haut de la liste (recherche importante)', 1, '2026-02-28 15:35:33', '2026-02-28 15:35:33'),
(6, 'candidate_boost_urgent', 'Propulser sa candidature en haut de la liste (recherche urgente)', 1, '2026-02-28 15:35:33', '2026-02-28 15:35:33'),
(7, 'company_view_profile_apprentice', 'Afficher les informations complètes d\'un profil de candidat (Apprenti)', 1, '2026-02-28 15:35:33', '2026-02-28 15:35:33'),
(8, 'company_view_profile_junior', 'Afficher les informations complètes d\'un profil de candidat (Junior)', 1, '2026-02-28 15:35:33', '2026-02-28 15:35:33'),
(9, 'company_view_profile_confirmed', 'Afficher les informations complètes d\'un profil de candidat (Confirmé)', 1, '2026-02-28 15:35:33', '2026-02-28 15:35:33'),
(10, 'company_view_profile_senior', 'Afficher les informations complètes d\'un profil de candidat (Senior)', 1, '2026-02-28 15:35:33', '2026-02-28 15:35:33'),
(11, 'company_boost_job_active', 'Mettre en avant une offre d\'emploi (Recherche active)', 1, '2026-02-28 15:35:33', '2026-02-28 15:35:33'),
(12, 'company_boost_job_important', 'Mettre en avant une offre d\'emploi (Recherche importante)', 1, '2026-02-28 15:35:33', '2026-02-28 15:35:33'),
(13, 'company_boost_job_urgent', 'Mettre en avant une offre d\'emploi (Recherche urgente)', 1, '2026-02-28 15:35:33', '2026-02-28 15:35:33');

-- --------------------------------------------------------

--
-- Structure de la table `activity`
--

CREATE TABLE `activity` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `fr_name` varchar(100) DEFAULT NULL,
  `en_name` varchar(100) DEFAULT NULL,
  `de_name` varchar(100) DEFAULT NULL,
  `es_name` varchar(100) DEFAULT NULL,
  `it_name` varchar(100) DEFAULT NULL,
  `nl_name` varchar(100) DEFAULT NULL,
  `pt_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `activity`
--

INSERT INTO `activity` (`id`, `name`, `description`, `fr_name`, `en_name`, `de_name`, `es_name`, `it_name`, `nl_name`, `pt_name`) VALUES
(1, 'computer', 'Développement logiciel, IT, cybersécurité, data science', 'Informatique et Technologies', 'IT and Technology', 'Informatik und Technologie', 'Informática y Tecnología', 'Informatica e Tecnologia', 'ICT en Technologie', 'Informática e Tecnologia'),
(2, 'building', 'Bâtiment, travaux publics, génie civil', 'BTP et Construction', 'Construction and Civil Engineering', 'Bau und Bauwesen', 'Construcción e Ingeniería Civil', 'Edilizia e Ingegneria Civile', 'Bouw en Civiele Techniek', 'Construção e Engenharia Civil'),
(3, 'health', 'Médecine, soins infirmiers, pharmacie, recherche médicale', 'Santé et Médical', 'Healthcare and Medical', 'Gesundheit und Medizin', 'Salud y Medicina', 'Sanità e Medicina', 'Gezondheidszorg en Medisch', 'Saúde e Medicina'),
(4, 'sales', 'Vente au détail, e-commerce, développement commercial', 'Commerce et Vente', 'Sales and Retail', 'Handel und Verkauf', 'Comercio y Ventas', 'Commercio e Vendite', 'Handel en Verkoop', 'Comércio e Vendas'),
(5, 'finance', 'Banque, assurance, comptabilité, audit', 'Finance et Banque', 'Finance and Banking', 'Finanzen und Bankwesen', 'Finanzas y Banca', 'Finanza e Banca', 'Financiën en Bankieren', 'Finanças e Banca'),
(6, 'marketing', 'Marketing digital, communication, publicité, relations publiques', 'Marketing et Communication', 'Marketing and Communications', 'Marketing und Kommunikation', 'Marketing y Comunicación', 'Marketing e Comunicazione', 'Marketing en Communicatie', 'Marketing e Comunicação'),
(7, 'hr', 'Recrutement, gestion des talents, formation, paie', 'Ressources Humaines', 'Human Resources', 'Personalwesen', 'Recursos Humanos', 'Risorse Umane', 'Human Resources', 'Recursos Humanos'),
(8, 'industry', 'Manufacturing, production, chaîne de montage, qualité', 'Industrie et Production', 'Manufacturing and Production', 'Industrie und Produktion', 'Industria y Producción', 'Industria e Produzione', 'Industrie en Productie', 'Indústria e Produção'),
(9, 'logistic', 'Supply chain, transport, entreposage, distribution', 'Logistique et Transport', 'Logistics and Transportation', 'Logistik und Transport', 'Logística y Transporte', 'Logistica e Trasporti', 'Logistiek en Transport', 'Logística e Transporte'),
(10, 'education', 'Enseignement, formation professionnelle, recherche académique', 'Éducation et Formation', 'Education and Training', 'Bildung und Ausbildung', 'Educación y Formación', 'Istruzione e Formazione', 'Onderwijs en Training', 'Educação e Formação'),
(11, 'hospitality', 'Hôtels, restaurants, tourisme, événementiel', 'Hôtellerie et Restauration', 'Hospitality and Catering', 'Gastgewerbe und Gastronomie', 'Hostelería y Restauración', 'Ospitalità e Ristorazione', 'Horeca en Catering', 'Hotelaria e Restauração'),
(12, 'legal', 'Avocats, notaires, conseil juridique, compliance', 'Juridique et Droit', 'Legal and Law', 'Recht und Justiz', 'Legal y Derecho', 'Legale e Diritto', 'Juridisch en Recht', 'Jurídico e Direito'),
(13, 'agriculture', 'Agriculture, élevage, industrie alimentaire, viticulture', 'Agriculture et Agroalimentaire', 'Agriculture and Food Industry', 'Landwirtschaft und Lebensmittel', 'Agricultura y Agroalimentaria', 'Agricoltura e Agroalimentare', 'Landbouw en Voedselindustrie', 'Agricultura e Agroalimentar'),
(14, 'energy', 'Énergies renouvelables, environnement, développement durable', 'Énergie et Environnement', 'Energy and Environment', 'Energie und Umwelt', 'Energía y Medio Ambiente', 'Energia e Ambiente', 'Energie en Milieu', 'Energia e Meio Ambiente'),
(15, 'real-estate', 'Promotion immobilière, gestion de biens, transaction', 'Immobilier', 'Real Estate', 'Immobilien', 'Inmobiliario', 'Immobiliare', 'Vastgoed', 'Imobiliário'),
(16, 'automobil', 'Construction automobile, équipements, services automobiles', 'Automobile', 'Automotive', 'Automobil', 'Automoción', 'Automobilistico', 'Automobielindustrie', 'Automóvel'),
(17, 'aerospace', 'Aviation, aérospatiale, défense', 'Aéronautique et Spatial', 'Aerospace and Aviation', 'Luft- und Raumfahrt', 'Aeronáutica y Espacial', 'Aeronautica e Spaziale', 'Lucht- en Ruimtevaart', 'Aeronáutica e Espacial'),
(18, 'arts', 'Arts, musées, spectacles, patrimoine culturel', 'Arts et Culture', 'Arts and Culture', 'Kunst und Kultur', 'Arte y Cultura', 'Arte e Cultura', 'Kunst en Cultuur', 'Arte e Cultura'),
(19, 'media', 'Journalisme, édition, audiovisuel, presse écrite', 'Médias et Presse', 'Media and Press', 'Medien und Presse', 'Medios y Prensa', 'Media e Stampa', 'Media en Pers', 'Média e Imprensa'),
(20, 'design', 'Design graphique, UX/UI, design produit, architecture intérieure', 'Design et Créativité', 'Design and Creativity', 'Design und Kreativität', 'Diseño y Creatividad', 'Design e Creatività', 'Design en Creativiteit', 'Design e Criatividade'),
(21, 'consulting', 'Conseil en management, stratégie, organisation', 'Conseil et Stratégie', 'Consulting and Strategy', 'Beratung und Strategie', 'Consultoría y Estrategia', 'Consulenza e Strategia', 'Consultancy en Strategie', 'Consultoria e Estratégia'),
(22, 'services', 'Nettoyage, sécurité, maintenance, services généraux', 'Services aux Entreprises', 'Business Services', 'Unternehmensdienstleistungen', 'Servicios a Empresas', 'Servizi alle Imprese', 'Zakelijke Diensten', 'Serviços às Empresas'),
(23, 'social', 'Aide à domicile, services sociaux, petite enfance', 'Social et Services à la Personne', 'Social and Personal Services', 'Soziales und Personendienste', 'Social y Servicios a la Persona', 'Sociale e Servizi alla Persona', 'Sociaal en Persoonlijke Diensten', 'Social e Serviços à Pessoa'),
(24, 'sport', 'Sport professionnel, fitness, loisirs, animation', 'Sport et Loisirs', 'Sports and Recreation', 'Sport und Freizeit', 'Deporte y Ocio', 'Sport e Tempo Libero', 'Sport en Recreatie', 'Desporto e Lazer'),
(25, 'luxury', 'Mode, textile, maroquinerie, joaillerie, cosmétique', 'Mode et Luxe', 'Fashion and Luxury', 'Mode und Luxus', 'Moda y Lujo', 'Moda e Lusso', 'Mode en Luxe', 'Moda e Luxo'),
(26, 'chemistry', 'Chimie industrielle, pharmacie, biotechnologie', 'Chimie et Pharmacie', 'Chemistry and Pharmaceuticals', 'Chemie und Pharmazie', 'Química y Farmacia', 'Chimica e Farmacia', 'Chemie en Farmacie', 'Química e Farmácia'),
(27, 'telecom', 'Opérateurs télécom, réseaux, infrastructures numériques', 'Télécommunications', 'Telecommunications', 'Telekommunikation', 'Telecomunicaciones', 'Telecomunicazioni', 'Telecommunicatie', 'Telecomunicações'),
(28, 'public', 'Fonction publique, collectivités, services de l\'État', 'Administration Publique', 'Public Administration', 'Öffentliche Verwaltung', 'Administración Pública', 'Amministrazione Pubblica', 'Overheidsadministratie', 'Administração Pública'),
(29, 'r&d', 'R&D, innovation, laboratoires, centres de recherche', 'Recherche et Développement', 'Research and Development', 'Forschung und Entwicklung', 'Investigación y Desarrollo', 'Ricerca e Sviluppo', 'Onderzoek en Ontwikkeling', 'Pesquisa e Desenvolvimento'),
(30, 'defense', 'Sécurité privée, défense, pompiers, police', 'Sécurité et Défense', 'Security and Defense', 'Sicherheit und Verteidigung', 'Seguridad y Defensa', 'Sicurezza e Difesa', 'Veiligheid en Defensie', 'Segurança e Defesa');

-- --------------------------------------------------------

--
-- Structure de la table `aiecourt`
--

CREATE TABLE `aiecourt` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) DEFAULT NULL COMMENT 'ID du secteur d''activité (peut être NULL pour prix par défaut)',
  `country_id` int(11) DEFAULT NULL COMMENT 'ID du pays (peut être NULL pour prix par défaut)',
  `action_id` int(11) NOT NULL COMMENT 'ID de l''action',
  `price` int(11) NOT NULL COMMENT 'Prix en AIECoins',
  `role` enum('candidate','company') NOT NULL COMMENT 'Rôle concerné',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `aiecourt`
--

INSERT INTO `aiecourt` (`id`, `activity_id`, `country_id`, `action_id`, `price`, `role`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 1, 5, 'candidate', '2026-02-28 15:35:40', '2026-02-28 15:35:40'),
(2, NULL, NULL, 2, 10, 'candidate', '2026-02-28 15:35:40', '2026-02-28 15:35:40'),
(3, NULL, NULL, 3, 20, 'candidate', '2026-02-28 15:35:40', '2026-02-28 15:35:40'),
(4, NULL, NULL, 4, 30, 'candidate', '2026-02-28 15:35:40', '2026-02-28 15:35:40'),
(5, NULL, NULL, 5, 40, 'candidate', '2026-02-28 15:35:40', '2026-02-28 15:35:40'),
(6, NULL, NULL, 6, 60, 'candidate', '2026-02-28 15:35:40', '2026-02-28 15:35:40'),
(7, NULL, NULL, 7, 5, 'company', '2026-02-28 15:35:40', '2026-02-28 15:37:15'),
(8, NULL, NULL, 8, 10, 'company', '2026-02-28 15:35:40', '2026-02-28 15:37:19'),
(9, NULL, NULL, 9, 20, 'company', '2026-02-28 15:35:40', '2026-02-28 15:37:22'),
(10, NULL, NULL, 10, 40, 'company', '2026-02-28 15:35:40', '2026-02-28 15:37:25'),
(11, NULL, NULL, 11, 20, 'company', '2026-02-28 15:35:40', '2026-02-28 15:37:27'),
(12, NULL, NULL, 12, 40, 'company', '2026-02-28 15:35:40', '2026-02-28 15:37:30'),
(13, NULL, NULL, 13, 60, 'company', '2026-02-28 15:35:40', '2026-02-28 15:37:33');

-- --------------------------------------------------------

--
-- Structure de la table `application`
--

CREATE TABLE `application` (
  `id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `recruiter_validation` tinyint(1) DEFAULT 0,
  `candidate_validation` tinyint(1) DEFAULT 0,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `application`
--

INSERT INTO `application` (`id`, `candidate_id`, `job_id`, `status`, `recruiter_validation`, `candidate_validation`, `comment`, `created_at`, `updated_at`) VALUES
(18, 71, 45, 'pending', 0, 1, NULL, '2026-03-07 19:28:30', '2026-03-07 19:28:30');

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
  `avatar_url` varchar(500) DEFAULT NULL COMMENT 'URL de l''avatar du candidat',
  `approved` tinyint(1) DEFAULT NULL COMMENT 'NULL=En attente, 1=Approuvé, 0=Refusé',
  `priority` int(11) NOT NULL DEFAULT 0 COMMENT 'Niveau de priorité du candidat (0 = aucune priorité)',
  `seniority` int(11) DEFAULT NULL COMMENT 'Ancienneté du candidat en années',
  `date_availability` date DEFAULT NULL COMMENT 'Date de disponibilité du candidat (YYYY-MM-DD)',
  `banned` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indique si le candidat est banni (0 = non, 1 = oui)',
  `banlength` int(11) DEFAULT NULL COMMENT 'Durée du bannissement en jours (-1 = permanent, NULL = non banni)',
  `ban_end_date` date DEFAULT NULL COMMENT 'Date de fin du bannissement (9999-12-31 = permanent)',
  `ban_reason` text DEFAULT NULL COMMENT 'Raison du bannissement',
  `start_date_priority` date DEFAULT NULL COMMENT 'Date de début de la période de priorité (YYYY-MM-DD)',
  `end_date_priority` date DEFAULT NULL COMMENT 'Date de fin de la période de priorité (YYYY-MM-DD)',
  `length_priority` int(11) NOT NULL DEFAULT 0 COMMENT 'Durée de la priorité en jours',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `candidate`
--

INSERT INTO `candidate` (`id`, `identity_id`, `country_id`, `activity_id`, `skill_ids`, `personality_ids`, `social_id`, `demand_id`, `experience_ids`, `formation_ids`, `mission_ids`, `media_code`, `avatar_url`, `approved`, `priority`, `seniority`, `date_availability`, `banned`, `banlength`, `ban_end_date`, `ban_reason`, `start_date_priority`, `end_date_priority`, `length_priority`, `created_at`, `updated_at`) VALUES
(71, 74, 1, 1, '[\"544\",\"545\",\"546\",\"547\",\"548\",\"549\",\"550\",\"551\"]', '[93,94,95,96]', NULL, 71, '[185,186,187,182]', '[105,99,102,103,104]', NULL, 'vm3274GMB16W', '/media/image/icon/avatar/men/1.png', 1, 4, 2, '2026-03-01', 0, NULL, NULL, NULL, NULL, NULL, 0, '2026-03-05 20:44:26', '2026-03-07 12:30:11'),
(72, 75, 1, 1, '[\"460\",\"461\",\"462\",\"463\"]', '[\"97\",\"98\"]', NULL, 72, '[\"183\",\"184\"]', '[\"100\",\"101\"]', NULL, '3zOOX1s7277k', '/media/image/icon/avatar/men/43.png', 1, 4, 2, '2026-03-01', 0, NULL, NULL, NULL, NULL, NULL, 0, '2026-03-05 23:19:59', '2026-03-07 01:09:56');

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
  `website` varchar(255) DEFAULT NULL COMMENT 'Site web de l''entreprise',
  `media_code` varchar(12) DEFAULT NULL COMMENT 'Code unique pour identifier les médias de l''entreprise (12 caractères)',
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

INSERT INTO `company` (`id`, `name`, `customer_number`, `nation_register_number`, `description`, `domain`, `capacity`, `hq_location`, `country`, `recruiting`, `website`, `media_code`, `contact_id`, `banned`, `banlength`, `ban_end_date`, `ban_reason`, `date_creation`, `created_at`, `updated_at`, `country_id`) VALUES
(32, 'Aienova', NULL, '91092579100014', 'Aienova Nothing to Something', 'Informatique/Numérique', 0, 'Mulhouse', NULL, 1, '', '177282738802', 30, 0, NULL, NULL, NULL, NULL, '2026-03-06 20:03:07', '2026-03-06 20:11:04', NULL),
(36, 'EA Sport', NULL, '34513048800017', 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle', 'Informatique/Numérique', 50, 'Paris', NULL, 1, NULL, '177284089730', 34, 0, NULL, NULL, NULL, NULL, '2026-03-06 23:48:17', '2026-03-06 23:48:46', NULL);

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
(29, 'Eudes', 'Konda', 'eudes.konda@aienova.com', '+33 7 53 47 59 13', '2026-03-06 20:03:07', '2026-03-06 20:03:07'),
(30, 'Eudes', 'Konda', 'eudes.konda@aienova.com', '0699073011', '2026-03-06 20:11:04', '2026-03-06 20:45:11'),
(31, 'Marie', 'Dupont', 'leroyodon1st@gmail.com', '+33 1 23 45 67 89', '2026-03-06 23:01:31', '2026-03-06 23:01:31'),
(32, 'Marie', 'Dupont', 'e2-venceslas@hotmail.fr', '+33 1 23 45 67 89', '2026-03-06 23:04:59', '2026-03-06 23:04:59'),
(33, 'Marie', 'Dupont', 'test.testeur@ea.com', '+33 1 23 45 67 89', '2026-03-06 23:17:13', '2026-03-06 23:17:13'),
(34, 'Marie', 'Dupont', 'eudes.konda@aienova.com', '+33 1 23 45 67 89', '2026-03-06 23:48:17', '2026-03-07 11:44:05');

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
(71, 'Freelance', 500.00, NULL, '2026-03-05 20:44:24', '2026-03-05 20:44:24'),
(72, 'Freelance', 500.00, NULL, '2026-03-05 23:19:58', '2026-03-05 23:19:58');

-- --------------------------------------------------------

--
-- Structure de la table `exchange`
--

CREATE TABLE `exchange` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) DEFAULT NULL COMMENT 'ID du secteur d''activité (peut être NULL pour taux par défaut)',
  `country_id` int(11) DEFAULT NULL COMMENT 'ID du pays (peut être NULL pour taux par défaut)',
  `currency_code` varchar(3) NOT NULL COMMENT 'Code de la devise (EUR, USD, GBP, etc.)',
  `rate` int(11) NOT NULL COMMENT 'Nombre d''AIECoins pour 1 unité de cette devise (toujours entier)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `exchange`
--

INSERT INTO `exchange` (`id`, `activity_id`, `country_id`, `currency_code`, `rate`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 'EUR', 25, '2026-02-28 18:29:54', '2026-02-28 18:30:12'),
(2, NULL, NULL, 'USD', 23, '2026-02-28 18:29:54', '2026-02-28 18:29:54'),
(3, NULL, NULL, 'GBP', 30, '2026-02-28 18:29:54', '2026-02-28 18:29:54'),
(4, NULL, NULL, 'JPY', 1, '2026-02-28 18:29:54', '2026-02-28 18:29:54'),
(5, NULL, NULL, 'AUD', 15, '2026-02-28 18:29:54', '2026-02-28 18:29:54'),
(6, NULL, NULL, 'CAD', 17, '2026-02-28 18:29:54', '2026-02-28 18:29:54'),
(7, NULL, NULL, 'CHF', 27, '2026-02-28 18:29:54', '2026-02-28 18:29:54'),
(8, NULL, NULL, 'CNY', 3, '2026-02-28 18:29:54', '2026-02-28 18:29:54'),
(9, NULL, NULL, 'SEK', 2, '2026-02-28 18:29:54', '2026-02-28 18:29:54'),
(10, NULL, NULL, 'NZD', 14, '2026-02-28 18:29:54', '2026-02-28 18:29:54');

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
(182, '2017-11-01', '2018-08-12', 0, 'Webmaster', 'APPIMAN', 'Rungis', '[342,343,344]', '2026-03-05 20:44:25', '2026-03-06 23:37:57'),
(183, '2017-09-20', NULL, 1, 'Freelance dans le domaine du développement web', 'Auto-entreprise', 'Paris', '[\"347\"]', '2026-03-05 23:19:59', '2026-03-06 23:40:42'),
(184, '2013-03-01', '2013-04-15', 0, 'Stage de fin d\'année de BTS', 'Ecoles ParisTech', 'Fontainebleau', '[\"346\"]', '2026-03-05 23:19:59', '2026-03-06 23:40:37'),
(185, '2024-09-16', '2025-12-12', 0, 'Formateur développement web', 'Centre de réadaptation de Mulhouse', 'Mulhouse', '[331,332,333,334]', '2026-03-05 23:33:55', '2026-03-06 23:37:56'),
(186, '2022-01-01', NULL, 1, 'Développeur Web Fullstack', 'Freelance', 'France,Allemagne', '[335,336,337]', '2026-03-05 23:33:55', '2026-03-06 23:37:56'),
(187, '2019-01-01', '2022-02-28', 0, 'Développeur Web Full Stack', 'MBA EKO Group', 'Noisy-le-grand', '[338,339,340,341]', '2026-03-05 23:33:55', '2026-03-06 23:37:56');

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
(99, '2018-08-01', '2019-07-02', 0, 'Webmarketing & Management', 'ISEAM', 'Lognes', '[263,265,267,269,271,272,273]', '2026-03-05 20:44:25', '2026-03-06 18:14:17'),
(100, '2012-09-04', '2014-07-04', 0, 'BTS IRIS', 'Lycée LaFayette', 'Champagne sur Seine', NULL, '2026-03-05 23:19:59', '2026-03-05 23:19:59'),
(101, '2009-09-04', '2012-06-04', 0, 'Baccalauréat STI', 'Lycée LaFayette', 'Champagne sur seine', NULL, '2026-03-05 23:19:59', '2026-03-05 23:19:59'),
(102, '2017-10-01', '2018-01-01', 0, 'Master 1 : Administrateur de Système d\'information', 'IPSSI', 'Paris', '[274,275,276,277,278,279,280]', '2026-03-05 23:39:40', '2026-03-06 18:14:17'),
(103, '2014-01-01', '2027-01-01', 0, 'Bachelor : Webdesign & Création Digital', 'EEMI', 'Paris', '[281,282,283,284,285,286,287,288,289,290]', '2026-03-05 23:40:27', '2026-03-06 18:14:17'),
(104, '2010-01-01', '2014-01-01', 0, 'Licence informatique', 'Université d\'Evry', 'Evry , Essonnes', '[291,292,293,294,295,296,297,298,299]', '2026-03-05 23:42:46', '2026-03-06 18:14:18'),
(105, '2025-02-01', '2025-03-01', 0, 'Certification Safe6 PO/PM', 'Kasa Academy', 'à distance', '[264,266,268,270]', '2026-03-06 18:14:11', '2026-03-06 23:37:57');

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
(88, 'Sci-Fi', '2026-03-05 20:44:26', '2026-03-05 20:44:26'),
(89, 'Football', '2026-03-05 20:44:26', '2026-03-05 20:44:26'),
(90, 'Histoire', '2026-03-05 20:44:26', '2026-03-05 20:44:26'),
(91, 'Jeux Videos', '2026-03-05 20:44:26', '2026-03-05 20:44:26'),
(92, 'Technologie en général', '2026-03-05 23:19:59', '2026-03-05 23:19:59'),
(93, 'Hardware', '2026-03-05 23:20:00', '2026-03-05 23:20:00');

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
(74, 'Konda', 'Eudes', '1992-06-09', 4, 'Développeur Web Fullstack', '+33 7 53 47 59 13', 'Mulhouse', 'eudes.konda@aienova.com', 0, 'Webdesigner et développeur web, je combine des compétences techniques et stratégiques dans la conception de solutions digitales. Avec une approche orientée Product Owner, je pilote la création de sites et d’applications web complexes en mettant l’accent sur l’expérience utilisateur et la valeur produit. J’ai notamment conçu et développé la plateforme 1-ted.com, un projet personnel qui illustre mes compétences en développement, design et gestion de produit.', '[96,97,98]', '[88,89,90,91]', '2026-03-05 20:44:24', '2026-03-07 12:39:33'),
(75, 'Da Silva', 'Jessy', '1993-01-14', 2, 'Développeur spécialisé backend', '+33669993592', 'Paris', 'dasilva.jessy7@gmail.com', 0, 'Developpeur Back-end passionné d\'informatique et du\ndigital basé sur Paris, j\'aide mes clients à assurer le bon\nfonctionnement de leur site web, la sécurité et la gestion de\nbases de données, sur des nouveaux projets ou projets déjà\nexistant.', '[\"99\",\"100\"]', '[\"92\",\"93\"]', '2026-03-05 23:19:58', '2026-03-05 23:20:00');

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
  `priority` int(11) NOT NULL DEFAULT 0 COMMENT 'Niveau de priorité de l''offre (0 = aucune priorité)',
  `start_date_priority` date DEFAULT NULL COMMENT 'Date de début de la période de priorité (YYYY-MM-DD)',
  `end_date_priority` date DEFAULT NULL COMMENT 'Date de fin de la période de priorité (YYYY-MM-DD)',
  `length_priority` int(11) NOT NULL DEFAULT 0 COMMENT 'Durée de la priorité en jours',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `job`
--

INSERT INTO `job` (`id`, `title`, `description`, `location`, `activity_id`, `country_id`, `salary`, `contract_type`, `start_date`, `length`, `company_id`, `work_condition`, `need_vehicle`, `contact_id`, `requirement_id`, `open`, `approved`, `priority`, `start_date_priority`, `end_date_priority`, `length_priority`, `created_at`, `updated_at`) VALUES
(41, 'Développeur Full Stack Senior', 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants utilisant les dernières technologies web.\\n\\nResponsabilités principales :\\n- Développer et maintenir des applications web complexes\\n- Collaborer avec l\'équipe produit et design\\n- Participer aux revues de code\\n- Mentorer les développeurs juniors', 'Paris, France', NULL, NULL, '50000 - 65000 € par an', 'CDI', '2024-03-01', 'Temps plein', 32, 'Conditions de travail :\\n- Télétravail partiel (2-3 jours par semaine)\\n- Horaires flexibles\\n- Environnement de travail moderne\\n- Équipe jeune et dynamique\\n- Opportunités de formation continue\\n- Avantages sociaux : tickets restaurant, mutuelle, RTT', 0, 30, 35, 1, 0, 0, NULL, NULL, 0, '2026-03-06 23:45:22', '2026-03-06 23:45:22'),
(42, 'Webdesigner', 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants utilisant les dernières technologies web.\\n\\nResponsabilités principales :\\n- Développer et maintenir des applications web complexes\\n- Collaborer avec l\'équipe produit et design\\n- Participer aux revues de code\\n- Mentorer les développeurs juniors', 'Paris, France', NULL, NULL, '50000 - 65000 € par an', 'CDI', '2024-03-01', 'Temps plein', 32, 'Conditions de travail :\\n- Télétravail partiel (2-3 jours par semaine)\\n- Horaires flexibles\\n- Environnement de travail moderne\\n- Équipe jeune et dynamique\\n- Opportunités de formation continue\\n- Avantages sociaux : tickets restaurant, mutuelle, RTT', 0, 30, 36, 1, 0, 0, NULL, NULL, 0, '2026-03-06 23:47:07', '2026-03-06 23:47:07'),
(43, 'Développeur Full Stack Senior', 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants utilisant les dernières technologies web.\n\nResponsabilités principales :\n- Développer et maintenir des applications web complexes\n- Collaborer avec l\'équipe produit et design\n- Participer aux revues de code\n- Mentorer les développeurs juniors', 'Paris, France', 1, NULL, '50000 - 65000 € par an', 'CDI', '2024-03-01', 'Temps plein', 36, 'Conditions de travail :\n- Télétravail partiel (2-3 jours par semaine)\n- Horaires flexibles\n- Environnement de travail moderne\n- Équipe jeune et dynamique\n- Opportunités de formation continue\n- Avantages sociaux : tickets restaurant, mutuelle, RTT', 0, 34, 37, 1, 0, 0, NULL, NULL, 0, '2026-03-06 23:48:17', '2026-03-07 11:53:19'),
(45, 'Développeur Full Stack Senior', 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants utilisant les dernières technologies web.\\n\\nResponsabilités principales :\\n- Développer et maintenir des applications web complexes\\n- Collaborer avec l\'équipe produit et design\\n- Participer aux revues de code\\n- Mentorer les développeurs juniors', 'Paris, France', 1, NULL, '50000 - 65000 € par an', 'CDI', '2024-03-01', 'Temps plein', 36, 'Conditions de travail :\\n- Télétravail partiel (2-3 jours par semaine)\\n- Horaires flexibles\\n- Environnement de travail moderne\\n- Équipe jeune et dynamique\\n- Opportunités de formation continue\\n- Avantages sociaux : tickets restaurant, mutuelle, RTT', 0, 34, 39, 1, 1, 0, NULL, NULL, 0, '2026-03-06 23:58:56', '2026-03-07 11:53:28');

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
(222, 'Product Owner', '2026-03-06 18:14:06', '2026-03-06 18:14:06'),
(223, 'Product Manager', '2026-03-06 18:14:06', '2026-03-06 18:14:06'),
(224, 'SCRUM', '2026-03-06 18:14:06', '2026-03-06 18:14:06'),
(225, 'Kanban', '2026-03-06 18:14:06', '2026-03-06 18:14:06'),
(226, 'Data analyse', '2026-03-06 18:14:06', '2026-03-06 18:14:06'),
(227, 'Gestion de crise', '2026-03-06 18:14:07', '2026-03-06 18:14:07'),
(228, 'Gestion de projet', '2026-03-06 18:14:07', '2026-03-06 18:14:07'),
(229, 'E-business', '2026-03-06 18:14:07', '2026-03-06 18:14:07'),
(230, 'Marketing de Réseau', '2026-03-06 18:14:07', '2026-03-06 18:14:07'),
(231, 'Intégration Web', '2026-03-06 18:14:07', '2026-03-06 18:14:07'),
(232, 'Management', '2026-03-06 18:14:07', '2026-03-06 18:14:07'),
(233, 'Programmation Javascript/Angular/React', '2026-03-06 18:14:07', '2026-03-06 18:14:07'),
(234, 'Wordpress', '2026-03-06 18:14:07', '2026-03-06 18:14:07'),
(235, 'Création graphique', '2026-03-06 18:14:07', '2026-03-06 18:14:07'),
(236, 'SEO', '2026-03-06 18:14:07', '2026-03-06 18:14:07'),
(237, 'Marketing', '2026-03-06 18:14:07', '2026-03-06 18:14:07'),
(238, 'Développement Web PHP', '2026-03-06 18:14:07', '2026-03-06 18:14:07'),
(239, 'UX/UI Design', '2026-03-06 18:14:07', '2026-03-06 18:14:07'),
(240, 'UX/UI Design', '2026-03-06 18:14:08', '2026-03-06 18:14:08'),
(241, 'Intégration Web HTML5/CSS3', '2026-03-06 18:14:08', '2026-03-06 18:14:08'),
(242, 'Développement Web PHP', '2026-03-06 18:14:08', '2026-03-06 18:14:08'),
(243, 'Développement Web JS Vue.js', '2026-03-06 18:14:08', '2026-03-06 18:14:08'),
(244, 'Bureautique', '2026-03-06 18:14:08', '2026-03-06 18:14:08'),
(245, 'Gestion d\'entreprise', '2026-03-06 18:14:08', '2026-03-06 18:14:08'),
(246, 'Comptabilité', '2026-03-06 18:14:08', '2026-03-06 18:14:08'),
(247, 'Webmarketing', '2026-03-06 18:14:08', '2026-03-06 18:14:08'),
(248, 'Elevator Pitch', '2026-03-06 18:14:08', '2026-03-06 18:14:08'),
(249, 'Gestion de projet', '2026-03-06 18:14:08', '2026-03-06 18:14:08'),
(250, 'POO Java', '2026-03-06 18:14:08', '2026-03-06 18:14:08'),
(251, 'C++', '2026-03-06 18:14:09', '2026-03-06 18:14:09'),
(252, 'Algèbre booléenne', '2026-03-06 18:14:09', '2026-03-06 18:14:09'),
(253, 'Réseau', '2026-03-06 18:14:09', '2026-03-06 18:14:09'),
(254, 'Base de données', '2026-03-06 18:14:09', '2026-03-06 18:14:09'),
(255, 'Macro-économie', '2026-03-06 18:14:09', '2026-03-06 18:14:09'),
(256, 'Micro-économie', '2026-03-06 18:14:09', '2026-03-06 18:14:09'),
(257, 'Python', '2026-03-06 18:14:09', '2026-03-06 18:14:09'),
(258, 'Anglais', '2026-03-06 18:14:09', '2026-03-06 18:14:09'),
(259, 'Product Owner', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(260, 'Product Manager', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(261, 'SCRUM', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(262, 'Kanban', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(263, 'Data analyse', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(264, 'Product Owner', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(265, 'Gestion de crise', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(266, 'Product Manager', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(267, 'Gestion de projet', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(268, 'SCRUM', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(269, 'E-business', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(270, 'Kanban', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(271, 'Marketing de Réseau', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(272, 'Intégration Web', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(273, 'Management', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(274, 'Programmation Javascript/Angular/React', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(275, 'Wordpress', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(276, 'Création graphique', '2026-03-06 18:14:11', '2026-03-06 18:14:11'),
(277, 'SEO', '2026-03-06 18:14:12', '2026-03-06 18:14:12'),
(278, 'Marketing', '2026-03-06 18:14:12', '2026-03-06 18:14:12'),
(279, 'Développement Web PHP', '2026-03-06 18:14:12', '2026-03-06 18:14:12'),
(280, 'UX/UI Design', '2026-03-06 18:14:12', '2026-03-06 18:14:12'),
(281, 'UX/UI Design', '2026-03-06 18:14:12', '2026-03-06 18:14:12'),
(282, 'Intégration Web HTML5/CSS3', '2026-03-06 18:14:12', '2026-03-06 18:14:12'),
(283, 'Développement Web PHP', '2026-03-06 18:14:12', '2026-03-06 18:14:12'),
(284, 'Développement Web JS Vue.js', '2026-03-06 18:14:13', '2026-03-06 18:14:13'),
(285, 'Bureautique', '2026-03-06 18:14:15', '2026-03-06 18:14:15'),
(286, 'Gestion d\'entreprise', '2026-03-06 18:14:15', '2026-03-06 18:14:15'),
(287, 'Comptabilité', '2026-03-06 18:14:15', '2026-03-06 18:14:15'),
(288, 'Webmarketing', '2026-03-06 18:14:15', '2026-03-06 18:14:15'),
(289, 'Elevator Pitch', '2026-03-06 18:14:16', '2026-03-06 18:14:16'),
(290, 'Gestion de projet', '2026-03-06 18:14:16', '2026-03-06 18:14:16'),
(291, 'POO Java', '2026-03-06 18:14:16', '2026-03-06 18:14:16'),
(292, 'C++', '2026-03-06 18:14:16', '2026-03-06 18:14:16'),
(293, 'Algèbre booléenne', '2026-03-06 18:14:16', '2026-03-06 18:14:16'),
(294, 'Réseau', '2026-03-06 18:14:16', '2026-03-06 18:14:16'),
(295, 'Base de données', '2026-03-06 18:14:16', '2026-03-06 18:14:16'),
(296, 'Macro-économie', '2026-03-06 18:14:16', '2026-03-06 18:14:16'),
(297, 'Micro-économie', '2026-03-06 18:14:16', '2026-03-06 18:14:16'),
(298, 'Python', '2026-03-06 18:14:16', '2026-03-06 18:14:16'),
(299, 'Anglais', '2026-03-06 18:14:16', '2026-03-06 18:14:16');

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
(96, 'Français', NULL, 0, '2026-03-05 20:44:26', '2026-03-05 20:44:26'),
(97, 'Anglais (C1)', NULL, 0, '2026-03-05 20:44:26', '2026-03-05 20:44:26'),
(98, 'Allemand (A1)', NULL, 0, '2026-03-05 20:44:26', '2026-03-05 20:44:26'),
(99, 'Français', NULL, 0, '2026-03-05 23:19:59', '2026-03-05 23:19:59'),
(100, 'English', NULL, 0, '2026-03-05 23:19:59', '2026-03-05 23:19:59');

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
(317, 'Formation en algorithmie', '2026-03-06 23:37:52', '2026-03-06 23:37:52'),
(318, 'Formation en Programmation Orienté Objet', '2026-03-06 23:37:52', '2026-03-06 23:37:52'),
(319, 'Accompagnement en insertion emploi', '2026-03-06 23:37:52', '2026-03-06 23:37:52'),
(320, 'Formation en intégration HTML/CSS', '2026-03-06 23:37:52', '2026-03-06 23:37:52'),
(321, 'Site web Wordpress', '2026-03-06 23:37:52', '2026-03-06 23:37:52'),
(322, 'Site web PHP', '2026-03-06 23:37:52', '2026-03-06 23:37:52'),
(323, 'Application Web React JS', '2026-03-06 23:37:52', '2026-03-06 23:37:52'),
(324, 'Développement d\'outil métier en interne', '2026-03-06 23:37:52', '2026-03-06 23:37:52'),
(325, 'Gestion des sites webs, Gestion de projet', '2026-03-06 23:37:52', '2026-03-06 23:37:52'),
(326, 'Formation des alternants sur le développement Web', '2026-03-06 23:37:52', '2026-03-06 23:37:52'),
(327, 'Analyse du traffic', '2026-03-06 23:37:52', '2026-03-06 23:37:52'),
(328, 'Création de site Web Wordpress', '2026-03-06 23:37:52', '2026-03-06 23:37:52'),
(329, 'Emailing', '2026-03-06 23:37:52', '2026-03-06 23:37:52'),
(330, 'Développement d\'application interne', '2026-03-06 23:37:52', '2026-03-06 23:37:52'),
(331, 'Formation en algorithmie', '2026-03-06 23:37:55', '2026-03-06 23:37:55'),
(332, 'Formation en Programmation Orienté Objet', '2026-03-06 23:37:55', '2026-03-06 23:37:55'),
(333, 'Accompagnement en insertion emploi', '2026-03-06 23:37:55', '2026-03-06 23:37:55'),
(334, 'Formation en intégration HTML/CSS', '2026-03-06 23:37:55', '2026-03-06 23:37:55'),
(335, 'Site web Wordpress', '2026-03-06 23:37:56', '2026-03-06 23:37:56'),
(336, 'Site web PHP', '2026-03-06 23:37:56', '2026-03-06 23:37:56'),
(337, 'Application Web React JS', '2026-03-06 23:37:56', '2026-03-06 23:37:56'),
(338, 'Développement d\'outil métier en interne', '2026-03-06 23:37:56', '2026-03-06 23:37:56'),
(339, 'Gestion des sites webs, Gestion de projet', '2026-03-06 23:37:56', '2026-03-06 23:37:56'),
(340, 'Formation des alternants sur le développement Web', '2026-03-06 23:37:56', '2026-03-06 23:37:56'),
(341, 'Analyse du traffic', '2026-03-06 23:37:56', '2026-03-06 23:37:56'),
(342, 'Création de site Web Wordpress', '2026-03-06 23:37:56', '2026-03-06 23:37:56'),
(343, 'Emailing', '2026-03-06 23:37:56', '2026-03-06 23:37:56'),
(344, 'Développement d\'application interne', '2026-03-06 23:37:56', '2026-03-06 23:37:56'),
(345, 'Spécialisé backend PHP/Symfony 4+', '2026-03-06 23:38:37', '2026-03-06 23:38:37'),
(346, 'Développement d\'un système de base de données avec log interne', '2026-03-06 23:39:11', '2026-03-06 23:39:11');

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
(93, 'Empathique', '2026-03-05 20:44:25', '2026-03-05 20:44:25'),
(94, 'Leader', '2026-03-05 20:44:25', '2026-03-05 20:44:25'),
(95, 'Optimiste', '2026-03-05 20:44:25', '2026-03-05 20:44:25'),
(96, 'Créatif', '2026-03-05 20:44:26', '2026-03-05 20:44:26'),
(97, 'Méthodique', '2026-03-05 23:19:59', '2026-03-05 23:19:59'),
(98, 'Passioné', '2026-03-05 23:19:59', '2026-03-05 23:19:59');

-- --------------------------------------------------------

--
-- Structure de la table `preference`
--

CREATE TABLE `preference` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `keywords` text DEFAULT NULL,
  `maximum_age` int(11) DEFAULT NULL,
  `minimum_experience` int(11) DEFAULT NULL,
  `minimum_salary` decimal(10,2) DEFAULT NULL,
  `maximum_salary` decimal(10,2) DEFAULT NULL,
  `locations` text DEFAULT NULL,
  `suggestion_activated` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `preference`
--

INSERT INTO `preference` (`id`, `account_id`, `keywords`, `maximum_age`, `minimum_experience`, `minimum_salary`, `maximum_salary`, `locations`, `suggestion_activated`, `created_at`, `updated_at`) VALUES
(1, 2, 'développeur, web, javascript, react', 40, 2, 35000.00, 55000.00, 'Paris, Lyon, Nantes', 1, '2026-02-15 17:40:19', '2026-02-15 17:40:19'),
(2, 3, 'commerce, vente, relation client', 45, 3, 30000.00, 45000.00, 'France', 1, '2026-02-15 17:40:19', '2026-02-15 17:40:19'),
(3, 15, 'design, ux, ui, figma', 35, 1, 32000.00, 48000.00, 'Paris, Bordeaux', 1, '2026-02-15 17:40:19', '2026-02-15 17:40:19'),
(4, 39, 'développeur, senior, php, symfony', 50, 5, 40000.00, 65000.00, 'Paris, Toulouse', 1, '2026-02-15 17:40:19', '2026-02-15 17:40:19'),
(5, 40, 'ingénieur, btp, construction', 45, 3, 38000.00, 58000.00, 'Lyon, Marseille', 1, '2026-02-15 17:40:19', '2026-02-15 17:40:19');

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
(29, 'Compétences techniques requises :\n- JavaScript, TypeScript, Node.js\n- React, Vue.js ou Angular\n- SQL et NoSQL\n- Git, Docker\n- API REST et GraphQL\n\nCompétences souhaitées :\n- Expérience avec des méthodologies Agile\n- Bonnes capacités de communication\n- Autonomie et esprit d\'équipe\n- Anglais professionnel', 'Bac +5 (Master, diplôme d\'ingénieur)', 5, '2026-03-06 20:03:07', '2026-03-06 20:03:07'),
(31, 'Compétences techniques requises :\\n- JavaScript, TypeScript, Node.js\\n- React, Vue.js ou Angular\\n- SQL et NoSQL\\n- Git, Docker\\n- API REST et GraphQL\\n\\nCompétences souhaitées :\\n- Expérience avec des méthodologies Agile\\n- Bonnes capacités de communication\\n- Autonomie et esprit d\'équipe\\n- Anglais professionnel', 'Bac +5 (Master, diplôme d\'ingénieur)', 5, '2026-03-06 21:03:08', '2026-03-06 21:03:08'),
(32, 'Compétences techniques requises :\n- JavaScript, TypeScript, Node.js\n- React, Vue.js ou Angular\n- SQL et NoSQL\n- Git, Docker\n- API REST et GraphQL\n\nCompétences souhaitées :\n- Expérience avec des méthodologies Agile\n- Bonnes capacités de communication\n- Autonomie et esprit d\'équipe\n- Anglais professionnel', 'Bac +5 (Master, diplôme d\'ingénieur)', 5, '2026-03-06 23:01:31', '2026-03-06 23:01:31'),
(33, 'Compétences techniques requises :\n- JavaScript, TypeScript, Node.js\n- React, Vue.js ou Angular\n- SQL et NoSQL\n- Git, Docker\n- API REST et GraphQL\n\nCompétences souhaitées :\n- Expérience avec des méthodologies Agile\n- Bonnes capacités de communication\n- Autonomie et esprit d\'équipe\n- Anglais professionnel', 'Bac +5 (Master, diplôme d\'ingénieur)', 5, '2026-03-06 23:04:59', '2026-03-06 23:04:59'),
(34, 'Compétences techniques requises :\n- JavaScript, TypeScript, Node.js\n- React, Vue.js ou Angular\n- SQL et NoSQL\n- Git, Docker\n- API REST et GraphQL\n\nCompétences souhaitées :\n- Expérience avec des méthodologies Agile\n- Bonnes capacités de communication\n- Autonomie et esprit d\'équipe\n- Anglais professionnel', 'Bac +5 (Master, diplôme d\'ingénieur)', 5, '2026-03-06 23:17:13', '2026-03-06 23:17:13'),
(35, 'Compétences techniques requises :\\n- JavaScript, TypeScript, Node.js\\n- React, Vue.js ou Angular\\n- SQL et NoSQL\\n- Git, Docker\\n- API REST et GraphQL\\n\\nCompétences souhaitées :\\n- Expérience avec des méthodologies Agile\\n- Bonnes capacités de communication\\n- Autonomie et esprit d\'équipe\\n- Anglais professionnel', 'Bac +5 (Master, diplôme d\'ingénieur)', 5, '2026-03-06 23:45:22', '2026-03-06 23:45:22'),
(36, 'Compétences techniques requises :\\n- JavaScript, TypeScript, Node.js\\n- React, Vue.js ou Angular\\n- SQL et NoSQL\\n- Git, Docker\\n- API REST et GraphQL\\n\\nCompétences souhaitées :\\n- Expérience avec des méthodologies Agile\\n- Bonnes capacités de communication\\n- Autonomie et esprit d\'équipe\\n- Anglais professionnel', 'Bac +5 (Master, diplôme d\'ingénieur)', 5, '2026-03-06 23:47:07', '2026-03-06 23:47:07'),
(37, 'Compétences techniques requises :\n- JavaScript, TypeScript, Node.js\n- React, Vue.js ou Angular\n- SQL et NoSQL\n- Git, Docker\n- API REST et GraphQL\n\nCompétences souhaitées :\n- Expérience avec des méthodologies Agile\n- Bonnes capacités de communication\n- Autonomie et esprit d\'équipe\n- Anglais professionnel', 'Bac +5 (Master, diplôme d\'ingénieur)', 5, '2026-03-06 23:48:17', '2026-03-06 23:48:17'),
(39, 'Compétences techniques requises :\\n- JavaScript, TypeScript, Node.js\\n- React, Vue.js ou Angular\\n- SQL et NoSQL\\n- Git, Docker\\n- API REST et GraphQL\\n\\nCompétences souhaitées :\\n- Expérience avec des méthodologies Agile\\n- Bonnes capacités de communication\\n- Autonomie et esprit d\'équipe\\n- Anglais professionnel', 'Bac +5 (Master, diplôme d\'ingénieur)', 5, '2026-03-06 23:58:56', '2026-03-06 23:58:56');

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
(444, 'PHP', '8', 1, '2026-03-05 20:44:24', '2026-03-05 20:44:24'),
(445, 'Javascript', '8', 1, '2026-03-05 20:44:24', '2026-03-05 20:44:24'),
(446, 'HTML5/CSS3', '9', 1, '2026-03-05 20:44:24', '2026-03-05 20:44:24'),
(447, 'Node JS / React / Next JS', '4', 0, '2026-03-05 20:44:24', '2026-03-05 20:44:24'),
(448, 'SCRUM', '3', 0, '2026-03-05 20:44:24', '2026-03-05 20:44:24'),
(449, 'MySQL', '7', 0, '2026-03-05 20:44:25', '2026-03-05 20:44:25'),
(450, 'Java', '5', 0, '2026-03-05 20:44:25', '2026-03-05 20:44:25'),
(451, 'Figma', '5', 0, '2026-03-05 20:44:25', '2026-03-05 20:44:25'),
(452, 'PHP', '8', 1, '2026-03-05 20:46:35', '2026-03-05 20:46:35'),
(453, 'Javascript', '8', 1, '2026-03-05 20:46:35', '2026-03-05 20:46:35'),
(454, 'HTML5/CSS3', '9', 1, '2026-03-05 20:46:35', '2026-03-05 20:46:35'),
(455, 'React / Next JS', '4', 0, '2026-03-05 20:46:36', '2026-03-05 20:46:36'),
(456, 'SCRUM', '3', 0, '2026-03-05 20:46:36', '2026-03-05 20:46:36'),
(457, 'MySQL', '7', 0, '2026-03-05 20:46:36', '2026-03-05 20:46:36'),
(458, 'Java', '5', 0, '2026-03-05 20:46:36', '2026-03-05 20:46:36'),
(459, 'Figma', '5', 0, '2026-03-05 20:46:36', '2026-03-05 20:46:36'),
(460, 'PHP', '7', 1, '2026-03-05 23:19:58', '2026-03-05 23:19:58'),
(461, 'C/++', '8', 1, '2026-03-05 23:19:58', '2026-03-05 23:19:58'),
(462, 'JavaScript', '6', 0, '2026-03-05 23:19:59', '2026-03-05 23:19:59'),
(463, 'Golang', '1', 0, '2026-03-05 23:19:59', '2026-03-05 23:19:59'),
(464, 'PHP', '8', 1, '2026-03-05 23:33:54', '2026-03-05 23:33:54'),
(465, 'Javascript', '8', 1, '2026-03-05 23:33:54', '2026-03-05 23:33:54'),
(466, 'HTML5/CSS3', '9', 1, '2026-03-05 23:33:54', '2026-03-05 23:33:54'),
(467, 'React / Next JS', '4', 0, '2026-03-05 23:33:55', '2026-03-05 23:33:55'),
(468, 'SCRUM', '3', 0, '2026-03-05 23:33:55', '2026-03-05 23:33:55'),
(469, 'MySQL', '7', 0, '2026-03-05 23:33:55', '2026-03-05 23:33:55'),
(470, 'Java', '5', 0, '2026-03-05 23:33:55', '2026-03-05 23:33:55'),
(471, 'Figma', '5', 0, '2026-03-05 23:33:55', '2026-03-05 23:33:55'),
(472, 'PHP', '8', 1, '2026-03-05 23:39:39', '2026-03-05 23:39:39'),
(473, 'Javascript', '8', 1, '2026-03-05 23:39:39', '2026-03-05 23:39:39'),
(474, 'HTML5/CSS3', '9', 1, '2026-03-05 23:39:39', '2026-03-05 23:39:39'),
(475, 'React / Next JS', '4', 0, '2026-03-05 23:39:39', '2026-03-05 23:39:39'),
(476, 'SCRUM', '3', 0, '2026-03-05 23:39:39', '2026-03-05 23:39:39'),
(477, 'MySQL', '7', 0, '2026-03-05 23:39:39', '2026-03-05 23:39:39'),
(478, 'Java', '5', 0, '2026-03-05 23:39:39', '2026-03-05 23:39:39'),
(479, 'Figma', '5', 0, '2026-03-05 23:39:39', '2026-03-05 23:39:39'),
(480, 'PHP', '8', 1, '2026-03-05 23:40:26', '2026-03-05 23:40:26'),
(481, 'Javascript', '8', 1, '2026-03-05 23:40:26', '2026-03-05 23:40:26'),
(482, 'HTML5/CSS3', '9', 1, '2026-03-05 23:40:26', '2026-03-05 23:40:26'),
(483, 'React / Next JS', '4', 0, '2026-03-05 23:40:26', '2026-03-05 23:40:26'),
(484, 'SCRUM', '3', 0, '2026-03-05 23:40:26', '2026-03-05 23:40:26'),
(485, 'MySQL', '7', 0, '2026-03-05 23:40:26', '2026-03-05 23:40:26'),
(486, 'Java', '5', 0, '2026-03-05 23:40:26', '2026-03-05 23:40:26'),
(487, 'Figma', '5', 0, '2026-03-05 23:40:26', '2026-03-05 23:40:26'),
(488, 'PHP', '8', 1, '2026-03-05 23:42:44', '2026-03-05 23:42:44'),
(489, 'Javascript', '8', 1, '2026-03-05 23:42:44', '2026-03-05 23:42:44'),
(490, 'HTML5/CSS3', '9', 1, '2026-03-05 23:42:44', '2026-03-05 23:42:44'),
(491, 'React / Next JS', '4', 0, '2026-03-05 23:42:44', '2026-03-05 23:42:44'),
(492, 'PHP', '8', 1, '2026-03-05 23:42:44', '2026-03-05 23:42:44'),
(493, 'SCRUM', '3', 0, '2026-03-05 23:42:44', '2026-03-05 23:42:44'),
(494, 'Javascript', '8', 1, '2026-03-05 23:42:44', '2026-03-05 23:42:44'),
(495, 'MySQL', '7', 0, '2026-03-05 23:42:44', '2026-03-05 23:42:44'),
(496, 'HTML5/CSS3', '9', 1, '2026-03-05 23:42:44', '2026-03-05 23:42:44'),
(497, 'Java', '5', 0, '2026-03-05 23:42:44', '2026-03-05 23:42:44'),
(498, 'React / Next JS', '4', 0, '2026-03-05 23:42:44', '2026-03-05 23:42:44'),
(499, 'Figma', '5', 0, '2026-03-05 23:42:44', '2026-03-05 23:42:44'),
(500, 'SCRUM', '3', 0, '2026-03-05 23:42:44', '2026-03-05 23:42:44'),
(501, 'MySQL', '7', 0, '2026-03-05 23:42:44', '2026-03-05 23:42:44'),
(502, 'Java', '5', 0, '2026-03-05 23:42:44', '2026-03-05 23:42:44'),
(503, 'Figma', '5', 0, '2026-03-05 23:42:45', '2026-03-05 23:42:45'),
(504, 'PHP', '8', 1, '2026-03-05 23:43:24', '2026-03-05 23:43:24'),
(505, 'Javascript', '8', 1, '2026-03-05 23:43:24', '2026-03-05 23:43:24'),
(506, 'HTML5/CSS3', '9', 1, '2026-03-05 23:43:24', '2026-03-05 23:43:24'),
(507, 'React / Next JS', '4', 0, '2026-03-05 23:43:24', '2026-03-05 23:43:24'),
(508, 'SCRUM', '3', 0, '2026-03-05 23:43:24', '2026-03-05 23:43:24'),
(509, 'MySQL', '7', 0, '2026-03-05 23:43:24', '2026-03-05 23:43:24'),
(510, 'Java', '5', 0, '2026-03-05 23:43:25', '2026-03-05 23:43:25'),
(511, 'Figma', '5', 0, '2026-03-05 23:43:25', '2026-03-05 23:43:25'),
(512, 'PHP', '8', 1, '2026-03-06 12:10:25', '2026-03-06 12:10:25'),
(513, 'Javascript', '8', 1, '2026-03-06 12:10:25', '2026-03-06 12:10:25'),
(514, 'HTML5/CSS3', '9', 1, '2026-03-06 12:10:25', '2026-03-06 12:10:25'),
(515, 'React / Next JS', '4', 0, '2026-03-06 12:10:25', '2026-03-06 12:10:25'),
(516, 'SCRUM', '3', 0, '2026-03-06 12:10:26', '2026-03-06 12:10:26'),
(517, 'MySQL', '7', 0, '2026-03-06 12:10:26', '2026-03-06 12:10:26'),
(518, 'Java', '5', 0, '2026-03-06 12:10:26', '2026-03-06 12:10:26'),
(519, 'Figma', '5', 0, '2026-03-06 12:10:26', '2026-03-06 12:10:26'),
(520, 'PHP', '8', 1, '2026-03-06 12:17:57', '2026-03-06 12:17:57'),
(521, 'Javascript', '8', 1, '2026-03-06 12:17:57', '2026-03-06 12:17:57'),
(522, 'HTML5/CSS3', '9', 1, '2026-03-06 12:17:58', '2026-03-06 12:17:58'),
(523, 'React / Next JS', '4', 0, '2026-03-06 12:17:58', '2026-03-06 12:17:58'),
(524, 'SCRUM', '3', 0, '2026-03-06 12:17:58', '2026-03-06 12:17:58'),
(525, 'MySQL', '7', 0, '2026-03-06 12:17:58', '2026-03-06 12:17:58'),
(526, 'Java', '5', 0, '2026-03-06 12:17:58', '2026-03-06 12:17:58'),
(527, 'Figma', '5', 0, '2026-03-06 12:17:58', '2026-03-06 12:17:58'),
(528, 'PHP', '8', 1, '2026-03-06 18:14:02', '2026-03-06 18:14:02'),
(529, 'Javascript', '8', 1, '2026-03-06 18:14:03', '2026-03-06 18:14:03'),
(530, 'HTML5/CSS3', '9', 1, '2026-03-06 18:14:03', '2026-03-06 18:14:03'),
(531, 'React / Next JS', '4', 0, '2026-03-06 18:14:03', '2026-03-06 18:14:03'),
(532, 'SCRUM', '3', 0, '2026-03-06 18:14:03', '2026-03-06 18:14:03'),
(533, 'MySQL', '7', 0, '2026-03-06 18:14:04', '2026-03-06 18:14:04'),
(534, 'Java', '5', 0, '2026-03-06 18:14:04', '2026-03-06 18:14:04'),
(535, 'Figma', '5', 0, '2026-03-06 18:14:04', '2026-03-06 18:14:04'),
(536, 'PHP', '8', 1, '2026-03-06 18:14:08', '2026-03-06 18:14:08'),
(537, 'Javascript', '8', 1, '2026-03-06 18:14:08', '2026-03-06 18:14:08'),
(538, 'HTML5/CSS3', '9', 1, '2026-03-06 18:14:08', '2026-03-06 18:14:08'),
(539, 'React / Next JS', '4', 0, '2026-03-06 18:14:08', '2026-03-06 18:14:08'),
(540, 'SCRUM', '3', 0, '2026-03-06 18:14:09', '2026-03-06 18:14:09'),
(541, 'MySQL', '7', 0, '2026-03-06 18:14:09', '2026-03-06 18:14:09'),
(542, 'Java', '5', 0, '2026-03-06 18:14:09', '2026-03-06 18:14:09'),
(543, 'Figma', '5', 0, '2026-03-06 18:14:09', '2026-03-06 18:14:09'),
(544, 'PHP', '8', 1, '2026-03-06 23:37:51', '2026-03-06 23:37:51'),
(545, 'Javascript', '8', 1, '2026-03-06 23:37:51', '2026-03-06 23:37:51'),
(546, 'HTML5/CSS3', '9', 1, '2026-03-06 23:37:51', '2026-03-06 23:37:51'),
(547, 'React / Next JS', '4', 0, '2026-03-06 23:37:51', '2026-03-06 23:37:51'),
(548, 'SCRUM', '3', 0, '2026-03-06 23:37:51', '2026-03-06 23:37:51'),
(549, 'MySQL', '7', 0, '2026-03-06 23:37:51', '2026-03-06 23:37:51'),
(550, 'Java', '5', 0, '2026-03-06 23:37:52', '2026-03-06 23:37:52'),
(551, 'Figma', '5', 0, '2026-03-06 23:37:52', '2026-03-06 23:37:52');

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

-- --------------------------------------------------------

--
-- Structure de la table `suggestion`
--

CREATE TABLE `suggestion` (
  `id` int(11) NOT NULL,
  `job_id` int(11) DEFAULT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Index pour la table `action`
--
ALTER TABLE `action`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `aiecourt`
--
ALTER TABLE `aiecourt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_aiecourt_activity` (`activity_id`),
  ADD KEY `fk_aiecourt_country` (`country_id`),
  ADD KEY `fk_aiecourt_action` (`action_id`);

--
-- Index pour la table `application`
--
ALTER TABLE `application`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_candidate_id` (`candidate_id`),
  ADD KEY `idx_job_id` (`job_id`);

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
-- Index pour la table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_company_contact` (`contact_id`),
  ADD KEY `idx_company_name` (`name`),
  ADD KEY `idx_country_id` (`country_id`),
  ADD KEY `idx_media_code` (`media_code`);

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contact_email` (`email`);

--
-- Index pour la table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_code` (`code`);

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
-- Index pour la table `exchange`
--
ALTER TABLE `exchange`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_exchange_activity` (`activity_id`),
  ADD KEY `fk_exchange_country` (`country_id`);

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
  ADD KEY `idx_job_approved` (`approved`),
  ADD KEY `idx_activity_id` (`activity_id`),
  ADD KEY `idx_country_id` (`country_id`);

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
-- Index pour la table `preference`
--
ALTER TABLE `preference`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_account_id` (`account_id`);

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
-- Index pour la table `suggestion`
--
ALTER TABLE `suggestion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_job_id` (`job_id`),
  ADD KEY `idx_candidate_id` (`candidate_id`),
  ADD KEY `idx_company_id` (`company_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT pour la table `action`
--
ALTER TABLE `action`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `aiecourt`
--
ALTER TABLE `aiecourt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `application`
--
ALTER TABLE `application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `candidate`
--
ALTER TABLE `candidate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT pour la table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `degree`
--
ALTER TABLE `degree`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `demand`
--
ALTER TABLE `demand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT pour la table `exchange`
--
ALTER TABLE `exchange`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `experience`
--
ALTER TABLE `experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT pour la table `formation`
--
ALTER TABLE `formation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT pour la table `hobbie`
--
ALTER TABLE `hobbie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT pour la table `identity`
--
ALTER TABLE `identity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT pour la table `job`
--
ALTER TABLE `job`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `knowledge`
--
ALTER TABLE `knowledge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=300;

--
-- AUTO_INCREMENT pour la table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT pour la table `language_level`
--
ALTER TABLE `language_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `mission`
--
ALTER TABLE `mission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=347;

--
-- AUTO_INCREMENT pour la table `personality`
--
ALTER TABLE `personality`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT pour la table `preference`
--
ALTER TABLE `preference`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `requirement`
--
ALTER TABLE `requirement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `skill`
--
ALTER TABLE `skill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=552;

--
-- AUTO_INCREMENT pour la table `social`
--
ALTER TABLE `social`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `suggestion`
--
ALTER TABLE `suggestion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `aiecourt`
--
ALTER TABLE `aiecourt`
  ADD CONSTRAINT `fk_aiecourt_action` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_aiecourt_activity` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_aiecourt_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `fk_application_candidate` FOREIGN KEY (`candidate_id`) REFERENCES `candidate` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_application_job` FOREIGN KEY (`job_id`) REFERENCES `job` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `candidate`
--
ALTER TABLE `candidate`
  ADD CONSTRAINT `fk_candidate_activity` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_candidate_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_candidate_demand` FOREIGN KEY (`demand_id`) REFERENCES `demand` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_candidate_identity` FOREIGN KEY (`identity_id`) REFERENCES `identity` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_candidate_social` FOREIGN KEY (`social_id`) REFERENCES `social` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `fk_company_contact` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `exchange`
--
ALTER TABLE `exchange`
  ADD CONSTRAINT `fk_exchange_activity` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_exchange_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `identity`
--
ALTER TABLE `identity`
  ADD CONSTRAINT `fk_identity_degree` FOREIGN KEY (`degree_id`) REFERENCES `degree` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `fk_job_activity` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_job_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_job_contact` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_job_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_job_requirement` FOREIGN KEY (`requirement_id`) REFERENCES `requirement` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `language`
--
ALTER TABLE `language`
  ADD CONSTRAINT `fk_language_level` FOREIGN KEY (`level_id`) REFERENCES `language_level` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `preference`
--
ALTER TABLE `preference`
  ADD CONSTRAINT `preference_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

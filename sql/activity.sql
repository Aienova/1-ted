-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 26 fév. 2026 à 19:03
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

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

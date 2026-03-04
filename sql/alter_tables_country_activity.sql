-- ============================================================
-- MODIFICATION DES TABLES POUR COUNTRY ET ACTIVITY
-- ============================================================
-- Ce fichier modifie les tables existantes pour ajouter les 
-- relations avec country_id et activity_id
-- ============================================================

-- --------------------------------------------------------
-- 1. CRÉATION DES TABLES COUNTRY ET ACTIVITY
-- --------------------------------------------------------

DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `pt_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `country` (`name`, `code`, `default_language`, `currency_symbol`, `fr_name`, `en_name`, `de_name`, `es_name`, `it_name`, `nl_name`, `pt_name`) VALUES
('France', 'FR', 'French', '€', 'France', 'France', 'Frankreich', 'Francia', 'Francia', 'Frankrijk', 'França'),
('Allemagne', 'DE', 'German', '€', 'Allemagne', 'Germany', 'Deutschland', 'Alemania', 'Germania', 'Duitsland', 'Alemanha'),
('Italie', 'IT', 'Italian', '€', 'Italie', 'Italy', 'Italien', 'Italia', 'Italia', 'Italië', 'Itália'),
('Espagne', 'ES', 'Spanish', '€', 'Espagne', 'Spain', 'Spanien', 'España', 'Spagna', 'Spanje', 'Espanha'),
('Pays-Bas', 'NL', 'Dutch', '€', 'Pays-Bas', 'Netherlands', 'Niederlande', 'Países Bajos', 'Paesi Bassi', 'Nederland', 'Países Baixos'),
('Belgique', 'BE', 'Dutch', '€', 'Belgique', 'Belgium', 'Belgien', 'Bélgica', 'Belgio', 'België', 'Bélgica'),
('Portugal', 'PT', 'Portuguese', '€', 'Portugal', 'Portugal', 'Portugal', 'Portugal', 'Portogallo', 'Portugal', 'Portugal'),
('Autriche', 'AT', 'German', '€', 'Autriche', 'Austria', 'Österreich', 'Austria', 'Austria', 'Oostenrijk', 'Áustria'),
('Grèce', 'GR', 'Greek', '€', 'Grèce', 'Greece', 'Griechenland', 'Grecia', 'Grecia', 'Griekenland', 'Grécia'),
('Pologne', 'PL', 'Polish', 'zł', 'Pologne', 'Poland', 'Polen', 'Polonia', 'Polonia', 'Polen', 'Polónia'),
('Roumanie', 'RO', 'Romanian', 'lei', 'Roumanie', 'Romania', 'Rumänien', 'Rumania', 'Romania', 'Roemenië', 'Roménia'),
('République tchèque', 'CZ', 'Czech', 'Kč', 'République tchèque', 'Czech Republic', 'Tschechien', 'República Checa', 'Repubblica Ceca', 'Tsjechië', 'República Checa'),
('Hongrie', 'HU', 'Hungarian', 'Ft', 'Hongrie', 'Hungary', 'Ungarn', 'Hungría', 'Ungheria', 'Hongarije', 'Hungria'),
('Suède', 'SE', 'Swedish', 'kr', 'Suède', 'Sweden', 'Schweden', 'Suecia', 'Svezia', 'Zweden', 'Suécia'),
('Bulgarie', 'BG', 'Bulgarian', 'лв', 'Bulgarie', 'Bulgaria', 'Bulgarien', 'Bulgaria', 'Bulgaria', 'Bulgarije', 'Bulgária'),
('Danemark', 'DK', 'Danish', 'kr', 'Danemark', 'Denmark', 'Dänemark', 'Dinamarca', 'Danimarca', 'Denemarken', 'Dinamarca'),
('Finlande', 'FI', 'Finnish', '€', 'Finlande', 'Finland', 'Finnland', 'Finlandia', 'Finlandia', 'Finland', 'Finlândia'),
('Slovaquie', 'SK', 'Slovak', '€', 'Slovaquie', 'Slovakia', 'Slowakei', 'Eslovaquia', 'Slovacchia', 'Slowakije', 'Eslováquia'),
('Irlande', 'IE', 'English', '€', 'Irlande', 'Ireland', 'Irland', 'Irlanda', 'Irlanda', 'Ierland', 'Irlanda'),
('Croatie', 'HR', 'Croatian', '€', 'Croatie', 'Croatia', 'Kroatien', 'Croacia', 'Croazia', 'Kroatië', 'Croácia'),
('Lituanie', 'LT', 'Lithuanian', '€', 'Lituanie', 'Lithuania', 'Litauen', 'Lituania', 'Lituania', 'Litouwen', 'Lituânia'),
('Slovénie', 'SI', 'Slovenian', '€', 'Slovénie', 'Slovenia', 'Slowenien', 'Eslovenia', 'Slovenia', 'Slovenië', 'Eslovénia'),
('Lettonie', 'LV', 'Latvian', '€', 'Lettonie', 'Latvia', 'Lettland', 'Letonia', 'Lettonia', 'Letland', 'Letónia'),
('Estonie', 'EE', 'Estonian', '€', 'Estonie', 'Estonia', 'Estland', 'Estonia', 'Estonia', 'Estland', 'Estónia'),
('Chypre', 'CY', 'Greek', '€', 'Chypre', 'Cyprus', 'Zypern', 'Chipre', 'Cipro', 'Cyprus', 'Chipre'),
('Luxembourg', 'LU', 'French', '€', 'Luxembourg', 'Luxembourg', 'Luxemburg', 'Luxemburgo', 'Lussemburgo', 'Luxemburg', 'Luxemburgo'),
('Malte', 'MT', 'Maltese', '€', 'Malte', 'Malta', 'Malta', 'Malta', 'Malta', 'Malta', 'Malta'),
('Royaume-Uni', 'GB', 'English', '£', 'Royaume-Uni', 'United Kingdom', 'Vereinigtes Königreich', 'Reino Unido', 'Regno Unito', 'Verenigd Koninkrijk', 'Reino Unido'),
('Suisse', 'CH', 'German', 'CHF', 'Suisse', 'Switzerland', 'Schweiz', 'Suiza', 'Svizzera', 'Zwitserland', 'Suíça');

DROP TABLE IF EXISTS `activity`;
CREATE TABLE `activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `fr_name` varchar(100) DEFAULT NULL,
  `en_name` varchar(100) DEFAULT NULL,
  `de_name` varchar(100) DEFAULT NULL,
  `es_name` varchar(100) DEFAULT NULL,
  `it_name` varchar(100) DEFAULT NULL,
  `nl_name` varchar(100) DEFAULT NULL,
  `pt_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `activity` (`name`, `description`, `fr_name`, `en_name`, `de_name`, `es_name`, `it_name`, `nl_name`, `pt_name`) VALUES
('Informatique et Technologies', 'Développement logiciel, IT, cybersécurité, data science', 'Informatique et Technologies', 'IT and Technology', 'Informatik und Technologie', 'Informática y Tecnología', 'Informatica e Tecnologia', 'ICT en Technologie', 'Informática e Tecnologia'),
('BTP et Construction', 'Bâtiment, travaux publics, génie civil', 'BTP et Construction', 'Construction and Civil Engineering', 'Bau und Bauwesen', 'Construcción e Ingeniería Civil', 'Edilizia e Ingegneria Civile', 'Bouw en Civiele Techniek', 'Construção e Engenharia Civil'),
('Santé et Médical', 'Médecine, soins infirmiers, pharmacie, recherche médicale', 'Santé et Médical', 'Healthcare and Medical', 'Gesundheit und Medizin', 'Salud y Medicina', 'Sanità e Medicina', 'Gezondheidszorg en Medisch', 'Saúde e Medicina'),
('Commerce et Vente', 'Vente au détail, e-commerce, développement commercial', 'Commerce et Vente', 'Sales and Retail', 'Handel und Verkauf', 'Comercio y Ventas', 'Commercio e Vendite', 'Handel en Verkoop', 'Comércio e Vendas'),
('Finance et Banque', 'Banque, assurance, comptabilité, audit', 'Finance et Banque', 'Finance and Banking', 'Finanzen und Bankwesen', 'Finanzas y Banca', 'Finanza e Banca', 'Financiën en Bankieren', 'Finanças e Banca'),
('Marketing et Communication', 'Marketing digital, communication, publicité, relations publiques', 'Marketing et Communication', 'Marketing and Communications', 'Marketing und Kommunikation', 'Marketing y Comunicación', 'Marketing e Comunicazione', 'Marketing en Communicatie', 'Marketing e Comunicação'),
('Ressources Humaines', 'Recrutement, gestion des talents, formation, paie', 'Ressources Humaines', 'Human Resources', 'Personalwesen', 'Recursos Humanos', 'Risorse Umane', 'Human Resources', 'Recursos Humanos'),
('Industrie et Production', 'Manufacturing, production, chaîne de montage, qualité', 'Industrie et Production', 'Manufacturing and Production', 'Industrie und Produktion', 'Industria y Producción', 'Industria e Produzione', 'Industrie en Productie', 'Indústria e Produção'),
('Logistique et Transport', 'Supply chain, transport, entreposage, distribution', 'Logistique et Transport', 'Logistics and Transportation', 'Logistik und Transport', 'Logística y Transporte', 'Logistica e Trasporti', 'Logistiek en Transport', 'Logística e Transporte'),
('Éducation et Formation', 'Enseignement, formation professionnelle, recherche académique', 'Éducation et Formation', 'Education and Training', 'Bildung und Ausbildung', 'Educación y Formación', 'Istruzione e Formazione', 'Onderwijs en Training', 'Educação e Formação'),
('Hôtellerie et Restauration', 'Hôtels, restaurants, tourisme, événementiel', 'Hôtellerie et Restauration', 'Hospitality and Catering', 'Gastgewerbe und Gastronomie', 'Hostelería y Restauración', 'Ospitalità e Ristorazione', 'Horeca en Catering', 'Hotelaria e Restauração'),
('Juridique et Droit', 'Avocats, notaires, conseil juridique, compliance', 'Juridique et Droit', 'Legal and Law', 'Recht und Justiz', 'Legal y Derecho', 'Legale e Diritto', 'Juridisch en Recht', 'Jurídico e Direito'),
('Agriculture et Agroalimentaire', 'Agriculture, élevage, industrie alimentaire, viticulture', 'Agriculture et Agroalimentaire', 'Agriculture and Food Industry', 'Landwirtschaft und Lebensmittel', 'Agricultura y Agroalimentaria', 'Agricoltura e Agroalimentare', 'Landbouw en Voedselindustrie', 'Agricultura e Agroalimentar'),
('Énergie et Environnement', 'Énergies renouvelables, environnement, développement durable', 'Énergie et Environnement', 'Energy and Environment', 'Energie und Umwelt', 'Energía y Medio Ambiente', 'Energia e Ambiente', 'Energie en Milieu', 'Energia e Meio Ambiente'),
('Immobilier', 'Promotion immobilière, gestion de biens, transaction', 'Immobilier', 'Real Estate', 'Immobilien', 'Inmobiliario', 'Immobiliare', 'Vastgoed', 'Imobiliário'),
('Automobile', 'Construction automobile, équipements, services automobiles', 'Automobile', 'Automotive', 'Automobil', 'Automoción', 'Automobilistico', 'Automobielindustrie', 'Automóvel'),
('Aéronautique et Spatial', 'Aviation, aérospatiale, défense', 'Aéronautique et Spatial', 'Aerospace and Aviation', 'Luft- und Raumfahrt', 'Aeronáutica y Espacial', 'Aeronautica e Spaziale', 'Lucht- en Ruimtevaart', 'Aeronáutica e Espacial'),
('Arts et Culture', 'Arts, musées, spectacles, patrimoine culturel', 'Arts et Culture', 'Arts and Culture', 'Kunst und Kultur', 'Arte y Cultura', 'Arte e Cultura', 'Kunst en Cultuur', 'Arte e Cultura'),
('Médias et Presse', 'Journalisme, édition, audiovisuel, presse écrite', 'Médias et Presse', 'Media and Press', 'Medien und Presse', 'Medios y Prensa', 'Media e Stampa', 'Media en Pers', 'Média e Imprensa'),
('Design et Créativité', 'Design graphique, UX/UI, design produit, architecture intérieure', 'Design et Créativité', 'Design and Creativity', 'Design und Kreativität', 'Diseño y Creatividad', 'Design e Creatività', 'Design en Creativiteit', 'Design e Criatividade'),
('Conseil et Stratégie', 'Conseil en management, stratégie, organisation', 'Conseil et Stratégie', 'Consulting and Strategy', 'Beratung und Strategie', 'Consultoría y Estrategia', 'Consulenza e Strategia', 'Consultancy en Strategie', 'Consultoria e Estratégia'),
('Services aux Entreprises', 'Nettoyage, sécurité, maintenance, services généraux', 'Services aux Entreprises', 'Business Services', 'Unternehmensdienstleistungen', 'Servicios a Empresas', 'Servizi alle Imprese', 'Zakelijke Diensten', 'Serviços às Empresas'),
('Social et Services à la Personne', 'Aide à domicile, services sociaux, petite enfance', 'Social et Services à la Personne', 'Social and Personal Services', 'Soziales und Personendienste', 'Social y Servicios a la Persona', 'Sociale e Servizi alla Persona', 'Sociaal en Persoonlijke Diensten', 'Social e Serviços à Pessoa'),
('Sport et Loisirs', 'Sport professionnel, fitness, loisirs, animation', 'Sport et Loisirs', 'Sports and Recreation', 'Sport und Freizeit', 'Deporte y Ocio', 'Sport e Tempo Libero', 'Sport en Recreatie', 'Desporto e Lazer'),
('Mode et Luxe', 'Mode, textile, maroquinerie, joaillerie, cosmétique', 'Mode et Luxe', 'Fashion and Luxury', 'Mode und Luxus', 'Moda y Lujo', 'Moda e Lusso', 'Mode en Luxe', 'Moda e Luxo'),
('Chimie et Pharmacie', 'Chimie industrielle, pharmacie, biotechnologie', 'Chimie et Pharmacie', 'Chemistry and Pharmaceuticals', 'Chemie und Pharmazie', 'Química y Farmacia', 'Chimica e Farmacia', 'Chemie en Farmacie', 'Química e Farmácia'),
('Télécommunications', 'Opérateurs télécom, réseaux, infrastructures numériques', 'Télécommunications', 'Telecommunications', 'Telekommunikation', 'Telecomunicaciones', 'Telecomunicazioni', 'Telecommunicatie', 'Telecomunicações'),
('Administration Publique', 'Fonction publique, collectivités, services de l\'État', 'Administration Publique', 'Public Administration', 'Öffentliche Verwaltung', 'Administración Pública', 'Amministrazione Pubblica', 'Overheidsadministratie', 'Administração Pública'),
('Recherche et Développement', 'R&D, innovation, laboratoires, centres de recherche', 'Recherche et Développement', 'Research and Development', 'Forschung und Entwicklung', 'Investigación y Desarrollo', 'Ricerca e Sviluppo', 'Onderzoek en Ontwikkeling', 'Pesquisa e Desenvolvimento'),
('Sécurité et Défense', 'Sécurité privée, défense, pompiers, police', 'Sécurité et Défense', 'Security and Defense', 'Sicherheit und Verteidigung', 'Seguridad y Defensa', 'Sicurezza e Difesa', 'Veiligheid en Defensie', 'Segurança e Defesa');

-- --------------------------------------------------------
-- 2. MODIFICATION DE LA TABLE ACCOUNT
-- --------------------------------------------------------

-- Vérifier si les colonnes existent déjà et les ajouter si nécessaire
SET @dbname = DATABASE();
SET @tablename = "account";
SET @columnname1 = "activity_id";
SET @columnname2 = "country_id";

SET @preparedStatement1 = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE (table_name = @tablename)
   AND (table_schema = @dbname)
   AND (column_name = @columnname1)) > 0,
  "SELECT 1",
  CONCAT("ALTER TABLE `", @tablename, "` ADD COLUMN `", @columnname1, "` int(11) DEFAULT NULL AFTER `company_siret`, ADD KEY `idx_activity_id` (`", @columnname1, "`)")
));
PREPARE alterIfNotExists1 FROM @preparedStatement1;
EXECUTE alterIfNotExists1;
DEALLOCATE PREPARE alterIfNotExists1;

SET @preparedStatement2 = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE (table_name = @tablename)
   AND (table_schema = @dbname)
   AND (column_name = @columnname2)) > 0,
  "SELECT 1",
  CONCAT("ALTER TABLE `", @tablename, "` ADD COLUMN `", @columnname2, "` int(11) DEFAULT NULL AFTER `activity_id`, ADD KEY `idx_country_id` (`", @columnname2, "`)")
));
PREPARE alterIfNotExists2 FROM @preparedStatement2;
EXECUTE alterIfNotExists2;
DEALLOCATE PREPARE alterIfNotExists2;

-- --------------------------------------------------------
-- 3. MODIFICATION DE LA TABLE CANDIDATE
-- --------------------------------------------------------

SET @tablename = "candidate";
SET @columnname1 = "country_id";
SET @columnname2 = "activity_id";

SET @preparedStatement1 = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE (table_name = @tablename)
   AND (table_schema = @dbname)
   AND (column_name = @columnname1)) > 0,
  "SELECT 1",
  CONCAT("ALTER TABLE `", @tablename, "` ADD COLUMN `", @columnname1, "` int(11) DEFAULT NULL, ADD KEY `idx_country_id` (`", @columnname1, "`)")
));
PREPARE alterIfNotExists1 FROM @preparedStatement1;
EXECUTE alterIfNotExists1;
DEALLOCATE PREPARE alterIfNotExists1;

SET @preparedStatement2 = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE (table_name = @tablename)
   AND (table_schema = @dbname)
   AND (column_name = @columnname2)) > 0,
  "SELECT 1",
  CONCAT("ALTER TABLE `", @tablename, "` ADD COLUMN `", @columnname2, "` int(11) DEFAULT NULL, ADD KEY `idx_activity_id` (`", @columnname2, "`)")
));
PREPARE alterIfNotExists2 FROM @preparedStatement2;
EXECUTE alterIfNotExists2;
DEALLOCATE PREPARE alterIfNotExists2;

-- --------------------------------------------------------
-- 4. MODIFICATION DE LA TABLE JOB
-- --------------------------------------------------------

SET @tablename = "job";
SET @columnname1 = "activity_id";
SET @columnname2 = "country_id";

SET @preparedStatement1 = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE (table_name = @tablename)
   AND (table_schema = @dbname)
   AND (column_name = @columnname1)) > 0,
  "SELECT 1",
  CONCAT("ALTER TABLE `", @tablename, "` ADD COLUMN `", @columnname1, "` int(11) DEFAULT NULL, ADD KEY `idx_activity_id` (`", @columnname1, "`)")
));
PREPARE alterIfNotExists1 FROM @preparedStatement1;
EXECUTE alterIfNotExists1;
DEALLOCATE PREPARE alterIfNotExists1;

SET @preparedStatement2 = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE (table_name = @tablename)
   AND (table_schema = @dbname)
   AND (column_name = @columnname2)) > 0,
  "SELECT 1",
  CONCAT("ALTER TABLE `", @tablename, "` ADD COLUMN `", @columnname2, "` int(11) DEFAULT NULL, ADD KEY `idx_country_id` (`", @columnname2, "`)")
));
PREPARE alterIfNotExists2 FROM @preparedStatement2;
EXECUTE alterIfNotExists2;
DEALLOCATE PREPARE alterIfNotExists2;

-- --------------------------------------------------------
-- 5. MODIFICATION DE LA TABLE COMPANY
-- --------------------------------------------------------

SET @tablename = "company";
SET @columnname = "country_id";

SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE (table_name = @tablename)
   AND (table_schema = @dbname)
   AND (column_name = @columnname)) > 0,
  "SELECT 1",
  CONCAT("ALTER TABLE `", @tablename, "` ADD COLUMN `", @columnname, "` int(11) DEFAULT NULL, ADD KEY `idx_country_id` (`", @columnname, "`)")
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- --------------------------------------------------------
-- 6. METTRE À JOUR LES DONNÉES EXISTANTES (OPTIONNEL)
-- --------------------------------------------------------

-- Mettre tous les candidats en France par défaut si country_id est NULL
-- UPDATE `candidate` SET `country_id` = 1 WHERE `country_id` IS NULL;

-- Mettre tous les jobs en France par défaut si country_id est NULL
-- UPDATE `job` SET `country_id` = 1 WHERE `country_id` IS NULL;

-- Mettre toutes les entreprises en France par défaut si country_id est NULL
-- UPDATE `company` SET `country_id` = 1 WHERE `country_id` IS NULL;

-- Mettre tous les comptes en France par défaut si country_id est NULL
-- UPDATE `account` SET `country_id` = 1 WHERE `country_id` IS NULL;

-- --------------------------------------------------------
-- FIN DU SCRIPT
-- --------------------------------------------------------

-- --------------------------------------------------------
-- 7. CLÉS ÉTRANGÈRES VERS COUNTRY ET ACTIVITY
-- À exécuter après la création de toutes les tables
-- --------------------------------------------------------

ALTER TABLE `candidate`
  ADD CONSTRAINT `fk_candidate_country`  FOREIGN KEY (`country_id`)  REFERENCES `country`  (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_candidate_activity` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE SET NULL;

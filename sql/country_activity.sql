-- Structure et données pour les tables Country et Activity
-- Tables de référence pour les pays et secteurs d'activité

-- --------------------------------------------------------
-- Structure de la table `country`
-- --------------------------------------------------------

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

-- --------------------------------------------------------
-- Insertion des données - Pays de l'UE + UK + Suisse
-- --------------------------------------------------------

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
-- Pays hors UE
('Royaume-Uni', 'GB', 'English', '£', 'Royaume-Uni', 'United Kingdom', 'Vereinigtes Königreich', 'Reino Unido', 'Regno Unito', 'Verenigd Koninkrijk', 'Reino Unido'),
('Suisse', 'CH', 'German', 'CHF', 'Suisse', 'Switzerland', 'Schweiz', 'Suiza', 'Svizzera', 'Zwitserland', 'Suíça');

-- --------------------------------------------------------
-- Structure de la table `activity`
-- --------------------------------------------------------

CREATE TABLE `activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `fr_name` varchar(255) DEFAULT NULL,
  `en_name` varchar(255) DEFAULT NULL,
  `de_name` varchar(255) DEFAULT NULL,
  `es_name` varchar(255) DEFAULT NULL,
  `it_name` varchar(255) DEFAULT NULL,
  `nl_name` varchar(255) DEFAULT NULL,
  `pt_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Insertion des données - Secteurs d'activité
-- --------------------------------------------------------

INSERT INTO `activity` (`name`, `fr_name`, `en_name`, `de_name`, `es_name`, `it_name`, `nl_name`, `pt_name`, `description`) VALUES
('Informatique et Technologies', 'Informatique et Technologies', 'IT and Technology', 'Informatik und Technologie', 'Informática y Tecnología', 'Informatica e Tecnologia', 'IT en Technologie', 'Informática e Tecnologia', 'Développement logiciel, infrastructure IT, cybersécurité, cloud computing'),
('BTP et Construction', 'BTP et Construction', 'Construction and Building', 'Bauwesen', 'Construcción', 'Edilizia e Costruzioni', 'Bouw en Constructie', 'Construção Civil', 'Construction de bâtiments, travaux publics, génie civil'),
('Santé et Médical', 'Santé et Médical', 'Healthcare and Medical', 'Gesundheit und Medizin', 'Salud y Medicina', 'Sanità e Medicina', 'Gezondheid en Medisch', 'Saúde e Medicina', 'Soins médicaux, hôpitaux, pharmaceutique, recherche médicale'),
('Commerce et Vente', 'Commerce et Vente', 'Sales and Retail', 'Handel und Verkauf', 'Comercio y Ventas', 'Commercio e Vendite', 'Handel en Verkoop', 'Comércio e Vendas', 'Commerce de détail, vente B2B, e-commerce'),
('Finance et Banque', 'Finance et Banque', 'Finance and Banking', 'Finanzen und Bankwesen', 'Finanzas y Banca', 'Finanza e Banca', 'Financiën en Bankieren', 'Finanças e Banca', 'Services bancaires, assurance, comptabilité, audit'),
('Marketing et Communication', 'Marketing et Communication', 'Marketing and Communication', 'Marketing und Kommunikation', 'Marketing y Comunicación', 'Marketing e Comunicazione', 'Marketing en Communicatie', 'Marketing e Comunicação', 'Marketing digital, publicité, relations publiques, communication'),
('Ressources Humaines', 'Ressources Humaines', 'Human Resources', 'Personalwesen', 'Recursos Humanos', 'Risorse Umane', 'Human Resources', 'Recursos Humanos', 'Recrutement, formation, gestion des talents, paie'),
('Industrie et Production', 'Industrie et Production', 'Manufacturing and Production', 'Industrie und Produktion', 'Industria y Producción', 'Industria e Produzione', 'Industrie en Productie', 'Indústria e Produção', 'Manufacturing, production industrielle, automatisation'),
('Logistique et Transport', 'Logistique et Transport', 'Logistics and Transportation', 'Logistik und Transport', 'Logística y Transporte', 'Logistica e Trasporti', 'Logistiek en Transport', 'Logística e Transporte', 'Supply chain, transport de marchandises, entreposage'),
('Éducation et Formation', 'Éducation et Formation', 'Education and Training', 'Bildung und Ausbildung', 'Educación y Formación', 'Istruzione e Formazione', 'Onderwijs en Training', 'Educação e Formação', 'Enseignement, formation professionnelle, e-learning'),
('Hôtellerie et Restauration', 'Hôtellerie et Restauration', 'Hospitality and Food Service', 'Gastgewerbe und Gastronomie', 'Hostelería y Restauración', 'Ospitalità e Ristorazione', 'Horeca', 'Hotelaria e Restauração', 'Hôtels, restaurants, tourisme, événementiel'),
('Agriculture et Agroalimentaire', 'Agriculture et Agroalimentaire', 'Agriculture and Food Industry', 'Landwirtschaft und Ernährung', 'Agricultura y Alimentación', 'Agricoltura e Alimentare', 'Landbouw en Voedsel', 'Agricultura e Agroalimentar', 'Production agricole, transformation alimentaire, viticulture'),
('Énergie et Environnement', 'Énergie et Environnement', 'Energy and Environment', 'Energie und Umwelt', 'Energía y Medio Ambiente', 'Energia e Ambiente', 'Energie en Milieu', 'Energia e Ambiente', 'Énergies renouvelables, gestion des déchets, développement durable'),
('Automobile', 'Automobile', 'Automotive', 'Automobilindustrie', 'Automoción', 'Automotive', 'Automotive', 'Automóvel', 'Construction automobile, équipementiers, services automobiles'),
('Aéronautique et Spatial', 'Aéronautique et Spatial', 'Aerospace', 'Luft- und Raumfahrt', 'Aeronáutica y Espacial', 'Aerospaziale', 'Ruimtevaart', 'Aeronáutica e Espacial', 'Aviation, spatial, défense aérienne'),
('Services juridiques', 'Services juridiques', 'Legal Services', 'Rechtsdienstleistungen', 'Servicios Jurídicos', 'Servizi Legali', 'Juridische Diensten', 'Serviços Jurídicos', 'Cabinets d\'avocats, conseil juridique, notariat'),
('Immobilier', 'Immobilier', 'Real Estate', 'Immobilien', 'Inmobiliario', 'Immobiliare', 'Vastgoed', 'Imobiliário', 'Promotion immobilière, gestion de patrimoine, courtage'),
('Médias et Entertainment', 'Médias et Entertainment', 'Media and Entertainment', 'Medien und Unterhaltung', 'Medios y Entretenimiento', 'Media e Intrattenimento', 'Media en Entertainment', 'Média e Entretenimento', 'Presse, télévision, cinéma, jeux vidéo, production audiovisuelle'),
('Télécommunications', 'Télécommunications', 'Telecommunications', 'Telekommunikation', 'Telecomunicaciones', 'Telecomunicazioni', 'Telecommunicatie', 'Telecomunicações', 'Opérateurs télécoms, réseaux, téléphonie'),
('Design et Créativité', 'Design et Créativité', 'Design and Creative', 'Design und Kreativität', 'Diseño y Creatividad', 'Design e Creatività', 'Design en Creativiteit', 'Design e Criatividade', 'Design graphique, UX/UI, architecture d\'intérieur, mode'),
('Conseil et Stratégie', 'Conseil et Stratégie', 'Consulting and Strategy', 'Beratung und Strategie', 'Consultoría y Estrategia', 'Consulenza e Strategia', 'Advies en Strategie', 'Consultoria e Estratégia', 'Conseil en management, stratégie d\'entreprise, transformation digitale'),
('Recherche et Développement', 'Recherche et Développement', 'Research and Development', 'Forschung und Entwicklung', 'Investigación y Desarrollo', 'Ricerca e Sviluppo', 'Onderzoek en Ontwikkeling', 'Pesquisa e Desenvolvimento', 'R&D, innovation, laboratoires de recherche'),
('Services à la personne', 'Services à la personne', 'Personal Services', 'Personenbezogene Dienstleistungen', 'Servicios Personales', 'Servizi alla Persona', 'Persoonlijke Diensten', 'Serviços Pessoais', 'Aide à domicile, soins à la personne, services de proximité'),
('Sécurité et Défense', 'Sécurité et Défense', 'Security and Defense', 'Sicherheit und Verteidigung', 'Seguridad y Defensa', 'Sicurezza e Difesa', 'Beveiliging en Defensie', 'Segurança e Defesa', 'Sécurité privée, gardiennage, défense nationale'),
('Chimie et Pharmacie', 'Chimie et Pharmacie', 'Chemistry and Pharmaceuticals', 'Chemie und Pharmazie', 'Química y Farmacia', 'Chimica e Farmacia', 'Chemie en Farmacie', 'Química e Farmácia', 'Industrie chimique, production pharmaceutique, biotechnologie'),
('Textile et Mode', 'Textile et Mode', 'Textile and Fashion', 'Textil und Mode', 'Textil y Moda', 'Tessile e Moda', 'Textiel en Mode', 'Têxtil e Moda', 'Industrie textile, mode, habillement, luxe'),
('Sport et Loisirs', 'Sport et Loisirs', 'Sports and Leisure', 'Sport und Freizeit', 'Deporte y Ocio', 'Sport e Tempo Libero', 'Sport en Recreatie', 'Esporte e Lazer', 'Équipements sportifs, clubs de sport, loisirs, fitness'),
('Administration publique', 'Administration publique', 'Public Administration', 'Öffentliche Verwaltung', 'Administración Pública', 'Amministrazione Pubblica', 'Openbaar Bestuur', 'Administração Pública', 'Services publics, collectivités territoriales, fonction publique'),
('Artisanat', 'Artisanat', 'Crafts and Trades', 'Handwerk', 'Artesanía', 'Artigianato', 'Ambacht', 'Artesanato', 'Métiers d\'art, artisanat traditionnel, métiers manuels'),
('Autres services', 'Autres services', 'Other Services', 'Sonstige Dienstleistungen', 'Otros Servicios', 'Altri Servizi', 'Andere Diensten', 'Outros Serviços', 'Services divers non classés ailleurs');

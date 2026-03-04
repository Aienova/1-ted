-- Table des prix AIECoins (AIECourt)
-- Les prix varient selon le pays, le secteur d'activité, l'action et le rôle

CREATE TABLE IF NOT EXISTS `aiecourt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) DEFAULT NULL COMMENT 'ID du secteur d\'activité (peut être NULL pour prix par défaut)',
  `country_id` int(11) DEFAULT NULL COMMENT 'ID du pays (peut être NULL pour prix par défaut)',
  `action_id` int(11) NOT NULL COMMENT 'ID de l\'action',
  `price` int(11) NOT NULL COMMENT 'Prix en AIECoins',
  `role` enum('candidate','recruiter','administrator') NOT NULL COMMENT 'Rôle concerné',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_aiecourt_activity` (`activity_id`),
  KEY `fk_aiecourt_country` (`country_id`),
  KEY `fk_aiecourt_action` (`action_id`),
  CONSTRAINT `fk_aiecourt_activity` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_aiecourt_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_aiecourt_action` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion des prix de base (sans pays/activité spécifique = prix par défaut)

-- Prix pour les candidats
INSERT INTO `aiecourt` (`activity_id`, `country_id`, `action_id`, `price`, `role`) VALUES
(NULL, NULL, 1, 5, 'candidate'),   -- Demander conseil communauté
(NULL, NULL, 2, 10, 'candidate'),  -- Message direct entreprise
(NULL, NULL, 3, 20, 'candidate'),  -- Boost actif
(NULL, NULL, 4, 30, 'candidate'),  -- Voir détails offre
(NULL, NULL, 5, 40, 'candidate'),  -- Boost important
(NULL, NULL, 6, 60, 'candidate'),  -- Boost urgent

-- Prix pour les entreprises/recruteurs
(NULL, NULL, 7, 5, 'recruiter'),   -- Voir profil Apprenti
(NULL, NULL, 8, 10, 'recruiter'),  -- Voir profil Junior
(NULL, NULL, 9, 20, 'recruiter'),  -- Voir profil Confirmé
(NULL, NULL, 10, 40, 'recruiter'), -- Voir profil Senior
(NULL, NULL, 11, 20, 'recruiter'), -- Boost offre active
(NULL, NULL, 12, 40, 'recruiter'), -- Boost offre importante
(NULL, NULL, 13, 60, 'recruiter'); -- Boost offre urgente

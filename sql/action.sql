-- Table des actions possibles pour le système AIECoins

CREATE TABLE IF NOT EXISTS `action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Nom de l\'action',
  `description` text DEFAULT NULL COMMENT 'Description de l\'action',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Action active ou non',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion des actions de base pour les candidats
INSERT INTO `action` (`name`, `description`, `is_active`) VALUES
('candidate_community_advice', 'Demander des conseils ou avis à la communauté', 1),
('candidate_direct_message', 'Envoyer un message direct à l\'entreprise', 1),
('candidate_boost_active', 'Propulser sa candidature en haut de la liste (recherche activement)', 1),
('candidate_view_job_details', 'Afficher les informations complètes d\'une offre d\'emploi', 1),
('candidate_boost_important', 'Propulser sa candidature en haut de la liste (recherche importante)', 1),
('candidate_boost_urgent', 'Propulser sa candidature en haut de la liste (recherche urgente)', 1),

-- Actions pour les entreprises
('company_view_profile_apprentice', 'Afficher les informations complètes d\'un profil de candidat (Apprenti)', 1),
('company_view_profile_junior', 'Afficher les informations complètes d\'un profil de candidat (Junior)', 1),
('company_view_profile_confirmed', 'Afficher les informations complètes d\'un profil de candidat (Confirmé)', 1),
('company_view_profile_senior', 'Afficher les informations complètes d\'un profil de candidat (Senior)', 1),
('company_boost_job_active', 'Mettre en avant une offre d\'emploi (Recherche active)', 1),
('company_boost_job_important', 'Mettre en avant une offre d\'emploi (Recherche importante)', 1),
('company_boost_job_urgent', 'Mettre en avant une offre d\'emploi (Recherche urgente)', 1);

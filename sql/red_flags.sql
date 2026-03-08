-- Table pour stocker les signalements d'entreprises
-- Cette table permet de garder un historique complet des signalements
-- même si la candidature est supprimée

CREATE TABLE IF NOT EXISTS `red_flags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL COMMENT 'ID de la candidature concernée',
  `company_id` int(11) NOT NULL COMMENT 'ID de l\'entreprise signalée',
  `candidate_id` int(11) NOT NULL COMMENT 'ID du candidat qui signale',
  `subject` varchar(255) NOT NULL COMMENT 'Motif du signalement',
  `reason` text NOT NULL COMMENT 'Description détaillée du problème',
  `status` enum('pending','investigating','resolved','dismissed') DEFAULT 'pending' COMMENT 'Statut de l\'enquête',
  `resolution_notes` text DEFAULT NULL COMMENT 'Notes de résolution après enquête',
  `resolved_by` varchar(100) DEFAULT NULL COMMENT 'Membre de l\'équipe ayant résolu',
  `resolved_at` timestamp NULL DEFAULT NULL COMMENT 'Date de résolution',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date du signalement',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_application` (`application_id`),
  KEY `idx_company` (`company_id`),
  KEY `idx_candidate` (`candidate_id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Signalements d\'entreprises par les candidats';

-- Index pour recherche rapide par statut et entreprise
CREATE INDEX idx_company_status ON red_flags(company_id, status);

-- Index pour recherche par date
CREATE INDEX idx_pending_created ON red_flags(status, created_at) WHERE status = 'pending';

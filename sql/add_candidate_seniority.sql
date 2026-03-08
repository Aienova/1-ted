-- Ajouter la colonne seniority à la table candidate si elle n'existe pas déjà

-- Vérifier si la colonne existe et l'ajouter si nécessaire
ALTER TABLE `candidate` 
ADD COLUMN IF NOT EXISTS `seniority` INT NULL DEFAULT NULL 
COMMENT 'Niveau de séniorité du candidat (0=apprenti, 1=junior, 2=confirmé, 3=senior)' 
AFTER `priority`;

-- Créer un index pour améliorer les performances de recherche
CREATE INDEX IF NOT EXISTS `idx_candidate_seniority` ON `candidate` (`seniority`);

-- Note: Les valeurs possibles pour seniority sont:
-- - 0 : Apprenti / Stagiaire
-- - 1 : Junior (0-2 ans)
-- - 2 : Confirmé (2-5 ans)
-- - 3 : Senior (5+ ans)
-- - NULL : Non spécifié

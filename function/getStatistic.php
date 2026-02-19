<?php
/**
 * Fonctions de récupération de statistiques pour le dashboard administrateur
 */

require_once __DIR__ . '/connectionDatabase.php';

/**
 * Récupère le nombre d'inscriptions par mois pour une année donnée
 * @param int $year Année à analyser
 * @return array Tableau associatif [mois => nombre] (1-12)
 */
function getRegistrationsByMonth($year = 2026) {
    try {
        $db = getDatabaseConnection();
        
        $query = "SELECT 
                    MONTH(created_at) as month, 
                    COUNT(*) as count 
                  FROM account 
                  WHERE YEAR(created_at) = :year 
                  GROUP BY MONTH(created_at)
                  ORDER BY month";
        
        $stmt = $db->prepare($query);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Initialiser tous les mois à 0
        $monthlyData = array_fill(1, 12, 0);
        
        // Remplir avec les données récupérées
        foreach ($results as $row) {
            $monthlyData[(int)$row['month']] = (int)$row['count'];
        }
        
        return $monthlyData;
        
    } catch (PDOException $e) {
        error_log("Erreur getRegistrationsByMonth: " . $e->getMessage());
        return array_fill(1, 12, 0);
    }
}

/**
 * Récupère le nombre total de candidats approuvés
 * @return int Nombre de candidats approuvés
 */
function getTotalApprovedCandidates() {
    try {
        $db = getDatabaseConnection();
        
        $query = "SELECT COUNT(*) as count FROM candidate WHERE approved = 1";
        $stmt = $db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)$result['count'];
        
    } catch (PDOException $e) {
        error_log("Erreur getTotalApprovedCandidates: " . $e->getMessage());
        return 0;
    }
}

/**
 * Récupère le nombre total d'entreprises approuvées
 * @return int Nombre d'entreprises approuvées
 */
function getTotalApprovedCompanies() {
    try {
        $db = getDatabaseConnection();
        
        // Vérifier si la colonne approved existe dans company
        $query = "SELECT COUNT(*) as count FROM company WHERE approved = 1";
        
        try {
            $stmt = $db->query($query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'];
        } catch (PDOException $e) {
            // Si la colonne approved n'existe pas, retourner le total
            $query = "SELECT COUNT(*) as count FROM company";
            $stmt = $db->query($query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'];
        }
        
    } catch (PDOException $e) {
        error_log("Erreur getTotalApprovedCompanies: " . $e->getMessage());
        return 0;
    }
}

/**
 * Récupère le nombre total d'offres d'emploi approuvées
 * @return int Nombre d'offres d'emploi approuvées
 */
function getTotalApprovedJobs() {
    try {
        $db = getDatabaseConnection();
        
        $query = "SELECT COUNT(*) as count FROM job WHERE approved = 1";
        $stmt = $db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)$result['count'];
        
    } catch (PDOException $e) {
        error_log("Erreur getTotalApprovedJobs: " . $e->getMessage());
        return 0;
    }
}

/**
 * Récupère le nombre de demandes d'approbation d'offres d'emploi en attente
 * @return array ['count' => int, 'hasRecent' => bool]
 */
function getPendingJobApprovals() {
    try {
        $db = getDatabaseConnection();
        
        // Nombre total en attente
        $query = "SELECT COUNT(*) as count FROM job WHERE approved IS NULL";
        $stmt = $db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = (int)$result['count'];
        
        // Vérifier s'il y a des demandes récentes (moins de 7 jours)
        $query = "SELECT COUNT(*) as count FROM job 
                  WHERE approved IS NULL 
                  AND created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $stmt = $db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $hasRecent = (int)$result['count'] > 0;
        
        return [
            'count' => $count,
            'hasRecent' => $hasRecent
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur getPendingJobApprovals: " . $e->getMessage());
        return ['count' => 0, 'hasRecent' => false];
    }
}

/**
 * Récupère le nombre de demandes d'approbation de CV en attente
 * @return array ['count' => int, 'hasRecent' => bool]
 */
function getPendingCandidateApprovals() {
    try {
        $db = getDatabaseConnection();
        
        // Nombre total en attente
        $query = "SELECT COUNT(*) as count FROM candidate WHERE approved IS NULL";
        $stmt = $db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = (int)$result['count'];
        
        // Vérifier s'il y a des demandes récentes (moins de 7 jours)
        $query = "SELECT COUNT(*) as count FROM candidate 
                  WHERE approved IS NULL 
                  AND created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $stmt = $db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $hasRecent = (int)$result['count'] > 0;
        
        return [
            'count' => $count,
            'hasRecent' => $hasRecent
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur getPendingCandidateApprovals: " . $e->getMessage());
        return ['count' => 0, 'hasRecent' => false];
    }
}

/**
 * Récupère le nombre total de refus d'approbation (CV + offres d'emploi)
 * @return int Nombre total de refus
 */
function getTotalRejections() {
    try {
        $db = getDatabaseConnection();
        
        // Nombre de candidats refusés
        $query = "SELECT COUNT(*) as count FROM candidate WHERE approved = 0";
        $stmt = $db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $rejectedCandidates = (int)$result['count'];
        
        // Nombre d'offres d'emploi refusées
        $query = "SELECT COUNT(*) as count FROM job WHERE approved = 0";
        $stmt = $db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $rejectedJobs = (int)$result['count'];
        
        return $rejectedCandidates + $rejectedJobs;
        
    } catch (PDOException $e) {
        error_log("Erreur getTotalRejections: " . $e->getMessage());
        return 0;
    }
}

?>

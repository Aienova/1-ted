<?php

function deleteJobOffer($jobOfferId) {
    try {
        $pdo = getDatabaseConnection();
        
        // Vérifier que l'offre d'emploi existe
        $stmt = $pdo->prepare("SELECT * FROM job_offer WHERE id = ?");
        $stmt->execute([$jobOfferId]);
        $jobOffer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$jobOffer) {
            return ['success' => false, 'message' => 'Offre d\'emploi introuvable'];
        }
        
        // Supprimer les compétences associées à l'offre d'emploi
        $stmt = $pdo->prepare("DELETE FROM job_offer_skill WHERE job_offer_id = ?");
        $stmt->execute([$jobOfferId]);
        
        // Supprimer l'offre d'emploi
        $stmt = $pdo->prepare("DELETE FROM job_offer WHERE id = ?");
        $stmt->execute([$jobOfferId]);
        
        return ['success' => true, 'message' => 'Offre d\'emploi supprimée avec succès'];
        
    } catch (PDOException $e) {
        error_log("Erreur lors de la suppression de l'offre d'emploi ID $jobOfferId: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erreur lors de la suppression de l\'offre d\'emploi'];
    }
}
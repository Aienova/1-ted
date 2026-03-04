<?php

function checkOfferIfAlreadyApplied($offerId) {
    if (!isset($_SESSION['accountId']) || $_SESSION['role'] !== 'candidate') {
        return false; // Non connecté ou pas candidat
    }
    
    $db = getDatabaseConnection();
    
    $stmt = $db->prepare("
        SELECT 1
        FROM application app
        JOIN account a ON app.account_id = a.id
        WHERE app.offer_id = :offer_id AND a.id = :account_id
        LIMIT 1
    ");
    $stmt->execute([
        ':offer_id' => $offerId,
        ':account_id' => $_SESSION['accountId']
    ]);
    
    return $stmt->fetchColumn() !== false; // Retourne true si une ligne est trouvée, sinon false
}
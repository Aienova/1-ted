<?php

    function getAvatar($candidateId) {
        // Récupérer l'avatar_url depuis la base de données
        $db = getDatabaseConnection();
        $stmt = $db->prepare("SELECT avatar_url FROM candidate WHERE id = :candidateId");
        $stmt->bindParam(':candidateId', $candidateId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && !empty($result['avatar_url'])) {
            return $result['avatar_url'];
        } else {
            // Retourner l'URL de l'image par défaut si aucun avatar n'est défini
            return '/data/candidates/media/default/photo.jpg';
        }
    }
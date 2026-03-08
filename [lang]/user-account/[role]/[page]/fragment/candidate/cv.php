<?php
/**
 * Page CV du candidat - Affiche le CV complet avec éditeur
 */

// Récupérer les fonctions nécessaires
require_once __DIR__ . '/../../../../../../function/getData.php';
require_once __DIR__ . '/../../../../../../function/connectionDatabase.php';

try {
    // Vérifier l'authentification
    if (!isset($_SESSION['accountId']) || !isset($_SESSION['role'])) {
        echo '<div style="padding: 2rem; text-align: center; color: #ef4444;">
                <h2>⚠️ Non authentifié</h2>
                <p>Vous devez être connecté pour accéder à cette page.</p>
              </div>';
        exit;
    }
    
    if ($_SESSION['role'] !== 'candidate') {
        echo '<div style="padding: 2rem; text-align: center; color: #ef4444;">
                <h2>⚠️ Accès refusé</h2>
                <p>Cette page est réservée aux candidats.</p>
              </div>';
        exit;
    }
    
    $db = getDatabaseConnection();
    
    // Récupérer l'ID du candidat depuis la session ou la base de données
    if (isset($_SESSION['candidate_id'])) {
        $candidateId = $_SESSION['candidate_id'];
    } else {
        $stmt = $db->prepare("SELECT candidate_id FROM account WHERE id = :account_id");
        $stmt->execute([':account_id' => $_SESSION['accountId']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $candidateId = $result['candidate_id'] ?? null;
        
        if ($candidateId) {
            $_SESSION['candidate_id'] = $candidateId;
        }
    }
    
    // Si aucun candidat n'est associé, afficher le formulaire de création de CV
    if (!$candidateId) {
        // Définir les variables pour le contexte user-account
        $isUserAccountContext = true;
        $userAccountId = $_SESSION['accountId'];
        ?>
        
        <script>
            // Définir le contexte global pour que add-cv.js puisse le détecter
            window.userAccountContext = {
                isUserAccount: true,
                accountId: <?php echo json_encode($userAccountId); ?>
            };
            console.log('🔧 Contexte user-account défini:', window.userAccountContext);
        </script>
        
        <?php
        echo '<div class="cv-creation-container" style="max-width: 1200px; margin: 0 auto;">';
        include __DIR__ . '/../../../../../../data/candidates/add-cv.php';
        echo '</div>';
        ?>
        
        <script>
            // Écouter l'événement de création réussie du CV pour rafraîchir la page
            window.addEventListener('cvCreatedSuccess', function(e) {
                console.log('✅ CV créé avec succès, rafraîchissement de la page...', e.detail);
                setTimeout(() => {
                    location.reload();
                }, 500);
            });
        </script>
        
        <?php
    } else {
        // Charger l'objet candidat complet
        $candidate = getData('candidate', $candidateId);
        
        if (!$candidate) {
            echo '<div style="padding: 2rem; text-align: center; color: #ef4444;">
                    <h2>⚠️ Erreur</h2>
                    <p>Impossible de charger vos données de CV.</p>
                  </div>';
        } else {
            // Inclure le fichier edit-cv.php qui contient la fonction importCandidate
            include __DIR__ . '/../../../../../../data/candidates/edit-cv.php';
            
            // Appeler la fonction pour afficher le CV (isUserAccount = true car on est dans l'espace compte)
            importCandidate($candidate, true);
        }
    }
    
} catch (Exception $e) {
    echo '<div style="padding: 2rem; text-align: center; color: #ef4444;">
            <h2>⚠️ Erreur</h2>
            <p>Une erreur s\'est produite lors du chargement du CV: ' . htmlspecialchars($e->getMessage()) . '</p>
          </div>';
}
?>

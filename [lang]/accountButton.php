<?php
// Le bouton de compte utilisateur - affiché uniquement si l'utilisateur est connecté
if (isset($_SESSION['token']) && isset($_SESSION['role'])): 
    $userLogin = isset($_SESSION['login']) ? $_SESSION['login'] : 'Utilisateur';
    $userRole = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'User';
    
    // Rafraîchir les aiecoins depuis la base de données pour avoir la valeur à jour
    require_once __DIR__ . '/../function/updateAiecoins.php';
    if (isset($_SESSION['accountId'])) {
        refreshAiecoinsInSession($_SESSION['accountId']);
    }
    $userAiecoins = isset($_SESSION['aiecoins']) ? $_SESSION['aiecoins'] : 0;
    
    // Définir les traductions des rôles
    $roleTranslations = [
        'fr' => [
            'administrator' => 'Admin',
            'candidate' => 'Candidat',
            'recruiter' => 'Recruteur'
        ],
        'en' => [
            'administrator' => 'Admin',
            'candidate' => 'Candidate',
            'recruiter' => 'Recruiter'
        ]
    ];
    
    // Traduire le rôle si possible
    if (isset($roleTranslations[$lang][$_SESSION['role']])) {
        $userRole = $roleTranslations[$lang][$_SESSION['role']];
    }
    
    // Déterminer l'URL du profil utilisateur en fonction du rôle
    $profileUrl = '/' . $lang . '/user-account/' . $_SESSION['role'] . '/profile';
    
    // Récupérer la photo du candidat si le rôle est candidate
    $userPhoto = '/media/image/icon/avatar/default.png';
    
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'candidate') {
        try {
            require_once __DIR__ . '/../function/connectionDatabase.php';
            $db = getDatabaseConnection();
            
            // Récupérer le candidate_id et media_code
            $stmt = $db->prepare("
                SELECT c.media_code 
                FROM account a
                LEFT JOIN candidate c ON a.candidate_id = c.id
                WHERE a.id = :account_id
            ");
            $stmt->execute([':account_id' => $_SESSION['accountId']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && !empty($result['media_code'])) {
                $mediaCode = $result['media_code'];
                $cacheVersion = time(); // Cache-busting
                $candidatePhotoUrl = '/data/candidates/media/' . $mediaCode . '/photo.jpg?v=' . $cacheVersion;
                
                // Vérifier si le fichier existe sur le serveur
                $photoFilePath = __DIR__ . '/../data/candidates/media/' . $mediaCode . '/photo.jpg';
                if (file_exists($photoFilePath)) {
                    $userPhoto = $candidatePhotoUrl;
                }
            }
        } catch (Exception $e) {
            // En cas d'erreur, on garde l'avatar par défaut
            error_log('Erreur lors du chargement de la photo du candidat: ' . $e->getMessage());
        }
    }
?>
    <a href="<?php echo $profileUrl; ?>" class="account-button">
        <div class="account-button-photo">
            <img src="<?php echo $userPhoto; ?>" alt="<?php echo htmlspecialchars($userLogin); ?>">
        </div>
        <div class="account-button-info">
            <span class="account-login"><?php echo htmlspecialchars($userLogin); ?> - <?php echo htmlspecialchars($userRole); ?></span>
            <span class="account-credits">Aiecoins : <?php echo "Illimité" // echo htmlspecialchars($userAiecoins); ?></span>
        </div>
    </a>
<?php endif; ?>

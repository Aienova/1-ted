<?php
// Le bouton de compte utilisateur - affiché uniquement si l'utilisateur est connecté
if (isset($_SESSION['token']) && isset($_SESSION['role'])): 
    $userLogin = isset($_SESSION['login']) ? $_SESSION['login'] : 'Utilisateur';
    $userRole = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'User';
    $userCredits = isset($_SESSION['credits']) ? $_SESSION['credits'] : 0;
    
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
    
    // Déterminer le chemin de la photo (à améliorer avec une vraie photo de profil)
    $userPhoto = '/media/image/icon/avatar/default.png';
?>
    <a href="<?php echo $profileUrl; ?>" class="account-button">
        <div class="account-button-photo">
            <img src="<?php echo $userPhoto; ?>" alt="<?php echo htmlspecialchars($userLogin); ?>">
        </div>
        <div class="account-button-info">
            <span class="account-login"><?php echo htmlspecialchars($userLogin); ?> - <?php echo htmlspecialchars($userRole); ?></span>
            <span class="account-credits">Credit : <?php echo htmlspecialchars($userCredits); ?></span>
        </div>
    </a>
<?php endif; ?>

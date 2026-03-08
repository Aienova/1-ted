<?php
// Récupérer la photo du candidat si le rôle est candidate
$userAvatarUrl = $baseUrl . '/media/image/icon/avatar/default.png';

if (isset($_SESSION['role']) && $_SESSION['role'] === 'candidate') {
    try {
        require_once __DIR__ . '/../../../../function/connectionDatabase.php';
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
            $candidatePhotoPath = $baseUrl . '/data/candidates/media/' . $mediaCode . '/photo.jpg?v=' . $cacheVersion;
            
            // Vérifier si le fichier existe sur le serveur
            $photoFilePath = __DIR__ . '/../../../../data/candidates/media/' . $mediaCode . '/photo.jpg';
            if (file_exists($photoFilePath)) {
                $userAvatarUrl = $candidatePhotoPath;
            }
        }
    } catch (Exception $e) {
        // En cas d'erreur, on garde l'avatar par défaut
        error_log('Erreur lors du chargement de la photo du candidat: ' . $e->getMessage());
    }
} elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'company') {
    try {
        require_once __DIR__ . '/../../../../function/connectionDatabase.php';
        $db = getDatabaseConnection();
        
        // Récupérer le company_id et media_code
        $stmt = $db->prepare("
            SELECT c.media_code 
            FROM account a
            LEFT JOIN company c ON a.company_id = c.id
            WHERE a.id = :account_id
        ");
        $stmt->execute([':account_id' => $_SESSION['accountId']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && !empty($result['media_code'])) {
            $mediaCode = $result['media_code'];
            $cacheVersion = time(); // Cache-busting
            $companyLogoPath = $baseUrl . '/data/company/media/' . $mediaCode . '/logo.png?v=' . $cacheVersion;
            
            // Vérifier si le fichier existe sur le serveur
            $logoFilePath = __DIR__ . '/../../../../data/company/media/' . $mediaCode . '/logo.png';
            if (file_exists($logoFilePath)) {
                $userAvatarUrl = $companyLogoPath;
            }
        }
    } catch (Exception $e) {
        // En cas d'erreur, on garde l'avatar par défaut
        error_log('Erreur lors du chargement du logo de la société: ' . $e->getMessage());
    }
}

$today = new DateTimeImmutable('today');
$targetDate = new DateTimeImmutable('2026-04-23');
$dayleft = (int) $today->diff($targetDate)->format('%r%a');
$dayleft = max(0, $dayleft);
?>
<menu class="menu-user-space">

       <!-- Logo -->
        <div class="user-space-logo">
            <a href="/<?php echo $lang; ?>" class="back-home-link" style="background-image: linear-gradient(135deg, #1e93e5 0%, #4b97a2 100%);">← Retour à l'accueil</a>
            <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>">
                <img src="<?php echo $baseUrl; ?>/media/image/logo.png" alt="1-TED Logo">
            </a>
            
        </div>
        

        <div class="user-info">
        <div class="user-photo">
            <img src="<?php echo $userAvatarUrl; ?>" alt="User Avatar">
        </div>
        <div class="user-text-info">
            <span><?php echo htmlspecialchars($userName); ?> :</span> <span style="color: blue;"> <?php echo htmlspecialchars($userRole); ?></span><span>Aiecoins : <span><br><span style="color: blue;"><?php echo "Illimité (" . $dayleft . " jours)"; ?></span>
        </div>
        </div>

        <!-- Menu latéral -->
        <aside class="user-space-menu">
            <nav>
                <ul>
                    <?php foreach ($menuPages as $menuPage): ?>
                        <?php 
                        $isActive = ($menuPage['name'] === $page) ? 'active' : '';
                        $menuLink = $baseUrl . '/' . $lang . $menuPage['link'];
                        ?>
                        <li class="<?php echo $isActive; ?>">
                            <a href="<?php echo $menuLink; ?>">
                                <?php echo htmlspecialchars($menuPage['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li class="menu-logout">
                        <a href="#" id="logoutBtn">Déconnexion</a>
                    </li>
                </ul>
            </nav>
        </aside>
</menu>

<?php include __DIR__ . '/../../../loader.php'; ?>
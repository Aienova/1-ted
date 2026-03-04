<?php

require_once __DIR__ . '/../../../function/getData.php';
require_once __DIR__ . '/../../../entity/job/job.php';

// Démarrer la session si ce n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et a le droit de voir le statut de validation
// Le validation-notice ne s'affiche que si context=user-account est passé en paramètre
$isUserAccount = false;
if (isset($_SESSION['accountId']) && isset($_SESSION['role']) && isset($_GET['context']) && $_GET['context'] === 'user-account') {
    $userRole = $_SESSION['role'];
    // Afficher le statut de validation pour les admins et les entreprises
    if ($userRole === 'admin' || $userRole === 'company' || $userRole === 'recruiter') {
        $isUserAccount = true;
    }
}

$id = $_GET['id'] ?? 1;
$isModal = isset($_GET['modal']) && $_GET['modal'] === 'true';

// Vérifier si c'est une création ou une édition
if ($id === 'add') {
    include '../add-job-offer.php';
    $job = null;
} else {
    include '../edit-job-offer.php';
    $job = getData('job', $id);
}

// Si mode modal, retourner uniquement le contenu
if ($isModal) {
    if ($job) {
        importJob($job, $isUserAccount);
    } else {
        echo "<h1>Offre d'emploi non trouvée</h1>";
        echo "<p>L'offre d'emploi avec l'ID $id n'existe pas.</p>";
    }
    
    // Ajouter les scripts nécessaires
    echo '<link rel="stylesheet" href="/style/joboffer.css">';
    echo '<link rel="stylesheet" href="/style/editor.css">';
    echo '<script src="/script/config.js"></script>';
    echo '<script src="/script/editor.js"></script>';
    echo '<script src="/script/forms/job-title.js"></script>';
    echo '<script src="/script/forms/job-basic-info.js"></script>';
    echo '<script src="/script/forms/job-description.js"></script>';
    echo '<script src="/script/forms/job-conditions.js"></script>';
    echo '<script src="/script/forms/job-requirements.js"></script>';
    echo '<script src="/script/forms/job-company-info.js"></script>';
    echo '<script src="/script/forms/job-contact.js"></script>';
    echo '<script src="/script/convertJobToHTML.js"></script>';
    echo '<script src="/script/save-job-offer.js"></script>';
    echo '<script src="/script/reset.js"></script>';
    echo '<script src="/script/print.js"></script>';
    exit;
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $job ? htmlspecialchars($job->getTitle()) : 'Offre d\'emploi'; ?> - TED Backoffice</title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/style.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/joboffer.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/editor.css">
</head>

        <body>
                <?php
                    if ($job) {
                        importJob($job, $isUserAccount);
                    } else {
                        echo "<h1>Offre d'emploi non trouvée</h1>";
                        echo "<p>L'offre d'emploi avec l'ID $id n'existe pas.</p>";
                    }
            ?>
        </body>

<script src="<?php echo $baseUrl; ?>/script/config.js"></script>
<script src="<?php echo $baseUrl; ?>/script/editor.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-title.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-basic-info.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-description.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-conditions.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-requirements.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-company-info.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-contact.js"></script>
<script src="<?php echo $baseUrl; ?>/script/convertJobToHTML.js"></script>
<?php if ($id === 'add'): ?>
<script src="<?php echo $baseUrl; ?>/script/add-job-offer.js"></script>
<script src="<?php echo $baseUrl; ?>/script/insertTestDataJob.js"></script>
<?php else: ?>
<script src="<?php echo $baseUrl; ?>/script/save-job-offer.js"></script>
<?php endif; ?>
<script src="<?php echo $baseUrl; ?>/script/reset.js"></script>
<script src="<?php echo $baseUrl; ?>/script/print.js"></script>



</html>

<?php
/**
 * Endpoint pour afficher une offre d'emploi dans une popup
 * Accessible via: /function/getViewerJob.php?id=X
 * Utilise edit-job-offer.php pour afficher l'offre complète avec l'éditeur
 */

// Charger la configuration
require_once __DIR__ . '/../security.php';
require_once __DIR__ . '/getData.php';

// Récupérer l'ID de l'offre d'emploi
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    echo '<div class="error-message" style="padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 8px; text-align: center;">ID d\'offre d\'emploi manquant</div>';
    exit;
}

// Vérifier si on est dans l'espace utilisateur (user-account)
// Le validation-notice ne doit s'afficher que pour admin/company/recruiter dans user-account
$isUserAccount = false;
if (isset($_SESSION['accountId']) && isset($_SESSION['role']) && isset($_GET['context']) && $_GET['context'] === 'user-account') {
    $userRole = $_SESSION['role'];
    // Afficher le statut de validation pour les admins et les entreprises
    if ($userRole === 'admin' || $userRole === 'company' || $userRole === 'recruiter') {
        $isUserAccount = true;
    }
}

// Include edit-job-offer.php pour avoir la fonction importJob
include __DIR__ . '/../data/jobs/edit-job-offer.php';

// Récupérer les données de l'offre d'emploi
$job = getData('job', $id);

if (!$job) {
    echo '<div class="error-message" style="padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 8px; text-align: center;">Offre d\'emploi introuvable</div>';
    exit;
}
?>

<!-- Styles pour l'affichage de l'offre -->
<link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/style.css">
<link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/joboffer.css">

<?php
// Importer et afficher l'offre d'emploi (la fonction importJob affiche le HTML)
// $isUserAccount = true uniquement si dans user-account avec role admin/company/recruiter
importJob($job, $isUserAccount);
?>

<!-- Scripts nécessaires pour l'éditeur d'offre -->
<script src="<?php echo $baseUrl; ?>/script/config.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-title.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-basic-info.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-description.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-conditions.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-requirements.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-company-info.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-contact.js"></script>
<script src="<?php echo $baseUrl; ?>/script/save-job-offer.js"></script>
<script src="<?php echo $baseUrl; ?>/script/convertJobToHTML.js"></script>

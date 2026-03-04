<?php
/**
 * Endpoint pour afficher le CV d'un candidat dans une popup
 * Accessible via: /function/getViewerCV.php?id=X
 * Utilise edit-cv.php pour afficher le CV complet avec l'éditeur
 */

// Charger la configuration
require_once __DIR__ . '/../security.php';
require_once __DIR__ . '/getData.php';

// Récupérer l'ID du candidat
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    echo '<div class="error-message" style="padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 8px; text-align: center;">ID de candidat manquant</div>';
    exit;
}

// Include edit-cv.php pour avoir la fonction importCandidate
include __DIR__ . '/../data/candidates/edit-cv.php';

// Récupérer les données du candidat
$candidate = getData('candidate', $id);

if (!$candidate) {
    echo '<div class="error-message" style="padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 8px; text-align: center;">Candidat introuvable</div>';
    exit;
}
?>

<!-- Styles pour l'affichage du CV -->
<link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/style.css">
<link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/editor.css">

<?php
// Importer et afficher le CV (la fonction importCandidate affiche le HTML)
importCandidate($candidate, true);
?>

<!-- Scripts nécessaires pour l'éditeur de CV -->
<script src="<?php echo $baseUrl; ?>/script/config.js"></script>
<script src="<?php echo $baseUrl; ?>/script/editor.js"></script>
<script src="<?php echo $baseUrl; ?>/script/convertToHTML.js"></script>
<script src="<?php echo $baseUrl; ?>/script/save.js"></script>
<script src="<?php echo $baseUrl; ?>/script/print.js"></script>
<script src="<?php echo $baseUrl; ?>/script/reset.js"></script>
<script src="<?php echo $baseUrl; ?>/script/classify.js"></script>
<script src="<?php echo $baseUrl; ?>/script/declassify.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/cv-title.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/personal-info.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-info.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/description.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/skills.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/experience.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/formation.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/qrcode.js"></script>

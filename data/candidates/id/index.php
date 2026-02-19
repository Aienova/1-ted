<?php

require_once __DIR__ . '/../../../function/getData.php';



$id = $_GET['id'] ?? 1;

// Vérifier si on est en mode ajout ou édition
if ($id === 'add') {
    // Mode création de CV
    include '../add-cv.php';
} else {
    // Mode édition de CV
    include '../edit-cv.php';
    $candidate = getData('candidate', $id);
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV - Apportage d'affaire</title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/style.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/editor.css">


</head>


<?php
  // Appeler la fonction d'import uniquement en mode édition
  if ($id !== 'add') {
      importCandidate($candidate);
  }
?>


<script src="<?php echo $baseUrl; ?>/script/config.js"></script>
<script src="<?php echo $baseUrl; ?>/script/editor.js"></script>
<script src="<?php echo $baseUrl; ?>/script/convertToHTML.js"></script>
<script src="<?php echo $baseUrl; ?>/script/save.js"></script>
<?php if ($id === 'add'): ?>
    <script src="<?php echo $baseUrl; ?>/script/add.js"></script>
    <script src="<?php echo $baseUrl; ?>/script/insertTestData.js"></script>
<?php else: ?>
    <script src="<?php echo $baseUrl; ?>/script/save.js"></script>
<?php endif; ?>
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

</html>


<?php
// Charger le contenu HTML depuis le dossier content
if ($pageContent) {
    $contentPath = PROJECT_ROOT . '/data/text/' . $lang . '/content/' . $pageContent;
    
    if (file_exists($contentPath)) {
        include $contentPath;
    } else {
        echo '<section class="container"><p>Contenu non trouvé pour cette page.</p></section>';
    }
} else {
    echo '<section class="container"><p>Aucun contenu défini pour cette page.</p></section>';
}
?>

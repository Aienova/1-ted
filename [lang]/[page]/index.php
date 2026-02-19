<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<?php include '../info.php'; ?>
<body>
    <?php include '../header.php'; ?>

    <div class="mini-banner">
        <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
    </div>

    <?php 
    // Charger le contenu HTML ou le fragment PHP selon le type de page
    if ($isSpecificPage && $pageFragment) {
        // Page spécifique : charger le fragment PHP
        $fragmentPath = __DIR__ . '/fragment/' . $pageFragment;
        if (file_exists($fragmentPath)) {
            include $fragmentPath;
        } else {
            echo '<section class="container"><p>Fragment non trouvé.</p></section>';
        }
    } elseif ($pageContent) {
        // Page statique : charger le contenu HTML
        include '../content.php';
    } else {
        echo '<section class="container"><p>Contenu non disponible.</p></section>';
        echo "content:".$pageContent;
    }
    ?>

    <?php include '../footer.php'; ?>
</body>
</html>
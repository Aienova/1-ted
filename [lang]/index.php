<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<?php include 'info.php'; ?>
<body>
    <?php include 'header.php'; ?>


    <div class="banner">

        <video autoplay muted loop>
            <source src="/media/video/banner.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <h1><?php echo htmlspecialchars($bannerTitle); ?></h1>
        <?php include 'search.php'; ?>
    </div>



    <?php include 'footer.php'; ?>
</body>
</html>
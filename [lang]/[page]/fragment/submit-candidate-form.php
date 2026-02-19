<section class="submit-candidate-section">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/registerPopup.css">
    
    <div class="container">
        <div class="form-intro">
            <h2>Soumettre votre candidature</h2>
            <p>Remplissez le formulaire ci-dessous pour créer votre profil et être visible auprès de milliers d'entreprises à la recherche de talents comme vous.</p>
        </div>

        <div id="job-offer-selected"></div>

         <?php include PROJECT_ROOT . '/data/candidates/add-cv.php'; ?>

    </div>

        <script src="<?php echo $baseUrl; ?>/script/config.js"></script>
        <script src="<?php echo $baseUrl; ?>/script/selected-job.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/cv-title.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/personal-info.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-info.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/experience.js"></script>
<script src="<?php echo $baseUrl; ?>/script/convertToHTML.js"></script>
<script src="<?php echo $baseUrl; ?>/script/register.js"></script>
<script src="<?php echo $baseUrl; ?>/script/add-cv.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/skills.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/formation.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/description.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/qrcode.js"></script>
</section>

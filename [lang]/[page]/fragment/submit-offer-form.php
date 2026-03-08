    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/joboffer.css">
<section class="submit-offer-section">
    <div class="container">
        <div class="form-intro">
            <h2>Déposer une offre d'emploi</h2>
            <p>Remplissez le formulaire ci-dessous pour publier votre offre d'emploi sur 1-TED et toucher des milliers de candidats qualifiés.</p>
                </div>
                <style>
                    #print-job-button{
                        
                    display: none;
                    }
                </style>
        <?php include PROJECT_ROOT . '/data/jobs/add-job-offer.php'; ?>

    </div>

    <script src="<?php echo $baseUrl; ?>/script/config.js"></script>
       <script src="<?php echo $baseUrl; ?>/script/reset.js"></script> 
    <script src="<?php echo $baseUrl; ?>/script/forms/job-title.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-basic-info.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-description.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-conditions.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-requirements.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-company-info.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-contact.js"></script>
<script src="<?php echo $baseUrl; ?>/script/add-job-offer.js"></script>
</section>





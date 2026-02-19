<?php

// Initialiser toutes les variables vides pour la création d'un nouveau CV
$candidateId = "";
$candidateIdentityId = "";
$candidateDemandId = "";
$candidateFirstName = "";
$candidateLastName = "";
$candidateJobTitle = "";
$candidateBirthdate = "";
$candidateLocation = "";
$candidateDegreeTitle = "";
$candidateDegreeId = ""; 
$candidateLanguages = [];
$candidatePersonalities = [];
$candidateHobbies = [];
$candidatePhone = "";
$candidateEmail = "";
$candidateIsVehicule = false;
$candidateContractType = "";
$candidateSalary = "";
$candidateDescription = "";  
$candidateLanguagesCollection = "[]";
$candidatePersonalitiesCollection = "[]";
$candidateHobbiesCollection = "[]";
$candidateKnowledgesCollection = "[]";
$candidateExperiencesCollection = "[]";
$candidateFormationsCollection = "[]";
$candidateSkills = [];
$candidateExperiences = [];
$candidateFormations = [];
$candidateMediaCode = "";
$photoUrl = "";
$qrcodeUrl = "";
?>


<body>

    <div class="button-container">

               <button id="test-cv-button" class="edit-cv-button">Préremplir le CV</button>
       <button id="reset-cv-button" class="edit-cv-button">Vider ce CV</button>
               <button id="add-cv-button" class="edit-cv-button">Soumettre ce CV</button>
      <!-- <button id="classify-cv-button" class="edit-cv-button">Classifier le CV</button> -->
     <!-- <button id="declassify-cv-button" style="display:none" class="edit-cv-button">Déclassifier le CV</button> -->
     <!--  <button id="print-cv-button" class="edit-cv-button">Imprimer ce CV</button> -->
    </div>
    <div class="cv-container" data-table="candidate" data-id="" data-mode="insert">
        <!-- Header -->
        <header class="header canEdit" data-editor="cv-title">
            <div class="header-left">
                <div class="profile-photo" data-table="candidate" data-column="media_code" data-value="" data-id="" style="background-image: url('');background-size: cover;" data-classified="true"></div>
            </div>
            <div class="header-right">
                <h1 class="title" ><span class="dataToEdit" data-table="identity" data-column="first_name" data-id="" data-value="" data-classified="false">Prénom</span> <span class="dataToEdit" data-table="identity" data-column="last_name" data-id="" data-value="" data-classified="true">Nom</span></h1>
  <h1 class="title dataToEdit" data-table="identity" data-column="job_title" data-id="" data-value="" data-classified="false">Titre du poste</h1>
            </div>
        </header>

        <!-- Info Section -->
        <section class="info-section">
            <div class="personal-info canEdit" data-editor="personal-info">
                <p><strong>Age :</strong> <span class="placeholder dataToEdit" data-table="identity" data-column="birthdate" data-id="" data-value="" data-classified="false">--</span></p>
                <p><strong>Niveau d'étude :</strong> <span class="placeholder dataToEdit" data-table="identity" data-column="degree_id" data-id="" data-value="" data-classified="false">--</span></p>
                <p><strong>Langue(s) :</strong> <span class="placeholder multipleDataToEdit" data-table="identity" data-column="language_ids" data-collection="[]" data-id="" data-classified="false">--</span></p>
                <p><strong>Trait de personnalité :</strong> <span class="placeholder multipleDataToEdit" data-table="candidate" data-column="personality_ids" data-collection="[]" data-id=""  data-classified="false">--</span></p>
                <p><strong>Hobbies :</strong> <span class="placeholder multipleDataToEdit" data-table="identity" data-column="hobbie_ids" data-collection="[]" data-id="" data-classified="false">--</span></p>
            
            </div>
            <div class="job-info canEdit" data-editor="job-info">
                <p><strong>Localisation :</strong> <span class="placeholder dataToEdit" data-table="identity" data-column="location" data-id="" data-value="" data-classified="true">--</span></p>
                <p><strong>Téléphone :</strong> <span class="placeholder dataToEdit" data-table="identity" data-column="phone" data-id="" data-value="" data-classified="true">--</span></p>
                <p><strong>Email :</strong> <span class="placeholder dataToEdit" data-table="identity" data-column="email" data-id="" data-value="" data-classified="true">--</span></p>
                <p><strong>Véhicule :</strong> <span class="placeholder dataToEdit" data-table="identity" data-column="vehicule" data-id="" data-value="false" data-classified="false">Non</span></p>
                <p><strong>Type de contract :</strong> <span class="placeholder dataToEdit" data-table="demand" data-column="contract_type" data-id="" data-value="" data-classified="false">--</span></p>
                <p><strong>Prétention :</strong> <span class="placeholder dataToEdit" data-table="demand" data-column="salary" data-id="" data-value="" data-classified="false">-- €</span></p>
            </div>
        </section>

        <!-- Description and Skills -->
        <section class="main-content">
            <div class="description canEdit" data-editor="description">
                <h2>Description :</h2>
                <p class="dataToEdit" data-table="identity" data-column="description" data-value="" data-id="" data-classified="false">Description du candidat...</p>
            </div>
            <div class="skills canEdit" data-editor="skills">
                <h2>Compétences techniques :</h2>
                <ul>
                    <li style="color: #999;">Aucune compétence ajoutée</li>
                </ul>

            </div>
        </section>

        <!-- Experience and Formation -->
        <section class="experience-formation">
            <div class="experience canEdit dataToEdit" data-editor="experience" data-table="candidate" data-id="" data-column="experience_ids" data-value="[]">
                <h2>Expériences professionnels</h2>
                <p style="color: #999;">Aucune expérience ajoutée</p>
            </div>
            <div class="formation canEdit dataToEdit" data-editor="formation" data-table="candidate" data-id="" data-column="formation_ids" data-value="[]">
                <h2>Formations</h2>
                <p style="color: #999;">Aucune formation ajoutée</p>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer canEdit" data-editor="qrcode">
            <div class="footer-logo">
                <img src="<?php echo $baseUrl; ?>/media/image/aienova.png" alt="AIEE NOVA Logo">
            </div>
            <div class="footer-text">
                <p data-table="identity" data-column="footer_text" data-id="" data-classified="false">Pour plus d'informations sur ce profil, n'hésitez pas à consulter ce Qr Code, pour voir ses réseaux sociaux ou son site web : </p>
            </div>
            <div class="footer-qr" data-table="candidate" data-column="media_code" data-value="" data-id="">
                <img data-classified="true" src="<?php echo $baseUrl; ?>/media/image/qrcode.png" alt="QR Code">
            </div>
        </footer>
    </div>
</body>
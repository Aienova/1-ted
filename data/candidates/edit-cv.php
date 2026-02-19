<?php


function importCandidate($candidate){

    $baseUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    $candidateId = $candidate->getId();
    $candidateIdentityId = $candidate->getIdentity()->getId();
    $candidateDemandId = $candidate->getDemand()->getId();
    $candidateFirstName = $candidate->getIdentity()->getFirstName();
    $candidateLastName = $candidate->getIdentity()->getLastName();
    $candidateJobTitle = $candidate->getIdentity()->getJobTitle();
    $candidateBirthdate = $candidate->getIdentity()->getBirthdate();
    $candidateLocation = $candidate->getIdentity()->getLocation();
    $candidateDegreeTitle = $candidate->getIdentity()->getDegree()->getTitle();
    $candidateDegreeId = $candidate->getIdentity()->getDegree()->getId(); 
    $candidateIdentityId = $candidate->getIdentity()->getId();
    $candidateLanguages = $candidate->getIdentity()->getLanguages();
    $candidatePersonalities = $candidate->getPersonalities();
    $candidateHobbies = $candidate->getIdentity()->getHobbies();
    $candidateLocation = $candidate->getIdentity()->getLocation();
    $candidatePhone = $candidate->getIdentity()->getPhone();
    $candidateEmail = $candidate->getIdentity()->getEmail();
    $candidateIsVehicule = $candidate->getIdentity()->isVehicule();
    $candidateContractType = $candidate->getDemand()->getContractType();
    $candidateSalary = $candidate->getDemand()->getSalary();
    $candidateDemandId = $candidate->getDemand()->getId();
    $candidateDescription = $candidate->getIdentity()->getDescription();  
    $candidateLanguagesCollection = "[".implode(',', $candidate->getIdentity()->getLanguageIds())."]";
    $candidatePersonalitiesCollection = "[".implode(',', array_map(function($personality) { return $personality->getId(); }, $candidatePersonalities))."]";
    $candidateHobbiesCollection = "[".implode(',', $candidate->getIdentity()->getHobbieIds())."]";
    $candidateKnowledgesCollection = "[".implode(',', array_map(function($formation) { 
        return implode(',', array_map(function($knowledge) { 
            return $knowledge->getId(); 
        }, $formation->getKnowledges())); 
    }, $candidate->getFormations()))."]";

    $candidateExperiencesCollection = "[".implode(',', array_map(function($experience) { 
        return $experience->getId(); 
    }, $candidate->getExperiences()))."]";

    $candidateFormationsCollection = "[".implode(',', array_map(function($formation) { 
        return $formation->getId(); 
    }, $candidate->getFormations()))."]";
    
    $candidateMediaCode = $candidate->getMediaCode();
    $photoUrl = $candidate->getMediaCode() ? "/data/candidates/media/".$candidate->getMediaCode()."/photo.jpg" : "";
    $qrcodeUrl = $candidate->getMediaCode() ? "/data/candidates/media/".$candidate->getMediaCode()."/qr-code.png" : "";
?>


<body>

    <div id="validation-notice" >
        <p>Ce CV n'a pas encore été validé par l'équipe. 1-ted</p>
    </div>

    <div class="button-container">
        <button id="save-cv-button" class="edit-cv-button">Sauvegarder le CV</button>
       <button id="reset-cv-button" class="edit-cv-button">Vider ce CV</button>
       <button id="classify-cv-button" class="edit-cv-button">Classifier le CV</button>
      <button id="declassify-cv-button" style="display:none" class="edit-cv-button">Déclassifier le CV</button>
       <button id="print-cv-button" class="edit-cv-button">Imprimer ce CV</button>
    </div>
    <div class="cv-container" data-table="candidate" data-id="<?php echo $candidateId; ?>" data-mode="update">
        <!-- Header -->
        <header class="header canEdit" data-editor="cv-title">
            <div class="header-left">
                <div class="profile-photo" data-table="candidate" data-column="media_code" data-value="<?php echo $candidateMediaCode; ?>" data-id="<?php echo $candidateId; ?>" style="background-image: url('<?php echo $photoUrl; ?>');background-size: cover;" data-classified="true"></div>
            </div>
            <div class="header-right">
                <h1 class="title" ><span class="dataToEdit" data-table="identity" data-column="first_name" data-id="<?php echo $candidateIdentityId; ?>" data-value="<?php echo $candidateFirstName; ?>" data-classified="false"><?php echo $candidateFirstName?></span> <span class="dataToEdit" data-table="identity" data-column="last_name" data-id="<?php echo $candidateIdentityId; ?>" data-value="<?php echo $candidateLastName; ?>" data-classified="true"><?php echo $candidateLastName; ?></span></h1>
  <h1 class="title dataToEdit" data-table="identity" data-column="job_title" data-id="<?php echo $candidateIdentityId; ?>" data-value="<?php echo $candidateJobTitle; ?>" data-classified="false"><?php echo $candidateJobTitle; ?></h1>
            </div>
        </header>

        <!-- Info Section -->
        <section class="info-section">
            <div class="personal-info canEdit" data-editor="personal-info">
                <p><strong>Age :</strong> <span class="placeholder dataToEdit" data-table="identity" data-column="birthdate" data-id="<?php echo $candidateIdentityId; ?>" data-value="<?php echo $candidateBirthdate; ?>" data-classified="false"><?php 
                    $birthdate = $candidateBirthdate;
                    if ($birthdate) {
                        $birth = new DateTime($birthdate);
                        $today = new DateTime();
                        $age = $today->diff($birth)->y;
                        echo $age . ' ans';
                    }
                ?></span></p>
                <p><strong>Niveau d'étude :</strong> <span class="placeholder dataToEdit" data-table="identity" data-column="degree_id" data-id="<?php echo $candidateIdentityId; ?>" data-value="<?php echo $candidateDegreeId; ?>" data-classified="false"><?php echo $candidateDegreeTitle; ?></span></p>
                <p><strong>Langue(s) :</strong> <span class="placeholder multipleDataToEdit" data-table="identity" data-column="language_ids" data-collection="<?php echo $candidateLanguagesCollection; ?>" data-id="<?php echo $candidateIdentityId; ?>" data-classified="false"><?php echo implode(', ', array_map(function($language) { return $language->getLanguageName(); }, $candidateLanguages)); ?></span></p>
                <p><strong>Trait de personnalité :</strong> <span class="placeholder multipleDataToEdit" data-table="candidate" data-column="personality_ids" data-collection="<?php echo $candidatePersonalitiesCollection; ?>" data-id="<?php echo  $candidateId; ?>"  data-classified="false"><?php echo implode(', ', array_map(function($personality) { return $personality->getTrait(); }, $candidatePersonalities)); ?></span></p>
                <p><strong>Hobbies :</strong> <span class="placeholder multipleDataToEdit" data-table="identity" data-column="hobbie_ids" data-collection="<?php echo $candidateHobbiesCollection; ?>" data-id="<?php echo $candidateIdentityId; ?>" data-classified="false"><?php echo implode(', ', array_map(function($hobbie) { return $hobbie->getTitle(); }, $candidateHobbies)); ?></span></p>
            
            </div>
            <div class="job-info canEdit" data-editor="job-info">
                <p><strong>Localisation :</strong> <span class="placeholder dataToEdit" data-table="identity" data-column="location" data-id="<?php echo $candidateIdentityId; ?>" data-value="<?php echo $candidateLocation; ?>" data-classified="true"><?php echo $candidateLocation; ?></span></p>
                <p><strong>Téléphone :</strong> <span class="placeholder dataToEdit" data-table="identity" data-column="phone" data-id="<?php echo $candidateIdentityId; ?>" data-value="<?php echo $candidatePhone; ?>" data-classified="true"><?php echo $candidatePhone; ?></span></p>
                <p><strong>Email :</strong> <span class="placeholder dataToEdit" data-table="identity" data-column="email" data-id="<?php echo $candidateIdentityId; ?>" data-value="<?php echo $candidateEmail; ?>" data-classified="true"><?php echo $candidateEmail; ?></span></p>
                <p><strong>Véhicule :</strong> <span class="placeholder dataToEdit" data-table="identity" data-column="vehicule" data-id="<?php echo $candidateIdentityId; ?>" data-value="<?php echo $candidateIsVehicule ? "true" : "false"; ?>" data-classified="false"><?php echo $candidateIsVehicule ? 'Oui' : 'Non'; ?></span></p>
                <p><strong>Type de contract :</strong> <span class="placeholder dataToEdit" data-table="demand" data-column="contract_type" data-id="<?php echo $candidateDemandId; ?>" data-value="<?php echo $candidateContractType; ?>" data-classified="false"><?php echo $candidateContractType; ?></span></p>
                <p><strong>Prétention :</strong> <span class="placeholder dataToEdit" data-table="demand" data-column="salary" data-id="<?php echo $candidateDemandId; ?>" data-value="<?php echo $candidateSalary; ?>" data-classified="false"><?php echo $candidateSalary . ' €'; ?></span></p>
            </div>
        </section>

        <!-- Description and Skills -->
        <section class="main-content">
            <div class="description canEdit" data-editor="description">
                <h2>Description :</h2>
                <p class="dataToEdit" data-table="identity" data-column="description" data-value="<?php echo $candidateDescription; ?>" data-id="<?php echo $candidateIdentityId; ?>" data-classified="false"><?php echo $candidateDescription; ?></p>
            </div>
            <div class="skills canEdit" data-editor="skills">
                <h2>Compétences techniques :</h2>
                <ul>
                    <?php foreach ($candidate->getSkills() as $skill): ?>
                        <li>
                            <?php 
                            // Afficher l'étoile si favori
                            echo $skill->getFavorite() ? '⭐ ' : ''; 
                            
                            // Extraire le nombre d'années pour la condition an/ans
                            preg_match('/\d+/', $skill->getYear(), $yearMatches);
                            $yearValue = isset($yearMatches[0]) ? (int)$yearMatches[0] : 0;
                            $yearText = $yearValue > 1 ? 'ans' : 'an';
                            
                            echo '<span class="dataToEdit" data-table="skill" data-column="title" data-value="'.$skill->getTitle().'" data-id="'.$skill->getId().'" data-classified="false">'.$skill->getTitle().'</span> <span class="dataToEdit" data-table="skill" data-column="year" data-value="'.$skill->getYear().'" data-id="'.$skill->getId().'" data-classified="false" style="font-size: 10px; color: #fff;">' . $skill->getYear() ."</span><span style='font-size: 10px; color: #fff;'> " . $yearText . " d'exp</span>";
                            
                            // Afficher la jauge d'expérience
                            if ($skill->getYear()) {
                                // Utiliser le nombre déjà extrait
                                $years = $yearValue;
                                
                                // Limiter à 15 ans max
                                $years = min($years, 15);
                                
                                // Calculer le pourcentage
                                $percentage = ($years / 15) * 100;
                                
                                // Créer la jauge visuelle avec HTML/CSS
                                echo '<br><div style="width: 100%; height: 10px; background-color: #fff; border: 2px solid #000; margin-top: 5px; position: relative;">';
                                echo '<div style="width: ' . $percentage . '%; height: 100%; background-color: #ff3333;"></div>';
                                echo '</div>';

                            }
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

            </div>
        </section>

        <!-- Experience and Formation -->
        <section class="experience-formation">
            <div class="experience canEdit dataToEdit" data-editor="experience" data-table="candidate" data-id="<?php echo $candidateId; ?>" data-column="experience_ids" data-value="<?php echo $candidateExperiencesCollection; ?></div>">
                <h2>Expériences professionnels</h2>
                <?php foreach ($candidate->getExperiences() as $experience): ?>
                <div class="timeline-item dataToEdit" data-table="experience" data-id="<?php echo $experience->getId(); ?>" data-start-date="<?php echo $experience->getStartDate(); ?>" data-end-date="<?php echo $experience->getEndDate(); ?>" data-current="<?php echo $experience->isCurrent() ? 'true' : 'false'; ?>">
                    <p class="period">
                        <span class="date"><?php echo date('Y', strtotime($experience->getStartDate())) . ' - ' . ($experience->isCurrent() ? 'Présent' : date('Y', strtotime($experience->getEndDate()))); ?> :</span> 
                        <span class="status dataToEdit" data-table="experience" data-column="position" data-value="<?php echo $experience->getPosition();  ?>" data-id="<?php echo $experience->getId(); ?>" data-classified="false"><?php echo $experience->getPosition(); ?></span> - <strong><span class="dataToEdit" data-table="experience" data-column="company" data-value="<?php echo $experience->getCompany(); ?>" data-id="<?php echo $experience->getId(); ?>" data-classified="true"><?php echo $experience->getCompany(); ?></span>, <span class="dataToEdit" data-table="experience" data-column="location" data-value="<?php echo $experience->getLocation(); ?>" data-id="<?php echo $experience->getId(); ?>" data-classified="true"><?php echo $experience->getLocation(); ?></span></strong>
                    </p>
                    <p><strong>Missions :</strong> 
                        <span class="missions multipleDataToEdit" data-table="experience" data-column="missions" data-collection="<?php 
                            $experienceMissions = $experience->getMissions();
                            $experienceMissionIds = array_map(function($mission) { 
                                return $mission->getId(); 
                            }, $experienceMissions);
                            echo "[".implode(',', $experienceMissionIds)."]"; 
                        ?>" data-id="<?php echo $experience->getId(); ?>" data-classified="false">
                            <?php 
                            $missionCount = count($experienceMissions);
                            foreach ($experienceMissions as $index => $mission): ?>
                                <span class="multipleDataToEditItem" data-id="<?php echo $mission->getId(); ?>"><?php echo $mission->getTitle(); ?></span><?php if ($index < $missionCount - 1) echo ', '; ?>
                            <?php endforeach; ?>
                        </span>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="formation canEdit dataToEdit" data-editor="formation" data-table="candidate" data-id="<?php echo $candidateId; ?>" data-column="formation_ids" data-value="<?php echo $candidateFormationsCollection; ?>">
                <h2>Formations</h2>
                <?php foreach ($candidate->getFormations() as $formation): ?>
                <div class="timeline-item dataToEdit" data-table="formation" data-id="<?php echo $formation->getId(); ?>" data-start-date="<?php echo $formation->getStartDate(); ?>" data-end-date="<?php echo $formation->getEndDate(); ?>" data-current="<?php echo $formation->isCurrent() ? 'true' : 'false'; ?>">
                    <p class="period">
                        <span class="date"><?php echo date('Y', strtotime($formation->getStartDate())) . ' - ' . ($formation->isCurrent() ? 'Présent' : date('Y', strtotime($formation->getEndDate()))); ?> :</span> 
                        <span class="status dataToEdit" data-table="formation" data-column="title" data-value="<?php echo $formation->getTitle(); ?>" data-id="<?php echo $formation->getId(); ?>" data-classified="false"><?php echo $formation->getTitle(); ?></span> - <strong><span class="dataToEdit" data-table="formation" data-column="school" data-value="<?php echo $formation->getSchool(); ?>" data-id="<?php echo $formation->getId(); ?>" data-classified="true"><?php echo $formation->getSchool(); ?></span>, <span class="dataToEdit" data-table="formation" data-column="location" data-value="<?php echo $formation->getLocation(); ?>" data-id="<?php echo $formation->getId(); ?>" data-classified="true"><?php echo $formation->getLocation(); ?></span></strong>
                    </p>
                    <p><strong>Apprentissage :</strong> 
                        <span class="multipleDataToEdit" data-table="formation" data-column="knowledge" data-collection="<?php 
                            $formationKnowledgeIds = array_map(function($knowledge) { 
                                return $knowledge->getId(); 
                            }, $formation->getKnowledges());
                            echo "[".implode(',', $formationKnowledgeIds)."]"; 
                        ?>" data-id="<?php echo $formation->getId(); ?>" data-classified="false">
                            <?php 
                            $knowledges = $formation->getKnowledges();
                            $knowledgeCount = count($knowledges);
                            foreach ($knowledges as $index => $knowledge): ?>
                                <span class="multipleDataToEditItem" data-id="<?php echo $knowledge->getId(); ?>"><?php echo $knowledge->getTitle(); ?></span><?php if ($index < $knowledgeCount - 1) echo ', '; ?>
                            <?php endforeach; ?>
                        </span>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer canEdit" data-editor="qrcode">
            <div class="footer-logo">
                <img src="/media/image/logo.png" alt="AIEE NOVA Logo">
            </div>
            <div class="footer-text">
                <p data-table="identity" data-column="footer_text" data-id="<?php echo $candidate->getIdentity()->getId(); ?>" data-classified="false">Pour plus d'informations sur ce profil, n'hésitez pas à consulter ce Qr Code, pour voir ses réseaux sociaux ou son site web : </p>
            </div>
            <div class="footer-qr" data-table="candidate" data-column="media_code" data-value="<?php echo $candidateMediaCode; ?>" data-id="<?php echo $candidateId; ?>">
                <img data-classified="true" src="/data/candidates/media/<?php echo $candidateMediaCode; ?>/qr-code.png" alt="QR Code">
            </div>
        </footer>
    </div>
</body>

<?php } ?>
<?php

function importJob($job, $isUserAccount = false){

    $jobId = $job->getId();
    $jobTitle = $job->getTitle();
    $jobDescription = $job->getDescription();
    $jobLocation = $job->getLocation();
    $jobSalary = $job->getSalary();
    $jobContractType = $job->getContractType();
    $jobStartDate = $job->getStartDate();
    $jobLength = $job->getLength();
    $jobCompanyId = $job->getCompanyId();
    $jobWorkCondition = $job->getWorkCondition();
    $jobNeedVehicle = $job->getNeedVehicle();
    $jobContactId = $job->getContactId();
    $jobRequirementId = $job->getRequirementId();
    $jobOpen = $job->getOpen();
    $jobApproved = $job->isApproved();
    
    // Récupérer les objets liés si les IDs existent
    require_once __DIR__ . '/../../function/getData.php';
    
    $company = $jobCompanyId ? getData('company', $jobCompanyId) : null;
    $contact = $jobContactId ? getData('contact', $jobContactId) : null;
    $requirement = $jobRequirementId ? getData('requirement', $jobRequirementId) : null;
    
    // Extraire les données de la société
    $companyName = $company ? $company->getName() : '';
    $companyDescription = $company ? $company->getDescription() : '';
    $companyDomain = $company ? $company->getDomain() : '';
    $companyCapacity = $company ? $company->getCapacity() : '';
    $companyHqLocation = $company ? $company->getHqLocation() : '';
    $companyCountry = $company ? $company->getCountry() : '';
    $companyMediaCode = $company ? $company->getMediaCode() : '';
    
    // Ajouter un timestamp pour le cache-busting
    $cacheVersion = time();
    $companyLogoUrl = $companyMediaCode ? "/data/company/media/".$companyMediaCode."/logo.png?v=".$cacheVersion : "";
    
    // Extraire les données du contact
    $contactFirstName = $contact ? $contact->getFirstName() : '';
    $contactLastName = $contact ? $contact->getLastName() : '';
    $contactEmail = $contact ? $contact->getEmail() : '';
    $contactPhone = $contact ? $contact->getPhone() : '';
    
    // Extraire les données des exigences
    $requirementDescription = $requirement ? $requirement->getDescription() : '';
    $requirementDegree = $requirement ? $requirement->getDegree() : '';
    $requirementExperienceYears = $requirement ? $requirement->getExperienceYears() : '';
?>


    
    <?php if ($isUserAccount): ?>
    <div id="validation-notice">
        <?php if ($jobApproved === true || $jobApproved === 1 || $jobApproved === "1" || $jobApproved === 'true'): ?>
            <div class="validation-message">
                <div class="validation-icon">✓</div>
                <div class="validation-text">
                    <strong>Votre offre d'emploi a été validé par l'équipe 1-TED</strong>
                    <p>Votre offre est désormais visible par les candidats et vous pouvez recevoir des candidatures.</p>
                </div>
            </div>
        <?php elseif ($jobApproved === false || $jobApproved === 0 || $jobApproved === "0" || $jobApproved === 'false'): ?>
            <div class="rejection-warning">
                <div class="warning-icon">❌</div>
                <div class="warning-text">
                    <strong>Votre offre d'emploi a été refusée par l'équipe 1-TED</strong>
                    <p>Votre offre ne respecte pas les conditions d'utilisation. Veuillez consulter nos <a href="/${lang}/terms" target="_blank">Conditions Générales d'Utilisation</a> ou <a href="/${lang}/contact" target="_blank">nous contacter</a> pour plus d'informations.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="approval-warning">
                <div class="warning-icon">⚠️</div>
                <div class="warning-text">
                    <strong>Votre offre d'emploi n'a pas encore été validée par l'équipe 1-TED</strong>
                    <p>Veuillez attendre l'approbation de votre offre pour qu'elle soit visible par les candidats.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>



    <div class="button-container">
        <button id="save-job-button" class="edit-job-button">Sauvegarder l'offre</button>
        <button id="reset-job-button" class="edit-job-button">Réinitialiser</button>
        <button id="print-job-button" class="edit-job-button">Imprimer</button>
    </div>
    
    <div class="job-offer-container" data-table="job" data-id="<?php echo $jobId; ?>" data-mode="update">
        
        <!-- Header -->
        <header class="job-header canEdit" data-editor="job-title">
            <div class="job-header-content">
                <div class="company-logo-circle" data-table="company" data-column="media_code" data-value="<?php echo $companyMediaCode; ?>" data-id="<?php echo $jobCompanyId; ?>" style="background-image: url('<?php echo $companyLogoUrl; ?>'); background-size: contain; background-position: center; background-repeat: no-repeat;" data-classified="false"></div>
                <div class="job-header-text">
                    <h1 class="job-title dataToEdit" data-table="job" data-column="title" data-id="<?php echo $jobId; ?>" data-value="<?php echo htmlspecialchars($jobTitle); ?>" data-classified="false">
                        <?php echo htmlspecialchars($jobTitle); ?>
                    </h1>
                    <div class="job-company">
                        <span class="dataToEdit" data-table="company" data-column="name" data-id="<?php echo $jobCompanyId; ?>" data-value="<?php echo htmlspecialchars($companyName); ?>" data-classified="false">
                            <?php echo htmlspecialchars($companyName); ?>
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Info Section -->
        <section class="job-info-section canEdit" data-editor="job-basic-info">
            <div class="job-info-grid">
                <div class="info-item">
                    <strong>📍 Localisation :</strong>
                    <span class="dataToEdit" data-table="job" data-column="location" data-id="<?php echo $jobId; ?>" data-value="<?php echo htmlspecialchars($jobLocation); ?>" data-classified="false">
                        <?php echo htmlspecialchars($jobLocation); ?>
                    </span>
                </div>
                <div class="info-item">
                    <strong>💰 Salaire :</strong>
                    <span class="dataToEdit" data-table="job" data-column="salary" data-id="<?php echo $jobId; ?>" data-value="<?php echo htmlspecialchars($jobSalary); ?>" data-classified="false">
                        <?php echo htmlspecialchars($jobSalary); ?>
                    </span>
                </div>
                <div class="info-item">
                    <strong>📋 Type de contrat :</strong>
                    <span class="dataToEdit" data-table="job" data-column="contract_type" data-id="<?php echo $jobId; ?>" data-value="<?php echo htmlspecialchars($jobContractType); ?>" data-classified="false">
                        <?php echo htmlspecialchars($jobContractType); ?>
                    </span>
                </div>
                <div class="info-item">
                    <strong>📅 Date de début :</strong>
                    <span class="dataToEdit" data-table="job" data-column="start_date" data-id="<?php echo $jobId; ?>" data-value="<?php echo htmlspecialchars($jobStartDate); ?>" data-classified="false">
                        <?php echo htmlspecialchars($jobStartDate); ?>
                    </span>
                </div>
                <div class="info-item">
                    <strong>⏱️ Durée :</strong>
                    <span class="dataToEdit" data-table="job" data-column="length" data-id="<?php echo $jobId; ?>" data-value="<?php echo htmlspecialchars($jobLength); ?>" data-classified="false">
                        <?php echo htmlspecialchars($jobLength); ?>
                    </span>
                </div>
                <div class="info-item">
                    <strong>🚗 Véhicule nécessaire :</strong>
                    <span class="dataToEdit" data-table="job" data-column="need_vehicle" data-id="<?php echo $jobId; ?>" data-value="<?php echo $jobNeedVehicle ? '1' : '0'; ?>" data-classified="false">
                        <?php echo $jobNeedVehicle ? 'Oui' : 'Non'; ?>
                    </span>
                </div>
            </div>
        </section>

        <!-- Description -->
        <section class="job-description canEdit" data-editor="job-description">
            <h2>Description du poste</h2>
            <div class="description-content dataToEdit" data-table="job" data-column="description" data-id="<?php echo $jobId; ?>" data-value="<?php echo htmlspecialchars($jobDescription); ?>" data-classified="false">
                <?php echo nl2br(htmlspecialchars($jobDescription)); ?>
            </div>
        </section>

        <!-- Conditions de travail -->
        <section class="job-conditions canEdit" data-editor="job-conditions">
            <h2>Conditions de travail</h2>
            <div class="conditions-content dataToEdit" data-table="job" data-column="work_condition" data-id="<?php echo $jobId; ?>" data-value="<?php echo htmlspecialchars($jobWorkCondition); ?>" data-classified="false">
                <?php echo nl2br(htmlspecialchars($jobWorkCondition)); ?>
            </div>
        </section>

        <!-- Exigences -->
        <section class="job-requirements canEdit" data-editor="job-requirements">
            <h2>Profil recherché</h2>
            <div class="requirements-grid">
                <div class="requirement-item">
                    <strong>🎓 Niveau d'études :</strong>
                    <span class="dataToEdit" data-table="requirement" data-column="degree" data-id="<?php echo $jobRequirementId; ?>" data-value="<?php echo htmlspecialchars($requirementDegree); ?>" data-classified="false">
                        <?php echo htmlspecialchars($requirementDegree); ?>
                    </span>
                </div>
                <div class="requirement-item">
                    <strong>💼 Années d'expérience :</strong>
                    <span class="dataToEdit" data-table="requirement" data-column="experience_years" data-id="<?php echo $jobRequirementId; ?>" data-value="<?php echo htmlspecialchars($requirementExperienceYears); ?>" data-classified="false">
                        <?php echo htmlspecialchars($requirementExperienceYears); ?>
                    </span>
                </div>
                <div class="requirement-description">
                    <strong>📝 Compétences requises :</strong>
                    <div class="dataToEdit" data-table="requirement" data-column="description" data-id="<?php echo $jobRequirementId; ?>" data-value="<?php echo htmlspecialchars($requirementDescription); ?>" data-classified="false">
                        <?php echo nl2br(htmlspecialchars($requirementDescription)); ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- À propos de l'entreprise -->
        <section class="job-company-info canEdit" data-editor="job-company-info">
            <h2>À propos de l'entreprise</h2>
            <div class="company-grid">
                <div class="company-item">
                    <strong>🏢 Nom :</strong>
                    <span class="dataToEdit" data-table="company" data-column="name" data-id="<?php echo $jobCompanyId; ?>" data-value="<?php echo htmlspecialchars($companyName); ?>" data-classified="false">
                        <?php echo htmlspecialchars($companyName); ?>
                    </span>
                </div>
                <div class="company-item">
                    <strong>🔧 Secteur :</strong>
                    <span class="dataToEdit" data-table="company" data-column="domain" data-id="<?php echo $jobCompanyId; ?>" data-value="<?php echo htmlspecialchars($companyDomain); ?>" data-classified="false">
                        <?php echo htmlspecialchars($companyDomain); ?>
                    </span>
                </div>
                <div class="company-item">
                    <strong>👥 Effectif :</strong>
                    <span class="dataToEdit" data-table="company" data-column="capacity" data-id="<?php echo $jobCompanyId; ?>" data-value="<?php echo htmlspecialchars($companyCapacity); ?>" data-classified="false">
                        <?php echo htmlspecialchars($companyCapacity); ?> employés
                    </span>
                </div>
                <div class="company-item">
                    <strong>📍 Siège social :</strong>
                    <span class="dataToEdit" data-table="company" data-column="hq_location" data-id="<?php echo $jobCompanyId; ?>" data-value="<?php echo htmlspecialchars($companyHqLocation); ?>" data-classified="false">
                        <?php echo htmlspecialchars($companyHqLocation); ?>
                    </span>
                </div>
                <div class="company-description">
                    <strong>📄 Description :</strong>
                    <div class="dataToEdit" data-table="company" data-column="description" data-id="<?php echo $jobCompanyId; ?>" data-value="<?php echo htmlspecialchars($companyDescription); ?>" data-classified="false">
                        <?php echo nl2br(htmlspecialchars($companyDescription)); ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact -->
        <section class="job-contact canEdit" data-editor="job-contact">
            <h2>Contact</h2>
            <div class="contact-grid">
                <div class="contact-item">
                    <strong>👤 Nom :</strong>
                    <span class="dataToEdit" data-table="contact" data-column="first_name" data-id="<?php echo $jobContactId; ?>" data-value="<?php echo htmlspecialchars($contactFirstName); ?>" data-classified="true">
                        <?php echo htmlspecialchars($contactFirstName); ?>
                    </span>
                    <span class="dataToEdit" data-table="contact" data-column="last_name" data-id="<?php echo $jobContactId; ?>" data-value="<?php echo htmlspecialchars($contactLastName); ?>" data-classified="true">
                        <?php echo htmlspecialchars($contactLastName); ?>
                    </span>
                </div>
                <div class="contact-item">
                    <strong>📧 Email :</strong>
                    <span class="dataToEdit" data-table="contact" data-column="email" data-id="<?php echo $jobContactId; ?>" data-value="<?php echo htmlspecialchars($contactEmail); ?>" data-classified="true">
                        <?php echo htmlspecialchars($contactEmail); ?>
                    </span>
                </div>
                <div class="contact-item">
                    <strong>📞 Téléphone :</strong>
                    <span class="dataToEdit" data-table="contact" data-column="phone" data-id="<?php echo $jobContactId; ?>" data-value="<?php echo htmlspecialchars($contactPhone); ?>" data-classified="true">
                        <?php echo htmlspecialchars($contactPhone); ?>
                    </span>
                </div>
            </div>
        </section>

    </div>

<?php } ?>

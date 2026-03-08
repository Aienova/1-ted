   <?php
    // Démarrer la session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/../../function/getData.php';
    require_once __DIR__ . '/../../function/connectionDatabase.php';
    
    $company = null;
    $contact = null;
    $jobCompanyId = '';
    $jobContactId = '';
    $isCompanyLoggedIn = false;
    $contactEmailFromAccount = '';

    // Vérifier si l'utilisateur est connecté en tant qu'entreprise
    if (isset($_SESSION['accountId']) && (isset($_SESSION['role']) && ($_SESSION['role'] === 'company' || $_SESSION['role'] === 'recruiter'))) {
        $isCompanyLoggedIn = true;
        
        try {
            $db = getDatabaseConnection();
            
            // Récupérer l'email du compte (qui est l'email du contact)
            $accountStmt = $db->prepare("SELECT email FROM account WHERE id = :account_id");
            $accountStmt->execute([':account_id' => $_SESSION['accountId']]);
            $accountResult = $accountStmt->fetch(PDO::FETCH_ASSOC);
            $contactEmailFromAccount = $accountResult['email'] ?? '';
            
            // Récupérer l'ID de l'entreprise depuis la session ou la base de données
            if (isset($_SESSION['company_id'])) {
                $companyId = $_SESSION['company_id'];
            } else {
                $stmt = $db->prepare("SELECT company_id FROM account WHERE id = :account_id");
                $stmt->execute([':account_id' => $_SESSION['accountId']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $companyId = $result['company_id'] ?? null;
                
                if ($companyId) {
                    $_SESSION['company_id'] = $companyId;
                }
            }
            
            // Si on a un company_id, récupérer les données
            if ($companyId) {
                $jobCompanyId = $companyId;
                $company = getDataById('company', $companyId);
                
                // Récupérer le contact associé à l'entreprise
                if ($company && $company->getContactId()) {
                    $jobContactId = $company->getContactId();
                    $contact = getDataById('contact', $company->getContactId());
                }
            }
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des données entreprise: " . $e->getMessage());
        }
    }

    // Extraire les données de la société
    $companyName = $company ? $company->getName() : '';
    $companyDescription = $company ? $company->getDescription() : '';
    $companyDomain = $company ? $company->getDomain() : '';
    $companyCapacity = $company ? $company->getCapacity() : '';
    $companyHqLocation = $company ? $company->getHqLocation() : '';
    $companyCountry = $company ? $company->getCountry() : '';
    
    // Extraire les données du contact
    $contactFirstName = $contact ? $contact->getFirstName() : '';
    $contactLastName = $contact ? $contact->getLastName() : '';
    // Utiliser l'email du compte si connecté, sinon celui du contact
    $contactEmail = $isCompanyLoggedIn && $contactEmailFromAccount ? $contactEmailFromAccount : ($contact ? $contact->getEmail() : '');
    $contactPhone = $contact ? $contact->getPhone() : '';
    $companyMediaCode = $company ? $company->getMediaCode() : '';
    
    // Ajouter un timestamp pour le cache-busting
    $cacheVersion = time();
    $companyLogoUrl = $companyMediaCode ? "/data/company/media/".$companyMediaCode."/logo.png?v=".$cacheVersion : "";

?>
   
   
   
   
   <link rel="stylesheet" href="/style/registerPopup.css">
    <div class="button-container">
        <button id="add-job-button" class="edit-job-button">Ajouter l'offre</button>
        <button id="test-job-button" class="edit-job-button">Données test</button>
        <button id="reset-job-button" class="edit-job-button">Réinitialiser</button>
        <button id="print-job-button" class="edit-job-button">Imprimer</button>
    </div>

    <div class="job-offer-container" data-table="job" data-id="" data-mode="insert">
        <header class="job-header canEdit" data-editor="job-title">
            <div class="job-header-content">
                <div class="company-logo-circle" data-table="company" data-column="media_code" data-value="<?php echo $companyMediaCode ?? ''; ?>" data-id="<?php echo $jobCompanyId; ?>" style="background-image: url('<?php echo $companyLogoUrl ?? ''; ?>'); background-size: contain; background-position: center; background-repeat: no-repeat;" data-classified="false"></div>
                <div class="job-header-text">
                    <h1 class="job-title dataToEdit" data-table="job" data-column="title" data-id="" data-value="" data-classified="false">Titre du poste</h1>
                    <div class="job-company"><span id="company-name-display">Nom de la société</span></div>
                </div>
            </div>
        </header>

        <section class="job-info-section canEdit" data-editor="job-basic-info">
            <div class="job-info-grid">
                <div class="info-item"><strong>📍 Localisation :</strong> <span class="dataToEdit" data-table="job" data-column="location" data-id="" data-value="" data-classified="false">--</span></div>
                <div class="info-item"><strong>💰 Salaire :</strong> <span class="dataToEdit" data-table="job" data-column="salary" data-id="" data-value="" data-classified="false">--</span></div>
                <div class="info-item"><strong>📋 Type de contrat :</strong> <span class="dataToEdit" data-table="job" data-column="contract_type" data-id="" data-value="" data-classified="false">--</span></div>
                <div class="info-item"><strong>📅 Date de début :</strong> <span class="dataToEdit" data-table="job" data-column="start_date" data-id="" data-value="" data-classified="false">--</span></div>
                <div class="info-item"><strong>⏱️ Durée :</strong> <span class="dataToEdit" data-table="job" data-column="length" data-id="" data-value="" data-classified="false">--</span></div>
                <div class="info-item"><strong>🚗 Véhicule nécessaire :</strong> <span class="dataToEdit" data-table="job" data-column="need_vehicle" data-id="" data-value="0" data-classified="false">Non</span></div>
            </div>
        </section>

        <section class="job-description canEdit" data-editor="job-description">
            <h2>Description du poste</h2>
            <div class="description-content dataToEdit" data-table="job" data-column="description" data-id="" data-value="" data-classified="false">Description du poste...</div>
        </section>

        <section class="job-conditions canEdit" data-editor="job-conditions">
            <h2>Conditions de travail</h2>
            <div class="conditions-content dataToEdit" data-table="job" data-column="work_condition" data-id="" data-value="" data-classified="false">Conditions de travail...</div>
        </section>

        <section class="job-requirements canEdit" data-editor="job-requirements">
            <h2>Profil recherché</h2>
            <div class="requirements-grid">
                <div class="requirement-item"><strong>🎓 Niveau d'études :</strong> <span class="dataToEdit" data-table="requirement" data-column="degree" data-id="" data-value="" data-classified="false">--</span></div>
                <div class="requirement-item"><strong>💼 Années d'expérience :</strong> <span class="dataToEdit" data-table="requirement" data-column="experience_years" data-id="" data-value="" data-classified="false">--</span></div>
                <div class="requirement-description"><strong>📝 Compétences requises :</strong> <div class="dataToEdit" data-table="requirement" data-column="description" data-id="" data-value="" data-classified="false">Compétences requises...</div></div>
            </div>
        </section>

        <!-- Les informations de l'entreprise et du contact sont déjà associées à votre compte -->
        <?php if ($isCompanyLoggedIn): ?>
        <section id="company-info-section" style="background: #f0f9ff; padding: 1rem; border-radius: 8px; margin: 1rem 0;">
            <p style="margin: 0; color: #0369a1;">
                ℹ️ Cette offre sera automatiquement associée à votre entreprise : <strong id="company-name-info"><?php echo htmlspecialchars($companyName); ?></strong>
            </p>
        </section>
        <?php endif; ?>

           <section class="job-company-info<?php echo $isCompanyLoggedIn ? '' : ' canEdit'; ?>" data-editor="job-company-info">
            <?php if ($isCompanyLoggedIn): ?>
                <div style="background: #e7f3ff; padding: 12px; border-radius: 8px; margin-bottom: 1rem; color: #0369a1;">
                    🔒 Ces informations sont pré-remplies depuis votre profil entreprise
                </div>
            <?php endif; ?>
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
        <section class="job-contact<?php echo $isCompanyLoggedIn ? '' : ' canEdit'; ?>" data-editor="job-contact">
            <?php if ($isCompanyLoggedIn): ?>
                <div style="background: #e7f3ff; padding: 12px; border-radius: 8px; margin-bottom: 1rem; color: #0369a1;">
                    🔒 Ces informations sont pré-remplies depuis votre profil de contact. L'email ne peut pas être modifié car il est lié à votre compte.
                </div>
            <?php endif; ?>
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
                    <span class="dataToEdit <?php echo $isCompanyLoggedIn ? 'non-editable' : ''; ?>" data-table="contact" data-column="email" data-id="<?php echo $jobContactId; ?>" data-value="<?php echo htmlspecialchars($contactEmail); ?>" data-classified="true" <?php echo $isCompanyLoggedIn ? 'data-readonly="true"' : ''; ?>>
                        <?php echo htmlspecialchars($contactEmail); ?>
                    </span>
                    <?php if ($isCompanyLoggedIn): ?>
                        <span style="color: #777; font-size: 0.9em; margin-left: 8px;">🔒 Non modifiable</span>
                    <?php endif; ?>
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

    <script src="/script/register.js"></script>
    </div>

<!-- Les scripts sont déjà chargés dans index.php, pas besoin de les recharger ici -->




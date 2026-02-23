<?php
// Page vierge pour ajouter une nouvelle offre d'emploi
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
                <h1 class="job-title dataToEdit" data-table="job" data-column="title" data-id="" data-value="" data-classified="false">Titre du poste</h1>
                <div class="job-company"><span class="dataToEdit" data-table="company" data-column="name" data-id="" data-value="" data-classified="true">Nom de l'entreprise</span></div>
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

        <section class="job-company-info canEdit" data-editor="job-company-info">
            <h2>À propos de l'entreprise</h2>
            <div class="company-grid">
                <div class="company-item"><strong>🏢 Nom :</strong> <span class="dataToEdit" data-table="company" data-column="name" data-id="" data-value="" data-classified="true">--</span></div>
                <div class="company-item"><strong>🔧 Secteur :</strong> <span class="dataToEdit" data-table="company" data-column="domain" data-id="" data-value="" data-classified="false">--</span></div>
                <div class="company-item"><strong>👥 Effectif :</strong> <span class="dataToEdit" data-table="company" data-column="capacity" data-id="" data-value="" data-classified="false">--</span></div>
                <div class="company-item"><strong>📍 Siège social :</strong> <span class="dataToEdit" data-table="company" data-column="hq_location" data-id="" data-value="" data-classified="false">--</span>, <span class="dataToEdit" data-table="company" data-column="country" data-id="" data-value="" data-classified="false">--</span></div>
                <div class="company-description"><strong>📄 Description :</strong> <div class="dataToEdit" data-table="company" data-column="description" data-id="" data-value="" data-classified="false">Description de l'entreprise...</div></div>
            </div>
        </section>

        <section class="job-contact canEdit" data-editor="job-contact">
            <h2>Contact</h2>
            <div class="contact-grid">
                <div class="contact-item"><strong>👤 Nom :</strong> <span class="dataToEdit" data-table="contact" data-column="first_name" data-id="" data-value="" data-classified="true">--</span> <span class="dataToEdit" data-table="contact" data-column="last_name" data-id="" data-value="" data-classified="true">--</span></div>
                <div class="contact-item"><strong>📧 Email :</strong> <span class="dataToEdit" data-table="contact" data-column="email" data-id="" data-value="" data-classified="true">--</span></div>
                <div class="contact-item"><strong>📞 Téléphone :</strong> <span class="dataToEdit" data-table="contact" data-column="phone" data-id="" data-value="" data-classified="true">--</span></div>
            </div>
        </section>
    </div>

    <script src="/script/register.js"></script>




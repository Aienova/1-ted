<!-- Page de gestion du profil candidat -->

<div class="profile-management">
    <h1 style="margin: 0 0 2rem 0; color: #333;">Mon profil</h1>
    
    <div class="profile-sections">
        <!-- Section Photo de profil -->
        <div class="profile-section">
            <h2 class="section-title">📸 Photo de profil</h2>
            <div class="photo-upload">
                <div class="photo-preview" id="photo-preview">
                    <img src="" alt="Photo de profil" id="profile-photo" style="display: none;">
                    <div class="photo-placeholder" id="photo-placeholder">
                        <span style="font-size: 3rem;">👤</span>
                    </div>
                </div>
                <div class="photo-controls">
                    <input type="file" id="photo-input" accept="image/*" style="display: none;" onchange="handlePhotoChange(event)">
                    <button type="button" onclick="document.getElementById('photo-input').click()" class="btn-action btn-secondary">
                        📁 Choisir une photo
                    </button>
                    <button type="button" onclick="removePhoto()" class="btn-action btn-danger" style="margin-top: 10px;">
                        🗑️ Supprimer
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Section Informations du compte -->
        <div class="profile-section">
            <h2 class="section-title">🔐 Informations du compte</h2>
            <form id="account-form">
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" id="account-email" required>
                </div>
                <div class="form-group">
                    <label>Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                    <input type="password" name="new_password" id="new-password" placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label>Confirmer le nouveau mot de passe</label>
                    <input type="password" name="confirm_password" id="confirm-password" placeholder="••••••••">
                </div>
                <button type="button" onclick="saveAccountInfo()" class="btn-action btn-primary">
                    💾 Enregistrer les modifications
                </button>
            </form>
        </div>
        
        <!-- Section Informations personnelles -->
        <div class="profile-section">
            <h2 class="section-title">👤 Informations personnelles</h2>
            <form id="personal-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Prénom *</label>
                        <input type="text" name="first_name" id="first-name" required>
                    </div>
                    <div class="form-group">
                        <label>Nom *</label>
                        <input type="text" name="last_name" id="last-name" required>
                    </div>
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="tel" name="phone" id="phone">
                    </div>
                    <div class="form-group">
                        <label>Date de naissance</label>
                        <input type="date" name="birth_date" id="birth-date">
                    </div>
                    <div class="form-group">
                        <label>Ville</label>
                        <input type="text" name="city" id="city">
                    </div>
                    <div class="form-group">
                        <label>Pays</label>
                        <input type="text" name="country" id="country">
                    </div>
                </div>
                <button type="button" onclick="savePersonalInfo()" class="btn-action btn-primary" style="margin-top: 1rem;">
                    💾 Enregistrer les modifications
                </button>
            </form>
        </div>
        
        <!-- Section Préférences professionnelles -->
        <div class="profile-section">
            <h2 class="section-title">💼 Préférences professionnelles</h2>
            <form id="preferences-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Secteur d'activité recherché</label>
                        <input type="text" name="desired_sector" id="desired-sector" placeholder="Ex: IT, Marketing, Finance">
                    </div>
                    <div class="form-group">
                        <label>Type de contrat préféré</label>
                        <select name="preferred_contract" id="preferred-contract">
                            <option value="">-- Sélectionner --</option>
                            <option value="CDI">CDI</option>
                            <option value="CDD">CDD</option>
                            <option value="Freelance">Freelance</option>
                            <option value="Stage">Stage</option>
                            <option value="Alternance">Alternance</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Salaire minimum souhaité</label>
                        <input type="text" name="min_salary" id="min-salary" placeholder="Ex: 30000€">
                    </div>
                    <div class="form-group">
                        <label>Mobilité géographique</label>
                        <select name="mobility" id="mobility">
                            <option value="">-- Sélectionner --</option>
                            <option value="local">Locale uniquement</option>
                            <option value="regional">Régionale</option>
                            <option value="national">Nationale</option>
                            <option value="international">Internationale</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Télétravail</label>
                        <select name="remote_work" id="remote-work">
                            <option value="">-- Sélectionner --</option>
                            <option value="no">Non souhaité</option>
                            <option value="partial">Hybride</option>
                            <option value="full">100% télétravail</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Disponibilité</label>
                        <select name="availability" id="availability">
                            <option value="">-- Sélectionner --</option>
                            <option value="immediate">Immédiate</option>
                            <option value="1month">1 mois</option>
                            <option value="2months">2 mois</option>
                            <option value="3months+">3 mois ou plus</option>
                        </select>
                    </div>
                </div>
                <button type="button" onclick="savePreferences()" class="btn-action btn-primary" style="margin-top: 1rem;">
                    💾 Enregistrer les modifications
                </button>
            </form>
        </div>
        
        <!-- Section Notifications -->
        <div class="profile-section">
            <h2 class="section-title">🔔 Notifications</h2>
            <form id="notifications-form">
                <div class="notification-option">
                    <label class="checkbox-label">
                        <input type="checkbox" name="email_new_offers" id="email-new-offers">
                        <span>Recevoir des emails pour les nouvelles offres d'emploi</span>
                    </label>
                </div>
                <div class="notification-option">
                    <label class="checkbox-label">
                        <input type="checkbox" name="email_application_updates" id="email-application-updates">
                        <span>Recevoir des emails pour les mises à jour de mes candidatures</span>
                    </label>
                </div>
                <div class="notification-option">
                    <label class="checkbox-label">
                        <input type="checkbox" name="email_messages" id="email-messages">
                        <span>Recevoir des emails lorsque je reçois un message</span>
                    </label>
                </div>
                <button type="button" onclick="saveNotifications()" class="btn-action btn-primary" style="margin-top: 1rem;">
                    💾 Enregistrer les préférences
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.profile-management {
    max-width: 900px;
    margin: 0 auto;
}

.profile-sections {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.profile-section {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.section-title {
    color: #333;
    font-size: 1.2rem;
    margin: 0 0 1.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.photo-upload {
    display: flex;
    gap: 2rem;
    align-items: center;
}

.photo-preview {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f9fafb;
}

.photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
}

.photo-controls {
    display: flex;
    flex-direction: column;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    margin-bottom: 0.5rem;
    color: #555;
    font-weight: 500;
    font-size: 0.9rem;
}

.form-group input,
.form-group select {
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-action {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, #1e93e5 0%, #4b97a2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #f3f4f6;
    color: #333;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

.notification-option {
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.checkbox-label span {
    color: #555;
    font-size: 0.95rem;
}
</style>

<script>
// Charger les données du profil au chargement
document.addEventListener('DOMContentLoaded', function() {
    loadProfileData();
});

// Charger les données du profil
function loadProfileData() {
    fetch('<?php echo $baseUrl; ?>/function/getCandidateProfile.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.profile) {
                populateProfileForm(data.profile);
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement du profil:', error);
        });
}

// Remplir le formulaire avec les données du profil
function populateProfileForm(profile) {
    // Compte
    if (profile.account) {
        document.getElementById('account-email').value = profile.account.email || '';
    }
    
    // Informations personnelles
    if (profile.identity) {
        document.getElementById('first-name').value = profile.identity.first_name || '';
        document.getElementById('last-name').value = profile.identity.last_name || '';
        document.getElementById('phone').value = profile.identity.phone || '';
        document.getElementById('birth-date').value = profile.identity.birth_date || '';
        document.getElementById('city').value = profile.identity.city || '';
        document.getElementById('country').value = profile.identity.country || '';
    }
    
    // Photo de profil
    if (profile.photo_url) {
        const photoElement = document.getElementById('profile-photo');
        photoElement.src = profile.photo_url;
        photoElement.style.display = 'block';
        document.getElementById('photo-placeholder').style.display = 'none';
    }
    
    // Préférences (si elles existent)
    if (profile.preferences) {
        document.getElementById('desired-sector').value = profile.preferences.desired_sector || '';
        document.getElementById('preferred-contract').value = profile.preferences.preferred_contract || '';
        document.getElementById('min-salary').value = profile.preferences.min_salary || '';
        document.getElementById('mobility').value = profile.preferences.mobility || '';
        document.getElementById('remote-work').value = profile.preferences.remote_work || '';
        document.getElementById('availability').value = profile.preferences.availability || '';
    }
    
    // Notifications (si elles existent)
    if (profile.notifications) {
        document.getElementById('email-new-offers').checked = profile.notifications.email_new_offers || false;
        document.getElementById('email-application-updates').checked = profile.notifications.email_application_updates || false;
        document.getElementById('email-messages').checked = profile.notifications.email_messages || false;
    }
}

// Gérer le changement de photo
function handlePhotoChange(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    // Vérifier le type de fichier
    if (!file.type.startsWith('image/')) {
        alert('Veuillez sélectionner une image');
        return;
    }
    
    // Vérifier la taille (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
        alert('L\'image est trop volumineuse (max 5MB)');
        return;
    }
    
    // Prévisualiser l'image
    const reader = new FileReader();
    reader.onload = function(e) {
        const photoElement = document.getElementById('profile-photo');
        photoElement.src = e.target.result;
        photoElement.style.display = 'block';
        document.getElementById('photo-placeholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
    
    // Envoyer au serveur
    const formData = new FormData();
    formData.append('photo', file);
    
    fetch('<?php echo $baseUrl; ?>/function/uploadProfilePhoto.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Photo de profil mise à jour avec succès!');
        } else {
            alert('Erreur lors du téléchargement: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors du téléchargement de la photo');
    });
}

// Supprimer la photo
function removePhoto() {
    if (!confirm('Êtes-vous sûr de vouloir supprimer votre photo de profil?')) return;
    
    fetch('<?php echo $baseUrl; ?>/function/deleteProfilePhoto.php', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('profile-photo').style.display = 'none';
            document.getElementById('photo-placeholder').style.display = 'flex';
            alert('Photo supprimée avec succès!');
        } else {
            alert('Erreur lors de la suppression: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la suppression de la photo');
    });
}

// Enregistrer les informations du compte
function saveAccountInfo() {
    const email = document.getElementById('account-email').value;
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    
    if (!email) {
        alert('L\'email est obligatoire');
        return;
    }
    
    if (newPassword && newPassword !== confirmPassword) {
        alert('Les mots de passe ne correspondent pas');
        return;
    }
    
    const data = { email };
    if (newPassword) {
        data.new_password = newPassword;
    }
    
    fetch('<?php echo $baseUrl; ?>/function/updateAccountInfo.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Informations du compte mises à jour avec succès!');
            document.getElementById('new-password').value = '';
            document.getElementById('confirm-password').value = '';
        } else {
            alert('Erreur: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la mise à jour');
    });
}

// Enregistrer les informations personnelles
function savePersonalInfo() {
    const formData = {
        first_name: document.getElementById('first-name').value,
        last_name: document.getElementById('last-name').value,
        phone: document.getElementById('phone').value,
        birth_date: document.getElementById('birth-date').value,
        city: document.getElementById('city').value,
        country: document.getElementById('country').value
    };
    
    if (!formData.first_name || !formData.last_name) {
        alert('Le prénom et le nom sont obligatoires');
        return;
    }
    
    fetch('<?php echo $baseUrl; ?>/function/updatePersonalInfo.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Informations personnelles mises à jour avec succès!');
        } else {
            alert('Erreur: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la mise à jour');
    });
}

// Enregistrer les préférences
function savePreferences() {
    const formData = {
        desired_sector: document.getElementById('desired-sector').value,
        preferred_contract: document.getElementById('preferred-contract').value,
        min_salary: document.getElementById('min-salary').value,
        mobility: document.getElementById('mobility').value,
        remote_work: document.getElementById('remote-work').value,
        availability: document.getElementById('availability').value
    };
    
    fetch('<?php echo $baseUrl; ?>/function/updatePreferences.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Préférences mises à jour avec succès!');
        } else {
            alert('Erreur: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la mise à jour');
    });
}

// Enregistrer les notifications
function saveNotifications() {
    const formData = {
        email_new_offers: document.getElementById('email-new-offers').checked,
        email_application_updates: document.getElementById('email-application-updates').checked,
        email_messages: document.getElementById('email-messages').checked
    };
    
    fetch('<?php echo $baseUrl; ?>/function/updateNotifications.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Préférences de notification mises à jour avec succès!');
        } else {
            alert('Erreur: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la mise à jour');
    });
}
</script>

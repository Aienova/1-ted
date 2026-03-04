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
                    <input type="email" name="email" id="account-email" required readonly style="background: #f3f4f6; cursor: not-allowed;">
                    <small style="color: #6b7280; font-size: 0.85rem; margin-top: 5px; display: block;">L'email ne peut pas être modifié</small>
                </div>
                <div class="form-group">
                    <label>Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                    <div style="position: relative;">
                        <input type="password" name="new_password" id="new-password" placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility('new-password')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1.2rem;" title="Afficher/Masquer le mot de passe">
                            👁️
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label>Confirmer le nouveau mot de passe</label>
                    <div style="position: relative;">
                        <input type="password" name="confirm_password" id="confirm-password" placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility('confirm-password')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1.2rem;" title="Afficher/Masquer le mot de passe">
                            👁️
                        </button>
                    </div>
                </div>
                <button type="button" onclick="savePassword()" class="btn-action btn-primary">
                    💾 Modifier le mot de passe
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
                        <label>Pays</label>
                        <select name="country" id="country">
                            <option value="">Chargement...</option>
                        </select>
                    </div>
                </div>
                <button type="button" onclick="savePersonalInfo()" class="btn-action btn-primary" style="margin-top: 1rem;">
                    💾 Enregistrer les modifications
                </button>
            </form>
        </div>
        
        <!-- Section Préférences professionnelles 
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
        </div> -->
        
        <!-- Section Notifications 
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
</div> -->

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
// Variable globale pour stocker les pays
let countriesData = [];

// Charger les données du profil au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Charger d'abord les pays, puis le profil
    loadCountries().then(() => {
        loadProfileData();
    });
});

// Charger la liste des pays depuis la base de données
function loadCountries() {
    console.log('🌍 Chargement de la liste des pays...');
    
    return fetch('<?php echo $baseUrl; ?>/function/getCountries.php?lang=fr')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.countries) {
                countriesData = data.countries;
                console.log('✅ ' + data.total + ' pays chargés');
                
                // Remplir le select des pays
                const countrySelect = document.getElementById('country');
                countrySelect.innerHTML = '<option value="">-- Sélectionner --</option>';
                
                data.countries.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.id;
                    option.textContent = country.name;
                    countrySelect.appendChild(option);
                });
                
                console.log('✅ Select des pays rempli');
            } else {
                console.error('❌ Erreur chargement pays:', data.error);
                alert('⚠️ Erreur lors du chargement des pays');
            }
        })
        .catch(error => {
            console.error('❌ Erreur réseau chargement pays:', error);
            alert('⚠️ Erreur de connexion lors du chargement des pays');
        });
}

// Charger les données du profil
function loadProfileData() {
    console.log('🔍 Chargement du profil candidat...');
    
    fetch('<?php echo $baseUrl; ?>/function/getMyProfile.php')
        .then(response => response.json())
        .then(data => {
            console.log('📦 Données reçues:', data);
            
            if (data.success && data.role === 'candidate' && data.profile) {
                console.log('✅ Profil candidat trouvé, remplissage des champs...');
                populateProfileForm(data.profile);
            } else if (data.success && data.role !== 'candidate') {
                console.error('❌ Cette page est réservée aux candidats');
                alert('⚠️ Cette page est réservée aux candidats');
            } else {
                console.error('❌ Erreur:', data.error);
                alert('⚠️ Erreur lors du chargement du profil: ' + (data.error || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            console.error('❌ Erreur réseau lors du chargement du profil:', error);
            alert('⚠️ Erreur de connexion au serveur');
        });
}

// Remplir le formulaire avec les données du profil
function populateProfileForm(profile) {
    console.log('📝 Remplissage du formulaire avec:', profile);
    
    // Compte
    if (profile.account && profile.account.email) {
        console.log('✉️ Email:', profile.account.email);
        document.getElementById('account-email').value = profile.account.email;
    }
    
    // Informations personnelles
    if (profile.identity) {
        console.log('👤 Identité:', profile.identity);
        
        if (profile.identity.first_name) {
            document.getElementById('first-name').value = profile.identity.first_name;
        }
        if (profile.identity.last_name) {
            document.getElementById('last-name').value = profile.identity.last_name;
        }
        if (profile.identity.phone) {
            document.getElementById('phone').value = profile.identity.phone;
        }
        if (profile.identity.birth_date) {
            document.getElementById('birth-date').value = profile.identity.birth_date;
        }
    }
    
    // Pays (sélectionner par ID)
    if (profile.country_id) {
        console.log('🌍 Pays ID:', profile.country_id);
        const countrySelect = document.getElementById('country');
        
        // Sélectionner le pays correspondant à l'ID
        countrySelect.value = profile.country_id;
        
        // Vérifier si la sélection a réussi
        if (countrySelect.value == profile.country_id) {
            const selectedOption = countrySelect.options[countrySelect.selectedIndex];
            console.log('✅ Pays sélectionné:', selectedOption.text);
        } else {
            console.warn('⚠️ Pays ID ' + profile.country_id + ' non trouvé dans la liste');
        }
    }
    
    // Photo de profil (utilise media_code)
    if (profile.photo_url) {
        console.log('📸 Photo URL:', profile.photo_url);
        const photoElement = document.getElementById('profile-photo');
        const photoPlaceholder = document.getElementById('photo-placeholder');
        
        photoElement.src = profile.photo_url + '?t=' + new Date().getTime(); // Cache busting
        photoElement.style.display = 'block';
        if (photoPlaceholder) {
            photoPlaceholder.style.display = 'none';
        }
    } else {
        console.log('📸 Pas de photo de profil');
    }
    
    // Préférences (si elles existent)
    if (profile.preferences) {
        console.log('⚙️ Préférences:', profile.preferences);
        document.getElementById('desired-sector').value = profile.preferences.desired_sector || '';
        document.getElementById('preferred-contract').value = profile.preferences.preferred_contract || '';
        document.getElementById('min-salary').value = profile.preferences.min_salary || '';
        document.getElementById('mobility').value = profile.preferences.mobility || '';
        document.getElementById('remote-work').value = profile.preferences.remote_work || '';
        document.getElementById('availability').value = profile.preferences.availability || '';
    }
    
    // Notifications (si elles existent)
    if (profile.notifications) {
        console.log('🔔 Notifications:', profile.notifications);
        document.getElementById('email-new-offers').checked = profile.notifications.email_new_offers || false;
        document.getElementById('email-application-updates').checked = profile.notifications.email_application_updates || false;
        document.getElementById('email-messages').checked = profile.notifications.email_messages || false;
    }
    
    console.log('✅ Formulaire rempli avec succès');
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
            alert('✅ Photo de profil mise à jour avec succès!');
            // Recharger la photo avec cache busting
            if (data.photo_url) {
                const photoElement = document.getElementById('profile-photo');
                photoElement.src = data.photo_url;
            }
        } else {
            alert('❌ Erreur lors du téléchargement: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('❌ Erreur lors du téléchargement de la photo');
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

// Afficher/Masquer le mot de passe
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}

// Enregistrer le nouveau mot de passe
function savePassword() {
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    
    if (!newPassword) {
        alert('Veuillez saisir un nouveau mot de passe');
        return;
    }
    
    if (newPassword.length < 6) {
        alert('Le mot de passe doit contenir au moins 6 caractères');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        alert('Les mots de passe ne correspondent pas');
        return;
    }
    
    fetch('<?php echo $baseUrl; ?>/function/changePassword.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ new_password: newPassword })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Mot de passe modifié avec succès!');
            document.getElementById('new-password').value = '';
            document.getElementById('confirm-password').value = '';
        } else {
            alert('❌ Erreur: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('❌ Erreur lors de la modification du mot de passe');
    });
}

// Enregistrer les informations personnelles
function savePersonalInfo() {
    const formData = {
        first_name: document.getElementById('first-name').value,
        last_name: document.getElementById('last-name').value,
        phone: document.getElementById('phone').value,
        birth_date: document.getElementById('birth-date').value,
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

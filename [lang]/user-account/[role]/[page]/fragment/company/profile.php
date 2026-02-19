<!-- Page de gestion du profil entreprise -->

<div class="company-profile-management">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="margin: 0; color: #333;">Profil de l'entreprise</h1>
        <button onclick="saveCompanyProfile()" class="btn-action btn-primary">
            💾 Enregistrer les modifications
        </button>
    </div>
    
    <div class="profile-sections">
        <!-- Section Informations générales -->
        <div class="profile-section">
            <h2 class="section-title">🏢 Informations générales</h2>
            <form id="company-info-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nom de l'entreprise *</label>
                        <input type="text" name="name" id="company-name" required>
                    </div>
                    <div class="form-group">
                        <label>SIRET</label>
                        <input type="text" name="siret" id="company-siret" placeholder="123 456 789 00012">
                    </div>
                    <div class="form-group">
                        <label>Secteur d'activité</label>
                        <input type="text" name="domain" id="company-domain" placeholder="Ex: Informatique, Finance, etc.">
                    </div>
                    <div class="form-group">
                        <label>Taille de l'entreprise</label>
                        <select name="size" id="company-size">
                            <option value="">-- Sélectionner --</option>
                            <option value="1-10">1-10 employés</option>
                            <option value="11-50">11-50 employés</option>
                            <option value="51-200">51-200 employés</option>
                            <option value="201-500">201-500 employés</option>
                            <option value="500+">500+ employés</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Site web</label>
                        <input type="url" name="website" id="company-website" placeholder="https://...">
                    </div>
                    <div class="form-group">
                        <label>Siège social</label>
                        <input type="text" name="hq_location" id="company-hq" placeholder="Ville, Pays">
                    </div>
                </div>
                <div class="form-group" style="margin-top: 1rem;">
                    <label>Description de l'entreprise</label>
                    <textarea name="description" id="company-description" rows="4" placeholder="Présentez votre entreprise, votre culture, vos valeurs..."></textarea>
                </div>
            </form>
        </div>
        
        <!-- Section Logo de l'entreprise -->
        <div class="profile-section">
            <h2 class="section-title">🎨 Logo de l'entreprise</h2>
            <div class="logo-upload">
                <div class="logo-preview" id="logo-preview">
                    <img src="" alt="Logo entreprise" id="company-logo" style="display: none;">
                    <div class="logo-placeholder" id="logo-placeholder">
                        <span style="font-size: 3rem;">🏢</span>
                    </div>
                </div>
                <div class="logo-controls">
                    <input type="file" id="logo-input" accept="image/*" style="display: none;" onchange="handleLogoChange(event)">
                    <button type="button" onclick="document.getElementById('logo-input').click()" class="btn-action btn-secondary">
                        📁 Choisir un logo
                    </button>
                    <button type="button" onclick="removeLogo()" class="btn-action btn-danger" style="margin-top: 10px;">
                        🗑️ Supprimer
                    </button>
                    <p style="margin-top: 10px; color: #999; font-size: 0.85rem;">Format recommandé : 400x400px (max 2MB)</p>
                </div>
            </div>
        </div>
        
        <!-- Section Contact entreprise -->
        <div class="profile-section">
            <h2 class="section-title">📞 Contact entreprise</h2>
            <form id="contact-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Prénom du contact *</label>
                        <input type="text" name="contact_first_name" id="contact-first-name" required>
                    </div>
                    <div class="form-group">
                        <label>Nom du contact *</label>
                        <input type="text" name="contact_last_name" id="contact-last-name" required>
                    </div>
                    <div class="form-group">
                        <label>Email de contact *</label>
                        <input type="email" name="contact_email" id="contact-email" required>
                    </div>
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="tel" name="contact_phone" id="contact-phone">
                    </div>
                    <div class="form-group">
                        <label>Poste occupé</label>
                        <input type="text" name="contact_position" id="contact-position" placeholder="Ex: DRH, Responsable recrutement">
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Section Réseaux sociaux -->
        <div class="profile-section">
            <h2 class="section-title">🌐 Réseaux sociaux</h2>
            <form id="social-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>LinkedIn</label>
                        <input type="url" name="linkedin" id="social-linkedin" placeholder="https://linkedin.com/company/...">
                    </div>
                    <div class="form-group">
                        <label>Twitter</label>
                        <input type="url" name="twitter" id="social-twitter" placeholder="https://twitter.com/...">
                    </div>
                    <div class="form-group">
                        <label>Facebook</label>
                        <input type="url" name="facebook" id="social-facebook" placeholder="https://facebook.com/...">
                    </div>
                    <div class="form-group">
                        <label>Instagram</label>
                        <input type="url" name="instagram" id="social-instagram" placeholder="https://instagram.com/...">
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Section Avantages entreprise -->
        <div class="profile-section">
            <h2 class="section-title">✨ Avantages proposés</h2>
            <div class="benefits-container">
                <label class="benefit-checkbox">
                    <input type="checkbox" name="benefit_remote" id="benefit-remote">
                    <span>🏠 Télétravail</span>
                </label>
                <label class="benefit-checkbox">
                    <input type="checkbox" name="benefit_flexible" id="benefit-flexible">
                    <span>⏰ Horaires flexibles</span>
                </label>
                <label class="benefit-checkbox">
                    <input type="checkbox" name="benefit_health" id="benefit-health">
                    <span>🏥 Mutuelle d'entreprise</span>
                </label>
                <label class="benefit-checkbox">
                    <input type="checkbox" name="benefit_training" id="benefit-training">
                    <span>📚 Formation continue</span>
                </label>
                <label class="benefit-checkbox">
                    <input type="checkbox" name="benefit_bonus" id="benefit-bonus">
                    <span>💰 Primes et bonus</span>
                </label>
                <label class="benefit-checkbox">
                    <input type="checkbox" name="benefit_restaurant" id="benefit-restaurant">
                    <span>🍽️ Tickets restaurant</span>
                </label>
                <label class="benefit-checkbox">
                    <input type="checkbox" name="benefit_transport" id="benefit-transport">
                    <span>🚇 Transport pris en charge</span>
                </label>
                <label class="benefit-checkbox">
                    <input type="checkbox" name="benefit_events" id="benefit-events">
                    <span>🎉 Événements d'équipe</span>
                </label>
            </div>
        </div>
    </div>
</div>

<style>
.company-profile-management {
    max-width: 1000px;
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
.form-group select,
.form-group textarea {
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

.logo-upload {
    display: flex;
    gap: 2rem;
    align-items: center;
}

.logo-preview {
    width: 150px;
    height: 150px;
    border-radius: 12px;
    overflow: hidden;
    border: 3px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f9fafb;
}

.logo-preview img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 10px;
}

.logo-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
}

.logo-controls {
    display: flex;
    flex-direction: column;
}

.benefits-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.benefit-checkbox {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.benefit-checkbox:hover {
    border-color: #667eea;
    background: #f9fafb;
}

.benefit-checkbox input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.benefit-checkbox span {
    color: #555;
    font-size: 0.95rem;
}
</style>

<script>
// Charger les données du profil au chargement
document.addEventListener('DOMContentLoaded', function() {
    loadCompanyProfile();
});

// Charger le profil de l'entreprise
function loadCompanyProfile() {
    fetch('/function/getMyCompany.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.company) {
                populateCompanyProfile(data.company);
            } else {
                console.error('Erreur:', data.error);
                alert('Erreur lors du chargement du profil: ' + (data.error || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement du profil:', error);
            alert('Erreur de connexion au serveur');
        });
}

// Remplir le formulaire avec les données
function populateCompanyProfile(company) {
    // Informations générales
    if (company.name) document.getElementById('company-name').value = company.name;
    if (company.siret) document.getElementById('company-siret').value = company.siret;
    if (company.domain) document.getElementById('company-domain').value = company.domain;
    if (company.size) document.getElementById('company-size').value = company.size;
    if (company.website) document.getElementById('company-website').value = company.website;
    if (company.hq_location) document.getElementById('company-hq').value = company.hq_location;
    if (company.description) document.getElementById('company-description').value = company.description;
    
    // Logo
    if (company.logo_url) {
        const logoElement = document.getElementById('company-logo');
        logoElement.src = company.logo_url;
        logoElement.style.display = 'block';
        document.getElementById('logo-placeholder').style.display = 'none';
    }
    
    // Contact
    if (company.contact) {
        if (company.contact.first_name) document.getElementById('contact-first-name').value = company.contact.first_name;
        if (company.contact.last_name) document.getElementById('contact-last-name').value = company.contact.last_name;
        if (company.contact.email) document.getElementById('contact-email').value = company.contact.email;
        if (company.contact.phone) document.getElementById('contact-phone').value = company.contact.phone;
        if (company.contact.position) document.getElementById('contact-position').value = company.contact.position;
    }
    
    // Réseaux sociaux
    if (company.social) {
        if (company.social.linkedin) document.getElementById('social-linkedin').value = company.social.linkedin;
        if (company.social.twitter) document.getElementById('social-twitter').value = company.social.twitter;
        if (company.social.facebook) document.getElementById('social-facebook').value = company.social.facebook;
        if (company.social.instagram) document.getElementById('social-instagram').value = company.social.instagram;
    }
    
    // Avantages
    if (company.benefits) {
        Object.keys(company.benefits).forEach(key => {
            const checkbox = document.getElementById('benefit-' + key);
            if (checkbox) checkbox.checked = company.benefits[key];
        });
    }
}

// Gérer le changement de logo
function handleLogoChange(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    if (!file.type.startsWith('image/')) {
        alert('Veuillez sélectionner une image');
        return;
    }
    
    if (file.size > 2 * 1024 * 1024) {
        alert('L\'image est trop volumineuse (max 2MB)');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const logoElement = document.getElementById('company-logo');
        logoElement.src = e.target.result;
        logoElement.style.display = 'block';
        document.getElementById('logo-placeholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
    
    // TODO: Envoyer au serveur
}

// Supprimer le logo
function removeLogo() {
    if (!confirm('Êtes-vous sûr de vouloir supprimer le logo?')) return;
    
    document.getElementById('company-logo').style.display = 'none';
    document.getElementById('logo-placeholder').style.display = 'flex';
    // TODO: Supprimer sur le serveur
}

// Enregistrer le profil
function saveCompanyProfile() {
    const profileData = {
        company_info: {
            name: document.getElementById('company-name').value,
            siret: document.getElementById('company-siret').value,
            domain: document.getElementById('company-domain').value,
            size: document.getElementById('company-size').value,
            website: document.getElementById('company-website').value,
            hq_location: document.getElementById('company-hq').value,
            description: document.getElementById('company-description').value
        },
        contact: {
            first_name: document.getElementById('contact-first-name').value,
            last_name: document.getElementById('contact-last-name').value,
            email: document.getElementById('contact-email').value,
            phone: document.getElementById('contact-phone').value,
            position: document.getElementById('contact-position').value
        },
        social: {
            linkedin: document.getElementById('social-linkedin').value,
            twitter: document.getElementById('social-twitter').value,
            facebook: document.getElementById('social-facebook').value,
            instagram: document.getElementById('social-instagram').value
        },
        benefits: {
            remote: document.getElementById('benefit-remote').checked,
            flexible: document.getElementById('benefit-flexible').checked,
            health: document.getElementById('benefit-health').checked,
            training: document.getElementById('benefit-training').checked,
            bonus: document.getElementById('benefit-bonus').checked,
            restaurant: document.getElementById('benefit-restaurant').checked,
            transport: document.getElementById('benefit-transport').checked,
            events: document.getElementById('benefit-events').checked
        }
    };
    
    fetch('/function/updateCompanyProfile.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(profileData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Profil mis à jour avec succès!');
            loadCompanyProfile(); // Recharger le profil
        } else {
            alert('Erreur: ' + (data.error || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la mise à jour du profil');
    });
}
</script>

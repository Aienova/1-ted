<!-- Page de gestion des candidatures reçues par l'entreprise -->

<div class="applications-management">
    <h1 style="margin: 0 0 2rem 0; color: #333;">Candidatures reçues</h1>
    
    <!-- Filtres -->
    <div class="filters-container">
        <div class="filter-group">
            <input 
                type="text" 
                id="search-keyword" 
                placeholder="🔍 Rechercher candidat ou poste..." 
                class="filter-input"
            >
        </div>
        <div class="filter-group">
            <select id="filter-status" class="filter-select">
                <option value="all">Tous les statuts</option>
                <option value="pending">⏳ En attente</option>
                <option value="in-process">👁️ En cours d'examen</option>
                <option value="question">❓ Entretien</option>
                <option value="validate">✅ Acceptée</option>
                <option value="rejected">❌ Refusée</option>
            </select>
        </div>
        <div class="filter-group">
            <select id="filter-job" class="filter-select">
                <option value="">Toutes les offres</option>
            </select>
        </div>
    </div>
    
    <!-- Liste des candidatures -->
    <div id="applications-list" class="applications-list">
        <p style="text-align: center; color: #999; padding: 2rem;">Chargement des candidatures...</p>
    </div>
</div>

<!-- Popup pour voir le CV -->
<div id="popup-cv" class="popup">
    <div class="popup-overlay" onclick="closePopup('popup-cv')"></div>
    <div class="popup-content popup-large">
        <button class="popup-close" onclick="closePopup('popup-cv')">×</button>
        <div id="popup-cv-content" style="max-height: calc(100vh - 8rem); overflow-y: auto;"></div>
    </div>
</div>

<!-- Popup pour les informations de contact -->
<div id="popup-contact" class="popup">
    <div class="popup-overlay" onclick="closePopup('popup-contact')"></div>
    <div class="popup-content popup-small">
        <button class="popup-close" onclick="closePopup('popup-contact')">×</button>
        <div id="popup-contact-content"></div>
    </div>
</div>

<style>
.applications-management {
    max-width: 1400px;
    margin: 0 auto;
}

.filters-container {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
    display: grid;
    grid-template-columns: 1fr auto auto;
    gap: 1rem;
}

.filter-input,
.filter-select {
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    width: 100%;
}

.filter-input:focus,
.filter-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.applications-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.application-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s;
}

.application-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.application-header {
    display: grid;
    grid-template-columns: auto 1fr auto auto;
    gap: 1.5rem;
    align-items: center;
    margin-bottom: 1rem;
}

.candidate-photo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e5e7eb;
}

.candidate-info h3 {
    margin: 0 0 0.5rem 0;
    color: #333;
    font-size: 1.2rem;
}

.candidate-meta {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    color: #666;
    font-size: 0.9rem;
}

.status-icon {
    font-size: 2rem;
}

.application-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-action,
.btn-small {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: linear-gradient(135deg, #1e93e5 0%, #4b97a2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-view-cv {
    background: #8b5cf6;
    color: white;
}

.btn-view-cv:hover {
    background: #7c3aed;
}

.btn-contact {
    background: #3b82f6;
    color: white;
}

.btn-contact:hover {
    background: #2563eb;
}

.btn-validate {
    background: #10b981;
    color: white;
}

.btn-validate:hover {
    background: #059669;
}

.btn-reject {
    background: #ef4444;
    color: white;
}

.btn-reject:hover {
    background: #dc2626;
}

.application-comment {
    margin-top: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-left: 3px solid #667eea;
    border-radius: 6px;
}

.application-comment strong {
    color: #333;
    display: block;
    margin-bottom: 0.5rem;
}

/* Popups */
.popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
    display: none;
}

.popup.active {
    display: block;
}

.popup-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}

.popup-content {
    position: relative;
    background: white;
    margin: 2rem auto;
    padding: 2rem;
    border-radius: 12px;
    max-height: calc(100vh - 4rem);
    overflow-y: auto;
}

.popup-small {
    max-width: 500px;
}

.popup-medium {
    max-width: 700px;
}

.popup-large {
    max-width: 1000px;
}

.popup-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    font-size: 1.5rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.popup-close:hover {
    background: #dc2626;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #555;
    font-weight: 500;
}

.form-group textarea,
.form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
}

.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Animation pour le spinner */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
}
</style>

<script>
let allApplications = [];
let searchTimeout;

// Charger les candidatures au chargement
document.addEventListener('DOMContentLoaded', function() {
    loadJobs(); // Pour le filtre
    loadApplications();
    
    // Événements des filtres
    document.getElementById('search-keyword').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadApplications();
        }, 500);
    });
    
    document.getElementById('filter-status').addEventListener('change', function() {
        loadApplications();
    });
    
    document.getElementById('filter-job').addEventListener('change', function() {
        loadApplications();
    });
});

// Charger la liste des offres pour le filtre
function loadJobs() {
    fetch('<?php echo $baseUrl; ?>/function/getMyCompanyJobs.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.jobs) {
                const select = document.getElementById('filter-job');
                data.jobs.forEach(job => {
                    const option = document.createElement('option');
                    option.value = job.id;
                    option.textContent = job.title;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Erreur:', error));
}

// Charger les candidatures
function loadApplications() {
    const keyword = document.getElementById('search-keyword').value;
    const status = document.getElementById('filter-status').value;
    const jobId = document.getElementById('filter-job').value;
    
    let url = '<?php echo $baseUrl; ?>/function/getCompanyApplications.php?';
    if (keyword) url += `keyword=${encodeURIComponent(keyword)}&`;
    if (status !== 'all') url += `status=${status}&`;
    if (jobId) url += `job_id=${jobId}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allApplications = data.applications;
                displayApplications(data.applications);
            } else {
                document.getElementById('applications-list').innerHTML = 
                    '<p style="text-align: center; color: #ef4444;">Erreur lors du chargement des candidatures.</p>';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('applications-list').innerHTML = 
                '<p style="text-align: center; color: #ef4444;">Erreur lors du chargement des candidatures.</p>';
        });
}

// Afficher les candidatures
function displayApplications(applications) {
    const container = document.getElementById('applications-list');
    
    if (!applications || applications.length === 0) {
        container.innerHTML = '<p style="text-align: center; color: #666; padding: 2rem;">Aucune candidature reçue pour le moment.</p>';
        return;
    }
    
    const statusIcons = {
        'pending': { icon: '⏳', color: '#f59e0b', tooltip: 'En attente' },
        'in-process': { icon: '👁️', color: '#3b82f6', tooltip: 'En cours d\'examen' },
        'question': { icon: '❓', color: '#8b5cf6', tooltip: 'Entretien prévu' },
        'rejected': { icon: '❌', color: '#ef4444', tooltip: 'Refusée' },
        'validate': { icon: '✅', color: '#10b981', tooltip: 'Acceptée' }
    };
    
    let html = '';
    applications.forEach(app => {
        const status = statusIcons[app.status] || statusIcons['pending'];
        const date = new Date(app.created_at).toLocaleDateString('fr-FR');
        const photoUrl = app.candidate_photo_url || '';
        
        html += `
            <div class="application-card">
                <div class="application-header">
                    <img src="<?php echo $baseUrl; ?>${photoUrl}" alt="Photo" class="candidate-photo" onerror="this.src='/media/image/icon/avatar/default.png'">
                    
                    <div class="candidate-info">
                        <h3>${app.first_name} ${app.last_name}</h3>
                        <p style="margin: 0.25rem 0; color: #667eea; font-weight: 500;">${app.job_title_candidate || 'Poste non renseigné'}</p>
                        <div class="candidate-meta">
                            <span>📋 Offre : ${app.job_title}</span>
                            <span>📅 Candidature du ${date}</span>
                        </div>
                    </div>
                    
                    <div class="status-icon" style="color: ${status.color};" title="${status.tooltip}">
                        ${status.icon}
                    </div>
                    
                    <div class="application-actions">
                        ${app.candidate_id ? `
                            <button onclick="viewCV(${app.candidate_id}, ${app.application_id})" class="btn-small btn-view-cv">
                                📄 Voir CV
                            </button>
                        ` : ''}
                        <button onclick="viewContact(${app.application_id})" class="btn-small btn-contact">
                            📞 Contact
                        </button>
                        <button onclick="validateApplication(${app.application_id})" class="btn-small btn-validate">
                            ✅ Valider
                        </button>
                        <button onclick="rejectApplication(${app.application_id})" class="btn-small btn-reject">
                            ❌ Refuser
                        </button>
                    </div>
                </div>
                
                ${app.comment ? `
                    <div class="application-comment">
                        <strong>💬 Commentaire :</strong>
                        ${app.comment}
                    </div>
                ` : ''}
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// Voir le CV dans une popup avec le viewer
async function viewCV(candidateId, applicationId) {
    const popupContent = document.getElementById('popup-cv-content');
    
    // Afficher le loader
    popupContent.innerHTML = `
        <div style="text-align: center; padding: 3rem;">
            <div class="spinner" style="margin: 0 auto 1rem; border: 4px solid #f3f3f3; border-top: 4px solid #667eea; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite;"></div>
            <p style="color: #666;">Chargement du CV...</p>
        </div>
    `;
    
    openPopup('popup-cv');
    
    // Mettre à jour le statut en "in-process" silencieusement
    if (applicationId) {
        fetch('<?php echo $baseUrl; ?>/function/updateApplicationStatus.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                application_id: applicationId,
                status: 'in-process'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour la liste en arrière-plan
                loadApplications();
            }
        })
        .catch(error => console.error('Erreur mise à jour statut:', error));
    }
    
    try {
        // Récupérer le media_code du candidat
        const mediaCodeResponse = await fetch(`<?php echo $baseUrl; ?>/function/getCandidateMediaCode.php?id=${candidateId}`);
        const mediaCodeData = await mediaCodeResponse.json();
        
        if (!mediaCodeData.success || !mediaCodeData.media_code) {
            throw new Error('Media code introuvable');
        }
        
        const mediaCode = mediaCodeData.media_code;
        
        // Charger le CV HTML
        const cvResponse = await fetch(`<?php echo $baseUrl; ?>/data/candidates/media/${mediaCode}/cv.html`);
        
        if (!cvResponse.ok) {
            throw new Error('CV introuvable');
        }
        
        const cvHTML = await cvResponse.text();
        
        // Afficher le CV
        popupContent.innerHTML = cvHTML;
        
        // Masquer automatiquement les données sensibles
        setTimeout(() => {
            classifyDataAutomatically(popupContent);
        }, 0);
        
    } catch (error) {
        console.error('Erreur:', error);
        popupContent.innerHTML = `
            <div style="text-align: center; padding: 3rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">⚠️</div>
                <h3>Erreur</h3>
                <p style="color: #ef4444;">${error.message}</p>
            </div>
        `;
    }
}

// Masquer les données sensibles dans le CV
function classifyDataAutomatically(container) {
    const classifiedElements = container.querySelectorAll('.classified');
    
    classifiedElements.forEach((element) => {
        if (element.classList.contains('profile-photo')) {
            element.style.backgroundImage = `url("<?php echo $baseUrl; ?>/data/candidates/media/default/photo.jpg")`;
        } else if (element.classList.contains('footer-qr')) {
            const qrImage = element.querySelector('img');
            if (qrImage) {
                qrImage.src = `<?php echo $baseUrl; ?>/media/image/qrcode.png`;
            }
            element.classList.remove('classified');
        } else {
            if (element.textContent && element.textContent.trim() !== '' && element.textContent !== 'Info classifiée') {
                element.textContent = 'Info classifiée';
            }
        }
    });
}

// Afficher les informations de contact du candidat
function viewContact(applicationId) {
    const app = allApplications.find(a => a.application_id == applicationId);
    if (!app) {
        return;
    }
    
    // Mettre à jour le statut en "question" silencieusement
    fetch('<?php echo $baseUrl; ?>/function/updateApplicationStatus.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            application_id: applicationId,
            status: 'question'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour la liste en arrière-plan
            loadApplications();
        }
    })
    .catch(error => console.error('Erreur mise à jour statut:', error));
    
    const content = `
        <h3 style="margin-top: 0; color: #333;">📞 Informations de contact</h3>
        <div style="padding: 1rem 0;">
            <div style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb;">
                <h4 style="margin: 0 0 0.5rem 0; color: #667eea;">${app.first_name} ${app.last_name}</h4>
                <p style="margin: 0; color: #666; font-size: 0.9rem;">${app.job_title_candidate || 'Poste non renseigné'}</p>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <span style="font-size: 1.5rem;">📧</span>
                    <div>
                        <strong style="display: block; color: #555; font-size: 0.85rem; margin-bottom: 0.25rem;">Email</strong>
                        <a href="mailto:${app.candidate_email || app.email}" style="color: #667eea; text-decoration: none; font-size: 1rem;">
                            ${app.candidate_email || app.email}
                        </a>
                    </div>
                </div>
                ${app.phone ? `
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <span style="font-size: 1.5rem;">📱</span>
                        <div>
                            <strong style="display: block; color: #555; font-size: 0.85rem; margin-bottom: 0.25rem;">Téléphone</strong>
                            <a href="tel:${app.phone}" style="color: #667eea; text-decoration: none; font-size: 1rem;">
                                ${app.phone}
                            </a>
                        </div>
                    </div>
                ` : '<p style="color: #999; font-style: italic;">Téléphone non renseigné</p>'}
            </div>
        </div>
    `;
    
    document.getElementById('popup-contact-content').innerHTML = content;
    openPopup('popup-contact');
}

// Valider une candidature
function validateApplication(applicationId) {
    if (!confirm('Êtes-vous sûr de vouloir valider cette candidature?')) return;
    
    updateApplicationStatus(applicationId, 'validate');
}

// Refuser une candidature
function rejectApplication(applicationId) {
    if (!confirm('Êtes-vous sûr de vouloir refuser cette candidature?')) return;
    
    updateApplicationStatus(applicationId, 'rejected');
}

// Mettre à jour le statut d'une candidature
function updateApplicationStatus(applicationId, status) {
    fetch('<?php echo $baseUrl; ?>/function/updateApplicationStatus.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            application_id: applicationId,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const statusText = status === 'validate' ? 'validée' : 'refusée';
            alert(`Candidature ${statusText} avec succès!`);
            loadApplications(); // Recharger la liste
        } else {
            alert('Erreur: ' + (data.error || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la mise à jour');
    });
}

// Soumettre le changement de statut
function submitStatusChange() {
    const applicationId = document.getElementById('status-application-id').value;
    const status = document.getElementById('status-select').value;
    const comment = document.getElementById('status-comment').value;
    
    fetch('<?php echo $baseUrl; ?>/function/updateApplicationStatus.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            application_id: applicationId,
            status: status,
            comment: comment
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Statut mis à jour avec succès!');
            closePopup('popup-status');
            loadApplications(); // Recharger la liste
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

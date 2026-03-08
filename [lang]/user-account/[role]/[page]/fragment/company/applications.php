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
                <option value="cancelled">🚫 Annulées</option>
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
        <button class="btn-print-cv" onclick="printCV()" title="Imprimer le CV">
            🖨️ Imprimer le CV
        </button>
        <div id="popup-cv-content" style="max-height: calc(100vh - 8rem);"></div>
    </div>
</div>

<!-- Charger html2canvas pour la capture d'écran -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- Popup pour les informations de contact -->
<div id="popup-contact" class="popup">
    <div class="popup-overlay" onclick="closePopup('popup-contact')"></div>
    <div class="popup-content popup-small">
        <button class="popup-close" onclick="closePopup('popup-contact')">×</button>
        <div id="popup-contact-content"></div>
    </div>
</div>

<!-- Popup pour le refus de candidature -->
<div id="popup-reject" class="popup">
    <div class="popup-overlay" onclick="closePopup('popup-reject')"></div>
    <div class="popup-content popup-medium">
        <button class="popup-close" onclick="closePopup('popup-reject')">×</button>
        <div id="popup-reject-content">
            <h2 style="margin-top: 0; color: #ef4444;">❌ Refuser cette candidature</h2>
            
            <div style="background: #fff3cd; border-left: 4px solid #f59e0b; padding: 20px; margin: 20px 0; border-radius: 8px;">
                <p style="margin: 0 0 10px 0; color: #856404;"><strong>💡 Aidez les candidats à s'améliorer !</strong></p>
                <p style="margin: 0; color: #856404; font-size: 0.95rem;">
                    En partageant les raisons de votre refus, vous aidez le candidat à mieux comprendre les attentes du marché et à progresser dans sa recherche d'emploi. 
                    C'est un geste professionnel et bienveillant très apprécié.
                </p>
            </div>
            
            <div style="background: #e8f5e9; border-left: 4px solid #10b981; padding: 20px; margin: 20px 0; border-radius: 8px;">
                <p style="margin: 0; color: #2e7d32;"><strong>🎁 Avantage 1-TED :</strong> Les entreprises qui fournissent des retours constructifs bénéficieront d'avantages exclusifs (meilleure visibilité, crédits gratuits, etc.).</p>
            </div>
            
            <div class="form-group">
                <label for="reject-comment">Raison du refus (optionnel mais recommandé) :</label>
                <textarea id="reject-comment" rows="5" placeholder="Exemple : Profil intéressant mais nous recherchons une expérience plus spécifique en [compétence]..." style="resize: vertical;"></textarea>
                <small style="color: #777; display: block; margin-top: 5px;">
                    💬 Suggestions : manque d'expérience, compétences non alignées, profil sur-qualifié, recherche d'un autre profil, etc.
                </small>
            </div>
            
            <input type="hidden" id="reject-application-id" value="">
            
            <div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 15px; margin: 20px 0; border-radius: 8px;">
                <p style="margin: 0; color: #991b1b;"><strong>⚠️ Attention :</strong> Cette action est irréversible. Le candidat sera immédiatement notifié par email.</p>
            </div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 20px;">
                <button onclick="closePopup('popup-reject')" class="btn-action" style="background: #6b7280;">
                    Annuler
                </button>
                <button onclick="submitRejection()" class="btn-action btn-reject">
                    ❌ Confirmer le refus
                </button>
            </div>
        </div>
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
    color: #777;
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
    z-index: 111111111;
    display: none;
}

.classified {
    filter: blur(0px)!important;
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

.btn-print-cv {
    /* position: absolute; */
    top: 1rem;
    right: 4rem;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
    /* justify-content: center; */
    margin: auto;
    margin-bottom: 2em;
}

.btn-print-cv:hover {
    background: #5568d3;
    transform: translateY(-2px);
}

/* Styles pour l'impression */
@media print {
    /* Cacher tous les éléments de la page sauf le CV */
    body > *:not(#popup-cv) {
        display: none !important;
    }
    
    .applications-management,
    .popup-overlay,
    .popup-close,
    .btn-print-cv {
        display: none !important;
    }
    
    /* Afficher uniquement le contenu du CV */
    #popup-cv {
        position: static !important;
        display: block !important;
    }
    
    .popup-content {
        position: static !important;
        box-shadow: none !important;
        margin: 0 !important;
        padding: 0 !important;
        max-width: 100% !important;
        max-height: none !important;
        overflow: visible !important;
    }
    
    #popup-cv-content {
        position: static !important;
        max-height: none !important;
        overflow: visible !important;
        width: 100%;
    }
    
    /* S'assurer que le contenu du CV est bien affiché */
    #popup-cv-content * {
        visibility: visible !important;
    }
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
let currentMediaCode = null; // Stocker le media_code du CV actuellement affiché

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
    // Pour "cancelled", on charge tout et on filtre côté client pour inclure aussi les "redflag"
    if (status !== 'all' && status !== 'cancelled') {
        url += `status=${status}&`;
    }
    if (jobId) url += `job_id=${jobId}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allApplications = data.applications;
                
                // Filtrer côté client si "cancelled" est sélectionné (pour inclure redflag)
                let filteredApplications = data.applications;
                if (status === 'cancelled') {
                    filteredApplications = data.applications.filter(app => 
                        app.status === 'cancelled' || app.status === 'redflag'
                    );
                }
                
                displayApplications(filteredApplications);
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
        container.innerHTML = '<p style="text-align: center; color: #777; padding: 2rem;">Aucune candidature reçue pour le moment.</p>';
        return;
    }
    
    const statusIcons = {
        'pending': { icon: '⏳', color: '#f59e0b', tooltip: 'En attente' },
        'in-process': { icon: '👁️', color: '#3b82f6', tooltip: 'En cours d\'examen' },
        'question': { icon: '❓', color: '#8b5cf6', tooltip: 'Entretien prévu' },
        'rejected': { icon: '❌', color: '#ef4444', tooltip: 'Refusée' },
        'validate': { icon: '✅', color: '#10b981', tooltip: 'Acceptée' },
        'cancelled': { icon: '🚫', color: '#6b7280', tooltip: 'Annulée' },
        'redflag': { icon: '🚫', color: '#6b7280', tooltip: 'Annulée' }
    };
    
    // Commentaires génériques pour les candidatures redflag (annulation par le candidat)
    const genericCancellationComments = [
        "Je ne souhaite plus poursuivre ma candidature pour le moment.",
        "J'ai accepté une autre opportunité professionnelle.",
        "Après réflexion, je préfère m'orienter vers un autre type de poste.",
        "Je retire ma candidature pour des raisons personnelles.",
        "J'ai finalement décidé de ne pas donner suite à cette candidature.",
        "Suite à une évolution de mon projet professionnel, je retire ma candidature.",
        "Je ne suis finalement plus disponible pour ce poste.",
        "Après mûre réflexion, je préfère ne pas poursuivre cette candidature.",
        "J'ai reçu une proposition qui correspond mieux à mes attentes.",
        "Je souhaite annuler ma candidature car mes priorités ont changé."
    ];
    
    let html = '';
    applications.forEach(app => {
        const status = statusIcons[app.status] || statusIcons['pending'];
        const date = new Date(app.created_at).toLocaleDateString('fr-FR');
        const photoUrl = app.candidate_photo_url || '';
        
        // Statuts définitifs
        const finalStatuses = ['cancelled', 'rejected', 'redflag', 'validate'];
        const isFinalStatus = finalStatuses.includes(app.status);
        const isValidated = app.status === 'validate';
        const isRejectedOrCancelled = ['cancelled', 'rejected', 'redflag'].includes(app.status);
        
        // Commentaire pour redflag : toujours afficher un commentaire générique (ne jamais révéler le vrai)
        let displayComment;
        if (app.status === 'redflag') {
            const commentIndex = app.application_id % genericCancellationComments.length;
            displayComment = genericCancellationComments[commentIndex];
        } else {
            displayComment = app.comment;
        }
        
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
                        ${!isRejectedOrCancelled && app.candidate_id ? `
                            <button onclick="viewCV(${app.candidate_id}, ${app.application_id}, '${app.status}')" class="btn-small btn-view-cv">
                                📄 Voir CV
                            </button>
                        ` : ''}
                        ${!isRejectedOrCancelled ? `
                            <button onclick="viewContact(${app.application_id}, '${app.status}')" class="btn-small btn-contact">
                                📞 Contact
                            </button>
                        ` : ''}
                        ${!isFinalStatus ? `
                            <button onclick="validateApplication(${app.application_id})" class="btn-small btn-validate">
                                ✅ Valider
                            </button>
                            <button onclick="rejectApplication(${app.application_id})" class="btn-small btn-reject">
                                ❌ Refuser
                            </button>
                        ` : ''}
                    </div>
                </div>
                
                ${displayComment ? `
                    <div class="application-comment">
                        <strong>💬 Commentaire :</strong>
                        ${displayComment}
                    </div>
                ` : ''}
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// Voir le CV dans une popup avec le viewer
async function viewCV(candidateId, applicationId, currentStatus) {
    const popupContent = document.getElementById('popup-cv-content');
    
    // Afficher le loader
    popupContent.innerHTML = `
        <div style="text-align: center; padding: 3rem;">
            <div class="spinner" style="margin: 0 auto 1rem; border: 4px solid #f3f3f3; border-top: 4px solid #667eea; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite;"></div>
            <p style="color: #777;">Chargement du CV...</p>
        </div>
    `;
    
    openPopup('popup-cv');
    
    // Mettre à jour le statut en "in-process" silencieusement SAUF si déjà validé
    if (applicationId && currentStatus !== 'validate') {
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
        
        // Stocker le media_code pour l'impression
        currentMediaCode = mediaCode;
        
        // Charger le CV HTML
        const cvResponse = await fetch(`<?php echo $baseUrl; ?>/data/candidates/media/${mediaCode}/cv.html`);
        
        if (!cvResponse.ok) {
            throw new Error('CV introuvable');
        }
        
        const cvHTML = await cvResponse.text();
        
        // Afficher le CV
        popupContent.innerHTML = cvHTML;
        

        
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
function viewContact(applicationId, currentStatus) {
    const app = allApplications.find(a => a.application_id == applicationId);
    if (!app) {
        return;
    }
    
    // Mettre à jour le statut en "question" silencieusement SAUF si déjà validé
    if (currentStatus !== 'validate') {
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
    }
    
    const content = `
        <h3 style="margin-top: 0; color: #333;">📞 Informations de contact</h3>
        <div style="padding: 1rem 0;">
            <div style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb;">
                <h4 style="margin: 0 0 0.5rem 0; color: #667eea;">${app.first_name} ${app.last_name}</h4>
                <p style="margin: 0; color: #777; font-size: 0.9rem;">${app.job_title_candidate || 'Poste non renseigné'}</p>
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
    // Ouvrir la popup de refus
    document.getElementById('reject-application-id').value = applicationId;
    document.getElementById('reject-comment').value = ''; // Réinitialiser le commentaire
    openPopup('popup-reject');
}

// Soumettre le refus avec commentaire
function submitRejection() {
    const applicationId = document.getElementById('reject-application-id').value;
    const comment = document.getElementById('reject-comment').value.trim();
    
    if (!applicationId) {
        alert('Erreur: ID de candidature manquant');
        return;
    }
    
    // Fermer la popup
    closePopup('popup-reject');
    
    // Envoyer le refus avec le commentaire
    fetch('<?php echo $baseUrl; ?>/function/updateApplicationStatus.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            application_id: applicationId,
            status: 'rejected',
            comment: comment
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Candidature refusée. Le candidat a été notifié par email.');
            loadApplications(); // Recharger la liste
        } else {
            alert('Erreur: ' + (data.error || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors du refus de la candidature');
    });
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

// Imprimer le CV
function printCV() {
    const cvContent = document.getElementById('popup-cv-content');
    
    if (!cvContent) {
        alert('Aucun CV à imprimer');
        return;
    }
    
    // Chercher le cv-container
    const cvContainer = cvContent.querySelector('.cv-container');
    
    if (!cvContainer) {
        alert('Contenu du CV introuvable');
        return;
    }
    
    // Afficher un message de chargement
    const originalButtonText = document.querySelector('.btn-print-cv').innerHTML;
    document.querySelector('.btn-print-cv').innerHTML = '⏳ Génération...';
    document.querySelector('.btn-print-cv').disabled = true;
    
    // Sauvegarder les styles originaux
    const originalWidth = cvContainer.style.width;
    const originalMaxWidth = cvContainer.style.maxWidth;
    const originalMargin = cvContainer.style.margin;
    const originalHeight = cvContainer.style.height;
    const originalMinHeight = cvContainer.style.minHeight;
    
    // Dimensions A4 en pixels (à 96 DPI: 210mm = 794px, 297mm = 1123px)
    const a4Width = 794;
    const a4Height = 1123;
    
    // Forcer les dimensions A4 pour la capture
    cvContainer.style.width = a4Width + 'px';
    cvContainer.style.maxWidth = a4Width + 'px';
    cvContainer.style.margin = '0 auto';
    cvContainer.style.minHeight = a4Height + 'px';
    
    // Attendre que le DOM se mette à jour
    setTimeout(() => {
        // Obtenir la hauteur réelle du contenu (pour inclure le footer)
        const realHeight = Math.max(cvContainer.scrollHeight, a4Height);
        
        // Capturer le CV en image avec html2canvas
        html2canvas(cvContainer, {
            scale: 3,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff',
            logging: false,
            width: a4Width,
            height: realHeight,
            windowWidth: a4Width,
            windowHeight: realHeight,
            imageTimeout: 0,
            scrollY: -window.scrollY,
            scrollX: -window.scrollX
        }).then(canvas => {
            // Restaurer les styles originaux
            cvContainer.style.width = originalWidth;
            cvContainer.style.maxWidth = originalMaxWidth;
            cvContainer.style.margin = originalMargin;
            cvContainer.style.height = originalHeight;
            cvContainer.style.minHeight = originalMinHeight;
            
            // Restaurer le bouton
            document.querySelector('.btn-print-cv').innerHTML = originalButtonText;
            document.querySelector('.btn-print-cv').disabled = false;
            
            // Convertir le canvas en image PNG haute qualité
            const imgData = canvas.toDataURL('image/png', 1.0);
            
            // Créer une nouvelle fenêtre pour l'impression
            const printWindow = window.open('', '_blank', 'width=900,height=700');
            
            if (!printWindow) {
                alert('Veuillez autoriser les popups pour imprimer le CV');
                return;
            }
            
            // Écrire le contenu dans la nouvelle fenêtre
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Impression CV</title>
                    <style>
                        * {
                            margin: 0;
                            padding: 0;
                            box-sizing: border-box;
                        }
                        
                        html, body {
                            width: 100%;
                            height: 100%;
                        }
                        
                        body {
                            display: flex;
                            justify-content: center;
                            align-items: flex-start;
                            background: white;
                            padding: 0;
                        }
                        
                        img {
                            width: 100%;
                            max-width: 100%;
                            height: auto;
                            display: block;
                            margin: 0 auto;
                        }
                        
                        @media print {
                            @page {
                                size: A4;
                                margin: 0 4mm;
                            }
                            
                            html, body {
                                width: 210mm;
                                height: auto;
                                margin: 0;
                                padding: 0;
                            }
                            
                            body {
                                margin: 0;
                                padding: 0;
                                display: block;
                            }
                            
                            img {
                                width: 210mm;
                                max-width: 210mm;
                                height: auto;
                                display: block;
                                margin: 0;
                                padding: 0;
                                page-break-before: auto;
                                page-break-after: auto;
                                page-break-inside: auto;
                            }
                        }
                    </style>
                </head>
                <body>
                    <img src=\"${imgData}\" alt=\"CV\" onload=\"setTimeout(() => window.print(), 500);\">
                </body>
                </html>
            `);
            
            printWindow.document.close();
        }).catch(error => {
            // Restaurer les styles en cas d'erreur
            cvContainer.style.width = originalWidth;
            cvContainer.style.maxWidth = originalMaxWidth;
            cvContainer.style.margin = originalMargin;
            cvContainer.style.height = originalHeight;
            cvContainer.style.minHeight = originalMinHeight;
            
            console.error('Erreur lors de la capture:', error);
            alert("Erreur lors de la génération de l'image du CV");
            
            // Restaurer le bouton en cas d'erreur
            document.querySelector('.btn-print-cv').innerHTML = originalButtonText;
            document.querySelector('.btn-print-cv').disabled = false;
        });
    }, 100); // Fin du setTimeout
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

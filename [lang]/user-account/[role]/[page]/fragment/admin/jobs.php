<?php
/**
 * Page de gestion des offres d'emploi
 * Affiche la liste des offres avec filtres et actions
 */
?>

<div class="jobs-management">
    <!-- Filtres -->
    <div class="jobs-filters">
        <div class="filter-group">
            <label for="status-filter">
                <span class="filter-icon">📊</span>
                Statut d'approbation
            </label>
            <select id="status-filter" class="filter-select">
                <option value="all">Toutes les offres</option>
                <option value="approved">Approuvées</option>
                <option value="pending">En attente</option>
                <option value="rejected">Refusées</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="keyword-filter">
                <span class="filter-icon">🔍</span>
                Recherche
            </label>
            <input type="text" 
                   id="keyword-filter" 
                   class="filter-input" 
                   placeholder="Titre, entreprise...">
        </div>
        
        <div class="filter-group">
            <label for="location-filter">
                <span class="filter-icon">📍</span>
                Localisation
            </label>
            <input type="text" 
                   id="location-filter" 
                   class="filter-input" 
                   placeholder="Ville, région...">
        </div>
        
        <button id="reset-filters" class="btn-reset-filters">
            🔄 Réinitialiser
        </button>
    </div>
    
    <!-- Compteur de résultats -->
    <div class="jobs-counter">
        <p id="results-count">Chargement des offres d'emploi...</p>
    </div>
    
    <!-- Grille de cartes -->
    <div id="jobs-grid" class="jobs-grid">
        <!-- Les cartes seront chargées ici via JavaScript -->
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Chargement des offres d'emploi...</p>
        </div>
    </div>
    
    <!-- Message si aucun résultat -->
    <div id="no-results" class="no-results" style="display: none;">
        <div class="no-results-icon">🔍</div>
        <h3>Aucune offre d'emploi trouvée</h3>
        <p>Essayez de modifier vos filtres de recherche.</p>
    </div>
</div>

<!-- Popup pour visualiser les offres d'emploi -->
<?php 
    $popupId = 'job-viewer-popup';
    $popupTitle = 'Visualisation de l\'offre d\'emploi';
    $popupSize = 'large';
    include __DIR__ . '/blocks/popup.php';
?>

<!-- Popup pour modifier le secteur d'activité -->
<?php 
    $popupId = 'activity-selector-popup';
    $popupTitle = 'Définir le secteur d\'activité';
    $popupSize = 'medium';
    include __DIR__ . '/blocks/popup.php';
?>

<!-- Inclure les fonctions pour les icônes et couleurs d'activité -->
<?php include __DIR__ . '/../../../../../../[lang]/[page]/fragment/activityIconAndColor.php'; ?>

<script>
// Variables globales
let allJobs = [];
const jobsBaseUrl = (typeof baseUrl !== 'undefined' && baseUrl) ? baseUrl : '<?php echo $baseUrl; ?>';

// Fonction pour obtenir l'icône d'une activité
function getActivityIcon(activityName) {
    if (!activityName) return '💼';
    
    const icons = {
        'technology': '💻',
        'technologie': '💻',
        'health': '🏥',
        'santé': '🏥',
        'education': '🎓',
        'éducation': '🎓',
        'finance': '💰',
        'retail': '🛒',
        'commerce': '🛒',
        'manufacturing': '🏭',
        'industrie': '🏭',
        'construction': '🏗️',
        'transport': '🚚',
        'hospitality': '🏨',
        'hôtellerie': '🏨',
        'agriculture': '🌾',
        'energy': '⚡',
        'énergie': '⚡',
        'media': '📰',
        'médias': '📰',
        'entertainment': '🎬',
        'divertissement': '🎬',
        'consulting': '💼',
        'conseil': '💼',
        'legal': '⚖️',
        'juridique': '⚖️',
        'real estate': '🏘️',
        'immobilier': '🏘️',
        'telecommunications': '📡',
        'télécommunications': '📡'
    };
    
    const key = activityName.toLowerCase();
    return icons[key] || '💼';
}

// Fonction pour obtenir la couleur d'une activité
function getActivityColor(activityName) {
    if (!activityName) return 'style="background: #f3f4f6; color: #6b7280;"';
    
    const colors = {
        'technology': 'style="background: #dbeafe; color: #1e40af;"',
        'technologie': 'style="background: #dbeafe; color: #1e40af;"',
        'health': 'style="background: #dcfce7; color: #166534;"',
        'santé': 'style="background: #dcfce7; color: #166534;"',
        'education': 'style="background: #fef3c7; color: #92400e;"',
        'éducation': 'style="background: #fef3c7; color: #92400e;"',
        'finance': 'style="background: #d1fae5; color: #065f46;"',
        'retail': 'style="background: #fce7f3; color: #9f1239;"',
        'commerce': 'style="background: #fce7f3; color: #9f1239;"',
        'manufacturing': 'style="background: #e0e7ff; color: #3730a3;"',
        'industrie': 'style="background: #e0e7ff; color: #3730a3;"',
        'construction': 'style="background: #fed7aa; color: #92400e;"',
        'transport': 'style="background: #cffafe; color: #155e75;"',
        'hospitality': 'style="background: #fae8ff; color: #86198f;"',
        'hôtellerie': 'style="background: #fae8ff; color: #86198f;"',
        'agriculture': 'style="background: #d9f99d; color: #3f6212;"',
        'energy': 'style="background: #fef9c3; color: #713f12;"',
        'énergie': 'style="background: #fef9c3; color: #713f12;"',
        'media': 'style="background: #ddd6fe; color: #5b21b6;"',
        'médias': 'style="background: #ddd6fe; color: #5b21b6;"',
        'entertainment': 'style="background: #fbcfe8; color: #831843;"',
        'divertissement': 'style="background: #fbcfe8; color: #831843;"',
        'consulting': 'style="background: #e5e7eb; color: #1f2937;"',
        'conseil': 'style="background: #e5e7eb; color: #1f2937;"',
        'legal': 'style="background: #f3e8ff; color: #6b21a8;"',
        'juridique': 'style="background: #f3e8ff; color: #6b21a8;"',
        'real estate': 'style="background: #fecaca; color: #991b1b;"',
        'immobilier': 'style="background: #fecaca; color: #991b1b;"',
        'telecommunications': 'style="background: #bfdbfe; color: #1e3a8a;"',
        'télécommunications': 'style="background: #bfdbfe; color: #1e3a8a;"'
    };
    
    const key = activityName.toLowerCase();
    return colors[key] || 'style="background: #f3f4f6; color: #6b7280;"';
}

// Charger les offres d'emploi
async function loadJobs() {
    try {
        const statusFilter = document.getElementById('status-filter').value;
        const keywordFilter = document.getElementById('keyword-filter').value;
        const locationFilter = document.getElementById('location-filter').value;
        
        // Construire l'URL avec les paramètres
        const params = new URLSearchParams({
            status: statusFilter,
            keyword: keywordFilter,
            location: locationFilter
        });
        
        const response = await fetch(`${jobsBaseUrl}/function/getJobs.php?${params}`);
        const data = await response.json();
        
        if (data.success) {
            allJobs = data.jobs;
            displayJobs(allJobs);
            updateResultsCount(data.count);
        } else {
            console.error('Erreur:', data.error);
            showError('Erreur lors du chargement des offres: ' + data.error);
        }
    } catch (error) {
        console.error('Erreur de chargement:', error);
        showError('Erreur de connexion au serveur');
    }
}

// Afficher les offres d'emploi
function displayJobs(jobs) {
    const grid = document.getElementById('jobs-grid');
    const noResults = document.getElementById('no-results');
    
    if (jobs.length === 0) {
        grid.style.display = 'none';
        noResults.style.display = 'flex';
        return;
    }
    
    grid.style.display = 'grid';
    noResults.style.display = 'none';
    
    grid.innerHTML = jobs.map(job => createJobCard(job)).join('');
}

// Créer une carte d'offre d'emploi
function createJobCard(job) {
    const statusInfo = getStatusInfo(job.approved);
    const isPending = job.approved === null || job.approved === 'null' || job.approved === '';
    
    return `
        <div class="job-card" data-job-id="${job.id}">
            <div class="job-status ${statusInfo.class}">
                <span class="status-icon">${statusInfo.icon}</span>
                <span class="status-label">${statusInfo.label}</span>
            </div>
            
            <div class="job-header">
                <h3 class="job-title">${escapeHtml(job.title)}</h3>
                <p class="job-company">🏢 ${escapeHtml(job.company_name || 'Entreprise non renseignée')}</p>
            </div>
            
            <div class="job-info">
                ${job.location ? `<p class="job-location">📍 ${escapeHtml(job.location)}</p>` : ''}
                ${job.contract_type ? `<p class="job-contract">📝 ${escapeHtml(job.contract_type)}</p>` : ''}
                ${job.salary_formatted ? `<p class="job-salary">💰 ${escapeHtml(job.salary_formatted)}</p>` : ''}
                ${job.start_date_formatted ? `<p class="job-start-date">📅 Début: ${escapeHtml(job.start_date_formatted)}</p>` : ''}
                <p class="job-activity" ${getActivityColor(job.activity_name)}>
                    ${getActivityIcon(job.activity_name)} ${job.activity_fr_name || job.activity_name || 'Secteur non spécifié'}
                </p>
            </div>
            
            <div class="job-description">
                <p>${truncateText(escapeHtml(job.description || ''), 120)}</p>
            </div>
            
            <div class="job-actions">
                <button class="btn-action btn-view" 
                        data-job-id="${job.id}"
                        title="Voir l'offre complète">
                    <span class="btn-icon">👁️</span>
                    <span class="btn-text">Voir l'offre</span>
                </button>
                
                <button class="btn-action btn-activity" 
                        data-job-id="${job.id}"
                        data-activity-id="${job.activity_id || ''}"
                        title="Modifier le secteur d'activité">
                    <span class="btn-icon">🏢</span>
                    <span class="btn-text">Secteur</span>
                </button>
            </div>
            
            ${isPending ? `
                <div class="approval-actions">
                    <button class="btn-approve" 
                            data-job-id="${job.id}" 
                            data-action="approve"
                            title="Approuver cette offre">
                        <span class="btn-icon">✓</span>
                        <span class="btn-text">Approuver</span>
                    </button>
                    
                    <button class="btn-reject" 
                            data-job-id="${job.id}" 
                            data-action="reject"
                            title="Refuser cette offre">
                        <span class="btn-icon">✗</span>
                        <span class="btn-text">Refuser</span>
                    </button>
                </div>
            ` : ''}
        </div>
    `;
}

// Obtenir les informations de statut
function getStatusInfo(approved) {
    if (approved === '1' || approved === 1) {
        return { class: 'status-approved', label: 'Approuvée', icon: '✓' };
    } else if (approved === '0' || approved === 0) {
        return { class: 'status-rejected', label: 'Refusée', icon: '✗' };
    } else {
        return { class: 'status-pending', label: 'En attente', icon: '⏳' };
    }
}

// Échapper les caractères HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Tronquer le texte
function truncateText(text, length) {
    if (text.length <= length) return text;
    return text.substring(0, length) + '...';
}

// Mettre à jour le compteur de résultats
function updateResultsCount(count) {
    const counter = document.getElementById('results-count');
    if (count === 0) {
        counter.textContent = 'Aucune offre d\'emploi trouvée';
    } else if (count === 1) {
        counter.textContent = '1 offre d\'emploi trouvée';
    } else {
        counter.textContent = `${count} offres d'emploi trouvées`;
    }
}

// Afficher une erreur
function showError(message) {
    const grid = document.getElementById('jobs-grid');
    grid.innerHTML = `
        <div class="error-message">
            <span class="error-icon">⚠️</span>
            <p>${message}</p>
        </div>
    `;
}

// Réinitialiser les filtres
function resetFilters() {
    document.getElementById('status-filter').value = 'all';
    document.getElementById('keyword-filter').value = '';
    document.getElementById('location-filter').value = '';
    loadJobs();
}

// Event listeners
document.getElementById('status-filter').addEventListener('change', loadJobs);
document.getElementById('keyword-filter').addEventListener('input', debounce(loadJobs, 500));
document.getElementById('location-filter').addEventListener('input', debounce(loadJobs, 500));
document.getElementById('reset-filters').addEventListener('click', resetFilters);

// Fonction debounce pour éviter trop de requêtes
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Gérer l'approbation/refus d'une offre d'emploi
async function handleApproval(jobId, approved) {
    try {
        const response = await fetch(`${jobsBaseUrl}/function/approveCard.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                type: 'job',
                id: jobId,
                approved: approved
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Construire le message de notification
            let message = data.message;
            if (data.emailSent) {
                message += ' - Email envoyé à ' + (data.emailInfo?.recipientEmail || 'l\'entreprise');
            } else if (data.emailInfo && !data.emailSent) {
                message += ' (Attention: L\'email de notification n\'a pas pu être envoyé)';
            }
            
            // Afficher un message de succès
            showNotification(message, 'success');
            
            // Recharger les offres pour mettre à jour l'affichage
            await loadJobs();
        } else {
            throw new Error(data.error || 'Erreur lors de l\'approbation');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('Erreur: ' + error.message, 'error');
    }
}

// Afficher une notification
function showNotification(message, type = 'info') {
    // Créer l'élément de notification
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Ajouter au body
    document.body.appendChild(notification);
    
    // Afficher avec animation
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Supprimer après 3 secondes
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Délégation d'événements pour les boutons d'action
document.addEventListener('click', async (e) => {
    const approveBtn = e.target.closest('.btn-approve');
    const rejectBtn = e.target.closest('.btn-reject');
    const viewJobBtn = e.target.closest('.btn-view');
    const activityBtn = e.target.closest('.btn-activity');
    
    if (approveBtn) {
        e.preventDefault();
        const jobId = approveBtn.dataset.jobId;
        
        if (confirm('Êtes-vous sûr de vouloir approuver cette offre d\'emploi ?')) {
            approveBtn.disabled = true;
            approveBtn.textContent = 'Approbation...';
            await handleApproval(jobId, 1);
        }
    }
    
    if (rejectBtn) {
        e.preventDefault();
        const jobId = rejectBtn.dataset.jobId;
        
        if (confirm('Êtes-vous sûr de vouloir refuser cette offre d\'emploi ?')) {
            rejectBtn.disabled = true;
            rejectBtn.textContent = 'Refus...';
            await handleApproval(jobId, 0);
        }
    }
    
    if (viewJobBtn) {
        e.preventDefault();
        const jobId = viewJobBtn.dataset.jobId;
        await openJobViewer(jobId);
    }
    
    if (activityBtn) {
        e.preventDefault();
        const jobId = activityBtn.dataset.jobId;
        const currentActivityId = activityBtn.dataset.activityId;
        await openActivitySelector(jobId, currentActivityId);
    }
});

// Ouvrir le viewer d'offre d'emploi
async function openJobViewer(jobId) {
    const popupId = 'job-viewer-popup';
    const url = `${jobsBaseUrl}/function/getViewerJob.php`;
    
    // Utiliser loadPopupContent du script popup.js
    // Passer context=user-account pour afficher le validation-notice
    await loadPopupContent(popupId, url, { id: jobId, context: 'user-account' });
}

// Ouvrir le sélecteur de secteur d'activité
async function openActivitySelector(jobId, currentActivityId) {
    const popupId = 'activity-selector-popup';
    
    try {
        // Charger la liste des secteurs d'activité
        const response = await fetch(`${jobsBaseUrl}/function/getActivities.php`);
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.error || 'Erreur lors du chargement des secteurs');
        }
        
        const activities = data.activities;
        
        // Générer le contenu HTML de la popup
        const content = `
            <div class="activity-selector">
                <p class="activity-selector-info">
                    <strong>📋 Information :</strong> Sélectionnez le secteur d'activité correspondant à cette offre d'emploi.
                </p>
                
                <div class="activity-grid">
                    <div class="activity-option ${!currentActivityId ? 'selected' : ''}" data-activity-id="">
                        <div class="activity-icon">💼</div>
                        <div class="activity-name">Non spécifié</div>
                    </div>
                    ${activities.map(activity => `
                        <div class="activity-option ${currentActivityId == activity.id ? 'selected' : ''}" data-activity-id="${activity.id}">
                            <div class="activity-icon">${getActivityIcon(activity.name)}</div>
                            <div class="activity-name">${escapeHtml(activity.fr_name)}</div>
                        </div>
                    `).join('')}
                </div>
                
                <div class="activity-actions">
                    <button class="btn-cancel" onclick="document.getElementById('${popupId}').classList.remove('active'); document.body.classList.remove('popup-open');">
                        ✗ Annuler
                    </button>
                    <button class="btn-save-activity" data-job-id="${jobId}">
                        ✓ Enregistrer
                    </button>
                </div>
            </div>
        `;
        
        // Afficher la popup avec le contenu
        const popup = document.getElementById(popupId);
        if (!popup) {
            throw new Error('Popup non trouvée : ' + popupId);
        }
        
        // Essayer différents sélecteurs pour le contenu de la popup
        const popupContent = popup.querySelector('.popup-body') || 
                           popup.querySelector('.popup-content') || 
                           popup.querySelector('[data-popup-content]');
        
        if (!popupContent) {
            console.error('Structure de la popup:', popup.innerHTML.substring(0, 200));
            throw new Error('Contenu de la popup non trouvé. Vérifiez la structure de blocks/popup.php');
        }
        
        // Injecter le contenu
        popupContent.innerHTML = content;
        
        // Afficher la popup
        popup.classList.add('active');
        document.body.classList.add('popup-open');
        
        // Gérer la sélection
        const options = document.querySelectorAll('.activity-option');
        options.forEach(option => {
            option.addEventListener('click', function() {
                options.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
        
        // Gérer l'enregistrement
        const saveBtn = document.querySelector('.btn-save-activity');
        saveBtn.addEventListener('click', async function() {
            const selectedOption = document.querySelector('.activity-option.selected');
            const activityId = selectedOption ? selectedOption.dataset.activityId : null;
            
            await saveJobActivity(jobId, activityId || null);
        });
        
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('Erreur: ' + error.message, 'error');
    }
}

// Sauvegarder le secteur d'activité d'une offre
async function saveJobActivity(jobId, activityId) {
    try {
        const response = await fetch(`${jobsBaseUrl}/function/updateJobActivity.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                job_id: jobId,
                activity_id: activityId
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            
            // Fermer la popup
            const popup = document.getElementById('activity-selector-popup');
            popup.classList.remove('active');
            document.body.classList.remove('popup-open');
            
            // Recharger les offres pour afficher le changement
            await loadJobs();
        } else {
            throw new Error(data.error || 'Erreur lors de la sauvegarde');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('Erreur: ' + error.message, 'error');
    }
}

// Charger les offres au chargement de la page
loadJobs();
</script>

<style>
.jobs-management {
    padding: 0;
}

/* Filtres */
.jobs-filters {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: flex-end;
}

.filter-group {
    flex: 1;
    min-width: 200px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-group label {
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 6px;
}

.filter-icon {
    font-size: 1.1rem;
}

.filter-select,
.filter-input {
    padding: 10px 14px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: white;
}

.filter-select:focus,
.filter-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-reset-filters {
    padding: 10px 20px;
    background: #f3f4f6;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-weight: 600;
    color: #374151;
    cursor: pointer;
    transition: all 0.3s ease;
    align-self: flex-end;
}

.btn-reset-filters:hover {
    background: #e5e7eb;
    transform: translateY(-2px);
}

/* Compteur */
.jobs-counter {
    margin-bottom: 20px;
}

.jobs-counter p {
    color: #6b7280;
    font-size: 1rem;
    font-weight: 500;
}

/* Grille de cartes */
.jobs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 25px;
}

.job-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.job-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

/* Statut */
.job-status {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 0px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
    z-index: 11111111;
}

.status-approved {
    background: #d1fae5;
    color: #065f46;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-rejected {
    background: #fee2e2;
    color: #991b1b;
}

.status-icon {
    font-size: 1rem;
}

/* Header de la carte */
.job-header {
    padding-right: 80px; /* Pour éviter le chevauchement avec le badge de statut */
}

.job-title {
    font-size: 1.3rem;
    color: #1f2937;
    margin: 0 0 8px 0;
    font-weight: 700;
    line-height: 1.3;
}

.job-company {
    font-size: 1rem;
    color: #667eea;
    margin: 0;
    font-weight: 600;
}

/* Informations */
.job-info {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.job-info p {
    margin: 0;
    color: #6b7280;
    font-size: 0.9rem;
}

.job-location,
.job-contract,
.job-salary,
.job-start-date {
    display: flex;
    align-items: center;
}

/* Description */
.job-description {
    flex: 1;
}

.job-description p {
    color: #374151;
    font-size: 0.95rem;
    line-height: 1.6;
    margin: 0;
}

/* Actions */
.job-actions {
    display: flex;
    gap: 10px;
    padding-top: 10px;
    border-top: 2px solid #f3f4f6;
}

.btn-action {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-view {
    background: linear-gradient(135deg, #1e93e5 0%, #4b97a2 100%);
    color: white;
}

.btn-view:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-activity {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.btn-activity:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
}

/* Secteur d'activité */
.job-activity {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 16px;
    font-size: 0.85rem;
    font-weight: 600;
}

/* Boutons d'approbation */
.approval-actions {
    display: flex;
    gap: 10px;
    padding-top: 10px;
    border-top: 2px solid #f3f4f6;
    margin-top: 10px;
}

.btn-approve,
.btn-reject {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 16px;
    border-radius: 8px;
    border: none;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-approve {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn-approve:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.btn-reject {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-reject:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

.btn-approve:disabled,
.btn-reject:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Notifications */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 16px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.95rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    opacity: 0;
    transform: translateX(400px);
    transition: all 0.3s ease;
    z-index: 10000;
}

.notification.show {
    opacity: 1;
    transform: translateX(0);
}

.notification-success {
    background: #d1fae5;
    color: #065f46;
    border-left: 4px solid #059669;
}

.notification-error {
    background: #fee2e2;
    color: #991b1b;
    border-left: 4px solid #dc2626;
}

.notification-info {
    background: #dbeafe;
    color: #1e40af;
    border-left: 4px solid #3b82f6;
}

/* Loading */
.loading-spinner {
    grid-column: 1 / -1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    color: #6b7280;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #e5e7eb;
    border-top-color: #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* No results */
.no-results {
    display: none;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 80px 20px;
    text-align: center;
}

.no-results-icon {
    font-size: 5rem;
    opacity: 0.3;
}

.no-results h3 {
    color: #374151;
    margin: 20px 0 10px;
}

.no-results p {
    color: #6b7280;
}

/* Error */
.error-message {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    background: #fee2e2;
    border-radius: 12px;
    color: #991b1b;
}

.error-icon {
    font-size: 3rem;
}

/* Responsive */
@media (max-width: 768px) {
    .jobs-grid {
        grid-template-columns: 1fr;
    }
    
    .jobs-filters {
        flex-direction: column;
    }
    
    .filter-group {
        width: 100%;
    }
    
    .btn-reset-filters {
        width: 100%;
    }
    
    .job-header {
        padding-right: 0;
        padding-bottom: 40px; /* Pour le badge de statut */
    }
    
    .job-status {
        top: auto;
        bottom: 15px;
    }
}

/* ========================================
   Styles pour la popup de sélection d'activité
   ======================================== */

.activity-selector {
    padding: 0;
}

.activity-selector-info {
    background: #f3f4f6;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    color: #374151;
    font-size: 0.95rem;
    border-left: 4px solid #667eea;
}

.activity-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 15px;
    max-height: 400px;
    overflow-y: auto;
    padding: 10px;
    margin-bottom: 20px;
}

.activity-option {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px 15px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.activity-option:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #667eea;
}

.activity-option.selected {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
}

.activity-icon {
    font-size: 2.5rem;
}

.activity-name {
    font-size: 0.9rem;
    font-weight: 600;
    line-height: 1.3;
}

.activity-option.selected .activity-name {
    color: white;
}

.activity-actions {
    display: flex;
    gap: 15px;
    padding-top: 15px;
    border-top: 2px solid #e5e7eb;
    justify-content: flex-end;
}

.btn-cancel,
.btn-save-activity {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-cancel {
    background: #f3f4f6;
    color: #374151;
}

.btn-cancel:hover {
    background: #e5e7eb;
    transform: translateY(-2px);
}

.btn-save-activity {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn-save-activity:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.btn-save-activity:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Scrollbar personnalisée pour la grille */
.activity-grid::-webkit-scrollbar {
    width: 8px;
}

.activity-grid::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 4px;
}

.activity-grid::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 4px;
}

.activity-grid::-webkit-scrollbar-thumb:hover {
    background: #5568d3;
}
</style>

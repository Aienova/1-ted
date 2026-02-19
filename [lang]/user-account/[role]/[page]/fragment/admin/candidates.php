<?php
/**
 * Page de gestion des candidats
 * Affiche la liste des candidats avec filtres et actions
 */
?>

<div class="candidates-management">
    <!-- Filtres -->
    <div class="candidates-filters">
        <div class="filter-group">
            <label for="status-filter">
                <span class="filter-icon">🔍</span>
                Filtrer par statut:
            </label>
            <select id="status-filter" class="filter-select">
                <option value="all">Tous les candidats</option>
                <option value="approved">✓ Approuvés</option>
                <option value="pending">⏳ En attente</option>
                <option value="rejected">✗ Refusés</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="keyword-filter">
                <span class="filter-icon">🔎</span>
                Recherche:
            </label>
            <input type="text" 
                   id="keyword-filter" 
                   class="filter-input" 
                   placeholder="Nom, prénom ou métier...">
        </div>
        
        <div class="filter-group">
            <label for="location-filter">
                <span class="filter-icon">📍</span>
                Localisation:
            </label>
            <input type="text" 
                   id="location-filter" 
                   class="filter-input" 
                   placeholder="Ville ou région...">
        </div>
        
        <button id="reset-filters" class="btn-reset-filters">
            🔄 Réinitialiser
        </button>
    </div>
    
    <!-- Compteur de résultats -->
    <div class="candidates-counter">
        <p id="results-count">Chargement des candidats...</p>
    </div>
    
    <!-- Grille de cartes -->
    <div id="candidates-grid" class="candidates-grid">
        <!-- Les cartes seront chargées ici via JavaScript -->
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Chargement des candidats...</p>
        </div>
    </div>
    
    <!-- Message si aucun résultat -->
    <div id="no-results" class="no-results" style="display: none;">
        <div class="no-results-icon">🔍</div>
        <h3>Aucun candidat trouvé</h3>
        <p>Essayez de modifier vos filtres de recherche.</p>
    </div>
</div>

<!-- Popup pour visualiser les CV -->
<?php 
    $popupId = 'cv-viewer-popup';
    $popupTitle = 'Visualisation du CV';
    $popupSize = 'large';
    include __DIR__ . '/blocks/popup.php';
?>

<script>
// Variables globales
let allCandidates = [];
const baseUrl = '<?php echo $baseUrl; ?>';
const lang = '<?php echo $lang; ?>';

// Charger les candidats
async function loadCandidates() {
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
        
        const response = await fetch(`${baseUrl}/function/getCandidates.php?${params}`);
        const data = await response.json();
        
        if (data.success) {
            allCandidates = data.candidates;
            displayCandidates(allCandidates);
            updateResultsCount(data.count);
        } else {
            console.error('Erreur:', data.error);
            showError('Erreur lors du chargement des candidats: ' + data.error);
        }
    } catch (error) {
        console.error('Erreur de chargement:', error);
        showError('Erreur de connexion au serveur');
    }
}

// Afficher les candidats
function displayCandidates(candidates) {
    const grid = document.getElementById('candidates-grid');
    const noResults = document.getElementById('no-results');
    
    if (candidates.length === 0) {
        grid.style.display = 'none';
        noResults.style.display = 'flex';
        return;
    }
    
    grid.style.display = 'grid';
    noResults.style.display = 'none';
    
    grid.innerHTML = candidates.map(candidate => createCandidateCard(candidate)).join('');
}

// Créer une carte de candidat
function createCandidateCard(candidate) {
    const statusInfo = getStatusInfo(candidate.approved);
    const photoUrl = candidate.photo_url ? `${baseUrl}/${candidate.photo_url}` : `${baseUrl}/data/candidates/media/default/photo.jpg`;
    const cvUrl = `${baseUrl}/${lang}/data/candidates/cv/${candidate.id}`;
    const isPending = candidate.approved === null || candidate.approved === 'null' || candidate.approved === '';
    
    return `
        <div class="candidate-card" data-candidate-id="${candidate.id}">
            <div class="candidate-status ${statusInfo.class}">
                <span class="status-icon">${statusInfo.icon}</span>
                <span class="status-label">${statusInfo.label}</span>
            </div>
            
            <div class="candidate-photo">
                <img src="${photoUrl}" 
                     alt="Photo de ${candidate.first_name} ${candidate.last_name}"
                     onerror="this.src='${baseUrl}/data/candidates/media/default/photo.jpg'">
            </div>
            
            <div class="candidate-info">
                <h3 class="candidate-name">${candidate.first_name} ${candidate.last_name}</h3>
                <p class="candidate-job-title">${candidate.job_title || 'Métier non renseigné'}</p>
                ${candidate.location ? `<p class="candidate-location">📍 ${candidate.location}</p>` : ''}
                ${candidate.age ? `<p class="candidate-age">🎂 ${candidate.age} ans</p>` : ''}
            </div>
            
            <div class="candidate-actions">
                <button class="btn-action btn-view" 
                        data-candidate-id="${candidate.id}"
                        title="Voir le CV">
                    <span class="btn-icon">👁️</span>
                    <span class="btn-text">Voir CV</span>
                </button>
                ${candidate.email ? `
                    <a href="mailto:${candidate.email}" class="btn-action btn-email" title="Envoyer un email">
                        <span class="btn-icon">✉️</span>
                        <span class="btn-text">Email</span>
                    </a>
                ` : ''}
            </div>
            
            ${isPending ? `
                <div class="approval-actions">
                    <button class="btn-approve" 
                            data-candidate-id="${candidate.id}" 
                            data-action="approve"
                            title="Approuver ce candidat">
                        <span class="btn-icon">✓</span>
                        <span class="btn-text">Approuver</span>
                    </button>
                    
                    <button class="btn-reject" 
                            data-candidate-id="${candidate.id}" 
                            data-action="reject"
                            title="Refuser ce candidat">
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
        return { class: 'status-approved', label: 'Approuvé', icon: '✓' };
    } else if (approved === '0' || approved === 0) {
        return { class: 'status-rejected', label: 'Refusé', icon: '✗' };
    } else {
        return { class: 'status-pending', label: 'En attente', icon: '⏳' };
    }
}

// Mettre à jour le compteur de résultats
function updateResultsCount(count) {
    const counter = document.getElementById('results-count');
    if (count === 0) {
        counter.textContent = 'Aucun candidat trouvé';
    } else if (count === 1) {
        counter.textContent = '1 candidat trouvé';
    } else {
        counter.textContent = `${count} candidats trouvés`;
    }
}

// Afficher une erreur
function showError(message) {
    const grid = document.getElementById('candidates-grid');
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
    loadCandidates();
}

// Event listeners
document.getElementById('status-filter').addEventListener('change', loadCandidates);
document.getElementById('keyword-filter').addEventListener('input', debounce(loadCandidates, 500));
document.getElementById('location-filter').addEventListener('input', debounce(loadCandidates, 500));
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

// Gérer l'approbation/refus d'un candidat
async function handleApproval(candidateId, approved) {
    try {
        const response = await fetch(`${baseUrl}/function/approveCard.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                type: 'candidate',
                id: candidateId,
                approved: approved
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Afficher un message de succès
            showNotification(data.message, 'success');
            
            // Recharger les candidats pour mettre à jour l'affichage
            await loadCandidates();
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

// Délégation d'événements pour les boutons d'approbation
document.addEventListener('click', async (e) => {
    const approveBtn = e.target.closest('.btn-approve');
    const rejectBtn = e.target.closest('.btn-reject');
    const viewCvBtn = e.target.closest('.btn-view');
    
    if (approveBtn) {
        e.preventDefault();
        const candidateId = approveBtn.dataset.candidateId;
        
        if (confirm('Êtes-vous sûr de vouloir approuver ce candidat ?')) {
            approveBtn.disabled = true;
            approveBtn.textContent = 'Approbation...';
            await handleApproval(candidateId, 1);
        }
    }
    
    if (rejectBtn) {
        e.preventDefault();
        const candidateId = rejectBtn.dataset.candidateId;
        
        if (confirm('Êtes-vous sûr de vouloir refuser ce candidat ?')) {
            rejectBtn.disabled = true;
            rejectBtn.textContent = 'Refus...';
            await handleApproval(candidateId, 0);
        }
    }
    
    if (viewCvBtn) {
        e.preventDefault();
        const candidateId = viewCvBtn.dataset.candidateId;
        await openCvViewer(candidateId);
    }
});

// Ouvrir le viewer de CV
async function openCvViewer(candidateId) {
    const popupId = 'cv-viewer-popup';
    const url = `${baseUrl}/function/getViewerCV.php`;
    
    // Utiliser loadPopupContent du script popup.js
    await loadPopupContent(popupId, url, { id: candidateId });
}

// Charger les candidats au chargement de la page
loadCandidates();
</script>

<style>
.candidates-management {
    padding: 0;
}

/* Filtres */
.candidates-filters {
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
.candidates-counter {
    margin-bottom: 20px;
}

.candidates-counter p {
    color: #6b7280;
    font-size: 1rem;
    font-weight: 500;
}

/* Grille de cartes */
.candidates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
}

.candidate-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.candidate-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

/* Statut */
.candidate-status {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
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

/* Photo */
.candidate-photo {
    width: 100px;
    height: 100px;
    margin: 0 auto;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid #e5e7eb;
}

.candidate-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Informations */
.candidate-info {
    text-align: center;
    flex: 1;
}

.candidate-name {
    font-size: 1.2rem;
    color: #1f2937;
    margin: 0 0 8px 0;
    font-weight: 700;
}

.candidate-job-title {
    font-size: 1rem;
    color: #667eea;
    margin: 0 0 10px 0;
    font-weight: 600;
}

.candidate-location,
.candidate-age {
    font-size: 0.9rem;
    color: #6b7280;
    margin: 4px 0;
}

/* Actions */
.candidate-actions {
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-view:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-email {
    background: #f3f4f6;
    color: #374151;
    border: 2px solid #e5e7eb;
}

.btn-email:hover {
    background: #e5e7eb;
    transform: translateY(-2px);
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
    z-index: 10000;
    opacity: 0;
    transform: translateX(400px);
    transition: all 0.3s ease;
}

.notification.show {
    opacity: 1;
    transform: translateX(0);
}

.notification-success {
    background: #d1fae5;
    color: #065f46;
    border-left: 4px solid #10b981;
}

.notification-error {
    background: #fee2e2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
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
    background: white;
    border-radius: 12px;
    padding: 60px 20px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.no-results-icon {
    font-size: 4rem;
    opacity: 0.3;
}

.no-results h3 {
    color: #374151;
    margin: 0;
}

.no-results p {
    color: #6b7280;
    margin: 0;
}

/* Error */
.error-message {
    grid-column: 1 / -1;
    background: #fee2e2;
    color: #991b1b;
    padding: 20px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.error-icon {
    font-size: 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .candidates-filters {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-group {
        min-width: 100%;
    }
    
    .btn-reset-filters {
        width: 100%;
    }
    
    .candidates-grid {
        grid-template-columns: 1fr;
    }
    
    .candidate-actions {
        flex-direction: column;
    }
    
    .approval-actions {
        flex-direction: column;
    }
    
    .notification {
        right: 10px;
        left: 10px;
        top: 10px;
    }
}
</style>

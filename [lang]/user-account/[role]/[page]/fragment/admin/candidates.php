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

<!-- Popup pour sélectionner un avatar -->
<div id="avatar-selector-popup" class="avatar-popup-overlay">
    <div class="avatar-popup">
        <div class="avatar-popup-header">
            <h3>🎭 Assigner un avatar</h3>
            <button class="avatar-popup-close" onclick="closeAvatarSelector()">&times;</button>
        </div>
        
        <div class="avatar-popup-content">
            <!-- Sélection du genre -->
            <div class="gender-selection">
                <h4>Sélectionnez le genre de l'avatar :</h4>
                <div class="gender-options">
                    <label class="gender-option">
                        <input type="radio" name="avatar-gender" id="gender-male" value="men">
                        <span class="gender-label">
                            <span class="gender-icon">👨</span>
                            <span>Homme</span>
                        </span>
                    </label>
                    <label class="gender-option">
                        <input type="radio" name="avatar-gender" id="gender-female" value="women">
                        <span class="gender-label">
                            <span class="gender-icon">👩</span>
                            <span>Femme</span>
                        </span>
                    </label>
                </div>
            </div>
            
            <!-- Aperçu de l'avatar -->
            <div class="avatar-preview-section">
                <h4>Aperçu :</h4>
                <div class="avatar-preview-container">
                    <img id="avatar-preview" src="/media/image/icon/avatar/default.png" alt="Aperçu avatar">
                </div>
            </div>
            
            <!-- Galerie d'avatars -->
            <div class="avatar-gallery-section">
                <h4>Choisissez un avatar :</h4>
                <div id="avatar-gallery" class="avatar-gallery">
                    <p style="text-align: center; color: #999; padding: 40px;">Sélectionnez un genre pour afficher les avatars</p>
                </div>
            </div>
        </div>
        
        <div class="avatar-popup-actions">
            <button id="save-avatar-btn" class="btn-save-avatar" onclick="saveAvatar()" disabled>
                💾 Sauvegarder
            </button>
            <button class="btn-cancel-avatar" onclick="closeAvatarSelector()">
                ❌ Annuler
            </button>
        </div>
    </div>
</div>

<!-- Popup pour changer la séniorité -->
<div id="seniority-selector-popup" class="seniority-popup-overlay">
    <div class="seniority-popup">
        <div class="seniority-popup-header">
            <h3>🎯 Définir la séniorité</h3>
            <button class="seniority-popup-close" onclick="closeSenioritySelector()">&times;</button>
        </div>
        
        <div class="seniority-popup-content">
            <p class="seniority-description">Sélectionnez le niveau de séniorité pour ce candidat :</p>
            
            <!-- Options de séniorité -->
            <div class="seniority-options">
                <label class="seniority-option">
                    <input type="radio" name="candidate-seniority" value="0">
                    <span class="seniority-label seniority-apprentice">
                        <span class="seniority-icon">🎓</span>
                        <div class="seniority-text">
                            <strong>Apprenti</strong>
                            <small>En formation ou stage</small>
                        </div>
                    </span>
                </label>
                
                <label class="seniority-option">
                    <input type="radio" name="candidate-seniority" value="1">
                    <span class="seniority-label seniority-junior">
                        <span class="seniority-icon">🌱</span>
                        <div class="seniority-text">
                            <strong>Junior</strong>
                            <small>0-2 ans d'expérience</small>
                        </div>
                    </span>
                </label>
                
                <label class="seniority-option">
                    <input type="radio" name="candidate-seniority" value="2">
                    <span class="seniority-label seniority-confirmed">
                        <span class="seniority-icon">💼</span>
                        <div class="seniority-text">
                            <strong>Confirmé</strong>
                            <small>2-5 ans d'expérience</small>
                        </div>
                    </span>
                </label>
                
                <label class="seniority-option">
                    <input type="radio" name="candidate-seniority" value="3">
                    <span class="seniority-label seniority-senior">
                        <span class="seniority-icon">⭐</span>
                        <div class="seniority-text">
                            <strong>Senior</strong>
                            <small>5+ ans d'expérience</small>
                        </div>
                    </span>
                </label>
                
                <label class="seniority-option">
                    <input type="radio" name="candidate-seniority" value="">
                    <span class="seniority-label seniority-none">
                        <span class="seniority-icon">⚪</span>
                        <div class="seniority-text">
                            <strong>Non spécifié</strong>
                            <small>Retirer la séniorité</small>
                        </div>
                    </span>
                </label>
            </div>
        </div>
        
        <div class="seniority-popup-actions">
            <button id="save-seniority-btn" class="btn-save-seniority" onclick="saveCandidateSeniority()" disabled>
                💾 Sauvegarder
            </button>
            <button class="btn-cancel-seniority" onclick="closeSenioritySelector()">
                ❌ Annuler
            </button>
        </div>
    </div>
</div>

<!-- Popup pour changer la priorité -->
<div id="priority-selector-popup" class="priority-popup-overlay">
    <div class="priority-popup">
        <div class="priority-popup-header">
            <h3>🎯 Modifier le niveau de recherche</h3>
            <button class="priority-popup-close" onclick="closePrioritySelector()">&times;</button>
        </div>
        
        <div class="priority-popup-content">
            <p class="priority-description">Sélectionnez le niveau de priorité pour ce candidat :</p>
            
            <!-- Options de priorité -->
            <div class="priority-options">
                <label class="priority-option">
                    <input type="radio" name="candidate-priority" value="1">
                    <span class="priority-label priority-1">
                        <span class="priority-icon">🟢</span>
                        <div class="priority-text">
                            <strong>Recherche Active</strong>
                            <small>Le candidat consulte régulièrement les offres</small>
                        </div>
                    </span>
                </label>
                
                <label class="priority-option">
                    <input type="radio" name="candidate-priority" value="2">
                    <span class="priority-label priority-2">
                        <span class="priority-icon">🟠</span>
                        <div class="priority-text">
                            <strong>Recherche Importante</strong>
                            <small>Le candidat recherche activement un emploi</small>
                        </div>
                    </span>
                </label>
                
                <label class="priority-option">
                    <input type="radio" name="candidate-priority" value="3">
                    <span class="priority-label priority-3">
                        <span class="priority-icon">🔴</span>
                        <div class="priority-text">
                            <strong>Recherche Urgente</strong>
                            <small>Le candidat a besoin de trouver rapidement</small>
                        </div>
                    </span>
                </label>
                
                <label class="priority-option">
                    <input type="radio" name="candidate-priority" value="4">
                    <span class="priority-label priority-4">
                        <span class="priority-icon">🌍</span>
                        <div class="priority-text">
                            <strong>Profil Public</strong>
                            <small>Priorité maximale - Profil visible publiquement</small>
                        </div>
                    </span>
                </label>
                
                <label class="priority-option">
                    <input type="radio" name="candidate-priority" value="0">
                    <span class="priority-label priority-0">
                        <span class="priority-icon">⚪</span>
                        <div class="priority-text">
                            <strong>Aucune priorité</strong>
                            <small>Retirer le niveau de priorité</small>
                        </div>
                    </span>
                </label>
            </div>
        </div>
        
        <div class="priority-popup-actions">
            <button id="save-priority-btn" class="btn-save-priority" onclick="saveCandidatePriority()" disabled>
                💾 Sauvegarder
            </button>
            <button class="btn-cancel-priority" onclick="closePrioritySelector()">
                ❌ Annuler
            </button>
        </div>
    </div>
</div>

<script src="/script/forms/select-avatar.js"></script>

<script>
// Variables globales - utiliser window pour éviter les conflits avec index.php
window.candidatesBaseUrl = window.baseUrl || '<?php echo $baseUrl; ?>';
window.candidatesLang = window.lang || '<?php echo $lang; ?>';
let allCandidates = [];

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
        
        const response = await fetch(`${window.candidatesBaseUrl}/function/getCandidates.php?${params}`);
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
    const photoUrl = candidate.photo_url ? `${window.candidatesBaseUrl}/${candidate.photo_url}` : `${window.candidatesBaseUrl}/data/candidates/media/default/photo.jpg`;
    const cvUrl = `${window.candidatesBaseUrl}/${window.candidatesLang}/data/candidates/cv/${candidate.id}`;
    const isPending = candidate.approved === null || candidate.approved === 'null' || candidate.approved === '';
    
    // Obtenir les informations de la priorité
    const priorityInfo = getPriorityBadge(candidate.priority);
    
    return `
        <div class="candidate-card" data-candidate-id="${candidate.id}">
            <div class="candidate-status ${statusInfo.class}">
                <span class="status-icon">${statusInfo.icon}</span>
                <span class="status-label">${statusInfo.label}</span>
            </div>
            
            ${priorityInfo.html}
            
            <div class="candidate-photo">
                <img src="${photoUrl}" 
                     alt="Photo de ${candidate.first_name} ${candidate.last_name}"
                     onerror="this.src='${window.candidatesBaseUrl}/data/candidates/media/default/photo.jpg'">
            </div>
            
            <div class="candidate-info">
                <h3 class="candidate-name">${candidate.first_name} ${candidate.last_name}</h3>
                <p class="candidate-job-title">${candidate.job_title || 'Métier non renseigné'}</p>
                ${candidate.location ? `<p class="candidate-location">📍 ${candidate.location}</p>` : ''}
                ${candidate.age ? `<p class="candidate-age">🎂 ${candidate.age} ans</p>` : ''}
                ${getSeniorityBadge(candidate.seniority)}
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
                <button class="btn-action btn-assign-avatar" 
                        data-candidate-id="${candidate.id}"
                        data-avatar-url="${candidate.avatar_url || ''}"
                        title="Assigner un avatar">
                    <span class="btn-icon">🎭</span>
                    <span class="btn-text">Avatar</span>
                </button>
                <button class="btn-action btn-change-priority" 
                        data-candidate-id="${candidate.id}"
                        data-current-priority="${candidate.priority || 0}"
                        title="Changer la priorité">
                    <span class="btn-icon">🎯</span>
                    <span class="btn-text">Priorité</span>
                </button>
                <button class="btn-action btn-change-seniority" 
                        data-candidate-id="${candidate.id}"
                        data-current-seniority="${candidate.seniority || ''}"
                        title="Définir la séniorité">
                    <span class="btn-icon">📊</span>
                    <span class="btn-text">Séniorité</span>
                </button>
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

// Obtenir le badge de séniorité
function getSeniorityBadge(seniority) {
    if (seniority === null || seniority === undefined || seniority === '') return '';
    
    const seniorityInfo = {
        0: { icon: '🎓', label: 'Apprenti', color: '#fef3c7', textColor: '#92400e' },
        1: { icon: '🌱', label: 'Junior', color: '#d1fae5', textColor: '#065f46' },
        2: { icon: '💼', label: 'Confirmé', color: '#dbeafe', textColor: '#1e40af' },
        3: { icon: '⭐', label: 'Senior', color: '#fce7f3', textColor: '#9f1239' }
    };
    
    const info = seniorityInfo[parseInt(seniority)];
    if (!info) return '';
    
    return `<p class="candidate-seniority" style="background: ${info.color}; color: ${info.textColor};">
        ${info.icon} ${info.label}
    </p>`;
}

// Obtenir le badge de priorité
function getPriorityBadge(priority) {
    const p = parseInt(priority);
    
    if (p === 1) {
        return {
            html: `<div class="candidate-priority priority-badge-1">
                <span class="priority-badge-icon">🟢</span>
                <span class="priority-badge-label">Actif</span>
            </div>`,
            label: 'Recherche Active',
            icon: '🟢'
        };
    } else if (p === 2) {
        return {
            html: `<div class="candidate-priority priority-badge-2">
                <span class="priority-badge-icon">🟠</span>
                <span class="priority-badge-label">Important</span>
            </div>`,
            label: 'Recherche Importante',
            icon: '🟠'
        };
    } else if (p === 3) {
        return {
            html: `<div class="candidate-priority priority-badge-3">
                <span class="priority-badge-icon">🔴</span>
                <span class="priority-badge-label">Urgent</span>
            </div>`,
            label: 'Recherche Urgente',
            icon: '🔴'
        };
    } else if (p === 4) {
        return {
            html: `<div class="candidate-priority priority-badge-4">
                <span class="priority-badge-icon">🌍</span>
                <span class="priority-badge-label">Public</span>
            </div>`,
            label: 'Profil Public',
            icon: '🌍'
        };
    } else {
        return {
            html: '',
            label: 'Aucune priorité',
            icon: ''
        };
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
    const assignAvatarBtn = e.target.closest('.btn-assign-avatar');
    };
}

// Gérer l'approbation/refus d'un candidat
async function handleApproval(candidateId, approved) {
    try {
        const response = await fetch(`${window.candidatesBaseUrl}/function/approveCard.php`, {
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
            // Construire le message de notification
            let message = data.message;
            if (data.emailSent) {
                message += ' - Email envoyé à ' + (data.emailInfo?.recipientEmail || 'le candidat');
            } else if (data.emailInfo && !data.emailSent) {
                message += ' (Attention: L\'email de notification n\'a pas pu être envoyé)';
            }
            
            // Afficher un message de succès
            showNotification(message, 'success');
            
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
    const assignAvatarBtn = e.target.closest('.btn-assign-avatar');
    const changePriorityBtn = e.target.closest('.btn-change-priority');
    const changeSeniorityBtn = e.target.closest('.btn-change-seniority');
    
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
    
    if (assignAvatarBtn) {
        e.preventDefault();
        const candidateId = assignAvatarBtn.dataset.candidateId;
        const currentAvatarUrl = assignAvatarBtn.dataset.avatarUrl || null;
        openAvatarSelector(candidateId, currentAvatarUrl);
    }
    
    if (changePriorityBtn) {
        e.preventDefault();
        const candidateId = changePriorityBtn.dataset.candidateId;
        const currentPriority = changePriorityBtn.dataset.currentPriority;
        openPrioritySelector(candidateId, currentPriority);
    }
    
    if (changeSeniorityBtn) {
        e.preventDefault();
        const candidateId = changeSeniorityBtn.dataset.candidateId;
        const currentSeniority = changeSeniorityBtn.dataset.currentSeniority;
        openSenioritySelector(candidateId, currentSeniority);
    }
});

// Ouvrir le viewer de CV
async function openCvViewer(candidateId) {
    const popupId = 'cv-viewer-popup';
    const url = `${window.candidatesBaseUrl}/function/getViewerCV.php`;
    
    // Utiliser loadPopupContent du script popup.js
    await loadPopupContent(popupId, url, { id: candidateId });
}

// Variables globales pour la priorité
let currentCandidateIdForPriority = null;

// Ouvrir le sélecteur de priorité
function openPrioritySelector(candidateId, currentPriority) {
    currentCandidateIdForPriority = candidateId;
    const popup = document.getElementById('priority-selector-popup');
    const saveBtn = document.getElementById('save-priority-btn');
    
    // Réinitialiser tous les radios
    document.querySelectorAll('input[name="candidate-priority"]').forEach(radio => {
        radio.checked = false;
    });
    
    // Pré-sélectionner la priorité actuelle si elle existe
    if (currentPriority && currentPriority != '0') {
        const currentRadio = document.querySelector(`input[name="candidate-priority"][value="${currentPriority}"]`);
        if (currentRadio) {
            currentRadio.checked = true;
            saveBtn.disabled = false;
        }
    }
    
    // Afficher la popup
    popup.classList.add('active');
    
    // Gérer la sélection des radios
    document.querySelectorAll('input[name="candidate-priority"]').forEach(radio => {
        radio.addEventListener('change', () => {
            saveBtn.disabled = false;
        });
    });
}

// Fermer le sélecteur de priorité
function closePrioritySelector() {
    const popup = document.getElementById('priority-selector-popup');
    popup.classList.remove('active');
    currentCandidateIdForPriority = null;
}

// Sauvegarder la priorité du candidat
async function saveCandidatePriority() {
    const selectedPriority = document.querySelector('input[name="candidate-priority"]:checked');
    
    if (!selectedPriority) {
        alert('Veuillez sélectionner un niveau de priorité');
        return;
    }
    
    if (!currentCandidateIdForPriority) {
        alert('Erreur: ID du candidat introuvable');
        return;
    }
    
    const priorityValue = parseInt(selectedPriority.value);
    const saveBtn = document.getElementById('save-priority-btn');
    
    // Désactiver le bouton pendant la sauvegarde
    saveBtn.disabled = true;
    saveBtn.textContent = '⏳ Sauvegarde...';
    
    try {
        const response = await fetch(`${window.candidatesBaseUrl}/function/updateCandidatePriority.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                candidate_id: currentCandidateIdForPriority,
                priority: priorityValue
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('✅ Priorité mise à jour avec succès', 'success');
            closePrioritySelector();
            
            // Recharger les candidats pour voir les changements
            await loadCandidates();
        } else {
            throw new Error(data.error || 'Erreur lors de la mise à jour');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('❌ Erreur: ' + error.message, 'error');
        saveBtn.disabled = false;
        saveBtn.textContent = '💾 Sauvegarder';
    }
}

// Variables globales pour la séniorité
let currentCandidateIdForSeniority = null;

// Ouvrir le sélecteur de séniorité
function openSenioritySelector(candidateId, currentSeniority) {
    currentCandidateIdForSeniority = candidateId;
    const popup = document.getElementById('seniority-selector-popup');
    const saveBtn = document.getElementById('save-seniority-btn');
    
    // Réinitialiser tous les radios
    document.querySelectorAll('input[name="candidate-seniority"]').forEach(radio => {
        radio.checked = false;
    });
    
    // Pré-sélectionner la séniorité actuelle si elle existe
    if (currentSeniority) {
        const currentRadio = document.querySelector(`input[name="candidate-seniority"][value="${currentSeniority}"]`);
        if (currentRadio) {
            currentRadio.checked = true;
            saveBtn.disabled = false;
        }
    }
    
    // Afficher la popup
    popup.classList.add('active');
    
    // Gérer la sélectio !== null && currentSeniority !== undefined && currentSeniority !== ''n des radios
    document.querySelectorAll('input[name="candidate-seniority"]').forEach(radio => {
        radio.addEventListener('change', () => {
            saveBtn.disabled = false;
        });
    });
}

// Fermer le sélecteur de séniorité
function closeSenioritySelector() {
    const popup = document.getElementById('seniority-selector-popup');
    popup.classList.remove('active');
    currentCandidateIdForSeniority = null;
}

// Sauvegarder la séniorité du candidat
async function saveCandidateSeniority() {
    const selectedSeniority = document.querySelector('input[name="candidate-seniority"]:checked');
    
    if (!selectedSeniority) {
        alert('Veuillez sélectionner un niveau de séniorité');
        return;
    }
    
    if (!currentCandidateIdForSeniority) {
        alert('Erreur: ID du candidat introuvable');
        return;
    }
    
    const seniorityValue = selectedSeniority.value !== '' ? parseInt(selectedSeniority.value) : null;
    const saveBtn = document.getElementById('save-seniority-btn');
    
    // Désactiver le bouton pendant la sauvegarde
    saveBtn.disabled = true;
    saveBtn.textContent = '⏳ Sauvegarde...';
    
    try {
        const response = await fetch(`${window.candidatesBaseUrl}/function/updateCandidateSeniority.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                candidate_id: currentCandidateIdForSeniority,
                seniority: seniorityValue
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            closeSenioritySelector();
            
            // Recharger les candidats pour voir le changement
            await loadCandidates();
        } else {
            throw new Error(data.error || 'Erreur lors de la sauvegarde');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('Erreur: ' + error.message, 'error');
        
        // Réactiver le bouton en cas d'erreur
        saveBtn.disabled = false;
        saveBtn.textContent = '💾 Sauvegarder';
    }
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

/* Badge de priorité */
.candidate-priority {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.priority-badge-1 {
    background: #d1fae5;
    color: #065f46;
    border: 2px solid #10b981;
}

.priority-badge-2 {
    background: #fef3c7;
    color: #92400e;
    border: 2px solid #f59e0b;
}

.priority-badge-3 {
    background: #fee2e2;
    color: #991b1b;
    border: 2px solid #ef4444;
}

.priority-badge-4 {
    background: #dbeafe;
    color: #1e40af;
    border: 2px solid #3b82f6;
}

.priority-badge-icon {
    font-size: 1rem;
}

.priority-badge-label {
    font-weight: 700;
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
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    padding-top: 10px;
    border-top: 2px solid #f3f4f6;
}

.btn-action {
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

.btn-email {
    background: #f3f4f6;
    color: #374151;
    border: 2px solid #e5e7eb;
}

.btn-email:hover {
    background: #e5e7eb;
    transform: translateY(-2px);
}

.btn-assign-avatar {
    background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
    color: white;
}

.btn-assign-avatar:hover {
    background: linear-gradient(135deg, #8e44ad 0%, #7d3c98 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(155, 89, 182, 0.3);
}

.btn-change-priority {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.btn-change-priority:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
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
        grid-template-columns: 1fr;
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

/* Popup de sélection d'avatar */
.avatar-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    padding: 20px;
}

.avatar-popup-overlay.active {
    display: flex;
}

.avatar-popup {
    background: white;
    border-radius: 16px;
    max-width: 900px;
    width: 100%;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    animation: popupSlideIn 0.3s ease-out;
}

@keyframes popupSlideIn {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.avatar-popup-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 25px;
    border-bottom: 2px solid #e5e7eb;
}

.avatar-popup-header h3 {
    margin: 0;
    font-size: 1.5rem;
    color: #374151;
}

.avatar-popup-close {
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: #9ca3af;
    line-height: 1;
    transition: color 0.2s;
}

.avatar-popup-close:hover {
    color: #374151;
}

.avatar-popup-content {
    padding: 25px;
    overflow-y: auto;
    flex: 1;
}

.gender-selection {
    margin-bottom: 25px;
}

.gender-selection h4 {
    margin: 0 0 15px 0;
    font-size: 1.1rem;
    color: #374151;
}

.gender-options {
    display: flex;
    gap: 15px;
}

.gender-option {
    flex: 1;
    cursor: pointer;
}

.gender-option input[type="radio"] {
    display: none;
}

.gender-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    padding: 20px;
    border: 3px solid #e5e7eb;
    border-radius: 12px;
    transition: all 0.3s ease;
    background: white;
}

.gender-option input[type="radio"]:checked + .gender-label {
    border-color: #667eea;
    background: #f0f4ff;
}

.gender-icon {
    font-size: 3rem;
}

.gender-label span:last-child {
    font-weight: 600;
    color: #374151;
}

.avatar-preview-section {
    margin-bottom: 25px;
}

.avatar-preview-section h4 {
    margin: 0 0 15px 0;
    font-size: 1.1rem;
    color: #374151;
}

.avatar-preview-container {
    display: flex;
    justify-content: center;
}

#avatar-preview {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.avatar-gallery-section h4 {
    margin: 0 0 15px 0;
    font-size: 1.1rem;
    color: #374151;
}

.avatar-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 15px;
    max-height: 400px;
    overflow-y: auto;
    padding: 15px;
    background: #f9fafb;
    border-radius: 12px;
}

.avatar-option {
    position: relative;
}

.avatar-option input[type="radio"] {
    display: none;
}

.avatar-option label {
    display: block;
    cursor: pointer;
    transition: all 0.3s ease;
}

.avatar-option label img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e5e7eb;
    transition: all 0.3s ease;
}

.avatar-option input[type="radio"]:checked + label img {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
    transform: scale(1.1);
}

.avatar-option label:hover img {
    border-color: #667eea;
    transform: scale(1.05);
}

.avatar-popup-actions {
    display: flex;
    gap: 15px;
    padding: 20px 25px;
    border-top: 2px solid #e5e7eb;
}

.btn-save-avatar,
.btn-cancel-avatar {
    flex: 1;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-save-avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-save-avatar:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-save-avatar:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-cancel-avatar {
    background: #f3f4f6;
    color: #374151;
}

.btn-cancel-avatar:hover {
    background: #e5e7eb;
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 0 auto;
}

/* Popup de sélection de priorité */
.priority-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    padding: 20px;
}

.priority-popup-overlay.active {
    display: flex;
}

.priority-popup {
    background: white;
    border-radius: 16px;
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    animation: popupSlideIn 0.3s ease-out;
}

.priority-popup-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 25px;
    border-bottom: 2px solid #e5e7eb;
}

.priority-popup-header h3 {
    margin: 0;
    font-size: 1.5rem;
    color: #374151;
}

.priority-popup-close {
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: #9ca3af;
    line-height: 1;
    transition: color 0.2s;
}

.priority-popup-close:hover {
    color: #374151;
}

.priority-popup-content {
    padding: 25px;
    overflow-y: auto;
    flex: 1;
}

.priority-description {
    margin: 0 0 20px 0;
    color: #6b7280;
    font-size: 1rem;
}

.priority-options {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.priority-option {
    cursor: pointer;
}

.priority-option input[type="radio"] {
    display: none;
}

.priority-label {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 16px 20px;
    border: 3px solid #e5e7eb;
    border-radius: 12px;
    transition: all 0.3s ease;
    background: white;
}

.priority-option input[type="radio"]:checked + .priority-label {
    border-color: #667eea;
    background: #f0f4ff;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.priority-label:hover {
    border-color: #667eea;
    background: #f9fafb;
}

.priority-icon {
    font-size: 2rem;
    flex-shrink: 0;
}

.priority-text {
    flex: 1;
}

.priority-text strong {
    display: block;
    font-size: 1.05rem;
    color: #374151;
    margin-bottom: 4px;
}

.priority-text small {
    display: block;
    font-size: 0.85rem;
    color: #6b7280;
}

/* Couleurs spécifiques pour chaque priorité */
.priority-option input[type="radio"]:checked + .priority-label.priority-1 {
    border-color: #10b981;
    background: #d1fae5;
}

.priority-option input[type="radio"]:checked + .priority-label.priority-2 {
    border-color: #f59e0b;
    background: #fef3c7;
}

.priority-option input[type="radio"]:checked + .priority-label.priority-3 {
    border-color: #ef4444;
    background: #fee2e2;
}

.priority-option input[type="radio"]:checked + .priority-label.priority-4 {
    border-color: #3b82f6;
    background: #dbeafe;
}

.priority-option input[type="radio"]:checked + .priority-label.priority-0 {
    border-color: #9ca3af;
    background: #f3f4f6;
}

.priority-popup-actions {
    display: flex;
    gap: 15px;
    padding: 20px 25px;
    border-top: 2px solid #e5e7eb;
}

.btn-save-priority,
.btn-cancel-priority {
    flex: 1;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-save-priority {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-save-priority:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-save-priority:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-cancel-priority {
    background: #f3f4f6;
    color: #374151;
}

.btn-cancel-priority:hover {
    background: #e5e7eb;
}

/* Badge de séniorité dans la carte candidat */
.candidate-seniority {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-top: 8px;
}

/* Popup de sélection de séniorité */
.seniority-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    padding: 20px;
}

.seniority-popup-overlay.active {
    display: flex;
}

.seniority-popup {
    background: white;
    border-radius: 16px;
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    animation: popupSlideIn 0.3s ease-out;
}

.seniority-popup-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 25px;
    border-bottom: 2px solid #e5e7eb;
}

.seniority-popup-header h3 {
    margin: 0;
    font-size: 1.5rem;
    color: #374151;
}

.seniority-popup-close {
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: #9ca3af;
    line-height: 1;
    transition: color 0.2s;
}

.seniority-popup-close:hover {
    color: #374151;
}

.seniority-popup-content {
    padding: 25px;
    overflow-y: auto;
    flex: 1;
}

.seniority-description {
    margin: 0 0 20px 0;
    color: #6b7280;
    font-size: 1rem;
}

.seniority-options {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.seniority-option {
    cursor: pointer;
}

.seniority-option input[type="radio"] {
    display: none;
}

.seniority-label {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 16px 20px;
    border: 3px solid #e5e7eb;
    border-radius: 12px;
    transition: all 0.3s ease;
    background: white;
}

.seniority-option input[type="radio"]:checked + .seniority-label {
    border-color: #667eea;
    background: #f0f4ff;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.seniority-label:hover {
    border-color: #667eea;
    background: #f9fafb;
}

.seniority-icon {
    font-size: 2rem;
    flex-shrink: 0;
}

.seniority-text {
    flex: 1;
}

.seniority-text strong {
    display: block;
    font-size: 1.05rem;
    color: #374151;
    margin-bottom: 4px;
}

.seniority-text small {
    display: block;
    font-size: 0.85rem;
    color: #6b7280;
}

/* Couleurs spécifiques pour chaque séniorité */
.seniority-option input[type="radio"]:checked + .seniority-label.seniority-apprentice {
    border-color: #f59e0b;
    background: #fef3c7;
}

.seniority-option input[type="radio"]:checked + .seniority-label.seniority-junior {
    border-color: #10b981;
    background: #d1fae5;
}

.seniority-option input[type="radio"]:checked + .seniority-label.seniority-confirmed {
    border-color: #3b82f6;
    background: #dbeafe;
}

.seniority-option input[type="radio"]:checked + .seniority-label.seniority-senior {
    border-color: #ec4899;
    background: #fce7f3;
}

.seniority-option input[type="radio"]:checked + .seniority-label.seniority-expert {
    border-color: #8b5cf6;
    background: #f3e8ff;
}

.seniority-option input[type="radio"]:checked + .seniority-label.seniority-none {
    border-color: #9ca3af;
    background: #f3f4f6;
}

.seniority-popup-actions {
    display: flex;
    gap: 15px;
    padding: 20px 25px;
    border-top: 2px solid #e5e7eb;
}

.btn-save-seniority,
.btn-cancel-seniority {
    flex: 1;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-save-seniority {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-save-seniority:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-save-seniority:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-cancel-seniority {
    background: #f3f4f6;
    color: #374151;
}

.btn-cancel-seniority:hover {
    background: #e5e7eb;
}

</style>

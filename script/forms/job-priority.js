/**
 * Popup de sélection de priorité pour les offres d'emploi
 * Niveaux de priorité :
 * - 3: Urgente (rouge vif)
 * - 2: Importante (orange)
 * - 1: Recherche Active (vert)
 * - 0: Normal (pas de badge)
 */

// Labels multilingues pour les priorités
const JOB_PRIORITY_LABELS = {
    fr: {
        0: 'Normal',
        1: 'Recherche Active',
        2: 'Importante',
        3: 'Urgente'
    },
    en: {
        0: 'Normal',
        1: 'Active Search',
        2: 'Important',
        3: 'Urgent'
    },
    es: {
        0: 'Normal',
        1: 'Búsqueda Activa',
        2: 'Importante',
        3: 'Urgente'
    },
    de: {
        0: 'Normal',
        1: 'Aktive Suche',
        2: 'Wichtig',
        3: 'Dringend'
    }
};

// Langue par défaut (peut être modifiée dynamiquement)
let currentLanguage = 'fr';

/**
 * Afficher la popup de sélection de priorité
 * @param {number} jobId - ID de l'offre d'emploi
 * @param {number} currentPriority - Priorité actuelle (0-3)
 * @param {function} onUpdate - Callback appelé après la mise à jour
 */
function showJobPriorityPopup(jobId, currentPriority = 0, onUpdate = null) {
    // Créer l'overlay
    const overlay = document.createElement('div');
    overlay.className = 'job-priority-overlay';
    overlay.innerHTML = `
        <div class="job-priority-popup">
            <div class="job-priority-header">
                <h3>🎯 Définir le niveau de priorité</h3>
                <button class="job-priority-close" onclick="closeJobPriorityPopup()">&times;</button>
            </div>
            
            <div class="job-priority-content">
                <p style="margin-bottom: 1.5rem; color: #777;">
                    Sélectionnez le niveau de priorité de cette offre d'emploi :
                </p>
                
                <div class="job-priority-options">
                    <div class="job-priority-option ${currentPriority === 3 ? 'selected' : ''}" 
                         data-priority="3" 
                         onclick="selectJobPriority(${jobId}, 3, ${onUpdate ? 'true' : 'false'})">
                        <div class="priority-badge priority-3">
                            ⚡ ${JOB_PRIORITY_LABELS[currentLanguage][3]}
                        </div>
                        <p class="priority-description">
                            Pour les postes à pourvoir immédiatement ou à très court terme
                        </p>
                    </div>
                    
                    <div class="job-priority-option ${currentPriority === 2 ? 'selected' : ''}" 
                         data-priority="2" 
                         onclick="selectJobPriority(${jobId}, 2, ${onUpdate ? 'true' : 'false'})">
                        <div class="priority-badge priority-2">
                            ⚠️ ${JOB_PRIORITY_LABELS[currentLanguage][2]}
                        </div>
                        <p class="priority-description">
                            Pour les postes stratégiques ou nécessitant une attention particulière
                        </p>
                    </div>
                    
                    <div class="job-priority-option ${currentPriority === 1 ? 'selected' : ''}" 
                         data-priority="1" 
                         onclick="selectJobPriority(${jobId}, 1, ${onUpdate ? 'true' : 'false'})">
                        <div class="priority-badge priority-1">
                            🔍 ${JOB_PRIORITY_LABELS[currentLanguage][1]}
                        </div>
                        <p class="priority-description">
                            Pour les offres en recrutement actif mais sans urgence
                        </p>
                    </div>
                    
                    <div class="job-priority-option ${currentPriority === 0 ? 'selected' : ''}" 
                         data-priority="0" 
                         onclick="selectJobPriority(${jobId}, 0, ${onUpdate ? 'true' : 'false'})">
                        <div class="priority-badge priority-0">
                            📋 ${JOB_PRIORITY_LABELS[currentLanguage][0]}
                        </div>
                        <p class="priority-description">
                            Pour les offres sans priorité particulière
                        </p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Stocker le callback pour y accéder plus tard
    if (onUpdate) {
        overlay.dataset.onUpdate = onUpdate.toString();
    }
    
    document.body.appendChild(overlay);
    
    // Fermer en cliquant sur l'overlay
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closeJobPriorityPopup();
        }
    });
    
    // Animation d'entrée
    setTimeout(() => {
        overlay.classList.add('active');
    }, 10);
}

/**
 * Sélectionner une priorité et mettre à jour l'offre
 * @param {number} jobId - ID de l'offre d'emploi
 * @param {number} priority - Niveau de priorité (0-3)
 * @param {boolean} hasCallback - Si un callback existe
 */
async function selectJobPriority(jobId, priority, hasCallback = false) {
    try {
        console.log(`🎯 Mise à jour priorité - Job ${jobId} -> Priorité ${priority}`);
        
        // Appel API pour mettre à jour la priorité
        const response = await fetch('/function/updateJobPriority.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                job_id: jobId,
                priority: priority
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            console.log('✅ Priorité mise à jour avec succès');
            
            // Fermer la popup
            closeJobPriorityPopup();
            
            // Appeler le callback si présent
            if (hasCallback) {
                const overlay = document.querySelector('.job-priority-overlay');
                if (overlay && overlay.dataset.onUpdate) {
                    try {
                        eval(overlay.dataset.onUpdate)();
                    } catch (e) {
                        console.warn('Erreur callback:', e);
                    }
                }
            }
            
            // Recharger la page ou la liste des jobs après un petit délai
            setTimeout(() => {
                if (typeof loadJobs === 'function') {
                    loadJobs();
                } else {
                    window.location.reload();
                }
            }, 300);
            
        } else {
            console.error('❌ Erreur:', data.error);
            alert('Erreur lors de la mise à jour de la priorité: ' + (data.error || 'Erreur inconnue'));
        }
        
    } catch (error) {
        console.error('❌ Erreur réseau:', error);
        alert('Erreur de connexion lors de la mise à jour de la priorité');
    }
}

/**
 * Fermer la popup de priorité
 */
function closeJobPriorityPopup() {
    const overlay = document.querySelector('.job-priority-overlay');
    if (overlay) {
        overlay.classList.remove('active');
        setTimeout(() => {
            overlay.remove();
        }, 300);
    }
}

/**
 * Obtenir le label de priorité dans la langue actuelle
 * @param {number} priority - Niveau de priorité (0-3)
 * @returns {string} Label de priorité
 */
function getJobPriorityLabel(priority) {
    return JOB_PRIORITY_LABELS[currentLanguage][priority] || JOB_PRIORITY_LABELS['fr'][priority];
}

/**
 * Générer le HTML du badge de priorité
 * @param {number} priority - Niveau de priorité (0-3)
 * @returns {string} HTML du badge
 */
function getJobPriorityBadge(priority) {
    if (priority === 0) return ''; // Pas de badge pour priorité normale
    
    let icon = '';
    let label = getJobPriorityLabel(priority);
    
    switch(priority) {
        case 3:
            icon = '⚡';
            break;
        case 2:
            icon = '⚠️';
            break;
        case 1:
            icon = '🔍';
            break;
    }
    
    return `<span class="priority-badge priority-${priority}">${icon} ${label}</span>`;
}

// Styles CSS pour la popup (à injecter)
const jobPriorityStyles = `
<style>
.job-priority-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    opacity: 0;
    transition: opacity 0.3s;
}

.job-priority-overlay.active {
    opacity: 1;
}

.job-priority-popup {
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    transform: scale(0.9);
    transition: transform 0.3s;
}

.job-priority-overlay.active .job-priority-popup {
    transform: scale(1);
}

.job-priority-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 2px solid #e5e7eb;
}

.job-priority-header h3 {
    margin: 0;
    color: #333;
    font-size: 1.3rem;
}

.job-priority-close {
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: #999;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s;
}

.job-priority-close:hover {
    background: #f3f4f6;
    color: #333;
}

.job-priority-content {
    padding: 1.5rem;
}

.job-priority-options {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.job-priority-option {
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.25rem;
    cursor: pointer;
    transition: all 0.3s;
}

.job-priority-option:hover {
    border-color: #667eea;
    background: #f9fafb;
    transform: translateX(5px);
}

.job-priority-option.selected {
    border-color: #667eea;
    background: #eef2ff;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.priority-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.priority-badge.priority-3 {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

.priority-badge.priority-2 {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
}

.priority-badge.priority-1 {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(251, 191, 36, 0.3);
}

.priority-badge.priority-0 {
    background: #e5e7eb;
    color: #6b7280;
}

.priority-description {
    margin: 0.5rem 0 0 0;
    color: #777;
    font-size: 0.9rem;
}
</style>
`;

// Injecter les styles au chargement
if (document.head && !document.getElementById('job-priority-styles')) {
    const styleElement = document.createElement('div');
    styleElement.id = 'job-priority-styles';
    styleElement.innerHTML = jobPriorityStyles;
    document.head.appendChild(styleElement);
}

<section class="offers-section">
    <!-- Section de filtres / Moteur de recherche -->
    <div class="offers-filters">
        <div class="filter-container">
            <h2>Filtrer les offres</h2>
            <?php include PROJECT_ROOT . '/[lang]/search.php'; ?>
        </div>
    </div>

    <!-- Container principal : Liste + Viewer -->
    <div class="containerflex">
        <!-- Colonne de gauche - Liste des offres -->
        <div class="offers-list-column">
            <h2><?php echo $textData['jobOffersAvailableText'] ?? "Offres d'emploi disponibles"; ?></h2>
            <p class="offers-count" id="offersCount"><?php echo $textData['loadingJobsText'] ?? "Chargement..."; ?></p>
            
            <div class="offers-grid" id="offersGrid">
                <!-- Les offres seront chargées ici dynamiquement -->
                <div class="loader" style="text-align: center; padding: 40px;">
                    <div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #f093fb; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                    <p style="margin-top: 20px; color: #777;"><?php echo $textData['loadingJobsText'] ?? "Chargement des offres..."; ?></p>
                </div>
            </div>
        </div>
        
        <!-- Colonne de droite - Viewer Offre -->
        <div class="job-viewer-column">
            <?php include "job-offer-viewer.php" ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/activityIconAndColor.php'; ?>

<style>
/* Styles pour les badges de priorité des offres */
.priority-badge {
    display: inline-block;
    padding: 0.4rem 0.9rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
    margin-bottom: 0.75rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}

.priority-badge.priority-1 {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
}

.priority-badge.priority-2 {
    background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
    color: white;
}

.priority-badge.priority-3 {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

/* Badge "Postulé" */
.applied-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: linear-gradient(135deg, #1fe576 0%, #329139 100%);
    color: white;
    padding: 0.35rem 0.75rem;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.75rem;
    box-shadow: 0 2px 6px rgba(220, 38, 38, 0.3);
    /* z-index: 10; */
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

/* Ajouter position relative aux cards pour le badge */
.job-card {
    position: relative;
}
</style>

<script>
// Langue courante injectée par PHP
const APP_LANG = '<?php echo $lang ?? 'fr'; ?>';

// Liste des IDs d'offres auxquelles le candidat a déjà postulé
let appliedJobIds = [];

// Traductions pour les badges de priorité
const PRIORITY_LABELS = {
    fr: {
        1: 'Recherche Activement',
        2: 'Recherche Importante',
        3: 'Recherche Urgente'
    },
    en: {
        1: 'Actively Hiring',
        2: 'Important Search',
        3: 'Urgent Search'
    },
    es: {
        1: 'Búsqueda Activa',
        2: 'Búsqueda Importante',
        3: 'Búsqueda Urgente'
    },
    de: {
        1: 'Aktiv auf der Suche',
        2: 'Wichtige Suche',
        3: 'Dringende Suche'
    }
};

/**
 * Retourne le label de priorité selon la langue
 */
function getPriorityLabel(priority, lang = APP_LANG) {
    const labels = PRIORITY_LABELS[lang] || PRIORITY_LABELS['fr'];
    return labels[priority] || '';
}

/**
 * Retourne la classe CSS pour le badge de priorité
 */
function getPriorityClass(priority) {
    const classes = {
        1: 'priority-badge priority-active',
        2: 'priority-badge priority-important',
        3: 'priority-badge priority-urgent'
    };
    return classes[priority] || '';
}

/**
 * Retourne l'icône pour le badge de priorité
 */
function getPriorityIcon(priority) {
    const icons = {
        1: '🔍',
        2: '⚠️',
        3: '⚡'
    };
    return icons[priority] || '';
}

// Charger la liste des offres au chargement de la page
document.addEventListener('DOMContentLoaded', async () => {
    await loadJobsList();
});

/**
 * Charge la liste des offres depuis l'API
 */
async function loadJobsList() {
    const grid = document.getElementById('offersGrid');
    
    try {
        // Si l'utilisateur est un candidat connecté, charger ses candidatures
        if (window.APP_CONFIG && window.APP_CONFIG.isCandidate) {
            await loadAppliedJobIds();
        }
        
        // Récupérer les paramètres de recherche depuis l'URL
        const urlParams = new URLSearchParams(window.location.search);
        const keyword = urlParams.get('keyword') || '';
        const location = urlParams.get('location') || '';
        
        // Construire l'URL de l'API avec les paramètres de recherche
        let apiUrl =  '/function/getJobs.php';
        const params = new URLSearchParams();
        
        if (keyword) params.append('keyword', keyword);
        if (location) params.append('location', location);
        
        if (params.toString()) {
            apiUrl += '?' + params.toString();
        }
        
        const response = await fetch(apiUrl);
        
        if (!response.ok) {
            throw new Error('Erreur lors du chargement des offres');
        }
        
        const data = await response.json();
        
        if (data.success && data.jobs.length > 0) {
            displayJobs(data.jobs);
        } else {
            displayEmptyStateJobs();
        }
        
    } catch (error) {
        console.error('Erreur:', error);
        displayErrorStateJobs(error.message);
    }
}

/**
 * Charge la liste des IDs d'offres auxquelles le candidat a déjà postulé
 */
async function loadAppliedJobIds() {
    try {
        const response = await fetch('/function/getMyAppliedJobIds.php');
        if (!response.ok) {
            throw new Error('Erreur lors du chargement des candidatures');
        }
        
        const data = await response.json();
        if (data.success) {
            appliedJobIds = data.jobIds || [];
        }
    } catch (error) {
        console.error('Erreur lors du chargement des candidatures:', error);
        appliedJobIds = [];
    }
}

/**
 * Vérifie si le candidat a déjà postulé à une offre
 */
function hasAppliedToJob(jobId) {
    return appliedJobIds.includes(parseInt(jobId));
}

/**
 * Calcule le score de pertinence d'une offre par rapport au mot-clé
 */
function calculateRelevanceScore(job, keyword) {
    if (!keyword) return 0;
    
    const searchTerm = keyword.toLowerCase();
    let score = 0;
    
    // Correspondance exacte dans le titre (score le plus élevé)
    if (job.title && job.title.toLowerCase() === searchTerm) {
        score += 100;
    } else if (job.title && job.title.toLowerCase().includes(searchTerm)) {
        score += 50;
    }
    
    // Correspondance dans la description
    if (job.description && job.description.toLowerCase().includes(searchTerm)) {
        score += 20;
    }
    
    // Correspondance dans le nom de l'entreprise
    if (job.company_name && job.company_name.toLowerCase().includes(searchTerm)) {
        score += 15;
    }
    
    // Correspondance dans le secteur d'activité
    if (job.activity_name && job.activity_name.toLowerCase().includes(searchTerm)) {
        score += 10;
    }
    
    // Correspondance dans le type de contrat
    if (job.contract_type && job.contract_type.toLowerCase().includes(searchTerm)) {
        score += 10;
    }
    
    // Correspondance dans la localisation
    if (job.location && job.location.toLowerCase().includes(searchTerm)) {
        score += 5;
    }
    
    return score;
}

/**
 * Trie les offres par priorité et pertinence
 */
function sortJobsByPriorityAndRelevance(jobs, keyword) {
    return jobs.sort((a, b) => {
        // Priorité d'abord (3 > 2 > 1, les null à la fin)
        const priorityA = parseInt(a.priority) || 0;
        const priorityB = parseInt(b.priority) || 0;
        
        if (priorityA !== priorityB) {
            return priorityB - priorityA; // Tri décroissant (urgent d'abord)
        }
        
        // Si même priorité, trier par pertinence
        const scoreA = calculateRelevanceScore(a, keyword);
        const scoreB = calculateRelevanceScore(b, keyword);
        
        if (scoreA !== scoreB) {
            return scoreB - scoreA; // Plus pertinent d'abord
        }
        
        // Si même score, trier par date (plus récent d'abord)
        return b.id - a.id;
    });
}

/**
 * Affiche les offres dans la grille
 */
function displayJobs(jobs) {
    const grid = document.getElementById('offersGrid');
    const countElement = document.getElementById('offersCount');
    
    // Récupérer le mot-clé de recherche pour le tri
    const urlParams = new URLSearchParams(window.location.search);
    const keyword = urlParams.get('keyword') || '';
    
    // Trier les offres par priorité et pertinence
    const sortedJobs = sortJobsByPriorityAndRelevance([...jobs], keyword);
    
    // Mettre à jour le compteur
    if (countElement) {
        const count = sortedJobs.length;
        countElement.textContent = `${count} <?php echo $textData['offerText'] ?? "Offre"; ?>${count > 1 ? 's' : ''} <?php echo $textData['availableText'] ?? "Disponible"; ?>${count > 1 ? 's' : ''}`;
    }
    
    grid.innerHTML = sortedJobs.map(job => `
        <div class="job-card" data-priority="${job.priority || 0}" data-job-id="${job.id}">
            ${hasAppliedToJob(job.id) ? '<span class="applied-badge">✓ Postulé</span>' : ''}
            ${job.priority > 0 ? `<span class="priority-badge priority-${job.priority}">${getPriorityIcon(job.priority)} ${getPriorityLabel(job.priority)}</span>` : ''}
            <div class="job-card-header">
                <div class="job-card-info">
                    <h3>${job.title || 'Titre non spécifié'}</h3>
                    <p class="company">🏢 ${job.company_name || 'Entreprise non spécifiée'}</p>
                </div>
            </div>
            
            <div class="job-card-details">
                <p>📍 ${job.location || 'Non spécifié'}</p>
                <p class="activity" ${getActivityColor(job.activity_name)}>${getActivityIcon(job.activity_name)} ${job['activity_' + APP_LANG + '_name'] || job.activity_name || 'Secteur non spécifié'}</p>
                <p>💰 ${job.salary || 'Non spécifié'}</p>
                <p>📋 ${job.contract_type || 'Type de contrat non spécifié'}</p>
                ${job.start_date ? `<p>📅 ${job.start_date}</p>` : ''}
            </div>
            
            <button class="btn-view-job"><?php echo $textData['viewOfferText'] ?? "Voir l'offre"; ?></button>
        </div>
    `).join('');
    
    // Attacher les événements après avoir créé les éléments
    attachJobCardListeners();
    
    // Masquer automatiquement les données classifiées dans la liste (sauf pour les admins)
    if (!window.APP_CONFIG.isAdmin) {
        classifyJobsListDataAutomatically();
    }
}

/**
 * Masque automatiquement les données sensibles dans la liste des offres
 * Note: Le nom de la société n'est plus masqué, il est visible pour tous
 */
function classifyJobsListDataAutomatically() {
    // Récupérer tous les éléments avec la classe "classified"
    const classifiedElements = document.querySelectorAll('.offers-grid .classified');
    
    classifiedElements.forEach((element) => {
        // Ignorer les noms d'entreprise (ils ne doivent plus être masqués)
        if (element.classList.contains('company')) {
            return; // Ne rien faire, laisser le nom visible
        }
        // Autres cas : remplacer le contenu texte par "Info classifiée"
        if (element.textContent && element.textContent.trim() !== '' && element.textContent !== 'Info classifiée') {
            element.textContent = 'Info classifiée';
        }
    });
    
    console.log(`🔒 ${classifiedElements.length} éléments sensibles vérifiés dans la liste des offres`);
}

/**
 * Affiche un message quand il n'y a pas d'offres
 */
function displayEmptyStateJobs() {
    const grid = document.getElementById('offersGrid');
    grid.innerHTML = `
        <div class="empty-state" style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 64px; margin-bottom: 20px;">💼</div>
            <h3 style="font-size: 20px; color: #777; margin-bottom: 10px;">Aucune offre disponible</h3>
            <p style="color: #999;">Revenez bientôt!</p>
        </div>
    `;
}

/**
 * Affiche un message d'erreur
 */
function displayErrorStateJobs(message) {
    const grid = document.getElementById('offersGrid');
    grid.innerHTML = `
        <div class="error-state" style="text-align: center; padding: 60px 20px; color: #e74c3c;">
            <div style="font-size: 64px; margin-bottom: 20px;">⚠️</div>
            <h3 style="font-size: 20px; margin-bottom: 10px;">Erreur</h3>
            <p>${message}</p>
        </div>
    `;
}
</script>

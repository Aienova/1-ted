<section class="candidates-section">
    <!-- Section de filtres / Moteur de recherche -->
    <div class="candidates-filters">
        <div class="filter-container">
            <h2>Filtrer les candidats</h2>
            <?php include PROJECT_ROOT . '/[lang]/search.php'; ?>
        </div>
    </div>

    <!-- Container principal : Liste + Viewer -->
    <div class="container">
        <!-- Colonne de gauche - Liste des candidats -->
        <div class="candidates-list-column">
            <h2>Candidats disponibles</h2>
            <p class="candidates-count" id="candidatesCount">Chargement...</p>
            
            <div class="candidates-grid" id="candidatesGrid">
                <!-- Les candidats seront chargés ici dynamiquement -->
                <div class="loader" style="text-align: center; padding: 40px;">
                    <div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                    <p style="margin-top: 20px; color: #777;">Chargement des candidats...</p>
                </div>
            </div>
        </div>
        
        <!-- Colonne de droite - Viewer CV -->
        <div class="cv-viewer-column">
            <?php include "cv-viewer.php" ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/activityIconAndColor.php'; ?>
<?php include __DIR__ . '/seniority.php'; ?>

<script>
// Langue courante injectée par PHP
const APP_LANG = '<?php echo $lang ?? 'fr'; ?>';

// URL par défaut pour la photo des candidats
const defaultPhotoUrl = '/data/candidates/media/default/photo.jpg';

// Traductions pour les badges de priorité
const CANDIDATE_PRIORITY_LABELS = {
    fr: {
        1: 'Recherche Activement',
        2: 'Recherche Importante',
        3: 'Recherche Urgente'
    },
    en: {
        1: 'Actively Searching',
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
function getCandidatePriorityLabel(priority, lang = APP_LANG) {
    const labels = CANDIDATE_PRIORITY_LABELS[lang] || CANDIDATE_PRIORITY_LABELS['fr'];
    return labels[priority] || '';
}

/**
 * Retourne la classe CSS pour le badge de priorité
 */
function getCandidatePriorityClass(priority) {
    const classes = {
        1: 'priority-badge priority-active',
        2: 'priority-badge priority-important',
        3: 'priority-badge priority-urgent'
    };
    return classes[priority] || '';
}

// Charger la liste des candidats au chargement de la page
document.addEventListener('DOMContentLoaded', async () => {
    await loadCandidatesList();
});

/**
 * Charge la liste des candidats depuis l'API
 */
async function loadCandidatesList() {
    const grid = document.getElementById('candidatesGrid');
    
    try {
        // Récupérer les paramètres de recherche depuis l'URL
        const urlParams = new URLSearchParams(window.location.search);
        const keyword = urlParams.get('keyword') || '';
        const location = urlParams.get('location') || '';
        
        // Construire l'URL de l'API avec les paramètres de recherche
        let apiUrl = window.APP_CONFIG.basePath + '/function/getCandidates.php';
        const params = new URLSearchParams();
        
        if (keyword) params.append('keyword', keyword);
        if (location) params.append('location', location);
        
        if (params.toString()) {
            apiUrl += '?' + params.toString();
        }
        
        const response = await fetch(apiUrl);
        
        if (!response.ok) {
            throw new Error('Erreur lors du chargement des candidats');
        }
        
        const data = await response.json();
        
        if (data.success && data.candidates.length > 0) {
            displayCandidates(data.candidates);
        } else {
            displayEmptyState();
        }
        
    } catch (error) {
        console.error('Erreur:', error);
        displayErrorState(error.message);
    }
}

/**
 * Calcule le score de pertinence d'un candidat par rapport au mot-clé
 */
function calculateCandidateRelevanceScore(candidate, keyword) {
    if (!keyword) return 0;
    
    const searchTerm = keyword.toLowerCase();
    let score = 0;
    
    // Correspondance exacte dans le titre du poste (score le plus élevé)
    if (candidate.job_title && candidate.job_title.toLowerCase() === searchTerm) {
        score += 100;
    } else if (candidate.job_title && candidate.job_title.toLowerCase().includes(searchTerm)) {
        score += 50;
    }
    
    // Correspondance dans le prénom/nom
    if (candidate.first_name && candidate.first_name.toLowerCase().includes(searchTerm)) {
        score += 30;
    }
    if (candidate.last_name && candidate.last_name.toLowerCase().includes(searchTerm)) {
        score += 30;
    }
    
    // Correspondance dans le secteur d'activité
    if (candidate.activity_name && candidate.activity_name.toLowerCase().includes(searchTerm)) {
        score += 20;
    }
    
    // Correspondance dans le type de contrat
    if (candidate.contract_type && candidate.contract_type.toLowerCase().includes(searchTerm)) {
        score += 15;
    }
    
    // Correspondance dans la localisation
    if (candidate.location && candidate.location.toLowerCase().includes(searchTerm)) {
        score += 10;
    }
    
    return score;
}

/**
 * Calcule la proximité de la date de disponibilité (plus proche = meilleur score)
 */
function calculateAvailabilityScore(dateAvailability) {
    if (!dateAvailability) return 0;
    
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const availDate = new Date(dateAvailability);
    availDate.setHours(0, 0, 0, 0);
    
    const diffTime = availDate - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    // Si déjà disponible ou disponible aujourd'hui
    if (diffDays <= 0) return 100;
    
    // Plus la date est proche, plus le score est élevé
    // Disponible dans 1 semaine = 80 points
    // Disponible dans 1 mois = 50 points
    // Disponible dans 3 mois = 20 points
    // Disponible dans 6 mois ou plus = 0 points
    if (diffDays <= 7) return 80;
    if (diffDays <= 30) return 50;
    if (diffDays <= 90) return 20;
    return 0;
}

/**
 * Calcule le score de séniorité (plus de séniorité = meilleur score)
 */
function calculateSeniorityScore(seniority) {
    if (!seniority) return 0;
    
    const seniorityNum = parseInt(seniority);
    if (isNaN(seniorityNum)) return 0;
    
    // 1 an = 10 points, 5 ans = 50 points, 10+ ans = 100 points
    return Math.min(seniorityNum * 10, 100);
}

/**
 * Trie les candidats par priorité, disponibilité, séniorité et pertinence
 */
function sortCandidatesByPriorityAndCriteria(candidates, keyword) {
    return candidates.sort((a, b) => {
        // 1. Priorité d'abord (3 > 2 > 1, les null/0 à la fin)
        const priorityA = parseInt(a.priority) || 0;
        const priorityB = parseInt(b.priority) || 0;
        
        if (priorityA !== priorityB) {
            return priorityB - priorityA; // Tri décroissant (urgent d'abord)
        }
        
        // 2. Score de disponibilité (plus proche = meilleur)
        const availScoreA = calculateAvailabilityScore(a.date_availability);
        const availScoreB = calculateAvailabilityScore(b.date_availability);
        
        if (availScoreA !== availScoreB) {
            return availScoreB - availScoreA;
        }
        
        // 3. Score de séniorité (plus expérimenté = meilleur)
        const seniorityScoreA = calculateSeniorityScore(a.seniority);
        const seniorityScoreB = calculateSeniorityScore(b.seniority);
        
        if (seniorityScoreA !== seniorityScoreB) {
            return seniorityScoreB - seniorityScoreA;
        }
        
        // 4. Si même priorité et séniorité, trier par pertinence
        const scoreA = calculateCandidateRelevanceScore(a, keyword);
        const scoreB = calculateCandidateRelevanceScore(b, keyword);
        
        if (scoreA !== scoreB) {
            return scoreB - scoreA; // Plus pertinent d'abord
        }
        
        // 5. Si même score, trier par ID (plus récent d'abord)
        return b.id - a.id;
    });
}

/**
 * Formate la date de disponibilité pour l'affichage
 */
function formatAvailabilityDate(dateAvailability, lang = APP_LANG) {
    if (!dateAvailability) return '';
    
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const availDate = new Date(dateAvailability);
    availDate.setHours(0, 0, 0, 0);
    
    const diffTime = availDate - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    const labels = {
        fr: {
            available: 'Disponible immédiatement',
            days: 'jours',
            in: 'Disponible dans'
        },
        en: {
            available: 'Available immediately',
            days: 'days',
            in: 'Available in'
        },
        es: {
            available: 'Disponible inmediatamente',
            days: 'días',
            in: 'Disponible en'
        },
        de: {
            available: 'Sofort verfügbar',
            days: 'Tage',
            in: 'Verfügbar in'
        }
    };
    
    const l = labels[lang] || labels['fr'];
    
    // Formater la date pour l'affichage
    const formatOptions = { year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = availDate.toLocaleDateString(lang, formatOptions);
    
    if (diffDays <= 0) {
        return `<span style="color: #27ae60; font-weight: bold;">✅ ${l.available}</span>`;
    } else {
        return `<span style="color: #e74c3c; font-weight: bold;">📅 ${l.in} ${diffDays} ${l.days} (${formattedDate})</span>`;
    }
}

/**
 * Affiche les candidats dans la grille
 */
function displayCandidates(candidates) {
    const grid = document.getElementById('candidatesGrid');
    const countElement = document.getElementById('candidatesCount');
    
    // Récupérer le mot-clé de recherche pour le tri
    const urlParams = new URLSearchParams(window.location.search);
    const keyword = urlParams.get('keyword') || '';
    
    // Trier les candidats par priorité, disponibilité, séniorité et pertinence
    const sortedCandidates = sortCandidatesByPriorityAndCriteria([...candidates], keyword);
    
    // Mettre à jour le compteur
    if (countElement) {
        const count = sortedCandidates.length;
        countElement.textContent = `${count} candidat${count > 1 ? 's' : ''} disponible${count > 1 ? 's' : ''}`;
    }
    
    grid.innerHTML = sortedCandidates.map(candidate => {
        // Pour les admins : afficher la vraie photo via media_code
        // Pour les autres : afficher l'avatar_url ou l'image par défaut
        const photoUrl = window.APP_CONFIG.isAdmin && candidate.media_code 
            ? `/data/candidates/media/${candidate.media_code}/photo.jpg?v=${Date.now()}`
            : (candidate.avatar_url || defaultPhotoUrl);
        
        return `
        <div class="candidate-card" data-priority="${candidate.priority}" data-candidate-id="${candidate.id}">
            ${candidate.priority ? `<div class="${getCandidatePriorityClass(candidate.priority)}">${getCandidatePriorityLabel(candidate.priority)}</div>` : ''}
            <div class="candidate-card-header">
                <img src="${photoUrl}" 
                     alt="Photo candidat" 
                     class="candidate-photo ${!window.APP_CONFIG.isAdmin ? 'classified' : ''}"
                     onerror="this.src='${defaultPhotoUrl}'">
                <div class="candidate-card-info">
                    <h3>${candidate.first_name} <span class="classified">${candidate.last_name}</span></h3>
                    <p class="position">${candidate.job_title || 'Poste non spécifié'}</p>
                    <p class="seniority" ${getSeniorityColor(candidate.seniority)}">📊 ${getSeniority(candidate.seniority, APP_LANG)}</p>
                    <p><span class="classified">📍 ${candidate.location || 'Non spécifié'}</span></p>
                    <p class="country"><img class="mini-logo" src="${candidate.country_flag_url || ''}" alt="${candidate.country_name || ''}" class="country-flag"> <span> ${candidate.country_name || 'Pays non spécifié'}</span></p>
                    <p class="activity" ${getActivityColor(candidate.activity_name)}><span>${getActivityIcon(candidate.activity_name)} ${candidate['activity_' + APP_LANG + '_name'] || candidate.activity_name || 'Secteur non spécifié'}</span></p>

                </div>
            </div>
            
            <div class="candidate-card-details">
                ${candidate.date_availability ? `<p>${formatAvailabilityDate(candidate.date_availability, APP_LANG)}</p>` : ''}
                <p>🎂 ${candidate.age ? candidate.age + ' ans' : 'Âge non spécifié'}
                💼 ${candidate.contract_type || 'Type de contrat non spécifié'}
                ${candidate.salary ? `💰 ${candidate.salary} €` : ''}</p>
            </div>
            
            <button class="btn-view-cv">Voir son CV</button>
        </div>
    `;
    }).join('');
    
    // Attacher les événements après avoir créé les éléments
    attachCardListeners();
    
    // Masquer automatiquement les données classifiées dans la liste (sauf pour les admins)
    if (!window.APP_CONFIG.isAdmin) {
        classifyListDataAutomatically();
    }
}

/** n 
 * Masque automatiquement les données sensibles dans la liste des candidats
 */
function classifyListDataAutomatically() {
    // Récupérer tous les éléments avec la classe "classified"
    const classifiedElements = document.querySelectorAll('.candidates-grid .classified');
    
    classifiedElements.forEach((element) => {
        // Si c'est une image (photo de profil)
        if (element.tagName === 'IMG' && element.classList.contains('candidate-photo')) {
            // L'image est déjà remplacée par défaut dans le HTML
            // Pas besoin de faire quoi que ce soit
        }
        // Cas général : texte
        else {
            // Vérifier si c'est la localisation (contient l'emoji 📍)
            if (element.textContent && element.textContent.includes('📍')) {
                element.textContent = '📍 Info classifiée';
            }
            // Autres cas : remplacer le contenu texte par "Info classifiée"
            else if (element.textContent && element.textContent.trim() !== '' && element.textContent !== 'Info classifiée') {
                element.textContent = 'Info classifiée';
            }
        }
    });
    
    console.log(`🔒 ${classifiedElements.length} éléments sensibles masqués dans la liste`);
}

/**
 * Affiche un message quand il n'y a pas de candidats
 */
function displayEmptyState() {
    const grid = document.getElementById('candidatesGrid');
    grid.innerHTML = `
        <div class="empty-state" style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 64px; margin-bottom: 20px;">👥</div>
            <h3 style="font-size: 20px; color: #777; margin-bottom: 10px;">Aucun candidat disponible</h3>
            <p style="color: #999;">Revenez bientôt!</p>
        </div>
    `;
}

/**
 * Affiche un message d'erreur
 */
function displayErrorState(message) {
    const grid = document.getElementById('candidatesGrid');
    grid.innerHTML = `
        <div class="error-state" style="text-align: center; padding: 60px 20px; color: #e74c3c;">
            <div style="font-size: 64px; margin-bottom: 20px;">⚠️</div>
            <h3 style="font-size: 20px; margin-bottom: 10px;">Erreur</h3>
            <p>${message}</p>
        </div>
    `;
}
</script>

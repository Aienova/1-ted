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

<script>
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
 * Affiche les candidats dans la grille
 */
function displayCandidates(candidates) {
    const grid = document.getElementById('candidatesGrid');
    const countElement = document.getElementById('candidatesCount');
    
    // Mettre à jour le compteur
    if (countElement) {
        const count = candidates.length;
        countElement.textContent = `${count} candidat${count > 1 ? 's' : ''} disponible${count > 1 ? 's' : ''}`;
    }
    
    grid.innerHTML = candidates.map(candidate => `
        <div class="candidate-card" data-candidate-id="${candidate.id}">
            <div class="candidate-card-header">
                <img src="${window.APP_CONFIG.basePath}/data/candidates/media/default/photo.jpg" 
                     alt="Photo candidat" 
                     class="candidate-photo classified"
                     onerror="this.src='${window.APP_CONFIG.basePath}/data/candidates/media/default/photo.jpg'">
                <div class="candidate-card-info">
                    <h3>${candidate.first_name} <span class="classified">${candidate.last_name}</span></h3>
                    <p class="position">${candidate.job_title || 'Poste non spécifié'}</p>
                </div>
            </div>
            
            <div class="candidate-card-details">
                <p><span class="classified">📍 ${candidate.location || 'Non spécifié'}</span></p>
                <p>🎂 ${candidate.age ? candidate.age + ' ans' : 'Âge non spécifié'}</p>
                <p>💼 ${candidate.contract_type || 'Type de contrat non spécifié'}</p>
                ${candidate.salary ? `<p>💰 ${candidate.salary} €</p>` : ''}
            </div>
            
            <button class="btn-view-cv">Voir son CV</button>
        </div>
    `).join('');
    
    // Attacher les événements après avoir créé les éléments
    attachCardListeners();
    
    // Masquer automatiquement les données classifiées dans la liste
    classifyListDataAutomatically();
}

/**
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

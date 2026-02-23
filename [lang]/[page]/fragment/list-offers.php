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
            <h2>Offres d'emploi disponibles</h2>
            <p class="offers-count" id="offersCount">Chargement...</p>
            
            <div class="offers-grid" id="offersGrid">
                <!-- Les offres seront chargées ici dynamiquement -->
                <div class="loader" style="text-align: center; padding: 40px;">
                    <div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #f093fb; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                    <p style="margin-top: 20px; color: #777;">Chargement des offres...</p>
                </div>
            </div>
        </div>
        
        <!-- Colonne de droite - Viewer Offre -->
        <div class="job-viewer-column">
            <?php include "job-offer-viewer.php" ?>
        </div>
    </div>
</section>

<script>
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
 * Affiche les offres dans la grille
 */
function displayJobs(jobs) {
    const grid = document.getElementById('offersGrid');
    const countElement = document.getElementById('offersCount');
    
    // Mettre à jour le compteur
    if (countElement) {
        const count = jobs.length;
        countElement.textContent = `${count} offre${count > 1 ? 's' : ''} disponible${count > 1 ? 's' : ''}`;
    }
    
    grid.innerHTML = jobs.map(job => `
        <div class="job-card" data-job-id="${job.id}">
            <div class="job-card-header">
                <div class="job-card-info">
                    <h3>${job.title || 'Titre non spécifié'}</h3>
                    <p class="company classified">🏢 ${job.company_name || 'Entreprise non spécifiée'}</p>
                </div>
            </div>
            
            <div class="job-card-details">
                <p>📍 ${job.location || 'Non spécifié'}</p>
                <p>💰 ${job.salary || 'Non spécifié'}</p>
                <p>📋 ${job.contract_type || 'Type de contrat non spécifié'}</p>
                ${job.start_date ? `<p>📅 ${job.start_date}</p>` : ''}
            </div>
            
            <button class="btn-view-job">Voir l'offre</button>
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
 */
function classifyJobsListDataAutomatically() {
    // Récupérer tous les éléments avec la classe "classified"
    const classifiedElements = document.querySelectorAll('.offers-grid .classified');
    
    classifiedElements.forEach((element) => {
        // Pour les noms d'entreprise, garder l'emoji et remplacer le nom
        if (element.classList.contains('company')) {
            element.textContent = '🏢 Info classifiée';
        }
        // Autres cas : remplacer le contenu texte par "Info classifiée"
        else if (element.textContent && element.textContent.trim() !== '' && element.textContent !== 'Info classifiée') {
            element.textContent = 'Info classifiée';
        }
    });
    
    console.log(`🔒 ${classifiedElements.length} éléments sensibles masqués dans la liste des offres`);
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

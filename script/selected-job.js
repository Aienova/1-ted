/**
 * Script pour afficher l'offre d'emploi sélectionnée dans la page de candidature
 */

document.addEventListener('DOMContentLoaded', async () => {
    // Récupérer le paramètre GET 'job'
    const urlParams = new URLSearchParams(window.location.search);
    const jobId = urlParams.get('job');
    
    if (jobId) {
        await displaySelectedJobOffer(jobId);
    }
});

/**
 * Affiche l'offre d'emploi sélectionnée dans le div job-offer-selected
 * @param {string} jobId - ID de l'offre d'emploi
 */
async function displaySelectedJobOffer(jobId) {
    const container = document.getElementById('job-offer-selected');
    if (!container) return;
    
    // Afficher un loader
    container.innerHTML = `
        <div class="selected-offer loading">
            <div class="loader">
                <div class="spinner"></div>
                <p>Chargement de l'offre...</p>
            </div>
        </div>
    `;
    
    try {
        // Charger les données de l'offre
        const jobData = await fetchJobOfferData(jobId);
        
        // Afficher l'offre dans un encadré
        displayJobOfferCard(container, jobData, jobId);
        
        // Ajouter l'ID de l'offre au conteneur CV pour créer la candidature plus tard
        const cvContainer = document.querySelector('.cv-container');
        if (cvContainer) {
            cvContainer.setAttribute('data-jobselectedid', jobId);
            console.log('✅ Offre d\'emploi sélectionnée (ID: ' + jobId + ') - Candidature sera créée après le CV');
        }
        
    } catch (error) {
        console.error('Erreur lors du chargement de l\'offre:', error);
        container.innerHTML = `
            <div class="selected-offer error">
                <div class="error-message">
                    <span class="icon">⚠️</span>
                    <p>Impossible de charger l'offre d'emploi</p>
                </div>
            </div>
        `;
    }
}

/**
 * Récupère les données JSON de l'offre d'emploi
 * @param {string} jobId - ID de l'offre
 * @returns {Promise<Object>} - Données de l'offre
 */
async function fetchJobOfferData(jobId) {
    const basePath = window.APP_CONFIG ? window.APP_CONFIG.basePath : '/1-ted-backoffice';
    // checkApproved=0 permet de charger les offres même si elles ne sont pas approuvées
    const response = await fetch(`/function/getJobById.php?id=${jobId}&checkApproved=0`);
    
    if (!response.ok) {
        throw new Error('Offre d\'emploi introuvable');
    }
    
    const data = await response.json();
    
    if (!data.success || !data.job) {
        throw new Error(data.error || 'L\'offre est introuvable');
    }
    
    return data.job;
}

/**
 * Affiche l'offre dans un encadré stylisé
 * @param {HTMLElement} container - Conteneur où afficher l'offre
 * @param {Object} jobData - Données de l'offre
 * @param {string} jobId - ID de l'offre
 */
function displayJobOfferCard(container, jobData, jobId) {
    // Formatter les informations
    const contractType = jobData.contract_type || 'Non spécifié';
    const location = jobData.location || 'Non spécifié';
    const salary = jobData.salary || 'Non communiqué';
    const startDate = jobData.start_date ? formatDate(jobData.start_date) : 'À définir';
    const companyName = jobData.company_name || 'Entreprise';
    const needVehicle = jobData.need_vehicle == 1 ? 'Oui' : 'Non';
    const workCondition = jobData.work_condition || 'Non spécifié';
    const length = jobData.length || 'Non spécifié';
    
    // Vérifier le statut d'approbation
    const isApproved = jobData.approved == 1;
    const approvalBadge = !isApproved ? '<span class="approval-badge pending">⏳ En attente de validation</span>' : '';
    
    container.innerHTML = `
        <div class="selected-offer compact">
            <div class="selected-offer-header">
                <div class="selected-offer-title">
                    <span class="icon">💼</span>
                    <h3>Vous postulez pour cette offre</h3>
                    ${approvalBadge}
                </div>
                <button class="btn-toggle-offer" onclick="toggleOfferDetails()" title="Afficher plus de détails">
                    <span class="toggle-icon">▼</span>
                </button>
            </div>
            <div class="selected-offer-content">
                <div class="job-card-info">
                    <div class="job-header">
                        <h4 class="job-title">${escapeHtml(jobData.title)}</h4>
                        <p class="company-name">${escapeHtml(companyName)}</p>
                    </div>
                    
                    <!-- Informations toujours visibles (mode compact) -->
                    <div class="job-details-compact">
                        <div class="detail-item-compact">
                            <span class="detail-icon">📍</span>
                            <span class="detail-text">${escapeHtml(location)}</span>
                        </div>
                        
                        <div class="detail-item-compact">
                            <span class="detail-icon">💰</span>
                            <span class="detail-text">${escapeHtml(salary)}</span>
                        </div>
                        
                        <div class="detail-item-compact">
                            <span class="detail-icon">📄</span>
                            <span class="detail-text">${escapeHtml(contractType)}</span>
                        </div>
                        
                        <div class="detail-item-compact">
                            <span class="detail-icon">📅</span>
                            <span class="detail-text">${escapeHtml(startDate)}</span>
                        </div>
                    </div>
                    
                    <!-- Informations supplémentaires (mode étendu) -->
                    <div class="job-details-extended">
                        <div class="detail-item">
                            <span class="detail-icon">⏱️</span>
                            <div class="detail-content">
                                <span class="detail-label">Durée</span>
                                <span class="detail-value">${escapeHtml(length)}</span>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-icon">🚗</span>
                            <div class="detail-content">
                                <span class="detail-label">Véhicule requis</span>
                                <span class="detail-value">${needVehicle}</span>
                            </div>
                        </div>
                        
                        ${jobData.description ? `
                            <div class="job-description">
                                <h5>Description du poste</h5>
                                <p>${escapeHtml(jobData.description)}</p>
                            </div>
                        ` : ''}
                        
                        ${jobData.work_condition && jobData.work_condition !== 'Non spécifié' ? `
                            <div class="job-description">
                                <h5>Conditions de travail</h5>
                                <p>${escapeHtml(jobData.work_condition)}</p>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Toggle entre mode compact et mode étendu de l'offre
 */
function toggleOfferDetails() {
    const offer = document.querySelector('.selected-offer');
    const toggleIcon = document.querySelector('.toggle-icon');
    const toggleBtn = document.querySelector('.btn-toggle-offer');
    
    if (!offer || !toggleIcon || !toggleBtn) return;
    
    if (offer.classList.contains('compact')) {
        // Passer en mode étendu
        offer.classList.remove('compact');
        offer.classList.add('expanded');
        toggleIcon.textContent = '▲';
        toggleBtn.title = 'Réduire les détails';
    } else {
        // Passer en mode compact
        offer.classList.remove('expanded');
        offer.classList.add('compact');
        toggleIcon.textContent = '▼';
        toggleBtn.title = 'Afficher plus de détails';
    }
}

/**
 * Formate une date ISO en format lisible
 * @param {string} dateString - Date au format ISO
 * @returns {string} - Date formatée
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('fr-FR', options);
}

/**
 * Échappe les caractères HTML pour éviter les injections XSS
 * @param {string} text - Texte à échapper
 * @returns {string} - Texte échappé
 */
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

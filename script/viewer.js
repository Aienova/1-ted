/**
 * Script pour gérer l'affichage dynamique des CV dans le viewer
 */

// État global du viewer
const ViewerState = {
    currentCandidateId: null,
    currentMediaCode: null,
    currentCandidatePriority: null,
    currentJobId: null
};

/**
 * Initialise le viewer au chargement de la page
 */
document.addEventListener('DOMContentLoaded', () => {
    initializeViewer();
    attachCardListeners();
    attachJobCardListeners();
    
    // Charger automatiquement le CV si l'URL contient un paramètre id
    loadCVFromURL();
    
    // Gérer la navigation avec les boutons retour/avant du navigateur
    window.addEventListener('popstate', () => {
        loadCVFromURL();
    });
});

/**
 * Initialise l'état vide du viewer
 */
function initializeViewer() {
    const viewer = document.getElementById('viewer');
    if (!viewer) return;
    
    const target = viewer.dataset.target;
    viewer.classList.add('empty');
    
    if (target === 'jobs') {
        viewer.innerHTML = `
            <div class="empty-state">
                <div class="icon">💼</div>
                <h3>Sélectionnez une offre</h3>
                <p>Cliquez sur "Voir l'offre" pour afficher les détails complets</p>
            </div>
        `;
    } else {
        viewer.innerHTML = `
            <div class="empty-state">
                <div class="icon">📄</div>
                <h3>Sélectionnez un candidat</h3>
                <p>Cliquez sur "Voir son CV" pour afficher le profil complet</p>
            </div>
        `;
    }
}

/**
 * Attache les événements aux boutons "Voir son CV"
 */
function attachCardListeners() {
    const viewButtons = document.querySelectorAll('.btn-view-cv');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', async (e) => {
            e.preventDefault();
            
            const card = button.closest('.candidate-card');
            const candidateId = card.dataset.candidateId;
            
            if (candidateId) {
                await loadCandidateCV(candidateId, card);
            }
        });
    });
}

/**
 * Charge le CV d'un candidat dans le viewer
 * @param {string} candidateId - ID du candidat
 * @param {HTMLElement} card - Carte du candidat cliquée
 * @param {boolean} updateURL - Mettre à jour l'URL (défaut: true)
 */
async function loadCandidateCV(candidateId, card, updateURL = true) {
    const viewer = document.getElementById('viewer');
    if (!viewer) return;

    ViewerState.currentCandidateId = candidateId;
    ViewerState.currentCandidatePriority = card ? (parseInt(card.dataset.priority) || 0) : 0;
    
    // Mettre à jour l'URL avec le paramètre id
    if (updateURL) {
        updateURLParameter('id', candidateId);
    }
    
    // Marquer la carte comme active
    markCardAsActive(card);
    
    // Afficher le loader
    showLoader(viewer);
    
    try {
        // Récupérer le media_code depuis l'API
        const mediaCode = await getMediaCodeByID(candidateId);
        
        if (!mediaCode) {
            throw new Error('Media code introuvable pour ce candidat');
        }
        
        // Charger le CV HTML
        const cvHTML = await fetchCVHTML(mediaCode);
        
        // Mettre à jour l'état
        ViewerState.currentMediaCode = mediaCode;

        // Afficher le CV
        displayCV(viewer, cvHTML);
        
        // Scroll en haut du viewer
        viewer.parentElement.scrollTop = 0;
        
    } catch (error) {
        console.error('Erreur lors du chargement du CV:', error);
        showError(viewer, error.message);
    }
}

/**
 * Marque une carte comme active et désactive les autres
 * @param {HTMLElement} activeCard - Carte à marquer comme active
 */
function markCardAsActive(activeCard) {
    // Retirer la classe active de toutes les cartes
    document.querySelectorAll('.candidate-card').forEach(card => {
        card.classList.remove('active');
    });
    
    // Ajouter la classe active à la carte sélectionnée
    activeCard.classList.add('active');
}

/**
 * Récupère le media_code d'un candidat via son ID
 * @param {string} candidateId - ID du candidat
 * @returns {Promise<string>} - Le media_code du candidat
 */
async function getMediaCodeByID(candidateId) {
    const basePath = window.APP_CONFIG ? window.APP_CONFIG.basePath : '/1-ted-backoffice';
    const response = await fetch(basePath + '/function/getCandidateMediaCode.php?id=' + candidateId);
    
    if (!response.ok) {
        throw new Error('Erreur lors de la récupération des données du candidat');
    }
    
    const data = await response.json();
    
    if (data.success && data.media_code) {
        return data.media_code;
    }
    
    throw new Error(data.error || 'Media code introuvable dans les données du candidat');
}

/**
 * Récupère le contenu HTML du CV
 * @param {string} mediaCode - Code média du candidat
 * @returns {Promise<string>} - Contenu HTML du CV
 */
async function fetchCVHTML(mediaCode) {
    const basePath = window.APP_CONFIG ? window.APP_CONFIG.basePath : '/1-ted-backoffice';
    const cvPath = `/data/candidates/media/${mediaCode}/cv.html`;
    
    const response = await fetch(cvPath);
    
    if (!response.ok) {
        throw new Error('CV introuvable pour ce candidat');
    }
    
    const html = await response.text();
    
    if (!html || html.trim() === '') {
        throw new Error('Le CV est vide ou n\'a pas encore été généré');
    }
    
    return html;
}

/**
 * Vérifie si le candidat connecté a déjà postulé à une offre
 * @param {string} jobId - ID de l'offre
 * @returns {Promise<object>} - {applied: boolean, status: string|null}
 */
async function checkIfJobAlreadyApplied(jobId) {
    try {
        // Vérifier si l'utilisateur est un candidat connecté
        if (!window.APP_CONFIG || !window.APP_CONFIG.isCandidate) {
            return {applied: false, status: null};
        }
        
        const response = await fetch(`/function/checkIfJobApplied.php?offer_id=${jobId}`);
        if (!response.ok) {
            return {applied: false, status: null};
        }
        
        const data = await response.json();
        return {
            applied: data.applied || false,
            status: data.status || null
        };
    } catch (error) {
        console.error('Erreur lors de la vérification de la candidature:', error);
        return {applied: false, status: null};
    }
}

/**
 * Affiche le CV dans le viewer
 * @param {HTMLElement} viewer - Élément viewer
 * @param {string} cvHTML - Contenu HTML du CV
 */
async function displayCV(viewer, cvHTML) {
    viewer.classList.remove('empty');
    
    // Vérifier si c'est une offre d'emploi
    const isJobOffer = viewer.dataset.target === 'jobs';
    
    if (isJobOffer) {
        // Ajouter le header avec les boutons pour les offres d'emploi
        const currentLang = window.location.pathname.split('/')[1] || 'fr';
        const jobId = ViewerState.currentJobId || '';
        
        // Vérifier si le candidat a déjà postulé à cette offre
        const applicationStatus = await checkIfJobAlreadyApplied(jobId);
        
        // Bouton approprié selon le statut de candidature
        let applyButton = '';
        if (applicationStatus.status === 'cancelled' || applicationStatus.status === 'redflag' || applicationStatus.status === 'rejected') {
            // Candidature annulée : pas de bouton de suivi
            applyButton = '';
        } else if (applicationStatus.applied) {
            // Candidature active : afficher le bouton de suivi
            applyButton = `<button id="track-application-button" class="btn-track-application">
                📊 Suivre la candidature
               </button>`;
        } else {
            // Pas de candidature : afficher le bouton de postulation
            applyButton = `<button id="apply-job-button" class="btn-apply-job">
                📝 Postuler à l'offre d'emploi
               </button>`;
        }
        
        viewer.innerHTML = `
            <div class="job-viewer-header">
                <div class="job-viewer-actions">
                    ${applyButton}
                    <button id="share-job-button" class="btn-share-job">
                        🔗 Partager
                    </button>
                </div>
            </div>
            <div class="cv-content">${cvHTML}</div>
        `;
        
        // Attacher les événements aux boutons
        setTimeout(() => {
            if (applicationStatus.status === 'cancelled') {
                // Pas de bouton à gérer pour les candidatures annulées
            } else if (applicationStatus.applied) {
                attachTrackApplicationHandler(currentLang);
            } else {
                attachApplyButtonHandler(jobId, currentLang);
            }
            loadShareScript();
        }, 0);
    } else {
        // Ajouter le header avec le bouton pour les CVs

        const currentLang = window.location.pathname.split('/')[1] || 'fr';
        const candidateId = ViewerState.currentCandidateId || '';
        const isPublicProfileCv = ViewerState.currentCandidatePriority === 4;
        
        viewer.innerHTML = `
            <div class="cv-viewer-header">
                <div class="cv-viewer-actions">
                    <button id="view-candidate-info-button" class="btn-view-candidate-info" onclick="window.location.href='/${currentLang}/contract?candidate=${candidateId}'">
                        📋 Voir les informations du candidat
                    </button>
                </div>
            </div>
            <div class="cv-content">${cvHTML}</div>
        `;

        if (isPublicProfileCv) {
            viewer.querySelectorAll('.classified').forEach((element) => {
                element.classList.remove('classified');
            });
        }
    }
    
    // Masquer automatiquement les données sensibles (pour CVs ET offres d'emploi, sauf pour les admins)
    const isPublicProfileCv = !isJobOffer && ViewerState.currentCandidatePriority === 4;

    if ((!window.APP_CONFIG || !window.APP_CONFIG.isAdmin) && !isPublicProfileCv) {
        setTimeout(async () => {
            await classifyDataAutomatically();
        }, 0);
    }
}

/**
 * Attache le gestionnaire d'événements au bouton "Suivre la candidature"
 */
function attachTrackApplicationHandler(lang) {
    const trackButton = document.getElementById('track-application-button');
    if (!trackButton) return;
    
    trackButton.addEventListener('click', () => {
        // Rediriger vers la page de suivi des candidatures
        window.location.href = `/${lang}/user-account/candidate/my-applications`;
    });
}

/**
 * Attache le gestionnaire d'événements au bouton "Postuler"
 */
function attachApplyButtonHandler(jobId, lang) {
    const applyButton = document.getElementById('apply-job-button');
    if (!applyButton) return;
    
    applyButton.addEventListener('click', async () => {
        // Vérifier si l'utilisateur est un candidat connecté
        const isCandidate = window.APP_CONFIG && window.APP_CONFIG.isCandidate;
        
        if (isCandidate) {
            // Ouvrir la popup de candidature pour les candidats connectés
            await showApplicationPopup(jobId, lang);
        } else {
            // Rediriger vers la page de soumission pour les visiteurs
            window.location.href = `/${lang}/submit-candidate?job=${jobId}`;
        }
    });
}

/**
 * Affiche la popup de candidature avec récapitulatif
 */
async function showApplicationPopup(jobId, lang) {
    try {
        // Récupérer les infos du candidat connecté
        const candidateResponse = await fetch('/function/getMyCandidateStatus.php');
        const candidateData = await candidateResponse.json();
        
        if (!candidateData.success) {
            alert('❌ Erreur: ' + candidateData.error);
            return;
        }
        
        const candidate = candidateData.candidate;
        const approvalStatus = candidate.approved; // Utiliser 'approved' au lieu de 'is_approved' pour avoir null, 0, ou 1
        
        // Récupérer les détails de l'offre
        const jobResponse = await fetch(`/function/getJobById.php?id=${jobId}`);
        const jobData = await jobResponse.json();
        
        if (!jobData.success) {
            alert('❌ Erreur: ' + jobData.error);
            return;
        }
        
        const job = jobData.job;
        
        // Déterminer le message selon le statut d'approbation
        let warningMessage = '';
        let canApply = false;
        
        // Debug: afficher la valeur reçue
        console.log('Statut d\'approbation reçu:', approvalStatus, typeof approvalStatus);
        
        // Vérifier d'abord si c'est null ou undefined (en attente)
        if (approvalStatus === null || approvalStatus === undefined) {
            // CV en attente
            warningMessage = `
                <div class="approval-warning">
                    <div class="warning-icon">⚠️</div>
                    <div class="warning-text">
                        <strong>Votre CV n'a pas encore été validé par l'équipe 1-TED</strong>
                        <p>Veuillez attendre l'approbation de votre profil pour pouvoir postuler aux offres d'emploi.</p>
                    </div>
                </div>
            `;
        } else if (approvalStatus == 1 || approvalStatus === true) {
            // CV approuvé (== pour gérer '1' et 1)
            canApply = true;
        } else {
            // CV refusé (0, false, '0', etc.)
            warningMessage = `
                <div class="rejection-warning">
                    <div class="warning-icon">❌</div>
                    <div class="warning-text">
                        <strong>Votre CV a été refusé par l'équipe 1-TED</strong>
                        <p>Votre profil ne respecte pas les conditions d'utilisation. Veuillez consulter nos <a href="/${lang}/terms" target="_blank">Conditions Générales d'Utilisation</a> ou <a href="/${lang}/contact" target="_blank">nous contacter</a> pour plus d'informations.</p>
                    </div>
                </div>
            `;
        }
        
        // Créer la popup
        const popup = document.createElement('div');
        popup.className = 'application-popup-overlay';
        popup.innerHTML = `
            <div class="application-popup">
                <div class="application-popup-header">
                    <h3>📝 Candidature</h3>
                    <button class="application-popup-close" onclick="closeApplicationPopup()">&times;</button>
                </div>
                
                <div class="application-popup-content">
                    <div class="application-summary">
                        <h4>💼 Offre d'emploi</h4>
                        <div class="summary-item">
                            <strong>${job.title}</strong>
                        </div>
                        <div class="summary-item">
                            <span>🏢 ${job.company_name || 'Entreprise'}</span>
                        </div>
                        <div class="summary-item">
                            <span>📍 ${job.location || 'Non spécifié'}</span>
                        </div>
                        ${job.contract_type ? `<div class="summary-item"><span>💼 ${job.contract_type}</span></div>` : ''}
                        ${job.salary ? `<div class="summary-item"><span>💰 ${job.salary}</span></div>` : ''}
                    </div>
                    
                    ${warningMessage}
                </div>
                
                <div class="application-popup-actions">
                    ${canApply ? `
                        <button class="btn-popup btn-submit" onclick="submitApplication(${jobId}, '${lang}')">
                            ✅ Envoyer ma candidature
                        </button>
                    ` : ''}
                    <button class="btn-popup btn-edit-cv" onclick="window.location.href='/${lang}/user-account/candidate/my-cv'">
                        ✏️ Modifier mon CV
                    </button>
                    <button class="btn-popup btn-cancel" onclick="closeApplicationPopup()">
                        ❌ Annuler
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(popup);
        
        // Animation d'entrée
        setTimeout(() => {
            popup.classList.add('active');
        }, 10);
        
        // Fermer en cliquant sur l'overlay
        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                closeApplicationPopup();
            }
        });
        
    } catch (error) {
        console.error('Erreur:', error);
        alert('❌ Erreur lors de l\'ouverture de la popup de candidature');
    }
}

/**
 * Ferme la popup de candidature
 */
function closeApplicationPopup() {
    const popup = document.querySelector('.application-popup-overlay');
    if (popup) {
        popup.classList.remove('active');
        setTimeout(() => {
            popup.remove();
        }, 300);
    }
}

/**
 * Soumet la candidature
 */
async function submitApplication(jobId, lang) {
    // Désactiver le bouton pour éviter les doubles soumissions
    const submitBtn = document.querySelector('.btn-submit');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = '⏳ Envoi en cours...';
    }
    
    try {
        const response = await fetch('/function/createApplication.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                job_id: jobId
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Fermer la popup
            closeApplicationPopup();
            
            // Construire le message selon l'envoi des emails
            let message = '✅ Candidature envoyée avec succès !';
            
            if (data.emails_sent) {
                // Emails envoyés avec succès
                const candidateEmail = data.candidate_email || 'votre adresse email';
                const emailDetails = data.email_details || {};
                
                if (emailDetails.candidate && emailDetails.company) {
                    message += `\n\n📧 Un email de confirmation vous a été envoyé à ${candidateEmail}.\nL'entreprise a également été notifiée.`;
                } else if (emailDetails.candidate) {
                    message += `\n\n📧 Un email de confirmation vous a été envoyé à ${candidateEmail}.`;
                } else if (emailDetails.company) {
                    message += `\n\nL'entreprise a été notifiée.`;
                } else {
                    message += `\n\n⚠️ Les emails de confirmation n'ont pas pu être envoyés.`;
                }
            } else {
                // Emails non envoyés
                message += `\n\n⚠️ Les emails de confirmation n'ont pas pu être envoyés, mais votre candidature a bien été enregistrée.`;
            }
            
            alert(message);
            
            // Recharger la page pour mettre à jour le badge et le bouton
            window.location.reload();
        } else {
            // Afficher l'erreur
            alert('❌ ' + (data.error || 'Erreur lors de l\'envoi de la candidature'));
            
            // Réactiver le bouton
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = '✅ Envoyer ma candidature';
            }
        }
    } catch (error) {
        console.error('Erreur:', error);
        alert('❌ Erreur lors de l\'envoi de la candidature');
        
        // Réactiver le bouton
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = '✅ Envoyer ma candidature';
        }
    }
}

/**
 * Charge le script de partage pour les offres d'emploi
 */
function loadShareScript() {
    const basePath = window.APP_CONFIG ? window.APP_CONFIG.basePath : '/1-ted-backoffice';
    
    // Vérifier si le script est déjà chargé
    if (!document.getElementById('share-script')) {
        const shareScript = document.createElement('script');
        shareScript.id = 'share-script';
        shareScript.src = `/script/share.js`;
        document.body.appendChild(shareScript);
    } else {
        // Si le script est déjà chargé, réinitialiser les événements
        if (typeof initShareButton === 'function') {
            initShareButton();
        }
    }
}

/**
 * Masque automatiquement les données sensibles (CVs et offres d'emploi)
 */
async function classifyDataAutomatically() {
    const viewer = document.getElementById('viewer');
    const isJobOffer = viewer && viewer.dataset && viewer.dataset.target === 'jobs';
    if (!isJobOffer && ViewerState.currentCandidatePriority === 4) {
        return;
    }

    // Récupérer tous les éléments avec la classe "classified"
    const classifiedElements = document.querySelectorAll('.classified');
    
    for (const element of classifiedElements) {
        // Cas spécial : photo de profil (div avec background-image)
        if (element.classList.contains('profile-photo')) {
            // Utiliser l'ID du candidat depuis ViewerState au lieu de data-id
            // car l'attribut data-id peut être supprimé pour des raisons de sécurité
            const candidateId = ViewerState.currentCandidateId || element.getAttribute('data-id');
            let avatarUrl = '/data/candidates/media/default/photo.jpg';
            
            if (candidateId) {
                try {
                    // Appeler l'API pour récupérer l'avatar_url
                    const response = await fetch(`/function/getAvatarApi.php?candidateId=${candidateId}`);
                    const data = await response.json();
                    
                    if (data.success && data.avatarUrl) {
                        avatarUrl = data.avatarUrl;
                    }
                } catch (error) {
                    console.error('Erreur lors de la récupération de l\'avatar:', error);
                }
            }
            
            element.style.backgroundImage = `url("${avatarUrl}")`;
        }
        // Cas spécial : QR code (div footer-qr avec img à l'intérieur)
        else if (element.classList.contains('footer-qr')) {
            // Chercher l'image à l'intérieur de la div footer-qr
            const qrImage = element.querySelector('img');
            if (qrImage) {
                const basePath = window.APP_CONFIG ? window.APP_CONFIG.basePath : '/1-ted-backoffice';
                qrImage.src = `/media/image/qrcode.png`;
            }
            // Retirer la classe classified pour ne pas flouter
            element.classList.remove('classified');
        }
        // Cas général : texte
        else {
            // Remplacer le contenu texte par "Info classifiée"
            if (element.textContent && element.textContent.trim() !== '' && element.textContent !== 'Info classifiée') {
                element.textContent = 'Info classifiée';
            }
        }
    }
    
    console.log(`🔒 ${classifiedElements.length} éléments sensibles masqués automatiquement`);
}

/**
 * Affiche un loader dans le viewer
 * @param {HTMLElement} viewer - Élément viewer
 * @param {string} message - Message de chargement (optionnel)
 */
function showLoader(viewer, message = 'Chargement du CV...') {
    viewer.classList.remove('empty');
    viewer.innerHTML = `
        <div class="loader">
            <img class="invert" style="height:300px;width:auto;" src="/media/image/aienova-loader.gif" alt="Chargement..." />
            <p class="loader-message">${message}</p>
        </div>
    `;
}

/**
 * Affiche un message d'erreur dans le viewer
 * @param {HTMLElement} viewer - Élément viewer
 * @param {string} message - Message d'erreur
 */
function showError(viewer, message) {
    viewer.classList.add('empty');
    viewer.innerHTML = `
        <div class="error-state">
            <div class="icon">⚠️</div>
            <h3>Erreur</h3>
            <p>${message}</p>
        </div>
    `;
}

/**
 * Rafraîchit la liste des candidats (à appeler après ajout/modification)
 */
async function refreshCandidatesList() {
    // Cette fonction peut être appelée pour recharger la liste dynamiquement
    // Pour l'instant, on recharge la page
    window.location.reload();
}

/**
 * Met à jour un paramètre dans l'URL sans recharger la page
 * @param {string} param - Nom du paramètre
 * @param {string} value - Valeur du paramètre
 */
function updateURLParameter(param, value) {
    const url = new URL(window.location);
    url.searchParams.set(param, value);
    window.history.pushState({}, '', url);
}

/**
 * Charge automatiquement le CV ou l'offre d'emploi si l'URL contient un paramètre id
 */
function loadCVFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    const viewer = document.getElementById('viewer');
    
    // Détecter si on est sur une page de jobs ou de candidats
    const isJobsPage = viewer && viewer.dataset.target === 'jobs';
    
    if (id) {
        if (isJobsPage) {
            // Attendre que les offres soient chargées avant de charger l'offre
            waitForJobCard(id);
        } else {
            // Attendre que les candidats soient chargés avant de charger le CV
            waitForCandidateCard(id);
        }
    } else {
        // Pas de paramètre id : réinitialiser le viewer et désactiver toutes les cartes
        if (viewer) {
            initializeViewer();
        }
        
        // Retirer la classe active de toutes les cartes
        document.querySelectorAll('.candidate-card').forEach(card => {
            card.classList.remove('active');
        });
        document.querySelectorAll('.job-card').forEach(card => {
            card.classList.remove('active');
        });
    }
}

/**
 * Attend que la carte du candidat soit disponible puis charge son CV
 * @param {string} candidateId - ID du candidat
 */
function waitForCandidateCard(candidateId) {
    const checkInterval = setInterval(() => {
        const card = document.querySelector(`.candidate-card[data-candidate-id="${candidateId}"]`);
        
        if (card) {
            clearInterval(checkInterval);
            // Charger le CV sans mettre à jour l'URL (elle est déjà correcte)
            loadCandidateCV(candidateId, card, false);
        }
    }, 100);
    
    // Timeout après 5 secondes
    setTimeout(() => {
        clearInterval(checkInterval);
    }, 5000);
}

/**
 * Attend que la carte de l'offre soit disponible puis charge l'offre
 * @param {string} jobId - ID de l'offre d'emploi
 */
function waitForJobCard(jobId) {
    const checkInterval = setInterval(() => {
        const card = document.querySelector(`.job-card[data-job-id="${jobId}"]`);
        
        if (card) {
            clearInterval(checkInterval);
            // Charger l'offre sans mettre à jour l'URL (elle est déjà correcte)
            loadJobOffer(jobId, card, false);
        }
    }, 100);
    
    // Timeout après 5 secondes
    setTimeout(() => {
        clearInterval(checkInterval);
    }, 5000);
}

// ==================== FONCTIONS POUR LES OFFRES D'EMPLOI ====================

/**
 * Attache les événements aux boutons "Voir l'offre"
 */
function attachJobCardListeners() {
    const viewButtons = document.querySelectorAll('.btn-view-job');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', async (e) => {
            e.preventDefault();
            
            const card = button.closest('.job-card');
            const jobId = card.dataset.jobId;
            
            if (jobId) {
                await loadJobOffer(jobId, card);
            }
        });
    });
}

/**
 * Charge une offre d'emploi dans le viewer
 * @param {string} jobId - ID de l'offre
 * @param {HTMLElement} card - Carte de l'offre cliquée
 * @param {boolean} updateURL - Mettre à jour l'URL (défaut: true)
 */
async function loadJobOffer(jobId, card, updateURL = true) {
    const viewer = document.getElementById('viewer');
    if (!viewer) return;

    ViewerState.currentCandidatePriority = null;
    
    // Mettre à jour l'URL avec le paramètre id
    if (updateURL) {
        updateURLParameter('id', jobId);
    }
    
    // Marquer la carte comme active
    markJobCardAsActive(card);
    
    // Afficher le loader
    showLoader(viewer, 'Chargement de l\'offre...');
    
    try {
        // Charger l'offre HTML depuis le chemin statique
        const jobHTML = await fetchJobHTML(jobId);
        
        // Mettre à jour l'état
        ViewerState.currentJobId = jobId;
        
        // Afficher l'offre
        displayCV(viewer, jobHTML);
        
        // Scroll en haut du viewer
        viewer.parentElement.scrollTop = 0;
        
    } catch (error) {
        console.error('Erreur lors du chargement de l\'offre:', error);
        showError(viewer, error.message);
    }
}

/**
 * Marque une carte d'offre comme active et désactive les autres
 * @param {HTMLElement} activeCard - Carte à marquer comme active
 */
function markJobCardAsActive(activeCard) {
    // Retirer la classe active de toutes les cartes
    document.querySelectorAll('.job-card').forEach(card => {
        card.classList.remove('active');
    });
    
    // Ajouter la classe active à la carte sélectionnée
    activeCard.classList.add('active');
}

/**
 * Récupère le contenu HTML de l'offre d'emploi
 * @param {string} jobId - ID de l'offre
 * @returns {Promise<string>} - Contenu HTML de l'offre
 */
async function fetchJobHTML(jobId) {
    // Essayer d'abord de charger le fichier HTML statique
    const basePath = window.APP_CONFIG ? window.APP_CONFIG.basePath : '/1-ted-backoffice';
    

    
    // Si le fichier statique n'existe pas, charger dynamiquement
    const jobPath = `/data/jobs/id/index.php?id=${jobId}`;
    
    const response = await fetch(jobPath);
    
    if (!response.ok) {
        throw new Error('Offre d\'emploi introuvable');
    }
    
    const html = await response.text();
    
    if (!html || html.trim() === '') {
        throw new Error('L\'offre est vide ou n\'a pas encore été générée');
    }
    
    // Nettoyer le HTML : enlever les classes d'édition et les boutons
    return cleanJobHTML(html);
}

/**
 * Nettoie le HTML de l'offre en enlevant les éléments d'édition
 * @param {string} html - HTML brut de l'offre
 * @returns {string} - HTML nettoyé
 */
function cleanJobHTML(html) {
    // Créer un élément temporaire pour parser le HTML
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    
    // Supprimer le conteneur de boutons
    const buttonContainer = doc.querySelector('.button-container');
    if (buttonContainer) {
        buttonContainer.remove();
    }
    
    // Supprimer tous les scripts
    const scripts = doc.querySelectorAll('script');
    scripts.forEach(script => script.remove());
    
    // Ajouter la classe 'classified' aux éléments qui ont data-classified="true"
    const classifiedElements = doc.querySelectorAll('[data-classified="true"]');
    classifiedElements.forEach(element => {
        element.classList.add('classified');
    });
    
    // Enlever toutes les classes et attributs d'édition
    const editableElements = doc.querySelectorAll('.canEdit, .dataToEdit, .multipleDataToEdit');
    editableElements.forEach(element => {
        element.classList.remove('canEdit');
        element.classList.remove('dataToEdit');
        element.classList.remove('multipleDataToEdit');
        
        // Supprimer TOUS les attributs data-* (pour sécurité)
        element.removeAttribute('data-editor');
        element.removeAttribute('data-table');
        element.removeAttribute('data-column');
        element.removeAttribute('data-id');
        element.removeAttribute('data-value');
        element.removeAttribute('data-classified');
        element.removeAttribute('data-collection');
        element.removeAttribute('data-mode');
    });
    
    // Récupérer uniquement le contenu du body
    const bodyContent = doc.body.innerHTML;
    
    // Ajouter un wrapper avec alignement à gauche
    return `<div style="text-align: left; width: 100%;">${bodyContent}</div>`;
}

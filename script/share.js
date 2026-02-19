/**
 * Script pour gérer le partage des offres d'emploi sur les réseaux sociaux
 */

/**
 * Initialise le bouton de partage
 */
function initShareButton() {
    const shareButton = document.getElementById('share-job-button');
    
    if (shareButton && !shareButton.dataset.initialized) {
        shareButton.dataset.initialized = 'true';
        shareButton.addEventListener('click', (e) => {
            e.preventDefault();
            showShareModal();
        });
    }
}

/**
 * Affiche le modal de partage
 */
function showShareModal() {
    // Supprimer le modal existant s'il y en a un
    const existingModal = document.getElementById('share-modal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Récupérer l'URL actuelle de la page
    const currentUrl = window.location.href;
    const pageTitle = document.querySelector('.job-offer-container .job-title')?.textContent || 'Offre d\'emploi';
    
    // Créer le modal
    const modal = document.createElement('div');
    modal.id = 'share-modal';
    modal.className = 'share-modal';
    modal.innerHTML = `
        <div class="share-modal-overlay"></div>
        <div class="share-modal-content">
            <div class="share-modal-header">
                <h3>Partager cette offre</h3>
                <button class="share-modal-close">&times;</button>
            </div>
            
            <div class="share-modal-body">
                <div class="share-buttons">
                    <button class="share-btn share-linkedin" data-platform="linkedin">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                        </svg>
                        LinkedIn
                    </button>
                    
                    <button class="share-btn share-facebook" data-platform="facebook">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z"/>
                        </svg>
                        Facebook
                    </button>
                    
                    <button class="share-btn share-twitter" data-platform="twitter">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.46 6c-.85.38-1.78.64-2.75.76a4.8 4.8 0 0 0 2.1-2.66c-.92.55-1.95.95-3.04 1.17a4.77 4.77 0 0 0-8.12 4.35A13.54 13.54 0 0 1 1.64 4.16a4.77 4.77 0 0 0 1.48 6.37 4.74 4.74 0 0 1-2.16-.6v.06a4.77 4.77 0 0 0 3.83 4.68 4.78 4.78 0 0 1-2.15.08 4.77 4.77 0 0 0 4.46 3.31A9.57 9.57 0 0 1 .96 19.67a13.5 13.5 0 0 0 7.29 2.14c8.75 0 13.54-7.25 13.54-13.54 0-.21 0-.41-.01-.61A9.65 9.65 0 0 0 24 4.59a9.45 9.45 0 0 0-2.74.75z"/>
                        </svg>
                        Twitter
                    </button>
                    
                    <button class="share-btn share-email" data-platform="email">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        Email
                    </button>
                    
                    <button class="share-btn share-copy" data-platform="copy">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                        </svg>
                        Copier le lien
                    </button>
                </div>
                
                <div class="share-url-container">
                    <input type="text" class="share-url-input" value="${currentUrl}" readonly>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Attacher les événements
    attachShareModalEvents(modal, currentUrl, pageTitle);
    
    // Afficher le modal avec animation
    setTimeout(() => {
        modal.classList.add('active');
    }, 10);
}

/**
 * Attache les événements au modal de partage
 * @param {HTMLElement} modal - L'élément modal
 * @param {string} url - L'URL à partager
 * @param {string} title - Le titre de la page
 */
function attachShareModalEvents(modal, url, title) {
    // Bouton de fermeture
    const closeButton = modal.querySelector('.share-modal-close');
    const overlay = modal.querySelector('.share-modal-overlay');
    
    const closeModal = () => {
        modal.classList.remove('active');
        setTimeout(() => {
            modal.remove();
        }, 300);
    };
    
    closeButton.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);
    
    // Boutons de partage
    const shareButtons = modal.querySelectorAll('.share-btn');
    
    shareButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const platform = button.dataset.platform;
            shareOnPlatform(platform, url, title);
        });
    });
    
    // Input URL - sélectionner tout au clic
    const urlInput = modal.querySelector('.share-url-input');
    urlInput.addEventListener('click', () => {
        urlInput.select();
    });
}

/**
 * Partage sur la plateforme sélectionnée
 * @param {string} platform - La plateforme de partage
 * @param {string} url - L'URL à partager
 * @param {string} title - Le titre de la page
 */
function shareOnPlatform(platform, url, title) {
    const encodedUrl = encodeURIComponent(url);
    const encodedTitle = encodeURIComponent(title);
    
    let shareUrl = '';
    
    switch (platform) {
        case 'linkedin':
            shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`;
            break;
            
        case 'facebook':
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
            break;
            
        case 'twitter':
            shareUrl = `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedTitle}`;
            break;
            
        case 'email':
            shareUrl = `mailto:?subject=${encodedTitle}&body=Découvrez cette offre d'emploi : ${encodedUrl}`;
            break;
            
        case 'copy':
            copyToClipboard(url);
            return;
    }
    
    if (shareUrl) {
        window.open(shareUrl, '_blank', 'width=600,height=400,scrollbars=yes');
    }
}

/**
 * Copie le texte dans le presse-papiers
 * @param {string} text - Le texte à copier
 */
function copyToClipboard(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(() => {
            showCopyConfirmation();
        }).catch(err => {
            console.error('Erreur lors de la copie:', err);
            fallbackCopyToClipboard(text);
        });
    } else {
        fallbackCopyToClipboard(text);
    }
}

/**
 * Méthode de secours pour copier dans le presse-papiers
 * @param {string} text - Le texte à copier
 */
function fallbackCopyToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    document.body.appendChild(textArea);
    textArea.select();
    
    try {
        document.execCommand('copy');
        showCopyConfirmation();
    } catch (err) {
        console.error('Erreur lors de la copie:', err);
    }
    
    document.body.removeChild(textArea);
}

/**
 * Affiche une confirmation de copie
 */
function showCopyConfirmation() {
    const copyButton = document.querySelector('.share-copy');
    if (!copyButton) return;
    
    const originalText = copyButton.innerHTML;
    
    copyButton.innerHTML = `
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
        </svg>
        Copié !
    `;
    
    copyButton.classList.add('copied');
    
    setTimeout(() => {
        copyButton.innerHTML = originalText;
        copyButton.classList.remove('copied');
    }, 2000);
}

// Initialiser au chargement du script
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initShareButton);
} else {
    initShareButton();
}

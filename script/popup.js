/**
 * Système de gestion des popups
 * Permet d'ouvrir/fermer des popups avec du contenu dynamique
 */

// Ouvrir une popup
function openPopup(popupId, content = null) {
    const popup = document.getElementById(popupId);
    
    if (!popup) {
        console.error(`Popup ${popupId} introuvable`);
        return;
    }
    
    // Si du contenu est fourni, l'insérer
    if (content) {
        const contentContainer = popup.querySelector('.popup-content');
        if (contentContainer) {
            contentContainer.innerHTML = content;
        }
    }
    
    // Afficher la popup
    popup.classList.add('active');
    document.body.style.overflow = 'hidden'; // Bloquer le scroll
}

// Fermer une popup
function closePopup(popupId) {
    const popup = document.getElementById(popupId);
    
    if (!popup) {
        console.error(`Popup ${popupId} introuvable`);
        return;
    }
    
    popup.classList.remove('active');
    document.body.style.overflow = ''; // Restaurer le scroll
}

// Fermer toutes les popups
function closeAllPopups() {
    const popups = document.querySelectorAll('.popup.active');
    popups.forEach(popup => {
        popup.classList.remove('active');
    });
    document.body.style.overflow = '';
}

// Charger du contenu dynamique dans une popup
async function loadPopupContent(popupId, url, params = {}) {
    try {
        // Construire l'URL avec les paramètres
        const urlParams = new URLSearchParams(params);
        const fullUrl = url + (Object.keys(params).length > 0 ? '?' + urlParams : '');
        
        // Afficher un loader
        openPopup(popupId, '<div class="popup-loader"><div class="spinner"></div><p>Chargement...</p></div>');
        
        // Charger le contenu
        const response = await fetch(fullUrl);
        
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        
        const content = await response.text();
        
        // Mettre à jour le contenu
        const popup = document.getElementById(popupId);
        const contentContainer = popup.querySelector('.popup-content');
        if (contentContainer) {
            contentContainer.innerHTML = content;
        }
        
    } catch (error) {
        console.error('Erreur lors du chargement du contenu:', error);
        openPopup(popupId, `
            <div class="popup-error">
                <span class="error-icon">⚠️</span>
                <h3>Erreur de chargement</h3>
                <p>${error.message}</p>
                <button onclick="closePopup('${popupId}')" class="btn-close-error">Fermer</button>
            </div>
        `);
    }
}

// Initialiser les événements au chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
    // Gérer les clics sur les boutons de fermeture
    document.addEventListener('click', function(e) {
        // Fermer avec le bouton close
        if (e.target.classList.contains('popup-close') || e.target.closest('.popup-close')) {
            const popup = e.target.closest('.popup');
            if (popup) {
                closePopup(popup.id);
            }
        }
        
        // Fermer en cliquant sur l'overlay
        if (e.target.classList.contains('popup-overlay')) {
            const popup = e.target.closest('.popup');
            if (popup) {
                closePopup(popup.id);
            }
        }
    });
    
    // Fermer avec la touche ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllPopups();
        }
    });
});

/**
 * Gestion du bandeau RGPD / Cookie Consent
 * Gère l'affichage, les préférences utilisateur et le stockage des choix
 */

// Clé de stockage localStorage
const GDPR_STORAGE_KEY = 'gdpr-consent';

// Objet pour stocker les préférences
let cookiePreferences = {
    necessary: true,      // Toujours vrai
    analytics: false,
    marketing: false,
    preferences: false,
    consentGiven: false,  // Indique si l'utilisateur a fait un choix
    timestamp: null       // Date du consentement
};

/**
 * Initialisation au chargement de la page
 */
document.addEventListener('DOMContentLoaded', () => {
    // Charger les préférences existantes
    loadCookiePreferences();
    
    // Vérifier si l'utilisateur a déjà donné son consentement
    if (!cookiePreferences.consentGiven) {
        showGDPRBanner();
    } else {
        // Appliquer les préférences sauvegardées
        applyCookiePreferences();
    }
    
    // Attacher les événements aux boutons
    attachGDPREventListeners();
});

/**
 * Charger les préférences depuis localStorage
 */
function loadCookiePreferences() {
    const stored = localStorage.getItem(GDPR_STORAGE_KEY);
    
    if (stored) {
        try {
            const parsed = JSON.parse(stored);
            cookiePreferences = { ...cookiePreferences, ...parsed };
            console.log('✅ Préférences RGPD chargées:', cookiePreferences);
        } catch (error) {
            console.error('❌ Erreur lors du chargement des préférences RGPD:', error);
        }
    }
}

/**
 * Sauvegarder les préférences dans localStorage
 */
function saveCookiePreferences() {
    cookiePreferences.timestamp = new Date().toISOString();
    
    try {
        localStorage.setItem(GDPR_STORAGE_KEY, JSON.stringify(cookiePreferences));
        console.log('💾 Préférences RGPD sauvegardées:', cookiePreferences);
    } catch (error) {
        console.error('❌ Erreur lors de la sauvegarde des préférences RGPD:', error);
    }
}

/**
 * Afficher le bandeau RGPD
 */
function showGDPRBanner() {
    const banner = document.getElementById('gdpr-banner');
    if (banner) {
        banner.style.display = 'flex';
        
        console.log('🍪 Bandeau RGPD affiché');
    }
}

/**
 * Masquer le bandeau RGPD avec animation
 */
function hideGDPRBanner() {
    const banner = document.getElementById('gdpr-banner');
    if (banner) {
        // Ajouter la classe d'animation de fermeture
        banner.classList.add('gdpr-closing');
        
        // Attendre la fin de l'animation avant de masquer
        setTimeout(() => {
            banner.style.display = 'none';
            banner.classList.remove('gdpr-closing');
            
            console.log('🍪 Bandeau RGPD masqué');
        }, 300);
    }
}

/**
 * Attacher les événements aux boutons
 */
function attachGDPREventListeners() {
    // Bouton "Accepter tout"
    const acceptAllBtn = document.getElementById('gdpr-accept-all');
    if (acceptAllBtn) {
        acceptAllBtn.addEventListener('click', handleAcceptAll);
    }
    
    // Bouton "Tout refuser"
    const rejectAllBtn = document.getElementById('gdpr-reject-all');
    if (rejectAllBtn) {
        rejectAllBtn.addEventListener('click', handleRejectAll);
    }
    
    // Bouton "Personnaliser"
    const customizeBtn = document.getElementById('gdpr-customize');
    if (customizeBtn) {
        customizeBtn.addEventListener('click', showDetailedView);
    }
    
    // Bouton "Retour" (vue détaillée)
    const backBtn = document.getElementById('gdpr-back');
    if (backBtn) {
        backBtn.addEventListener('click', showSimpleView);
    }
    
    // Bouton "Enregistrer mes préférences"
    const saveBtn = document.getElementById('gdpr-save-preferences');
    if (saveBtn) {
        saveBtn.addEventListener('click', handleSavePreferences);
    }
}

/**
 * Gérer "Accepter tout"
 */
function handleAcceptAll() {
    console.log('✅ Accepter tous les cookies');
    
    cookiePreferences = {
        necessary: true,
        analytics: true,
        marketing: true,
        preferences: true,
        consentGiven: true,
        timestamp: null
    };
    
    saveCookiePreferences();
    applyCookiePreferences();
    hideGDPRBanner();
}

/**
 * Gérer "Tout refuser"
 */
function handleRejectAll() {
    console.log('❌ Refuser tous les cookies (sauf nécessaires)');
    
    cookiePreferences = {
        necessary: true,
        analytics: false,
        marketing: false,
        preferences: false,
        consentGiven: true,
        timestamp: null
    };
    
    saveCookiePreferences();
    applyCookiePreferences();
    hideGDPRBanner();
}

/**
 * Afficher la vue détaillée (personnalisation)
 */
function showDetailedView() {
    console.log('⚙️ Affichage de la vue personnalisée');
    
    const simpleView = document.getElementById('gdpr-simple-view');
    const detailedView = document.getElementById('gdpr-detailed-view');
    
    if (simpleView && detailedView) {
        simpleView.style.display = 'none';
        detailedView.style.display = 'block';
        
        // Charger les préférences actuelles dans les checkboxes
        loadPreferencesIntoCheckboxes();
    }
}

/**
 * Afficher la vue simple
 */
function showSimpleView() {
    console.log('← Retour à la vue simple');
    
    const simpleView = document.getElementById('gdpr-simple-view');
    const detailedView = document.getElementById('gdpr-detailed-view');
    
    if (simpleView && detailedView) {
        detailedView.style.display = 'none';
        simpleView.style.display = 'block';
    }
}

/**
 * Charger les préférences actuelles dans les checkboxes
 */
function loadPreferencesIntoCheckboxes() {
    const analyticsCheckbox = document.getElementById('cookie-analytics');
    const marketingCheckbox = document.getElementById('cookie-marketing');
    const preferencesCheckbox = document.getElementById('cookie-preferences');
    
    if (analyticsCheckbox) analyticsCheckbox.checked = cookiePreferences.analytics;
    if (marketingCheckbox) marketingCheckbox.checked = cookiePreferences.marketing;
    if (preferencesCheckbox) preferencesCheckbox.checked = cookiePreferences.preferences;
}

/**
 * Gérer "Enregistrer mes préférences"
 */
function handleSavePreferences() {
    console.log('💾 Enregistrement des préférences personnalisées');
    
    const analyticsCheckbox = document.getElementById('cookie-analytics');
    const marketingCheckbox = document.getElementById('cookie-marketing');
    const preferencesCheckbox = document.getElementById('cookie-preferences');
    
    cookiePreferences = {
        necessary: true, // Toujours vrai
        analytics: analyticsCheckbox ? analyticsCheckbox.checked : false,
        marketing: marketingCheckbox ? marketingCheckbox.checked : false,
        preferences: preferencesCheckbox ? preferencesCheckbox.checked : false,
        consentGiven: true,
        timestamp: null
    };
    
    saveCookiePreferences();
    applyCookiePreferences();
    hideGDPRBanner();
}

/**
 * Appliquer les préférences de cookies
 * (Charger/Bloquer les scripts selon les choix)
 */
function applyCookiePreferences() {
    console.log('🔧 Application des préférences de cookies:', cookiePreferences);
    
    // Cookies analytics (Google Analytics, etc.)
    if (cookiePreferences.analytics) {
        enableAnalytics();
    } else {
        disableAnalytics();
    }
    
    // Cookies marketing (Facebook Pixel, Google Ads, etc.)
    if (cookiePreferences.marketing) {
        enableMarketing();
    } else {
        disableMarketing();
    }
    
    // Cookies de préférences (langue, thème, etc.)
    if (cookiePreferences.preferences) {
        enablePreferences();
    } else {
        disablePreferences();
    }
}

/**
 * Activer les cookies analytics
 */
function enableAnalytics() {
    console.log('📊 Activation des cookies analytics');
    
    // Exemple: Charger Google Analytics
    // window.dataLayer = window.dataLayer || [];
    // function gtag(){dataLayer.push(arguments);}
    // gtag('js', new Date());
    // gtag('config', 'GA_MEASUREMENT_ID');
    
    // Indiquer aux scripts analytics qu'ils peuvent fonctionner
    window.analyticsEnabled = true;
}

/**
 * Désactiver les cookies analytics
 */
function disableAnalytics() {
    console.log('📊 Désactivation des cookies analytics');
    window.analyticsEnabled = false;
    
    // Supprimer les cookies analytics existants
    deleteCookiesByPrefix('_ga');
    deleteCookiesByPrefix('_gid');
}

/**
 * Activer les cookies marketing
 */
function enableMarketing() {
    console.log('📢 Activation des cookies marketing');
    
    // Exemple: Charger Facebook Pixel, Google Ads, etc.
    window.marketingEnabled = true;
}

/**
 * Désactiver les cookies marketing
 */
function disableMarketing() {
    console.log('📢 Désactivation des cookies marketing');
    window.marketingEnabled = false;
    
    // Supprimer les cookies marketing existants
    deleteCookiesByPrefix('_fbp');
    deleteCookiesByPrefix('fr');
}

/**
 * Activer les cookies de préférences
 */
function enablePreferences() {
    console.log('⚙️ Activation des cookies de préférences');
    window.preferencesEnabled = true;
}

/**
 * Désactiver les cookies de préférences
 */
function disablePreferences() {
    console.log('⚙️ Désactivation des cookies de préférences');
    window.preferencesEnabled = false;
}

/**
 * Supprimer les cookies par préfixe
 * @param {string} prefix - Préfixe du nom du cookie
 */
function deleteCookiesByPrefix(prefix) {
    const cookies = document.cookie.split(';');
    
    for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i].trim();
        const cookieName = cookie.split('=')[0];
        
        if (cookieName.startsWith(prefix)) {
            // Supprimer le cookie
            document.cookie = cookieName + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            console.log('🗑️ Cookie supprimé:', cookieName);
        }
    }
}

/**
 * Fonction publique pour réinitialiser les préférences RGPD
 * (Utile pour les tests ou si l'utilisateur veut modifier ses choix)
 */
function resetGDPRPreferences() {
    localStorage.removeItem(GDPR_STORAGE_KEY);
    cookiePreferences = {
        necessary: true,
        analytics: false,
        marketing: false,
        preferences: false,
        consentGiven: false,
        timestamp: null
    };
    console.log('🔄 Préférences RGPD réinitialisées');
    location.reload();
}

// Exposer la fonction de réinitialisation globalement
window.resetGDPRPreferences = resetGDPRPreferences;

// Exposer les préférences globalement pour que d'autres scripts puissent les consulter
window.getCookiePreferences = () => cookiePreferences;

console.log('🍪 Script GDPR initialisé');

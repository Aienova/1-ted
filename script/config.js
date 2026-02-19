/**
 * Utilitaire de configuration pour accéder aux chemins de l'application
 */

/**
 * Obtient le basePath de l'application depuis la configuration
 * @returns {string} Le basePath configuré (ex: '/1-ted-backoffice' en local, '' en prod)
 */
function getBasePath() {
    return window.APP_CONFIG ? window.APP_CONFIG.basePath : '';
}

/**
 * Obtient le baseUrl de l'application depuis la configuration
 * @returns {string} Le baseUrl complet (ex: 'http://1-ted.local/')
 */
function getBaseUrl() {
    return window.APP_CONFIG ? window.APP_CONFIG.baseUrl : 'http://1-ted.local/';
}

/**
 * Construit une URL absolue vers une fonction PHP
 * @param {string} functionPath - Chemin relatif depuis le dossier function (ex: 'insertData.php')
 * @returns {string} URL absolue complète
 */
function getFunctionUrl(functionPath) {
    return `${getBasePath()}/function/${functionPath}`;
}

/**
 * Construit une URL absolue vers n'importe quel fichier
 * @param {string} path - Chemin relatif depuis la racine (ex: 'style/body.css')
 * @returns {string} URL absolue complète
 */
function getAbsolutePath(path) {
    // Enlever le slash de début si présent
    const cleanPath = path.startsWith('/') ? path.substring(1) : path;
    return `${getBasePath()}/${cleanPath}`;
}

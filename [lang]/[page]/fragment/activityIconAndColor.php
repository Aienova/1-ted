<script>
/**
 * Retourne l'attribut style background-color correspondant au secteur d'activité.
 * @param {string} activityName - La clé du secteur (ex: 'computer', 'health', ...)
 * @returns {string} Attribut HTML style (ex: 'style="background-color:#4A90D9"')
 */


function getTextContrastColor(hexColor) {
    // Convertir le code hexadécimal en RGB
    const r = parseInt(hexColor.substr(1, 2), 16);
    const g = parseInt(hexColor.substr(3, 2), 16);
    const b = parseInt(hexColor.substr(5, 2), 16);

    // Calculer la luminance relative
    const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;

    // Retourner noir ou blanc selon la luminance
    return luminance > 0.5 ? '#000000' : '#FFFFFF';
}

function getActivityColor(activityName) {
    const colors = {
        'computer':    '#77c6cb', // bleu tech
        'building':    '#E8912A', // orange BTP
        'health':      '#E05C6A', // rouge médical
        'sales':       '#27AE60', // vert commerce
        'finance':     '#1A7A4A', // vert foncé finance
        'marketing':   '#8E44AD', // violet marketing
        'hr':          '#16A085', // teal RH
        'industry':    '#7F8C8D', // gris industrie
        'logistic':    '#D35400', // orange transport
        'education':   '#2980B9', // bleu éducation
        'hospitality': '#F39C12', // jaune or hôtellerie
        'legal':       '#2C3E50', // bleu nuit juridique
        'agriculture': '#5D8A3C', // vert agriculture
        'energy':      '#F1C40F', // jaune énergie
        'real-estate': '#A0522D', // marron immobilier
        'automobil':   '#555F60', // gris anthracite auto
        'aerospace':   '#1B3A6B', // bleu foncé aéro
        'arts':        '#C0392B', // rouge arts
        'media':       '#E74C3C', // rouge médias
        'design':      '#E91E8C', // rose design
        'consulting':  '#34495E', // gris bleu conseil
        'services':    '#95A5A6', // gris services
        'social':      '#1ABC9C', // turquoise social
        'sport':       '#28B463', // vert sport
        'luxury':      '#B8860B', // or luxe
        'chemistry':   '#6C3483', // violet chimie
        'telecom':     '#2471A3', // bleu télécom
        'public':      '#1F618D', // bleu marine public
        'r&d':         '#0097A7', // cyan R&D
        'defense':     '#4E5B31', // kaki défense
    };

    const color = colors[activityName] || '#999999';
    return `style="background-color:${color}; color:${getTextContrastColor(color)}"`;
}

/**
 * Retourne l'emoji correspondant au secteur d'activité.
 * @param {string} activityName - La clé du secteur (ex: 'computer', 'health', ...)
 * @returns {string} Emoji représentant le secteur
 */
function getActivityIcon(activityName) {
    const icons = {
        'computer':    '💻',
        'building':    '🏗️',
        'health':      '🏥',
        'sales':       '🛒',
        'finance':     '💰',
        'marketing':   '📢',
        'hr':          '👥',
        'industry':    '⚙️',
        'logistic':    '🚚',
        'education':   '📚',
        'hospitality': '🏨',
        'legal':       '⚖️',
        'agriculture': '🌾',
        'energy':      '⚡',
        'real-estate': '🏠',
        'automobil':   '🚗',
        'aerospace':   '✈️',
        'arts':        '🎭',
        'media':       '📺',
        'design':      '🎨',
        'consulting':  '📊',
        'services':    '🔧',
        'social':      '🤝',
        'sport':       '⚽',
        'luxury':      '💎',
        'chemistry':   '🧪',
        'telecom':     '📡',
        'public':      '🏛️',
        'r&d':         '🔬',
        'defense':     '🛡️',
    };

    return icons[activityName] || '💼';
}
</script>

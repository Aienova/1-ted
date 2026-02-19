/**
 * Script pour gérer le moteur de recherche
 * Redirige vers la page appropriée selon le type de recherche sélectionné
 */

document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Empêcher la soumission par défaut
            
            // Récupérer les valeurs des champs
            const keywords = document.querySelector('[name="keywords"]');
            const location = document.querySelector('[name="location"]');
            
            // Pour le type de recherche, on récupère tous les champs avec ce nom (select + hidden)
            // et on prend la valeur du premier qui n'est pas vide ou disabled
            const searchTypeFields = document.querySelectorAll('[name="candidate-or-joboffer"]');
            let searchTypeValue = '';
            
            for (let field of searchTypeFields) {
                if (field.value && (!field.disabled || field.type === 'hidden')) {
                    searchTypeValue = field.value;
                    break;
                }
            }
            
            if (!keywords || !location || !searchTypeValue) {
                console.error('Un ou plusieurs champs du formulaire sont introuvables');
                return;
            }
            
            const keywordValue = keywords.value.trim();
            const locationValue = location.value.trim();
            
            // Validation des champs requis
            if (!keywordValue || !locationValue) {
                alert('Veuillez remplir tous les champs requis');
                return;
            }
            
            // Déterminer la page de destination
            let targetPage = '';
            if (searchTypeValue === 'joboffer') {
                targetPage = 'offers';
            } else if (searchTypeValue === 'candidate') {
                targetPage = 'candidates';
            } else {
                console.error('Type de recherche invalide:', searchTypeValue);
                return;
            }
            
            // Construire l'URL avec les paramètres
            // Récupérer la configuration depuis la variable globale
            const basePath = window.APP_CONFIG ? window.APP_CONFIG.basePath : '/1-ted-backoffice';
            const currentLang = window.APP_CONFIG ? window.APP_CONFIG.lang : 'fr';
            
            // Construire l'URL de redirection
            const baseUrl = window.location.origin + basePath + '/' + currentLang + '/' + targetPage;
            const params = new URLSearchParams({
                keyword: keywordValue,
                location: locationValue
            });
            
            const redirectUrl = baseUrl + '?' + params.toString();
            
            // Rediriger vers la page appropriée
            window.location.href = redirectUrl;
        });
    }
});

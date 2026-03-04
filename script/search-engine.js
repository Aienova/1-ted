/**
 * Script pour gérer le moteur de recherche
 * Redirige vers la page appropriée selon le type de recherche sélectionné
 */

document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    
    if (searchForm) {
        // Gérer la checkbox "Rechercher partout en France"
        const searchEverywhereCheckbox = document.getElementById('search-everywhere-france');
        const locationInput = document.querySelector('[name="location"]');
        
        if (searchEverywhereCheckbox && locationInput) {
            // Événement sur le changement de la checkbox
            searchEverywhereCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Désactiver et vider l'input location
                    locationInput.disabled = true;
                    locationInput.value = '';
                    locationInput.removeAttribute('required');
                    locationInput.classList.add('disabled-input');
                    locationInput.placeholder = 'Recherche dans toute la France...';
                } else {
                    // Réactiver l'input location
                    locationInput.disabled = false;
                    locationInput.setAttribute('required', 'required');
                    locationInput.classList.remove('disabled-input');
                    // Restaurer le placeholder d'origine (à adapter selon vos besoins)
                    locationInput.placeholder = locationInput.getAttribute('data-original-placeholder') || 'Paris, Lyon, Marseille...';
                }
            });
            
            // Stocker le placeholder d'origine
            if (!locationInput.hasAttribute('data-original-placeholder')) {
                locationInput.setAttribute('data-original-placeholder', locationInput.placeholder);
            }
        }
        
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Empêcher la soumission par défaut
            
            // Récupérer les valeurs des champs
            const keywords = document.querySelector('[name="keywords"]');
            const location = document.querySelector('[name="location"]');
            const searchEverywhereCheckbox = document.getElementById('search-everywhere-france');
            
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
            const searchEverywhere = searchEverywhereCheckbox && searchEverywhereCheckbox.checked;

            // Validation des champs requis (location non requis si recherche partout en France)
            if (!keywordValue) {
                alert('Veuillez remplir le champ de recherche');
                return;
            }
            
            if (!searchEverywhere && !locationValue) {
                alert('Veuillez remplir le champ de localisation ou cocher "Rechercher partout en France"');
                return;
            }

            // Validation de longueur (max 100 caractères)
            if (keywordValue.length > 100 || locationValue.length > 100) {
                alert('Les champs de recherche ne peuvent pas dépasser 100 caractères');
                return;
            }

            // Détection de contenu suspect (balises HTML / scripts)
            const suspiciousPattern = /<[^>]*>|javascript:|on\w+\s*=/i;
            if (suspiciousPattern.test(keywordValue) || suspiciousPattern.test(locationValue)) {
                alert('Les champs de recherche contiennent des caractères non autorisés');
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
                keyword: keywordValue
            });
            
            // Ajouter la location seulement si pas de recherche partout en France
            if (!searchEverywhere && locationValue) {
                params.append('location', locationValue);
            }
            
            const redirectUrl = baseUrl + '?' + params.toString();
            
            // Rediriger vers la page appropriée
            window.location.href = redirectUrl;
        });
    }
});

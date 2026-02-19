document.addEventListener('DOMContentLoaded', function() {
    const languageLinks = document.querySelectorAll('.language-link');
    
    languageLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const newLang = this.getAttribute('data-lang');
            const currentUrl = window.location.pathname;
            
            // Extraire le chemin après la base URL
            // Format: {basePath}/fr/page ou {basePath}/fr
            const pathParts = currentUrl.split('/');
            
            // Trouver l'index de la langue actuelle
            let langIndex = -1;
            for (let i = 0; i < pathParts.length; i++) {
                if (pathParts[i].length === 2 && /^[a-z]{2}$/.test(pathParts[i])) {
                    langIndex = i;
                    break;
                }
            }
            
            if (langIndex !== -1) {
                // Remplacer le code langue
                pathParts[langIndex] = newLang;
                const newUrl = pathParts.join('/');
                
                // Rediriger vers la nouvelle URL
                window.location.href = newUrl;
            }
        });
    });
});
// Script pour déclassifier le CV (restaurer les données sensibles)
document.addEventListener('DOMContentLoaded', () => {
    const declassifyButton = document.getElementById('declassify-cv-button');
    
    if (declassifyButton) {
        declassifyButton.addEventListener('click', () => {
            // Récupérer les données depuis localStorage
            const storedData = localStorage.getItem('classifiedText');
            
            if (!storedData) {
                console.error('❌ Aucune donnée classifiée trouvée dans localStorage');
                return;
            }
            
            const classifiedData = JSON.parse(storedData);
            
            // Récupérer tous les éléments avec data-classified="true"
            const classifiedElements = document.querySelectorAll('[data-classified="true"]');
            
            // Restaurer les données authentiques
            classifiedElements.forEach((element, index) => {
                const dataEntry = classifiedData[index];
                
                if (!dataEntry) {
                    console.warn(`⚠️ Pas de données pour l'élément ${index}`);
                    return;
                }
                
                // Restaurer selon le type
                if (dataEntry.isProfilePhoto) {
                    // Restaurer la photo de profil
                    element.style.backgroundImage = dataEntry.backgroundImage;
                } else if (dataEntry.isQRCode) {
                    // Restaurer le QR code
                    element.src = dataEntry.src;
                } else {
                    // Restaurer le texte et data-value
                    element.textContent = dataEntry.textContent;
                    element.setAttribute('data-value', dataEntry.dataValue);
                }
                
                // Retirer la classe .classified
                element.classList.remove('classified');
            });
            
            // Nettoyer le localStorage
            localStorage.removeItem('classifiedText');
            
            // Masquer le bouton déclassifier
            declassifyButton.style.display = 'none';
            
            // Afficher le bouton classifier
            const classifyButton = document.getElementById('classify-cv-button');
            if (classifyButton) {
                classifyButton.style.display = 'inline-block';
            }
            
            console.log('🔓 CV déclassifié - Données authentiques restaurées');
            console.log(`📦 ${classifiedData.length} éléments restaurés`);
        });
    }
});

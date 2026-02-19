/**
 * Convertit le CV en HTML statique et le sauvegarde dans le dossier media
 * @param {string} mediaCode - Le code média du candidat
 * @returns {Promise<boolean>} - True si la conversion a réussi
 */
async function convertCVToHTML(mediaCode) {
    console.log('🔄 Conversion du CV en HTML statique...');
    
    if (!mediaCode) {
        console.error('❌ Aucun media_code fourni pour la conversion HTML');
        return false;
    }
    
    try {
        // Récupérer le conteneur CV
        const cvContainer = document.querySelector('.cv-container');
        if (!cvContainer) {
            console.error('❌ Conteneur CV introuvable');
            return false;
        }
        
        // Cloner le conteneur pour ne pas modifier l'original
        const clonedContainer = cvContainer.cloneNode(true);
        
        // Nettoyer le clone:
        // 1. Ajouter la classe 'classified' aux éléments qui ont data-classified="true"
        const classifiedElements = clonedContainer.querySelectorAll('[data-classified="true"]');
        classifiedElements.forEach(element => {
            element.classList.add('classified');
        });
        
        // 2. Supprimer TOUS les attributs data-* (pour sécurité - éviter l'accès aux données masquées)
        const elementsWithData = clonedContainer.querySelectorAll('[data-table], [data-id], [data-column], [data-value], [data-classified], [data-collection], [data-editor], [data-mode]');
        elementsWithData.forEach(element => {
            element.removeAttribute('data-table');
            element.removeAttribute('data-id');
            element.removeAttribute('data-column');
            element.removeAttribute('data-value');
            element.removeAttribute('data-classified');
            element.removeAttribute('data-collection');
            element.removeAttribute('data-editor');
            element.removeAttribute('data-mode');
        });
        
        // 3. Supprimer les classes d'édition
        const editableElements = clonedContainer.querySelectorAll('.dataToEdit, .multipleDataToEdit, .canEdit');
        editableElements.forEach(element => {
            element.classList.remove('dataToEdit');
            element.classList.remove('multipleDataToEdit');
            element.classList.remove('canEdit');
        });
        
        // 4. Supprimer le conteneur de boutons (s'il est dans le cv-container)
        const buttonContainer = clonedContainer.querySelector('.button-container');
        if (buttonContainer) {
            buttonContainer.remove();
        }
        
        // 5. Nettoyer les attributs style vides
        const elementsWithStyle = clonedContainer.querySelectorAll('[style]');
        elementsWithStyle.forEach(element => {
            if (!element.getAttribute('style') || element.getAttribute('style').trim() === '') {
                element.removeAttribute('style');
            }
        });
        
        // Récupérer le HTML nettoyé
        const cleanHTML = clonedContainer.outerHTML;
        
        // Créer uniquement le contenu réutilisable (styles + cv-container)
        const fullHTML = `<style>
        /* Styles supplémentaires pour la version statique */

        .cv-container {
            max-width: 21cm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        /* Masquer les curseurs d'édition */
        * {
            cursor: default !important;
        }
        
        /* Styles d'impression */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .cv-container {
                box-shadow: none;
                max-width: 100%;
            }
        }
    </style>

${cleanHTML}`;
        
        // Envoyer le HTML au serveur pour sauvegarde
        const response = await fetch(getFunctionUrl('saveHTMLCV.php'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                mediaCode: mediaCode,
                htmlContent: fullHTML
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            console.log('✅ CV HTML généré avec succès:', result.filePath);
            return true;
        } else {
            console.error('❌ Erreur lors de la génération du CV HTML:', result.error);
            return false;
        }
        
    } catch (error) {
        console.error('❌ Erreur lors de la conversion HTML:', error);
        return false;
    }
}

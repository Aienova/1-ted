/**
 * Convertit l'offre d'emploi en HTML statique et la sauvegarde dans le dossier jobs
 * @param {string} jobId - L'ID de l'offre d'emploi
 * @returns {Promise<boolean>} - True si la conversion a réussi
 */
async function convertJobToHTML(jobId) {
    console.log('🔄 Conversion de l\'offre en HTML statique...');
    
    if (!jobId) {
        console.error('❌ Aucun ID d\'offre fourni pour la conversion HTML');
        return false;
    }
    
    try {
        // Récupérer le conteneur de l'offre
        const jobContainer = document.querySelector('.job-offer-container');
        if (!jobContainer) {
            console.error('❌ Conteneur d\'offre introuvable');
            return false;
        }
        
        // Cloner le conteneur pour ne pas modifier l'original
        const clonedContainer = jobContainer.cloneNode(true);
        
        // Nettoyer le clone:
        // 1. Supprimer tous les attributs data-* liés à l'édition
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
        
        // 2. Supprimer les classes d'édition
        const editableElements = clonedContainer.querySelectorAll('.dataToEdit, .multipleDataToEdit, .canEdit');
        editableElements.forEach(element => {
            element.classList.remove('dataToEdit');
            element.classList.remove('multipleDataToEdit');
            element.classList.remove('canEdit');
        });
        
        // 3. Nettoyer les attributs style vides
        const elementsWithStyle = clonedContainer.querySelectorAll('[style]');
        elementsWithStyle.forEach(element => {
            if (!element.getAttribute('style') || element.getAttribute('style').trim() === '') {
                element.removeAttribute('style');
            }
        });
        
        // Récupérer le HTML nettoyé
        const cleanHTML = clonedContainer.outerHTML;
        
        // Créer uniquement le contenu réutilisable (styles + job-offer-container)
        const fullHTML = `<style>
        /* Styles pour la version statique de l'offre */
        .job-offer-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            text-align: left;
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
            
            .job-offer-container {
                max-width: 100%;
            }
        }
    </style>

${cleanHTML}`;
        
        // Envoyer le HTML au serveur pour sauvegarde
        const response = await fetch(getFunctionUrl('saveHTMLJob.php'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                jobId: jobId,
                htmlContent: fullHTML
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            console.log('✅ Offre HTML générée avec succès:', result.filePath);
            return true;
        } else {
            console.error('❌ Erreur lors de la génération de l\'offre HTML:', result.error);
            return false;
        }
        
    } catch (error) {
        console.error('❌ Erreur lors de la conversion HTML:', error);
        return false;
    }
}

// Script pour classifier le CV (masquer réellement les données sensibles)
document.addEventListener('DOMContentLoaded', () => {
    const classifyButton = document.getElementById('classify-cv-button');
    
    if (classifyButton) {
        classifyButton.addEventListener('click', async () => {
            // Objet pour stocker les données authentiques
            const classifiedData = [];
            
            // Récupérer tous les éléments avec data-classified="true"
            const classifiedElements = document.querySelectorAll('[data-classified="true"]');
            
            for (let index = 0; index < classifiedElements.length; index++) {
                const element = classifiedElements[index];
                const dataEntry = {
                    index: index,
                    textContent: element.textContent || '',
                    dataValue: element.getAttribute('data-value') || '',
                    isImage: false,
                    isProfilePhoto: false,
                    isQRCode: false
                };
                
                // Cas spécial : photo de profil (div avec background-image)
                if (element.classList.contains('profile-photo')) {
                    dataEntry.isProfilePhoto = true;
                    dataEntry.backgroundImage = element.style.backgroundImage || '';
                    
                    // Récupérer l'ID du candidat depuis l'attribut data-id de l'élément
                    // Si non disponible, essayer depuis le conteneur cv-container
                    let candidateId = element.getAttribute('data-id');
                    if (!candidateId) {
                        const cvContainer = document.querySelector('.cv-container');
                        if (cvContainer) {
                            candidateId = cvContainer.getAttribute('data-id');
                        }
                    }
                    
                    let avatarUrl = '/data/candidates/media/default/photo.jpg';
                    
                    if (candidateId) {
                        try {
                            // Appeler l'API pour récupérer l'avatar_url
                            const response = await fetch(`/function/getAvatarApi.php?candidateId=${candidateId}`);
                            const data = await response.json();
                            
                            if (data.success && data.avatarUrl) {
                                avatarUrl = data.avatarUrl;
                            }
                        } catch (error) {
                            console.error('Erreur lors de la récupération de l\'avatar:', error);
                        }
                    }
                    
                    dataEntry.avatarUrl = avatarUrl;
                    element.style.backgroundImage = `url("${avatarUrl}")`;
                }
                // Cas spécial : QR code (div footer-qr avec img à l'intérieur)
                else if (element.classList.contains('footer-qr')) {
                    dataEntry.isQRCode = true;
                    // Chercher l'image à l'intérieur de la div footer-qr
                    const qrImage = element.querySelector('img');
                    if (qrImage) {
                        dataEntry.src = qrImage.src || '';
                        // Remplacer par QR code par défaut
                        qrImage.src = getAbsolutePath('media/image/qrcode.png');
                    }
                }
                // Cas général : texte
                else {
                    // Remplacer le contenu texte par "Classifié"
                    element.textContent = 'Classifié';
                    // Remplacer data-value par "Classifié"
                    element.setAttribute('data-value', 'Classifié');
                }
                
                // Ajouter la classe .classified pour le blur
                element.classList.add('classified');
                
                // Stocker les données
                classifiedData.push(dataEntry);
            }
            
            // Sauvegarder dans localStorage
            localStorage.setItem('classifiedText', JSON.stringify(classifiedData));
            
            // Masquer le bouton classifier
            classifyButton.style.display = 'none';
            
            // Afficher le bouton déclassifier
            const declassifyButton = document.getElementById('declassify-cv-button');
            if (declassifyButton) {
                declassifyButton.style.display = 'inline-block';
            }
            
            console.log('🔒 CV classifié - Données sensibles masquées et stockées en localStorage');
            console.log(`📦 ${classifiedData.length} éléments classifiés`);
        });
    }
});

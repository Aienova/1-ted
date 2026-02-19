// Script pour vider les données du CV
document.addEventListener('DOMContentLoaded', () => {
    const resetButton = document.getElementById('reset-cv-button');
    
    if (resetButton) {
        resetButton.addEventListener('click', () => {
            // Demander confirmation avant de vider
            const confirmation = confirm('⚠️ Êtes-vous sûr de vouloir vider toutes les données du CV ? Cette action est irréversible.');
            
            if (!confirmation) {
                return;
            }
            
            // Vider tous les éléments dataToEdit
            const dataElements = document.querySelectorAll('.dataToEdit');
            dataElements.forEach(element => {
                const name = element.getAttribute('data-name');
                const column = element.getAttribute('data-column');
                
                // Pour les photos de profil
                if (name === 'profile-photo') {
                    element.style.backgroundImage = '';
                    element.style.backgroundColor = '#000';
                } 
                // Pour les autres champs texte
                else {
                    element.textContent = '';
                }
            });
            
            // Vider la description
            const descriptionElement = document.querySelector('.description p');
            if (descriptionElement) {
                descriptionElement.textContent = '';
            }
            
            // Vider les compétences
            const skillsList = document.querySelector('.skills ul');
            if (skillsList) {
                skillsList.innerHTML = '<li style="color: #fff; font-style: italic;">Aucune compétence ajoutée</li>';
            }
            
            // Vider les expériences
            const experienceSection = document.querySelector('.experience');
            if (experienceSection) {
                const h2 = experienceSection.querySelector('h2');
                experienceSection.innerHTML = '';
                if (h2) experienceSection.appendChild(h2);
                const emptyMessage = document.createElement('p');
                emptyMessage.style.fontStyle = 'italic';
                emptyMessage.style.color = '#999';
                emptyMessage.textContent = 'Aucune expérience professionnelle ajoutée';
                experienceSection.appendChild(emptyMessage);
            }
            
            // Vider les formations
            const formationSection = document.querySelector('.formation');
            if (formationSection) {
                const h2 = formationSection.querySelector('h2');
                formationSection.innerHTML = '';
                if (h2) formationSection.appendChild(h2);
                const emptyMessage = document.createElement('p');
                emptyMessage.style.fontStyle = 'italic';
                emptyMessage.style.color = '#999';
                emptyMessage.textContent = 'Aucune formation ajoutée';
                formationSection.appendChild(emptyMessage);
            }
            
            // Vider le footer
            const footerText = document.querySelector('.footer-text p');
            if (footerText) {
                footerText.textContent = '';
            }
            
            const footerLogo = document.querySelector('.footer-logo img');
            if (footerLogo) {
                footerLogo.src = '';
                footerLogo.alt = 'Aucun logo';
            }
            
            const footerQR = document.querySelector('.footer-qr img');
            if (footerQR) {
                footerQR.src = '';
                footerQR.alt = 'Aucun QR Code';
            }
            
            alert('✅ Le CV a été vidé avec succès !');
        });
    }
});

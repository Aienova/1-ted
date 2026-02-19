// Formulaire d'édition pour les informations professionnelles
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['job-info'] = {
    
    generateForm: function(currentData) {
        // Récupérer les données actuelles depuis le DOM
        const parentElement = document.querySelector('[data-editor="job-info"]');
        let location = '', phone = '', email = '', vehicule = false, salary = '', contractType = '';
        
        if (parentElement) {
            location = parentElement.querySelector('p:nth-child(1) .placeholder')?.textContent.trim() || '';
            phone = parentElement.querySelector('p:nth-child(2) .placeholder')?.textContent.trim() || '';
            email = parentElement.querySelector('p:nth-child(3) .placeholder')?.textContent.trim() || '';
            vehicule = parentElement.querySelector('p:nth-child(4) .placeholder')?.textContent.trim() === 'Oui';
            contractType = parentElement.querySelector('p:nth-child(5) .placeholder')?.textContent.trim() || '';
            salary = parentElement.querySelector('p:nth-child(6) .placeholder')?.textContent.replace(' €', '').trim() || '';
        }
        
        return `
            <div class="form-group">
                <label for="edit-location">Localisation</label>
                <input 
                    type="text" 
                    id="edit-location" 
                    class="form-input" 
                    value="${location}"
                    placeholder="Ex: Paris, France"
                >
            </div>
            
            <div class="form-group">
                <label for="edit-phone">Téléphone</label>
                <input 
                    type="tel" 
                    id="edit-phone" 
                    class="form-input" 
                    value="${phone}"
                    placeholder="Ex: 06 12 34 56 78"
                >
            </div>
            
            <div class="form-group">
                <label for="edit-email">Email</label>
                <input 
                    type="email" 
                    id="edit-email" 
                    class="form-input" 
                    value="${email}"
                    placeholder="Ex: exemple@email.com"
                >
            </div>
            
            <div class="form-group">
                <label for="edit-vehicule">Véhicule</label>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            id="edit-vehicule" 
                            ${vehicule ? 'checked' : ''}
                        >
                        <span>Je possède un véhicule</span>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="edit-contract-type">Type de contrat</label>
                <select id="edit-contract-type" class="form-input" onchange="window.FormEditors['job-info'].updateSalaryField()">
                    <option value="">Sélectionnez un type</option>
                    <option value="CDI" ${contractType === 'CDI' ? 'selected' : ''}>CDI</option>
                    <option value="CDD" ${contractType === 'CDD' ? 'selected' : ''}>CDD</option>
                    <option value="Freelance" ${contractType === 'Freelance' ? 'selected' : ''}>Freelance</option>
                    <option value="Stage" ${contractType === 'Stage' ? 'selected' : ''}>Stage</option>
                    <option value="Alternance" ${contractType === 'Alternance' ? 'selected' : ''}>Alternance</option>
                    <option value="Intérim" ${contractType === 'Intérim' ? 'selected' : ''}>Intérim</option>
                </select>
            </div>
            
            <div class="form-group" id="salary-group" style="${contractType === 'Stage' ? 'display: none;' : ''}">
                <label for="edit-salary" id="salary-label">${contractType === 'Freelance' ? 'Taux Journalier Moyen (€)' : 'Prétention salariale (€)'}</label>
                <input 
                    type="number" 
                    id="edit-salary" 
                    class="form-input" 
                    value="${salary}"
                    placeholder="${contractType === 'Freelance' ? 'Ex: 500' : 'Ex: 35000'}"
                    min="0"
                    step="${contractType === 'Freelance' ? '50' : '1000'}"
                >
                <small class="form-help" id="salary-help">${contractType === 'Freelance' ? 'Taux journalier moyen' : 'Montant annuel brut'}</small>
            </div>
        `;
    },
    
    init: function(currentData) {
        // Initialiser l'affichage du champ salaire selon le type de contrat
        this.updateSalaryField();
    },
    
    updateSalaryField: function() {
        const contractType = document.getElementById('edit-contract-type')?.value || '';
        const salaryGroup = document.getElementById('salary-group');
        const salaryLabel = document.getElementById('salary-label');
        const salaryInput = document.getElementById('edit-salary');
        const salaryHelp = document.getElementById('salary-help');
        
        if (!salaryGroup || !salaryLabel || !salaryInput || !salaryHelp) return;
        
        // Gérer l'affichage selon le type de contrat
        if (contractType === 'Stage') {
            // Masquer complètement le champ pour les stages
            salaryGroup.style.display = 'none';
        } else {
            // Afficher le champ
            salaryGroup.style.display = '';
            
            if (contractType === 'Freelance') {
                // Configuration pour Freelance
                salaryLabel.textContent = 'Taux Journalier Moyen (€)';
                salaryInput.placeholder = 'Ex: 500';
                salaryInput.step = '50';
                salaryHelp.textContent = 'Taux journalier moyen';
            } else {
                // Configuration par défaut (CDI, CDD, etc.)
                salaryLabel.textContent = 'Prétention salariale (€)';
                salaryInput.placeholder = 'Ex: 35000';
                salaryInput.step = '1000';
                salaryHelp.textContent = 'Montant annuel brut';
            }
        }
    },
    
    getData: function() {
        const vehiculeChecked = document.getElementById('edit-vehicule')?.checked || false;
        
        return {
            'location': document.getElementById('edit-location')?.value || '',
            'phone': document.getElementById('edit-phone')?.value || '',
            'email': document.getElementById('edit-email')?.value || '',
            'vehicule': vehiculeChecked ? 'true' : 'false',
            'salary': document.getElementById('edit-salary')?.value || '',
            'contract-type': document.getElementById('edit-contract-type')?.value || ''
        };
    }
};

// Formulaire d'édition des informations de base
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['job-basic-info'] = {
    generateForm: function(currentData) {
        return `
            <div class="form-group">
                <label for="location-input">Localisation</label>
                <input type="text" id="location-input" class="form-control" value="${currentData.location || ''}" data-column="location" data-table="job">
            </div>
            <div class="form-group">
                <label for="salary-input">Salaire</label>
                <input type="text" id="salary-input" class="form-control" value="${currentData.salary || ''}" data-column="salary" data-table="job">
            </div>
            <div class="form-group">
                <label for="contract-type-input">Type de contrat</label>
                <select id="contract-type-input" class="form-control" data-column="contract_type" data-table="job">
                    <option value="CDI" ${currentData.contract_type === 'CDI' ? 'selected' : ''}>CDI</option>
                    <option value="CDD" ${currentData.contract_type === 'CDD' ? 'selected' : ''}>CDD</option>
                    <option value="Interim" ${currentData.contract_type === 'Interim' ? 'selected' : ''}>Intérim</option>
                    <option value="Stage" ${currentData.contract_type === 'Stage' ? 'selected' : ''}>Stage</option>
                    <option value="Alternance" ${currentData.contract_type === 'Alternance' ? 'selected' : ''}>Alternance</option>
                </select>
            </div>
            <div class="form-group">
                <label for="start-date-input">Date de début</label>
                <input type="date" id="start-date-input" class="form-control" value="${currentData.start_date || ''}" data-column="start_date" data-table="job">
            </div>
            <div class="form-group">
                <label for="length-input">Durée</label>
                <input type="text" id="length-input" class="form-control" value="${currentData.length || ''}" data-column="length" data-table="job" placeholder="Ex: Indéterminée, 6 mois, 1 an">
            </div>
            <div class="form-group">
                <label for="need-vehicle-input">Véhicule nécessaire</label>
                <select id="need-vehicle-input" class="form-control" data-column="need_vehicle" data-table="job">
                    <option value="0" ${currentData.need_vehicle === '0' || currentData.need_vehicle === 'Non' ? 'selected' : ''}>Non</option>
                    <option value="1" ${currentData.need_vehicle === '1' || currentData.need_vehicle === 'Oui' ? 'selected' : ''}>Oui</option>
                </select>
            </div>
        `;
    },
    
    getData: function() {
        return {
            location: document.getElementById('location-input').value,
            salary: document.getElementById('salary-input').value,
            contract_type: document.getElementById('contract-type-input').value,
            start_date: document.getElementById('start-date-input').value,
            length: document.getElementById('length-input').value,
            need_vehicle: document.getElementById('need-vehicle-input').value
        };
    }
};

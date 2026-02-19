// Formulaire d'édition pour les informations personnelles
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['personal-info'] = {
    languages: [],
    languageIds: [],
    personalities: [],
    personalityIds: [],
    hobbies: [],
    hobbieIds: [],
    degrees: [],
    currentDegreeId: '',
    currentBirthdate: '',
    
    generateForm: function(currentData) {
        // Récupérer les données du DOM pour les listes
        const parentElement = document.querySelector('[data-editor="personal-info"]');
        
        // Récupérer la date de naissance actuelle
        if (parentElement) {
            const birthdateEl = parentElement.querySelector('p:nth-child(1) .placeholder');
            if (birthdateEl) {
                this.currentBirthdate = birthdateEl.getAttribute('data-value') || '';
            }
            
            // Récupérer l'ID du diplôme actuel
            const degreeEl = parentElement.querySelector('p:nth-child(2) .placeholder');
            if (degreeEl) {
                this.currentDegreeId = degreeEl.getAttribute('data-value') || '';
            }
        }
        
        return `
            <div class="form-group">
                <label for="edit-birthdate">Date de naissance</label>
                <input 
                    type="date" 
                    id="edit-birthdate" 
                    class="form-input"
                    value="${this.currentBirthdate}"
                    placeholder="JJ/MM/AAAA"
                >
                <small class="form-help">L'âge sera calculé automatiquement</small>
            </div>
            
            <div class="form-group">
                <label for="edit-degree">Niveau d'étude</label>
                <select id="edit-degree" class="form-input">
                    <option value="">Chargement...</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Langues parlées</label>
                <div id="languages-list" class="multi-input-list"></div>
                <div class="add-item-container">
                    <input 
                        type="text" 
                        id="new-language" 
                        class="form-input" 
                        placeholder="Ex: Français"
                    >
                    <button type="button" class="editor-btn editor-btn-add" onclick="window.FormEditors['personal-info'].addLanguage()">
                        ➕ Ajouter
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label>Traits de personnalité</label>
                <div id="personalities-list" class="multi-input-list"></div>
                <div class="add-item-container">
                    <input 
                        type="text" 
                        id="new-personality" 
                        class="form-input" 
                        placeholder="Ex: Dynamique"
                    >
                    <button type="button" class="editor-btn editor-btn-add" onclick="window.FormEditors['personal-info'].addPersonality()">
                        ➕ Ajouter
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label>Hobbies</label>
                <div id="hobbies-list" class="multi-input-list"></div>
                <div class="add-item-container">
                    <input 
                        type="text" 
                        id="new-hobbie" 
                        class="form-input" 
                        placeholder="Ex: Football"
                    >
                    <button type="button" class="editor-btn editor-btn-add" onclick="window.FormEditors['personal-info'].addHobbie()">
                        ➕ Ajouter
                    </button>
                </div>
            </div>
        `;
    },
    
    init: function(currentData) {
        // Initialiser les listes vides
        this.languages = [];
        this.languageIds = [];
        this.personalities = [];
        this.personalityIds = [];
        this.hobbies = [];
        this.hobbieIds = [];
        
        // Récupérer les données actuelles depuis le DOM
        const parentElement = document.querySelector('[data-editor="personal-info"]');
        if (parentElement) {
            // Récupérer les langues et leurs IDs
            const langText = parentElement.querySelector('p:nth-child(3) .placeholder')?.textContent.trim();
            const langCollection = parentElement.querySelector('p:nth-child(3) .placeholder')?.getAttribute('data-collection') || '[]';
            if (langText && langText !== '') {
                this.languages = langText.split(',').map(l => l.trim()).filter(l => l);
                try {
                    this.languageIds = JSON.parse(langCollection);
                } catch(e) {
                    this.languageIds = [];
                }
            }
            
            // Récupérer les personnalités et leurs IDs
            const persText = parentElement.querySelector('p:nth-child(4) .placeholder')?.textContent.trim();
            const persCollection = parentElement.querySelector('p:nth-child(4) .placeholder')?.getAttribute('data-collection') || '[]';
            if (persText && persText !== '') {
                this.personalities = persText.split(',').map(p => p.trim()).filter(p => p);
                try {
                    this.personalityIds = JSON.parse(persCollection);
                } catch(e) {
                    this.personalityIds = [];
                }
            }
            
            // Récupérer les hobbies et leurs IDs
            const hobbiesText = parentElement.querySelector('p:nth-child(5) .placeholder')?.textContent.trim();
            const hobbiesCollection = parentElement.querySelector('p:nth-child(5) .placeholder')?.getAttribute('data-collection') || '[]';
            if (hobbiesText && hobbiesText !== '') {
                this.hobbies = hobbiesText.split(',').map(h => h.trim()).filter(h => h);
                try {
                    this.hobbieIds = JSON.parse(hobbiesCollection);
                } catch(e) {
                    this.hobbieIds = [];
                }
            }
        }
        
        // Charger les diplômes depuis la BDD
        this.loadDegrees();
        
        // Afficher les listes
        this.renderLanguages();
        this.renderPersonalities();
        this.renderHobbies();
    },
    
    loadDegrees: function() {
        fetch(getFunctionUrl('getData.php?table=degree'))
            .then(response => response.json())
            .then(result => {
                if (result.success && result.data) {
                    this.degrees = result.data;
                    this.renderDegrees();
                } else {
                    console.error('Erreur de chargement des diplômes');
                    this.renderDegrees(); // Afficher quand même un select vide
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                this.renderDegrees();
            });
    },
    
    renderDegrees: function() {
        const select = document.getElementById('edit-degree');
        if (!select) return;
        
        if (this.degrees.length === 0) {
            select.innerHTML = '<option value="">Aucun diplôme disponible</option>';
            return;
        }
        
        select.innerHTML = '<option value="">Sélectionnez un niveau</option>' + 
            this.degrees.map(degree => 
                `<option value="${degree.id}" ${degree.id == this.currentDegreeId ? 'selected' : ''}>${degree.title}</option>`
            ).join('');
    },
    
    addLanguage: function() {
        const input = document.getElementById('new-language');
        const value = input.value.trim();
        if (value && !this.languages.includes(value)) {
            this.languages.push(value);
            this.languageIds.push('newId'); // Nouvelle donnée sans ID
            this.renderLanguages();
            input.value = '';
        }
    },
    
    removeLanguage: function(index) {
        this.languages.splice(index, 1);
        this.languageIds.splice(index, 1);
        this.renderLanguages();
    },
    
    renderLanguages: function() {
        const list = document.getElementById('languages-list');
        if (!list) return;
        
        list.innerHTML = this.languages.map((lang, index) => `
            <div class="multi-input-item">
                <span>${lang}</span>
                <button type="button" class="remove-item-btn" onclick="window.FormEditors['personal-info'].removeLanguage(${index})">❌</button>
            </div>
        `).join('');
    },
    
    addPersonality: function() {
        const input = document.getElementById('new-personality');
        const value = input.value.trim();
        if (value && !this.personalities.includes(value)) {
            this.personalities.push(value);
            this.personalityIds.push('newId'); // Nouvelle donnée sans ID
            this.renderPersonalities();
            input.value = '';
        }
    },
    
    removePersonality: function(index) {
        this.personalities.splice(index, 1);
        this.personalityIds.splice(index, 1);
        this.renderPersonalities();
    },
    
    renderPersonalities: function() {
        const list = document.getElementById('personalities-list');
        if (!list) return;
        
        list.innerHTML = this.personalities.map((pers, index) => `
            <div class="multi-input-item">
                <span>${pers}</span>
                <button type="button" class="remove-item-btn" onclick="window.FormEditors['personal-info'].removePersonality(${index})">❌</button>
            </div>
        `).join('');
    },
    
    addHobbie: function() {
        const input = document.getElementById('new-hobbie');
        const value = input.value.trim();
        if (value && !this.hobbies.includes(value)) {
            this.hobbies.push(value);
            this.hobbieIds.push('newId'); // Nouvelle donnée sans ID
            this.renderHobbies();
            input.value = '';
        }
    },
    
    removeHobbie: function(index) {
        this.hobbies.splice(index, 1);
        this.hobbieIds.splice(index, 1);
        this.renderHobbies();
    },
    
    renderHobbies: function() {
        const list = document.getElementById('hobbies-list');
        if (!list) return;
        
        list.innerHTML = this.hobbies.map((hobbie, index) => `
            <div class="multi-input-item">
                <span>${hobbie}</span>
                <button type="button" class="remove-item-btn" onclick="window.FormEditors['personal-info'].removeHobbie(${index})">❌</button>
            </div>
        `).join('');
    },
    
    getData: function() {
        const degreeSelect = document.getElementById('edit-degree');
        const selectedDegreeId = degreeSelect?.value || '';
        const selectedDegreeText = degreeSelect?.options[degreeSelect.selectedIndex]?.text || '';
        
        return {
            'birthdate': document.getElementById('edit-birthdate')?.value || '',
            'degree_id': selectedDegreeId,
            'degree_text': selectedDegreeText,
            'languages': this.languages.join(', '),
            'languageIds': this.languageIds,
            'personalities': this.personalities.join(', '),
            'personalityIds': this.personalityIds,
            'hobbies': this.hobbies.join(', '),
            'hobbieIds': this.hobbieIds
        };
    }
};

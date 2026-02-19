// Formulaire d'édition pour les expériences professionnelles
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['experience'] = {
    experiences: [],
    editingIndex: -1, // -1 signifie qu'on n'est pas en mode édition
    currentMissions: [], // Missions en cours d'ajout/édition
    
    generateForm: function(currentData) {
        return `
            <div class="form-group">
                <label>Expériences professionnelles</label>
                <div id="experiences-list" class="experiences-list-container"></div>
            </div>
            
            <div class="add-experience-form">
                <h3>Ajouter une expérience</h3>
                <div class="form-row">
                    <div class="form-group flex-1">
                        <label for="new-exp-position">Poste occupé</label>
                        <input 
                            type="text" 
                            id="new-exp-position" 
                            class="form-input" 
                            placeholder="Ex: Développeur Full Stack"
                        >
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group flex-1">
                        <label for="new-exp-company">Entreprise</label>
                        <input 
                            type="text" 
                            id="new-exp-company" 
                            class="form-input" 
                            placeholder="Ex: Microsoft"
                        >
                    </div>
                    <div class="form-group flex-1">
                        <label for="new-exp-location">Localisation</label>
                        <input 
                            type="text" 
                            id="new-exp-location" 
                            class="form-input" 
                            placeholder="Ex: Paris, France"
                        >
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="new-exp-start">Date de début</label>
                        <input 
                            type="date" 
                            id="new-exp-start" 
                            class="form-input"
                        >
                    </div>
                    <div class="form-group">
                        <label for="new-exp-end">Date de fin</label>
                        <input 
                            type="date" 
                            id="new-exp-end" 
                            class="form-input"
                        >
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label" style="margin-top: 25px;">
                            <input type="checkbox" id="new-exp-current" onchange="window.FormEditors['experience'].toggleCurrent()">
                            <span>En cours</span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Missions</label>
                    <div id="missions-list" class="multi-input-list"></div>
                    <div class="multi-input-add">
                        <input 
                            type="text" 
                            id="new-mission-input" 
                            class="form-input" 
                            placeholder="Ex: Développement d'applications web"
                        >
                        <button type="button" class="editor-btn editor-btn-add" onclick="window.FormEditors['experience'].addMission()">
                            ➕ Ajouter
                        </button>
                    </div>
                </div>
                <button type="button" class="editor-btn editor-btn-add" id="exp-submit-btn" onclick="window.FormEditors['experience'].addExperience()">
                    ➕ Ajouter l'expérience
                </button>
                <button type="button" class="editor-btn editor-btn-cancel" id="exp-cancel-btn" style="display: none;" onclick="window.FormEditors['experience'].cancelEdit()">
                    ❌ Annuler
                </button>
            </div>
        `;
    },
    
    init: function(currentData) {
        this.experiences = [];
        this.editingIndex = -1;
        this.currentMissions = [];
        
        // Récupérer les expériences actuelles depuis le DOM
        const parentElement = document.querySelector('[data-editor="experience"]');
        if (parentElement) {
            const expItems = parentElement.querySelectorAll('.timeline-item');
            expItems.forEach(item => {
                const periodEl = item.querySelector('.period');
                
                // Récupérer l'ID depuis l'élément ou depuis data-column
                const positionEl = periodEl?.querySelector('[data-column="position"]');
                const id = item.getAttribute('data-id') || positionEl?.getAttribute('data-id') || null;
                
                // Récupérer les données depuis les éléments avec data-column
                const position = positionEl?.textContent.trim() || '';
                const company = periodEl?.querySelector('[data-column="company"]')?.textContent.trim() || '';
                const location = periodEl?.querySelector('[data-column="location"]')?.textContent.trim() || '';
                
                // Lire les dates depuis les attributs data-* (prioritaire)
                let startDate = item.getAttribute('data-start-date') || '';
                let endDate = item.getAttribute('data-end-date') || '';
                let isCurrent = item.getAttribute('data-current') === 'true';
                
                // Fallback: si pas d'attributs data-*, parser depuis le texte visuel
                if (!startDate) {
                    const dateText = periodEl?.querySelector('.date')?.textContent.trim() || '';
                    const dateMatch = dateText.match(/(\d{4})\s*-\s*(.+?)\s*:/);
                    if (dateMatch) {
                        const startYear = dateMatch[1];
                        const endYear = dateMatch[2] === 'Présent' ? 'current' : dateMatch[2];
                        startDate = `${startYear}-01-01`;
                        endDate = endYear === 'current' ? '' : `${endYear}-12-31`;
                        isCurrent = endYear === 'current';
                    }
                }
                
                // Récupérer les missions avec leurs IDs
                const missionsEl = item.querySelector('.missions.multipleDataToEdit');
                let missions = [];
                
                if (missionsEl) {
                    const missionsText = missionsEl.textContent.trim();
                    const missionsCollection = missionsEl.getAttribute('data-collection');
                    
                    if (missionsText && missionsCollection) {
                        try {
                            // Parser les IDs
                            const missionIds = JSON.parse(missionsCollection);
                            // Parser les titres
                            const missionTitles = missionsText.split(',').map(m => m.trim());
                            
                            // Créer des objets mission avec ID et titre
                            missions = missionIds.map((id, index) => ({
                                id: id,
                                title: missionTitles[index] || ''
                            }));
                        } catch (e) {
                            console.error('Erreur de parsing missions:', e);
                            // Fallback : missions simples sans ID
                            missions = missionsText ? missionsText.split(',').map(m => ({ id: 'newId', title: m.trim() })) : [];
                        }
                    }
                }
                
                if (position && startDate) {
                    this.experiences.push({
                        id: id,
                        position: position,
                        company: company,
                        location: location,
                        startDate: startDate,
                        endDate: endDate,
                        current: isCurrent,
                        missions: missions
                    });
                }
            });
        }
        
        this.renderExperiences();
        this.renderMissions();
    },
    
    toggleCurrent: function() {
        const currentCheckbox = document.getElementById('new-exp-current');
        const endDateInput = document.getElementById('new-exp-end');
        
        if (currentCheckbox?.checked) {
            endDateInput.disabled = true;
            endDateInput.value = '';
        } else {
            endDateInput.disabled = false;
        }
    },
    
    addExperience: function() {
        const position = document.getElementById('new-exp-position')?.value.trim();
        const company = document.getElementById('new-exp-company')?.value.trim();
        const location = document.getElementById('new-exp-location')?.value.trim();
        const startDate = document.getElementById('new-exp-start')?.value;
        const endDate = document.getElementById('new-exp-end')?.value;
        const current = document.getElementById('new-exp-current')?.checked || false;
        
        if (!position || !company || !startDate) {
            alert('Veuillez remplir les champs obligatoires (Poste, Entreprise, Date de début)');
            return;
        }
        
        // Les missions sont déjà des objets {id, title}
        const missions = [...this.currentMissions];
        
        if (this.editingIndex >= 0) {
            // Mode édition : mettre à jour l'expérience existante en conservant l'ID
            const existingId = this.experiences[this.editingIndex].id;
            this.experiences[this.editingIndex] = {
                id: existingId,
                position: position,
                company: company,
                location: location || '',
                startDate: startDate,
                endDate: current ? '' : endDate,
                current: current,
                missions: missions
            };
            this.editingIndex = -1;
        } else {
            // Mode ajout : ajouter une nouvelle expérience sans ID (sera ajouté après insertion)
            this.experiences.unshift({
                id: null,
                position: position,
                company: company,
                location: location || '',
                startDate: startDate,
                endDate: current ? '' : endDate,
                current: current,
                missions: missions
            });
        }
        
        // Réinitialiser le formulaire
        this.resetForm();
        this.renderExperiences();
    },
    
    editExperience: function(index) {
        const exp = this.experiences[index];
        if (!exp) return;
        
        // Remplir le formulaire avec les données de l'expérience
        document.getElementById('new-exp-position').value = exp.position;
        document.getElementById('new-exp-company').value = exp.company;
        document.getElementById('new-exp-location').value = exp.location;
        document.getElementById('new-exp-start').value = exp.startDate;
        document.getElementById('new-exp-end').value = exp.endDate;
        document.getElementById('new-exp-current').checked = exp.current;
        
        // Remplir la liste des missions
        this.currentMissions = [...exp.missions];
        this.renderMissions();
        
        // Désactiver le champ date de fin si en cours
        if (exp.current) {
            document.getElementById('new-exp-end').disabled = true;
        }
        
        // Passer en mode édition
        this.editingIndex = index;
        
        // Changer le titre
        const formTitle = document.querySelector('.add-experience-form h3');
        if (formTitle) {
            formTitle.textContent = 'Modifier une expérience';
        }
        
        // Changer le bouton
        const submitBtn = document.getElementById('exp-submit-btn');
        const cancelBtn = document.getElementById('exp-cancel-btn');
        if (submitBtn) {
            submitBtn.innerHTML = '✏️ Mettre à jour';
            submitBtn.className = 'editor-btn editor-btn-upload';
        }
        if (cancelBtn) {
            cancelBtn.style.display = 'inline-block';
        }
        
        // Scroll vers le formulaire
        document.querySelector('.add-experience-form')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    },
    
    cancelEdit: function() {
        this.editingIndex = -1;
        this.resetForm();
    },
    
    resetForm: function() {
        document.getElementById('new-exp-position').value = '';
        document.getElementById('new-exp-company').value = '';
        document.getElementById('new-exp-location').value = '';
        document.getElementById('new-exp-start').value = '';
        document.getElementById('new-exp-end').value = '';
        document.getElementById('new-exp-end').disabled = false;
        document.getElementById('new-exp-current').checked = false;
        
        // Vider la liste des missions
        this.currentMissions = [];
        this.renderMissions();
        
        // Remettre le titre en mode ajout
        const formTitle = document.querySelector('.add-experience-form h3');
        if (formTitle) {
            formTitle.textContent = 'Ajouter une expérience';
        }
        
        // Remettre le bouton en mode ajout
        const submitBtn = document.getElementById('exp-submit-btn');
        const cancelBtn = document.getElementById('exp-cancel-btn');
        if (submitBtn) {
            submitBtn.innerHTML = '➕ Ajouter l\'expérience';
            submitBtn.className = 'editor-btn editor-btn-add';
        }
        if (cancelBtn) {
            cancelBtn.style.display = 'none';
        }
    },
    
    removeExperience: function(index) {
        this.experiences.splice(index, 1);
        this.renderExperiences();
    },
    
    addMission: function() {
        const input = document.getElementById('new-mission-input');
        const mission = input?.value.trim();
        
        if (!mission) {
            alert('Veuillez entrer une mission');
            return;
        }
        
        // Ajouter comme objet avec id: 'newId' pour les nouvelles missions
        this.currentMissions.push({
            id: 'newId',
            title: mission
        });
        input.value = '';
        this.renderMissions();
    },
    
    removeMission: function(index) {
        this.currentMissions.splice(index, 1);
        this.renderMissions();
    },
    
    renderMissions: function() {
        const list = document.getElementById('missions-list');
        if (!list) return;
        
        list.innerHTML = this.currentMissions.map((mission, index) => {
            const title = typeof mission === 'object' ? mission.title : mission;
            return `
                <div class="multi-input-item">
                    <span>${title}</span>
                    <button type="button" class="remove-btn" onclick="window.FormEditors['experience'].removeMission(${index})">×</button>
                </div>
            `;
        }).join('');
    },
    
    renderExperiences: function() {
        const list = document.getElementById('experiences-list');
        if (!list) return;
        
        // Trier les expériences de la plus récente à la moins récente
        const sortedExperiences = [...this.experiences].sort((a, b) => {
            const dateA = new Date(a.startDate);
            const dateB = new Date(b.startDate);
            return dateB - dateA; // Ordre décroissant (plus récent en premier)
        });
        
        list.innerHTML = sortedExperiences.map((exp) => {
            const index = this.experiences.indexOf(exp);
            const startYear = new Date(exp.startDate).getFullYear();
            const endYear = exp.current ? 'Présent' : new Date(exp.endDate).getFullYear();
            
            const missionsText = exp.missions.map(m => typeof m === 'object' ? m.title : m).join(', ');
            
            return `
                <div class="experience-item">
                    <div class="experience-header">
                        <strong>${exp.position}</strong> - ${exp.company}
                        <div class="item-actions">
                            <button type="button" class="edit-item-btn" onclick="window.FormEditors['experience'].editExperience(${index})" title="Modifier">✏️</button>
                            <button type="button" class="remove-item-btn" onclick="window.FormEditors['experience'].removeExperience(${index})" title="Supprimer">🗑️</button>
                        </div>
                    </div>
                    <div class="experience-details">
                        <div>📍 ${exp.location}</div>
                        <div>📅 ${startYear} - ${endYear}</div>
                    </div>
                    ${missionsText ? `
                        <div class="experience-missions">
                            <strong>Missions:</strong> ${missionsText}
                        </div>
                    ` : ''}
                </div>
            `;
        }).join('') || '<p class="empty-message">Aucune expérience ajoutée</p>';
    },
    
    getData: function() {
        return {
            'experiences': this.experiences
        };
    }
};

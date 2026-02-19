// Formulaire d'édition pour les formations
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['formation'] = {
    formations: [],
    editingIndex: -1,
    currentKnowledges: [],
    
    generateForm: function(currentData) {
        return `
            <div class="form-group">
                <label>Formations</label>
                <div id="formations-list" class="formations-list-container"></div>
            </div>
            
            <div class="add-formation-form">
                <h3>Ajouter une formation</h3>
                <div class="form-row">
                    <div class="form-group flex-1">
                        <label for="new-form-title">Diplôme / Titre de la formation</label>
                        <input 
                            type="text" 
                            id="new-form-title" 
                            class="form-input" 
                            placeholder="Ex: Master en Informatique"
                        >
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group flex-1">
                        <label for="new-form-school">École / Université</label>
                        <input 
                            type="text" 
                            id="new-form-school" 
                            class="form-input" 
                            placeholder="Ex: Université Paris-Saclay"
                        >
                    </div>
                    <div class="form-group flex-1">
                        <label for="new-form-location">Localisation</label>
                        <input 
                            type="text" 
                            id="new-form-location" 
                            class="form-input" 
                            placeholder="Ex: Paris, France"
                        >
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="new-form-start">Date de début</label>
                        <input 
                            type="date" 
                            id="new-form-start" 
                            class="form-input"
                        >
                    </div>
                    <div class="form-group">
                        <label for="new-form-end">Date de fin</label>
                        <input 
                            type="date" 
                            id="new-form-end" 
                            class="form-input"
                        >
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label" style="margin-top: 25px;">
                            <input type="checkbox" id="new-form-current" onchange="window.FormEditors['formation'].toggleCurrent()">
                            <span>En cours</span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Connaissances acquises</label>
                    <div id="knowledges-list" class="multi-input-list"></div>
                    <div class="multi-input-add">
                        <input 
                            type="text" 
                            id="new-knowledge-input" 
                            class="form-input" 
                            placeholder="Ex: Programmation orientée objet"
                        >
                        <button type="button" class="editor-btn editor-btn-add" onclick="window.FormEditors['formation'].addKnowledge()">
                            ➕ Ajouter
                        </button>
                    </div>
                </div>
                <button type="button" class="editor-btn editor-btn-add" id="form-submit-btn" onclick="window.FormEditors['formation'].addFormation()">
                    ➕ Ajouter la formation
                </button>
                <button type="button" class="editor-btn editor-btn-cancel" id="form-cancel-btn" style="display: none;" onclick="window.FormEditors['formation'].cancelEdit()">
                    ❌ Annuler
                </button>
            </div>
        `;
    },
    
    init: function(currentData) {
        this.formations = [];
        this.editingIndex = -1;
        this.currentKnowledges = [];
        
        // Récupérer les formations actuelles depuis le DOM
        const parentElement = document.querySelector('[data-editor="formation"]');
        if (parentElement) {
            const formItems = parentElement.querySelectorAll('.timeline-item');
            formItems.forEach(item => {
                const periodEl = item.querySelector('.period');
                
                // Récupérer l'ID depuis l'élément ou depuis data-column
                const titleEl = periodEl?.querySelector('[data-column="title"]');
                const id = item.getAttribute('data-id') || titleEl?.getAttribute('data-id') || null;
                
                // Récupérer les données depuis les éléments avec data-column
                const title = titleEl?.textContent.trim() || '';
                const school = periodEl?.querySelector('[data-column="school"]')?.textContent.trim() || '';
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
                
                // Récupérer les connaissances depuis les multipleDataToEditItem
                const knowledgesEl = item.querySelector('.multipleDataToEdit[data-column="knowledge"]');
                let knowledges = [];
                
                if (knowledgesEl) {
                    // Chercher les spans individuels .multipleDataToEditItem
                    const knowledgeItems = knowledgesEl.querySelectorAll('.multipleDataToEditItem');
                    
                    if (knowledgeItems.length > 0) {
                        // Nouvelle structure avec des spans individuels
                        knowledges = Array.from(knowledgeItems).map(span => ({
                            id: span.getAttribute('data-id') || 'newId',
                            title: span.textContent.trim()
                        }));
                    } else {
                        // Fallback: ancienne structure avec texte simple
                        const knowledgesText = knowledgesEl.textContent.trim();
                        const dataCollection = knowledgesEl.getAttribute('data-collection');
                        
                        if (dataCollection && knowledgesText) {
                            try {
                                // Parser les IDs
                                const ids = JSON.parse(dataCollection);
                                const values = knowledgesText.split(',').map(v => v.trim()).filter(v => v);
                                
                                // Créer les objets {id, title}
                                knowledges = ids.map((id, index) => ({
                                    id: id,
                                    title: values[index] || ''
                                }));
                            } catch (e) {
                                // Fallback: simple array of strings
                                knowledges = knowledgesText ? knowledgesText.split(',').map(k => ({
                                    id: 'newId',
                                    title: k.trim()
                                })) : [];
                            }
                        } else if (knowledgesText) {
                            // Fallback si pas de data-collection
                            knowledges = knowledgesText.split(',').map(k => ({
                                id: 'newId',
                                title: k.trim()
                            }));
                        }
                    }
                }
                
                if (title && startDate) {
                    this.formations.push({
                        id: id,
                        title: title,
                        school: school,
                        location: location,
                        startDate: startDate,
                        endDate: endDate,
                        current: isCurrent,
                        knowledges: knowledges
                    });
                }
            });
        }
        
        this.renderFormations();
        this.renderKnowledges();
    },
    
    toggleCurrent: function() {
        const currentCheckbox = document.getElementById('new-form-current');
        const endDateInput = document.getElementById('new-form-end');
        
        if (currentCheckbox?.checked) {
            endDateInput.disabled = true;
            endDateInput.value = '';
        } else {
            endDateInput.disabled = false;
        }
    },
    
    addFormation: function() {
        const title = document.getElementById('new-form-title')?.value.trim();
        const school = document.getElementById('new-form-school')?.value.trim();
        const location = document.getElementById('new-form-location')?.value.trim();
        const startDate = document.getElementById('new-form-start')?.value;
        const endDate = document.getElementById('new-form-end')?.value;
        const current = document.getElementById('new-form-current')?.checked || false;
        
        if (!title || !school || !startDate) {
            alert('Veuillez remplir les champs obligatoires (Diplôme, École, Date de début)');
            return;
        }
        
        const knowledges = [...this.currentKnowledges];
        
        if (this.editingIndex >= 0) {
            // Mode édition : mettre à jour la formation existante en conservant l'ID
            const existingId = this.formations[this.editingIndex].id;
            this.formations[this.editingIndex] = {
                id: existingId,
                title: title,
                school: school,
                location: location || '',
                startDate: startDate,
                endDate: current ? '' : endDate,
                current: current,
                knowledges: knowledges
            };
            this.editingIndex = -1;
        } else {
            // Mode ajout : ajouter une nouvelle formation sans ID (sera ajouté après insertion)
            this.formations.unshift({
                id: null,
                title: title,
                school: school,
                location: location || '',
                startDate: startDate,
                endDate: current ? '' : endDate,
                current: current,
                knowledges: knowledges
            });
        }
        
        // Réinitialiser le formulaire
        this.resetForm();
        
        this.renderFormations();
    },
    
    editFormation: function(index) {
        const form = this.formations[index];
        if (!form) return;
        
        // Remplir le formulaire
        document.getElementById('new-form-title').value = form.title;
        document.getElementById('new-form-school').value = form.school;
        document.getElementById('new-form-location').value = form.location;
        document.getElementById('new-form-start').value = form.startDate;
        document.getElementById('new-form-end').value = form.endDate;
        document.getElementById('new-form-current').checked = form.current;
        
        // Remplir la liste des connaissances
        this.currentKnowledges = [...form.knowledges];
        this.renderKnowledges();
        
        if (form.current) {
            document.getElementById('new-form-end').disabled = true;
        }
        
        this.editingIndex = index;
        
        const formTitle = document.querySelector('.add-formation-form h3');
        if (formTitle) formTitle.textContent = 'Modifier une formation';
        
        const submitBtn = document.getElementById('form-submit-btn');
        const cancelBtn = document.getElementById('form-cancel-btn');
        if (submitBtn) {
            submitBtn.innerHTML = '✏️ Mettre à jour';
            submitBtn.className = 'editor-btn editor-btn-upload';
        }
        if (cancelBtn) cancelBtn.style.display = 'inline-block';
        
        document.querySelector('.add-formation-form')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    },
    
    cancelEdit: function() {
        this.editingIndex = -1;
        this.resetForm();
    },
    
    resetForm: function() {
        document.getElementById('new-form-title').value = '';
        document.getElementById('new-form-school').value = '';
        document.getElementById('new-form-location').value = '';
        document.getElementById('new-form-start').value = '';
        document.getElementById('new-form-end').value = '';
        document.getElementById('new-form-end').disabled = false;
        document.getElementById('new-form-current').checked = false;
        
        this.currentKnowledges = [];
        this.renderKnowledges();
        
        const formTitle = document.querySelector('.add-formation-form h3');
        if (formTitle) formTitle.textContent = 'Ajouter une formation';
        
        const submitBtn = document.getElementById('form-submit-btn');
        const cancelBtn = document.getElementById('form-cancel-btn');
        if (submitBtn) {
            submitBtn.innerHTML = '➕ Ajouter la formation';
            submitBtn.className = 'editor-btn editor-btn-add';
        }
        if (cancelBtn) cancelBtn.style.display = 'none';
    },
    
    addKnowledge: function() {
        const input = document.getElementById('new-knowledge-input');
        const knowledge = input?.value.trim();
        
        if (!knowledge) {
            alert('Veuillez entrer une connaissance');
            return;
        }
        
        // Créer un objet avec id: 'newId' pour les nouvelles connaissances
        this.currentKnowledges.push({
            id: 'newId',
            title: knowledge
        });
        input.value = '';
        this.renderKnowledges();
    },
    
    removeKnowledge: function(index) {
        this.currentKnowledges.splice(index, 1);
        this.renderKnowledges();
    },
    
    renderKnowledges: function() {
        const list = document.getElementById('knowledges-list');
        if (!list) return;
        
        list.innerHTML = this.currentKnowledges.map((knowledge, index) => `
            <div class="multi-input-item">
                <span>${typeof knowledge === 'object' ? knowledge.title : knowledge}</span>
                <button type="button" class="remove-btn" onclick="window.FormEditors['formation'].removeKnowledge(${index})">×</button>
            </div>
        `).join('');
    },
    
    removeFormation: function(index) {
        this.formations.splice(index, 1);
        this.renderFormations();
    },
    
    renderFormations: function() {
        const list = document.getElementById('formations-list');
        if (!list) return;
        
        // Trier de la plus récente à la moins récente
        const sortedFormations = [...this.formations].sort((a, b) => {
            const dateA = new Date(a.startDate);
            const dateB = new Date(b.startDate);
            return dateB - dateA;
        });
        
        list.innerHTML = sortedFormations.map((form) => {
            const index = this.formations.indexOf(form);
            const startYear = new Date(form.startDate).getFullYear();
            const endYear = form.current ? 'Présent' : new Date(form.endDate).getFullYear();
            
            return `
                <div class="formation-item">
                    <div class="formation-header">
                        <strong>${form.title}</strong> - ${form.school}
                        <div class="item-actions">
                            <button type="button" class="edit-item-btn" onclick="window.FormEditors['formation'].editFormation(${index})" title="Modifier">✏️</button>
                            <button type="button" class="remove-item-btn" onclick="window.FormEditors['formation'].removeFormation(${index})" title="Supprimer">🗑️</button>
                        </div>
                    </div>
                    <div class="formation-details">
                        <div>📍 ${form.location}</div>
                        <div>📅 ${startYear} - ${endYear}</div>
                    </div>
                    ${form.knowledges.length > 0 ? `
                        <div class="formation-knowledges">
                            <strong>Apprentissage:</strong> ${form.knowledges.map(k => typeof k === 'object' ? k.title : k).join(', ')}
                        </div>
                    ` : ''}
                </div>
            `;
        }).join('') || '<p class="empty-message">Aucune formation ajoutée</p>';
    },
    
    getData: function() {
        return {
            'formations': this.formations
        };
    }
};

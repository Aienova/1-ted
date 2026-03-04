// Gestionnaire principal d'édition
class EditorManager {
    constructor() {
        this.currentPopup = null;
        this.currentEditor = null;
        this.init();
    }

    init() {
        // Attacher les événements de clic sur tous les éléments canEdit
        document.addEventListener('click', (e) => {
            const canEditElement = e.target.closest('.canEdit');
            if (canEditElement) {
                e.preventDefault();
                e.stopPropagation();
                this.openEditor(canEditElement);
            }
        });

        // Fermer la popup avec Echap
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.currentPopup) {
                this.closePopup();
            }
        });
    }

    openEditor(element) {
        const editorType = element.getAttribute('data-editor');
        
        if (!editorType) {
            console.error('Aucun data-editor trouvé sur cet élément');
            return;
        }

        // Récupérer les données actuelles des éléments dataToEdit
        const dataElements = element.querySelectorAll('.dataToEdit');
        const currentData = {};
        
        dataElements.forEach(el => {
            const column = el.getAttribute('data-column');
            if (column) {
                // Pour les images de profil
                if (column === 'profile-photo') {
                    const bgImage = window.getComputedStyle(el).backgroundImage;
                    currentData[column] = bgImage !== 'none' ? bgImage.slice(5, -2) : '';
                } else {
                    // Utiliser data-value en priorité, sinon textContent
                    currentData[column] = el.getAttribute('data-value') || el.textContent.trim();
                }
            }
        });

        // Charger le formulaire correspondant
        this.loadForm(editorType, currentData, element);
    }

    loadForm(editorType, currentData, sourceElement) {
        // Vérifier si le formulaire existe
        const formPath = `script/forms/${editorType}.js`;
        
        // Dynamiquement charger et exécuter le formulaire
        if (typeof window.FormEditors === 'undefined') {
            window.FormEditors = {};
        }

        // Vérifier si le formulaire est déjà chargé
        if (window.FormEditors[editorType]) {
            this.showPopup(editorType, currentData, sourceElement);
        } else {
            console.error(`Le formulaire ${editorType} n'est pas chargé`);
        }
    }

    showPopup(editorType, currentData, sourceElement) {
        // Créer la popup
        const popup = document.createElement('div');
        popup.className = 'editor-popup-overlay';
        popup.innerHTML = `
            <div class="editor-popup">
                <div class="editor-popup-header">
                    <h2>Modifier les informations</h2>
                    <button class="editor-close-btn" onclick="editorManager.closePopup()">&times;</button>
                </div>
                <div class="editor-popup-content">
                    <!-- Le contenu sera inséré ici -->
                </div>
                <div class="editor-popup-footer">
                    <button class="editor-btn editor-btn-cancel" onclick="editorManager.closePopup()">Annuler</button>
                    <button class="editor-btn editor-btn-save" onclick="editorManager.saveChanges()">Enregistrer</button>
                </div>
            </div>
        `;

        document.body.appendChild(popup);
        this.currentPopup = popup;
        this.currentEditor = { type: editorType, sourceElement, currentData };

        // Injecter le formulaire spécifique
        const contentDiv = popup.querySelector('.editor-popup-content');
        if (window.FormEditors[editorType]) {
            const formHTML = window.FormEditors[editorType].generateForm(currentData);
            contentDiv.innerHTML = formHTML;
            
            // Initialiser le formulaire si nécessaire
            if (window.FormEditors[editorType].init) {
                window.FormEditors[editorType].init(currentData);
            }
        }

        // Animation d'apparition
        setTimeout(() => {
            popup.classList.add('show');
        }, 10);
    }

    closePopup() {
        if (this.currentPopup) {
            this.currentPopup.classList.remove('show');
            setTimeout(() => {
                this.currentPopup.remove();
                this.currentPopup = null;
                this.currentEditor = null;
            }, 300);
        }
    }

    async saveChanges() {
        if (!this.currentEditor) return;

        const editorType = this.currentEditor.type;
        const sourceElement = this.currentEditor.sourceElement;

        // Récupérer les nouvelles données du formulaire
        let newData = {};
        if (window.FormEditors[editorType] && window.FormEditors[editorType].getData) {
            newData = window.FormEditors[editorType].getData();
        }

        // Gérer l'upload du logo pour job-title
        if (editorType === 'job-title' && newData.logoData) {
            const logoElement = sourceElement.querySelector('[data-column="media_code"]');
            if (logoElement) {
                const companyId = logoElement.getAttribute('data-id');
                let mediaCode = logoElement.getAttribute('data-value');
                
                // Générer un mediaCode si nécessaire
                if (!mediaCode || mediaCode === '') {
                    mediaCode = `${Date.now()}-${Math.random().toString(36).substring(2,15)}`;
                    logoElement.setAttribute('data-value', mediaCode);
                }
                
                try {
                    // Upload du logo via la fonction du formulaire
                    await window.FormEditors['job-title'].uploadLogoToServer(mediaCode);
                    
                    // Mettre à jour l'affichage du logo
                    const cacheVersion = Date.now();
                    const logoUrl = `/data/company/media/${mediaCode}/logo.png?v=${cacheVersion}`;
                    logoElement.style.backgroundImage = `url('${logoUrl}')`;
                    
                    // Mettre à jour le media_code dans la BD si c'est une company existante
                    if (companyId) {
                        await fetch(getFunctionUrl('updateData.php'), {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                table: 'company',
                                id: companyId,
                                data: { media_code: mediaCode }
                            })
                        });
                    }
                } catch (error) {
                    console.error('❌ Erreur upload logo:', error);
                    alert('⚠️ Erreur lors de l\'upload du logo. Les autres modifications ont été sauvegardées.');
                }
            }
        }

        // Mettre à jour les éléments dans le DOM
        this.updateElements(sourceElement, newData);

        // Sauvegarder dans la base de données (à implémenter)
        this.saveToDatabase(newData, editorType);

        // Fermer la popup
        this.closePopup();
    }
    
    async uploadCompanyLogo(companyId, logoFile, mediaCode) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            
            reader.onload = async function(e) {
                try {
                    const imageData = e.target.result;
                    
                    const response = await fetch(getFunctionUrl('uploadImage.php'), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            mediaCode: mediaCode,
                            type: 'logo',
                            imageData: imageData,
                            entityType: 'company'
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        console.log('✅ Logo uploadé avec succès');
                        resolve(result);
                    } else {
                        console.error('❌ Erreur upload logo:', result.error);
                        reject(new Error(result.error || 'Échec upload logo'));
                    }
                } catch (error) {
                    console.error('❌ Erreur lors de l\'upload:', error);
                    reject(error);
                }
            };
            
            reader.onerror = function() {
                reject(new Error('Erreur lors de la lecture du fichier'));
            };
            
            reader.readAsDataURL(logoFile);
        });
    }

    updateElements(sourceElement, newData) {
        const editorType = sourceElement.getAttribute('data-editor');
        
        // Gestion spéciale pour chaque type d'éditeur
        switch(editorType) {
            case 'cv-title':
                this.updateCVTitle(sourceElement, newData);
                break;
            case 'job-title':
                this.updateJobTitle(sourceElement, newData);
                break;
            case 'job-company-info':
                this.updateJobCompanyInfo(sourceElement, newData);
                break;
            case 'personal-info':
                this.updatePersonalInfo(sourceElement, newData);
                break;
            case 'job-info':
                this.updateJobInfo(sourceElement, newData);
                break;
            case 'description':
                this.updateDescription(sourceElement, newData);
                break;
            case 'skills':
                this.updateSkills(sourceElement, newData);
                break;
            case 'experience':
                this.updateExperience(sourceElement, newData);
                break;
            case 'formation':
                this.updateFormation(sourceElement, newData);
                break;
            case 'qrcode':
                this.updateQRCode(sourceElement, newData);
                break;
            default:
                // Mise à jour générique
                this.updateGeneric(sourceElement, newData);
        }
    }
    
    updateCVTitle(sourceElement, newData) {
        const dataElements = sourceElement.querySelectorAll('.dataToEdit');
        dataElements.forEach(el => {
            const column = el.getAttribute('data-column');
            if (column && newData[column] !== undefined) {
                if (column === 'profile-photo') {
                    if (newData[column]) {
                        el.style.backgroundImage = `url('${newData[column]}')`;
                        el.style.backgroundSize = 'cover';
                        el.style.backgroundPosition = 'center';
                    }
                } else {
                    el.textContent = newData[column];
                    el.setAttribute('data-value', newData[column]);
                }
            }
        });
    }
    
    updateJobTitle(sourceElement, newData) {
        // Mettre à jour le titre du poste
        if (newData.title) {
            const titleEl = sourceElement.querySelector('[data-column="title"]');
            if (titleEl) {
                titleEl.setAttribute('data-value', newData.title);
                titleEl.textContent = newData.title;
            }
        }
        
        // Mettre à jour le nom de la société dans le header
        if (newData.name !== undefined) {
            const companyNameDisplay = document.getElementById('company-name-display');
            if (companyNameDisplay) {
                companyNameDisplay.textContent = newData.name;
            }
            
            // Mettre à jour aussi dans la section "À propos de l'entreprise"
            const companySection = document.querySelector('.job-company-info');
            if (companySection) {
                const companyNameEl = companySection.querySelector('[data-column="name"]');
                if (companyNameEl) {
                    companyNameEl.setAttribute('data-value', newData.name);
                    companyNameEl.textContent = newData.name;
                }
            }
        }
    }
    
    updateJobCompanyInfo(sourceElement, newData) {
        // Mettre à jour tous les champs de la section company
        const dataElements = sourceElement.querySelectorAll('.dataToEdit');
        dataElements.forEach(el => {
            const column = el.getAttribute('data-column');
            if (column && newData[column] !== undefined) {
                el.setAttribute('data-value', newData[column]);
                
                // Traitement spécial pour capacity (ajouter "employés")
                if (column === 'capacity') {
                    el.textContent = newData[column] + ' employés';
                } else if (column === 'description') {
                    // Pour la description, conserver les sauts de ligne
                    el.innerHTML = newData[column].replace(/\n/g, '<br>');
                } else {
                    el.textContent = newData[column];
                }
                
                // Si c'est le nom de la société, mettre à jour aussi dans le header
                if (column === 'name') {
                    const companyNameDisplay = document.getElementById('company-name-display');
                    if (companyNameDisplay) {
                        companyNameDisplay.textContent = newData[column];
                    }
                }
            }
        });
    }
    
    // Fonction helper pour formater les IDs en JSON valide
    formatCollectionIds(ids) {
        return '[' + ids.map(id => {
            // Si c'est 'newId' ou une chaîne, ajouter des guillemets
            if (id === 'newId' || typeof id === 'string') {
                return '"' + id + '"';
            }
            // Si c'est un nombre, le laisser tel quel
            return id;
        }).join(',') + ']';
    }
    
    updatePersonalInfo(sourceElement, newData) {
        // Mettre à jour la date de naissance et l'âge
        const birthdateEl = sourceElement.querySelector('p:nth-child(1) .placeholder');
        if (birthdateEl && newData.birthdate) {
            birthdateEl.setAttribute('data-value', newData.birthdate);
            
            // Calculer et afficher l'âge
            const birth = new Date(newData.birthdate);
            const today = new Date();
            const age = today.getFullYear() - birth.getFullYear();
            const monthDiff = today.getMonth() - birth.getMonth();
            const finalAge = (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) 
                ? age - 1 
                : age;
            birthdateEl.textContent = finalAge + ' ans';
        }
        
        // Mettre à jour le niveau d'étude (avec ID et texte)
        const degreeEl = sourceElement.querySelector('p:nth-child(2) .placeholder');
        if (degreeEl && 'degree_id' in newData) {
            degreeEl.textContent = newData.degree_text || '';
            degreeEl.setAttribute('data-value', newData.degree_id);
        }
        
        // Mettre à jour les langues (avec collection d'IDs)
        const langEl = sourceElement.querySelector('p:nth-child(3) .placeholder');
        if (langEl && newData.languages) {
            langEl.textContent = newData.languages;
            langEl.setAttribute('data-value', newData.languages);
            if (newData.languageIds) {
                langEl.setAttribute('data-collection', this.formatCollectionIds(newData.languageIds));
            }
        }
        
        // Mettre à jour les personnalités (avec collection d'IDs)
        const persEl = sourceElement.querySelector('p:nth-child(4) .placeholder');
        if (persEl && newData.personalities) {
            persEl.textContent = newData.personalities;
            persEl.setAttribute('data-value', newData.personalities);
            if (newData.personalityIds) {
                persEl.setAttribute('data-collection', this.formatCollectionIds(newData.personalityIds));
            }
        }
        
        // Mettre à jour les hobbies (avec collection d'IDs)
        const hobbiesEl = sourceElement.querySelector('p:nth-child(5) .placeholder');
        if (hobbiesEl && newData.hobbies) {
            hobbiesEl.textContent = newData.hobbies;
            hobbiesEl.setAttribute('data-value', newData.hobbies);
            if (newData.hobbieIds) {
                hobbiesEl.setAttribute('data-collection', this.formatCollectionIds(newData.hobbieIds));
            }
        }
    }
    
    updateJobInfo(sourceElement, newData) {
        if (newData.location) {
            const el = sourceElement.querySelector('p:nth-child(1) .placeholder');
            if (el) {
                el.textContent = newData.location;
                el.setAttribute('data-value', newData.location);
            }
        }
        if (newData.phone) {
            const el = sourceElement.querySelector('p:nth-child(2) .placeholder');
            if (el) {
                el.textContent = newData.phone;
                el.setAttribute('data-value', newData.phone);
            }
        }
        if (newData.email) {
            const el = sourceElement.querySelector('p:nth-child(3) .placeholder');
            if (el) {
                el.textContent = newData.email;
                el.setAttribute('data-value', newData.email);
            }
        }
        if (newData.vehicule !== undefined) {
            const el = sourceElement.querySelector('p:nth-child(4) .placeholder');
            if (el) {
                // Afficher 'Oui' ou 'Non' dans le texte
                el.textContent = newData.vehicule === 'true' ? 'Oui' : 'Non';
                // Garder 'true' ou 'false' dans data-value
                el.setAttribute('data-value', newData.vehicule);
            }
        }
        if (newData['contract-type']) {
            const el = sourceElement.querySelector('p:nth-child(5) .placeholder');
            if (el) {
                el.textContent = newData['contract-type'];
                el.setAttribute('data-value', newData['contract-type']);
            }
        }
        if (newData.salary) {
            const el = sourceElement.querySelector('p:nth-child(6) .placeholder');
            if (el) {
                el.textContent = newData.salary + ' €';
                el.setAttribute('data-value', newData.salary);
            }
        }
    }
    
    updateDescription(sourceElement, newData) {
        const descEl = sourceElement.querySelector('p');
        if (descEl && newData.description) {
            descEl.textContent = newData.description;
            descEl.setAttribute('data-value', newData.description);
        }
    }
    
    updateSkills(sourceElement, newData) {
        if (!newData.skills || !Array.isArray(newData.skills)) return;
        
        const ul = sourceElement.querySelector('ul');
        if (!ul) return;
        
        // Trier: favoris en premier
        const sortedSkills = [...newData.skills].sort((a, b) => {
            if (a.favorite && !b.favorite) return -1;
            if (!a.favorite && b.favorite) return 1;
            return 0;
        });
        
        // Reconstruire les <li> en préservant la structure avec data-table, data-column, data-value et data-id
        ul.innerHTML = sortedSkills.map(skill => {
            const percentage = Math.min((skill.years / 15) * 100, 100);
            const yearsText = skill.years > 1 ? 'ans' : 'an';
            return `
                <li>
                    ${skill.favorite ? '⭐ ' : ''}<span class="dataToEdit" data-table="skill" data-column="title" data-value="${skill.title}" data-id="${skill.id || ''}" data-classified="false">${skill.title}</span> <span class="dataToEdit" data-table="skill" data-column="year" data-value="${skill.years}" data-id="${skill.id || ''}" data-classified="false" style="font-size: 10px; color: #fff;">${skill.years}</span><span style="font-size: 10px; color: #fff;"> ${yearsText} d'exp</span>
                    <br><div style="width: 100%; height: 10px; background-color: #fff; border: 2px solid #000; margin-top: 5px; position: relative;">
                    <div style="width: ${percentage}%; height: 100%; background-color: #ff3333;"></div>
                    </div>
                </li>
            `;
        }).join('');
    }
    
    updateExperience(sourceElement, newData) {
        if (!newData.experiences || !Array.isArray(newData.experiences)) return;
        
        // Trier les expériences de la plus récente à la moins récente
        const sortedExperiences = [...newData.experiences].sort((a, b) => {
            const dateA = new Date(a.startDate);
            const dateB = new Date(b.startDate);
            return dateB - dateA; // Ordre décroissant (plus récent en premier)
        });
        
        // Recréer le HTML des expériences
        const h2 = sourceElement.querySelector('h2');
        sourceElement.innerHTML = '';
        sourceElement.appendChild(h2);
        
        sortedExperiences.forEach(exp => {
            const startYear = new Date(exp.startDate).getFullYear();
            const endYear = exp.current ? 'Présent' : new Date(exp.endDate).getFullYear();
            
            // Définir l'ID (newId pour les nouvelles expériences)
            const expId = exp.id || 'newId';
            
            const div = document.createElement('div');
            div.className = 'timeline-item dataToEdit';
            div.setAttribute('data-table', 'experience');
            div.setAttribute('data-start-date', exp.startDate);
            div.setAttribute('data-end-date', exp.endDate || '');
            div.setAttribute('data-current', exp.current ? 'true' : 'false');
            div.setAttribute('data-id', expId);
            
            // Générer le HTML des missions avec des spans individuels
            let missionsHTML = '';
            if (Array.isArray(exp.missions) && exp.missions.length > 0) {
                missionsHTML = exp.missions.map((m, index) => {
                    const missionId = typeof m === 'object' ? (m.id || 'newId') : 'newId';
                    const missionTitle = typeof m === 'object' ? m.title : m;
                    const comma = index < exp.missions.length - 1 ? ', ' : '';
                    return `<span class="multipleDataToEditItem" data-id="${missionId}">${missionTitle}</span>${comma}`;
                }).join('');
            }
            
            div.innerHTML = `
                <p class="period">
                    <span class="date">${startYear} - ${endYear} :</span> 
                    <span class="status dataToEdit" data-table="experience" data-column="position" data-value="${exp.position}" data-id="${expId}" data-classified="false">${exp.position}</span> - <strong><span class="dataToEdit" data-table="experience" data-column="company" data-value="${exp.company}" data-id="${expId}" data-classified="true">${exp.company}</span>, <span class="dataToEdit" data-table="experience" data-column="location" data-value="${exp.location}" data-id="${expId}" data-classified="true">${exp.location}</span></strong>
                </p>
                <p><strong>Missions :</strong> 
                    <span class="missions multipleDataToEdit" data-table="experience" data-column="missions" data-collection='${this.formatMissionIds(exp.missions)}' data-id="${expId}" data-classified="false">${missionsHTML}</span>
                </p>
            `;
            sourceElement.appendChild(div);
        });
    }
    
    formatMissionIds(missions) {
        if (!Array.isArray(missions)) return '[]';
        
        // Si les missions sont des objets avec id, extraire les IDs
        const ids = missions.map(m => {
            if (typeof m === 'object' && m.id) {
                return m.id;
            }
            // Pour les nouvelles missions sans ID, retourner "newId"
            return 'newId';
        });
        
        return '[' + ids.map(id => typeof id === 'string' ? '"' + id + '"' : id).join(',') + ']';
    }
    
    formatKnowledgeIds(knowledges) {
        if (!Array.isArray(knowledges)) return '[]';
        
        // Si les knowledges sont des objets avec id, extraire les IDs
        const ids = knowledges.map(k => {
            if (typeof k === 'object' && k.id) {
                return k.id;
            }
            // Pour les nouvelles knowledges sans ID, retourner "newId"
            return 'newId';
        });
        
        return '[' + ids.map(id => typeof id === 'string' ? '"' + id + '"' : id).join(',') + ']';
    }
    
    updateFormation(sourceElement, newData) {
        if (!newData.formations || !Array.isArray(newData.formations)) return;
        
        // Trier les formations de la plus récente à la moins récente
        const sortedFormations = [...newData.formations].sort((a, b) => {
            const dateA = new Date(a.startDate);
            const dateB = new Date(b.startDate);
            return dateB - dateA;
        });
        
        // Recréer le HTML des formations
        const h2 = sourceElement.querySelector('h2');
        sourceElement.innerHTML = '';
        sourceElement.appendChild(h2);
        
        sortedFormations.forEach(form => {
            const startYear = new Date(form.startDate).getFullYear();
            const endYear = form.current ? 'Présent' : new Date(form.endDate).getFullYear();
            
            // Utiliser 'newId' si l'ID est null (nouvelle formation)
            const formId = form.id || 'newId';
            
            const div = document.createElement('div');
            div.className = 'timeline-item dataToEdit';
            div.setAttribute('data-table', 'formation');
            div.setAttribute('data-id', formId);
            div.setAttribute('data-start-date', form.startDate);
            div.setAttribute('data-end-date', form.endDate || '');
            div.setAttribute('data-current', form.current ? 'true' : 'false');
            
            // Créer le HTML de base
            const periodHTML = `
                <p class="period">
                    <span class="date">${startYear} - ${endYear} :</span> 
                    <span class="status dataToEdit" data-table="formation" data-column="title" data-value="${form.title}" data-id="${formId}" data-classified="false">${form.title}</span> - <strong><span class="dataToEdit" data-table="formation" data-column="school" data-value="${form.school}" data-id="${formId}" data-classified="true">${form.school}</span>, <span class="dataToEdit" data-table="formation" data-column="location" data-value="${form.location}" data-id="${formId}" data-classified="true">${form.location}</span></strong>
                </p>
            `;
            
            div.innerHTML = periodHTML;
            
            // Créer le conteneur pour les knowledges avec multipleDataToEditItem
            const knowledgesP = document.createElement('p');
            knowledgesP.innerHTML = '<strong>Apprentissage :</strong> ';
            
            const knowledgesSpan = document.createElement('span');
            knowledgesSpan.className = 'learning multipleDataToEdit';
            knowledgesSpan.setAttribute('data-table', 'formation');
            knowledgesSpan.setAttribute('data-column', 'knowledge');
            knowledgesSpan.setAttribute('data-collection', this.formatKnowledgeIds(form.knowledges));
            knowledgesSpan.setAttribute('data-id', formId);
            knowledgesSpan.setAttribute('data-classified', 'false');
            
            // Ajouter data-value avec les titres en JSON pour add.js
            if (Array.isArray(form.knowledges) && form.knowledges.length > 0) {
                const knowledgeTitles = form.knowledges.map(k => typeof k === 'object' ? k.title : k);
                knowledgesSpan.setAttribute('data-value', JSON.stringify(knowledgeTitles));
                
                // Créer les multipleDataToEditItem pour chaque knowledge
                form.knowledges.forEach((knowledge, index) => {
                    const knowledgeItem = document.createElement('span');
                    knowledgeItem.className = 'multipleDataToEditItem';
                    knowledgeItem.setAttribute('data-id', typeof knowledge === 'object' && knowledge.id ? knowledge.id : 'newId');
                    knowledgeItem.textContent = typeof knowledge === 'object' ? knowledge.title : knowledge;
                    
                    knowledgesSpan.appendChild(knowledgeItem);
                    
                    // Ajouter une virgule entre les items (sauf le dernier)
                    if (index < form.knowledges.length - 1) {
                        knowledgesSpan.appendChild(document.createTextNode(', '));
                    }
                });
            } else {
                knowledgesSpan.setAttribute('data-value', '[]');
            }
            
            knowledgesP.appendChild(knowledgesSpan);
            div.appendChild(knowledgesP);
            
            sourceElement.appendChild(div);
        });
    }
    
    updateQRCode(sourceElement, newData) {
        // Mettre à jour le texte du footer
        if (newData['footer-text']) {
            const textEl = sourceElement.querySelector('.footer-text p');
            if (textEl) {
                textEl.textContent = newData['footer-text'];
                textEl.setAttribute('data-value', newData['footer-text']);
            }
        }
        
        // Mettre à jour le logo
        if (newData['logo-url']) {
            const logoImg = sourceElement.querySelector('.footer-logo img');
            if (logoImg) logoImg.src = newData['logo-url'];
        }
        
        // Mettre à jour le QR code
        if (newData['qrcode-url']) {
            const qrImg = sourceElement.querySelector('.footer-qr img');
            if (qrImg) qrImg.src = newData['qrcode-url'];
        }
    }
    
    updateGeneric(sourceElement, newData) {
        const dataElements = sourceElement.querySelectorAll('.dataToEdit');
        dataElements.forEach(el => {
            const column = el.getAttribute('data-column');
            if (column && newData[column] !== undefined) {
                el.textContent = newData[column];
                el.setAttribute('data-value', newData[column]);
            }
        });
    }

    saveToDatabase(data, editorType) {
        // TODO: Implémenter la sauvegarde en base de données
        console.log('Sauvegarde des données:', data, 'Type:', editorType);
        
        // Exemple d'appel AJAX
        /*
        fetch('function/updateData.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                type: editorType,
                data: data
            })
        })
        .then(response => response.json())
        .then(result => {
            console.log('Données sauvegardées:', result);
        })
        .catch(error => {
            console.error('Erreur lors de la sauvegarde:', error);
        });
        */
    }
}

// Initialiser le gestionnaire d'édition au chargement de la page
let editorManager;
document.addEventListener('DOMContentLoaded', () => {
    editorManager = new EditorManager();
});

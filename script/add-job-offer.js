// Script pour ajouter une nouvelle offre d'emploi

// Variables globales pour stocker les infos de l'entreprise connectée
let isCompanyLoggedIn = false;
let currentCompanyId = null;
let currentContactId = null;
let currentCompanyName = null;

document.addEventListener('DOMContentLoaded', async () => {
    // Vérifier si nous sommes sur une page qui nécessite les infos de l'entreprise
    const addButton = document.getElementById('add-job-button');
    
    // Charger les informations de l'entreprise seulement si le bouton existe
    if (addButton) {
        await loadCompanyInfo();
    }
    
    if (addButton) {
        addButton.addEventListener('click', async () => {
            // Si une popup d'inscription existe déjà, réafficher et attendre la réponse
            if (window.currentRegisterPopup) {
                console.log('🔄 Réaffichage de la popup d\'inscription existante...');
                
                // Récupérer les IDs stockés sur la popup
                const companyId = window.currentRegisterPopup.getAttribute('data-company-id');
                const contactId = window.currentRegisterPopup.getAttribute('data-contact-id');
                const jobId = window.currentRegisterPopup.getAttribute('data-job-id');
                
                try {
                    // Réafficher la popup et attendre l'action de l'utilisateur
                    const registerResult = await registerPopupCompany(companyId, contactId);
                    console.log('✅ Inscription terminée:', registerResult);
                    
                    // Si l'utilisateur a cliqué "Revenir en arrière", ne rien faire
                    if (registerResult && registerResult.skipped) {
                        console.log('⏭️ Utilisateur a cliqué sur "Revenir en arrière"');
                        return;
                    }
                    
                    // Sinon, rediriger vers la connexion
                    setTimeout(async () => {
                        try {
                            await convertJobToHTML(jobId);
                            console.log('✅ HTML généré avec succès');
                        } catch (error) {
                            console.error('⚠️ Erreur lors de la génération HTML:', error);
                        }
                        
                        window.location.href = getAbsolutePath(`user-account/connection`);
                    }, 1500);
                } catch (error) {
                    console.log('⚠️ Inscription annulée:', error.message);
                }
                
                return;
            }
            
            console.log('🆕 Ajout d\'une nouvelle offre d\'emploi...');
            
            try {
                // Collecter les données du formulaire
                const dataByTable = collectDataByTable();
                
                // Récupérer le fichier logo s'il existe (depuis l'éditeur ou le dataElement)
                const logoElement = document.querySelector('[data-table="company"][data-column="media_code"]');
                const logoFile = logoElement ? logoElement.getAttribute('data-logo-file') : null;
                
                // Insérer toutes les données
                await insertNewJob(dataByTable, logoFile);
                
                alert('✅ Offre d\'emploi créée avec succès!');
            } catch (error) {
                console.error('❌ Erreur lors de l\'ajout:', error);
                alert('❌ Erreur lors de l\'ajout de l\'offre: ' + error.message);
            }
        });
    }
});

/**
 * Charge les informations de l'entreprise connectée
 */
async function loadCompanyInfo() {
    try {
        console.log('🔍 Vérification de la connexion entreprise...');
        
        const response = await fetch(getFunctionUrl('getMyCompany.php'));
        const data = await response.json();
        
        if (data.success && data.company) {
            console.log('✅ Entreprise connectée:', data.company);
            
            isCompanyLoggedIn = true;
            currentCompanyId = data.company.id;
            currentContactId = data.company.contact?.id || null;
            currentCompanyName = data.company.name;
            
            // Pré-remplir les informations de l'entreprise et du contact
            prefillCompanyAndContact(data.company);
            
            // Désactiver les sections d'édition pour company et contact
            disableCompanyAndContactEditors();
            
            // Mettre à jour l'affichage
            updateCompanyDisplayName(data.company.name);
            
            console.log('🔒 Sections company et contact verrouillées (pré-remplies avec les données du compte)');
        } else {
            console.log('ℹ️ Aucune entreprise connectée - formulaire complet requis');
            isCompanyLoggedIn = false;
        }
    } catch (error) {
        console.log('ℹ️ Pas d\'entreprise connectée:', error.message);
        isCompanyLoggedIn = false;
    }
}

/**
 * Pré-remplit les champs company et contact
 */
function prefillCompanyAndContact(company) {
    console.log('📝 Pré-remplissage des données entreprise et contact...');
    
    // Pré-remplir les données de l'entreprise
    if (company.name) {
        updateDataElement('company', 'name', company.name);
    }
    if (company.description) {
        updateDataElement('company', 'description', company.description);
    }
    if (company.domain) {
        updateDataElement('company', 'domain', company.domain);
    }
    if (company.capacity) {
        updateDataElement('company', 'capacity', company.capacity);
    }
    if (company.hq_location) {
        updateDataElement('company', 'hq_location', company.hq_location);
    }
    if (company.country) {
        updateDataElement('company', 'country', company.country);
    }
    
    // Pré-remplir les données du contact
    if (company.contact) {
        if (company.contact.first_name) {
            updateDataElement('contact', 'first_name', company.contact.first_name);
        }
        if (company.contact.last_name) {
            updateDataElement('contact', 'last_name', company.contact.last_name);
        }
        if (company.contact.email) {
            updateDataElement('contact', 'email', company.contact.email);
        }
        if (company.contact.phone) {
            updateDataElement('contact', 'phone', company.contact.phone);
        }
    }
    
    console.log('✅ Données pré-remplies');
}

/**
 * Met à jour un élément dataToEdit avec une valeur
 */
function updateDataElement(table, column, value) {
    const element = document.querySelector(`[data-table="${table}"][data-column="${column}"]`);
    if (element) {
        element.setAttribute('data-value', value);
        if (element.tagName === 'DIV') {
            element.textContent = value;
        } else {
            element.textContent = value;
        }
    }
}

/**
 * Désactive les éditeurs pour company et contact
 */
function disableCompanyAndContactEditors() {
    const companySection = document.querySelector('[data-editor="job-company-info"]');
    const contactSection = document.querySelector('[data-editor="job-contact"]');
    
    if (companySection) {
        companySection.classList.remove('canEdit');
        companySection.style.opacity = '0.7';
        companySection.style.pointerEvents = 'none';
        
        // Ajouter un message informatif
        const infoMessage = document.createElement('div');
        infoMessage.style.background = '#e7f3ff';
        infoMessage.style.padding = '12px';
        infoMessage.style.borderRadius = '8px';
        infoMessage.style.marginBottom = '1rem';
        infoMessage.style.color = '#0369a1';
        infoMessage.innerHTML = '🔒 Ces informations sont pré-remplies depuis votre profil entreprise';
        companySection.insertBefore(infoMessage, companySection.firstChild);
    }
    
    if (contactSection) {
        contactSection.classList.remove('canEdit');
        contactSection.style.opacity = '0.7';
        contactSection.style.pointerEvents = 'none';
        
        // Ajouter un message informatif
        const infoMessage = document.createElement('div');
        infoMessage.style.background = '#e7f3ff';
        infoMessage.style.padding = '12px';
        infoMessage.style.borderRadius = '8px';
        infoMessage.style.marginBottom = '1rem';
        infoMessage.style.color = '#0369a1';
        infoMessage.innerHTML = '🔒 Ces informations sont pré-remplies depuis votre profil entreprise';
        contactSection.insertBefore(infoMessage, contactSection.firstChild);
    }
}

/**
 * Met à jour l'affichage du nom de l'entreprise
 */
function updateCompanyDisplayName(name) {
    const companyNameDisplay = document.getElementById('company-name-display');
    if (companyNameDisplay) {
        companyNameDisplay.textContent = name;
    }
    
    const companyNameInfo = document.getElementById('company-name-info');
    if (companyNameInfo) {
        companyNameInfo.textContent = name;
    }
    
    // Afficher la section d'information si l'utilisateur est connecté
    const companyInfoSection = document.getElementById('company-info-section');
    if (companyInfoSection) {
        companyInfoSection.style.display = 'block';
    }
}

/**
 * Upload un logo pour une entreprise
 */
async function uploadCompanyLogo(companyId, logoFile, mediaCode) {
    if (!logoFile) {
        console.log('⏭️ Pas de logo à uploader');
        return null;
    }
    
    console.log('📤 Upload du logo pour l\'entreprise', companyId, 'MediaCode:', mediaCode);
    
    // Convertir l'image en base64
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

/**
 * Génère un mediaCode unique pour une company
 */
function generateMediaCode() {
    const timestamp = Date.now();
    const random = Math.random().toString(36).substring(2, 15);
    return `${timestamp}-${random}`;
}

/**
 * Insère une nouvelle offre d'emploi avec toutes ses données
 */
async function insertNewJob(dataByTable, logoFile = null) {
    const insertedIds = {};
    let companyMediaCode = null;
    
    console.log('📦 Données à insérer:', dataByTable);
    
    // ==================== PHASE 1: CRÉER LES ENTITÉS LIÉES ====================
    
    // ÉTAPE 1: Company - utiliser l'ID existant si connecté, sinon créer
    if (isCompanyLoggedIn && currentCompanyId) {
        console.log('1️⃣ Utilisation de l\'entreprise connectée (ID:', currentCompanyId, ')');
        insertedIds.company_id = currentCompanyId;
        
        // Si on a un nouveau logo, l'uploader pour l'entreprise existante
        if (logoFile) {
            // Récupérer le mediaCode de l'entreprise existante
            try {
                const companyResponse = await fetch(getFunctionUrl('getData.php') + `?table=company&id=${currentCompanyId}`);
                const companyData = await companyResponse.json();
                if (companyData.success && companyData.data && companyData.data.media_code) {
                    companyMediaCode = companyData.data.media_code;
                    await uploadCompanyLogo(currentCompanyId, logoFile, companyMediaCode);
                }
            } catch (error) {
                console.error('⚠️ Erreur lors de l\'upload du logo pour l\'entreprise existante:', error);
            }
        }
    } else {
        // Créer une nouvelle entreprise
        if (dataByTable.company) {
            console.log('1️⃣ Insertion company...');
            
            const companyData = {};
            for (const [column, value] of Object.entries(dataByTable.company)) {
                if (value !== null && value !== undefined && value !== '' && value !== '--' && column !== 'id') {
                    companyData[column] = value;
                }
            }
            
            // Validation: au moins le nom de l'entreprise doit être fourni
            if (!companyData.name || companyData.name.trim() === '') {
                throw new Error('Le nom de l\'entreprise est requis');
            }
            
            // Générer un mediaCode unique pour la nouvelle entreprise
            companyMediaCode = generateMediaCode();
            companyData.media_code = companyMediaCode;
            
            console.log('📤 Données company:', companyData);
            const companyResponse = await fetch(getFunctionUrl('insertData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'company',
                    data: companyData
                })
            });
            
            const companyResult = await companyResponse.json();
            console.log('✅ Résultat company:', companyResult);
            
            if (!companyResult.success) {
                throw new Error('Échec insertion company: ' + (companyResult.error || companyResult.message || JSON.stringify(companyResult)));
            }
            
            insertedIds.company_id = companyResult.id;
            
            // Uploader le logo si fourni
            if (logoFile) {
                try {
                    await uploadCompanyLogo(companyResult.id, logoFile, companyMediaCode);
                } catch (error) {
                    console.error('⚠️ Erreur lors de l\'upload du logo:', error);
                    // Ne pas bloquer la création de l'offre si l'upload échoue
                }
            }
        } else {
            throw new Error('Les informations de l\'entreprise sont requises');
        }
    }
    
    // ÉTAPE 2: Contact - utiliser l'ID existant si connecté, sinon créer
    if (isCompanyLoggedIn && currentContactId) {
        console.log('2️⃣ Utilisation du contact existant (ID:', currentContactId, ')');
        insertedIds.contact_id = currentContactId;
    } else {
        // Créer un nouveau contact
        if (dataByTable.contact) {
            console.log('2️⃣ Insertion contact...');
            
            const contactData = {};
            for (const [column, value] of Object.entries(dataByTable.contact)) {
                if (value !== null && value !== undefined && value !== '' && value !== '--' && column !== 'id') {
                    contactData[column] = value;
                }
            }
            
            // Validation: au moins email ou téléphone doit être fourni
            if ((!contactData.email || contactData.email.trim() === '') && (!contactData.phone || contactData.phone.trim() === '')) {
                throw new Error('Au moins un email ou un téléphone est requis pour le contact');
            }
            
            console.log('📤 Données contact:', contactData);
            const contactResponse = await fetch(getFunctionUrl('insertData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'contact',
                    data: contactData
                })
            });
            
            const contactResult = await contactResponse.json();
            console.log('✅ Résultat contact:', contactResult);
            
            if (!contactResult.success) {
                throw new Error('Échec insertion contact: ' + (contactResult.error || contactResult.message || JSON.stringify(contactResult)));
            }
            
            insertedIds.contact_id = contactResult.id;
        } else {
            throw new Error('Les informations de contact sont requises');
        }
    }
    
    // ÉTAPE 3: Insérer requirement
    if (dataByTable.requirement) {
        console.log('3️⃣ Insertion requirement...');
        
        const requirementData = {};
        for (const [column, value] of Object.entries(dataByTable.requirement)) {
            if (value !== null && value !== undefined && value !== '' && value !== '--' && column !== 'id') {
                requirementData[column] = value;
            }
        }
        
        console.log('📤 Données requirement:', requirementData);
        const requirementResponse = await fetch(getFunctionUrl('insertData.php'), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                table: 'requirement',
                data: requirementData
            })
        });
        
        const requirementResult = await requirementResponse.json();
        console.log('✅ Résultat requirement:', requirementResult);
        
        if (!requirementResult.success) {
            throw new Error('Échec insertion requirement: ' + (requirementResult.error || requirementResult.message || JSON.stringify(requirementResult)));
        }
        
        insertedIds.requirement_id = requirementResult.id;
    }
    
    // ==================== PHASE 2: CRÉER L'OFFRE D'EMPLOI ====================
    
    // ÉTAPE 4: Insérer job avec tous les IDs
    console.log('4️⃣ Insertion job...');
    
    const jobData = {
        ...insertedIds
    };
    
    // Ajouter les données de la table job
    if (dataByTable.job) {
        for (const [column, value] of Object.entries(dataByTable.job)) {
            if (column !== 'id') {
                // Traiter spécialement need_vehicle pour le convertir en entier
                if (column === 'need_vehicle') {
                    jobData[column] = value ? 1 : 0;
                } else if (value !== null && value !== undefined && value !== '' && value !== '--') {
                    jobData[column] = value;
                }
            }
        }
    }
    
    // Par défaut, l'offre est ouverte
    jobData.open = 1;
    
    console.log('📤 Données job:', jobData);
    const jobResponse = await fetch(getFunctionUrl('insertData.php'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            table: 'job',
            data: jobData
        })
    });
    
    const jobResult = await jobResponse.json();
    console.log('✅ Résultat job:', jobResult);
    
    if (!jobResult.success) {
        throw new Error('Échec insertion job: ' + (jobResult.error || jobResult.message || JSON.stringify(jobResult)));
    }
    
    const jobId = jobResult.id;
    
    console.log('🎉 Offre d\'emploi créée avec succès! ID:', jobId);
    
    // Afficher la popup d'inscription pour l'entreprise UNIQUEMENT si pas déjà connecté
    if (!isCompanyLoggedIn) {
        try {
            console.log('📝 Affichage de la popup d\'inscription entreprise...');
            
            // Appeler registerPopupCompany (crée la popup synchroniquement)
            const registerPromise = registerPopupCompany(insertedIds.company_id, insertedIds.contact_id);
            
            // Stocker les IDs sur la popup immédiatement après sa création
            if (window.currentRegisterPopup) {
                window.currentRegisterPopup.setAttribute('data-company-id', insertedIds.company_id);
                window.currentRegisterPopup.setAttribute('data-contact-id', insertedIds.contact_id);
                window.currentRegisterPopup.setAttribute('data-job-id', jobId);
            }
            
            // Attendre la résolution de la Promise (action de l'utilisateur)
            const registerResult = await registerPromise;
            console.log('✅ Inscription terminée:', registerResult);
            
            // Si l'utilisateur a cliqué "Revenir en arrière", ne pas rediriger
            if (registerResult && registerResult.skipped) {
                console.log('⚠️ L\'utilisateur a choisi de revenir en arrière - pas de redirection');
                return { jobId, ...insertedIds };
            }
        } catch (error) {
            console.log('⚠️ Inscription annulée ou passée:', error.message);
        }
    } else {
        console.log('ℹ️ Entreprise déjà connectée - pas de popup d\'inscription');
    }
    
    // Convertir l'offre en HTML statique
    setTimeout(async () => {
        try {
            await convertJobToHTML(jobId);
            console.log('✅ HTML généré avec succès');
        } catch (error) {
            console.error('⚠️ Erreur lors de la génération HTML:', error);
        }
        
        // Rediriger selon le contexte
        if (isCompanyLoggedIn) {
            // Si connecté, rediriger vers le dashboard entreprise
            window.location.href = getAbsolutePath(`user-account/company/jobs`);
        } else {
            // Si non connecté, rediriger vers la page de connexion
            window.location.href = getAbsolutePath(`user-account/connection`);
        }
    }, 1500);
    
    return { jobId, ...insertedIds };
}

/**
 * Collecte toutes les données du formulaire groupées par table
 */
function collectDataByTable() {
    console.log('🔎 Début de la collecte des données...');
    
    const dataByTable = {};
    
    // Collecter les données simples (dataToEdit)
    const simpleElements = document.querySelectorAll('.dataToEdit');
    console.log(`📋 ${simpleElements.length} éléments trouvés`);
    
    simpleElements.forEach(element => {
        const table = element.dataset.table;
        const column = element.dataset.column;
        const value = element.dataset.value || element.textContent.trim();
        
        if (!dataByTable[table]) {
            dataByTable[table] = {};
        }
        
        // Gérer les valeurs booléennes pour need_vehicle
        if (column === 'need_vehicle') {
            dataByTable[table][column] = value === '1' || value === 'true' || value === 'Oui';
        } else {
            dataByTable[table][column] = value;
        }
    });
    
    console.log('📦 Données collectées:', dataByTable);
    return dataByTable;
}

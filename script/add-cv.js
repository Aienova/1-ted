// Script pour ajouter un nouveau CV
document.addEventListener('DOMContentLoaded', () => {
    const addButton = document.getElementById('add-cv-button');
    
    if (addButton) {
        addButton.addEventListener('click', async () => {
            console.log('🆕 Ajout d\'un nouveau candidat...');
            
            try {
                // Collecter les données du formulaire
                const dataByTable = collectDataByTable();
                
                // Insérer toutes les données
                const result = await insertNewCandidate(dataByTable);
                const { mediaCode, candidateId } = result;
                
                // Convertir le CV en HTML statique
                if (mediaCode) {
                    console.log('📄 Génération de la version HTML statique...');
                    await convertCVToHTML(mediaCode);
                }
                
                // Afficher la popup de création de compte
                if (candidateId) {
                    console.log('🔐 Affichage popup de création de compte pour candidat ID:', candidateId);
                    try {
                        // Popup de création de compte (bloque le script jusqu'à validation ou annulation)
                        await registerPopup(candidateId);
                        
                        // Si on arrive ici, c'est que le compte a été créé avec succès
                        
                        // Vérifier s'il y a une offre d'emploi sélectionnée pour créer une candidature
                        const cvContainer = document.querySelector('.cv-container');
                        const jobSelectedId = cvContainer ? cvContainer.getAttribute('data-jobselectedid') : null;
                        
                        if (jobSelectedId) {
                            console.log('📋 Création de la candidature pour l\'offre ID:', jobSelectedId);
                            try {
                                await createJobApplication(candidateId, jobSelectedId);
                                console.log('✅ Candidature créée avec succès!');
                                alert('✅ Candidat, compte et candidature créés avec succès ! Vous pouvez maintenant vous connecter.');
                            } catch (appError) {
                                console.error('❌ Erreur création candidature:', appError);
                                alert('✅ Candidat et compte créés avec succès, mais erreur lors de la création de la candidature. Vous pouvez postuler manuellement.');
                            }
                        } else {
                            alert('✅ Candidat et compte créés avec succès ! Vous pouvez maintenant vous connecter.');
                        }
                        
                        // Rediriger vers la page de connexion
                        setTimeout(() => {
                            window.location.href = './user-account/connection';
                        }, 500);
                    } catch (error) {
                        // Si la popup est annulée, le candidat est déjà supprimé
                        console.log('❌ Inscription annulée:', error.message);
                        // La page sera rechargée automatiquement par registerPopup
                    }
                } else {
                    console.error('❌ Pas de candidateId trouvé');
                }
                
            } catch (error) {
                console.error('❌ Erreur lors de l\'ajout:', error);
                alert('❌ Erreur lors de l\'ajout du candidat: ' + error.message);
            }
        });
    }
});

/**
 * Génère un code média unique de 12 caractères
 * Mélange des lettres aléatoires avec des chiffres extraits de la date/heure
 */
function generateUniqueMediaCode() {
    const timestamp = Date.now().toString();
    const digits = timestamp.split('').filter(c => /\d/.test(c));
    
    // Générer des lettres aléatoires
    const letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const randomLetters = [];
    for (let i = 0; i < 6; i++) {
        randomLetters.push(letters.charAt(Math.floor(Math.random() * letters.length)));
    }
    
    // Sélectionner 6 chiffres aléatoires du timestamp
    const shuffledDigits = digits.sort(() => Math.random() - 0.5);
    const selectedDigits = shuffledDigits.slice(0, 6);
    
    // Mélanger les lettres et les chiffres
    const characters = [...randomLetters, ...selectedDigits];
    characters.sort(() => Math.random() - 0.5);
    
    return characters.slice(0, 12).join('');
}

/**
 * Crée le dossier media et upload les images
 */
async function createMediaFolderAndUploadImages(mediaCode) {
    console.log('📁 Création du dossier media et upload des images...');
    
    const photoElement = document.querySelector('.profile-photo[data-column="media_code"]');
    const photoUrl = photoElement ? photoElement.style.backgroundImage.match(/url\(['"]?([^'"]+)['"]?\)/)?.[1] : null;
    
    const qrcodeElement = document.querySelector('.footer-qr img[data-classified="true"]');
    const qrcodeUrl = qrcodeElement ? qrcodeElement.src : null;
    
    const formData = new FormData();
    formData.append('mediaCode', mediaCode);
    
    // Ajouter la photo si elle existe
    if (photoUrl && !photoUrl.includes('default') && photoUrl !== '') {
        const photoBlob = await fetch(photoUrl).then(r => r.blob());
        formData.append('photo', photoBlob, 'photo.jpg');
    }
    
    // Ajouter le QR code si il existe
    if (qrcodeUrl && !qrcodeUrl.includes('qrcode.png') && qrcodeUrl !== '') {
        const qrcodeBlob = await fetch(qrcodeUrl).then(r => r.blob());
        formData.append('qrcode', qrcodeBlob, 'qr-code.png');
    }
    
    const response = await fetch(getFunctionUrl('uploadImage.php'), {
        method: 'POST',
        body: formData
    });
    
    const result = await response.json();
    console.log('✅ Résultat upload images:', result);
    
    return result;
}

/**
 * Insère un nouveau candidat avec toutes ses données
 */
async function insertNewCandidate(dataByTable) {
    const insertedIds = {};
    
    console.log('📦 Données à insérer:', dataByTable);
    console.log('🔍 Collections:', dataByTable.collections);
    
    // ==================== PHASE 1: CRÉER LES ENTITÉS DE BASE ====================
    
    // ÉTAPE 1: Insérer identity (SANS collections)
    if (dataByTable.identity) {
        console.log('1️⃣ Insertion identity...');
        
        const identityData = {};
        for (const [column, value] of Object.entries(dataByTable.identity)) {
            // Filtrer les valeurs vides/nulles et les collections
            if (value !== null && value !== undefined && value !== '' && value !== '--' 
                && !column.includes('_ids') && column !== 'id') {
                identityData[column] = value;
            }
        }
        
        console.log('📤 Données identity:', identityData);
        const identityResponse = await fetch(getFunctionUrl('insertData.php'), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                table: 'identity',
                data: identityData
            })
        });
        
        const identityResult = await identityResponse.json();
        console.log('✅ Résultat identity:', identityResult);
        
        if (!identityResult.success) {
            throw new Error('Échec insertion identity: ' + identityResult.error);
        }
        
        insertedIds.identity_id = identityResult.id;
    }
    
    // ÉTAPE 2: Insérer demand
    if (dataByTable.demand) {
        console.log('2️⃣ Insertion demand...');
        
        const demandData = {};
        for (const [column, value] of Object.entries(dataByTable.demand)) {
            if (value !== null && value !== undefined && value !== '' && value !== '--' && column !== 'id') {
                demandData[column] = value;
            }
        }
        
        console.log('📤 Données demand:', demandData);
        const demandResponse = await fetch(getFunctionUrl('insertData.php'), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                table: 'demand',
                data: demandData
            })
        });
        
        const demandResult = await demandResponse.json();
        console.log('✅ Résultat demand:', demandResult);
        
        if (!demandResult.success) {
            throw new Error('Échec insertion demand: ' + demandResult.error);
        }
        
        insertedIds.demand_id = demandResult.id;
    }
    
    // ÉTAPE 3: Insérer skills
    if (dataByTable.skill && dataByTable.skill.items && dataByTable.skill.items.length > 0) {
        console.log('3️⃣ Insertion skills...');
        
        const skillIds = [];
        for (const skillItem of dataByTable.skill.items) {
            const skillData = {};
            for (const [column, value] of Object.entries(skillItem)) {
                if (value !== null && value !== undefined && value !== '' && column !== 'id') {
                    skillData[column] = value;
                }
            }
            
            console.log('📤 Insertion skill:', skillData);
            const skillResponse = await fetch(getFunctionUrl('insertData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'skill',
                    data: skillData
                })
            });
            
            const skillResult = await skillResponse.json();
            console.log('✅ Skill ajouté:', skillResult);
            
            if (skillResult.success) {
                skillIds.push(skillResult.id);
            }
        }
        
        insertedIds.skill_ids = JSON.stringify(skillIds);
        console.log('📊 Tous les skill_ids:', insertedIds.skill_ids);
    }
    
    // ÉTAPE 4: Insérer experiences avec leurs missions
    if (dataByTable.experience && dataByTable.experience.items && dataByTable.experience.items.length > 0) {
        console.log('4️⃣ Insertion experiences...');
        
        const experienceIds = [];
        for (const expItem of dataByTable.experience.items) {
            const expData = {};
            for (const [column, value] of Object.entries(expItem)) {
                // Traiter spécialement current pour le convertir en entier
                if (column === 'current') {
                    expData[column] = value ? 1 : 0;
                } else if (value !== null && value !== undefined && value !== '' && column !== 'id' && column !== 'missions') {
                    expData[column] = value;
                }
            }
            
            console.log('📤 Insertion experience:', expData);
            const expResponse = await fetch(getFunctionUrl('insertData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'experience',
                    data: expData
                })
            });
            
            const expResult = await expResponse.json();
            console.log('✅ Experience ajoutée:', expResult);
            
            if (expResult.success) {
                const experienceId = expResult.id;
                experienceIds.push(experienceId);
                
                // Insérer les missions pour cette expérience
                if (expItem.missions && expItem.missions.length > 0) {
                    console.log('📋 Insertion missions pour experience', experienceId);
                    
                    const missionIds = [];
                    for (const missionItem of expItem.missions) {
                        const missionData = {};
                        for (const [column, value] of Object.entries(missionItem)) {
                            if (value !== null && value !== undefined && value !== '' && column !== 'id') {
                                missionData[column] = value;
                            }
                        }
                        
                        console.log('📤 Insertion mission:', missionData);
                        const missionResponse = await fetch(getFunctionUrl('insertData.php'), {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                table: 'mission',
                                data: missionData
                            })
                        });
                        
                        const missionResult = await missionResponse.json();
                        if (missionResult.success) {
                            missionIds.push(missionResult.id);
                        }
                    }
                    
                    // Mettre à jour l'expérience avec les mission_ids
                    if (missionIds.length > 0) {
                        console.log('🔄 Mise à jour experience avec missions:', missionIds);
                        await fetch(getFunctionUrl('updateData.php'), {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                table: 'experience',
                                id: experienceId,
                                data: { missions: JSON.stringify(missionIds) }
                            })
                        });
                    }
                }
            }
        }
        
        insertedIds.experience_ids = JSON.stringify(experienceIds);
        console.log('📊 Tous les experience_ids:', insertedIds.experience_ids);
    }
    
    // ÉTAPE 5: Insérer formations avec leurs knowledges
    if (dataByTable.formation && dataByTable.formation.items && dataByTable.formation.items.length > 0) {
        console.log('5️⃣ Insertion formations...');
        
        const formationIds = [];
        for (const formItem of dataByTable.formation.items) {
            const formData = {};
            for (const [column, value] of Object.entries(formItem)) {
                // Traiter spécialement current pour le convertir en entier
                if (column === 'current') {
                    formData[column] = value ? 1 : 0;
                } else if (value !== null && value !== undefined && value !== '' && column !== 'id' && column !== 'knowledges') {
                    formData[column] = value;
                }
            }
            
            console.log('📤 Insertion formation:', formData);
            const formResponse = await fetch(getFunctionUrl('insertData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'formation',
                    data: formData
                })
            });
            
            const formResult = await formResponse.json();
            console.log('✅ Formation ajoutée:', formResult);
            
            if (formResult.success) {
                const formationId = formResult.id;
                formationIds.push(formationId);
                
                // Insérer les knowledges pour cette formation
                if (formItem.knowledges && formItem.knowledges.length > 0) {
                    console.log('📋 Insertion knowledges pour formation', formationId);
                    
                    const knowledgeIds = [];
                    for (const knowledgeItem of formItem.knowledges) {
                        const knowledgeData = {};
                        for (const [column, value] of Object.entries(knowledgeItem)) {
                            if (value !== null && value !== undefined && value !== '' && column !== 'id') {
                                knowledgeData[column] = value;
                            }
                        }
                        
                        console.log('📤 Insertion knowledge:', knowledgeData);
                        const knowledgeResponse = await fetch(getFunctionUrl('insertData.php'), {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                table: 'knowledge',
                                data: knowledgeData
                            })
                        });
                        
                        const knowledgeResult = await knowledgeResponse.json();
                        if (knowledgeResult.success) {
                            knowledgeIds.push(knowledgeResult.id);
                        }
                    }
                    
                    // Mettre à jour la formation avec les knowledge_ids
                    if (knowledgeIds.length > 0) {
                        console.log('🔄 Mise à jour formation avec knowledge:', knowledgeIds);
                        await fetch(getFunctionUrl('updateData.php'), {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                table: 'formation',
                                id: formationId,
                                data: { knowledge: JSON.stringify(knowledgeIds) }
                            })
                        });
                    }
                }
            }
        }
        
        insertedIds.formation_ids = JSON.stringify(formationIds);
        console.log('📊 Tous les formation_ids:', insertedIds.formation_ids);
    }
    
    // ÉTAPE 6: Insérer les personalities
    if (dataByTable.collections && dataByTable.collections.personalities && dataByTable.collections.personalities.length > 0) {
        console.log('6️⃣ Insertion personalities...');
        
        const personalityIds = [];
        for (const item of dataByTable.collections.personalities) {
            const value = extractCollectionValue(item);
            if (!value || value === 'Array' || value === '--') continue;
            
            const personalityData = { trait: value };
            
            console.log('📤 Insertion personality:', personalityData);
            const personalityResponse = await fetch(getFunctionUrl('insertData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'personality',
                    data: personalityData
                })
            });
            
            const personalityResult = await personalityResponse.json();
            if (personalityResult.success) {
                personalityIds.push(personalityResult.id);
            }
        }
        
        insertedIds.personality_ids = JSON.stringify(personalityIds);
        console.log('📊 Tous les personality_ids:', insertedIds.personality_ids);
    }
    
    // ÉTAPE 7: Créer le candidat avec tous les IDs
    console.log('7️⃣ Création du candidat final...');
    
    // Générer le media_code
    const mediaCode = generateUniqueMediaCode();
    console.log('📝 Media code généré:', mediaCode);
    
    // Uploader les images
    await createMediaFolderAndUploadImages(mediaCode);
    
    const candidateData = {
        ...insertedIds,
        media_code: mediaCode
    };
    
    console.log('📤 Insertion candidate:', candidateData);
    const candidateResponse = await fetch(getFunctionUrl('insertData.php'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            table: 'candidate',
            data: candidateData
        })
    });
    
    const candidateResult = await candidateResponse.json();
    console.log('✅ Résultat candidate:', candidateResult);
    
    if (!candidateResult.success) {
        throw new Error('Échec insertion candidate: ' + candidateResult.error);
    }
    
    const candidateId = candidateResult.id;
    
    // ==================== PHASE 2: AJOUTER LES COLLECTIONS VIA UPDATE ====================
    
    // ÉTAPE 8: Ajouter les langues à identity
    if (dataByTable.collections && dataByTable.collections.languages && dataByTable.collections.languages.length > 0) {
        console.log('8️⃣ Ajout des langues à identity...');
        
        const languageIds = [];
        for (const item of dataByTable.collections.languages) {
            const value = extractCollectionValue(item);
            if (!value || value === 'Array' || value === '--') continue;
            
            const languageData = { language_name: value };
            
            console.log('📤 Insertion language:', languageData);
            const languageResponse = await fetch(getFunctionUrl('insertData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'language',
                    data: languageData
                })
            });
            
            const languageResult = await languageResponse.json();
            if (languageResult.success) {
                languageIds.push(languageResult.id);
            }
        }
        
        if (languageIds.length > 0) {
            console.log('🔄 Mise à jour identity avec language_ids:', languageIds);
            await fetch(getFunctionUrl('updateData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'identity',
                    id: insertedIds.identity_id,
                    data: { language_ids: JSON.stringify(languageIds) }
                })
            });
        }
    }
    
    // ÉTAPE 9: Ajouter les hobbies à identity
    if (dataByTable.collections && dataByTable.collections.hobbies && dataByTable.collections.hobbies.length > 0) {
        console.log('9️⃣ Ajout des hobbies à identity...');
        
        const hobbieIds = [];
        for (const item of dataByTable.collections.hobbies) {
            const value = extractCollectionValue(item);
            if (!value || value === 'Array' || value === '--') continue;
            
            const hobbieData = { title: value };
            
            console.log('📤 Insertion hobbie:', hobbieData);
            const hobbieResponse = await fetch(getFunctionUrl('insertData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'hobbie',
                    data: hobbieData
                })
            });
            
            const hobbieResult = await hobbieResponse.json();
            if (hobbieResult.success) {
                hobbieIds.push(hobbieResult.id);
            }
        }
        
        if (hobbieIds.length > 0) {
            console.log('🔄 Mise à jour identity avec hobbie_ids:', hobbieIds);
            await fetch(getFunctionUrl('updateData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'identity',
                    id: insertedIds.identity_id,
                    data: { hobbie_ids: JSON.stringify(hobbieIds) }
                })
            });
        }
    }
    
    console.log('🎉 Candidat créé avec succès! ID:', candidateId);
    
    // Mettre à jour le data-id du conteneur pour référence future
    const cvContainer = document.querySelector('.cv-container');
    if (cvContainer) {
        cvContainer.setAttribute('data-id', candidateId);
        cvContainer.setAttribute('data-mode', 'update');
    }
    
    // Retourner à la fois le mediaCode ET le candidateId
    return { mediaCode, candidateId };
}

/**
 * Extrait la valeur d'un élément de collection
 * L'éditeur stocke les valeurs directement comme strings dans data-value
 */
function extractCollectionValue(item) {
    // L'éditeur retourne directement des strings (ex: "Français", "Anglais")
    if (typeof item === 'string') {
        return item;
    }
    
    // Fallback pour les anciens formats avec objets
    if (typeof item === 'object' && item !== null) {
        return item.title || item.trait || item.name || item.language_name || '';
    }
    
    return '';
}

/**
 * Collecte toutes les données du formulaire groupées par table
 */
function collectDataByTable() {
    console.log('🔎 Début de la collecte des données...');
    
    const dataByTable = {
        collections: {
            languages: [],
            personalities: [],
            hobbies: []
        }
    };
    
    // Collecter les données simples (dataToEdit)
    const simpleElements = document.querySelectorAll('.dataToEdit:not(.multipleDataToEdit)');
    console.log(`📋 ${simpleElements.length} éléments simples trouvés`);
    
    simpleElements.forEach(element => {
        const table = element.dataset.table;
        const column = element.dataset.column;
        const value = element.dataset.value || element.textContent.trim();
        
        if (!dataByTable[table]) {
            dataByTable[table] = {};
        }
        
        dataByTable[table][column] = value;
    });
    
    // Collecter les collections (multipleDataToEdit)
    const collectionElements = document.querySelectorAll('.multipleDataToEdit');
    console.log(`📋 ${collectionElements.length} éléments de collection trouvés`);
    
    collectionElements.forEach(element => {
        const column = element.dataset.column;
        const dataValue = element.dataset.value; // Récupérer depuis data-value au lieu de data-collection
        
        console.log(`🔎 Collection ${column} (data-value):`, dataValue);
        
        let parsedCollection = [];
        if (dataValue && dataValue !== '' && dataValue !== '--') {
            try {
                // Essayer de parser comme JSON (si c'est un tableau)
                if (dataValue.startsWith('[')) {
                    parsedCollection = JSON.parse(dataValue);
                    console.log(`✅ Collection ${column} parsée depuis data-value:`, parsedCollection);
                } else {
                    // Sinon, split par virgule
                    parsedCollection = dataValue.split(',').map(s => s.trim()).filter(s => s);
                    console.log(`✅ Collection ${column} split:`, parsedCollection);
                }
            } catch (e) {
                console.log(`⚠️ Erreur parsing ${column}, utilisation du texte`);
                const text = element.textContent.trim();
                if (text && text !== '--') {
                    parsedCollection = text.split(',').map(s => s.trim()).filter(s => s);
                }
            }
        }
        
        if (column === 'language_ids') {
            dataByTable.collections.languages = parsedCollection;
        } else if (column === 'personality_ids') {
            dataByTable.collections.personalities = parsedCollection;
        } else if (column === 'hobbie_ids') {
            dataByTable.collections.hobbies = parsedCollection;
        }
    });
    
    // Collecter les skills
    dataByTable.skill = collectSkills();
    
    // Collecter les experiences
    dataByTable.experience = collectExperiences();
    
    // Collecter les formations
    dataByTable.formation = collectFormations();
    
    console.log('📦 Données collectées:', dataByTable);
    return dataByTable;
}

/**
 * Collecte toutes les compétences
 */
function collectSkills() {
    const skillsSection = document.querySelector('[data-editor="skills"]');
    if (!skillsSection) return { items: [] };
    
    const skillItems = skillsSection.querySelectorAll('li');
    const skills = [];
    
    skillItems.forEach(item => {
        // Ignorer le placeholder
        const fullText = item.textContent.trim();
        if (fullText === 'Aucune compétence ajoutée') return;
        
        // Vérifier si c'est un favori (présence de ⭐)
        const isFavorite = fullText.includes('⭐');
        
        // Récupérer le titre depuis l'élément avec data-column="title"
        const titleElement = item.querySelector('.dataToEdit[data-column="title"]');
        const title = titleElement ? titleElement.dataset.value || titleElement.textContent.trim() : '';
        
        // Récupérer les années depuis l'élément avec data-column="year"
        const yearElement = item.querySelector('.dataToEdit[data-column="year"]');
        const year = yearElement ? yearElement.dataset.value || yearElement.textContent.trim() : '0';
        
        if (title) {
            skills.push({ 
                title,
                year: parseInt(year) || 0,
                favorite: isFavorite
            });
        }
    });
    
    console.log('💼 Skills collectés:', skills);
    return { items: skills };
}

/**
 * Collecte toutes les expériences
 */
function collectExperiences() {
    const expSection = document.querySelector('[data-editor="experience"]');
    if (!expSection) return { items: [] };
    
    const expItems = expSection.querySelectorAll('.timeline-item');
    const experiences = [];
    
    expItems.forEach(item => {
        // Les dates sont stockées dans des attributs data-* du timeline-item
        const start_date = item.dataset.startDate || item.getAttribute('data-start-date') || '';
        let end_date = item.dataset.endDate || item.getAttribute('data-end-date') || '';
        
        // Vérifier si c'est en cours (data-current="true")
        const isCurrent = item.getAttribute('data-current') === 'true';
        
        // Si en cours OU si la date contient "présent", mettre à NULL
        if (isCurrent || (end_date && end_date.toLowerCase().includes('présent'))) {
            end_date = null;
        }
        
        // L'intitulé du poste est dans data-column="position"
        const position = item.querySelector('.dataToEdit[data-column="position"]')?.dataset.value || '';
        const company = item.querySelector('.dataToEdit[data-column="company"]')?.dataset.value || '';
        const location = item.querySelector('.dataToEdit[data-column="location"]')?.dataset.value || '';
        
        // Les missions sont dans des spans .multipleDataToEditItem
        const missionsSpan = item.querySelector('.missions');
        const missions = [];
        if (missionsSpan) {
            const missionItems = missionsSpan.querySelectorAll('.multipleDataToEditItem');
            missionItems.forEach(missionItem => {
                const missionTitle = missionItem.textContent.trim();
                if (missionTitle) {
                    missions.push({ title: missionTitle });
                }
            });
        }
        
        experiences.push({
            position,
            company,
            location,
            start_date,
            end_date,
            current: isCurrent,
            missions
        });
    });
    
    console.log('💼 Experiences collectées:', experiences);
    return { items: experiences };
}

/**
 * Collecte toutes les formations
 */
function collectFormations() {
    const formSection = document.querySelector('[data-editor="formation"]');
    if (!formSection) return { items: [] };
    
    const formItems = formSection.querySelectorAll('.timeline-item');
    const formations = [];
    
    formItems.forEach(item => {
        // Les dates sont stockées dans des attributs data-* du timeline-item
        const start_date = item.dataset.startDate || item.getAttribute('data-start-date') || '';
        let end_date = item.dataset.endDate || item.getAttribute('data-end-date') || '';
        
        // Vérifier si c'est en cours (data-current="true")
        const isCurrent = item.getAttribute('data-current') === 'true';
        
        // Si en cours OU si la date contient "présent", mettre à NULL
        if (isCurrent || (end_date && end_date.toLowerCase().includes('présent'))) {
            end_date = null;
        }
        
        const title = item.querySelector('.dataToEdit[data-column="title"]')?.dataset.value || '';
        const school = item.querySelector('.dataToEdit[data-column="school"]')?.dataset.value || '';
        const location = item.querySelector('.dataToEdit[data-column="location"]')?.dataset.value || '';
        
        // Les apprentissages sont dans des spans .multipleDataToEditItem
        const learningSpan = item.querySelector('.learning');
        console.log('🔍 learningSpan trouvé:', learningSpan);
        const knowledges = [];
        if (learningSpan) {
            const knowledgeItems = learningSpan.querySelectorAll('.multipleDataToEditItem');
            console.log('🔍 knowledgeItems trouvés:', knowledgeItems.length);
            knowledgeItems.forEach(knowledgeItem => {
                const knowledgeTitle = knowledgeItem.textContent.trim();
                console.log('🔍 knowledgeTitle:', knowledgeTitle);
                if (knowledgeTitle) {
                    knowledges.push({ title: knowledgeTitle });
                }
            });
        } else {
            console.log('⚠️ Aucun learningSpan trouvé pour cette formation');
        }
        console.log('📚 Knowledges collectés pour cette formation:', knowledges);
        
        formations.push({
            title,
            school,
            location,
            start_date,
            end_date,
            current: isCurrent,
            knowledges
        });
    });
    
    console.log('🎓 Formations collectées:', formations);
    return { items: formations };
}

/**
 * Crée une candidature (application) pour une offre d'emploi
 * @param {number} candidateId - ID du candidat
 * @param {number} jobId - ID de l'offre d'emploi
 */
async function createJobApplication(candidateId, jobId) {
    console.log('📋 Création application - Candidat:', candidateId, 'Job:', jobId);
    
    const applicationData = {
        candidate_id: candidateId,
        job_id: jobId,
        status: 'pending',
        recruiter_validation: 0,
        candidate_validation: 1  // Le candidat a validé sa candidature en postulant
    };
    
    const response = await fetch(getFunctionUrl('insertData.php'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            table: 'application',
            data: applicationData
        })
    });
    
    const result = await response.json();
    console.log('✅ Résultat création application:', result);
    
    if (!result.success) {
        throw new Error('Échec création application: ' + result.error);
    }
    
    return result.id;
}

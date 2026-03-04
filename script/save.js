// Script pour sauvegarder le CV

/**
 * Helper pour parser les réponses JSON avec gestion d'erreur améliorée
 */
async function parseJSONResponse(response, context = 'request') {
    const contentType = response.headers.get('content-type');
    
    if (!contentType || !contentType.includes('application/json')) {
        const text = await response.text();
        console.error(`❌ ${context} - Réponse non-JSON reçue:`, text.substring(0, 500));
        throw new Error(`${context}: La réponse n'est pas du JSON valide. Contenu: ${text.substring(0, 100)}`);
    }
    
    try {
        return await response.json();
    } catch (error) {
        const text = await response.text();
        console.error(`❌ ${context} - Erreur parsing JSON:`, text);
        throw new Error(`${context}: Impossible de parser la réponse JSON`);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const saveButton = document.getElementById('save-cv-button');
    
    if (saveButton) {
        saveButton.addEventListener('click', async () => {
            try {
                // Récupérer le conteneur principal
                const cvContainer = document.querySelector('.cv-container');
                if (!cvContainer) {
                    alert('❌ Erreur: Conteneur CV introuvable');
                    return;
                }
                
                const candidateId = cvContainer.getAttribute('data-id');
                const mode = cvContainer.getAttribute('data-mode') || 'update';
                
                // Récupérer le media_code du candidat depuis le footer-qr (toujours présent)
                const mediaCodeElement = document.querySelector('.footer-qr[data-column="media_code"]');
                const mediaCode = mediaCodeElement?.getAttribute('data-value');
                
                console.log('🔍 Media code trouvé:', mediaCode);
                
                if (!mediaCode) {
                    console.error('⚠️ Aucun media_code trouvé, upload d\'images impossible');
                    alert('❌ Erreur: Aucun media_code trouvé. Impossible d\'uploader les images.');
                    return;
                }
                
                // Upload des images en attente AVANT la sauvegarde des données
                if (mediaCode) {
                    console.log('📤 Upload des images en cours...');
                    
                    // Upload de la photo si présente
                    if (window.FormEditors['cv-title']?.pendingPhotoData) {
                        console.log('📤 Upload de la photo...');
                        const photoUrl = await window.FormEditors['cv-title'].uploadPhotoToServer(mediaCode);
                        if (photoUrl) {
                            console.log('✅ Photo uploadée avec succès:', photoUrl);
                            // Mettre à jour l'affichage de la photo avec l'URL finale + timestamp pour éviter le cache
                            const profilePhoto = document.querySelector('.profile-photo');
                            if (profilePhoto) {
                                const timestamp = Date.now();
                                profilePhoto.style.backgroundImage = `url('${photoUrl}?t=${timestamp}')`;
                                profilePhoto.style.backgroundSize = 'cover';
                                console.log('📷 Photo mise à jour dans le DOM avec cache-busting:', timestamp);
                            }
                        }
                    }
                    
                    // Upload du QR code si présent
                    if (window.FormEditors['qrcode']?.pendingQRCodeData) {
                        console.log('📤 Upload du QR code...');
                        const qrUrl = await window.FormEditors['qrcode'].uploadQRCodeToServer(mediaCode);
                        if (qrUrl) {
                            console.log('✅ QR code uploadé avec succès:', qrUrl);
                            // Mettre à jour l'affichage du QR code + timestamp pour éviter le cache
                            const qrImg = document.querySelector('.footer-qr img');
                            if (qrImg) {
                                const timestamp = Date.now();
                                qrImg.src = `${qrUrl}?t=${timestamp}`;
                                console.log('📷 QR code mis à jour dans le DOM avec cache-busting:', timestamp);
                            }
                        }
                    }
                }
                
                // Collecter toutes les données
                const dataByTable = collectDataByTable();
                
                console.log('=== DEBUT SAUVEGARDE ===');
                console.log('Données collectées:', dataByTable);
                console.log('Mode:', mode, 'Candidate ID:', candidateId);
                
                // Sauvegarder les données selon le mode
                if (mode === 'insert') {
                    console.log('MODE INSERT');
                    await insertAllData(dataByTable);
                } else {
                    console.log('MODE UPDATE');
                    await updateAllData(dataByTable, candidateId);
                }
                
                console.log('=== FIN SAUVEGARDE ===');
                
                // Convertir le CV en HTML statique
                console.log('📄 Génération de la version HTML statique...');
                await convertCVToHTML(mediaCode);
                
                alert('✅ CV sauvegardé avec succès !');
                
            } catch (error) {
                console.error('Erreur lors de la sauvegarde:', error);
                alert('❌ Erreur lors de la sauvegarde du CV');
            }
        });
    }
});

/**
 * Génère un code média unique de 12 caractères
 * Mélange des lettres aléatoires avec des chiffres extraits de la date/heure
 */
function generateUniqueMediaCode() {
    const timestamp = Date.now().toString(); // Ex: 1738675200000
    const digits = timestamp.split('').filter(c => /\d/.test(c));
    
    // Générer des lettres aléatoires (majuscules et minuscules)
    const letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const randomLetters = [];
    for (let i = 0; i < 6; i++) {
        randomLetters.push(letters[Math.floor(Math.random() * letters.length)]);
    }
    
    // Sélectionner 6 chiffres aléatoires du timestamp
    const shuffledDigits = digits.sort(() => Math.random() - 0.5);
    const selectedDigits = shuffledDigits.slice(0, 6);
    
    // Mélanger les lettres et les chiffres
    const characters = [...randomLetters, ...selectedDigits];
    characters.sort(() => Math.random() - 0.5);
    
    // Prendre les 12 premiers caractères
    return characters.slice(0, 12).join('');
}

/**
 * Crée le dossier media et upload les images (photo et QR code)
 */
async function createMediaFolderAndUploadImages(mediaCode) {
    console.log('📁 Création du dossier media et upload des images...');
    
    // Récupérer la photo depuis le DOM
    const photoElement = document.querySelector('.profile-photo[data-column="media_code"]');
    const photoUrl = photoElement ? photoElement.style.backgroundImage.match(/url\(['"]?([^'"]+)['"]?\)/)?.[1] : null;
    
    // Récupérer le QR code depuis le DOM
    const qrcodeElement = document.querySelector('.footer-qr img[data-classified="true"]');
    const qrcodeUrl = qrcodeElement ? qrcodeElement.src : null;
    
    const formData = new FormData();
    formData.append('mediaCode', mediaCode);
    
    // Ajouter la photo si elle existe et n'est pas l'image par défaut
    if (photoUrl && !photoUrl.includes('default') && photoUrl !== '') {
        try {
            const photoBlob = await fetch(photoUrl).then(r => r.blob());
            formData.append('photo', photoBlob, 'photo.jpg');
            console.log('📷 Photo ajoutée au FormData');
        } catch (error) {
            console.warn('⚠️ Impossible de récupérer la photo:', error);
        }
    }
    
    // Ajouter le QR code si il existe et n'est pas l'image par défaut
    if (qrcodeUrl && !qrcodeUrl.includes('qrcode.png') && qrcodeUrl !== '') {
        try {
            const qrcodeBlob = await fetch(qrcodeUrl).then(r => r.blob());
            formData.append('qrcode', qrcodeBlob, 'qr-code.png');
            console.log('🔲 QR code ajouté au FormData');
        } catch (error) {
            console.warn('⚠️ Impossible de récupérer le QR code:', error);
        }
    }
    
    // Envoyer au serveur pour créer le dossier et uploader les fichiers
    const response = await fetch(getFunctionUrl('uploadImage.php'), {
        method: 'POST',
        body: formData
    });
    
    const result = await response.json();
    console.log('✅ Résultat upload images:', result);
    
    return result;
}

/**
 * Insère toutes les données en base de données (mode insert)
 */
async function insertAllData(dataByTable) {
    const insertedIds = {};
    
    console.log('🆕 MODE INSERT - Création d\'un nouveau candidat');
    console.log('📦 Données complètes à insérer:', dataByTable);
    console.log('🔍 Collections détectées:', dataByTable.collections);
    
    // ==================== PHASE 1: CRÉER LES ENTITÉS DE BASE ====================
    
    // ÉTAPE 1: Insérer identity (SANS collections)
    if (dataByTable.identity) {
        console.log('1️⃣ Insertion de identity (sans collections)...');
        const identityData = {};
        
        for (const [column, columnData] of Object.entries(dataByTable.identity.columns)) {
            // Skip les collections - on les ajoutera après
            if (column === 'language_ids' || column === 'hobbie_ids') {
                continue;
            }
            
            const value = columnData.value;
            if (value !== null && value !== undefined && value !== '' && value !== '--') {
                identityData[column] = value;
            }
        }
        
        console.log('📤 Insertion identity:', identityData);
        const identityResponse = await fetch(getFunctionUrl('insertData.php'), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ table: 'identity', data: identityData })
        });
        
        const identityResult = await parseJSONResponse(identityResponse, 'Insert identity');
        console.log('✅ Identity créé avec ID:', identityResult.id);
        
        if (identityResult.success) {
            insertedIds.identity_id = identityResult.id;
        } else {
            throw new Error(`Échec identity: ${identityResult.error}`);
        }
    }
    
    // ÉTAPE 2: Insérer demand
    if (dataByTable.demand) {
        console.log('2️⃣ Insertion de demand...');
        const demandData = {};
        
        for (const [column, columnData] of Object.entries(dataByTable.demand.columns)) {
            // Ne pas inclure les valeurs vides
            const value = columnData.value;
            if (value === '' || value === null || value === undefined || value === '--') {
                continue;
            }
            demandData[column] = value;
        }
        
        console.log('📤 Insertion demand avec:', demandData);
        const demandResponse = await fetch(getFunctionUrl('insertData.php'), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                table: 'demand',
                data: demandData
            })
        });
        
        const demandResult = await parseJSONResponse(demandResponse, 'Insert demand');
        console.log('✅ Résultat demand:', demandResult);
        
        if (demandResult.success) {
            insertedIds.demand_id = demandResult.id;
            
            // Mettre à jour le DOM avec le nouvel ID
            const demandElements = document.querySelectorAll('[data-table="demand"]');
            demandElements.forEach(el => {
                if (!el.getAttribute('data-id')) {
                    el.setAttribute('data-id', demandResult.id);
                }
            });
        } else {
            throw new Error('Échec insertion demand: ' + demandResult.message);
        }
    }
    
    // ÉTAPE 3: Insérer skills
    if (dataByTable.skill && dataByTable.skill.items && dataByTable.skill.items.length > 0) {
        console.log('3️⃣ Insertion des skills...');
        const skillIds = [];
        
        for (const skill of dataByTable.skill.items) {
            // Filtrer les données vides
            const cleanSkill = {};
            for (const [key, value] of Object.entries(skill)) {
                if (key !== 'id' && value !== '' && value !== null && value !== undefined) {
                    cleanSkill[key] = value;
                }
            }
            
            if (Object.keys(cleanSkill).length === 0) continue; // Skip si vide
            
            console.log('📤 Insert skill:', cleanSkill);
            const skillResponse = await fetch(getFunctionUrl('insertData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'skill',
                    data: cleanSkill
                })
            });
            
            const skillResult = await parseJSONResponse(skillResponse, 'Insert skill');
            console.log('✅ Résultat skill:', skillResult);
            
            if (skillResult.success) {
                skillIds.push(skillResult.id);
            }
        }
        
        if (skillIds.length > 0) {
            insertedIds.skill_ids = JSON.stringify(skillIds);
            console.log('📦 Skill IDs:', insertedIds.skill_ids);
        }
    }
    
    // ÉTAPE 4: Insérer experiences avec leurs missions
    if (dataByTable.experience && dataByTable.experience.items && dataByTable.experience.items.length > 0) {
        console.log('4️⃣ Insertion des experiences...');
        const experienceIds = [];
        
        const expSection = document.querySelector('[data-editor="experience"]');
        const expItems = expSection?.querySelectorAll('.timeline-item') || [];
        
        for (let i = 0; i < dataByTable.experience.items.length; i++) {
            const exp = dataByTable.experience.items[i];
            const expElement = expItems[i];
            
            // Vérifier si l'expérience a des données valides
            if (!exp.position && !exp.company && !exp.location) {
                console.log('⏭️ Experience vide, ignorée');
                continue;
            }
            
            console.log('📤 Insert experience:', exp);
            
            // Récupérer les dates depuis le DOM
            const startDate = expElement?.getAttribute('data-start-date') || null;
            const endDate = expElement?.getAttribute('data-end-date') || null;
            const isCurrent = expElement?.getAttribute('data-current') === 'true';
            
            // Insérer les missions d'abord
            const missionsEl = expElement?.querySelector('.missions.multipleDataToEdit');
            const missionItems = missionsEl?.querySelectorAll('.multipleDataToEditItem') || [];
            const missionIds = [];
            
            for (const missionItem of missionItems) {
                const missionTitle = missionItem.textContent.trim();
                if (missionTitle) {
                    const missionResponse = await fetch(getFunctionUrl('insertData.php'), {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            table: 'mission',
                            data: { title: missionTitle }
                        })
                    });
                    
                    const missionResult = await missionResponse.json();
                    if (missionResult.success) {
                        missionIds.push(missionResult.id);
                        missionItem.setAttribute('data-id', missionResult.id);
                    }
                }
            }
            
            // Créer l'expérience avec les missions
            const experienceData = {
                position: exp.position || '',
                company: exp.company || '',
                location: exp.location || '',
                start_date: startDate,
                end_date: isCurrent ? null : endDate,
                current: isCurrent ? 1 : 0,
                missions: JSON.stringify(missionIds)
            };
            
            const expResponse = await fetch(getFunctionUrl('insertData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'experience',
                    data: experienceData
                })
            });
            
            const expResult = await parseJSONResponse(expResponse, 'Insert experience');
            console.log('✅ Résultat experience:', expResult);
            
            if (expResult.success) {
                experienceIds.push(expResult.id);
                
                // Mettre à jour le DOM
                if (expElement) {
                    expElement.setAttribute('data-id', expResult.id);
                }
            }
        }
        
        if (experienceIds.length > 0) {
            insertedIds.experience_ids = JSON.stringify(experienceIds);
            console.log('📦 Experience IDs:', insertedIds.experience_ids);
        }
    }
    
    // ÉTAPE 5: Insérer formations avec leurs knowledges
    if (dataByTable.formation && dataByTable.formation.items && dataByTable.formation.items.length > 0) {
        console.log('5️⃣ Insertion des formations...');
        const formationIds = [];
        
        const formSection = document.querySelector('[data-editor="formation"]');
        const formItems = formSection?.querySelectorAll('.timeline-item') || [];
        
        for (let i = 0; i < dataByTable.formation.items.length; i++) {
            const form = dataByTable.formation.items[i];
            const formElement = formItems[i];
            
            // Vérifier si la formation a des données valides
            if (!form.title && !form.school && !form.location) {
                console.log('⏭️ Formation vide, ignorée');
                continue;
            }
            
            console.log('📤 Insert formation:', form);
            
            // Récupérer les dates depuis le DOM
            const startDate = formElement?.getAttribute('data-start-date') || null;
            const endDate = formElement?.getAttribute('data-end-date') || null;
            const isCurrent = formElement?.getAttribute('data-current') === 'true';
            
            // Insérer les knowledges d'abord
            const knowledgesEl = formElement?.querySelector('.multipleDataToEdit[data-column="knowledge"]');
            const knowledgeItems = knowledgesEl?.querySelectorAll('.multipleDataToEditItem') || [];
            const knowledgeIds = [];
            
            for (const knowledgeItem of knowledgeItems) {
                const knowledgeTitle = knowledgeItem.textContent.trim();
                if (knowledgeTitle) {
                    const knowledgeResponse = await fetch(getFunctionUrl('insertData.php'), {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            table: 'knowledge',
                            data: { title: knowledgeTitle }
                        })
                    });
                    
                    const knowledgeResult = await knowledgeResponse.json();
                    if (knowledgeResult.success) {
                        knowledgeIds.push(knowledgeResult.id);
                        knowledgeItem.setAttribute('data-id', knowledgeResult.id);
                    }
                }
            }
            
            // Créer la formation avec les knowledges
            const formationData = {
                title: form.title || '',
                school: form.school || '',
                location: form.location || '',
                start_date: startDate,
                end_date: isCurrent ? null : endDate,
                current: isCurrent ? 1 : 0,
                knowledge: JSON.stringify(knowledgeIds)
            };
            
            const formResponse = await fetch(getFunctionUrl('insertData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'formation',
                    data: formationData
                })
            });
            
            const formResult = await formResponse.json();
            console.log('✅ Résultat formation:', formResult);
            
            if (formResult.success) {
                formationIds.push(formResult.id);
                
                // Mettre à jour le DOM
                if (formElement) {
                    formElement.setAttribute('data-id', formResult.id);
                }
            }
        }
        
        if (formationIds.length > 0) {
            insertedIds.formation_ids = JSON.stringify(formationIds);
            console.log('📦 Formation IDs:', insertedIds.formation_ids);
        }
    }
    
    // ÉTAPE 6: Insérer les personalities (collection)
    if (dataByTable.collections && dataByTable.collections.personalities && dataByTable.collections.personalities.length > 0) {
        console.log('6️⃣ Insertion des personalities...');
        console.log('📋 Personalities brutes:', dataByTable.collections.personalities);
        const personalityIds = [];
        
        for (const trait of dataByTable.collections.personalities) {
            // Extraire la valeur correctement
            let traitValue = null;
            let traitId = null;
            
            if (typeof trait === 'string') {
                traitValue = trait;
            } else if (trait && typeof trait === 'object') {
                // Nouvelle structure : {id, value}
                traitValue = trait.value || trait.title || trait.trait || trait.name;
                traitId = trait.id || null;
            }
            
            console.log('🔍 Trait extrait:', traitValue, '(ID existant:', traitId, ') depuis:', trait);
            
            if (!traitValue || traitValue === '--' || traitValue === 'Array') continue;
            
            // Si l'ID existe déjà, on le réutilise au lieu de créer un nouveau
            if (traitId && traitId !== 'newId') {
                personalityIds.push(traitId);
                console.log(`♻️ Personnalité "${traitValue}" réutilisée avec ID existant:`, traitId);
                continue;
            }
            
            const personalityResponse = await fetch(getFunctionUrl('insertData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'personality',
                    data: { trait: traitValue }
                })
            });
            
            const personalityResult = await personalityResponse.json();
            if (personalityResult.success) {
                personalityIds.push(personalityResult.id);
                console.log(`✅ Personality "${traitValue}" créée avec ID:`, personalityResult.id);
            } else {
                console.error(`❌ Échec insertion personality "${traitValue}":`, personalityResult);
            }
        }
        
        if (personalityIds.length > 0) {
            insertedIds.personality_ids = JSON.stringify(personalityIds);
            console.log('📦 Personality IDs:', insertedIds.personality_ids);
        } else {
            console.warn('⚠️ Aucune personality valide à ajouter');
        }
    }
    
    // ÉTAPE 7: Créer le candidat avec tous les IDs
    console.log('7️⃣ Création du candidat final...');
    
    // Générer un media_code unique (12 caractères aléatoires)
    const mediaCode = generateUniqueMediaCode();
    console.log('📝 Media code généré:', mediaCode);
    
    // Créer le dossier media et uploader les images si présentes
    await createMediaFolderAndUploadImages(mediaCode);
    
    const candidateData = {
        ...insertedIds,
        media_code: mediaCode
    };
    
    console.log('📤 Insertion candidate avec:', candidateData);
    const candidateResponse = await fetch(getFunctionUrl('insertData.php'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            table: 'candidate',
            data: candidateData
        })
    });
    
    const candidateResult = await parseJSONResponse(candidateResponse, 'Insert candidate');
    console.log('✅ Résultat candidate:', candidateResult);
    
    if (!candidateResult.success) {
        throw new Error(`Échec création candidat: ${candidateResult.message}`);
    }
    
    const candidateId = candidateResult.id;
    insertedIds.candidate_id = candidateId;
    
    // ==================== PHASE 2: AJOUTER LES COLLECTIONS VIA UPDATE ====================
    
    // ÉTAPE 8: Ajouter les langues à identity
    if (dataByTable.collections && dataByTable.collections.languages && dataByTable.collections.languages.length > 0) {
        console.log('8️⃣ Ajout des langues à identity...');
        console.log('📋 Langues brutes:', dataByTable.collections.languages);
        const languageIds = [];
        
        for (const lang of dataByTable.collections.languages) {
            // Extraire la valeur correctement
            let langValue = null;
            let langId = null;
            
            if (typeof lang === 'string') {
                langValue = lang;
            } else if (lang && typeof lang === 'object') {
                // Nouvelle structure : {id, value}
                langValue = lang.value || lang.title || lang.language_name || lang.name;
                langId = lang.id || null;
            }
            
            console.log('🔍 Langue extraite:', langValue, '(ID existant:', langId, ') depuis:', lang);
            
            if (!langValue || langValue === '--' || langValue === 'Array') continue;
            
            // Si l'ID existe déjà, on le réutilise au lieu de créer un nouveau
            if (langId && langId !== 'newId') {
                languageIds.push(langId);
                console.log(`♻️ Langue "${langValue}" réutilisée avec ID existant:`, langId);
                continue;
            }
            
            const langResponse = await fetch(getFunctionUrl('insertData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'language',
                    data: { language_name: langValue }
                })
            });
            
            const langResult = await langResponse.json();
            if (langResult.success) {
                languageIds.push(langResult.id);
                console.log(`✅ Langue "${langValue}" créée avec ID:`, langResult.id);
            } else {
                console.error(`❌ Échec insertion langue "${langValue}":`, langResult);
            }
        }
        
        if (languageIds.length > 0) {
            const updateResponse = await fetch(getFunctionUrl('updateData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'identity',
                    id: insertedIds.identity_id,
                    data: { language_ids: JSON.stringify(languageIds) }
                })
            });
            const updateResult = await updateResponse.json();
            console.log('✅ Langues ajoutées à identity:', languageIds, updateResult);
        } else {
            console.warn('⚠️ Aucune langue valide à ajouter');
        }
    }
    
    // ÉTAPE 9: Ajouter les hobbies à identity
    if (dataByTable.collections && dataByTable.collections.hobbies && dataByTable.collections.hobbies.length > 0) {
        console.log('9️⃣ Ajout des hobbies à identity...');
        console.log('📋 Hobbies bruts:', dataByTable.collections.hobbies);
        const hobbieIds = [];
        
        for (const hobby of dataByTable.collections.hobbies) {
            // Extraire la valeur correctement
            let hobbyValue = null;
            let hobbyId = null;
            
            if (typeof hobby === 'string') {
                hobbyValue = hobby;
            } else if (hobby && typeof hobby === 'object') {
                // Nouvelle structure : {id, value}
                hobbyValue = hobby.value || hobby.title || hobby.name;
                hobbyId = hobby.id || null;
            }
            
            console.log('🔍 Hobby extrait:', hobbyValue, '(ID existant:', hobbyId, ') depuis:', hobby);
            
            if (!hobbyValue || hobbyValue === '--' || hobbyValue === 'Array') continue;
            
            // Si l'ID existe déjà, on le réutilise au lieu de créer un nouveau
            if (hobbyId && hobbyId !== 'newId') {
                hobbieIds.push(hobbyId);
                console.log(`♻️ Hobby "${hobbyValue}" réutilisé avec ID existant:`, hobbyId);
                continue;
            }
            
            const hobbyResponse = await fetch(getFunctionUrl('insertData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'hobbie',
                    data: { title: hobbyValue }
                })
            });
            
            const hobbyResult = await hobbyResponse.json();
            if (hobbyResult.success) {
                hobbieIds.push(hobbyResult.id);
                console.log(`✅ Hobby "${hobbyValue}" créé avec ID:`, hobbyResult.id);
            } else {
                console.error(`❌ Échec insertion hobby "${hobbyValue}":`, hobbyResult);
            }
        }
        
        if (hobbieIds.length > 0) {
            const updateResponse = await fetch(getFunctionUrl('updateData.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table: 'identity',
                    id: insertedIds.identity_id,
                    data: { hobbie_ids: JSON.stringify(hobbieIds) }
                })
            });
            const updateResult = await updateResponse.json();
            console.log('✅ Hobbies ajoutés à identity:', hobbieIds, updateResult);
        } else {
            console.warn('⚠️ Aucun hobby valide à ajouter');
        }
    }
    
    // ÉTAPE 10: Ajouter les personalities à candidate
    if (insertedIds.personality_ids) {
        console.log('🔟 Ajout des personalities à candidate...');
        console.log('📋 Personality IDs à ajouter:', insertedIds.personality_ids);
        const updateResponse = await fetch(getFunctionUrl('updateData.php'), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                table: 'candidate',
                id: candidateId,
                data: { personality_ids: insertedIds.personality_ids }
            })
        });
        const updateResult = await updateResponse.json();
        console.log('✅ Personalities ajoutées à candidate:', updateResult);
    } else {
        console.warn('⚠️ Aucune personality à ajouter au candidat');
    }
    
    // Mettre à jour le conteneur principal
    const cvContainer = document.querySelector('.cv-container');
    if (cvContainer) {
        cvContainer.setAttribute('data-id', candidateId);
        cvContainer.setAttribute('data-mode', 'update');
    }
    
    console.log('🎉 Candidat créé avec succès! ID:', candidateId);
    
    // Rediriger vers la page d'édition du candidat

    setTimeout(() => {
        window.location.href = `/user-account/connection`;
    }, 1500);
    
    return insertedIds;
}

/**
 * Met à jour toutes les données en base de données (mode update)
 */
async function updateAllData(dataByTable, candidateId) {
    for (const [table, tableData] of Object.entries(dataByTable)) {
        if (table === 'candidate' || table === 'collections') continue;
        
        console.log(`UPDATE - Traitement table: ${table}`, tableData);
        
        if (tableData.items) {
            // Traiter les collections (skills, experiences, formations)
            for (const item of tableData.items) {
                // Traiter comme INSERT si l'ID est null, 'newId' ou vide
                if (item.id && item.id !== 'newId') {
                    // Update
                    console.log(`Update ${table} ID ${item.id}:`, item);
                    
                    const response = await fetch(getFunctionUrl('updateData.php'), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            table: table,
                            id: item.id,
                            data: item
                        })
                    });
                    
                    const result = await response.json();
                    console.log(`Résultat update ${table}:`, result);
                } else {
                    // Insert new item
                    console.log(`Insert nouveau ${table}:`, item);
                    
                    const response = await fetch(getFunctionUrl('insertData.php'), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            table: table,
                            data: item
                        })
                    });
                    
                    const result = await response.json();
                    console.log(`Résultat insert ${table}:`, result);
                    
                    // Stocker le nouvel ID retourné dans l'objet item
                    if (result.success && result.id) {
                        item.id = result.id;
                    }
                }
            }
        } else if (tableData.columns) {
            // Structure avec colonnes (format save.js)
            const dataToUpdate = {};
            for (const [column, columnData] of Object.entries(tableData.columns)) {
                if (columnData.type === 'collection') {
                    // Gérer les collections
                    console.log(`UPDATE Collection ${column}:`, columnData.values);
                    const collectionIds = await updateCollection(column, columnData.values, tableData.id);
                    dataToUpdate[column.replace(/s$/, '') + '_ids'] = JSON.stringify(collectionIds);
                } else {
                    // Convertir "true"/"false" en 1/0 pour les booléens
                    let value = columnData.value;
                    if (column === 'vehicule') {
                        value = value === 'true' ? 1 : 0;
                    }
                    dataToUpdate[column] = value;
                }
            }
            
            if (Object.keys(dataToUpdate).length > 0 && tableData.id) {
                console.log(`Update table ${table} ID ${tableData.id}:`, dataToUpdate);
                
                const response = await fetch(getFunctionUrl('updateData.php'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        table: table,
                        id: tableData.id,
                        data: dataToUpdate
                    })
                });
                
                const result = await response.json();
                console.log(`Résultat update ${table}:`, result);
            }
        } else {
            // Structure plate (format add-cv.js) - récupérer l'ID depuis le DOM
            const dataToUpdate = {};
            let recordId = null;
            
            // Chercher l'ID depuis le DOM pour cette table
            const elementWithId = document.querySelector(`[data-table="${table}"][data-id]`);
            if (elementWithId) {
                recordId = elementWithId.getAttribute('data-id');
            }
            
            // Traiter chaque colonne
            for (const [column, value] of Object.entries(tableData)) {
                // Convertir "true"/"false" en 1/0 pour les booléens
                let finalValue = value;
                if (column === 'vehicule') {
                    finalValue = value === 'true' ? 1 : 0;
                }
                dataToUpdate[column] = finalValue;
            }
            
            if (Object.keys(dataToUpdate).length > 0 && recordId) {
                console.log(`Update table ${table} ID ${recordId} (structure plate):`, dataToUpdate);
                
                const response = await fetch(getFunctionUrl('updateData.php'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        table: table,
                        id: recordId,
                        data: dataToUpdate
                    })
                });
                
                const result = await response.json();
                console.log(`Résultat update ${table}:`, result);
            }
        }
    }
    
    // Traiter les collections multipleDataToEdit
    if (dataByTable.collections) {
        await updateCollections(dataByTable.collections, dataByTable);
    }
    
    // Mettre à jour skill_ids dans la table candidate
    await updateSkillIds(dataByTable, candidateId);
    
    // Mettre à jour les data-id des skills dans le DOM
    updateSkillDataIds(dataByTable);
    
    // Insérer les nouvelles expériences avec leurs données complètes (dates + missions)
    await insertNewExperiences(candidateId);
    
    // Mettre à jour experience_ids dans la table candidate
    await updateExperienceIds(dataByTable, candidateId);
    
    // Mettre à jour les data-id des experiences dans le DOM
    updateExperienceDataIds(dataByTable);
    
    // Mettre à jour les data-id des missions dans le DOM (AVANT updateMissionIds)
    updateMissionDataIds(dataByTable);
    
    // Mettre à jour mission_ids pour chaque expérience existante (APRÈS updateMissionDataIds)
    await updateMissionIds(dataByTable);
    
    // Insérer les nouvelles formations avec leurs données complètes (dates + knowledges)
    await insertNewFormations(candidateId);
    
    // Mettre à jour formation_ids dans la table candidate
    await updateFormationIds(dataByTable, candidateId);
    
    // Mettre à jour les dates des formations existantes depuis les attributs data-*
    await updateFormationDates();
    
    // Mettre à jour les data-id des formations dans le DOM
    updateFormationDataIds(dataByTable);
    
    // Mettre à jour les data-id des knowledges dans le DOM (AVANT updateKnowledgeIds)
    updateKnowledgeDataIds(dataByTable);
    
    // Mettre à jour knowledge pour chaque formation existante (APRÈS updateKnowledgeDataIds)
    await updateKnowledgeIds(dataByTable);
}

/**
 * Met à jour les data-id des skills dans le DOM
 */
function updateSkillDataIds(dataByTable) {
    if (!dataByTable.skill || !dataByTable.skill.items) {
        return;
    }
    
    const skillsSection = document.querySelector('[data-editor="skills"]');
    if (!skillsSection) return;
    
    const skillItems = skillsSection.querySelectorAll('li');
    
    // Mettre à jour les data-id pour chaque skill
    dataByTable.skill.items.forEach((skill, index) => {
        if (skillItems[index] && skill.id) {
            const titleSpan = skillItems[index].querySelector('[data-column="title"]');
            const yearSpan = skillItems[index].querySelector('[data-column="year"]');
            
            if (titleSpan) {
                titleSpan.setAttribute('data-id', skill.id);
            }
            if (yearSpan) {
                yearSpan.setAttribute('data-id', skill.id);
            }
        }
    });
    
    console.log('Skills data-id mis à jour dans le DOM');
}

/**
 * Met à jour les data-id des experiences dans le DOM
 */
function updateExperienceDataIds(dataByTable) {
    if (!dataByTable.experience || !dataByTable.experience.items) {
        return;
    }
    
    const expSection = document.querySelector('[data-editor="experience"]');
    if (!expSection) return;
    
    const expItems = expSection.querySelectorAll('.timeline-item');
    
    // Mettre à jour les data-id pour chaque expérience
    dataByTable.experience.items.forEach((exp, index) => {
        if (expItems[index] && exp.id) {
            // Mettre à jour le data-id du timeline-item
            expItems[index].setAttribute('data-id', exp.id);
            
            // Mettre à jour les attributs de date
            if (exp.start_date) {
                expItems[index].setAttribute('data-start-date', exp.start_date);
            }
            if (exp.end_date) {
                expItems[index].setAttribute('data-end-date', exp.end_date);
            }
            expItems[index].setAttribute('data-current', exp.current ? 'true' : 'false');
            
            // Mettre à jour les data-id des éléments internes
            const positionSpan = expItems[index].querySelector('[data-column="position"]');
            const companySpan = expItems[index].querySelector('[data-column="company"]');
            const locationSpan = expItems[index].querySelector('[data-column="location"]');
            
            if (positionSpan) {
                positionSpan.setAttribute('data-id', exp.id);
            }
            if (companySpan) {
                companySpan.setAttribute('data-id', exp.id);
            }
            if (locationSpan) {
                locationSpan.setAttribute('data-id', exp.id);
            }
        }
    });
    
    console.log('Experiences data-id mis à jour dans le DOM');
}

/**
 * Met à jour les data-id des formations dans le DOM
 */
function updateFormationDataIds(dataByTable) {
    if (!dataByTable.formation || !dataByTable.formation.items) {
        return;
    }
    
    const formSection = document.querySelector('[data-editor="formation"]');
    if (!formSection) return;
    
    const formItems = formSection.querySelectorAll('.timeline-item');
    
    // Mettre à jour les data-id pour chaque formation
    dataByTable.formation.items.forEach((form, index) => {
        if (formItems[index] && form.id) {
            console.log(`🔄 Mise à jour formation ${form.id}:`, {
                index: index,
                'form.start_date': form.start_date,
                'form.end_date': form.end_date,
                'form.current': form.current,
                'Avant - data-start-date': formItems[index].getAttribute('data-start-date'),
                'Avant - data-end-date': formItems[index].getAttribute('data-end-date'),
                'Avant - data-current': formItems[index].getAttribute('data-current')
            });
            
            // Mettre à jour le data-id du timeline-item
            formItems[index].setAttribute('data-id', form.id);
            
            // Mettre à jour les attributs de date
            if (form.start_date) {
                formItems[index].setAttribute('data-start-date', form.start_date);
            }
            if (form.end_date) {
                formItems[index].setAttribute('data-end-date', form.end_date);
            }
            formItems[index].setAttribute('data-current', form.current ? 'true' : 'false');
            
            console.log(`✅ Après mise à jour:`, {
                'Après - data-start-date': formItems[index].getAttribute('data-start-date'),
                'Après - data-end-date': formItems[index].getAttribute('data-end-date'),
                'Après - data-current': formItems[index].getAttribute('data-current')
            });
            
            // Mettre à jour les data-id des éléments internes
            const titleSpan = formItems[index].querySelector('[data-column="title"]');
            const schoolSpan = formItems[index].querySelector('[data-column="school"]');
            const locationSpan = formItems[index].querySelector('[data-column="location"]');
            
            if (titleSpan) {
                titleSpan.setAttribute('data-id', form.id);
            }
            if (schoolSpan) {
                schoolSpan.setAttribute('data-id', form.id);
            }
            if (locationSpan) {
                locationSpan.setAttribute('data-id', form.id);
            }
        }
    });
    
    console.log('Formations data-id mis à jour dans le DOM');
}

/**
 * Met à jour les data-id des missions dans le DOM pour chaque expérience
 */
function updateMissionDataIds(dataByTable) {
    if (!dataByTable.collections || !dataByTable.collections.missions) {
        return;
    }
    
    const expSection = document.querySelector('[data-editor="experience"]');
    if (!expSection) return;
    
    const expItems = expSection.querySelectorAll('.timeline-item');
    
    // Mettre à jour data-collection pour chaque expérience
    expItems.forEach((item, expIndex) => {
        const expId = item.getAttribute('data-id');
        if (!expId) return;
        
        const missionsEl = item.querySelector('.missions.multipleDataToEdit');
        if (!missionsEl) return;
        
        const dataCollection = missionsEl.getAttribute('data-collection');
        if (!dataCollection) return;
        
        try {
            // Récupérer uniquement les missions de cette expérience depuis la collection globale
            const missionsForThisExp = dataByTable.collections.missions.filter(
                m => m.parentExperienceId === expId
            );
            
            // Extraire les IDs mis à jour
            const updatedIds = missionsForThisExp
                .map(m => m.id)
                .filter(id => id !== null && id !== 'newId');
            
            // Mettre à jour data-collection avec les vrais IDs
            const formattedIds = '[' + updatedIds.join(',') + ']';
            missionsEl.setAttribute('data-collection', formattedIds);
            
            console.log(`Updated mission data-collection for experience ${expId}:`, formattedIds);
        } catch (e) {
            console.error('Erreur parsing mission data-collection:', e);
        }
    });
    
    console.log('Missions data-collection mis à jour dans le DOM');
}

/**
 * Insère les nouvelles expériences avec toutes leurs données (dates + missions)
 */
async function insertNewExperiences(candidateId) {
    const expSection = document.querySelector('[data-editor="experience"]');
    if (!expSection) {
        console.log('Section experience introuvable');
        return;
    }
    
    const expItems = expSection.querySelectorAll('.timeline-item');
    
    for (const item of expItems) {
        const expId = item.getAttribute('data-id');
        
        // Traiter uniquement les nouvelles expériences (data-id="newId")
        if (expId !== 'newId') {
            continue;
        }
        
        console.log('🆕 Nouvelle expérience détectée, insertion en cours...');
        
        // Récupérer toutes les données de l'expérience
        const periodEl = item.querySelector('.period');
        const position = periodEl?.querySelector('[data-column="position"]')?.getAttribute('data-value') || '';
        const company = periodEl?.querySelector('[data-column="company"]')?.getAttribute('data-value') || '';
        const location = periodEl?.querySelector('[data-column="location"]')?.getAttribute('data-value') || '';
        
        // Récupérer les dates depuis les attributs data-*
        const startDate = item.getAttribute('data-start-date') || null;
        const endDate = item.getAttribute('data-end-date') || null;
        const isCurrent = item.getAttribute('data-current') === 'true';
        
        // Récupérer les missions
        const missionsEl = item.querySelector('.missions.multipleDataToEdit');
        const missionItems = missionsEl?.querySelectorAll('.multipleDataToEditItem') || [];
        
        // Insérer d'abord les missions et récupérer leurs IDs
        const missionIds = [];
        for (const missionItem of missionItems) {
            const missionTitle = missionItem.textContent.trim();
            const missionId = missionItem.getAttribute('data-id');
            
            // Si c'est une nouvelle mission (newId), l'insérer
            if (missionId === 'newId') {
                console.log(`  📌 Insertion mission: ${missionTitle}`);
                
                const response = await fetch(getFunctionUrl('insertData.php'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        table: 'mission',
                        data: { title: missionTitle }
                    })
                });
                
                const result = await response.json();
                if (result.success && result.id) {
                    missionIds.push(result.id);
                    // Mettre à jour le data-id dans le DOM
                    missionItem.setAttribute('data-id', result.id);
                    console.log(`  ✅ Mission insérée avec ID: ${result.id}`);
                }
            } else {
                // Utiliser l'ID existant
                missionIds.push(parseInt(missionId));
            }
        }
        
        // Préparer les données de l'expérience
        const experienceData = {
            position: position,
            company: company,
            location: location,
            start_date: startDate,
            end_date: isCurrent ? null : endDate,
            current: isCurrent ? 1 : 0,
            missions: JSON.stringify(missionIds)
        };
        
        console.log('📋 Données de l\'expérience à insérer:', experienceData);
        
        // Insérer l'expérience
        const expResponse = await fetch(getFunctionUrl('insertData.php'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                table: 'experience',
                data: experienceData
            })
        });
        
        const expResult = await expResponse.json();
        console.log('📥 Résultat insertion expérience:', expResult);
        
        if (expResult.success && expResult.id) {
            const newExperienceId = expResult.id;
            console.log(`✅ Expérience insérée avec ID: ${newExperienceId}`);
            
            // Mettre à jour le data-id dans le DOM
            item.setAttribute('data-id', newExperienceId);
            
            // Mettre à jour les data-id des éléments internes
            const positionEl = periodEl?.querySelector('[data-column="position"]');
            const companyEl = periodEl?.querySelector('[data-column="company"]');
            const locationEl = periodEl?.querySelector('[data-column="location"]');
            
            if (positionEl) positionEl.setAttribute('data-id', newExperienceId);
            if (companyEl) companyEl.setAttribute('data-id', newExperienceId);
            if (locationEl) locationEl.setAttribute('data-id', newExperienceId);
            if (missionsEl) missionsEl.setAttribute('data-id', newExperienceId);
            
            // Mettre à jour data-collection des missions
            if (missionsEl && missionIds.length > 0) {
                missionsEl.setAttribute('data-collection', JSON.stringify(missionIds));
            }
            
            console.log(`🎉 Expérience complète insérée et DOM mis à jour !`);
        } else {
            console.error('❌ Erreur lors de l\'insertion de l\'expérience:', expResult);
        }
    }
}

/**
 * Met à jour les experience_ids dans la table candidate
 */
async function updateExperienceIds(dataByTable, candidateId) {
    const expSection = document.querySelector('[data-editor="experience"]');
    if (!expSection) {
        return;
    }
    
    // Récupérer tous les IDs d'expériences depuis le DOM (après insertion des nouvelles)
    const expItems = expSection.querySelectorAll('.timeline-item');
    const experienceIds = [];
    
    expItems.forEach(item => {
        const expId = item.getAttribute('data-id');
        // Ignorer les expériences qui ont encore newId (ne devrait pas arriver après insertNewExperiences)
        if (expId && expId !== 'newId') {
            experienceIds.push(parseInt(expId));
        }
    });
    
    if (experienceIds.length === 0) {
        console.log('Aucune expérience à lier au candidat');
        return;
    }
    
    // Mettre à jour experience_ids dans la table candidate
    const experienceIdsJson = JSON.stringify(experienceIds);
    
    console.log(`Updating candidate.experience_ids with:`, experienceIdsJson);
    
    const response = await fetch(getFunctionUrl('updateData.php'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            table: 'candidate',
            id: candidateId,
            data: {
                experience_ids: experienceIdsJson
            }
        })
    });
    
    const result = await response.json();
    console.log(`Résultat update candidate.experience_ids:`, result);
}

/**
 * Met à jour les dates des nouvelles formations (avec newId) après qu'elles aient obtenu leur ID
 */
async function updateNewFormationDates(dataByTable) {
    if (!dataByTable.formation || !dataByTable.formation.items) {
        return;
    }
    
    const formSection = document.querySelector('[data-editor="formation"]');
    if (!formSection) return;
    
    const formItems = formSection.querySelectorAll('.timeline-item');
    
    for (const item of formItems) {
        const formId = item.getAttribute('data-id');
        
        // Ne traiter que les formations qui ont maintenant un vrai ID (pas newId)
        if (!formId || formId === 'newId') {
            continue;
        }
        
        const startDate = item.getAttribute('data-start-date');
        const endDate = item.getAttribute('data-end-date');
        const isCurrent = item.getAttribute('data-current') === 'true';
        
        if (!startDate) continue;
        
        // Vérifier si cette formation était une nouvelle formation (recherche dans dataByTable)
        const formData = dataByTable.formation.items.find(f => f.id === formId);
        if (formData && formData.wasNew) {
            console.log(`Mise à jour des dates pour la nouvelle formation ${formId}`);
            
            const response = await fetch(getFunctionUrl('updateData.php'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    table: 'formation',
                    id: formId,
                    data: {
                        start_date: startDate,
                        end_date: isCurrent ? null : endDate,
                        current: isCurrent ? 1 : 0
                    }
                })
            });
            
            const result = await response.json();
            console.log(`Dates mises à jour pour formation ${formId}:`, result);
        }
    }
}

/**
 * Insère les nouvelles formations avec toutes leurs données (dates + knowledges)
 */
async function insertNewFormations(candidateId) {
    const formSection = document.querySelector('[data-editor="formation"]');
    if (!formSection) {
        console.log('Section formation introuvable');
        return;
    }
    
    const formItems = formSection.querySelectorAll('.timeline-item');
    
    for (const item of formItems) {
        const formId = item.getAttribute('data-id');
        
        // Traiter uniquement les nouvelles formations (data-id="newId")
        if (formId !== 'newId') {
            continue;
        }
        
        console.log('🆕 Nouvelle formation détectée, insertion en cours...');
        
        // Récupérer toutes les données de la formation
        const periodEl = item.querySelector('.period');
        const title = periodEl?.querySelector('[data-column="title"]')?.getAttribute('data-value') || '';
        const school = periodEl?.querySelector('[data-column="school"]')?.getAttribute('data-value') || '';
        const location = periodEl?.querySelector('[data-column="location"]')?.getAttribute('data-value') || '';
        
        // Récupérer les dates depuis les attributs data-*
        const startDate = item.getAttribute('data-start-date') || null;
        const endDate = item.getAttribute('data-end-date') || null;
        const isCurrent = item.getAttribute('data-current') === 'true';
        
        // Récupérer les knowledges
        const knowledgesEl = item.querySelector('.multipleDataToEdit[data-column="knowledge"]');
        const knowledgeItems = knowledgesEl?.querySelectorAll('.multipleDataToEditItem') || [];
        
        // Insérer d'abord les knowledges et récupérer leurs IDs
        const knowledgeIds = [];
        for (const knowledgeItem of knowledgeItems) {
            const knowledgeTitle = knowledgeItem.textContent.trim();
            const knowledgeId = knowledgeItem.getAttribute('data-id');
            
            // Si c'est une nouvelle connaissance (newId), l'insérer
            if (knowledgeId === 'newId') {
                console.log(`  📚 Insertion knowledge: ${knowledgeTitle}`);
                
                const response = await fetch(getFunctionUrl('insertData.php'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        table: 'knowledge',
                        data: { title: knowledgeTitle }
                    })
                });
                
                const result = await response.json();
                if (result.success && result.id) {
                    knowledgeIds.push(result.id);
                    // Mettre à jour le data-id dans le DOM
                    knowledgeItem.setAttribute('data-id', result.id);
                    console.log(`  ✅ Knowledge inséré avec ID: ${result.id}`);
                }
            } else {
                // Utiliser l'ID existant
                knowledgeIds.push(parseInt(knowledgeId));
            }
        }
        
        // Préparer les données de la formation
        const formationData = {
            title: title,
            school: school,
            location: location,
            start_date: startDate,
            end_date: isCurrent ? null : endDate,
            current: isCurrent ? 1 : 0,
            knowledge: JSON.stringify(knowledgeIds)
        };
        
        console.log('📋 Données de la formation à insérer:', formationData);
        
        // Insérer la formation
        const formResponse = await fetch(getFunctionUrl('insertData.php'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                table: 'formation',
                data: formationData
            })
        });
        
        const formResult = await formResponse.json();
        console.log('📥 Résultat insertion formation:', formResult);
        
        if (formResult.success && formResult.id) {
            const newFormationId = formResult.id;
            console.log(`✅ Formation insérée avec ID: ${newFormationId}`);
            
            // Mettre à jour le data-id dans le DOM
            item.setAttribute('data-id', newFormationId);
            
            // Mettre à jour les data-id des éléments internes
            const titleEl = periodEl?.querySelector('[data-column="title"]');
            const schoolEl = periodEl?.querySelector('[data-column="school"]');
            const locationEl = periodEl?.querySelector('[data-column="location"]');
            
            if (titleEl) titleEl.setAttribute('data-id', newFormationId);
            if (schoolEl) schoolEl.setAttribute('data-id', newFormationId);
            if (locationEl) locationEl.setAttribute('data-id', newFormationId);
            if (knowledgesEl) knowledgesEl.setAttribute('data-id', newFormationId);
            
            // Mettre à jour data-collection des knowledges
            if (knowledgesEl && knowledgeIds.length > 0) {
                knowledgesEl.setAttribute('data-collection', JSON.stringify(knowledgeIds));
            }
            
            console.log(`🎉 Formation complète insérée et DOM mis à jour !`);
        } else {
            console.error('❌ Erreur lors de l\'insertion de la formation:', formResult);
        }
    }
}

/**
 * Met à jour les formation_ids dans la table candidate
 */
async function updateFormationIds(dataByTable, candidateId) {
    const formSection = document.querySelector('[data-editor="formation"]');
    if (!formSection) {
        return;
    }
    
    // Récupérer tous les IDs de formations depuis le DOM (après insertion des nouvelles)
    const formItems = formSection.querySelectorAll('.timeline-item');
    const formationIds = [];
    
    formItems.forEach(item => {
        const formId = item.getAttribute('data-id');
        // Ignorer les formations qui ont encore newId (ne devrait pas arriver après insertNewFormations)
        if (formId && formId !== 'newId') {
            formationIds.push(parseInt(formId));
        }
    });
    
    if (formationIds.length === 0) {
        console.log('Aucune formation à lier au candidat');
        return;
    }
    
    // Mettre à jour formation_ids dans la table candidate
    const formationIdsJson = JSON.stringify(formationIds);
    
    console.log(`Updating candidate.formation_ids with:`, formationIdsJson);
    
    const response = await fetch(getFunctionUrl('updateData.php'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            table: 'candidate',
            id: candidateId,
            data: {
                formation_ids: formationIdsJson
            }
        })
    });
    
    const result = await response.json();
    console.log(`Résultat update candidate.formation_ids:`, result);
}

/**
 * Met à jour les dates des formations en lisant les attributs data-* du DOM
 */
async function updateFormationDates() {
    const formSection = document.querySelector('[data-editor="formation"]');
    if (!formSection) {
        console.log('Section formation introuvable');
        return;
    }
    
    const formItems = formSection.querySelectorAll('.timeline-item');
    
    for (const item of formItems) {
        const formId = item.getAttribute('data-id');
        
        // Ignorer les formations avec newId (elles n'ont pas encore d'ID en DB)
        if (!formId || formId === 'newId') {
            console.log('⏭️ Formation sans ID ou avec newId, dates seront mises à jour après insertion');
            continue;
        }
        
        const startDate = item.getAttribute('data-start-date') || null;
        const endDate = item.getAttribute('data-end-date') || null;
        const isCurrent = item.getAttribute('data-current') === 'true';
        
        console.log(`📅 Mise à jour dates formation ${formId}:`, {
            start_date: startDate,
            end_date: endDate,
            current: isCurrent
        });
        
        const response = await fetch(getFunctionUrl('updateData.php'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                table: 'formation',
                id: formId,
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    current: isCurrent ? 1 : 0
                }
            })
        });
        
        const result = await response.json();
        console.log(`✅ Résultat update formation ${formId} dates:`, result);
    }
}

/**
 * Met à jour les skill_ids dans la table candidate
 */
async function updateSkillIds(dataByTable, candidateId) {
    // Vérifier si des skills existent
    if (!dataByTable.skill || !dataByTable.skill.items) {
        return;
    }
    
    // Collecter tous les IDs de skills
    const skillIds = dataByTable.skill.items
        .map(skill => skill.id)
        .filter(id => id); // Filtrer les IDs vides/null
    
    if (skillIds.length === 0) {
        return;
    }
    
    // Mettre à jour skill_ids dans la table candidate
    const skillIdsJson = JSON.stringify(skillIds);
    
    console.log(`Updating candidate.skill_ids with:`, skillIdsJson);
    
    const response = await fetch(getFunctionUrl('updateData.php'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            table: 'candidate',
            id: candidateId,
            data: {
                skill_ids: skillIdsJson
            }
        })
    });
    
    const result = await response.json();
    console.log(`Résultat update candidate.skill_ids:`, result);
}

/**
 * Met à jour les mission_ids pour chaque expérience dans la table experience
 */
async function updateMissionIds(dataByTable) {
    // Vérifier si la collection missions existe
    if (!dataByTable.collections || !dataByTable.collections.missions) {
        return;
    }
    
    const expSection = document.querySelector('[data-editor="experience"]');
    if (!expSection) return;
    
    const expItems = expSection.querySelectorAll('.timeline-item');
    
    // Pour chaque expérience, mettre à jour mission_ids
    expItems.forEach(async (item, index) => {
        const expId = item.getAttribute('data-id');
        if (!expId) return;
        
        const missionsEl = item.querySelector('.missions.multipleDataToEdit');
        if (!missionsEl) return;
        
        const dataCollection = missionsEl.getAttribute('data-collection');
        if (!dataCollection) return;
        
        try {
            // Parser les mission IDs pour cette expérience
            const missionIds = JSON.parse(dataCollection);
            
            // Filtrer les IDs valides
            const validIds = missionIds.filter(id => id !== null && id !== 'newId');
            
            if (validIds.length === 0) {
                console.log(`Aucune mission valide pour l'expérience ${expId}`);
                return;
            }
            
            const missionIdsJson = JSON.stringify(validIds);
            
            console.log(`Updating experience ${expId}.missions with:`, missionIdsJson);
            
            // Mettre à jour missions dans la table experience
            const response = await fetch(getFunctionUrl('updateData.php'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    table: 'experience',
                    id: expId,
                    data: {
                        missions: missionIdsJson
                    }
                })
            });
            
            const result = await response.json();
            console.log(`Résultat update experience.missions:`, result);
            
        } catch (e) {
            console.error('Erreur parsing mission_ids:', e);
        }
    });
}

/**
 * Met à jour les data-id des knowledges dans le DOM pour chaque formation
 */
function updateKnowledgeDataIds(dataByTable) {
    if (!dataByTable.collections || !dataByTable.collections.knowledges) {
        return;
    }
    
    const formSection = document.querySelector('[data-editor="formation"]');
    if (!formSection) return;
    
    const formItems = formSection.querySelectorAll('.timeline-item');
    
    // Mettre à jour data-collection pour chaque formation
    formItems.forEach((item, formIndex) => {
        const formId = item.getAttribute('data-id');
        if (!formId) return;
        
        const knowledgesEl = item.querySelector('.multipleDataToEdit[data-column="knowledge"]');
        if (!knowledgesEl) return;
        
        const dataCollection = knowledgesEl.getAttribute('data-collection');
        if (!dataCollection) return;
        
        try {
            // Récupérer uniquement les knowledges de cette formation depuis la collection globale
            const knowledgesForThisForm = dataByTable.collections.knowledges.filter(
                k => k.parentFormationId === formId
            );
            
            // Extraire les IDs mis à jour
            const updatedIds = knowledgesForThisForm
                .map(k => k.id)
                .filter(id => id !== null && id !== 'newId');
            
            // Mettre à jour data-collection avec les vrais IDs
            const formattedIds = '[' + updatedIds.join(',') + ']';
            knowledgesEl.setAttribute('data-collection', formattedIds);
            
            console.log(`Updated knowledge data-collection for formation ${formId}:`, formattedIds);
        } catch (e) {
            console.error('Erreur parsing knowledge data-collection:', e);
        }
    });
    
    console.log('Knowledges data-collection mis à jour dans le DOM');
}

/**
 * Met à jour les knowledge pour chaque formation dans la table formation
 */
async function updateKnowledgeIds(dataByTable) {
    // Vérifier si la collection knowledges existe
    if (!dataByTable.collections || !dataByTable.collections.knowledges) {
        return;
    }
    
    const formSection = document.querySelector('[data-editor="formation"]');
    if (!formSection) return;
    
    const formItems = formSection.querySelectorAll('.timeline-item');
    
    // Pour chaque formation, mettre à jour knowledge
    formItems.forEach(async (item, index) => {
        const formId = item.getAttribute('data-id');
        if (!formId) return;
        
        const knowledgesEl = item.querySelector('.multipleDataToEdit[data-column="knowledge"]');
        if (!knowledgesEl) return;
        
        const dataCollection = knowledgesEl.getAttribute('data-collection');
        if (!dataCollection) return;
        
        try {
            // Parser les knowledge IDs pour cette formation
            const knowledgeIds = JSON.parse(dataCollection);
            
            // Filtrer les IDs valides
            const validIds = knowledgeIds.filter(id => id !== null && id !== 'newId');
            
            if (validIds.length === 0) {
                console.log(`Aucune connaissance valide pour la formation ${formId}`);
                return;
            }
            
            const knowledgeIdsJson = JSON.stringify(validIds);
            
            console.log(`Updating formation ${formId}.knowledge with:`, knowledgeIdsJson);
            
            // Mettre à jour knowledge dans la table formation
            const response = await fetch(getFunctionUrl('updateData.php'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    table: 'formation',
                    id: formId,
                    data: {
                        knowledge: knowledgeIdsJson
                    }
                })
            });
            
            const result = await response.json();
            console.log(`Résultat update formation.knowledge:`, result);
            
        } catch (e) {
            console.error('Erreur parsing knowledge_ids:', e);
        }
    });
}

/**
 * Met à jour ou insère les collections (languages, personalities, hobbies, etc.)
 */
async function updateCollections(collections, dataByTable) {
    // Map pour stocker les nouveaux IDs par type de collection
    const newIdsMap = {
        languages: [],
        personalities: [],
        hobbies: [],
        missions: [],
        knowledges: []
    };
    
    for (const [collectionName, items] of Object.entries(collections)) {
        if (!items || items.length === 0) continue;
        
        console.log(`UPDATE - Traitement collection: ${collectionName}`, items);
        
        for (let i = 0; i < items.length; i++) {
            const item = items[i];
            if (!item.value) continue;
            
            const dataToSave = {
                [item.column]: item.value
            };
            
            if (item.id === 'newId') {
                // INSERT nouvelle entrée
                console.log(`INSERT ${item.table}:`, dataToSave);
                
                const response = await fetch(getFunctionUrl('insertData.php'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        table: item.table,
                        data: dataToSave
                    })
                });
                
                const result = await response.json();
                console.log(`Résultat insert ${item.table}:`, result);
                
                // Stocker le nouvel ID retourné
                if (result.success && result.id) {
                    item.id = result.id; // Mettre à jour l'ID dans l'objet
                    newIdsMap[collectionName].push({ index: i, newId: result.id });
                }
                
            } else {
                // UPDATE entrée existante
                console.log(`UPDATE ${item.table} ID ${item.id}:`, dataToSave);
                
                const response = await fetch(getFunctionUrl('updateData.php'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        table: item.table,
                        id: item.id,
                        data: dataToSave
                    })
                });
                
                const result = await response.json();
                console.log(`Résultat update ${item.table}:`, result);
            }
        }
    }
    
    // Mettre à jour les data-collection dans le DOM avec les nouveaux IDs
    updateCollectionIdsInDOM(collections, newIdsMap);
    
    // Mettre à jour les colonnes _ids dans les tables parent (identity, candidate)
    await updateParentCollectionColumns(collections, dataByTable);
}

/**
 * Met à jour les attributs data-collection dans le DOM avec les nouveaux IDs
 */
function updateCollectionIdsInDOM(collections, newIdsMap) {
    const cvContainer = document.querySelector('.cv-container');
    if (!cvContainer) return;
    
    const columnMapping = {
        'languages': 'language_ids',
        'personalities': 'personality_ids',
        'hobbies': 'hobbie_ids',
        'missions': 'missions',
        'knowledges': 'knowledge'
    };
    
    for (const [collectionName, items] of Object.entries(collections)) {
        if (!items || items.length === 0) continue;
        
        const columnName = columnMapping[collectionName];
        if (!columnName) continue;
        
        // Trouver l'élément correspondant dans le DOM
        const element = cvContainer.querySelector(`[data-column="${columnName}"]`);
        if (!element) continue;
        
        // Construire le nouveau tableau d'IDs avec tous les IDs comme des nombres
        const newIdsArray = items.map(item => item.id);
        const formattedIds = '[' + newIdsArray.join(',') + ']';
        
        // Mettre à jour data-collection
        element.setAttribute('data-collection', formattedIds);
        console.log(`Updated ${columnName} data-collection:`, formattedIds);
    }
}

/**
 * Met à jour les colonnes _ids dans les tables parent (identity, candidate)
 */
async function updateParentCollectionColumns(collections, dataByTable) {
    // Récupérer l'ID du candidat depuis le DOM
    const cvContainer = document.querySelector('.cv-container');
    const candidateId = cvContainer ? cvContainer.getAttribute('data-id') : null;
    
    // Mapping des collections vers leurs tables et colonnes parent
    const parentMapping = {
        'languages': { table: 'identity', column: 'language_ids' },
        'hobbies': { table: 'identity', column: 'hobbie_ids' },
        'personalities': { table: 'candidate', column: 'personality_ids' }
        // Notez que 'missions' n'est pas ici car il sera géré séparément par updateMissionIds()
    };
    
    for (const [collectionName, items] of Object.entries(collections)) {
        if (!items || items.length === 0) continue;
        
        const mapping = parentMapping[collectionName];
        if (!mapping) continue;
        
        // Déterminer l'ID à utiliser
        let targetId = null;
        if (mapping.table === 'candidate') {
            // Pour la table candidate, utiliser l'ID depuis le DOM
            targetId = candidateId;
        } else {
            // Pour les autres tables (identity), chercher dans dataByTable
            const tableData = dataByTable[mapping.table];
            targetId = tableData ? tableData.id : null;
        }
        
        if (!targetId) {
            console.warn(`Impossible de trouver l'ID pour ${mapping.table}`);
            continue;
        }
        
        // Construire le tableau d'IDs (tous des nombres)
        const idsArray = items.map(item => item.id);
        const idsJson = JSON.stringify(idsArray);
        
        console.log(`Updating ${mapping.table}.${mapping.column} with:`, idsJson);
        
        // Mettre à jour la colonne dans la table parent
        const response = await fetch(getFunctionUrl('updateData.php'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                table: mapping.table,
                id: targetId,
                data: {
                    [mapping.column]: idsJson
                }
            })
        });
        
        const result = await response.json();
        console.log(`Résultat update ${mapping.table}.${mapping.column}:`, result);
    }
}

/**
 * Insère une collection de données (languages, hobbies, etc.)
 */
async function insertCollection(columnName, values) {
    const tableName = getCollectionTableName(columnName);
    const ids = [];
    
    for (const value of values) {
        // Mapper le nom de colonne selon la table
        const columnMapping = {
            'language': 'language_name',
            'personality': 'trait',
            'hobbie': 'title',
            'mission': 'title',
            'knowledge': 'title'
        };
        
        const dataColumn = columnMapping[tableName] || 'title';
        
        const response = await fetch(getFunctionUrl('insertData.php'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                table: tableName,
                data: { [dataColumn]: value }
            })
        });
        
        const result = await response.json();
        if (result.success) {
            ids.push(result.id);
        }
    }
    
    return ids;
}

/**
 * Met à jour une collection de données
 */
async function updateCollection(columnName, values, parentId) {
    // Pour simplifier, on supprime et recrée
    return await insertCollection(columnName, values);
}

/**
 * Retourne le nom de la table pour une collection
 */
function getCollectionTableName(columnName) {
    const mapping = {
        'languages': 'language',
        'hobbies': 'hobbie',
        'personalities': 'personality',
        'missions': 'mission',
        'knowledges': 'knowledge'
    };
    return mapping[columnName] || columnName.replace(/s$/, '');
}

/**
 * Collecte toutes les données des éléments dataToEdit et les groupe par table
 */
function collectDataByTable() {
    // Récupérer le conteneur CV
    const cvContainer = document.querySelector('.cv-container');
    if (!cvContainer) return {};
    
    // Sélectionner uniquement les .dataToEdit à l'intérieur du cv-container
    const dataElements = cvContainer.querySelectorAll('.dataToEdit');
    const dataByTable = {};
    
    // Initialiser la structure pour les collections
    const collections = {
        languages: [],
        personalities: [],
        hobbies: [],
        missions: [],
        knowledges: []
    };
    
    dataElements.forEach(element => {
        const table = element.getAttribute('data-table');
        const column = element.getAttribute('data-column');
        const id = element.getAttribute('data-id');
        
        if (!table || !column) return;
        
        // Récupérer la valeur depuis data-value
        let value = '';
        if (column === 'profile-photo') {
            const bgImage = window.getComputedStyle(element).backgroundImage;
            value = bgImage !== 'none' ? bgImage.slice(5, -2) : '';
        } else {
            // Utiliser data-value si disponible, sinon textContent
            value = element.getAttribute('data-value') || element.textContent.trim();
        }
        
        // Pour les tables avec plusieurs enregistrements (skill, experience, formation)
        // on doit grouper par table ET par id
        if (['skill', 'experience', 'formation'].includes(table)) {
            if (!dataByTable[table]) {
                dataByTable[table] = {
                    items: []
                };
            }
            
            // Convertir 'newId' en null pour forcer l'insertion
            const actualId = (id === 'newId' || !id) ? null : id;
            
            // Trouver ou créer l'item correspondant à cet id
            // IMPORTANT: Ne pas utiliser find() pour les items avec id null car null === null
            // Cela fusionnerait toutes les nouvelles entrées en une seule !
            let item;
            if (actualId === null) {
                // Pour les nouvelles entrées, toujours créer un nouvel item
                item = { id: null, wasNew: true };
                dataByTable[table].items.push(item);
            } else {
                // Pour les entrées existantes, chercher par ID
                item = dataByTable[table].items.find(i => i.id === actualId);
                if (!item) {
                    item = { id: actualId };
                    dataByTable[table].items.push(item);
                }
            }
            
            // Ajouter la colonne à cet item
            item[column] = value;
        } else {
            // Pour les autres tables (identity, demand), grouper normalement
            if (!dataByTable[table]) {
                dataByTable[table] = {
                    id: id || null,
                    columns: {}
                };
            } else if (id && !dataByTable[table].id) {
                dataByTable[table].id = id;
            }
            
            // Gérer les collections (valeurs séparées par des virgules)
            if (isCollectionColumn(column)) {
                const items = value.split(',').map(item => item.trim()).filter(item => item);
                dataByTable[table].columns[column] = {
                    type: 'collection',
                    values: items
                };
            } else {
                dataByTable[table].columns[column] = {
                    type: 'simple',
                    value: value
                };
            }
        }
    });
    
    // Traiter les multipleDataToEdit (collections avec IDs)
    const multipleDataElements = cvContainer.querySelectorAll('.multipleDataToEdit');
    console.log('🔎 Nombre de multipleDataToEdit trouvés:', multipleDataElements.length);
    
    multipleDataElements.forEach(element => {
        const table = element.getAttribute('data-table');
        const column = element.getAttribute('data-column');
        const dataCollection = element.getAttribute('data-collection');
        const textValue = element.textContent.trim();
        
        console.log('📋 Traitement multipleDataToEdit:', {
            column,
            dataCollection,
            textValue
        });
        
        // Ignorer les placeholders vides (--) mais traiter les collections modifiées
        if (!dataCollection && (!textValue || textValue === '--')) {
            console.log('⏭️ Ignoré car vide');
            return;
        }
        
        try {
            // Parser le tableau depuis data-collection (contient les IDs)
            let collectionIds = [];
            if (dataCollection && dataCollection !== '[]') {
                collectionIds = JSON.parse(dataCollection);
                console.log('✅ data-collection IDs parsés:', collectionIds);
            }
            
            // Récupérer les valeurs textuelles depuis le textContent
            let collectionArray = [];
            if (textValue && textValue !== '--') {
                const values = textValue.split(',').map(v => v.trim()).filter(v => v && v !== '--');
                
                // Créer un tableau avec les valeurs textuelles
                // Si on a des IDs, les associer aux valeurs
                if (collectionIds.length === values.length) {
                    collectionArray = values.map((value, index) => ({
                        id: collectionIds[index],
                        value: value
                    }));
                } else {
                    // Nouveaux éléments sans IDs
                    collectionArray = values.map(value => ({
                        id: null,
                        value: value
                    }));
                }
                console.log('✅ Collection créée avec textes:', collectionArray);
            }
            
            // Déterminer la clé de collection
            const collectionKey = getCollectionKeyFromColumn(column);
            
            if (collectionKey) {
                console.log(`📦 Ajout à collections.${collectionKey}:`, collectionArray);
                
                // Pour missions et knowledges, ajouter au lieu de remplacer
                if (collectionKey === 'missions' || collectionKey === 'knowledges') {
                    if (!collections[collectionKey]) {
                        collections[collectionKey] = [];
                    }
                    collections[collectionKey] = collections[collectionKey].concat(collectionArray);
                } else {
                    // Pour languages, hobbies, personalities
                    collections[collectionKey] = collectionArray;
                }
            }
        } catch (e) {
            console.error('❌ Erreur de parsing data-collection:', e, dataCollection);
        }
    });
    
    // Ajouter les collections à dataByTable
    dataByTable.collections = collections;
    
    // Post-traitement pour les experiences et formations : récupérer missions et knowledge depuis les spans
    addMissionsToExperiences(dataByTable);
    addKnowledgesToFormations(dataByTable);
    
    return dataByTable;
}

/**
 * Vérifie si une colonne contient une collection de données
 */
function isCollectionColumn(column) {
    const collectionColumns = [
        'languages',
        'hobbies',
        'personalities',
        'missions',
        'knowledges'
    ];
    return collectionColumns.includes(column);
}

/**
 * Retourne la clé de collection depuis le nom de colonne
 */
function getCollectionKeyFromColumn(column) {
    const mapping = {
        'language_ids': 'languages',
        'personality_ids': 'personalities',
        'hobbie_ids': 'hobbies',
        'missions': 'missions',
        'knowledge': 'knowledges'
    };
    return mapping[column];
}

/**
 * Retourne le nom de la table depuis le nom de colonne
 */
function getCollectionTableFromColumn(column) {
    const mapping = {
        'language_ids': 'language',
        'personality_ids': 'personality',
        'hobbie_ids': 'hobbie',
        'missions': 'mission',
        'knowledge': 'knowledge'
    };
    return mapping[column];
}

/**
 * Retourne le nom de la colonne dans la table de collection
 */
function getCollectionColumnName(column) {
    const mapping = {
        'language_ids': 'language_name',
        'personality_ids': 'trait',
        'hobbie_ids': 'title',
        'missions': 'title',
        'knowledge': 'title'
    };
    return mapping[column];
}

/**
 * Ajoute les missions aux expériences depuis les spans .missions
 */
function addMissionsToExperiences(dataByTable) {
    if (!dataByTable['experience']) return;
    
    const expSection = document.querySelector('[data-editor="experience"]');
    if (!expSection) return;
    
    const expItems = expSection.querySelectorAll('.timeline-item');
    
    // Utiliser l'index pour matcher les expériences au lieu de l'ID
    // car plusieurs nouvelles expériences peuvent avoir id: null
    expItems.forEach((item, index) => {
        const positionEl = item.querySelector('[data-column="position"]');
        
        if (positionEl) {
            // Utiliser l'index pour accéder à l'expérience correspondante
            // Les items sont dans le même ordre dans le DOM et dans dataByTable
            const experienceItem = dataByTable['experience'].items[index];
            
            if (experienceItem) {
                // Ne parser les dates depuis le texte QUE si elles n'existent pas déjà
                // (elles sont déjà lues depuis data-* dans collectExperiences)
                if (!experienceItem.start_date) {
                    const dateText = item.querySelector('.date')?.textContent.trim() || '';
                    const dateMatch = dateText.match(/(\d{4})\s*-\s*(.+?)\s*:/);
                    if (dateMatch) {
                        const startYear = dateMatch[1];
                        const endYear = dateMatch[2] === 'Présent' ? null : dateMatch[2];
                        const isCurrent = dateMatch[2] === 'Présent';
                        
                        experienceItem.start_date = `${startYear}-01-01`;
                        experienceItem.end_date = endYear ? `${endYear}-12-31` : null;
                        experienceItem.current = isCurrent ? 1 : 0;
                    }
                }
                
                // Les missions sont gérées via le système de collections multipleDataToEdit
                // Elles ne sont plus ajoutées ici
            }
        }
    });
}

/**
 * Ajoute les connaissances aux formations depuis les spans .learning
 */
function addKnowledgesToFormations(dataByTable) {
    if (!dataByTable['formation']) return;
    
    const formSection = document.querySelector('[data-editor="formation"]');
    if (!formSection) return;
    
    const formItems = formSection.querySelectorAll('.timeline-item');
    formItems.forEach(item => {
        const titleEl = item.querySelector('[data-column="title"]');
        const learningEl = item.querySelector('.learning');
        
        if (titleEl && learningEl) {
            const id = titleEl.getAttribute('data-id');
            const knowledgesText = learningEl.textContent.trim();
            const knowledges = knowledgesText ? knowledgesText.split(',').map(k => k.trim()) : [];
            
            // Trouver l'item correspondant
            const formationItem = dataByTable['formation'].items.find(i => i.id === id);
            if (formationItem) {
                formationItem.knowledge = JSON.stringify(knowledges);
                
                // Ne parser les dates depuis le texte QUE si elles n'existent pas déjà
                // (elles sont déjà lues depuis data-* dans collectFormations)
                if (!formationItem.start_date) {
                    const dateText = item.querySelector('.date')?.textContent.trim() || '';
                    const dateMatch = dateText.match(/(\d{4})\s*-\s*(.+?)\s*:/);
                    if (dateMatch) {
                        const startYear = dateMatch[1];
                        const endYear = dateMatch[2] === 'Présent' ? null : dateMatch[2];
                        const isCurrent = dateMatch[2] === 'Présent';
                        
                        formationItem.start_date = `${startYear}-01-01`;
                        formationItem.end_date = endYear ? `${endYear}-12-31` : null;
                        formationItem.current = isCurrent ? 1 : 0;
                    }
                }
            }
        }
    });
}

/**
 * Collecte toutes les compétences depuis la section skills
 */
function collectSkills() {
    const skillsSection = document.querySelector('[data-editor="skills"]');
    if (!skillsSection) return [];
    
    const skills = [];
    const skillItems = skillsSection.querySelectorAll('li');
    
    skillItems.forEach(item => {
        const titleEl = item.querySelector('[data-column="title"]');
        const yearEl = item.querySelector('[data-column="year"]');
        const isFavorite = item.textContent.includes('⭐');
        
        if (titleEl && yearEl) {
            skills.push({
                title: titleEl.textContent.trim(),
                year: yearEl.textContent.trim(),
                favorite: isFavorite
            });
        }
    });
    
    return skills;
}

/**
 * Collecte toutes les expériences depuis la section experience
 */
function collectExperiences() {
    const expSection = document.querySelector('[data-editor="experience"]');
    if (!expSection) return [];
    
    const experiences = [];
    const expItems = expSection.querySelectorAll('.timeline-item');
    
    expItems.forEach(item => {
        const periodEl = item.querySelector('.period');
        const missionsContainer = item.querySelector('.multipleDataToEdit[data-column="missions"]');
        
        if (!periodEl) return;
        
        // Récupérer les données depuis les éléments DOM
        const position = periodEl.querySelector('[data-column="position"]')?.textContent.trim() || '';
        const company = periodEl.querySelector('[data-column="company"]')?.textContent.trim() || '';
        const location = periodEl.querySelector('[data-column="location"]')?.textContent.trim() || '';
        const id = periodEl.querySelector('[data-column="position"]')?.getAttribute('data-id') || null;
        
        // Lire les dates depuis les attributs data-*
        const startDate = item.getAttribute('data-start-date') || '';
        const endDate = item.getAttribute('data-end-date') || null;
        const isCurrent = item.getAttribute('data-current') === 'true';
        
        // Récupérer les missions depuis les items de la collection
        const missionItems = missionsContainer?.querySelectorAll('.multipleDataToEditItem') || [];
        const missions = Array.from(missionItems).map(item => {
            const id = item.getAttribute('data-id');
            const title = item.textContent.trim();
            return { id: id === 'newId' ? 'newId' : parseInt(id), title: title };
        });
        
        if (position || company) {
            experiences.push({
                id: id,
                position: position,
                company: company,
                location: location,
                start_date: startDate || null,
                end_date: endDate,
                current: isCurrent ? 1 : 0,
                missions: JSON.stringify(missions)
            });
        }
    });
    
    return experiences;
}

/**
 * Collecte toutes les formations depuis la section formation
 */
function collectFormations() {
    const formSection = document.querySelector('[data-editor="formation"]');
    if (!formSection) return [];
    
    const formations = [];
    const formItems = formSection.querySelectorAll('.timeline-item');
    
    formItems.forEach(item => {
        const periodEl = item.querySelector('.period');
        const knowledgesContainer = item.querySelector('.multipleDataToEdit[data-column="knowledge"]');
        
        if (!periodEl) return;
        
        // Récupérer les données depuis les attributs data et les éléments DOM
        const title = periodEl.querySelector('[data-column="title"]')?.textContent.trim() || '';
        const school = periodEl.querySelector('[data-column="school"]')?.textContent.trim() || '';
        const location = periodEl.querySelector('[data-column="location"]')?.textContent.trim() || '';
        const id = periodEl.querySelector('[data-column="title"]')?.getAttribute('data-id') || null;
        
        // Lire les dates depuis les attributs data-*
        const startDate = item.getAttribute('data-start-date') || '';
        const endDate = item.getAttribute('data-end-date') || null;
        const isCurrent = item.getAttribute('data-current') === 'true';
        
        // Récupérer les connaissances depuis les items de la collection
        const knowledgeItems = knowledgesContainer?.querySelectorAll('.multipleDataToEditItem') || [];
        const knowledges = Array.from(knowledgeItems).map(item => {
            const id = item.getAttribute('data-id');
            const title = item.textContent.trim();
            return { id: id === 'newId' ? 'newId' : parseInt(id), title: title };
        });
        
        if (title || school) {
            const isNew = !id || id === 'newId';
            
            const formationData = {
                id: id === 'newId' ? null : id,
                wasNew: isNew,  // Marquer si c'est une nouvelle formation
                title: title,
                school: school,
                location: location,
                start_date: startDate || null,
                end_date: endDate,
                current: isCurrent ? 1 : 0,
                knowledge: JSON.stringify(knowledges)
            };
            
            console.log('🔍 Formation collectée:', {
                title: title,
                id: id,
                wasNew: isNew,
                'data-start-date (attribut)': item.getAttribute('data-start-date'),
                'data-end-date (attribut)': item.getAttribute('data-end-date'),
                'data-current (attribut)': item.getAttribute('data-current'),
                'Données envoyées à la BDD': formationData
            });
            
            formations.push(formationData);
        }
    });
    
    console.log('📦 Total formations collectées:', formations);
    return formations;
}

/**
 * Met à jour les data-id après un insert
 */
function updateDataIds(ids) {
    // Mettre à jour l'id du candidat
    if (ids.candidate) {
        const cvContainer = document.querySelector('.cv-container');
        if (cvContainer) {
            cvContainer.setAttribute('data-id', ids.candidate);
            cvContainer.setAttribute('data-mode', 'update');
        }
    }
    
    // Mettre à jour les autres IDs
    for (const [table, id] of Object.entries(ids)) {
        if (table === 'candidate') continue;
        
        const elements = document.querySelectorAll(`[data-table="${table}"]`);
        elements.forEach(el => {
            if (!el.getAttribute('data-id')) {
                el.setAttribute('data-id', id);
            }
        });
    }
}

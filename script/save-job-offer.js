// Script pour sauvegarder l'offre d'emploi

// Gestionnaire de clic sur le logo de la société pour uploader une nouvelle image
document.addEventListener('DOMContentLoaded', () => {
    const logoCircle = document.querySelector('.company-logo-circle');
    
    if (logoCircle) {
        // Créer un input file invisible pour la sélection du logo
        const logoInput = document.createElement('input');
        logoInput.type = 'file';
        logoInput.accept = 'image/*';
        logoInput.style.display = 'none';
        document.body.appendChild(logoInput);
        
        // Lorsque le logo est cliqué, ouvrir le sélecteur de fichier
        logoCircle.addEventListener('click', () => {
            logoInput.click();
        });
        
        // Lors de la sélection d'un fichier, afficher l'aperçu
        logoInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                // Vérifier que c'est une image
                if (!file.type.startsWith('image/')) {
                    alert('❌ Veuillez sélectionner un fichier image');
                    return;
                }
                
                // Prévisualiser l'image
                const reader = new FileReader();
                reader.onload = (event) => {
                    logoCircle.style.backgroundImage = `url('${event.target.result}')`;
                    logoCircle.style.backgroundSize = 'cover';
                    logoCircle.style.backgroundPosition = 'center';
                    
                    // Stocker le fichier pour l'upload lors de la sauvegarde
                    logoCircle.pendingLogoFile = file;
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Ajouter un style pointer au survol
        logoCircle.style.cursor = 'pointer';
        logoCircle.title = 'Cliquez pour changer le logo';
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const saveButton = document.getElementById('save-job-button');
    
    if (saveButton) {
        saveButton.addEventListener('click', async () => {
            try {
                // Récupérer le conteneur principal
                const jobContainer = document.querySelector('.job-offer-container');
                if (!jobContainer) {
                    alert('❌ Erreur: Conteneur offre d\'emploi introuvable');
                    return;
                }
                
                const jobId = jobContainer.getAttribute('data-id');
                const mode = jobContainer.getAttribute('data-mode') || 'update';
                
                // Collecter toutes les données
                const dataByTable = collectJobDataByTable();
                
                console.log('=== DEBUT SAUVEGARDE OFFRE ===');
                console.log('Données collectées:', dataByTable);
                console.log('Mode:', mode, 'Job ID:', jobId);
                
                // Sauvegarder les données selon le mode
                if (mode === 'insert') {
                    console.log('MODE INSERT');
                    await insertAllJobData(dataByTable);
                } else {
                    console.log('MODE UPDATE');
                    await updateAllJobData(dataByTable, jobId);
                }
                
                console.log('=== FIN SAUVEGARDE OFFRE ===');
                
                // Convertir l'offre en HTML statique
                if (jobId) {
                    await convertJobToHTML(jobId);
                }
                
                alert('✅ Offre d\'emploi sauvegardée avec succès !');
                
                // Recharger la page pour afficher les données mises à jour
                window.location.reload();
                
            } catch (error) {
                console.error('Erreur lors de la sauvegarde:', error);
                alert('❌ Erreur lors de la sauvegarde de l\'offre d\'emploi: ' + error.message);
            }
        });
    }
});

/**
 * Collecte toutes les données de l'offre d'emploi par table
 */
function collectJobDataByTable() {
    const dataByTable = {
        job: {},
        company: {},
        contact: {},
        requirement: {}
    };
    
    // Récupérer tous les éléments éditables
    const editableElements = document.querySelectorAll('[data-table][data-column]');
    
    editableElements.forEach(element => {
        const table = element.getAttribute('data-table');
        const column = element.getAttribute('data-column');
        const id = element.getAttribute('data-id');
        let value = element.getAttribute('data-value') || element.textContent.trim();
        
        // Vérifier que la table existe dans notre structure
        if (!dataByTable[table]) {
            console.warn(`Table ${table} non gérée, ignorée`);
            return;
        }
        
        // Initialiser l'objet de la table si nécessaire
        if (!dataByTable[table][id]) {
            dataByTable[table][id] = { id: id };
        }
        
        // Stocker la valeur
        dataByTable[table][id][column] = value;
        
        console.log(`📝 ${table}.${column} (ID: ${id}) = ${value}`);
    });
    
    return dataByTable;
}

/**
 * Insère toutes les données en base de données (mode insert)
 */
async function insertAllJobData(dataByTable) {
    const insertedIds = {};
    
    // Ordre d'insertion : contact, company, requirement, puis job
    const insertOrder = ['contact', 'company', 'requirement', 'job'];
    
    for (const table of insertOrder) {
        const tableData = dataByTable[table];
        
        for (const [recordId, data] of Object.entries(tableData)) {
            console.log(`📝 Insertion dans ${table}:`, data);
            
            try {
                const response = await fetch(getFunctionUrl('insertData.php'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        table: table,
                        data: data
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    insertedIds[table] = insertedIds[table] || {};
                    insertedIds[table][recordId] = result.id;
                    console.log(`✅ ${table} inséré avec ID:`, result.id);
                } else {
                    throw new Error(`Erreur insertion ${table}: ${result.error}`);
                }
            } catch (error) {
                console.error(`❌ Erreur insertion ${table}:`, error);
                throw error;
            }
        }
    }
    
    return insertedIds;
}

/**
 * Met à jour toutes les données en base de données (mode update)
 */
async function updateAllJobData(dataByTable, mainJobId) {
    // Vérifier si un nouveau logo a été sélectionné
    const logoCircle = document.querySelector('.company-logo-circle');
    if (logoCircle && logoCircle.pendingLogoFile) {
        console.log('📸 Nouveau logo détecté, upload en cours...');
        
        try {
            // Récupérer ou générer le media_code de la société
            let mediaCode = logoCircle.getAttribute('data-value');
            const companyId = logoCircle.getAttribute('data-id');
            
            // Si pas de media_code, en générer un nouveau
            if (!mediaCode || mediaCode === '') {
                mediaCode = generateMediaCode();
                console.log('🆕 Nouveau media_code généré:', mediaCode);
            }
            
            // Uploader le logo
            await uploadCompanyLogo(logoCircle.pendingLogoFile, mediaCode);
            
            // Mettre à jour le media_code dans les données de la société si nécessaire
            if (dataByTable.company && dataByTable.company[companyId]) {
                dataByTable.company[companyId].media_code = mediaCode;
            } else {
                // Créer l'entrée company si elle n'existe pas
                if (!dataByTable.company) {
                    dataByTable.company = {};
                }
                dataByTable.company[companyId] = {
                    id: companyId,
                    media_code: mediaCode
                };
            }
            
            console.log('✅ Logo uploadé et media_code mis à jour:', mediaCode);
            
            // Nettoyer le fichier en attente
            delete logoCircle.pendingLogoFile;
        } catch (error) {
            console.error('❌ Erreur lors de l\'upload du logo:', error);
            alert('⚠️ Erreur lors de l\'upload du logo: ' + error.message);
            // Continuer quand même avec la sauvegarde des autres données
        }
    }
    
    const updatePromises = [];
    
    // Parcourir chaque table
    for (const [table, records] of Object.entries(dataByTable)) {
        // Parcourir chaque enregistrement de la table
        for (const [recordId, data] of Object.entries(records)) {
            // Ne pas envoyer l'ID dans les données
            const { id, ...dataToUpdate } = data;
            
            // Ignorer si pas de données à mettre à jour
            if (Object.keys(dataToUpdate).length === 0) {
                console.log(`⏭️ Pas de données à mettre à jour pour ${table} ID ${recordId}`);
                continue;
            }
            
            console.log(`📝 Mise à jour ${table} ID ${recordId}:`, dataToUpdate);
            
            // Créer la promesse de mise à jour
            const updatePromise = fetch(getFunctionUrl('updateData.php'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    table: table,
                    id: recordId,
                    data: dataToUpdate
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    console.log(`✅ ${table} ID ${recordId} mis à jour`);
                    return { success: true, table, id: recordId };
                } else {
                    throw new Error(`Erreur mise à jour ${table} ID ${recordId}: ${result.error}`);
                }
            })
            .catch(error => {
                console.error(`❌ Erreur mise à jour ${table} ID ${recordId}:`, error);
                throw error;
            });
            
            updatePromises.push(updatePromise);
        }
    }
    
    // Attendre toutes les mises à jour
    if (updatePromises.length > 0) {
        const results = await Promise.all(updatePromises);
        console.log('✅ Toutes les mises à jour terminées:', results);
    } else {
        console.log('⚠️ Aucune donnée à mettre à jour');
    }
}

/**
 * Génère un code média unique pour stocker les fichiers de la société
 */
function generateMediaCode() {
    return 'MC' + Date.now() + Math.random().toString(36).substring(2, 9);
}

/**
 * Upload le logo de la société
 * @param {File} logoFile - Le fichier logo à uploader
 * @param {string} mediaCode - Le code média de la société
 * @returns {Promise} - Promesse de l'upload
 */
async function uploadCompanyLogo(logoFile, mediaCode) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        
        reader.onload = async (e) => {
            const base64Image = e.target.result;
            
            try {
                const response = await fetch(getFunctionUrl('uploadImage.php'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        imageData: base64Image,
                        mediaCode: mediaCode,
                        entityType: 'company',
                        type: 'logo'
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    console.log('✅ Logo uploadé avec succès');
                    resolve(result);
                } else {
                    console.error('❌ Erreur upload logo:', result.error);
                    reject(new Error(result.error));
                }
            } catch (error) {
                console.error('❌ Erreur réseau upload logo:', error);
                reject(error);
            }
        };
        
        reader.onerror = () => {
            reject(new Error('Erreur lecture fichier'));
        };
        
        reader.readAsDataURL(logoFile);
    });
}

/**
 * Bouton reset
 */
document.addEventListener('DOMContentLoaded', () => {
    const resetButton = document.getElementById('reset-job-button');
    
    if (resetButton) {
        resetButton.addEventListener('click', () => {
            if (confirm('⚠️ Êtes-vous sûr de vouloir recharger la page ? Les modifications non sauvegardées seront perdues.')) {
                window.location.reload();
            }
        });
    }
});

/**
 * Bouton print
 */
document.addEventListener('DOMContentLoaded', () => {
    const printButton = document.getElementById('print-job-button');
    
    if (printButton) {
        printButton.addEventListener('click', () => {
            window.print();
        });
    }
});

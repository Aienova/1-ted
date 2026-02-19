// Script pour ajouter une nouvelle offre d'emploi
document.addEventListener('DOMContentLoaded', () => {
    const addButton = document.getElementById('add-job-button');
    
    if (addButton) {
        addButton.addEventListener('click', async () => {
            console.log('🆕 Ajout d\'une nouvelle offre d\'emploi...');
            
            try {
                // Collecter les données du formulaire
                const dataByTable = collectDataByTable();
                
                // Insérer toutes les données
                await insertNewJob(dataByTable);
                
                alert('✅ Offre d\'emploi créée avec succès!');
            } catch (error) {
                console.error('❌ Erreur lors de l\'ajout:', error);
                alert('❌ Erreur lors de l\'ajout de l\'offre: ' + error.message);
            }
        });
    }
});

/**
 * Insère une nouvelle offre d'emploi avec toutes ses données
 */
async function insertNewJob(dataByTable) {
    const insertedIds = {};
    
    console.log('📦 Données à insérer:', dataByTable);
    
    // ==================== PHASE 1: CRÉER LES ENTITÉS LIÉES ====================
    
    // ÉTAPE 1: Insérer company
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
    } else {
        throw new Error('Les informations de l\'entreprise sont requises');
    }
    
    // ÉTAPE 2: Insérer contact
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
    
    // Afficher la popup d'inscription pour l'entreprise
    try {
        console.log('📝 Affichage de la popup d\'inscription entreprise...');
        await registerPopupCompany(insertedIds.company_id, insertedIds.contact_id);
        console.log('✅ Inscription terminée');
    } catch (error) {
        console.log('⚠️ Inscription annulée ou passée:', error.message);
    }
    
    // Convertir l'offre en HTML statique
    setTimeout(async () => {
        try {
            await convertJobToHTML(jobId);
            console.log('✅ HTML généré avec succès');
        } catch (error) {
            console.error('⚠️ Erreur lors de la génération HTML:', error);
        }
        
        // Rediriger vers la page d'édition de l'offre
        window.location.href = getAbsolutePath(`user-account/connection`);
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

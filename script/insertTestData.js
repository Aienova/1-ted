// Script pour insérer des données de test dans le CV (sans sauvegarder en base)
document.addEventListener('DOMContentLoaded', () => {
    const testButton = document.getElementById('test-cv-button');
    
    if (testButton) {
        testButton.addEventListener('click', () => {
            console.log('🧪 Insertion de données de test...');
            insertTestData();
        });
    }
});

function insertTestData() {
    // Données de test
    const testData = {
        // Identity
        first_name: 'Jean',
        last_name: 'Dupont',
        job_title: 'Développeur Full Stack Senior',
        birthdate: '15/03/1990',
        location: 'Paris, France',
        phone: '06 12 34 56 78',
        email: 'jean.dupont@example.com',
        description: 'Développeur passionné avec 8 ans d\'expérience dans le développement web et mobile. Expert en JavaScript, Python et PHP. Capacité à travailler en équipe et à mener des projets complexes.',
        vehicule: 'Oui',
        degree_id: '3', // Bac+5
        
        // Demand
        contract_type: 'CDI',
        salary: '50000',
        
        // Collections
        languages: [
            { title: 'Français' },
            { title: 'Anglais' },
            { title: 'Espagnol' }
        ],
        hobbies: [
            { title: 'Développement Open Source' },
            { title: 'Course à pied' },
            { title: 'Photographie' }
        ],
        personalities: [
            { title: 'Créatif' },
            { title: 'Rigoureux' },
            { title: 'Autonome' }
        ],
        
        // Skills
        skills: [
            { title: 'JavaScript', year: '8' },
            { title: 'React', year: '5' },
            { title: 'Node.js', year: '6' },
            { title: 'PHP', year: '7' },
            { title: 'Python', year: '4' }
        ],
        
        // Experiences
        experiences: [
            {
                position: 'Lead Developer',
                company: 'TechCorp',
                location: 'Paris',
                start_date: '2020-01',
                end_date: null,
                current: true,
                missions: ['Développement d\'une plateforme SaaS', 'Management d\'une équipe de 5 développeurs', 'Architecture microservices']
            },
            {
                position: 'Développeur Full Stack',
                company: 'WebAgency',
                location: 'Lyon',
                start_date: '2017-06',
                end_date: '2019-12',
                current: false,
                missions: ['Développement de sites web', 'Intégration d\'APIs REST', 'Maintenance et optimisation']
            }
        ],
        
        // Formations
        formations: [
            {
                title: 'Master Informatique',
                school: 'Université Paris-Saclay',
                location: 'Paris',
                start_date: '2013-09',
                end_date: '2015-06',
                current: false,
                knowledges: ['Algorithmique avancée', 'Bases de données', 'Architecture logicielle']
            },
            {
                title: 'Licence Informatique',
                school: 'Université de Lyon',
                location: 'Lyon',
                start_date: '2010-09',
                end_date: '2013-06',
                current: false,
                knowledges: ['Programmation orientée objet', 'Réseaux', 'Systèmes d\'exploitation']
            }
        ]
    };
    
    // 1. Remplir les champs simples
    fillSimpleFields(testData);
    
    // 2. Remplir les collections (languages, hobbies, personalities)
    fillCollections(testData);
    
    // 3. Remplir les compétences
    fillSkills(testData.skills);
    
    // 4. Remplir les expériences
    fillExperiences(testData.experiences);
    
    // 5. Remplir les formations
    fillFormations(testData.formations);
    
    console.log('✅ Données de test insérées avec succès !');
    alert('✅ Données de test insérées ! Vous pouvez maintenant cliquer sur "Sauvegarder" pour tester l\'insertion en base de données.');
}

/**
 * Remplit les champs simples (nom, prénom, email, etc.)
 */
function fillSimpleFields(data) {
    // First name
    updateField('identity', 'first_name', data.first_name);
    
    // Last name
    updateField('identity', 'last_name', data.last_name);
    
    // Job title
    updateField('identity', 'job_title', data.job_title);
    
    // Birthdate
    updateField('identity', 'birthdate', data.birthdate);
    
    // Location
    updateField('identity', 'location', data.location);
    
    // Phone
    updateField('identity', 'phone', data.phone);
    
    // Email
    updateField('identity', 'email', data.email);
    
    // Description
    updateField('identity', 'description', data.description);
    
    // Vehicule
    updateField('identity', 'vehicule', data.vehicule);
    
    // Degree
    updateField('identity', 'degree_id', data.degree_id);
    
    // Contract type
    updateField('demand', 'contract_type', data.contract_type);
    
    // Salary
    updateField('demand', 'salary', data.salary + ' €');
}

/**
 * Met à jour un champ dans le DOM
 */
function updateField(table, column, value) {
    const element = document.querySelector(`[data-table="${table}"][data-column="${column}"]`);
    if (element) {
        element.setAttribute('data-value', value);
        element.textContent = value;
        console.log(`✓ ${column}: ${value}`);
    }
}

/**
 * Remplit les collections (languages, hobbies, personalities)
 */
function fillCollections(data) {
    // Languages
    fillCollection('identity', 'language_ids', data.languages);
    
    // Hobbies
    fillCollection('identity', 'hobbie_ids', data.hobbies);
    
    // Personalities
    fillCollection('candidate', 'personality_ids', data.personalities);
}

/**
 * Remplit une collection spécifique
 */
function fillCollection(table, column, items) {
    const element = document.querySelector(`[data-table="${table}"][data-column="${column}"]`);
    if (element) {
        // Créer un tableau d'IDs (tous "newId" pour les nouvelles données)
        const ids = items.map(() => 'newId');
        const collection = JSON.stringify(ids);
        element.setAttribute('data-collection', collection);
        
        // Mettre à jour le texte affiché
        const displayText = items.map(item => item.title).join(', ');
        element.textContent = displayText;
        // Ne pas retirer la classe placeholder car personal-info.js l'utilise pour sélectionner l'élément
        
        console.log(`✓ ${column}: ${displayText}`);
    }
}

/**
 * Remplit les compétences
 */
function fillSkills(skills) {
    const skillsSection = document.querySelector('[data-editor="skills"] ul');
    if (!skillsSection) return;
    
    // Vider la section
    skillsSection.innerHTML = '';
    
    // Ajouter chaque compétence
    skills.forEach(skill => {
        const li = document.createElement('li');
        li.setAttribute('data-id', '');
        li.innerHTML = `
            <span class="dataToEdit" data-table="skill" data-column="title" data-value="${skill.title}" data-id="">${skill.title}</span>
            <span class="dataToEdit" data-table="skill" data-column="year" data-value="${skill.year}" data-id="">${skill.year} ans</span>
        `;
        skillsSection.appendChild(li);
    });
    
    console.log(`✓ ${skills.length} compétences ajoutées`);
}

/**
 * Remplit les expériences
 */
function fillExperiences(experiences) {
    const expSection = document.querySelector('[data-editor="experience"]');
    if (!expSection) return;
    
    // Vider la section
    expSection.innerHTML = '<h2>Expériences professionnels</h2>';
    
    // Ajouter chaque expérience
    experiences.forEach(exp => {
        const timeline = document.createElement('div');
        timeline.className = 'timeline-item';
        timeline.setAttribute('data-id', 'newId');
        timeline.setAttribute('data-start-date', exp.start_date);
        timeline.setAttribute('data-end-date', exp.end_date || '');
        timeline.setAttribute('data-current', exp.current ? 'true' : 'false');
        
        const dateRange = exp.current ? 
            `${formatDate(exp.start_date)} - Aujourd'hui` : 
            `${formatDate(exp.start_date)} - ${formatDate(exp.end_date)}`;
        
        timeline.innerHTML = `
            <div class="timeline-marker"></div>
            <div class="timeline-content">
                <div class="period">
                    <h3>
                        <span class="dataToEdit" data-table="experience" data-column="position" data-value="${exp.position}" data-id="newId">${exp.position}</span>
                        <span class="dataToEdit" data-table="experience" data-column="company" data-value="${exp.company}" data-id="newId">${exp.company}</span>
                    </h3>
                    <p class="date">${dateRange}</p>
                    <p class="dataToEdit" data-table="experience" data-column="location" data-value="${exp.location}" data-id="newId">${exp.location}</p>
                </div>
                <div class="missions-section">
                    <strong>Missions :</strong>
                    <span class="missions multipleDataToEdit" data-table="experience" data-column="missions" data-collection='${JSON.stringify(exp.missions.map(m => ({title: m})))}' data-id="newId">
                        ${exp.missions.join(', ')}
                    </span>
                </div>
            </div>
        `;
        
        expSection.appendChild(timeline);
    });
    
    console.log(`✓ ${experiences.length} expériences ajoutées`);
}

/**
 * Remplit les formations
 */
function fillFormations(formations) {
    const formSection = document.querySelector('[data-editor="formation"]');
    if (!formSection) return;
    
    // Vider la section
    formSection.innerHTML = '<h2>Formations</h2>';
    
    // Ajouter chaque formation
    formations.forEach(form => {
        const timeline = document.createElement('div');
        timeline.className = 'timeline-item';
        timeline.setAttribute('data-id', 'newId');
        timeline.setAttribute('data-start-date', form.start_date);
        timeline.setAttribute('data-end-date', form.end_date || '');
        timeline.setAttribute('data-current', form.current ? 'true' : 'false');
        
        const dateRange = form.current ? 
            `${formatDate(form.start_date)} - Aujourd'hui` : 
            `${formatDate(form.start_date)} - ${formatDate(form.end_date)}`;
        
        timeline.innerHTML = `
            <div class="timeline-marker"></div>
            <div class="timeline-content">
                <div class="period">
                    <h3>
                        <span class="dataToEdit" data-table="formation" data-column="title" data-value="${form.title}" data-id="newId">${form.title}</span>
                    </h3>
                    <p class="dataToEdit" data-table="formation" data-column="school" data-value="${form.school}" data-id="newId">${form.school}</p>
                    <p class="date">${dateRange}</p>
                    <p class="dataToEdit" data-table="formation" data-column="location" data-value="${form.location}" data-id="newId">${form.location}</p>
                </div>
                <div class="learning-section">
                    <strong>Connaissances acquises :</strong>
                    <span class="learning multipleDataToEdit" data-table="formation" data-column="knowledge" data-collection='${JSON.stringify(form.knowledges.map(k => ({title: k})))}' data-id="newId">
                        ${form.knowledges.join(', ')}
                    </span>
                </div>
            </div>
        `;
        
        formSection.appendChild(timeline);
    });
    
    console.log(`✓ ${formations.length} formations ajoutées`);
}

/**
 * Formate une date YYYY-MM en texte lisible
 */
function formatDate(dateStr) {
    if (!dateStr) return '';
    const [year, month] = dateStr.split('-');
    const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
    return `${months[parseInt(month) - 1]} ${year}`;
}

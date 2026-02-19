// Script pour insérer des données de test dans le formulaire d'offre d'emploi
document.addEventListener('DOMContentLoaded', () => {
    const testButton = document.getElementById('test-job-button');
    
    if (testButton) {
        testButton.addEventListener('click', insertTestDataJob);
    }
});

function insertTestDataJob() {
    // Données de test pour l'offre d'emploi
    const testData = {
        job: {
            title: 'Développeur Full Stack Senior',
            location: 'Paris, France',
            salary: '50000 - 65000 € par an',
            contract_type: 'CDI',
            start_date: '2024-03-01',
            length: 'Temps plein',
            need_vehicle: '0',
            description: 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants utilisant les dernières technologies web.\n\nResponsabilités principales :\n- Développer et maintenir des applications web complexes\n- Collaborer avec l\'équipe produit et design\n- Participer aux revues de code\n- Mentorer les développeurs juniors',
            work_condition: 'Conditions de travail :\n- Télétravail partiel (2-3 jours par semaine)\n- Horaires flexibles\n- Environnement de travail moderne\n- Équipe jeune et dynamique\n- Opportunités de formation continue\n- Avantages sociaux : tickets restaurant, mutuelle, RTT'
        },
        requirement: {
            degree: 'Bac +5 (Master, diplôme d\'ingénieur)',
            experience_years: '5',
            description: 'Compétences techniques requises :\n- JavaScript, TypeScript, Node.js\n- React, Vue.js ou Angular\n- SQL et NoSQL\n- Git, Docker\n- API REST et GraphQL\n\nCompétences souhaitées :\n- Expérience avec des méthodologies Agile\n- Bonnes capacités de communication\n- Autonomie et esprit d\'équipe\n- Anglais professionnel'
        },
        company: {
            name: 'TechCorp Solutions',
            domain: 'Technologies de l\'information',
            capacity: '50-200 employés',
            hq_location: 'Paris',
            country: 'France',
            description: 'TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure pour les entreprises. Fondée en 2015, nous accompagnons nos clients dans leur transformation digitale.\n\nNos valeurs :\n- Innovation et excellence technique\n- Collaboration et transparence\n- Développement des compétences\n- Équilibre vie professionnelle/personnelle'
        },
        contact: {
            first_name: 'Marie',
            last_name: 'Dupont',
            email: 'marie.dupont@techcorp.com',
            phone: '+33 1 23 45 67 89'
        }
    };

    // Insérer les données dans les champs du formulaire
    Object.keys(testData).forEach(table => {
        Object.keys(testData[table]).forEach(column => {
            const element = document.querySelector(
                `.dataToEdit[data-table="${table}"][data-column="${column}"]`
            );
            
            if (element) {
                const value = testData[table][column];
                
                // Mettre à jour l'attribut data-value
                element.setAttribute('data-value', value);
                
                // Mettre à jour le contenu visible
                if (column === 'description' || column === 'work_condition') {
                    // Pour les textes longs, remplacer les retours à la ligne par des <br>
                    element.innerHTML = value.replace(/\n/g, '<br>');
                } else if (column === 'need_vehicle') {
                    // Pour le booléen véhicule
                    element.textContent = value === '1' ? 'Oui' : 'Non';
                } else {
                    // Pour les autres champs
                    element.textContent = value;
                }
                
                // Marquer le champ comme modifié
                element.classList.add('modified');
            }
        });
    });

    console.log('Données de test insérées avec succès');
    
    // Afficher un message de confirmation
    alert('Les données de test ont été chargées dans le formulaire.');
}

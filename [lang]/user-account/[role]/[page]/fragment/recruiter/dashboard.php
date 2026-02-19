<div class="dashboard-cards">
    <div class="dashboard-card">
        <h3>Mes offres d'emploi</h3>
        <p>Créez et gérez vos offres d'emploi pour attirer les meilleurs talents.</p>
        <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/user-account/recruiter/mes-offres" class="btn-card">Gérer mes offres</a>
    </div>
    
    <div class="dashboard-card">
        <h3>Candidatures reçues</h3>
        <p>Consultez et gérez les candidatures reçues pour vos offres.</p>
        <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/user-account/recruiter/candidatures" class="btn-card">Voir les candidatures</a>
    </div>
    
    <div class="dashboard-card">
        <h3>Recherche de candidats</h3>
        <p>Recherchez des profils de candidats correspondant à vos besoins.</p>
        <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/user-account/recruiter/recherche-candidats" class="btn-card">Rechercher</a>
    </div>
    
    <div class="dashboard-card">
        <h3>Mon profil recruteur</h3>
        <p>Gérez vos informations de recruteur et paramètres.</p>
        <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/user-account/recruiter/mon-profil" class="btn-card">Mon profil</a>
    </div>
</div>

<style>
.btn-card {
    display: inline-block;
    margin-top: 12px;
    padding: 8px 16px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}
</style>

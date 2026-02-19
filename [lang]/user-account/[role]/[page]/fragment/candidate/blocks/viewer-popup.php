<?php
/**
 * Viewer de CV pour popup
 * Charge et affiche le CV d'un candidat
 */

// Récupérer l'ID du candidat
$candidateId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$candidateId) {
    echo '<div class="error-message">ID de candidat manquant</div>';
    exit;
}

// Charger les données du candidat
require_once __DIR__ . '/../../../../../../../function/connectionDatabase.php';

try {
    $db = getDatabaseConnection();
    
    // Requête pour récupérer toutes les dont du candidat
    $query = "
        SELECT 
            c.*,
            i.first_name, i.last_name, i.job_title, i.birthdate, i.location, 
            i.phone, i.email, i.description, i.degree_id, i.language_ids, i.hobbie_ids,
            i.vehicule,
            d.contract_type, d.salary, d.work_condition,
            deg.title as degree_title
        FROM candidate c
        LEFT JOIN identity i ON c.identity_id = i.id
        LEFT JOIN demand d ON c.demand_id = d.id
        LEFT JOIN degree deg ON i.degree_id = deg.id
        WHERE c.id = :id
    ";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $candidateId, PDO::PARAM_INT);
    $stmt->execute();
    
    $candidate = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$candidate) {
        echo '<div class="error-message">Candidat introuvable</div>';
        exit;
    }
    
    // Calculer l'âge
    $age = '';
    if ($candidate['birthdate']) {
        $birthDate = new DateTime($candidate['birthdate']);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;
    }
    
    // URL de la photo
    $photoUrl = $candidate['media_code'] 
        ? $baseUrl . '/data/candidates/media/' . $candidate['media_code'] . '/photo.jpg'
        : $baseUrl . '/data/candidates/media/default/photo.jpg';
    
    // URL du QR code
    $qrcodeUrl = $candidate['media_code']
        ? $baseUrl . '/data/candidates/media/' . $candidate['media_code'] . '/qr-code.png'
        : $baseUrl . '/data/candidates/media/default/qr-code.png';
    
    // Décoder les JSON pour languages, hobbies, etc.
    $languageIds = json_decode($candidate['language_ids'], true) ?: [];
    $hobbieIds = json_decode($candidate['hobbie_ids'], true) ?: [];
    $personalityIds = json_decode($candidate['personality_ids'], true) ?: [];
    $skillIds = json_decode($candidate['skill_ids'], true) ?: [];
    $experienceIds = json_decode($candidate['experience_ids'], true) ?: [];
    $formationIds = json_decode($candidate['formation_ids'], true) ?: [];
    
    // Récupérer les noms des langues
    $languages = [];
    if (!empty($languageIds)) {
        $placeholders = implode(',', array_fill(0, count($languageIds), '?'));
        $stmt = $db->prepare("SELECT language_name FROM language WHERE id IN ($placeholders)");
        $stmt->execute($languageIds);
        $languages = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    // Récupérer les hobbies
    $hobbies = [];
    if (!empty($hobbieIds)) {
        $placeholders = implode(',', array_fill(0, count($hobbieIds), '?'));
        $stmt = $db->prepare("SELECT title FROM hobbie WHERE id IN ($placeholders)");
        $stmt->execute($hobbieIds);
        $hobbies = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    // Récupérer les traits de personnalité
    $personalities = [];
    if (!empty($personalityIds)) {
        $placeholders = implode(',', array_fill(0, count($personalityIds), '?'));
        $stmt = $db->prepare("SELECT trait FROM personality WHERE id IN ($placeholders)");
        $stmt->execute($personalityIds);
        $personalities = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    // Récupérer les compétences
    $skills = [];
    if (!empty($skillIds)) {
        $placeholders = implode(',', array_fill(0, count($skillIds), '?'));
        $stmt = $db->prepare("SELECT title FROM skill WHERE id IN ($placeholders)");
        $stmt->execute($skillIds);
        $skills = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    // Récupérer les expériences
    $experiences = [];
    if (!empty($experienceIds)) {
        $placeholders = implode(',', array_fill(0, count($experienceIds), '?'));
        $stmt = $db->prepare("SELECT * FROM experience WHERE id IN ($placeholders) ORDER BY start_date DESC");
        $stmt->execute($experienceIds);
        $experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupérer les formations
    $formations = [];
    if (!empty($formationIds)) {
        $placeholders = implode(',', array_fill(0, count($formationIds), '?'));
        $stmt = $db->prepare("SELECT * FROM formation WHERE id IN ($placeholders) ORDER BY start_date DESC");
        $stmt->execute($formationIds);
        $formations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
} catch (PDOException $e) {
    echo '<div class="error-message">Erreur base de données: ' . htmlspecialchars($e->getMessage()) . '</div>';
    exit;
}
?>

<!-- CV Viewer -->
<div class="cv-viewer-container">
    <!-- Boutons d'action -->
    <div class="cv-viewer-actions">
        <button id="print-cv-viewer" class="cv-action-btn" title="Imprimer le CV">
            <span>🖨️</span> Imprimer
        </button>
        <button id="download-cv-viewer" class="cv-action-btn" title="Télécharger le CV">
            <span>📥</span> Télécharger
        </button>
    </div>
    
    <!-- CV Content -->
    <div class="cv-content" id="cv-printable">
        <!-- Header -->
        <header class="cv-header">
            <div class="cv-header-left">
                <div class="cv-profile-photo" style="background-image: url('<?php echo $photoUrl; ?>');"></div>
            </div>
            <div class="cv-header-right">
                <h1 class="cv-name"><?php echo htmlspecialchars($candidate['first_name'] . ' ' . $candidate['last_name']); ?></h1>
                <h2 class="cv-job-title"><?php echo htmlspecialchars($candidate['job_title'] ?: 'Candidat'); ?></h2>
            </div>
        </header>

        <!-- Info Section -->
        <section class="cv-info-section">
            <div class="cv-personal-info">
                <h3>📋 Informations personnelles</h3>
                <?php if ($age): ?>
                    <p><strong>Âge :</strong> <?php echo $age; ?> ans</p>
                <?php endif; ?>
                <?php if ($candidate['degree_title']): ?>
                    <p><strong>Niveau d'étude :</strong> <?php echo htmlspecialchars($candidate['degree_title']); ?></p>
                <?php endif; ?>
                <?php if (!empty($languages)): ?>
                    <p><strong>Langue(s) :</strong> <?php echo htmlspecialchars(implode(', ', $languages)); ?></p>
                <?php endif; ?>
                <?php if (!empty($personalities)): ?>
                    <p><strong>Personnalité :</strong> <?php echo htmlspecialchars(implode(', ', $personalities)); ?></p>
                <?php endif; ?>
                <?php if (!empty($hobbies)): ?>
                    <p><strong>Hobbies :</strong> <?php echo htmlspecialchars(implode(', ', $hobbies)); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="cv-job-info">
                <h3>💼 Recherche d'emploi</h3>
                <p><strong>Localisation :</strong> <?php echo htmlspecialchars($candidate['location']); ?></p>
                <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($candidate['phone']); ?></p>
                <p><strong>Email :</strong> <?php echo htmlspecialchars($candidate['email']); ?></p>
                <p><strong>Véhicule :</strong> <?php echo $candidate['vehicule'] ? 'Oui' : 'Non'; ?></p>
                <?php if ($candidate['contract_type']): ?>
                    <p><strong>Type de contrat :</strong> <?php echo htmlspecialchars($candidate['contract_type']); ?></p>
                <?php endif; ?>
                <?php if ($candidate['work_condition']): ?>
                    <p><strong>Conditions de travail :</strong> <?php echo htmlspecialchars($candidate['work_condition']); ?></p>
                <?php endif; ?>
                <?php if ($candidate['salary']): ?>
                    <p><strong>Salaire souhaité :</strong> <?php echo htmlspecialchars($candidate['salary']); ?></p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Description -->
        <?php if ($candidate['description']): ?>
            <section class="cv-description">
                <h3>📝 Présentation</h3>
                <p><?php echo nl2br(htmlspecialchars($candidate['description'])); ?></p>
            </section>
        <?php endif; ?>

        <!-- Compétences -->
        <?php if (!empty($skills)): ?>
            <section class="cv-skills">
                <h3>🎯 Compétences</h3>
                <div class="skills-grid">
                    <?php foreach ($skills as $skill): ?>
                        <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Expériences -->
        <?php if (!empty($experiences)): ?>
            <section class="cv-experiences">
                <h3>💼 Expériences professionnelles</h3>
                <?php foreach ($experiences as $exp): ?>
                    <div class="experience-item">
                        <h4><?php echo htmlspecialchars($exp['position']); ?></h4>
                        <p class="company"><?php echo htmlspecialchars($exp['company_name']); ?></p>
                        <p class="period">
                            <?php echo date('m/Y', strtotime($exp['start_date'])); ?> - 
                            <?php echo $exp['end_date'] ? date('m/Y', strtotime($exp['end_date'])) : 'Aujourd\'hui'; ?>
                        </p>
                        <?php if ($exp['description']): ?>
                            <p class="description"><?php echo nl2br(htmlspecialchars($exp['description'])); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <!-- Formations -->
        <?php if (!empty($formations)): ?>
            <section class="cv-formations">
                <h3>🎓 Formations</h3>
                <?php foreach ($formations as $form): ?>
                    <div class="formation-item">
                        <h4><?php echo htmlspecialchars($form['title']); ?></h4>
                        <p class="school"><?php echo htmlspecialchars($form['school_name']); ?></p>
                        <p class="period">
                            <?php echo date('m/Y', strtotime($form['start_date'])); ?> - 
                            <?php echo $form['end_date'] ? date('m/Y', strtotime($form['end_date'])) : 'Aujourd\'hui'; ?>
                        </p>
                        <?php if ($form['description']): ?>
                            <p class="description"><?php echo nl2br(htmlspecialchars($form['description'])); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <!-- Footer -->
        <footer class="cv-footer">
            <div class="footer-logo">
                <img src="<?php echo $baseUrl; ?>/media/image/logo.png" alt="1-TED Logo">
            </div>
            <div class="footer-text">
                <p>CV généré via 1-TED</p>
            </div>
            <div class="footer-qr">
                <img src="<?php echo $qrcodeUrl; ?>" alt="QR Code">
            </div>
        </footer>
    </div>
</div>

<script>
// Impression du CV
document.getElementById('print-cv-viewer').addEventListener('click', function() {
    window.print();
});

// Téléchargement du CV (impression en PDF)
document.getElementById('download-cv-viewer').addEventListener('click', function() {
    window.print();
});
</script>

<style>
.cv-viewer-container {
    max-width: 1000px;
    margin: 0 auto;
}

.cv-viewer-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    padding: 15px;
    background: #f9fafb;
    border-radius: 8px;
}

.cv-action-btn {
    flex: 1;
    padding: 12px 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.cv-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.cv-content {
    background: white;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.cv-header {
    display: flex;
    gap: 30px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 3px solid #667eea;
}

.cv-profile-photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background-size: cover;
    background-position: center;
    border: 4px solid #667eea;
}

.cv-name {
    font-size: 2rem;
    color: #1f2937;
    margin: 0 0 10px 0;
}

.cv-job-title {
    font-size: 1.3rem;
    color: #667eea;
    margin: 0;
    font-weight: 600;
}

.cv-info-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 30px;
}

.cv-info-section h3 {
    color: #667eea;
    font-size: 1.1rem;
    margin-bottom: 15px;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 8px;
}

.cv-info-section p {
    margin: 8px 0;
    color: #374151;
}

.cv-description,
.cv-skills,
.cv-experiences,
.cv-formations {
    margin-bottom: 30px;
}

.cv-description h3,
.cv-skills h3,
.cv-experiences h3,
.cv-formations h3 {
    color: #667eea;
    font-size: 1.2rem;
    margin-bottom: 15px;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 8px;
}

.skills-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.skill-tag {
    padding: 8px 16px;
    background: #e0e7ff;
    color: #4338ca;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.experience-item,
.formation-item {
    margin-bottom: 25px;
    padding: 20px;
    background: #f9fafb;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.experience-item h4,
.formation-item h4 {
    color: #1f2937;
    font-size: 1.1rem;
    margin: 0 0 8px 0;
}

.company,
.school {
    color: #667eea;
    font-weight: 600;
    margin: 4px 0;
}

.period {
    color: #6b7280;
    font-size: 0.9rem;
    margin: 4px 0 15px 0;
}

.description {
    color: #374151;
    line-height: 1.6;
}

.cv-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: #f9fafb;
    border-radius: 8px;
    margin-top: 30px;
}

.footer-logo img,
.footer-qr img {
    max-height: 60px;
}

.footer-text {
    color: #6b7280;
    font-size: 0.9rem;
}

.error-message {
    padding: 20px;
    background: #fee2e2;
    color: #991b1b;
    border-radius: 8px;
    text-align: center;
}

/* Impression */
@media print {
    .cv-viewer-actions {
        display: none;
    }
    
    .cv-content {
        box-shadow: none;
    }
    
    body {
        background: white;
    }
}

@media (max-width: 768px) {
    .cv-info-section {
        grid-template-columns: 1fr;
    }
    
    .cv-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .cv-viewer-actions {
        flex-direction: column;
    }
}
</style>
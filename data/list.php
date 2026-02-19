<?php
require_once __DIR__ . '/../function/getData.php';
require_once __DIR__ . '/../function/connectionDatabase.php';

// Charger la configuration
$configPath = __DIR__ . '/../en/info.php';
if (!file_exists($configPath)) {
    $configPath = __DIR__ . '/../fr/info.php';
}
require_once $configPath;

// Récupérer tous les candidats
$candidates = getData('candidate', null, true);

// Récupérer toutes les offres d'emploi
$jobs = getData('job', null, true);

// Statistiques
$totalCandidates = count($candidates);
$totalJobs = count($jobs);

// Statistiques candidats par statut
$candidatesPending = 0;
$candidatesApproved = 0;
$candidatesRejected = 0;

foreach ($candidates as $candidate) {
    $approved = $candidate->isApproved();
    if ($approved === null) {
        $candidatesPending++;
    } elseif ($approved === true || $approved === 1) {
        $candidatesApproved++;
    } else {
        $candidatesRejected++;
    }
}

// Statistiques jobs par statut
$jobsPending = 0;
$jobsApproved = 0;
$jobsRejected = 0;

foreach ($jobs as $job) {
    $approved = $job->isApproved();
    if ($approved === null) {
        $jobsPending++;
    } elseif ($approved === true || $approved === 1) {
        $jobsApproved++;
    } else {
        $jobsRejected++;
    }
}

// Pour les candidats, on va aussi charger les identités
$candidatesWithIdentity = [];
foreach ($candidates as $candidate) {
    $identityId = $candidate->getIdentityId();
    if ($identityId) {
        $identity = getData('identity', $identityId, true);
        $candidatesWithIdentity[] = [
            'candidate' => $candidate,
            'identity' => $identity
        ];
    }
}

// Pour les jobs, charger les entreprises
$jobsWithCompany = [];
foreach ($jobs as $job) {
    $companyId = $job->getCompanyId();
    $company = null;
    if ($companyId) {
        $company = getData('company', $companyId, true);
    }
    $jobsWithCompany[] = [
        'job' => $job,
        'company' => $company
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des candidats et offres d'emploi</title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/list.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <h1>📋 Tableau de bord de validation</h1>
            <p>Gérez vos candidats et vos offres d'emploi</p>
        </div>

        <!-- Onglets -->
        <div class="tabs">
            <button class="tab-button active" data-tab="candidates">
                👤 Candidats (<?php echo $totalCandidates; ?>)
            </button>
            <button class="tab-button" data-tab="jobs">
                💼 Offres d'emploi (<?php echo $totalJobs; ?>)
            </button>
        </div>

        <!-- Contenu onglet Candidats -->
        <div class="tab-content active" id="candidates-tab">
            <!-- Statistiques candidats -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $totalCandidates; ?></div>
                    <div class="stat-label">Total candidats</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $candidatesPending; ?></div>
                    <div class="stat-label">En attente</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $candidatesApproved; ?></div>
                    <div class="stat-label">Approuvés</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $candidatesRejected; ?></div>
                    <div class="stat-label">Refusés</div>
                </div>
            </div>

            <!-- Liste des candidats -->
            <div class="items-grid" id="candidates-grid">
                <?php if (empty($candidatesWithIdentity)): ?>
                    <div class="empty-state">
                        <div class="icon">📭</div>
                        <h3>Aucun candidat</h3>
                        <p>Il n'y a aucun candidat dans la base de données.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($candidatesWithIdentity as $item): 
                        $candidate = $item['candidate'];
                        $identity = $item['identity'];
                        
                        $candidateId = $candidate->getId();
                        $firstName = $identity ? $identity->getFirstName() : 'N/A';
                        $lastName = $identity ? $identity->getLastName() : 'N/A';
                        $jobTitle = $identity ? $identity->getJobTitle() : 'N/A';
                        $location = $identity ? $identity->getLocation() : 'N/A';
                        $email = $identity ? $identity->getEmail() : 'N/A';
                        $phone = $identity ? $identity->getPhone() : 'N/A';
                        $mediaCode = $candidate->getMediaCode();
                        
                        $photoUrl = $mediaCode ? "candidates/media/{$mediaCode}/photo.jpg" : "candidates/media/default/photo.jpg";
                    ?>
                        <div class="candidate-card" data-id="<?php echo $candidateId; ?>" data-type="candidate">
                            <div class="candidate-header">
                                <img src="<?php echo $photoUrl; ?>" alt="Photo" class="candidate-photo" onerror="this.src='candidates/media/default/photo.jpg'">
                                <div class="candidate-info">
                                    <h3><?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></h3>
                                    <p><?php echo htmlspecialchars($jobTitle); ?></p>
                                </div>
                            </div>
                            <div class="candidate-body">
                                <div class="candidate-details">
                                    <div class="detail-row">
                                        <span class="detail-label">📍 Localisation</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($location); ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">📧 Email</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($email); ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">📞 Téléphone</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($phone); ?></span>
                                    </div>
                                </div>
                                
                                <span class="status-badge status-pending" data-status="pending">⏳ En attente</span>
                                
                                <div class="card-actions">
                                    <button class="action-btn btn-view" onclick="viewCandidate(<?php echo $candidateId; ?>)">
                                        👁️ Consulter
                                    </button>
                                    <button class="action-btn btn-approve" onclick="approveItem(<?php echo $candidateId; ?>, 'candidate')">
                                        ✅ Valider
                                    </button>
                                    <button class="action-btn btn-reject" onclick="rejectItem(<?php echo $candidateId; ?>, 'candidate')">
                                        ❌ Refuser
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Contenu onglet Offres d'emploi -->
        <div class="tab-content" id="jobs-tab">
            <!-- Statistiques jobs -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $totalJobs; ?></div>
                    <div class="stat-label">Total offres</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $jobsPending; ?></div>
                    <div class="stat-label">En attente</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $jobsApproved; ?></div>
                    <div class="stat-label">Approuvées</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $jobsRejected; ?></div>
                    <div class="stat-label">Refusées</div>
                </div>
            </div>

            <!-- Liste des offres -->
            <div class="items-grid" id="jobs-grid">
                <?php if (empty($jobsWithCompany)): ?>
                    <div class="empty-state">
                        <div class="icon">📭</div>
                        <h3>Aucune offre d'emploi</h3>
                        <p>Il n'y a aucune offre d'emploi dans la base de données.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($jobsWithCompany as $item): 
                        $job = $item['job'];
                        $company = $item['company'];
                        
                        $jobId = $job->getId();
                        $title = $job->getTitle();
                        $companyName = $company ? $company->getName() : 'Non spécifiée';
                        $location = $job->getLocation();
                        $salary = $job->getSalary();
                        $contractType = $job->getContractType();
                        $isOpen = $job->getOpen();
                    ?>
                        <div class="job-card" data-id="<?php echo $jobId; ?>" data-type="job">
                            <div class="job-header">
                                <h3><?php echo htmlspecialchars($title); ?></h3>
                                <div class="job-company">
                                    <span>🏢</span>
                                    <span><?php echo htmlspecialchars($companyName); ?></span>
                                </div>
                            </div>
                            <div class="job-body">
                                <div class="job-badges">
                                    <?php if ($location): ?>
                                        <span class="badge">📍 <?php echo htmlspecialchars($location); ?></span>
                                    <?php endif; ?>
                                    <?php if ($contractType): ?>
                                        <span class="badge">📋 <?php echo htmlspecialchars($contractType); ?></span>
                                    <?php endif; ?>
                                    <?php if ($salary): ?>
                                        <span class="badge">💰 <?php echo htmlspecialchars($salary); ?></span>
                                    <?php endif; ?>
                                    <span class="badge <?php echo $isOpen ? 'open' : 'closed'; ?>">
                                        <?php echo $isOpen ? '✅ Ouverte' : '❌ Fermée'; ?>
                                    </span>
                                </div>
                                
                                <?php 
                                    $approved = $job->isApproved();
                                    if ($approved === null) {
                                        echo '<span class="status-badge status-pending" data-status="pending">⏳ En attente</span>';
                                    } elseif ($approved === true || $approved === 1) {
                                        echo '<span class="status-badge status-approved" data-status="approved">✅ Approuvé</span>';
                                    } else {
                                        echo '<span class="status-badge status-rejected" data-status="rejected">❌ Refusé</span>';
                                    }
                                ?>
                                
                                <div class="card-actions">
                                    <button class="action-btn btn-view" onclick="viewJob(<?php echo $jobId; ?>)">
                                        👁️ Consulter
                                    </button>
                                    <button class="action-btn btn-approve" onclick="approveItem(<?php echo $jobId; ?>, 'job')">
                                        ✅ Valider
                                    </button>
                                    <button class="action-btn btn-reject" onclick="rejectItem(<?php echo $jobId; ?>, 'job')">
                                        ❌ Refuser
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="<?php echo $baseUrl; ?>/script/config.js"></script>
    <script src="<?php echo $baseUrl; ?>/script/list.js"></script>
</body>
</html>

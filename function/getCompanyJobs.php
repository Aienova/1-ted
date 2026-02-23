<?php
/**
 * Viewer pour afficher les offres d'emploi d'une entreprise dans une popup
 */

require_once __DIR__ . '/connectionDatabase.php';

// Récupérer l'ID de l'entreprise
$companyId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($companyId <= 0) {
    echo '<div class="popup-error">';
    echo '<div class="error-icon">⚠️</div>';
    echo '<h3>Erreur</h3>';
    echo '<p>ID d\'entreprise invalide</p>';
    echo '</div>';
    exit;
}

try {
    $pdo = getDatabaseConnection();
    
    // Récupérer le nom de l'entreprise
    $stmt = $pdo->prepare("SELECT name FROM company WHERE id = :id");
    $stmt->execute([':id' => $companyId]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$company) {
        echo '<div class="popup-error">';
        echo '<div class="error-icon">❌</div>';
        echo '<h3>Entreprise non trouvée</h3>';
        echo '<p>L\'entreprise demandée n\'existe pas.</p>';
        echo '</div>';
        exit;
    }
    
    // Récupérer toutes les offres d'emploi de l'entreprise
    $sql = "SELECT 
                j.id,
                j.title,
                j.description,
                j.location,
                j.salary,
                j.contract_type,
                j.start_date,
                j.length,
                j.work_condition,
                j.need_vehicle,
                j.approved,
                j.created_at,
                cnt.first_name AS contact_first_name,
                cnt.last_name AS contact_last_name,
                cnt.email AS contact_email,
                cnt.phone AS contact_phone
            FROM job j
            LEFT JOIN contact cnt ON j.contact_id = cnt.id
            WHERE j.company_id = :company_id
            ORDER BY j.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':company_id' => $companyId]);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    echo '<div class="popup-error">';
    echo '<div class="error-icon">⚠️</div>';
    echo '<h3>Erreur de base de données</h3>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '</div>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1f2937;
        }
        
        .jobs-viewer {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .jobs-header {
            background: linear-gradient(135deg, #1e93e5 0%, #4b97a2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        
        .jobs-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .jobs-header p {
            font-size: 1rem;
            opacity: 0.95;
        }
        
        .jobs-count {
            background: #f9fafb;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #667eea;
        }
        
        .jobs-count strong {
            color: #667eea;
            font-size: 1.2rem;
        }
        
        .jobs-grid {
            display: grid;
            gap: 20px;
        }
        
        .job-card {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 25px;
            transition: all 0.3s ease;
        }
        
        .job-card:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }
        
        .job-card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }
        
        .job-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .job-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: #6b7280;
        }
        
        .job-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .job-description {
            color: #4b5563;
            margin-bottom: 15px;
            line-height: 1.8;
            max-height: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .job-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }
        
        .job-info-item {
            font-size: 0.85rem;
        }
        
        .job-info-label {
            font-weight: 600;
            color: #6b7280;
        }
        
        .job-info-value {
            color: #1f2937;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-rejected {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #f9fafb;
            border-radius: 12px;
            border: 2px dashed #d1d5db;
        }
        
        .empty-state .icon {
            font-size: 4rem;
            opacity: 0.3;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            color: #374151;
            margin-bottom: 10px;
        }
        
        .empty-state p {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="jobs-viewer">
        <!-- Header -->
        <div class="jobs-header">
            <h1>Offres d'emploi</h1>
            <p><?php echo htmlspecialchars($company['name']); ?></p>
        </div>
        
        <?php if (count($jobs) > 0): ?>
            <!-- Compteur -->
            <div class="jobs-count">
                <strong><?php echo count($jobs); ?></strong> offre<?php echo count($jobs) > 1 ? 's' : ''; ?> d'emploi publiée<?php echo count($jobs) > 1 ? 's' : ''; ?>
            </div>
            
            <!-- Liste des offres -->
            <div class="jobs-grid">
                <?php foreach ($jobs as $job): ?>
                    <div class="job-card">
                        <div class="job-card-header">
                            <div>
                                <h2 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h2>
                            </div>
                            <div>
                                <?php
                                if ($job['approved'] === null) {
                                    echo '<span class="badge badge-pending">En attente</span>';
                                } elseif ($job['approved'] == 1) {
                                    echo '<span class="badge badge-success">✓ Approuvée</span>';
                                } else {
                                    echo '<span class="badge badge-rejected">✗ Rejetée</span>';
                                }
                                ?>
                            </div>
                        </div>
                        
                        <div class="job-meta">
                            <?php if ($job['location']): ?>
                                <span>📍 <?php echo htmlspecialchars($job['location']); ?></span>
                            <?php endif; ?>
                            <?php if ($job['contract_type']): ?>
                                <span>📋 <?php echo htmlspecialchars($job['contract_type']); ?></span>
                            <?php endif; ?>
                            <?php if ($job['salary']): ?>
                                <span>💰 <?php echo number_format($job['salary'], 0, ',', ' ') . ' €'; ?></span>
                            <?php endif; ?>
                            <?php if ($job['start_date']): ?>
                                <span>📅 Début: <?php echo date('d/m/Y', strtotime($job['start_date'])); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($job['description']): ?>
                            <div class="job-description">
                                <?php echo nl2br(htmlspecialchars(substr($job['description'], 0, 200))); ?>
                                <?php if (strlen($job['description']) > 200) echo '...'; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="job-info-grid">
                            <?php if ($job['length']): ?>
                                <div class="job-info-item">
                                    <div class="job-info-label">Durée:</div>
                                    <div class="job-info-value"><?php echo htmlspecialchars($job['length']); ?></div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($job['work_condition']): ?>
                                <div class="job-info-item">
                                    <div class="job-info-label">Conditions:</div>
                                    <div class="job-info-value"><?php echo htmlspecialchars($job['work_condition']); ?></div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="job-info-item">
                                <div class="job-info-label">Véhicule requis:</div>
                                <div class="job-info-value"><?php echo $job['need_vehicle'] ? 'Oui' : 'Non'; ?></div>
                            </div>
                            
                            <div class="job-info-item">
                                <div class="job-info-label">Publiée le:</div>
                                <div class="job-info-value"><?php echo date('d/m/Y', strtotime($job['created_at'])); ?></div>
                            </div>
                        </div>
                        
                        <?php if ($job['contact_email']): ?>
                            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                                <div style="font-size: 0.85rem; color: #6b7280;">
                                    <strong>Contact:</strong> 
                                    <?php echo htmlspecialchars($job['contact_first_name'] . ' ' . $job['contact_last_name']); ?>
                                    - <a href="mailto:<?php echo htmlspecialchars($job['contact_email']); ?>" style="color: #667eea;">
                                        <?php echo htmlspecialchars($job['contact_email']); ?>
                                    </a>
                                    <?php if ($job['contact_phone']): ?>
                                        - <?php echo htmlspecialchars($job['contact_phone']); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
        <?php else: ?>
            <!-- État vide -->
            <div class="empty-state">
                <div class="icon">📋</div>
                <h3>Aucune offre d'emploi</h3>
                <p>Cette entreprise n'a pas encore publié d'offres d'emploi.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
/**
 * Viewer pour afficher les informations détaillées d'une entreprise dans une popup
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
    
    // Récupérer les données de l'entreprise avec contact et statistiques
    $sql = "SELECT 
                c.*,
                cnt.first_name AS contact_first_name,
                cnt.last_name AS contact_last_name,
                cnt.email AS contact_email,
                cnt.phone AS contact_phone,
                COUNT(DISTINCT j.id) AS jobs_count,
                COUNT(DISTINCT CASE WHEN j.approved = 1 THEN j.id END) AS approved_jobs_count,
                COUNT(DISTINCT a.id) AS accounts_count
            FROM company c
            LEFT JOIN contact cnt ON c.contact_id = cnt.id
            LEFT JOIN job j ON j.company_id = c.id
            LEFT JOIN account a ON a.company_id = c.id
            WHERE c.id = :id
            GROUP BY c.id";
    
    $stmt = $pdo->prepare($sql);
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
        
        .company-viewer {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .company-header {
            background: linear-gradient(135deg, #1e93e5 0%, #4b97a2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        
        .company-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .company-header .company-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 0.95rem;
            opacity: 0.95;
        }
        
        .company-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #f9fafb;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        
        .stat-card .label {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 5px;
        }
        
        .stat-card .value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1f2937;
        }
        
        .info-section {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
        }
        
        .info-section h2 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: #667eea;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 15px;
        }
        
        .info-label {
            font-weight: 600;
            color: #4b5563;
        }
        
        .info-value {
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
        
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .description-box {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            white-space: pre-wrap;
            line-height: 1.8;
        }
        
        .empty-value {
            color: #9ca3af;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="company-viewer">
        <!-- Header de l'entreprise -->
        <div class="company-header">
            <h1><?php echo htmlspecialchars($company['name'] ?: 'Sans nom'); ?></h1>
            <div class="company-meta">
                <?php if ($company['domain']): ?>
                    <span>📁 <?php echo htmlspecialchars($company['domain']); ?></span>
                <?php endif; ?>
                <?php if ($company['hq_location']): ?>
                    <span>📍 <?php echo htmlspecialchars($company['hq_location']); ?></span>
                <?php endif; ?>
                <?php if ($company['country']): ?>
                    <span>🌍 <?php echo htmlspecialchars($company['country']); ?></span>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="label">Offres d'emploi</div>
                <div class="value"><?php echo $company['jobs_count']; ?></div>
            </div>
            <div class="stat-card">
                <div class="label">Offres approuvées</div>
                <div class="value"><?php echo $company['approved_jobs_count']; ?></div>
            </div>
            <div class="stat-card">
                <div class="label">Comptes utilisateurs</div>
                <div class="value"><?php echo $company['accounts_count']; ?></div>
            </div>
            <?php if ($company['capacity']): ?>
            <div class="stat-card">
                <div class="label">Effectif</div>
                <div class="value"><?php echo $company['capacity']; ?></div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Informations générales -->
        <div class="info-section">
            <h2>Informations générales</h2>
            <div class="info-grid">
                <div class="info-label">Nom de l'entreprise:</div>
                <div class="info-value"><?php echo htmlspecialchars($company['name']) ?: '<span class="empty-value">Non renseigné</span>'; ?></div>
                
                <div class="info-label">Numéro client:</div>
                <div class="info-value"><?php echo $company['customer_number'] ? htmlspecialchars($company['customer_number']) : '<span class="empty-value">Non renseigné</span>'; ?></div>
                
                <div class="info-label">SIRET/Registre:</div>
                <div class="info-value"><?php echo $company['nation_register_number'] ? htmlspecialchars($company['nation_register_number']) : '<span class="empty-value">Non renseigné</span>'; ?></div>
                
                <div class="info-label">Secteur d'activité:</div>
                <div class="info-value"><?php echo $company['domain'] ? htmlspecialchars($company['domain']) : '<span class="empty-value">Non renseigné</span>'; ?></div>
                
                <div class="info-label">Effectif:</div>
                <div class="info-value"><?php echo $company['capacity'] ? $company['capacity'] . ' employés' : '<span class="empty-value">Non renseigné</span>'; ?></div>
                
                <div class="info-label">Siège social:</div>
                <div class="info-value"><?php echo $company['hq_location'] ? htmlspecialchars($company['hq_location']) : '<span class="empty-value">Non renseigné</span>'; ?></div>
                
                <div class="info-label">Pays:</div>
                <div class="info-value"><?php echo $company['country'] ? htmlspecialchars($company['country']) : '<span class="empty-value">Non renseigné</span>'; ?></div>
                
                <div class="info-label">Recrutement actif:</div>
                <div class="info-value">
                    <?php if ($company['recruiting']): ?>
                        <span class="badge badge-success">✓ Oui</span>
                    <?php else: ?>
                        <span class="badge badge-danger">✗ Non</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Description -->
        <?php if ($company['description']): ?>
        <div class="info-section">
            <h2>Description de l'entreprise</h2>
            <div class="description-box">
                <?php echo nl2br(htmlspecialchars($company['description'])); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Contact -->
        <?php if ($company['contact_id']): ?>
        <div class="info-section">
            <h2>Personne de contact</h2>
            <div class="info-grid">
                <div class="info-label">Nom:</div>
                <div class="info-value"><?php echo htmlspecialchars($company['contact_first_name'] . ' ' . $company['contact_last_name']); ?></div>
                
                <div class="info-label">Email:</div>
                <div class="info-value">
                    <a href="mailto:<?php echo htmlspecialchars($company['contact_email']); ?>" style="color: #667eea; text-decoration: none;">
                        <?php echo htmlspecialchars($company['contact_email']); ?>
                    </a>
                </div>
                
                <div class="info-label">Téléphone:</div>
                <div class="info-value">
                    <a href="tel:<?php echo htmlspecialchars($company['contact_phone']); ?>" style="color: #667eea; text-decoration: none;">
                        <?php echo htmlspecialchars($company['contact_phone']); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Dates -->
        <div class="info-section">
            <h2>Dates</h2>
            <div class="info-grid">
                <div class="info-label">Créée le:</div>
                <div class="info-value"><?php echo date('d/m/Y à H:i', strtotime($company['created_at'])); ?></div>
                
                <div class="info-label">Dernière mise à jour:</div>
                <div class="info-value"><?php echo date('d/m/Y à H:i', strtotime($company['updated_at'])); ?></div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Charger les fonctions de statistiques
require_once __DIR__ . '/../../../../../../function/getStatistic.php';

// Récupérer les statistiques
$monthlyData = getRegistrationsByMonth(2026);
$year = 2026;

$totalCandidates = getTotalApprovedCandidates();
$totalCompanies = getTotalApprovedCompanies();
$totalJobs = getTotalApprovedJobs();
$pendingJobs = getPendingJobApprovals();
$pendingCandidates = getPendingCandidateApprovals();
$totalRejections = getTotalRejections();
?>

<div class="dashboard-statistics">
    <!-- Bloc 1: Graphique des inscriptions mensuelles -->
    <?php include __DIR__ . '/blocks/graphic.php'; ?>
    
    <!-- Blocs 2-7: Statistiques -->
    <div class="stats-grid">
        <!-- Bloc 2: Candidats approuvés -->
        <?php 
            $title = 'Candidats approuvés';
            $value = $totalCandidates;
            $icon = '👤';
            $color = 'blue';
            $link = $baseUrl . '/' . $lang . '/user-account/administrator/manage-candidates';
            $hasNotification = false;
            include __DIR__ . '/blocks/stat.php';
        ?>
        
        <!-- Bloc 3: Entreprises approuvées -->
        <?php 
            $title = 'Entreprises approuvées';
            $value = $totalCompanies;
            $icon = '🏢';
            $color = 'green';
            $link = $baseUrl . '/' . $lang . '/user-account/administrator/manage-companies';
            $hasNotification = false;
            include __DIR__ . '/blocks/stat.php';
        ?>
        
        <!-- Bloc 4: Offres d'emploi approuvées -->
        <?php 
            $title = 'Offres d\'emploi approuvées';
            $value = $totalJobs;
            $icon = '💼';
            $color = 'purple';
            $link = $baseUrl . '/' . $lang . '/user-account/administrator/manage-jobs';
            $hasNotification = false;
            include __DIR__ . '/blocks/stat.php';
        ?>
        
        <!-- Bloc 5: Demandes d'approbation d'offres d'emploi -->
        <?php 
            $title = 'Offres en attente';
            $value = $pendingJobs['count'];
            $icon = '⏳';
            $color = 'orange';
            $link = $baseUrl . '/' . $lang . '/user-account/administrator/manage-jobs';
            $hasNotification = $pendingJobs['hasRecent'];
            include __DIR__ . '/blocks/stat.php';
        ?>
        
        <!-- Bloc 6: Demandes d'approbation de CV -->
        <?php 
            $title = 'CV en attente';
            $value = $pendingCandidates['count'];
            $icon = '📄';
            $color = 'orange';
            $link = $baseUrl . '/' . $lang . '/user-account/administrator/manage-candidates';
            $hasNotification = $pendingCandidates['hasRecent'];
            include __DIR__ . '/blocks/stat.php';
        ?>
        
        <!-- Bloc 7: Refus d'approbation -->
        <?php 
            $title = 'Refus d\'approbation';
            $value = $totalRejections;
            $icon = '❌';
            $color = 'red';
            $link = null;
            $hasNotification = false;
            include __DIR__ . '/blocks/stat.php';
        ?>
    </div>
</div>

<style>
.dashboard-statistics {
    padding: 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 0;
}

/* Styles pour les blocs de statistiques */
.stat-block {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-block::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.stat-block:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.stat-notification {
    position: absolute;
    top: 15px;
    right: 15px;
    color: #ef4444;
    font-size: 1.5rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.stat-content {
    margin-bottom: 15px;
}

.stat-title {
    font-size: 0.95rem;
    color: #6b7280;
    margin: 0 0 10px 0;
    font-weight: 500;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.stat-link {
    display: inline-block;
    color: #667eea;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.stat-link:hover {
    color: #764ba2;
    transform: translateX(5px);
}

/* Couleurs spécifiques */
.stat-blue::before {
    background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
}

.stat-blue .stat-value {
    color: #3b82f6;
}

.stat-green::before {
    background: linear-gradient(90deg, #10b981 0%, #059669 100%);
}

.stat-green .stat-value {
    color: #10b981;
}

.stat-purple::before {
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.stat-purple .stat-value {
    color: #667eea;
}

.stat-orange::before {
    background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
}

.stat-orange .stat-value {
    color: #f59e0b;
}

.stat-red::before {
    background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
}

.stat-red .stat-value {
    color: #ef4444;
}

.stat-gray::before {
    background: linear-gradient(90deg, #6b7280 0%, #4b5563 100%);
}

.stat-gray .stat-value {
    color: #6b7280;
}

/* Responsive */
@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-value {
        font-size: 1.75rem;
    }
    
    .stat-icon {
        font-size: 2rem;
    }
}
</style>
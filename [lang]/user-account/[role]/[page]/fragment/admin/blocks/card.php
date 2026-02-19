<?php
/**
 * Carte de candidat
 * 
 * Variables attendues:
 * - $candidate: Array avec les données du candidat
 *   - id, media_code, approved, first_name, last_name, job_title, 
 *     location, email, age, photo_url
 */

$candidate = $candidate ?? [];
$candidateId = $candidate['id'] ?? 0;
$firstName = $candidate['first_name'] ?? 'Prénom';
$lastName = $candidate['last_name'] ?? 'Nom';
$jobTitle = $candidate['job_title'] ?? 'Métier non renseigné';
$location = $candidate['location'] ?? '';
$email = $candidate['email'] ?? '';
$age = $candidate['age'] ?? '';
$photoUrl = $candidate['photo_url'] ?? 'data/candidates/media/default/photo.jpg';
$approved = $candidate['approved'] ?? null;

// Déterminer le statut
$statusInfo = [
    'class' => '',
    'label' => '',
    'icon' => ''
];

if ($approved === '1' || $approved === 1) {
    $statusInfo = [
        'class' => 'status-approved',
        'label' => 'Approuvé',
        'icon' => '✓'
    ];
} elseif ($approved === '0' || $approved === 0) {
    $statusInfo = [
        'class' => 'status-rejected',
        'label' => 'Refusé',
        'icon' => '✗'
    ];
} else {
    $statusInfo = [
        'class' => 'status-pending',
        'label' => 'En attente',
        'icon' => '⏳'
    ];
}

$cvUrl = $baseUrl . '/' . $lang . '/data/candidates/cv/' . $candidateId;
?>

<div class="candidate-card" data-candidate-id="<?php echo $candidateId; ?>">
    <!-- Badge de statut -->
    <div class="candidate-status <?php echo $statusInfo['class']; ?>">
        <span class="status-icon"><?php echo $statusInfo['icon']; ?></span>
        <span class="status-label"><?php echo $statusInfo['label']; ?></span>
    </div>
    
    <!-- Photo du candidat -->
    <div class="candidate-photo">
        <img src="<?php echo $baseUrl . '/' . htmlspecialchars($photoUrl); ?>" 
             alt="Photo de <?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>"
             onerror="this.src='<?php echo $baseUrl; ?>/data/candidates/media/default/photo.jpg'">
    </div>
    
    <!-- Informations du candidat -->
    <div class="candidate-info">
        <h3 class="candidate-name">
            <?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>
        </h3>
        
        <p class="candidate-job-title">
            <?php echo htmlspecialchars($jobTitle); ?>
        </p>
        
        <?php if ($location): ?>
            <p class="candidate-location">
                📍 <?php echo htmlspecialchars($location); ?>
            </p>
        <?php endif; ?>
        
        <?php if ($age): ?>
            <p class="candidate-age">
                🎂 <?php echo $age; ?> ans
            </p>
        <?php endif; ?>
    </div>
    
    <!-- Actions -->
    <div class="candidate-actions">
        <button class="btn-action btn-view" 
                data-candidate-id="<?php echo $candidateId; ?>"
                title="Voir le CV">
            <span class="btn-icon">👁️</span>
            <span class="btn-text">Voir CV</span>
        </button>
        
        <?php if ($email): ?>
            <a href="mailto:<?php echo htmlspecialchars($email); ?>" 
               class="btn-action btn-email"
               title="Envoyer un email">
                <span class="btn-icon">✉️</span>
                <span class="btn-text">Email</span>
            </a>
        <?php endif; ?>
    </div>
    
    <!-- Boutons d'approbation (uniquement si en attente) -->
    <?php if ($approved === null || $approved === 'null' || $approved === ''): ?>
        <div class="approval-actions">
            <button class="btn-approve" 
                    data-candidate-id="<?php echo $candidateId; ?>" 
                    data-action="approve"
                    title="Approuver ce candidat">
                <span class="btn-icon">✓</span>
                <span class="btn-text">Approuver</span>
            </button>
            
            <button class="btn-reject" 
                    data-candidate-id="<?php echo $candidateId; ?>" 
                    data-action="reject"
                    title="Refuser ce candidat">
                <span class="btn-icon">✗</span>
                <span class="btn-text">Refuser</span>
            </button>
        </div>
    <?php endif; ?>
</div>

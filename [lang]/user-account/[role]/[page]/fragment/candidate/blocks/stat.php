<?php
/**
 * Bloc de statistique dynamique
 * Utilisé pour afficher une statistique avec un titre, une valeur et une icône
 * 
 * Variables attendues:
 * - $title: Titre de la statistique
 * - $value: Valeur numérique à afficher
 * - $icon: Icône à afficher (optionnel)
 * - $hasNotification: Badge de notification pour nouveauté (optionnel)
 * - $link: Lien vers la page de détail (optionnel)
 * - $color: Couleur du thème (optionnel: 'blue', 'green', 'orange', 'red', 'purple', 'gray')
 */

$title = $title ?? 'Statistique';
$value = $value ?? 0;
$icon = $icon ?? '📊';
$hasNotification = $hasNotification ?? false;
$link = $link ?? null;
$color = $color ?? 'blue';

// Définir les couleurs selon le thème
$colorClasses = [
    'blue' => 'stat-blue',
    'green' => 'stat-green',
    'orange' => 'stat-orange',
    'red' => 'stat-red',
    'purple' => 'stat-purple',
    'gray' => 'stat-gray'
];

$colorClass = $colorClasses[$color] ?? 'stat-blue';
?>

<div class="stat-block <?php echo $colorClass; ?>">
    <?php if ($hasNotification): ?>
        <span class="stat-notification">●</span>
    <?php endif; ?>
    
    <div class="stat-icon"><?php echo $icon; ?></div>
    
    <div class="stat-content">
        <h3 class="stat-title"><?php echo htmlspecialchars($title); ?></h3>
        <p class="stat-value"><?php echo number_format($value, 0, ',', ' '); ?></p>
    </div>
    
    <?php if ($link): ?>
        <a href="<?php echo $link; ?>" class="stat-link">Voir détails →</a>
    <?php endif; ?>
</div>

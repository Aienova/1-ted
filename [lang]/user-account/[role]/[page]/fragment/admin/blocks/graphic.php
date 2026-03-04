<?php
/**
 * Bloc graphique des inscriptions mensuelles
 * Affiche un graphique des inscriptions par mois sur l'année
 * 
 * Variables attendues:
 * - $monthlyData: Tableau des données mensuelles [1-12 => nombre]
 * - $year: Année concernée
 * - $maxYAxis: Maximum de l'axe Y (par défaut: 100)
 */

$monthlyData = $monthlyData ?? array_fill(1, 12, 0);
$year = $year ?? 2026;
$maxYAxis = $maxYAxis ?? 100;

// Noms des mois en français
$monthNames = [
    1 => 'Jan', 2 => 'Fév', 3 => 'Mar', 4 => 'Avr',
    5 => 'Mai', 6 => 'Juin', 7 => 'Juil', 8 => 'Aoû',
    9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'
];

// Calculer le maximum pour l'échelle
$maxValue = max($monthlyData);
$maxValue = $maxValue > 0 ? $maxValue : 10; // Minimum 10 pour éviter division par zéro

// Hauteur maximale du graphique en pixels
$maxHeight = 250; // Hauteur maximale en pixels

// Calculer le total
$totalRegistrations = array_sum($monthlyData);
?>

<div class="graphic-block">
    <div class="graphic-header">
        <h3>📈 Inscriptions par mois (<?php echo $year; ?>)</h3>
        <p class="graphic-total">Total: <strong><?php echo number_format($totalRegistrations, 0, ',', ' '); ?></strong> inscriptions</p>
    </div>
    
    <div class="graphic-chart">
        <div class="graphic-y-axis">
            <?php for ($i = $maxYAxis; $i >= 0; $i -= max(1, floor($maxYAxis / 5))): ?>
                <span class="y-label"><?php echo $i; ?></span>
            <?php endfor; ?>
        </div>
        
        <div class="graphic-bars">
            <?php foreach ($monthlyData as $month => $count): ?>
                <?php $barHeight = $maxYAxis > 0 ? round(($count / $maxYAxis) * $maxHeight) : 0; ?>
                <div class="bar-container">
                    <div 
                        class="bar" 
                        style="height: <?php echo $barHeight; ?>px;"
                        data-count="<?php echo $count; ?>"
                    >
                        <span class="bar-value"><?php echo $count; ?></span>
                    </div>
                    <span class="bar-label"><?php echo $monthNames[$month]; ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
.graphic-block {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 25px;
}

.graphic-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e5e7eb;
}

.graphic-header h3 {
    font-size: 1.4rem;
    color: #1f2937;
    margin: 0;
}

.graphic-total {
    color: #6b7280;
    margin: 0;
    font-size: 1rem;
}

.graphic-total strong {
    color: #667eea;
    font-size: 1.2rem;
}

.graphic-chart {
    display: flex;
    gap: 15px;
    position: relative;
    height: 300px; /* Hauteur fixe : 250px de graphique + 50px pour l'axe Y et padding */
}

.graphic-y-axis {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding-right: 10px;
    min-width: 40px;
    color: #6b7280;
    font-size: 0.85rem;
}

.y-label {
    text-align: right;
}

.graphic-bars {
    flex: 1;
    display: flex;
    align-items: flex-end;
    justify-content: space-around;
    gap: 8px;
    border-left: 2px solid #e5e7eb;
    border-bottom: 2px solid #e5e7eb;
    padding: 10px 10px 0 10px;
    position: relative;
    height: 250px; /* Hauteur fixe correspondant à $maxHeight */
}

.bar-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    min-width: 30px;
}

.bar {
    width: 100%;
    background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
    border-radius: 6px 6px 0 0;
    transition: all 0.3s ease;
    position: relative;
    min-height: 5px;
    cursor: pointer;
}

.bar:hover {
    filter: brightness(1.15);
    transform: scaleY(1.05);
}

.bar-value {
    position: absolute;
    top: -25px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 0.85rem;
    font-weight: 600;
    color: #1f2937;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.bar:hover .bar-value {
    opacity: 1;
}

.bar-label {
    font-size: 0.8rem;
    color: #6b7280;
    font-weight: 500;
    text-align: center;
}

@media (max-width: 768px) {
    .graphic-chart {
        height: 250px;
    }
    
    .graphic-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .bar-label {
        font-size: 0.7rem;
    }
    
    .graphic-y-axis {
        font-size: 0.75rem;
    }
}
</style>

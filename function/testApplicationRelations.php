<?php
/**
 * Exemple d'utilisation des relations dans Application
 * 
 * Ce fichier montre comment utiliser la classe Application avec ses relations
 */

require_once __DIR__ . '/connectionDatabase.php';
require_once __DIR__ . '/getData.php';

// Récupérer une application par son ID
$applicationId = 1;
$application = getData('application', $applicationId, true);

if ($application) {
    echo "<h2>Application #" . $application->getId() . "</h2>";
    echo "<p><strong>Statut:</strong> " . $application->getStatus() . "</p>";
    
    // Afficher les informations du candidat (via la relation)
    $candidate = $application->getCandidate();
    if ($candidate) {
        echo "<h3>Candidat:</h3>";
        echo "<ul>";
        echo "<li>ID: " . $candidate->getId() . "</li>";
        echo "<li>Identité ID: " . $candidate->getIdentityId() . "</li>";
        echo "</ul>";
    }
    
    // Afficher les informations de l'offre d'emploi (via la relation)
    $job = $application->getJob();
    if ($job) {
        echo "<h3>Offre d'emploi:</h3>";
        echo "<ul>";
        echo "<li>ID: " . $job->getId() . "</li>";
        echo "<li>Titre: " . htmlspecialchars($job->getTitle()) . "</li>";
        echo "<li>Description: " . htmlspecialchars($job->getDescription()) . "</li>";
        echo "<li>Localisation: " . htmlspecialchars($job->getLocation()) . "</li>";
        echo "<li>Salaire: " . $job->getSalary() . " €</li>";
        echo "</ul>";
    }
    
    echo "<h3>Validations:</h3>";
    echo "<ul>";
    echo "<li>Validation recruteur: " . ($application->isRecruiterValidation() ? 'Oui' : 'Non') . "</li>";
    echo "<li>Validation candidat: " . ($application->isCandidateValidation() ? 'Oui' : 'Non') . "</li>";
    echo "</ul>";
    
    if ($application->getComment()) {
        echo "<h3>Commentaire:</h3>";
        echo "<p>" . htmlspecialchars($application->getComment()) . "</p>";
    }
    
} else {
    echo "<p>Application non trouvée.</p>";
}

echo "<hr>";
echo "<h2>Toutes les applications (avec relations):</h2>";

// Récupérer toutes les applications
$allApplications = getData('application', null, true);

if (count($allApplications) > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Candidat ID</th>";
    echo "<th>Job ID</th>";
    echo "<th>Titre du poste</th>";
    echo "<th>Statut</th>";
    echo "<th>Validation recruteur</th>";
    echo "<th>Validation candidat</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    foreach ($allApplications as $app) {
        $job = $app->getJob();
        $jobTitle = $job ? $job->getTitle() : 'N/A';
        
        echo "<tr>";
        echo "<td>" . $app->getId() . "</td>";
        echo "<td>" . $app->getCandidateId() . "</td>";
        echo "<td>" . $app->getJobId() . "</td>";
        echo "<td>" . htmlspecialchars($jobTitle) . "</td>";
        echo "<td>" . $app->getStatus() . "</td>";
        echo "<td>" . ($app->isRecruiterValidation() ? '✓' : '✗') . "</td>";
        echo "<td>" . ($app->isCandidateValidation() ? '✓' : '✗') . "</td>";
        echo "</tr>";
    }
    
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>Aucune application trouvée.</p>";
}

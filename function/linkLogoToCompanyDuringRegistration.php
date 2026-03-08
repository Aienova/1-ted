<?php
/**
 * Récupère le logo en base64 et le media_code de l'entreprise, puis uploade le logo
 * Cette fonction est appelée lors de la validation du formulaire registerPopup
 * 
 * @param int $companyId - L'ID de l'entreprise
 * @param string $logoData - Les données du logo en base64 ou URL
 * @return array ['success' => bool, 'logoUrl' => string|null, 'message' => string]
 */

header('Content-Type: application/json');
@ini_set('display_errors', 0);
@error_reporting(E_ERROR | E_PARSE);

require_once __DIR__ . '/connectionDatabase.php';

function linkLogoToCompanyDuringRegistration($companyId, $logoData) {
    try {
        $pdo = getDatabaseConnection();
        
        // Validation
        if (empty($companyId)) {
            return [
                'success' => false,
                'logoUrl' => null,
                'message' => 'ID de l\'entreprise requis'
            ];
        }
        
        if (empty($logoData)) {
            return [
                'success' => false,
                'logoUrl' => null,
                'message' => 'Données du logo requises'
            ];
        }
        
        // Si c'est déjà une URL (logo déjà uploadé), on retourne directement
        if (strpos($logoData, 'http') === 0 || strpos($logoData, '/data/') === 0) {
            return [
                'success' => true,
                'logoUrl' => $logoData,
                'message' => 'Logo déjà uploadé',
                'alreadyUploaded' => true
            ];
        }
        
        // Récupérer le media_code de l'entreprise
        $sql = "SELECT media_code FROM company WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $companyId, PDO::PARAM_INT);
        $stmt->execute();
        
        $company = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$company) {
            return [
                'success' => false,
                'logoUrl' => null,
                'message' => 'Entreprise non trouvée'
            ];
        }
        
        $mediaCode = $company['media_code'];
        
        if (empty($mediaCode)) {
            return [
                'success' => false,
                'logoUrl' => null,
                'message' => 'Media code manquant pour cette entreprise'
            ];
        }
        
        // Créer le dossier si nécessaire
        $mediaDir = __DIR__ . '/../data/company/media/' . $mediaCode;
        if (!file_exists($mediaDir)) {
            if (!mkdir($mediaDir, 0755, true)) {
                return [
                    'success' => false,
                    'logoUrl' => null,
                    'message' => 'Impossible de créer le dossier media'
                ];
            }
        }
        
        // Décoder et sauvegarder le logo
        if (preg_match('/^data:image\/(\w+);base64,/', $logoData, $matches)) {
            $imageType = $matches[1];
            $base64Data = substr($logoData, strpos($logoData, ',') + 1);
            $decodedData = base64_decode($base64Data);
            
            if ($decodedData === false) {
                return [
                    'success' => false,
                    'logoUrl' => null,
                    'message' => 'Impossible de décoder l\'image base64'
                ];
            }
            
            // Sauvegarder en PNG
            $filePath = $mediaDir . '/logo.png';
            $urlPath = '/data/company/media/' . $mediaCode . '/logo.png';
            
            if (file_put_contents($filePath, $decodedData) === false) {
                return [
                    'success' => false,
                    'logoUrl' => null,
                    'message' => 'Impossible d\'écrire le fichier logo'
                ];
            }
            
            return [
                'success' => true,
                'logoUrl' => $urlPath,
                'mediaCode' => $mediaCode,
                'message' => 'Logo uploadé avec succès'
            ];
            
        } else {
            return [
                'success' => false,
                'logoUrl' => null,
                'message' => 'Format base64 invalide'
            ];
        }
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'logoUrl' => null,
            'message' => 'Erreur: ' . $e->getMessage()
        ];
    }
}

// Si appelé directement via HTTP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonInput = file_get_contents('php://input');
    $data = json_decode($jsonInput, true);
    
    $companyId = $data['company_id'] ?? null;
    $logoData = $data['logo_data'] ?? null;
    
    $result = linkLogoToCompanyDuringRegistration($companyId, $logoData);
    echo json_encode($result);
    exit;
}

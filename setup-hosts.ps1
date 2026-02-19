# Script pour ajouter 1-ted.local au fichier hosts
# DOIT ÊTRE EXÉCUTÉ EN TANT QU'ADMINISTRATEUR

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Configuration du fichier hosts pour 1-TED" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$hostsPath = "C:\Windows\System32\drivers\etc\hosts"
$backupPath = "C:\Windows\System32\drivers\etc\hosts.backup"
$hostsEntry = "127.0.0.1    1-ted.local"

# Créer une sauvegarde
Write-Host "[1/4] Création d'une sauvegarde..." -ForegroundColor Yellow
try {
    Copy-Item -Path $hostsPath -Destination $backupPath -Force
    Write-Host "✓ Sauvegarde créée: $backupPath" -ForegroundColor Green
} catch {
    Write-Host "⚠ Impossible de créer la sauvegarde" -ForegroundColor Yellow
}
Write-Host ""

# Nettoyer et corriger le fichier hosts
Write-Host "[2/4] Nettoyage et correction du fichier hosts..." -ForegroundColor Yellow
try {
    # Lire le fichier hosts
    $hostsContent = Get-Content $hostsPath
    
    # Corriger les lignes mal formatées (collées)
    $hostsContent = $hostsContent -replace 'localhost127\.0\.0\.1', 'localhost'
    
    # Supprimer les anciennes entrées 1-ted.local
    $hostsContent = $hostsContent | Where-Object { $_ -notmatch '^127\.0\.0\.1\s+1-ted\.local' }
    
    # Ajouter la nouvelle entrée correctement formatée
    $hostsContent += ""
    $hostsContent += $hostsEntry
    
    # Écrire le fichier corrigé
    $hostsContent | Set-Content $hostsPath -Force
    
    Write-Host "✓ Fichier hosts corrigé et mis à jour!" -ForegroundColor Green
} catch {
    Write-Host "✗ Erreur: $_" -ForegroundColor Red
    Write-Host "Veuillez exécuter ce script en tant qu'administrateur" -ForegroundColor Red
    pause
    exit 1
}
Write-Host ""

Write-Host "[3/4] Vidage du cache DNS..." -ForegroundColor Yellow
ipconfig /flushdns | Out-Null
Write-Host "✓ Cache DNS vidé" -ForegroundColor Green
Write-Host ""

Write-Host "[4/4] Test de la résolution DNS..." -ForegroundColor Yellow
$testResult = Test-Connection -ComputerName "1-ted.local" -Count 1 -Quiet
if ($testResult) {
    Write-Host "✓ 1-ted.local résout correctement vers 127.0.0.1" -ForegroundColor Green
} else {
    Write-Host "⚠ La résolution DNS ne fonctionne pas encore. Redémarrez votre navigateur." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Configuration terminée!" -ForegroundColor Green
Write-Host "Vous pouvez maintenant accéder à: http://1-ted.local" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Prochaines étapes:" -ForegroundColor Yellow
Write-Host "1. Redémarrez Apache via XAMPP Control Panel" -ForegroundColor White
Write-Host "2. Ouvrez votre navigateur sur: http://1-ted.local" -ForegroundColor White
Write-Host ""
Write-Host "Note: Une sauvegarde a été créée: $backupPath" -ForegroundColor Gray
Write-Host ""
pause

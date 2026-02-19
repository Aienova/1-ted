@echo off
:: Configuration automatique de 1-ted.local
:: Ce script doit être exécuté en tant qu'administrateur

echo ========================================
echo Configuration de 1-ted.local
echo ========================================
echo.

:: Vérifier les droits administrateur
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo ERREUR: Ce script necessite les droits administrateur.
    echo.
    echo Faites un clic droit sur ce fichier et selectionnez
    echo "Executer en tant qu'administrateur"
    echo.
    pause
    exit /b 1
)

set HOSTS_FILE=%WINDIR%\System32\drivers\etc\hosts
set TEMP_FILE=%TEMP%\hosts_temp.txt
set BACKUP_FILE=%WINDIR%\System32\drivers\etc\hosts.backup

:: Créer une sauvegarde
echo [1/4] Creation d'une sauvegarde...
copy /Y "%HOSTS_FILE%" "%BACKUP_FILE%" >nul 2>&1
echo Sauvegarde creee: %BACKUP_FILE%
echo.

:: Nettoyer et corriger le fichier hosts
echo [2/4] Nettoyage et correction du fichier hosts...

:: Lire le fichier hosts et corriger les lignes collées
powershell -Command "$content = Get-Content '%HOSTS_FILE%'; $corrected = $content -replace 'localhost127\.0\.0\.1', 'localhost'; $corrected = $corrected | Where-Object { $_ -notmatch '^127\.0\.0\.1\s+1-ted\.local' }; $corrected | Set-Content '%TEMP_FILE%'"

:: Ajouter l'entrée 1-ted.local sur une nouvelle ligne
echo 127.0.0.1    1-ted.local >> "%TEMP_FILE%"

:: Remplacer le fichier hosts
copy /Y "%TEMP_FILE%" "%HOSTS_FILE%" >nul 2>&1
del "%TEMP_FILE%" >nul 2>&1

echo Fichier hosts corrige et mis a jour!
echo.


:: Vider le cache DNS
echo [3/4] Vidage du cache DNS...
ipconfig /flushdns >nul 2>&1
echo Cache DNS vide.
echo.

:: Tester la résolution
echo [4/4] Test de la resolution DNS...
ping 1-ted.local -n 1 -w 1000 >nul 2>&1
if %errorLevel% equ 0 (
    echo Succes! 1-ted.local resout correctement.
) else (
    echo Attention: La resolution ne fonctionne pas encore.
    echo Redemarrez votre navigateur et Apache.
)
echo.

echo ========================================
echo Configuration terminee!
echo ========================================
echo.
echo Prochaines etapes:
echo 1. Redemarrez Apache via XAMPP Control Panel
echo 2. Ouvrez votre navigateur sur: http://1-ted.local
echo.
echo Note: Une sauvegarde a ete creee: %BACKUP_FILE%
echo.
pause

# Configuration VirtualHost pour 1-TED Backoffice

## ⚠️ PROBLÈME DÉTECTÉ : DNS_PROBE_FINISHED_NXDOMAIN

Le fichier hosts n'a pas été modifié correctement à cause des permissions Windows.

## 🔧 SOLUTION RAPIDE (2 ÉTAPES)

### ÉTAPE 1 : Modifier le fichier hosts (REQUIS)

**Option A - Script automatique (Recommandé) :**
1. Faites un **clic droit** sur le fichier `setup-hosts.ps1` dans ce dossier
2. Sélectionnez **"Exécuter avec PowerShell"**
3. Acceptez la demande UAC (droits administrateur)
4. Le script va ajouter l'entrée et vider le cache DNS

**Option B - Modification manuelle :**
1. Ouvrez **Notepad** en tant qu'administrateur :
   - Tapez `notepad` dans le menu Démarrer
   - Clic droit → "Exécuter en tant qu'administrateur"
2. Ouvrez le fichier : `C:\Windows\System32\drivers\etc\hosts`
3. Ajoutez cette ligne à la fin du fichier :
   ```
   127.0.0.1    1-ted.local
   ```
4. Sauvegardez le fichier (Ctrl+S)
5. Ouvrez PowerShell en tant qu'administrateur et exécutez :
   ```powershell
   ipconfig /flushdns
   ```

### ÉTAPE 2 : Redémarrer Apache

1. Ouvrez **XAMPP Control Panel**
2. Cliquez sur **Stop** pour Apache
3. Cliquez sur **Start** pour redémarrer Apache

### ÉTAPE 3 : Tester

Ouvrez votre navigateur et accédez à : **http://1-ted.local**

---

## 📋 Résumé de la configuration effectuée

Les modifications suivantes ont été préparées :

### 1. Fichier hosts Windows
**Emplacement :** `C:\Windows\System32\drivers\etc\hosts`

Ajout de l'entrée :
```
127.0.0.1    1-ted.local
```

### 2. Configuration Apache VirtualHost
**Emplacement :** `C:\xampp\apache\conf\extra\httpd-vhosts.conf`

Configuration ajoutée :
```apache
# VirtualHost pour 1-TED Backoffice
<VirtualHost *:80>
    ServerName 1-ted.local
    ServerAlias www.1-ted.local
    DocumentRoot "C:/xampp/htdocs/1-ted-backoffice"
    
    <Directory "C:/xampp/htdocs/1-ted-backoffice">
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "logs/1-ted-error.log"
    CustomLog "logs/1-ted-access.log" combined
</VirtualHost>
```

## 🔄 Redémarrer Apache

Pour appliquer la configuration, vous devez redémarrer Apache :

### Option 1 : Via XAMPP Control Panel (Recommandé)
1. Ouvrez **XAMPP Control Panel** (xampp-control.exe)
2. Cliquez sur le bouton **Stop** à côté d'Apache
3. Attendez que le service s'arrête
4. Cliquez sur le bouton **Start** pour redémarrer Apache

### Option 2 : Via PowerShell (sans service)
Si Apache n'est pas installé comme service, utilisez ces commandes :
```powershell
# Arrêter Apache
C:\xampp\apache\bin\httpd.exe -k stop

# Démarrer Apache
C:\xampp\apache\bin\httpd.exe -k start
```

## 🌐 Accès au site

Après le redémarrage d'Apache, vous pouvez accéder au site via :

- **URL principale :** http://1-ted.local
- **Avec www :** http://www.1-ted.local

**Ancien accès (toujours fonctionnel) :** http://1-ted.local/

## ⚠️ Vérifications

### Tester la configuration Apache
```powershell
C:\xampp\apache\bin\httpd.exe -t
```
Cette commande teste la configuration. Vous devriez voir "Syntax OK".

### Tester la résolution DNS
```powershell
ping 1-ted.local
```
Devrait répondre depuis 127.0.0.1

### Logs d'erreur
Si vous rencontrez des problèmes, consultez les logs :
- **Erreurs 1-TED :** `C:\xampp\apache\logs\1-ted-error.log`
- **Erreurs Apache générales :** `C:\xampp\apache\logs\error.log`

## 🔧 Modifications dans le code

Vous devrez peut-être mettre à jour les variables `$baseUrl` dans votre code PHP pour utiliser :
```php
$baseUrl = 'http://1-ted.local';
```

Au lieu de :
```php
$baseUrl = 'http://1-ted.local/';
```

## 🎉 Avantages du VirtualHost

✅ URLs plus propres et professionnelles  
✅ Chemins relatifs simplifiés  
✅ Meilleure simulation d'un environnement de production  
✅ Évite les problèmes de sous-dossiers  
✅ Logs séparés pour chaque projet  

---

**Note :** Si vous avez besoin de supprimer cette configuration, commentez simplement le bloc `<VirtualHost>` dans le fichier `httpd-vhosts.conf` et redémarrez Apache.

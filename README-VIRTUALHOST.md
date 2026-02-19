# 🚀 Configuration Virtualhost 1-ted.local

## ⚡ GUIDE RAPIDE - 3 ÉTAPES

### 1️⃣ Exécuter le script de configuration

Dans ce dossier, vous trouverez le fichier **`setup-hosts.ps1`**

**→ Faites un clic droit dessus et sélectionnez "Exécuter avec PowerShell"**

Une fenêtre PowerShell va s'ouvrir et demander les droits administrateur (UAC) → **Acceptez**

Le script va :
- ✅ Ajouter `1-ted.local` au fichier hosts
- ✅ Vider le cache DNS
- ✅ Tester la configuration

---

### 2️⃣ Redémarrer Apache

Ouvrez **XAMPP Control Panel** et :
1. Cliquez sur **Stop** à côté d'Apache
2. Cliquez sur **Start** pour le redémarrer

---

### 3️⃣ Accéder au site

Ouvrez votre navigateur et allez sur : **http://1-ted.local**

---

## ❓ Si ça ne marche pas

### Test 1 : Vérifier le fichier hosts
Ouvrez une PowerShell et tapez :
```powershell
notepad C:\Windows\System32\drivers\etc\hosts
```

Vérifiez que vous avez cette ligne :
```
127.0.0.1    1-ted.local
```

Si elle n'est pas là, ajoutez-la et sauvegardez (vous devrez ouvrir Notepad en administrateur).

### Test 2 : Vider le cache DNS du navigateur
- **Chrome/Edge** : `chrome://net-internals/#dns` → "Clear host cache"
- **Firefox** : Redémarrez Firefox

### Test 3 : Vider le cache DNS Windows
Ouvrez PowerShell en administrateur :
```powershell
ipconfig /flushdns
```

### Test 4 : Ping
```powershell
ping 1-ted.local
```
Doit répondre : `127.0.0.1`

---

## 📝 Modification du code PHP

Pour que votre application utilise la nouvelle URL, modifiez les variables `$baseUrl` dans vos fichiers PHP :

**Ancien :**
```php
$baseUrl = 'http://1-ted.local/';
```

**Nouveau :**
```php
$baseUrl = 'http://1-ted.local';
```

Ou utilisez une détection automatique :
```php
$baseUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
```

---

## 🎯 Avantages

✅ URL professionnelle : `http://1-ted.local`  
✅ Plus de problèmes de sous-dossiers  
✅ Chemins relatifs simplifiés  
✅ Compatible avec tous les serveurs  

---

## 🔄 Pour désactiver

Si vous voulez revenir à l'ancienne configuration :

1. Ouvrez **Notepad en administrateur**
2. Ouvrez `C:\Windows\System32\drivers\etc\hosts`
3. Supprimez ou commentez la ligne (ajoutez # devant) :
   ```
   # 127.0.0.1    1-ted.local
   ```
4. Sauvegardez
5. Redémarrez Apache

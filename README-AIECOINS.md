# Documentation Système AIECoins

## Vue d'ensemble

Le système AIECoins est un système de monnaie virtuelle pour la plateforme 1-TED. Il permet de gérer les actions payantes des utilisateurs (candidats et recruteurs) et les conversions entre AIECoins et devises réelles.

## Structure de la base de données

### Table `action`
Contient toutes les actions possibles sur la plateforme.

**Colonnes :**
- `id` : Identifiant unique
- `name` : Nom de l'action (clé technique)
- `description` : Description de l'action
- `is_active` : Action active ou non (1/0)
- `created_at`, `updated_at` : Dates de création/modification

**Actions pour les candidats :**
- `candidate_community_advice` : Demander conseil (5 AIECoins)
- `candidate_direct_message` : Message direct entreprise (10 AIECoins)
- `candidate_boost_active` : Boost actif (20 AIECoins)
- `candidate_view_job_details` : Voir détails offre (30 AIECoins)
- `candidate_boost_important` : Boost important (40 AIECoins)
- `candidate_boost_urgent` : Boost urgent (60 AIECoins)

**Actions pour les recruteurs :**
- `company_view_profile_apprentice` : Voir profil Apprenti (5 AIECoins)
- `company_view_profile_junior` : Voir profil Junior (10 AIECoins)
- `company_view_profile_confirmed` : Voir profil Confirmé (20 AIECoins)
- `company_view_profile_senior` : Voir profil Senior (40 AIECoins)
- `company_boost_job_active` : Boost offre active (20 AIECoins)
- `company_boost_job_important` : Boost offre importante (40 AIECoins)
- `company_boost_job_urgent` : Boost offre urgente (60 AIECoins)

### Table `aiecourt`
Contient les prix des actions selon le contexte (pays, secteur d'activité).

**Colonnes :**
- `id` : Identifiant unique
- `activity_id` : ID du secteur d'activité (NULL = prix par défaut)
- `country_id` : ID du pays (NULL = prix par défaut)
- `action_id` : ID de l'action
- `price` : Prix en AIECoins
- `role` : Rôle concerné (candidate, recruiter, administrator)

**Logique de tarification :**
Le système récupère le prix dans cet ordre de priorité :
1. Prix spécifique : pays + activité + action + rôle
2. Prix par activité : activité + action + rôle
3. Prix par pays : pays + action + rôle
4. Prix par défaut : action + rôle

### Table `exchange`
Contient les taux de change entre AIECoins et devises réelles.

**Colonnes :**
- `id` : Identifiant unique
- `activity_id` : ID du secteur d'activité (NULL = taux par défaut)
- `country_id` : ID du pays (NULL = taux par défaut)
- `currency_code` : Code de la devise (EUR, USD, GBP, JPY, AUD, CAD, CHF, CNY, SEK, NZD)
- `rate` : Nombre d'AIECoins pour 1 unité de cette devise
- `created_at`, `updated_at` : Dates de création/modification

**Taux de change par défaut (base 1€ = 25 AIECoins) :**
- EUR (Euro) : 25 AIECoins
- USD (Dollar US) : 23 AIECoins
- GBP (Livre Sterling) : 30 AIECoins
- JPY (Yen Japonais) : 1 AIECoin
- AUD (Dollar Australien) : 15 AIECoins
- CAD (Dollar Canadien) : 17 AIECoins
- CHF (Franc Suisse) : 27 AIECoins
- CNY (Yuan Chinois) : 3 AIECoins
- SEK (Couronne Suédoise) : 2 AIECoins
- NZD (Dollar NZ) : 14 AIECoins

**Note importante :** Tous les taux sont des **entiers** car les AIECoins ne peuvent pas être fractionnés.

**Logique de conversion :**
Le système récupère le taux dans cet ordre de priorité :
1. Taux spécifique : pays + activité + devise
2. Taux par activité : activité + devise
3. Taux par pays : pays + devise
4. Taux par défaut : devise

## Fichiers PHP

### 1. `function/updateAiecoins.php`
Gestion des modifications de solde AIECoins.

**Fonctions :**
```php
// Mettre à jour le solde (ajouter ou retirer)
updateAiecoins($accountId, $amount, $reason);

// Ajouter des AIECoins
addAiecoins($accountId, $amount, $reason);

// Retirer des AIECoins
removeAiecoins($accountId, $amount, $reason);

// Obtenir le solde actuel
getAiecoinsBalance($accountId);

// Rafraîchir la session
refreshAiecoinsInSession($accountId);
```

**Exemple :**
```php
require_once 'function/updateAiecoins.php';

// Ajouter 100 AIECoins
$result = addAiecoins(123, 100, 'Achat de pack');

if ($result['success']) {
    echo "Nouveau solde : " . $result['newBalance'];
}
```

### 2. `function/getAiecoinsPrice.php`
Récupération des prix et gestion des actions payantes.

**Fonctions :**
```php
// Récupérer le prix d'une action
getAiecoinsPrice($actionId, $role, $countryId = null, $activityId = null);

// Vérifier si l'utilisateur peut se permettre une action
canAffordAction($accountId, $actionId, $role, $countryId = null, $activityId = null);

// Effectuer une action payante (vérifie + débite)
performPaidAction($accountId, $actionId, $role, $countryId = null, $activityId = null);
```

**Exemple :**
```php
require_once 'function/getAiecoinsPrice.php';

// Récupérer le prix pour consulter un CV Senior en France
$priceResult = getAiecoinsPrice(
    10,        // action_id (company_view_profile_senior)
    'recruiter', // role
    1,         // country_id (France)
    5          // activity_id (IT)
);

if ($priceResult['success']) {
    echo "Prix : " . $priceResult['price'] . " AIECoins";
}

// Effectuer l'action (vérifie le solde et débite automatiquement)
$result = performPaidAction(
    $_SESSION['accountId'],
    10,
    'recruiter',
    1,
    5
);

if ($result['success']) {
    echo "Action effectuée ! Nouveau solde : " . $result['newBalance'];
} else {
    echo "Erreur : " . $result['message'];
}
```

### 3. `function/getExchange.php`
Gestion des taux de change et conversions.

**Fonctions :**
```php
// Récupérer le taux de change pour une devise
getExchangeRate($currencyCode, $countryId = null, $activityId = null);

// Convertir AIECoins en devise
convertAiecoinsToMoney($aiecoins, $currencyCode, $countryId = null, $activityId = null);

// Convertir devise en AIECoins
convertMoneyToAiecoins($amount, $currencyCode, $countryId = null, $activityId = null);

// Récupérer tous les taux de change
$exchanges = getAllData('exchange', true);

// Récupérer tous les taux disponibles
getAllExchangeRates();

// Acheter des AIECoins avec de l'argent réel
purchaseAiecoins($accountId, $amount, $currencyCode, $countryId = null, $activityId = null);
```

**Exemples :**
```php
require_once 'function/getExchange.php';

// Récupérer le taux EUR
$rate = getExchangeRate('EUR');
// Résultat : ['success' => true, 'rate' => 25.00, 'currencyCode' => 'EUR']

// Convertir 100 AIECoins en euros
$money = convertAiecoinsToMoney(100, 'EUR');
// Résultat : ['success' => true, 'amount' => 4.00, 'currency' => 'EUR']

// Convertir 10€ en AIECoins
$coins = convertMoneyToAiecoins(10, 'EUR');
// Résultat : ['success' => true, 'aiecoins' => 250]

// Acheter des AIECoins
$purchase = purchaseAiecoins(123, 10, 'EUR'); // Acheter pour 10€
// Résultat : ['success' => true, 'aiecoins' => 250, 'newBalance' => 350]
```

### 4. `function/getData.php`
Récupération des données avec mapping automatique.

**Exemple :**
```php
// Récupérer toutes les actions
$actions = getAllData('action', true); // true = convertir en objets

// Récupérer une action par ID
$action = getDataById('action', 1, true);

// Récupérer tous les prix AIECoins
$prices = getAllData('aiecourt', true);
```

## Utilisation AJAX

### Récupérer un prix
```javascript
const response = await fetch('/function/getAiecoinsPrice.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        action: 'getPrice',
        actionId: 10,
        role: 'recruiter',
        countryId: 1,
        activityId: 5
    })
});

const result = await response.json();
if (result.success) {
    console.log('Prix:', result.price);
}
```

### Vérifier si l'utilisateur peut se permettre une action
```javascript
const response = await fetch('/function/getAiecoinsPrice.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        action: 'canAfford',
        accountId: 123,
        actionId: 10,
        role: 'recruiter'
    })
});

const result = await response.json();
if (result.canAfford) {
    console.log('Action possible !');
} else {
    console.log('Solde insuffisant');
}
```

### Effectuer une action payante
```javascript
const response = await fetch('/function/getAiecoinsPrice.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        action: 'perform',
        accountId: 123,
        actionId: 10,
        role: 'recruiter',
        countryId: 1,
        activityId: 5
    })
});

const result = await response.json();
if (result.success) {
    console.log('Action effectuée ! Nouveau solde:', result.newBalance);
    // Rafraîchir l'affichage du solde
} else {
    alert('Erreur : ' + result.message);
}
```

### Ajouter/Retirer des AIECoins
```javascript
// Ajouter
const response = await fetch('/function/updateAiecoins.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        action: 'add',
        accountId: 123,
        amount: 100,
        reason: 'Achat de pack'
    })
});

// Retirer
const response = await fetch('/function/updateAiecoins.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        action: 'remove',
        accountId: 123,
        amount: 50,
        reason: 'Remboursement'
    })
})# Conversion de devises

```javascript
// Convertir AIECoins en euros
const response = await fetch('/function/getExchange.php', {
    method: 'POST',
mysql -u root -p 1ted < sql/exchange.sql
```

2. Vérifier que les colonnes `aiecoins` existent dans la table `account` :
```bash
mysql -u root -p 1ted < sql/add_aiecoins_to_account.sql
```

3. Les fichiers PHP sont prêts à l'emploi !

## Tarification et taux de change par défaut

**Taux de conversion de base

// Convertir euros en AIECoins
const response2 = await fetch('/function/getExchange.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        action: 'convertToAiecoins',
        amount: 10,
        currencyCode: 'EUR'
    })
});

const result2 = await response2.json();
if (result2.success) {
    console.log(`10€ = ${result2.aiecoins} AIECoins`);
}

// Acheter des AIECoins
const response3 = await fetch('/function/getExchange.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        action: 'purchase',
        accountId: 123,
        amount: 20,
        currencyCode: 'EUR'
    })
});

const result3 = await response3.json();
if (result3.success) {
    console.log(`Achat réussi ! +${result3.aiecoins} AIECoins`);
    console.log(`Nouveau solde : ${result3.newBalance}`);
}

// Récupérer tous les taux
const response4 = await fetch('/function/getExchange.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        action: 'getAllRates'
    })
});

const result4 = await response4.json();
if (result4.success) {
    console.log('Taux disponibles :', result4.rates);
    // { EUR: 25, USD: 22.73, GBP: 29.41, ... }
}
```

##;
```

## Installation

1. Exécuter les fichiers SQL dans cet ordre :
```bash
mysql -u root -p 1ted < sql/action.sql
mysql -u root -p 1ted < sql/aiecourt.sql
```

2. Vérifier que les colonnes `aiecoins` existent dans la table `account` :
```bash
mysql -u root -p 1ted < sql/add_aiecoins_to_account.sql
```

3. Les fichiers PHP sont prêts à l'emploi !

## Tarification par défaut

**Taux de conversion :** 1€ = 25 AIECoins

Les prix peuvent varier selon :
- Le secteur d'activité (plus demandé = plus cher)
- Le pays de l'entreprise/candidat
- Le niveau d'ancienneté du candidat (pour les recruteurs)
- L'urgence de la recherche (boost)

## Notes importantes

- Toutes les fonctions utilisent des **transactions SQL** pour garantir la cohérence
- Les sessions sont **automatiquement mises à jour** lors des modifications
- Les erreurs sont **loggées** dans les logs PHP
- La vérification du **solde minimum** (>= 0) est automatique

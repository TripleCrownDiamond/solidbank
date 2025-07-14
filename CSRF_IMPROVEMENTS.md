# Améliorations de la gestion CSRF

## Problème identifié
Erreur "page expired 419" lors de la connexion, causée par des problèmes de gestion des tokens CSRF côté JavaScript.

## Solutions implémentées

### 1. Gestionnaire CSRF JavaScript (`resources/js/csrf-handler.js`)

**Fonctionnalités ajoutées :**
- Configuration automatique d'Axios avec le token CSRF
- Intercepteur pour gérer les erreurs 419 automatiquement
- Rafraîchissement périodique du token CSRF (toutes les 30 minutes)
- Gestion spécifique pour Livewire avec hooks personnalisés
- Validation du token au chargement de la page

**Avantages :**
- Prévention proactive des erreurs de token expiré
- Récupération automatique en cas d'expiration
- Meilleure expérience utilisateur avec des alertes informatives

### 2. Route de rafraîchissement CSRF (`routes/web.php`)

```php
Route::get('/csrf-token', function () {
    return response()->json([
        'csrf_token' => csrf_token()
    ]);
})->name('csrf.token');
```

**Utilité :**
- Permet au JavaScript de récupérer un nouveau token sans recharger la page
- Améliore la fluidité de l'application

### 3. Amélioration du composant Login Livewire

**Modifications apportées :**
- Logging détaillé des erreurs CSRF
- Dispatch d'événements JavaScript pour la gestion côté client
- Rechargement automatique avec délai personnalisable
- Messages d'alerte plus informatifs

### 4. Gestion d'événements dans les layouts

**Nouveaux événements Livewire :**
- `refresh-page-delayed` : Rechargement avec délai personnalisable
- `csrf-token-expired` : Déclenchement du rafraîchissement du token

## Configuration requise

### Meta tag CSRF (déjà présent)
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### Import du gestionnaire CSRF
```javascript
import "./csrf-handler";
```

## Utilisation

### Rafraîchissement manuel du token
```javascript
window.csrfHandler.refresh();
```

### Validation du token
```javascript
if (window.csrfHandler.validate()) {
    // Token valide
}
```

## Avantages de cette approche

1. **Prévention proactive** : Le token est rafraîchi automatiquement avant expiration
2. **Récupération automatique** : En cas d'erreur 419, le système tente de récupérer automatiquement
3. **Expérience utilisateur améliorée** : Messages informatifs et actions automatiques
4. **Compatibilité** : Fonctionne avec Livewire et les requêtes Axios classiques
5. **Logging** : Traçabilité des erreurs pour le débogage

## Tests recommandés

1. **Test de connexion normale** : Vérifier que la connexion fonctionne sans erreur
2. **Test d'expiration de session** : Laisser la page ouverte longtemps puis tenter une action
3. **Test de navigation** : Vérifier que les tokens sont correctement gérés lors de la navigation
4. **Test de requêtes AJAX** : S'assurer que les requêtes Axios incluent le bon token

## Monitoring

Surveiller les logs pour :
- Erreurs CSRF répétées
- Échecs de rafraîchissement de token
- Patterns d'utilisation anormaux

## Maintenance

- Vérifier périodiquement que le rafraîchissement automatique fonctionne
- Ajuster l'intervalle de rafraîchissement si nécessaire (actuellement 30 minutes)
- Surveiller les performances de l'application avec ces améliorations
# Résolution de l'erreur 419 "Page Expired"

## Problème identifié
L'erreur 419 "Page Expired" lors de la connexion était causée par une configuration de session incomplète.

## Solution appliquée

### 1. Configuration du driver de session
**Fichier modifié :** `.env`
```env
# Avant
SESSION_DRIVER=file

# Après
SESSION_DRIVER=database
```

### 2. Configuration complète des sessions
**Ajout dans `.env` :**
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

### 3. Nettoyage du cache
```bash
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## Diagnostic effectué

Le diagnostic a révélé que :
- ✅ La configuration CSRF est correcte
- ✅ Les middlewares sont bien configurés
- ✅ La base de données sessions fonctionne
- ✅ Les tokens CSRF sont générés correctement
- ✅ SESSION_DOMAIN maintenant défini sur 127.0.0.1
- ✅ Cache de configuration vidé

## Causes possibles et solutions

### 1. APP_KEY manquante ou incorrecte

**Vérification :**
```bash
php artisan key:generate
```

**Si le fichier .env n'existe pas :**
```bash
cp .env.example .env
php artisan key:generate
```

### 2. Configuration APP_URL incorrecte

**Vérifiez dans .env :**
```env
APP_URL=http://localhost:8000
# ou
APP_URL=http://127.0.0.1:8000
# ou votre domaine exact
```

**Important :** L'URL doit correspondre exactement à celle utilisée dans le navigateur.

### 3. Cache et sessions corrompus

**Nettoyage complet :**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan session:flush
```

### 4. Problèmes de cookies

**Configuration session dans .env :**
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

**Pour HTTPS :**
```env
SESSION_SECURE_COOKIE=true
```

### 5. Permissions de fichiers

**Windows (PowerShell en tant qu'administrateur) :**
```powershell
# Donner les permissions complètes au dossier storage
icacls "storage" /grant Everyone:F /T
icacls "bootstrap\cache" /grant Everyone:F /T
```

**Linux/Mac :**
```bash
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### 6. Redémarrage du serveur

**Arrêtez et redémarrez le serveur de développement :**
```bash
# Ctrl+C pour arrêter
php artisan serve
# ou
php artisan serve --host=127.0.0.1 --port=8000
```

## Solution rapide (à exécuter dans l'ordre)

```bash
# 1. Vérifier/générer la clé
php artisan key:generate

# 2. Nettoyer tous les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 3. Vider les sessions
php artisan session:flush

# 4. Redémarrer le serveur
php artisan serve
```

## Vérifications supplémentaires

### 1. Navigateur
- Videz le cache du navigateur (Ctrl+Shift+Del)
- Désactivez temporairement les extensions
- Testez en navigation privée
- Vérifiez que les cookies sont activés

### 2. Formulaires

Assurez-vous que tous vos formulaires contiennent :
```html
<form method="POST" action="...">
    @csrf
    <!-- ou -->
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <!-- contenu du formulaire -->
</form>
```

### 3. Requêtes AJAX

```javascript
// jQuery
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Vanilla JS
fetch('/api/endpoint', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
});
```

### 4. Meta tag dans le layout

Ajoutez dans `<head>` :
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

## Test de validation

Après avoir appliqué les solutions :

1. Accédez à votre page de connexion
2. Ouvrez les outils de développement (F12)
3. Vérifiez l'onglet "Network" lors de la soumission
4. Vérifiez l'onglet "Application" > "Cookies" pour voir les cookies de session

## Si le problème persiste

1. Vérifiez les logs Laravel : `storage/logs/laravel.log`
2. Activez le debug : `APP_DEBUG=true` dans .env
3. Vérifiez la configuration du serveur web (Apache/Nginx)
4. Testez avec un autre navigateur
5. Vérifiez les paramètres de pare-feu/antivirus

## Configuration recommandée pour le développement

**.env pour développement local :**
```env
APP_NAME="SolidBank"
APP_ENV=local
APP_KEY=base64:VOTRE_CLE_GENEREE
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

---

**Note :** L'erreur 419 est généralement liée à l'expiration ou à l'absence du token CSRF. Les solutions ci-dessus couvrent 99% des cas rencontrés.
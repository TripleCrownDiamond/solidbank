# Guide de déploiement en production - SolidBank

## Problèmes identifiés et solutions

### 1. Configuration .env pour la production

```env
# Configuration de base
APP_NAME="Wolf-Developpe"
APP_DESCRIPTION="A modern banking application."
APP_ENV=production
APP_KEY=base64:X5+xaETYgid+LwSqBrPSvrO4QgZ4U6ctutbG/2bSQlY=
APP_DEBUG=false
APP_URL=https://dbqic.com

APP_LOCALE=fr
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
PHP_CLI_SERVER_WORKERS=4
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error  # Changé de debug à error pour la production

# Base de données
DB_CONNECTION=sqlite

# Configuration de session sécurisée pour HTTPS
SESSION_DRIVER=database
SESSION_LIFETIME=120  # Réduit à 2 heures
SESSION_ENCRYPT=true  # Activé pour la sécurité
SESSION_PATH=/
SESSION_DOMAIN=.dbqic.com  # Avec point pour inclure les sous-domaines
SESSION_SECURE_COOKIE=true  # OBLIGATOIRE pour HTTPS
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
SESSION_EXPIRE_ON_CLOSE=false

# Configuration CSRF pour HTTPS
CSRF_COOKIE_SECURE=true
CSRF_COOKIE_HTTP_ONLY=false
CSRF_COOKIE_SAME_SITE=lax

# Cache et stockage
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public  # Changé de local à public
QUEUE_CONNECTION=sync
CACHE_STORE=database

# Configuration mail
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME='contact@dbqic.com'
MAIL_PASSWORD='Azerty%1234'
MAIL_FROM_ADDRESS="contact@dbqic.com"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

### 2. Configuration Apache (.htaccess)

```apache
RewriteEngine On

# Redirection HTTPS forcée
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Headers de sécurité
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:;"

# Configuration de session sécurisée
php_value session.cookie_secure 1
php_value session.cookie_httponly 1
php_value session.cookie_samesite Lax

# Redirection vers le dossier public
RewriteCond %{REQUEST_URI} !^public
RewriteRule ^(.*)$ public/$1 [L]

# Gestion des erreurs personnalisées
ErrorDocument 404 /public/index.php
ErrorDocument 500 /public/index.php
```

### 3. Commandes de déploiement

```bash
# 1. Mise à jour des dépendances
composer install --optimize-autoloader --no-dev

# 2. Génération de la clé d'application (si nécessaire)
php artisan key:generate

# 3. Création du lien symbolique pour le stockage
php artisan storage:link

# 4. Migration de la base de données
php artisan migrate --force

# 5. Création de la table de sessions
php artisan session:table
php artisan migrate --force

# 6. Optimisation pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize

# 7. Compilation des assets
npm ci
npm run build

# 8. Permissions (sur serveur Linux)
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### 4. Vérifications post-déploiement

```bash
# Vérifier la configuration
php artisan config:show session
php artisan config:show app

# Tester la base de données
php artisan tinker
# Dans Tinker :
# Schema::hasTable('sessions')
# DB::table('sessions')->count()

# Vérifier les logs
tail -f storage/logs/laravel.log

# Tester les routes
php artisan route:list | grep storage
php artisan route:list | grep csrf
```

### 5. Résolution des problèmes spécifiques

#### Problème : "Page expirée" lors de la connexion

**Cause** : Configuration CSRF/Session incorrecte pour HTTPS
**Solution** :

1. Vérifier `SESSION_SECURE_COOKIE=true`
2. Vérifier `SESSION_DOMAIN=.dbqic.com`
3. S'assurer que le site fonctionne en HTTPS
4. Vider le cache : `php artisan config:clear`

#### Problème : Erreur 404 sur les documents

**Cause** : Lien symbolique manquant ou route de fallback non fonctionnelle
**Solution** :

1. Recréer le lien symbolique : `php artisan storage:link`
2. Vérifier les permissions sur le dossier storage
3. Tester la route de fallback : `/storage/test.jpg`

#### Problème : Session qui expire trop rapidement

**Cause** : Configuration de durée de session
**Solution** :

1. Augmenter `SESSION_LIFETIME` si nécessaire
2. Vérifier que la table sessions existe
3. S'assurer que le driver de session est `database`

### 6. Monitoring et logs

```bash
# Surveiller les erreurs CSRF
grep "CSRF Token Mismatch" storage/logs/laravel.log

# Surveiller les erreurs de stockage
grep "Storage file not found" storage/logs/laravel.log

# Surveiller les erreurs de session
grep "Session" storage/logs/laravel.log
```

### 7. Tests de validation

1. **Test de connexion** :

    - Se connecter avec un utilisateur valide
    - Vérifier que la session persiste
    - Tester la déconnexion

2. **Test des documents** :

    - Accéder à la page de gestion utilisateur
    - Cliquer sur "Voir document"
    - Vérifier que le document s'affiche

3. **Test CSRF** :
    - Soumettre un formulaire
    - Vérifier qu'aucune erreur 419 n'apparaît
    - Tester avec une session expirée

### 8. Optimisations supplémentaires

```bash
# Optimisation OPcache (dans php.ini)
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0

# Optimisation de la base de données SQLite
php artisan db:optimize

# Compression Gzip (dans .htaccess)
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

## Checklist de déploiement

-   [ ] Configuration .env mise à jour
-   [ ] HTTPS activé et fonctionnel
-   [ ] Lien symbolique storage créé
-   [ ] Table sessions créée
-   [ ] Cache optimisé
-   [ ] Assets compilés
-   [ ] Permissions correctes
-   [ ] Tests de connexion réussis
-   [ ] Tests d'accès aux documents réussis
-   [ ] Logs surveillés
-   [ ] Monitoring en place

## Support et dépannage

En cas de problème persistant :

1. Vérifier les logs Laravel
2. Vérifier les logs du serveur web
3. Tester en mode debug temporairement
4. Vérifier la configuration PHP
5. Contacter le support technique

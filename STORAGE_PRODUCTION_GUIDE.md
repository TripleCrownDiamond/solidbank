# Guide de Résolution des Erreurs 404 Storage en Production

## Problème Identifié

Les fichiers stockés dans `storage/app/public` sont accessibles localement mais génèrent des erreurs 404 en production. Cela est généralement dû à :

1. **Lien symbolique manquant** : Le lien `public/storage -> storage/app/public` n'existe pas
2. **Permissions insuffisantes** : Le serveur web n'a pas les droits pour créer/accéder aux liens symboliques
3. **Hébergement restrictif** : Certains hébergeurs désactivent les liens symboliques

## Solutions Recommandées

### Solution 1: Créer le Lien Symbolique (Recommandée)

```bash
# Sur le serveur de production
php artisan storage:link
```

**Vérification :**
```bash
# Vérifier que le lien existe
ls -la public/storage

# Doit afficher quelque chose comme :
# lrwxrwxrwx 1 user user 30 date public/storage -> ../storage/app/public
```

### Solution 2: Route Personnalisée (Alternative)

Si les liens symboliques ne fonctionnent pas, ajoutez cette route dans `routes/web.php` :

```php
// Route pour servir les fichiers storage en production
Route::get('/storage/{path}', function ($path) {
    $file = storage_path('app/public/' . $path);
    
    if (!File::exists($file)) {
        abort(404);
    }
    
    $mimeType = File::mimeType($file);
    
    return response()->file($file, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000', // Cache 1 an
    ]);
})->where('path', '.*')->name('storage.serve');
```

### Solution 3: Configuration Nginx (Si applicable)

Ajoutez cette configuration dans votre fichier Nginx :

```nginx
# Servir les fichiers storage directement
location /storage/ {
    alias /path/to/your/app/storage/app/public/;
    expires 1y;
    add_header Cache-Control "public, immutable";
    try_files $uri =404;
}
```

### Solution 4: Stockage Cloud (Production Avancée)

Pour une solution robuste en production, configurez S3 ou un autre service cloud :

```php
// Dans config/filesystems.php
'default' => env('FILESYSTEM_DISK', 's3'),

// Variables d'environnement en production
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=your_region
AWS_BUCKET=your_bucket
```

## Diagnostic et Vérification

### Script de Diagnostic

Utilisez le script `diagnose_storage.php` pour identifier le problème :

```bash
php diagnose_storage.php
```

### Vérifications Manuelles

1. **Vérifier l'existence du lien :**
   ```bash
   ls -la public/storage
   ```

2. **Vérifier les permissions :**
   ```bash
   ls -la storage/app/public/
   ```

3. **Tester l'accès direct :**
   ```bash
   curl -I https://votre-domaine.com/storage/config/logos/logo.png
   ```

## Déploiement en Production

### Checklist de Déploiement

- [ ] Exécuter `php artisan storage:link` après chaque déploiement
- [ ] Vérifier les permissions du dossier `storage/`
- [ ] Tester l'accès aux fichiers uploadés
- [ ] Configurer la mise en cache des fichiers statiques
- [ ] Sauvegarder régulièrement le dossier `storage/app/public/`

### Script de Déploiement Automatique

```bash
#!/bin/bash
# deploy.sh

# Mise à jour du code
git pull origin main

# Installation des dépendances
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Mise à jour de la base de données
php artisan migrate --force

# Création du lien symbolique
php artisan storage:link

# Nettoyage du cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Redémarrage des services
sudo systemctl reload nginx
sudo systemctl restart php8.3-fpm

echo "Déploiement terminé avec succès!"
```

## Monitoring et Maintenance

### Surveillance des Erreurs 404

Ajoutez cette surveillance dans vos logs :

```php
// Dans app/Exceptions/Handler.php
public function report(Throwable $exception)
{
    if ($exception instanceof NotFoundHttpException) {
        $url = request()->fullUrl();
        if (str_contains($url, '/storage/')) {
            Log::warning('Storage file not found', [
                'url' => $url,
                'user_agent' => request()->userAgent(),
                'ip' => request()->ip()
            ]);
        }
    }
    
    parent::report($exception);
}
```

### Nettoyage Automatique

```php
// Commande artisan pour nettoyer les fichiers orphelins
php artisan make:command CleanOrphanedFiles
```

## Résolution de Problèmes Courants

### Erreur: "The link already exists"

```bash
# Supprimer le lien existant et le recréer
rm public/storage
php artisan storage:link
```

### Erreur: "Permission denied"

```bash
# Corriger les permissions
sudo chown -R www-data:www-data storage/
sudo chmod -R 755 storage/
```

### Erreur: "Symlinks not supported"

Utilisez la Solution 2 (Route personnalisée) ou la Solution 4 (Stockage cloud).

## Support et Documentation

- [Documentation Laravel Storage](https://laravel.com/docs/filesystem)
- [Guide des liens symboliques](https://laravel.com/docs/filesystem#the-public-disk)
- [Configuration S3](https://laravel.com/docs/filesystem#s3-driver-configuration)

---

**Note :** Ce guide couvre les solutions les plus courantes. Pour des problèmes spécifiques à votre hébergeur, consultez leur documentation ou contactez leur support technique.
# Guide de Déploiement - SolidBank

## Problème Résolu

L'erreur `Database file at path [C:\Users\User\Desktop\projets\solidbank\database\database.sqlite] does not exist` était causée par l'absence du fichier de base de données SQLite sur le serveur de production.

## Solution Implémentée

### 1. Création du fichier de base de données
```bash
# Créer le fichier SQLite vide
touch database/database.sqlite
# Ou sur Windows
New-Item -Path database\database.sqlite -ItemType File -Force
```

### 2. Exécution des migrations
```bash
php artisan migrate --force
```

### 3. Optimisation pour la production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Instructions de Déploiement

### Méthode Automatique (Recommandée)

1. **Télécharger les fichiers sur le serveur**
   - Transférer tous les fichiers du projet via FTP/SFTP
   - S'assurer que le dossier `database` est inclus

2. **Exécuter le script de déploiement**
   ```bash
   chmod +x deploy.sh
   ./deploy.sh
   ```

### Méthode Manuelle

1. **Créer le fichier de base de données**
   ```bash
   touch database/database.sqlite
   chmod 664 database/database.sqlite
   ```

2. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   # Éditer .env avec les bonnes valeurs
   php artisan key:generate
   ```

3. **Exécuter les migrations**
   ```bash
   php artisan migrate --force
   ```

4. **Optimiser l'application**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. **Configurer les permissions**
   ```bash
   chmod -R 755 storage
   chmod -R 755 bootstrap/cache
   chmod 664 database/database.sqlite
   ```

## Configuration .env pour Production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bred-fin.com

DB_CONNECTION=sqlite
# Pas besoin de DB_HOST, DB_PORT, etc. pour SQLite

CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

## Vérifications Post-Déploiement

1. **Vérifier que l'application fonctionne**
   ```bash
   php artisan about
   ```

2. **Tester les routes principales**
   - Page d'accueil : `/`
   - Connexion : `/login`
   - Inscription : `/register`
   - Transactions : `/fr/transactions`

3. **Vérifier les permissions**
   ```bash
   ls -la database/database.sqlite
   ls -la storage/
   ls -la bootstrap/cache/
   ```

## Dépannage

### Erreur : "Permission denied"
```bash
chmod 664 database/database.sqlite
chown www-data:www-data database/database.sqlite
```

### Erreur : "Route not found"
```bash
php artisan route:clear
php artisan route:cache
```

### Erreur : "Configuration cached"
```bash
php artisan config:clear
php artisan config:cache
```

## Notes Importantes

- ⚠️ **Sauvegarde** : Toujours sauvegarder la base de données avant une mise à jour
- 🔒 **Sécurité** : S'assurer que le fichier `.env` n'est pas accessible publiquement
- 📊 **Performance** : Les caches doivent être reconstruits après chaque déploiement
- 🗄️ **Base de données** : SQLite est approprié pour des applications de taille moyenne

## Support

En cas de problème, vérifier :
1. Les logs Laravel dans `storage/logs/`
2. Les logs du serveur web
3. Les permissions des fichiers
4. La configuration `.env`
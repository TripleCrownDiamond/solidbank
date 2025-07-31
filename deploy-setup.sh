#!/bin/bash

# Script de configuration post-déploiement pour Hostinger
# À exécuter via SSH après le déploiement

echo "Configuration post-déploiement DBQIC..."

# Créer les dossiers de cache s'ils n'existent pas
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Définir les permissions correctes
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Vider et recréer les caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Créer le lien symbolique storage si nécessaire
if [ ! -L "public/storage" ]; then
    ln -sf "$(pwd)/storage/app/public" "$(pwd)/public/storage"
    echo "Lien symbolique storage créé"
fi

# Optimiser pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Configuration terminée avec succès!"
echo "Vérifiez que les permissions sont correctes :"
ls -la storage/
ls -la bootstrap/cache/
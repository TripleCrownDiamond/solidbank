#!/bin/bash

# Script de déploiement pour SolidBank
# Ce script doit être exécuté sur le serveur de production

echo "🚀 Début du déploiement SolidBank..."

# 1. Créer le fichier de base de données SQLite s'il n'existe pas
if [ ! -f "database/database.sqlite" ]; then
    echo "📁 Création du fichier database.sqlite..."
    touch database/database.sqlite
    chmod 664 database/database.sqlite
else
    echo "✅ Le fichier database.sqlite existe déjà"
fi

# 2. Exécuter les migrations
echo "🔄 Exécution des migrations..."
php artisan migrate --force

# 3. Optimiser l'application pour la production
echo "⚡ Optimisation de l'application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Définir les permissions appropriées
echo "🔐 Configuration des permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 664 database/database.sqlite

# 5. Vérifier que l'application fonctionne
echo "🔍 Vérification de l'application..."
php artisan about

echo "✅ Déploiement terminé avec succès !"
echo "📝 N'oubliez pas de :"
echo "   - Configurer le fichier .env avec les bonnes valeurs"
echo "   - Vérifier les permissions des fichiers"
echo "   - Tester l'application sur le serveur"
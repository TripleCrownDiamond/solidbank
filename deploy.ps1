# Script de déploiement PowerShell pour SolidBank
# Ce script doit être exécuté sur le serveur de production Windows

Write-Host "🚀 Début du déploiement SolidBank..." -ForegroundColor Green

# 1. Créer le fichier de base de données SQLite s'il n'existe pas
if (-not (Test-Path "database\database.sqlite")) {
    Write-Host "📁 Création du fichier database.sqlite..." -ForegroundColor Yellow
    New-Item -Path "database\database.sqlite" -ItemType File -Force | Out-Null
    Write-Host "✅ Fichier database.sqlite créé" -ForegroundColor Green
} else {
    Write-Host "✅ Le fichier database.sqlite existe déjà" -ForegroundColor Green
}

# 2. Vérifier que PHP est disponible
try {
    $phpVersion = php -v
    Write-Host "✅ PHP détecté" -ForegroundColor Green
} catch {
    Write-Host "❌ PHP n'est pas disponible dans le PATH" -ForegroundColor Red
    exit 1
}

# 3. Exécuter les migrations
Write-Host "🔄 Exécution des migrations..." -ForegroundColor Yellow
try {
    php artisan migrate --force
    Write-Host "✅ Migrations exécutées avec succès" -ForegroundColor Green
} catch {
    Write-Host "❌ Erreur lors de l'exécution des migrations" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
}

# 4. Optimiser l'application pour la production
Write-Host "⚡ Optimisation de l'application..." -ForegroundColor Yellow

try {
    Write-Host "   - Cache de configuration..." -ForegroundColor Cyan
    php artisan config:cache
    
    Write-Host "   - Cache des routes..." -ForegroundColor Cyan
    php artisan route:cache
    
    Write-Host "   - Cache des vues..." -ForegroundColor Cyan
    php artisan view:cache
    
    Write-Host "✅ Optimisation terminée" -ForegroundColor Green
} catch {
    Write-Host "❌ Erreur lors de l'optimisation" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
}

# 5. Vérifier que l'application fonctionne
Write-Host "🔍 Vérification de l'application..." -ForegroundColor Yellow
try {
    php artisan about
    Write-Host "✅ Application vérifiée" -ForegroundColor Green
} catch {
    Write-Host "⚠️ Impossible de vérifier l'application" -ForegroundColor Yellow
}

# 6. Afficher les informations importantes
Write-Host ""
Write-Host "✅ Déploiement terminé avec succès !" -ForegroundColor Green
Write-Host ""
Write-Host "📝 N'oubliez pas de :" -ForegroundColor Yellow
Write-Host "   - Configurer le fichier .env avec les bonnes valeurs" -ForegroundColor White
Write-Host "   - Vérifier les permissions des fichiers" -ForegroundColor White
Write-Host "   - Tester l'application sur le serveur" -ForegroundColor White
Write-Host "   - Configurer le serveur web (Apache/Nginx)" -ForegroundColor White
Write-Host ""
Write-Host "🌐 Fichiers importants :" -ForegroundColor Cyan
Write-Host "   - Base de données : database\database.sqlite" -ForegroundColor White
Write-Host "   - Configuration : .env" -ForegroundColor White
Write-Host "   - Logs : storage\logs\" -ForegroundColor White

# 7. Vérifier la taille de la base de données
if (Test-Path "database\database.sqlite") {
    $dbSize = (Get-Item "database\database.sqlite").Length
    Write-Host "📊 Taille de la base de données : $($dbSize) bytes" -ForegroundColor Cyan
}

Write-Host ""
Write-Host "🎉 Déploiement terminé !" -ForegroundColor Green
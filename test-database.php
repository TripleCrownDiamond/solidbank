<?php

// Script de test de la base de données SQLite
require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Tester la connexion à la base de données
    $pdo = DB::connection()->getPdo();
    echo "✅ Connexion SQLite réussie\n";
    
    // Vérifier les tables
    $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
    echo "📊 Tables trouvées: " . count($tables) . "\n";
    
    foreach ($tables as $table) {
        echo "   - " . $table->name . "\n";
    }
    
    // Tester une requête sur la table cache
    try {
        $cacheCount = DB::table('cache')->count();
        echo "💾 Entrées dans le cache: $cacheCount\n";
    } catch (Exception $e) {
        echo "⚠️ Table cache non accessible: " . $e->getMessage() . "\n";
    }
    
    // Tester une requête sur la table users
    try {
        $userCount = DB::table('users')->count();
        echo "👥 Utilisateurs enregistrés: $userCount\n";
    } catch (Exception $e) {
        echo "⚠️ Table users non accessible: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎉 Test de base de données terminé avec succès!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur de connexion à la base de données:\n";
    echo $e->getMessage() . "\n";
    exit(1);
}
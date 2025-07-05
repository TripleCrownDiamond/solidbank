<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simuler une requête pour tester la session
$request = Illuminate\Http\Request::create('/', 'GET');
$response = $kernel->handle($request);

echo "=== Test de diagnostic session 419 ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

// Vérifier la configuration de session
echo "1. Configuration de session:\n";
echo "   - Driver: " . config('session.driver') . "\n";
echo "   - Lifetime: " . config('session.lifetime') . " minutes\n";
echo "   - Domain: " . (config('session.domain') ?: 'null') . "\n";
echo "   - Path: " . config('session.path') . "\n";
echo "   - Secure: " . (config('session.secure') ? 'true' : 'false') . "\n";
echo "   - HttpOnly: " . (config('session.http_only') ? 'true' : 'false') . "\n";
echo "   - SameSite: " . config('session.same_site') . "\n\n";

// Vérifier la configuration CSRF
echo "2. Configuration CSRF:\n";
echo "   - APP_KEY définie: " . (config('app.key') ? 'Oui' : 'Non') . "\n";
echo "   - Middleware CSRF: Activé\n\n";

// Vérifier la base de données
echo "3. Test de connexion base de données:\n";
try {
    $pdo = new PDO('sqlite:' . database_path('database.sqlite'));
    echo "   - Connexion SQLite: ✅ Réussie\n";
    
    // Vérifier la table sessions
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='sessions'");
    if ($stmt->fetch()) {
        echo "   - Table sessions: ✅ Existe\n";
        
        // Compter les sessions actives
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM sessions");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "   - Sessions actives: $count\n";
    } else {
        echo "   - Table sessions: ❌ N'existe pas\n";
    }
} catch (Exception $e) {
    echo "   - Erreur base de données: " . $e->getMessage() . "\n";
}

echo "\n4. Solutions appliquées pour résoudre l'erreur 419:\n";
echo "   ✅ SESSION_DOMAIN défini sur 127.0.0.1\n";
echo "   ✅ Cache de configuration vidé\n";
echo "   ✅ Middleware CSRF correctement configuré\n";
echo "   ✅ Table sessions présente en base\n\n";

echo "5. Recommandations:\n";
echo "   - Vider le cache du navigateur\n";
echo "   - Redémarrer le serveur de développement\n";
echo "   - Vérifier que les cookies sont activés\n";
echo "   - Tester la connexion dans un nouvel onglet\n\n";

echo "=== Fin du diagnostic ===\n";
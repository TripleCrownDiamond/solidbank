<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== Diagnostic complet des sessions ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

// 1. Configuration de base
echo "1. Configuration de base:\n";
echo "   - APP_KEY: " . (config('app.key') ? '✅ Définie' : '❌ Manquante') . "\n";
echo "   - APP_URL: " . config('app.url') . "\n";
echo "   - APP_ENV: " . config('app.env') . "\n\n";

// 2. Configuration des sessions
echo "2. Configuration des sessions:\n";
echo "   - Driver: " . config('session.driver') . "\n";
echo "   - Lifetime: " . config('session.lifetime') . " minutes\n";
echo "   - Domain: " . (config('session.domain') ?: 'null') . "\n";
echo "   - Path: " . config('session.path') . "\n";
echo "   - Secure: " . (config('session.secure') ? 'true' : 'false') . "\n";
echo "   - HttpOnly: " . (config('session.http_only') ? 'true' : 'false') . "\n";
echo "   - SameSite: " . config('session.same_site') . "\n";
echo "   - Encrypt: " . (config('session.encrypt') ? 'true' : 'false') . "\n\n";

// 3. Test de connexion à la base de données
echo "3. Test de connexion base de données:\n";
try {
    $pdo = new PDO(
        'mysql:host=' . config('database.connections.mysql.host') . 
        ';dbname=' . config('database.connections.mysql.database'),
        config('database.connections.mysql.username'),
        config('database.connections.mysql.password')
    );
    echo "   - Connexion MySQL: ✅ Réussie\n";
    
    // Vérifier la table sessions
    $stmt = $pdo->query("SHOW TABLES LIKE 'sessions'");
    if ($stmt->rowCount() > 0) {
        echo "   - Table sessions: ✅ Existe\n";
        
        // Compter les sessions actives
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM sessions");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "   - Sessions actives: $count\n";
        
        // Vérifier la structure de la table
        $stmt = $pdo->query("DESCRIBE sessions");
        echo "   - Structure de la table sessions:\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "     * " . $row['Field'] . " - " . $row['Type'] . "\n";
        }
    } else {
        echo "   - Table sessions: ❌ N'existe pas\n";
    }
} catch (Exception $e) {
    echo "   - Erreur base de données: " . $e->getMessage() . "\n";
}

// 4. Test de génération de token CSRF
echo "\n4. Test CSRF:\n";
try {
    $token = csrf_token();
    echo "   - Token CSRF généré: " . substr($token, 0, 10) . "...\n";
    echo "   - Longueur du token: " . strlen($token) . " caractères\n";
} catch (Exception $e) {
    echo "   - Erreur génération CSRF: " . $e->getMessage() . "\n";
}

// 5. Test de session
echo "\n5. Test de session:\n";
try {
    session_start();
    $sessionId = session_id();
    echo "   - ID de session: " . ($sessionId ?: 'Non généré') . "\n";
    echo "   - Statut de session: " . (session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive') . "\n";
} catch (Exception $e) {
    echo "   - Erreur session: " . $e->getMessage() . "\n";
}

echo "\n=== Fin du diagnostic ===\n"; 
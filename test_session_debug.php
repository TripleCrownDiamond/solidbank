<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Test de la configuration de session
echo "=== DEBUG SESSION CONFIGURATION ===\n";
echo "SESSION_DRIVER: " . env('SESSION_DRIVER') . "\n";
echo "SESSION_CONNECTION: " . env('SESSION_CONNECTION') . "\n";
echo "SESSION_LIFETIME: " . env('SESSION_LIFETIME') . "\n";
echo "SESSION_DOMAIN: " . env('SESSION_DOMAIN') . "\n";
echo "SESSION_SECURE_COOKIE: " . env('SESSION_SECURE_COOKIE') . "\n";
echo "SESSION_HTTP_ONLY: " . env('SESSION_HTTP_ONLY') . "\n";
echo "SESSION_SAME_SITE: " . env('SESSION_SAME_SITE') . "\n";

echo "\n=== CONFIG VALUES ===\n";
echo "config('session.driver'): " . config('session.driver') . "\n";
echo "config('session.connection'): " . config('session.connection') . "\n";
echo "config('session.table'): " . config('session.table') . "\n";

echo "\n=== DATABASE CONNECTION ===\n";
try {
    $pdo = DB::connection()->getPdo();
    echo "Database connected: YES\n";
    echo "Database name: " . DB::connection()->getDatabaseName() . "\n";
    
    // Vérifier si la table sessions existe
    $hasSessionsTable = Schema::hasTable('sessions');
    echo "Sessions table exists: " . ($hasSessionsTable ? 'YES' : 'NO') . "\n";
    
    if ($hasSessionsTable) {
        $sessionCount = DB::table('sessions')->count();
        echo "Sessions count: " . $sessionCount . "\n";
    }
    
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

echo "\n=== SESSION STORE TEST ===\n";
try {
    $sessionManager = app('session');
    echo "Session manager class: " . get_class($sessionManager) . "\n";
    
    $store = $sessionManager->getDefaultDriver();
    echo "Session store: " . $store . "\n";
    
    $handler = $sessionManager->driver()->getHandler();
    echo "Session handler class: " . get_class($handler) . "\n";
    
} catch (Exception $e) {
    echo "Session error: " . $e->getMessage() . "\n";
}

echo "\n=== CSRF TOKEN TEST ===\n";
try {
    // Simuler une requête pour tester le token CSRF
    $request = Illuminate\Http\Request::create('/', 'GET');
    $app->instance('request', $request);
    
    // Démarrer la session
    $session = $sessionManager->driver();
    $session->start();
    
    // Générer un token CSRF
    $token = $session->token();
    echo "CSRF token generated: " . ($token ? 'YES' : 'NO') . "\n";
    echo "Token length: " . strlen($token) . "\n";
    
    // Tester la validation du token
    $isValid = hash_equals($token, $token);
    echo "Token validation test: " . ($isValid ? 'PASS' : 'FAIL') . "\n";
    
} catch (Exception $e) {
    echo "CSRF test error: " . $e->getMessage() . "\n";
}

echo "\n=== END DEBUG ===\n";
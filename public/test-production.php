<?php
// Test simple pour vérifier le fonctionnement en production
// Accès via: https://trade-europe.online/test-production.php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    echo '<h1>Test Production - SolidBank</h1>';
    echo '<p>✅ Laravel application loaded successfully</p>';

    // Test des routes importantes
    $testRoutes = [
        '/fr' => "Page d'accueil française",
        '/fr/transactions' => 'Page des transactions',
        '/fr/login' => 'Page de connexion',
        '/fr/register' => "Page d'inscription"
    ];

    echo '<h2>Test des routes:</h2>';
    echo '<ul>';

    foreach ($testRoutes as $route => $description) {
        try {
            $request = Illuminate\Http\Request::create($route, 'GET');
            $router = $app['router'];
            $matchedRoute = $router->getRoutes()->match($request);

            echo "<li style='color: green;'>✅ <strong>$route</strong> - $description</li>";
        } catch (Exception $e) {
            echo "<li style='color: red;'>❌ <strong>$route</strong> - $description (Erreur: " . $e->getMessage() . ')</li>';
        }
    }

    echo '</ul>';

    // Informations sur l'environnement
    echo '<h2>Informations environnement:</h2>';
    echo '<ul>';
    echo '<li><strong>APP_ENV:</strong> ' . (env('APP_ENV') ?: 'Non défini') . '</li>';
    echo '<li><strong>APP_DEBUG:</strong> ' . (env('APP_DEBUG') ? 'true' : 'false') . '</li>';
    echo '<li><strong>Cache des routes:</strong> ' . (file_exists(base_path('bootstrap/cache/routes-v7.php')) ? '✅ Activé' : '❌ Désactivé') . '</li>';
    echo '<li><strong>Cache de configuration:</strong> ' . (file_exists(base_path('bootstrap/cache/config.php')) ? '✅ Activé' : '❌ Désactivé') . '</li>';
    echo '</ul>';

    echo '<h2>Actions recommandées:</h2>';
    echo '<p>Si toutes les routes sont ✅, votre application est prête pour la production !</p>';
    echo "<p><strong>N'oubliez pas de supprimer ce fichier de test après vérification.</strong></p>";
} catch (Exception $e) {
    echo "<h1 style='color: red;'>Erreur</h1>";
    echo "<p style='color: red;'>" . $e->getMessage() . '</p>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
?>
<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel sans démarrer le serveur HTTP
$app = require_once __DIR__ . '/bootstrap/app.php';

echo "=== Test de la route d'activation ===\n\n";

// Vérifier si la route existe
try {
    $router = app('router');
    $routes = $router->getRoutes();
    
    echo "Recherche de la route 'account.activate'...\n";
    
    $activationRouteFound = false;
    foreach ($routes as $route) {
        if ($route->getName() === 'account.activate') {
            $activationRouteFound = true;
            echo "✓ Route 'account.activate' trouvée !\n";
            echo "  - URI: " . $route->uri() . "\n";
            echo "  - Méthodes: " . implode(', ', $route->methods()) . "\n";
            echo "  - Action: " . $route->getActionName() . "\n";
            echo "  - Middleware: " . implode(', ', $route->middleware()) . "\n";
            break;
        }
    }
    
    if (!$activationRouteFound) {
        echo "✗ Route 'account.activate' NON TROUVÉE !\n\n";
        echo "Routes disponibles contenant 'activate':\n";
        $foundActivateRoutes = false;
        foreach ($routes as $route) {
            $routeName = $route->getName() ?? 'sans nom';
            $routeUri = $route->uri();
            if (strpos($routeUri, 'activate') !== false || strpos($routeName, 'activate') !== false) {
                echo "  - $routeName => $routeUri\n";
                $foundActivateRoutes = true;
            }
        }
        
        if (!$foundActivateRoutes) {
            echo "  Aucune route contenant 'activate' trouvée.\n";
        }
        
        echo "\nRoutes avec locale (fr):\n";
        foreach ($routes as $route) {
            $routeUri = $route->uri();
            if (strpos($routeUri, '{locale}') !== false || strpos($routeUri, 'fr/') !== false) {
                $routeName = $route->getName() ?? 'sans nom';
                echo "  - $routeName => $routeUri\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "Erreur lors de la vérification des routes: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Vérification du contrôleur ===\n";

// Vérifier si le contrôleur existe
if (class_exists('App\\Http\\Controllers\\AccountActivationController')) {
    echo "✓ Contrôleur AccountActivationController existe\n";
    
    $reflection = new ReflectionClass('App\\Http\\Controllers\\AccountActivationController');
    if ($reflection->hasMethod('activate')) {
        echo "✓ Méthode 'activate' existe dans le contrôleur\n";
        
        $method = $reflection->getMethod('activate');
        $parameters = $method->getParameters();
        echo "  - Paramètres de la méthode:\n";
        foreach ($parameters as $param) {
            $type = $param->getType() ? $param->getType()->getName() : 'mixed';
            echo "    * \${$param->getName()} ($type)\n";
        }
    } else {
        echo "✗ Méthode 'activate' n'existe pas dans le contrôleur\n";
    }
} else {
    echo "✗ Contrôleur AccountActivationController n'existe pas\n";
}

echo "\n=== Conclusion ===\n";
if (!$activationRouteFound) {
    echo "🔴 PROBLÈME IDENTIFIÉ: La route 'account.activate' n'existe pas !\n";
    echo "\nSOLUTION:\n";
    echo "1. Vérifier que la route est bien définie dans routes/web.php\n";
    echo "2. Vérifier que le fichier de routes est bien chargé\n";
    echo "3. Vider le cache des routes: php artisan route:clear\n";
} else {
    echo "🟡 La route existe, le problème peut venir de:\n";
    echo "- La signature de l'URL qui n'est pas valide\n";
    echo "- L'utilisateur qui n'existe pas\n";
    echo "- Le hash qui ne correspond pas\n";
    echo "- Un middleware qui bloque l'accès\n";
}
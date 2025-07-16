<?php
// Laravel route debugging script
// Access via: https://wolf-developpe.com/laravel-debug.php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    // Force route loading by handling a dummy request
    $dummyRequest = Illuminate\Http\Request::create('/', 'GET');
    $kernel->handle($dummyRequest);

    // Create a request for /fr/transactions
    $request = Illuminate\Http\Request::create('/fr/transactions', 'GET');

    echo '<h1>Laravel Route Debug</h1>';
    echo '<p>Testing route: /fr/transactions</p>';

    // Get all registered routes
    $router = $app['router'];
    $routes = $router->getRoutes();

    echo '<h2>Debug Info:</h2>';
    echo '<p>Total routes found: ' . count($routes) . '</p>';
    echo '<p>Router class: ' . get_class($router) . '</p>';
    echo '<p>Routes collection class: ' . get_class($routes) . '</p>';

    // Check if routes are loaded
    if (count($routes) === 0) {
        echo "<p style='color: red;'>⚠️ No routes loaded! This indicates a problem with route registration.</p>";

        // Try to manually load routes
        echo '<p>Attempting to manually load routes...</p>';
        try {
            require_once __DIR__ . '/../routes/web.php';
            $routes = $router->getRoutes();
            echo '<p>Routes after manual load: ' . count($routes) . '</p>';
        } catch (Exception $e) {
            echo "<p style='color: red;'>Error loading routes: " . $e->getMessage() . '</p>';
        }
    }

    echo '<h2>All Registered Routes:</h2>';
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo '<tr><th>Method</th><th>URI</th><th>Name</th><th>Action</th></tr>';

    foreach ($routes as $route) {
        $methods = implode('|', $route->methods());
        $uri = $route->uri();
        $name = $route->getName() ?: 'N/A';
        $action = $route->getActionName();

        // Highlight transaction routes
        $style = (strpos($uri, 'transaction') !== false) ? 'background-color: yellow;' : '';

        echo "<tr style='$style'>";
        echo "<td>$methods</td>";
        echo "<td>$uri</td>";
        echo "<td>$name</td>";
        echo "<td>$action</td>";
        echo '</tr>';
    }
    echo '</table>';

    // Test route matching
    echo '<h2>Route Matching Test:</h2>';
    try {
        $route = $router->getRoutes()->match($request);
        echo "<p style='color: green;'>✓ Route found for /fr/transactions</p>";
        echo '<p>Route URI: ' . $route->uri() . '</p>';
        echo '<p>Route Name: ' . ($route->getName() ?: 'N/A') . '</p>';
        echo '<p>Route Action: ' . $route->getActionName() . '</p>';
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ No route found for /fr/transactions</p>";
        echo '<p>Error: ' . $e->getMessage() . '</p>';
    }

    // Test middleware
    echo '<h2>Middleware Test:</h2>';
    $middlewareAliases = $app['config']['app.aliases'] ?? [];
    echo '<p>Available middleware aliases:</p>';
    echo '<pre>';
    print_r($app->make('router')->getMiddleware());
    echo '</pre>';
} catch (Exception $e) {
    echo '<h1>Error</h1>';
    echo "<p style='color: red;'>" . $e->getMessage() . '</p>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
?>
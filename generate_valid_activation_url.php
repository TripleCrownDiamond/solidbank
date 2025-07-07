<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Démarrer l'application
$app->boot();

echo "<h1>Génération d'une URL d'activation valide</h1>";

try {
    // Définir la locale
    app()->setLocale('fr');
    
    // Récupérer un utilisateur existant (ID 2)
    $user = \App\Models\User::find(2);
    
    if (!$user) {
        echo "<p style='color: red;'>Utilisateur avec ID 2 non trouvé</p>";
        exit;
    }
    
    echo "<p>Utilisateur trouvé: " . $user->email . "</p>";
    
    // Générer l'URL d'activation avec les bons paramètres
    $hash = sha1($user->email);
    $expires = now()->addMinutes(60)->timestamp;
    
    // Générer l'URL avec la signature
    $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
        'account.activate',
        now()->addMinutes(60),
        [
            'locale' => 'fr',
            'id' => $user->id,
            'hash' => $hash
        ]
    );
    
    echo "<h2>URL d'activation générée:</h2>";
    echo "<p><a href='" . $url . "' target='_blank'>" . $url . "</a></p>";
    
    // Tester cette URL avec cURL
    echo "<h2>Test de l'URL générée:</h2>";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "<p style='color: red;'>Erreur cURL: " . $error . "</p>";
    } else {
        echo "<p>Code HTTP: <strong>" . $httpCode . "</strong></p>";
        
        if ($httpCode == 200) {
            echo "<p style='color: green;'>✓ URL accessible - Activation réussie</p>";
        } elseif ($httpCode == 404) {
            echo "<p style='color: red;'>✗ Erreur 404 - Route non trouvée</p>";
        } elseif ($httpCode == 302 || $httpCode == 301) {
            echo "<p style='color: orange;'>→ Redirection détectée</p>";
        } elseif ($httpCode == 403) {
            echo "<p style='color: orange;'>→ Erreur 403 - Signature invalide ou lien expiré</p>";
        } else {
            echo "<p style='color: orange;'>Code HTTP: " . $httpCode . "</p>";
        }
        
        // Afficher une partie de la réponse
        if ($response) {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            if ($headerSize > 0) {
                $headers = substr($response, 0, $headerSize);
                $body = substr($response, $headerSize);
                
                echo "<h3>En-têtes de réponse:</h3>";
                echo "<pre>" . htmlspecialchars($headers) . "</pre>";
                
                if (strlen($body) > 0 && strlen($body) < 1000) {
                    echo "<h3>Début de la réponse:</h3>";
                    echo "<pre>" . htmlspecialchars(substr($body, 0, 500)) . "</pre>";
                }
            }
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
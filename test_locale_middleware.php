<?php

// Test de l'URL d'activation valide générée par Laravel
echo "<h1>Test de l'URL d'activation valide</h1>";

$validUrl = 'http://127.0.0.1:8000/fr/activate/2/63a710569261a24b3766275b7000ce8d7b32e2f7?expires=1751821048&signature=82bac133b6a1e1bdf62997bb86725dab76ee26d594f6fb06d4ace9abfe482249';

echo "<p>URL générée par Laravel: <br><small>" . $validUrl . "</small></p>";

// Test avec cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $validUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
curl_close($ch);

if ($error) {
    echo "<p style='color: red;'>Erreur cURL: " . $error . "</p>";
} else {
    echo "<h2>Résultat:</h2>";
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
    
    // Afficher les en-têtes de réponse
    if ($headerSize > 0) {
        $headers = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        
        echo "<h3>En-têtes de réponse:</h3>";
        echo "<pre>" . htmlspecialchars($headers) . "</pre>";
        
        if (strlen($body) > 0 && strlen($body) < 2000) {
            echo "<h3>Début de la réponse:</h3>";
            echo "<pre>" . htmlspecialchars(substr($body, 0, 1000)) . "</pre>";
        }
    }
}

// Comparaison avec l'URL originale du problème
echo "<h2>Comparaison avec l'URL originale:</h2>";
$originalUrl = 'http://127.0.0.1:8000/fr/activate/2/63a710569261a24b3766275b7000ce8d7b32e2f7?expires=1735401600&signature=82bac133b6a1e1bdf62997bb86725dab76ee26d594f6fb06d4ace9abfe482249';

echo "<p>URL originale (du problème): <br><small>" . $originalUrl . "</small></p>";

// Test de l'URL originale
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $originalUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if (!$error) {
    $status = ($httpCode == 200 || $httpCode == 302) ? '✓' : '✗';
    $color = ($httpCode == 200 || $httpCode == 302) ? 'green' : 'red';
    echo "<p style='color: $color;'>$status URL originale → HTTP $httpCode</p>";
} else {
    echo "<p style='color: red;'>✗ URL originale → Erreur: $error</p>";
}

echo "<h3>Différences identifiées:</h3>";
echo "<ul>";
echo "<li>Expires: 1751821048 (valide) vs 1735401600 (originale)</li>";
echo "<li>Les deux utilisent la même signature et le même hash</li>";
echo "<li>La différence principale est la date d'expiration</li>";
echo "</ul>";
<?php

// Test d'un lien d'activation expiré
echo "<h1>Test du comportement avec un lien expiré</h1>";

// Créer une URL avec une date d'expiration dans le passé
$baseUrl = 'http://127.0.0.1:8000';
$expiredUrl = $baseUrl . '/fr/activate/2/63a710569261a24b3766275b7000ce8d7b32e2f7?expires=1735401600&signature=82bac133b6a1e1bdf62997bb86725dab76ee26d594f6fb06d4ace9abfe482249';

echo "<p>URL expirée testée: <br><small>" . $expiredUrl . "</small></p>";
echo "<p>Date d'expiration: " . date('Y-m-d H:i:s', 1735401600) . " (" . date('Y-m-d H:i:s') . " actuel)</p>";

// Test avec cURL pour voir la réponse complète
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $expiredUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Ne pas suivre les redirections
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
        echo "<p style='color: green;'>✓ URL accessible (inattendu pour un lien expiré)</p>";
    } elseif ($httpCode == 404) {
        echo "<p style='color: red;'>✗ Erreur 404 - C'est le problème signalé</p>";
    } elseif ($httpCode == 403) {
        echo "<p style='color: orange;'>→ Erreur 403 - Signature invalide (comportement attendu)</p>";
    } elseif ($httpCode == 302 || $httpCode == 301) {
        echo "<p style='color: green;'>→ Redirection détectée (comportement souhaité)</p>";
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
            echo "<h3>Corps de la réponse:</h3>";
            echo "<pre>" . htmlspecialchars(substr($body, 0, 1000)) . "</pre>";
        }
    }
}

// Test avec une URL sans signature
echo "<h2>Test sans signature:</h2>";
$urlWithoutSignature = $baseUrl . '/fr/activate/2/63a710569261a24b3766275b7000ce8d7b32e2f7';

echo "<p>URL sans signature: <br><small>" . $urlWithoutSignature . "</small></p>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $urlWithoutSignature);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if (!$error) {
    echo "<p>Code HTTP sans signature: <strong>" . $httpCode . "</strong></p>";
    if ($httpCode == 404) {
        echo "<p style='color: red;'>✗ Erreur 404 - Le middleware 'signed' bloque avant d'atteindre le contrôleur</p>";
    } elseif ($httpCode == 403) {
        echo "<p style='color: orange;'>→ Erreur 403 - Signature manquante</p>";
    }
} else {
    echo "<p style='color: red;'>Erreur: $error</p>";
}

echo "<h2>Diagnostic:</h2>";
echo "<ul>";
echo "<li>Le middleware 'signed' de Laravel vérifie automatiquement la signature</li>";
echo "<li>Si la signature est invalide ou expirée, Laravel retourne une erreur HTTP avant d'atteindre le contrôleur</li>";
echo "<li>Le contrôleur AccountActivationController n'est jamais exécuté dans ce cas</li>";
echo "<li>Il faut gérer cette erreur au niveau global ou modifier l'approche</li>";
echo "</ul>";
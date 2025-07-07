<?php

echo "=== Test final de l'URL d'activation ===\n\n";

// URL d'activation originale
$url = 'http://127.0.0.1:8000/fr/activate/2/b1d7b51ef319cdcffdda46c5c6129e396135a858?expires=1751819792&signature=64197a25f78c71906dd57f810b5c91b762599bb9555756b359f78fb8a37277e8';

echo "Test de l'URL: $url\n\n";

// Utiliser cURL pour tester l'URL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "❌ Erreur cURL: $error\n";
} else {
    echo "📊 Code de statut HTTP: $httpCode\n";
    
    if ($httpCode == 200) {
        echo "✅ SUCCESS: L'URL d'activation fonctionne !\n";
        echo "La page s'affiche correctement.\n";
    } elseif ($httpCode == 302 || $httpCode == 301) {
        echo "🔄 REDIRECTION: L'URL redirige (code $httpCode)\n";
        if ($redirectUrl) {
            echo "URL de redirection: $redirectUrl\n";
        }
        echo "✅ Cela signifie que l'activation fonctionne et redirige vers la page de login !\n";
    } elseif ($httpCode == 404) {
        echo "❌ ERREUR 404: L'URL d'activation retourne toujours une erreur 404.\n";
        echo "Le problème persiste.\n";
    } else {
        echo "⚠️  Code de statut inattendu: $httpCode\n";
    }
    
    // Afficher les premiers caractères de la réponse pour diagnostic
    if ($response && strlen($response) > 0) {
        echo "\n📄 Début de la réponse:\n";
        echo substr($response, 0, 500) . "...\n";
    }
}

echo "\n=== Informations supplémentaires ===\n";
echo "- Timestamp actuel: " . time() . " (" . date('Y-m-d H:i:s') . ")\n";
echo "- Timestamp d'expiration: 1751819792 (" . date('Y-m-d H:i:s', 1751819792) . ")\n";
echo "- Lien expiré: " . (time() > 1751819792 ? 'OUI' : 'NON') . "\n";
<?php

echo "=== Analyse de l'URL d'activation ===\n\n";

// Paramètres de l'URL d'activation
$userId = 2;
$hash = 'b1d7b51ef319cdcffdda46c5c6129e396135a858';
$expires = 1751819792;
$signature = '64197a25f78c71906dd57f810b5c91b762599bb9555756b359f78fb8a37277e8';

echo "Paramètres de l'URL:\n";
echo "- User ID: $userId\n";
echo "- Hash: $hash\n";
echo "- Expires: $expires (" . date('Y-m-d H:i:s', $expires) . ")\n";
echo "- Signature: $signature\n\n";

// Vérifier l'expiration
echo "=== Vérification de l'expiration ===\n";
echo "- Timestamp actuel: " . time() . " (" . date('Y-m-d H:i:s') . ")\n";
echo "- Timestamp d'expiration: $expires (" . date('Y-m-d H:i:s', $expires) . ")\n";
$isExpired = time() > $expires;
echo "- Lien expiré: " . ($isExpired ? '✗ OUI - EXPIRÉ' : '✓ Non') . "\n\n";

if ($isExpired) {
    $expiredSince = time() - $expires;
    $days = floor($expiredSince / 86400);
    $hours = floor(($expiredSince % 86400) / 3600);
    $minutes = floor(($expiredSince % 3600) / 60);
    
    echo "🔴 PROBLÈME IDENTIFIÉ: Le lien a expiré il y a ";
    if ($days > 0) echo "$days jour(s), ";
    if ($hours > 0) echo "$hours heure(s), ";
    echo "$minutes minute(s)\n\n";
    
    echo "=== SOLUTION ===\n";
    echo "Le lien d'activation a expiré. Il faut:\n";
    echo "1. Générer un nouveau lien d'activation\n";
    echo "2. Renvoyer l'email d'activation à l'utilisateur\n\n";
    
    echo "Pour générer un nouveau lien, vous pouvez:\n";
    echo "- Utiliser la fonction de renvoi d'email d'activation\n";
    echo "- Ou créer manuellement un nouveau lien avec une nouvelle expiration\n";
} else {
    echo "🟡 Le lien n'a pas expiré, le problème 404 vient d'ailleurs.\n";
    echo "Vérifications supplémentaires nécessaires:\n";
    echo "- Route 'account.activate' existe-t-elle?\n";
    echo "- L'utilisateur avec l'ID $userId existe-t-il?\n";
    echo "- Le hash correspond-il à l'email de l'utilisateur?\n";
    echo "- La signature est-elle valide?\n";
}

echo "\n=== URL complète ===\n";
echo "http://127.0.0.1:8000/fr/activate/$userId/$hash?expires=$expires&signature=$signature\n";
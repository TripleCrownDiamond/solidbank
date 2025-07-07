<?php

require_once __DIR__ . '/vendor/autoload.php';

// Charger l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Carbon;

echo "<h1>Test de la nouvelle configuration d'expiration (72h)</h1>";

// Vérifier la configuration
$expirationMinutes = Config::get('auth.verification.expire', 60);
echo "<p>Configuration actuelle: <strong>" . $expirationMinutes . " minutes</strong></p>";
echo "<p>Équivalent en heures: <strong>" . ($expirationMinutes / 60) . " heures</strong></p>";

// Calculer la date d'expiration
$expirationDate = Carbon::now()->addMinutes($expirationMinutes);
echo "<p>Date d'expiration calculée: <strong>" . $expirationDate->format('Y-m-d H:i:s') . "</strong></p>";
echo "<p>Dans: <strong>" . $expirationDate->diffForHumans() . "</strong></p>";

// Vérifier si c'est bien 72 heures
if ($expirationMinutes == 4320) {
    echo "<p style='color: green;'>✅ Configuration correcte: 72 heures (4320 minutes)</p>";
} else {
    echo "<p style='color: red;'>❌ Configuration incorrecte. Attendu: 4320 minutes, Actuel: " . $expirationMinutes . " minutes</p>";
}

echo "<h2>Comparaison avec l'ancienne configuration</h2>";
echo "<ul>";
echo "<li>Ancienne durée: 60 minutes (1 heure)</li>";
echo "<li>Nouvelle durée: " . $expirationMinutes . " minutes (" . ($expirationMinutes / 60) . " heures)</li>";
echo "<li>Amélioration: " . ($expirationMinutes / 60) . "x plus long</li>";
echo "</ul>";

echo "<h2>Recommandations</h2>";
echo "<ul>";
echo "<li>Redémarrer le serveur Laravel pour appliquer les changements</li>";
echo "<li>Vider le cache de configuration: <code>php artisan config:clear</code></li>";
echo "<li>Tester avec un nouveau lien d'activation généré</li>";
echo "</ul>";
<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

echo "=== Test d'activation avec logs de débogage ===\n\n";

// Trouver un utilisateur non vérifié ou créer un utilisateur de test
$user = User::where('email_verified_at', null)->first();

if (!$user) {
    echo "Aucun utilisateur non vérifié trouvé. Création d'un utilisateur de test...\n";
    $user = User::create([
        'name' => 'Test User Debug',
        'email' => 'test.debug@example.com',
        'password' => bcrypt('password'),
        'email_verified_at' => null,
        'is_active' => false
    ]);
    echo "Utilisateur créé: ID {$user->id}, Email: {$user->email}\n";
} else {
    echo "Utilisateur trouvé: ID {$user->id}, Email: {$user->email}\n";
}

// Générer un nouveau lien d'activation
$activationUrl = URL::temporarySignedRoute(
    'account.activate',
    now()->addMinutes(config('auth.verification.expire', 60)),
    [
        'locale' => 'fr',
        'id' => $user->id,
        'hash' => sha1($user->getEmailForVerification())
    ]
);

echo "\nLien d'activation généré:\n";
echo $activationUrl . "\n\n";

// Effacer les logs précédents
Log::info('=== DÉBUT DU TEST D\'ACTIVATION DEBUG ===', [
    'user_id' => $user->id,
    'user_email' => $user->email,
    'activation_url' => $activationUrl
]);

echo "Test du lien d'activation avec cURL...\n";

// Tester le lien avec cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $activationUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
$error = curl_error($ch);
curl_close($ch);

echo "Résultat du test cURL:\n";
echo "- Code HTTP: $httpCode\n";
echo "- URL finale: $finalUrl\n";

if ($error) {
    echo "- Erreur cURL: $error\n";
}

if ($httpCode >= 400) {
    echo "- ERREUR: Le lien retourne une erreur $httpCode\n";
    
    // Extraire les en-têtes de la réponse
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $headerSize);
    echo "\nEn-têtes de réponse:\n$headers\n";
} else {
    echo "- SUCCÈS: Le lien fonctionne correctement\n";
}

echo "\n=== Vérifiez les logs dans storage/logs/laravel.log pour plus de détails ===\n";
echo "Commande pour voir les logs en temps réel: tail -f storage/logs/laravel.log\n";

Log::info('=== FIN DU TEST D\'ACTIVATION DEBUG ===', [
    'http_code' => $httpCode,
    'final_url' => $finalUrl,
    'curl_error' => $error
]);
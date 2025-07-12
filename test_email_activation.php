<?php

require_once __DIR__ . '/vendor/autoload.php';

// Charger l'environnement Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Mail\AccountActivationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

try {
    // Email de test
    $testEmail = 'gagbahungba2010@gmail.com';

    echo "=== Test d'envoi d'email d'activation ===\n";
    echo "Email de destination: {$testEmail}\n\n";

    // Créer un utilisateur temporaire pour le test
    $testUser = new User([
        'name' => 'Test User',
        'email' => $testEmail,
        'email_verified_at' => null,
    ]);

    // Générer une URL d'activation temporaire
    $activationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => 999,  // ID fictif pour le test
            'hash' => sha1($testEmail),
        ]
    );

    echo "URL d'activation générée: {$activationUrl}\n\n";

    // Créer et envoyer l'email
    $mail = new AccountActivationMail($testUser, $activationUrl);

    echo "Envoi de l'email en cours...\n";

    Mail::to($testEmail)->send($mail);

    echo "✅ Email d'activation envoyé avec succès !\n";
    echo "\nVérifiez votre boîte de réception (et le dossier spam) à l'adresse: {$testEmail}\n";
    echo "\n=== Détails de l'email ===\n";
    echo "Sujet: Confirmation de votre adresse e-mail - Privedyme Bank\n";
    echo "Type: Email d'activation de compte\n";
    echo "En-têtes anti-spam: Activés\n";
    echo "\n=== Modifications appliquées ===\n";
    echo "- Contenu textuel optimisé pour éviter les filtres spam\n";
    echo "- En-têtes spécifiques ajoutés (X-Email-Category, X-Message-Type, etc.)\n";
    echo "- Design du bouton rendu moins 'agressif'\n";
    echo "- Sujet statique et professionnel\n";
} catch (Exception $e) {
    echo "❌ Erreur lors de l'envoi de l'email: " . $e->getMessage() . "\n";
    echo 'Trace: ' . $e->getTraceAsString() . "\n";
}

echo "\n=== Fin du test ===\n";

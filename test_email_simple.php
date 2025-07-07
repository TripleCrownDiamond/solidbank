<?php

// Charger l'autoloader de Composer
require_once __DIR__ . '/vendor/autoload.php';

// Charger l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test d'envoi d'email d'activation ===\n";
echo "Destination: gagbahungba2010@gmail.com\n\n";

try {
    // Importer les classes nécessaires
    $userClass = new ReflectionClass('App\Models\User');
    $mailClass = new ReflectionClass('App\Mail\AccountActivationMail');
    $mailFacade = new ReflectionClass('Illuminate\Support\Facades\Mail');
    $urlFacade = new ReflectionClass('Illuminate\Support\Facades\URL');

    echo "✅ Classes Laravel chargées avec succès\n";

    // Créer un utilisateur de test
    $testUser = new App\Models\User();
    $testUser->name = 'Test User';
    $testUser->email = 'gagbahungba2010@gmail.com';
    $testUser->email_verified_at = null;
    // Définir un ID fictif pour la génération de l'URL
    $testUser->id = 999;

    echo "✅ Utilisateur de test créé\n";

    // Créer l'email (l'URL d'activation sera générée automatiquement)
    $mail = new App\Mail\AccountActivationMail($testUser);

    echo "✅ Email d'activation créé\n";

    // Configuration mail actuelle
    echo "\n=== Configuration Mail ===\n";
    echo 'Driver: ' . config('mail.default') . "\n";
    echo 'Host: ' . config('mail.mailers.smtp.host') . "\n";
    echo 'Port: ' . config('mail.mailers.smtp.port') . "\n";
    echo 'From: ' . config('mail.from.address') . "\n";

    // Envoyer l'email
    echo "\n=== Envoi en cours ===\n";
    Illuminate\Support\Facades\Mail::to('gagbahungba2010@gmail.com')->send($mail);

    echo "✅ Email envoyé avec succès !\n";
    echo "\n📧 Vérifiez votre boîte de réception à l'adresse: gagbahungba2010@gmail.com\n";
    echo "📁 N'oubliez pas de vérifier le dossier spam/courrier indésirable\n";

    echo "\n=== Améliorations appliquées ===\n";
    echo "- Sujet optimisé: 'Confirmation de votre adresse e-mail - Celesium-Fin'\n";
    echo "- Contenu textuel moins 'commercial'\n";
    echo "- En-têtes anti-spam ajoutés\n";
    echo "- Design du bouton moins agressif\n";
} catch (Exception $e) {
    echo '❌ Erreur: ' . $e->getMessage() . "\n";
    echo '📍 Fichier: ' . $e->getFile() . ':' . $e->getLine() . "\n";
    echo "🔍 Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Test terminé ===\n";

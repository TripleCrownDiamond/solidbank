<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\AccountBlock;

// Trouver un utilisateur avec un compte
$user = User::whereHas('accounts')->first();

if (!$user) {
    echo "Aucun utilisateur avec compte trouvé.\n";
    exit(1);
}

echo "Création d'un blocage pour l'utilisateur: {$user->name} ({$user->email})\n";

// Créer un blocage de test
$block = AccountBlock::create([
    'user_id' => $user->id,
    'reason' => 'Test de vérification',
    'description' => 'Blocage de test pour démonstration du système',
    'instructions' => 'Ceci est un blocage de test. Veuillez effectuer un virement de 1€ pour débloquer votre compte.',
    'amount_to_pay' => 1.00,
    'show_rib' => true,
    'request_id_document' => false,
]);

echo "Blocage créé avec l'ID: {$block->id}\n";

// Associer le blocage au premier compte de l'utilisateur
$account = $user->accounts()->first();
$account->accountBlocks()->attach($block->id, ['status' => 'active']);

echo "Blocage associé au compte: {$account->account_number}\n";
echo "Blocage créé avec succès!\n";
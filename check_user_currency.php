<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Account;

echo "=== Vérification des devises des comptes ===\n\n";

// Récupérer tous les utilisateurs avec leurs comptes
$users = User::with('accounts')->get();

foreach ($users as $user) {
    echo "Utilisateur: {$user->name} (ID: {$user->id})\n";
    
    if ($user->accounts->count() > 0) {
        echo "Comptes:\n";
        foreach ($user->accounts as $account) {
            echo "  - Compte: {$account->account_number}\n";
            echo "    Devise: {$account->currency}\n";
            echo "    Statut: {$account->status}\n";
        }
    } else {
        echo "Aucun compte trouvé\n";
    }
    
    echo str_repeat('-', 60) . "\n";
}

echo "\n=== Fin de la vérification ===\n";
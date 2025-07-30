<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AccountBlock;
use App\Models\User;
use App\Models\Account;

echo "=== Vérification des blocages de comptes ===\n\n";

// Vérifier le nombre total de blocages
$totalBlocks = AccountBlock::count();
echo "Nombre total de blocages: {$totalBlocks}\n\n";

if ($totalBlocks > 0) {
    echo "Liste des blocages:\n";
    echo str_repeat('-', 80) . "\n";
    
    $blocks = AccountBlock::with(['user'])->get();
    
    foreach ($blocks as $block) {
        echo "ID: {$block->id}\n";
        echo "Utilisateur: {$block->user->name} ({$block->user->email})\n";
        echo "Raison: {$block->reason}\n";
        echo "Description: {$block->description}\n";
        echo "Instructions: {$block->instructions}\n";
        echo "Montant à payer: {$block->amount_to_pay}\n";
        echo "Afficher RIB: " . ($block->show_rib ? 'Oui' : 'Non') . "\n";
        echo "Demander pièce d'identité: " . ($block->request_id_document ? 'Oui' : 'Non') . "\n";
        echo "Statut: {$block->status}\n";
        echo "Créé le: {$block->created_at}\n";
        echo str_repeat('-', 80) . "\n";
    }
} else {
    echo "Aucun blocage trouvé.\n\n";
    
    // Vérifier s'il y a des comptes
    $totalAccounts = Account::count();
    echo "Nombre total de comptes: {$totalAccounts}\n";
    
    if ($totalAccounts > 0) {
        echo "\nListe des comptes disponibles:\n";
        echo str_repeat('-', 60) . "\n";
        
        $accounts = Account::with('user')->get();
        foreach ($accounts as $account) {
            echo "ID: {$account->id} | Numéro: {$account->account_number} | Devise: {$account->currency} | Utilisateur: {$account->user->name}\n";
        }
    }
}

// Vérifier les utilisateurs
$totalUsers = User::count();
echo "\nNombre total d'utilisateurs: {$totalUsers}\n";

echo "\n=== Fin de la vérification ===\n";
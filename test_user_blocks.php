<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\AccountBlock;

echo "=== Test des blocages utilisateur ===\n\n";

// Récupérer tous les utilisateurs
$users = User::all();

foreach ($users as $user) {
    echo "Utilisateur: {$user->name} (ID: {$user->id})\n";
    
    // Méthode 1: Via la relation
    $blocksViaRelation = $user->accountBlocks;
    echo "Blocages via relation: {$blocksViaRelation->count()}\n";
    
    // Méthode 2: Requête directe
    $blocksDirectQuery = AccountBlock::where('user_id', $user->id)->get();
    echo "Blocages via requête directe: {$blocksDirectQuery->count()}\n";
    
    if ($blocksViaRelation->count() > 0) {
        echo "Détails des blocages via relation:\n";
        foreach ($blocksViaRelation as $block) {
            echo "  - ID: {$block->id}, Raison: {$block->reason}, Statut: {$block->status}\n";
        }
    }
    
    if ($blocksDirectQuery->count() > 0) {
        echo "Détails des blocages via requête directe:\n";
        foreach ($blocksDirectQuery as $block) {
            echo "  - ID: {$block->id}, Raison: {$block->reason}, Statut: {$block->status}\n";
        }
    }
    
    echo str_repeat('-', 60) . "\n";
}

echo "\n=== Fin du test ===\n";
<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Assigning default transfer step groups to existing accounts and wallets...\n";

// Assigner le groupe "Vérification Standard" aux comptes existants
$standardGroup = App\Models\TransferStepGroup::where('name', 'Vérification Standard')->first();
if ($standardGroup) {
    $accounts = App\Models\Account::whereDoesntHave('transferStepGroups')->get();
    echo "Found {$accounts->count()} accounts without transfer step groups\n";
    
    foreach ($accounts as $account) {
        $account->transferStepGroups()->attach($standardGroup->id);
        echo "Assigned 'Vérification Standard' to account {$account->id}\n";
    }
} else {
    echo "Standard group not found!\n";
}

// Assigner le groupe "Vérification Express" aux wallets existants
$expressGroup = App\Models\TransferStepGroup::where('name', 'Vérification Express')->first();
if ($expressGroup) {
    $wallets = App\Models\Wallet::whereDoesntHave('transferStepGroups')->get();
    echo "Found {$wallets->count()} wallets without transfer step groups\n";
    
    foreach ($wallets as $wallet) {
        $wallet->transferStepGroups()->attach($expressGroup->id);
        echo "Assigned 'Vérification Express' to wallet {$wallet->id}\n";
    }
} else {
    echo "Express group not found!\n";
}

echo "\nDone! Verifying assignments...\n";

// Vérifier les assignations
$accounts = App\Models\Account::with('transferStepGroups')->get();
foreach($accounts as $account) {
    echo "Account {$account->id}: " . $account->transferStepGroups->count() . " groups\n";
}

$wallets = App\Models\Wallet::with('transferStepGroups')->get();
foreach($wallets as $wallet) {
    echo "Wallet {$wallet->id}: " . $wallet->transferStepGroups->count() . " groups\n";
}
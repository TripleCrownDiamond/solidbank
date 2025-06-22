<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking Account-TransferStepGroup associations:\n";
$accounts = App\Models\Account::with('transferStepGroups')->get();
foreach($accounts as $account) {
    echo "Account {$account->id}: " . $account->transferStepGroups->count() . " groups\n";
    foreach($account->transferStepGroups as $group) {
        echo "  - Group {$group->id}: {$group->name}\n";
    }
}

echo "\nChecking Wallet-TransferStepGroup associations:\n";
$wallets = App\Models\Wallet::with('transferStepGroups')->get();
foreach($wallets as $wallet) {
    echo "Wallet {$wallet->id}: " . $wallet->transferStepGroups->count() . " groups\n";
    foreach($wallet->transferStepGroups as $group) {
        echo "  - Group {$group->id}: {$group->name}\n";
    }
}

echo "\nChecking TransferStepGroups and their steps:\n";
$groups = App\Models\TransferStepGroup::with('transferSteps')->get();
foreach($groups as $group) {
    echo "Group {$group->id}: {$group->name} ({$group->transferSteps->count()} steps)\n";
    foreach($group->transferSteps as $step) {
        echo "  - Step {$step->id}: {$step->title} (order: {$step->order})\n";
    }
}
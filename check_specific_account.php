<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking account ACC8119888...\n";

$account = App\Models\Account::where('account_number', 'ACC8119888')->with('transferStepGroups.transferSteps')->first();

if ($account) {
    echo "Account found: ID {$account->id}, Number: {$account->account_number}\n";
    echo "Transfer step groups assigned: {$account->transferStepGroups->count()}\n";
    
    if ($account->transferStepGroups->count() > 0) {
        foreach ($account->transferStepGroups as $group) {
            echo "\nGroup: {$group->name} (ID: {$group->id})\n";
            echo "Description: {$group->description}\n";
            echo "Steps in this group: {$group->transferSteps->count()}\n";
            
            foreach ($group->transferSteps->sortBy('order') as $step) {
                echo "  - Step {$step->order}: {$step->title} (Code: {$step->code})\n";
            }
        }
    } else {
        echo "No transfer step groups assigned to this account.\n";
    }
} else {
    echo "Account ACC8119888 not found.\n";
}
<?php

require_once 'vendor/autoload.php';

// Charger l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Account;
use App\Models\AccountBlock;
use Illuminate\Support\Facades\DB;

echo "=== VÉRIFICATION DES BLOCAGES ===\n\n";

// 1. Vérifier tous les blocages existants
echo "1. TOUS LES BLOCAGES DANS LA TABLE account_blocks:\n";
$blocks = AccountBlock::all();
foreach ($blocks as $block) {
    echo "ID: {$block->id}, Raison: {$block->reason}, Montant: {$block->amount_to_pay}\n";
    echo "Description: {$block->description}\n";
    echo "Instructions: {$block->instructions}\n";
    echo "User ID: {$block->user_id}\n";
    echo "---\n";
}
echo "Total: " . $blocks->count() . " blocages\n\n";

// 2. Vérifier la table pivot account_account_block
echo "2. RELATIONS DANS LA TABLE PIVOT account_account_block:\n";
$pivotData = DB::table('account_account_block')
    ->join('accounts', 'account_account_block.account_id', '=', 'accounts.id')
    ->join('account_blocks', 'account_account_block.account_block_id', '=', 'account_blocks.id')
    ->join('users', 'accounts.user_id', '=', 'users.id')
    ->select(
        'users.first_name',
        'users.last_name', 
        'users.email',
        'accounts.account_number',
        'account_blocks.reason as block_reason',
        'account_blocks.amount_to_pay',
        'account_account_block.status',
        'account_account_block.created_at'
    )
    ->get();

foreach ($pivotData as $relation) {
    echo "Utilisateur: {$relation->first_name} {$relation->last_name} ({$relation->email})\n";
    echo "Compte: {$relation->account_number}\n";
    echo "Blocage: {$relation->block_reason} - {$relation->amount_to_pay}\n";
    echo "Status: {$relation->status}\n";
    echo "Créé le: {$relation->created_at}\n";
    echo "---\n";
}
echo "Total relations: " . $pivotData->count() . "\n\n";

// 3. Vérifier les utilisateurs sans blocages
echo "3. UTILISATEURS SANS BLOCAGES:\n";
$usersWithoutBlocks = User::whereDoesntHave('accounts.accountBlocks')->get();
foreach ($usersWithoutBlocks as $user) {
    echo "Utilisateur: {$user->first_name} {$user->last_name} ({$user->email})\n";
}
echo "Total utilisateurs sans blocages: " . $usersWithoutBlocks->count() . "\n\n";

// 4. Vérifier les comptes sans blocages
echo "4. COMPTES SANS BLOCAGES:\n";
$accountsWithoutBlocks = Account::whereDoesntHave('accountBlocks')->with('user')->get();
foreach ($accountsWithoutBlocks as $account) {
    echo "Compte: {$account->account_number} - Utilisateur: {$account->user->first_name} {$account->user->last_name}\n";
}
echo "Total comptes sans blocages: " . $accountsWithoutBlocks->count() . "\n\n";

// 5. Statistiques générales
echo "5. STATISTIQUES GÉNÉRALES:\n";
echo "Total utilisateurs: " . User::count() . "\n";
echo "Total comptes: " . Account::count() . "\n";
echo "Total blocages: " . AccountBlock::count() . "\n";
echo "Total relations compte-blocage: " . DB::table('account_account_block')->count() . "\n";

// 6. Vérifier le premier blocage et combien de comptes l'ont
echo "\n6. PREMIER BLOCAGE ET SES ASSOCIATIONS:\n";
$firstBlock = AccountBlock::first();
if ($firstBlock) {
    echo "Premier blocage: {$firstBlock->reason} (ID: {$firstBlock->id})\n";
    echo "Montant: {$firstBlock->amount_to_pay}\n";
    echo "User ID: {$firstBlock->user_id}\n";
    $accountsWithFirstBlock = DB::table('account_account_block')
        ->where('account_block_id', $firstBlock->id)
        ->count();
    echo "Nombre de comptes avec ce blocage: {$accountsWithFirstBlock}\n";
    
    // Afficher les statuts dans la table pivot pour ce blocage
    $pivotStatuses = DB::table('account_account_block')
        ->where('account_block_id', $firstBlock->id)
        ->select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->get();
    echo "Statuts dans la table pivot:\n";
    foreach ($pivotStatuses as $statusInfo) {
        echo "  - {$statusInfo->status}: {$statusInfo->count} comptes\n";
    }
} else {
    echo "Aucun blocage trouvé dans la base de données.\n";
}

echo "\n=== FIN DE LA VÉRIFICATION ===\n";
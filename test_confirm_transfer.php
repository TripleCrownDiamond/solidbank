<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

try {
    // Simuler une authentification
    $user = User::first();
    if (!$user) {
        echo "Aucun utilisateur trouvé\n";
        exit(1);
    }
    
    Auth::login($user);
    echo "Utilisateur connecté: {$user->email}\n";
    
    // Compter les transactions avant
    $countBefore = Transaction::count();
    echo "Nombre de transactions avant: {$countBefore}\n";
    
    // Simuler les données du formulaire
    $transferData = [
        'user_id' => Auth::id(),
        'type' => 'TRANSFER_EXTERNAL',
        'amount' => 100,
        'currency' => 'EUR',
        'status' => Transaction::STATUS_BLOCKED,
        'description' => 'Test Transfert',
        'reference' => 'TRF-' . strtoupper(uniqid()),
        'account_id' => 1,
        'wallet_id' => null,
        'external_bank_info' => [
            'recipient_name' => 'Test Recipient',
            'recipient_iban' => 'FR1420041010050500013M02606',
            'recipient_bank' => 'Test Bank',
            'recipient_country' => 'FR'
        ],
        'is_blocked' => true,
        'blocked_reason' => 'Transfert en attente de validation',
        'blocked_at' => now()
    ];
    
    echo "Création de la transaction...\n";
    $transaction = Transaction::create($transferData);
    
    echo "Transaction créée avec succès!\n";
    echo "ID: {$transaction->id}\n";
    echo "Status: {$transaction->status}\n";
    echo "Amount: {$transaction->amount}\n";
    echo "Reference: {$transaction->reference}\n";
    
    // Compter les transactions après
    $countAfter = Transaction::count();
    echo "Nombre de transactions après: {$countAfter}\n";
    
    // Vérifier la session
    session([
        'transfer_data' => [
            'transaction_id' => $transaction->id,
            'source_type' => 'account',
            'selected_source_id' => 1,
            'recipient_name' => 'Test Recipient',
            'transfer_amount' => 100,
            'transfer_currency' => 'EUR'
        ]
    ]);
    
    echo "Données stockées en session\n";
    echo "URL de redirection: /transfers/progress/{$transaction->id}\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
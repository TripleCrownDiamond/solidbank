<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Transaction;

echo "Nombre total de transactions: " . Transaction::count() . "\n";
echo "\nTransactions récentes:\n";

$transactions = Transaction::latest()->take(5)->get(['id', 'user_id', 'type', 'amount', 'status', 'created_at']);

foreach ($transactions as $transaction) {
    echo "ID: {$transaction->id}, User: {$transaction->user_id}, Type: {$transaction->type}, Amount: {$transaction->amount}, Status: {$transaction->status}, Created: {$transaction->created_at}\n";
}

echo "\nTransactions avec ID 11:\n";
$transaction11 = Transaction::find(11);
if ($transaction11) {
    echo "Transaction trouvée - ID: {$transaction11->id}, User: {$transaction11->user_id}, Type: {$transaction11->type}, Amount: {$transaction11->amount}, Status: {$transaction11->status}\n";
} else {
    echo "Aucune transaction avec l'ID 11\n";
}
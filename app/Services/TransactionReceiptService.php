<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Config;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class TransactionReceiptService
{
    /**
     * Générer un bordereau PDF pour une transaction
     */
    public function generateReceipt(Transaction $transaction): string
    {
        // Récupérer les informations de la banque
        $config = Config::first();
        
        // Déterminer la devise
        $currency = $transaction->currency ?: 
            ($transaction->account ? $transaction->account->currency : 
                ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR'));
        
        // Préparer les données pour le PDF
        $data = [
            'transaction' => $transaction,
            'user' => $transaction->user,
            'config' => $config,
            'amount' => number_format($transaction->amount, 2),
            'currency' => $currency,
            'date' => $transaction->created_at->format('d/m/Y H:i'),
            'reference' => $transaction->reference ?: $this->generateReference($transaction),
            'bank_name' => bank_config('bank_name'),
            'bank_address' => bank_config('bank_address'),
            'bank_phone' => bank_config('bank_phone'),
            'bank_email' => bank_config('bank_email'),
            'bank_swift' => bank_config('bank_swift'),
        ];
        
        // Générer le PDF
        $pdf = Pdf::loadView('pdf.transaction-receipt', $data);
        $pdf->setPaper('A4', 'portrait');
        
        // Créer le nom du fichier
        $filename = 'bordereau_' . $transaction->type . '_' . $transaction->id . '_' . time() . '.pdf';
        $filepath = 'receipts/' . $filename;
        
        // Sauvegarder le PDF
        Storage::disk('public')->put($filepath, $pdf->output());
        
        return $filepath;
    }
    
    /**
     * Générer une référence pour la transaction
     */
    private function generateReference(Transaction $transaction): string
    {
        $prefix = match($transaction->type) {
            'DEPOSIT' => 'DEP',
            'WITHDRAWAL' => 'WIT',
            'TRANSFER_BANK' => 'TRB',
            'TRANSFER_CRYPTO' => 'TRC',
            'TRANSFER_EXTERNAL' => 'TRE',
            default => 'TXN'
        };
        
        return $prefix . '-' . str_pad($transaction->id, 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Obtenir le chemin complet du fichier PDF
     */
    public function getReceiptPath(string $filepath): string
    {
        return Storage::disk('public')->path($filepath);
    }
    
    /**
     * Vérifier si un bordereau existe
     */
    public function receiptExists(string $filepath): bool
    {
        return Storage::disk('public')->exists($filepath);
    }
}
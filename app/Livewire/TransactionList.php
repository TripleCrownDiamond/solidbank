<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Wallet;
use App\Models\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\TransactionConfirmationMail;
use App\Mail\TransactionCancellationMail;
use App\Mail\TransferConfirmationMail;
use App\Mail\TransferCancellationMail;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionList extends Component
{
    // Méthodes pour traiter les transactions par l'admin
    public function confirmPendingTransaction($transactionId)
    {
        if (!Auth::user()->is_admin) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => __('common.unauthorized_access')
            ]);
            return;
        }

        $transaction = null;  // Initialize $transaction outside the closure
        try {
            DB::transaction(function () use ($transactionId, &$transaction) {
                $transaction = Transaction::findOrFail($transactionId);

                if ($transaction->status !== 'PENDING') {
                    throw new \Exception(__('common.transaction_cannot_be_confirmed'));
                }

                // Traiter selon le type de transaction
                if ($transaction->type === 'DEPOSIT') {
                    $this->processDeposit($transaction);
                } elseif ($transaction->type === 'WITHDRAWAL') {
                    $this->processWithdrawal($transaction);
                }

                // Mettre à jour le statut
                $transaction->update([
                    'status' => 'COMPLETED',
                    'processed_by_admin_id' => Auth::id(),
                    'processed_at' => now()
                ]);

                // Envoyer un email de confirmation
                $this->sendTransactionEmail($transaction, 'confirmed');
            });

            $message = '';
            if ($transaction?->type === 'DEPOSIT') {
                $message = __('common.deposit_confirmed_successfully');
            } elseif ($transaction?->type === 'WITHDRAWAL') {
                $message = __('common.withdrawal_confirmed_successfully');
            } else {
                $message = __('common.transaction_confirmed_successfully');
            }

            $this->dispatch('alert', [
                'type' => 'success',
                'message' => $message
            ]);
            $this->dispatch('transactionUpdated');
        } catch (\Exception $e) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function cancelPendingTransaction($transactionId)
    {
        if (!Auth::user()->is_admin) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => __('common.unauthorized_access')
            ]);
            return;
        }

        try {
            $transaction = Transaction::findOrFail($transactionId);

            if ($transaction->status !== 'PENDING') {
                throw new \Exception(__('common.transaction_cannot_be_cancelled'));
            }

            $transaction->update([
                'status' => 'CANCELLED',
                'processed_by_admin_id' => Auth::id(),
                'processed_at' => now()
            ]);

            // Envoyer un email d'annulation
            $this->sendTransactionEmail($transaction, 'cancelled');

            $this->dispatch('alert', [
                'type' => 'success',
                'message' => __('common.transaction_cancelled_successfully')
            ]);

            $this->dispatch('transactionUpdated');
        } catch (\Exception $e) {
            Log::error('Transaction cancellation failed: ' . $e->getMessage());
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    private function processDeposit($transaction)
    {
        if ($transaction->account_id) {
            // Dépôt sur compte
            $account = Account::findOrFail($transaction->account_id);
            $account->increment('balance', $transaction->amount);
        } elseif ($transaction->wallet_id) {
            // Dépôt sur wallet
            $wallet = Wallet::findOrFail($transaction->wallet_id);
            $wallet->increment('balance', $transaction->amount);
        }
    }

    private function processWithdrawal($transaction)
    {
        if ($transaction->account_id) {
            // Retrait du compte
            $account = Account::findOrFail($transaction->account_id);
            if ($account->balance < $transaction->amount) {
                throw new \Exception(__('common.insufficient_balance_withdrawal'));
            }
            $account->decrement('balance', $transaction->amount);
        } elseif ($transaction->wallet_id) {
            // Retrait du wallet
            $wallet = Wallet::findOrFail($transaction->wallet_id);
            if ($wallet->balance < $transaction->amount) {
                throw new \Exception(__('common.insufficient_balance_withdrawal'));
            }
            $wallet->decrement('balance', $transaction->amount);
        }
    }

    private function sendTransactionEmail($transaction, $type)
    {
        try {
            $user = $transaction->user;
            $amount = number_format($transaction->amount, 2);
            $currency = $transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR'));

            $data = [
                'user' => $user,
                'transaction' => $transaction,
                'amount' => $amount,
                'currency' => $currency
            ];

            if ($type === 'confirmed') {
                // Utiliser un sujet et un message différents selon le type de transaction
                // $emailSubject = 'common.transaction_confirmed_subject';
                $emailTemplate = 'emails.transaction-confirmed';

                if ($transaction->type === 'DEPOSIT') {
                    $emailSubject = 'common.deposit_confirmed_email_subject';
                } elseif ($transaction->type === 'WITHDRAWAL') {
                    $emailSubject = 'common.withdrawal_confirmed_email_subject';
                }

                $messageKey = '';
                if ($transaction->type === 'DEPOSIT') {
                    $messageKey = 'deposit_confirmed_email_message';
                } elseif ($transaction->type === 'WITHDRAWAL') {
                    $messageKey = 'withdrawal_cancelled_email_message';
                }
                $emailMessage = __('common.' . $messageKey, ['amount' => "{$amount} {$currency}"]);
                Mail::to($user->email)->send(new TransactionNotification(
                    $emailSubject,
                    $emailMessage,
                    $user,
                    $transaction,
                    $amount,
                    $type === 'confirmed' ? 'emails.transaction-confirmed' : 'emails.transaction-cancelled'
                ));
            } elseif ($type === 'cancelled') {
                $emailSubject = 'common.transaction_cancelled_subject';
                $messageKey = '';
                if ($transaction->type === 'DEPOSIT') {
                    $emailSubject = 'common.deposit_cancelled_email_subject';
                    $messageKey = 'deposit_cancelled_email_message';
                } elseif ($transaction->type === 'WITHDRAWAL') {
                    $emailSubject = 'common.withdrawal_cancelled_email_subject';
                    $messageKey = 'withdrawal_cancelled_email_message';
                }
                $emailMessage = __('common.' . $messageKey, ['amount' => "{$amount} {$currency}"]);
                Mail::to($user->email)->send(new TransactionNotification(
                    $emailSubject,
                    $emailMessage,
                    $user,
                    $transaction,
                    $amount,
                    $type === 'confirmed' ? 'emails.transaction-confirmed' : 'emails.transaction-cancelled'
                ));
            }
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas faire échouer la transaction
            Log::error(__('common.email_sending_error') . ': ' . $e->getMessage());
        }
    }

    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $typeFilter = 'all';
    public $perPage = 10;

    public $showBlockedDetailsModal = false;
    public $selectedTransaction;

    protected $listeners = [
        'transactionCreated' => 'refreshTransactions',
        'transaction-created' => 'refreshTransactions',
        'execute-method' => 'executeMethod'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function refreshTransactions()
    {
        $this->resetPage();
        $this->reset(['search', 'statusFilter', 'typeFilter']);
        $this->resetPage();
    }

    public function executeMethod($data)
    {
        $method = $data['method'] ?? null;
        $params = $data['params'] ?? [];

        if ($method && method_exists($this, $method)) {
            if (!empty($params)) {
                call_user_func_array([$this, $method], $params);
            } else {
                $this->$method();
            }
        }
    }

    public function getTransactionsProperty()
    {
        $query = Transaction::with(['user', 'account', 'wallet', 'fromAccount', 'toAccount', 'processedByAdmin'])
            ->orderBy('created_at', 'desc');

        // Si l'utilisateur n'est pas admin, filtrer les transactions
        if (!Auth::user()->is_admin) {
            $userAccounts = Account::where('user_id', Auth::id())->pluck('id');
            $userWallets = Wallet::where('user_id', Auth::id())->pluck('id');

            $query->where(function ($q) use ($userAccounts, $userWallets) {
                $q
                    ->whereIn('account_id', $userAccounts)
                    ->orWhereIn('wallet_id', $userWallets)
                    ->orWhereIn('from_account_id', $userAccounts)
                    ->orWhereIn('to_account_id', $userAccounts)
                    ->orWhere('user_id', Auth::id());
            });
        }

        // Filtres de recherche
        if ($this->search) {
            $query->where(function ($q) {
                $q
                    ->where('description', 'like', '%' . $this->search . '%')
                    ->orWhere('reference', 'like', '%' . $this->search . '%')
                    ->orWhere('amount', 'like', '%' . $this->search . '%');
            });
        }

        // Filtre par statut
        if ($this->statusFilter !== 'all') {
            $query->where('status', strtoupper($this->statusFilter));
        }

        // Filtre par type
        if ($this->typeFilter !== 'all') {
            $query->where('type', strtoupper($this->typeFilter));
        }

        return $query->paginate($this->perPage);
    }

    public function showBlockedTransactionDetails($transactionId)
    {
        $this->selectedTransaction = Transaction::with([
            'user', 
            'account.rib', 
            'toAccount.rib.user',
            'toAccount.user',
            'blockedAtTransferStep.transferStepGroup.transferSteps', 
            'blockedAtTransferStepGroup.transferSteps',
            'transferStepCompletions.transferStep'
        ])->find($transactionId);

        $this->showBlockedDetailsModal = true;
    }

    public function closeBlockedDetailsModal()
    {
        $this->showBlockedDetailsModal = false;
        $this->selectedTransaction = null;
    }

    public function confirmTransaction($transactionId)
    {
        if (!Auth::user()->is_admin) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.unauthorized_access')]);
            return;
        }

        $transaction = Transaction::find($transactionId);
        if (!$transaction) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.transaction_not_found')]);
            return;
        }

        if (!$transaction->isPending()) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.only_pending_transactions_can_be_confirmed')]);
            return;
        }

        // Gestion spéciale pour les transferts TRF
        if (in_array($transaction->type, ['TRANSFER_BANK', 'TRANSFER_CRYPTO', 'TRANSFER_EXTERNAL'])) {
            $this->processTransferConfirmation($transactionId);
        } else {
            $this->processConfirmTransaction($transactionId);
        }
    }

    public function processConfirmTransaction($transactionId)
    {
        try {
            $transaction = Transaction::find($transactionId);
            if (!$transaction || !$transaction->isPending()) {
                throw new \Exception(__('messages.invalid_or_processed_transaction'));
            }

            // Utiliser la méthode confirm du modèle (qui gère automatiquement la mise à jour du solde et l'envoi d'email)
            $transaction->confirm(Auth::id());

            $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.transaction_confirmed_successfully')]);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.transaction_confirmation_error', ['error' => $e->getMessage()])]);
        }

        $this->dispatch('action-completed');
    }

    public function cancelTransaction($transactionId)
    {
        if (!Auth::user()->is_admin) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.unauthorized_access')]);
            return;
        }

        $transaction = Transaction::find($transactionId);
        if (!$transaction) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.transaction_not_found')]);
            return;
        }

        if (!$transaction->isPending()) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.only_pending_transactions_can_be_cancelled')]);
            return;
        }

        // Gestion spéciale pour les transferts TRF
        if (in_array($transaction->type, ['TRANSFER_BANK', 'TRANSFER_CRYPTO', 'TRANSFER_EXTERNAL'])) {
            $this->processTransferCancellation($transactionId);
        } else {
            $this->processCancelTransaction($transactionId);
        }
    }

    public function processCancelTransaction($transactionId)
    {
        try {
            $transaction = Transaction::find($transactionId);
            if (!$transaction || !$transaction->isPending()) {
                throw new \Exception(__('messages.invalid_or_processed_transaction'));
            }

            // Utiliser la méthode cancel du modèle (qui gère automatiquement l'envoi d'email)
            $transaction->cancel(Auth::id(), "Annulée par l'administrateur");

            $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.transaction_cancelled_successfully')]);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.transaction_cancellation_error', ['error' => $e->getMessage()])]);
        }

        $this->dispatch('action-completed');
    }

    /**
     * Bloquer une transaction à une étape de transfert
     */
    public function blockTransaction($transactionId, $transferStepId, $transferStepGroupId = null, $reason = null)
    {
        if (!Auth::user()->is_admin) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.unauthorized_access')]);
            return;
        }

        $transaction = Transaction::find($transactionId);
        if (!$transaction) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.transaction_not_found')]);
            return;
        }

        if (!$transaction->isPending()) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.only_pending_transactions_can_be_blocked')]);
            return;
        }

        $transaction->blockAtTransferStep($transferStepId, $transferStepGroupId, $reason);

        $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.transaction_blocked_successfully')]);
    }

    /**
     * Débloquer une transaction
     */
    public function unblockTransaction($transactionId)
    {
        if (!Auth::user()->is_admin) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.unauthorized_access')]);
            return;
        }

        $transaction = Transaction::find($transactionId);
        if (!$transaction) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.transaction_not_found')]);
            return;
        }

        if (!$transaction->isBlocked()) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.transaction_not_blocked')]);
            return;
        }

        $transaction->unblock(Auth::id());

        $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.transaction_unblocked_successfully')]);
    }

    /**
     * Traiter la confirmation d'un transfert TRF
     */
    public function processTransferConfirmation($transactionId)
    {
        try {
            DB::transaction(function () use ($transactionId) {
                $transaction = Transaction::findOrFail($transactionId);
                
                if (!$transaction->isPending()) {
                    throw new \Exception(__('messages.invalid_or_processed_transaction'));
                }

                // Vérifier et débiter le solde selon le type de source
                if ($transaction->account_id) {
                    $account = Account::findOrFail($transaction->account_id);
                    if ($account->balance < $transaction->amount) {
                        throw new \Exception(__('transfers.insufficient_balance_transfer'));
                    }
                    $account->decrement('balance', $transaction->amount);
                } elseif ($transaction->wallet_id) {
                    $wallet = Wallet::findOrFail($transaction->wallet_id);
                    if ($wallet->balance < $transaction->amount) {
                        throw new \Exception(__('transfers.insufficient_balance_transfer'));
                    }
                    $wallet->decrement('balance', $transaction->amount);
                }

                // Mettre à jour le statut de la transaction
                $transaction->update([
                    'status' => Transaction::STATUS_COMPLETED,
                    'processed_by_admin_id' => Auth::id(),
                    'processed_at' => now()
                ]);

                // Générer le ticket PDF
                $ticketPath = $this->generateTransferTicket($transaction);
                
                // Envoyer l'email de confirmation avec le ticket
                $this->sendTransferConfirmationEmail($transaction, $ticketPath);
            });

            $this->dispatch('alert', ['type' => 'success', 'message' => __('transfers.transfer_confirmed_successfully')]);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => $e->getMessage()]);
        }

        $this->dispatch('action-completed');
    }

    /**
     * Traiter l'annulation d'un transfert TRF
     */
    public function processTransferCancellation($transactionId)
    {
        try {
            $transaction = Transaction::findOrFail($transactionId);
            
            if (!$transaction->isPending()) {
                throw new \Exception(__('messages.invalid_or_processed_transaction'));
            }

            // Annuler la transaction
            $transaction->update([
                'status' => Transaction::STATUS_CANCELLED,
                'processed_by_admin_id' => Auth::id(),
                'processed_at' => now()
            ]);

            // Envoyer l'email d'annulation
            $this->sendTransferCancellationEmail($transaction);

            $this->dispatch('alert', ['type' => 'success', 'message' => __('transfers.transfer_cancelled_successfully')]);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => $e->getMessage()]);
        }

        $this->dispatch('action-completed');
    }

    /**
     * Générer le ticket PDF pour un transfert
     */
    private function generateTransferTicket($transaction)
    {
        // Récupérer les informations de configuration bancaire
        $config = \App\Models\Config::first();
        
        $ticketData = [
            'transaction' => $transaction,
            'config' => $config,
            'bank_info' => [
                'name' => $config->bank_name ?? __('transfers.bank_name_default'),
                'swift' => $config->bank_swift ?? '',
                'country' => $config->bank_country ?? '',
                'address' => $config->bank_address ?? '',
                'phone' => $config->bank_phone ?? '',
                'email' => $config->bank_email ?? '',
                'website' => $config->bank_website ?? ''
            ],
            'generated_at' => now()->format('d/m/Y H:i:s')
        ];

        // Récupérer les données du compte ou wallet source
        $account = $transaction->account_id ? $transaction->account : null;
        $wallet = $transaction->wallet_id ? $transaction->wallet : null;
        
        // Créer le contenu HTML du ticket
        $html = view('pdfs.transfer-ticket', [
            'transaction' => $transaction,
            'account' => $account,
            'wallet' => $wallet,
            'config' => $config
        ])->render();
        
        // Générer le PDF avec dompdf
        $filename = 'transfer_ticket_' . $transaction->reference . '_' . time() . '.pdf';
        $filepath = storage_path('app/public/tickets/' . $filename);
        
        // Créer le répertoire s'il n'existe pas
        if (!file_exists(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }
        
        // Générer le PDF
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->save($filepath);
        
        return $filename;
    }

    /**
     * Envoyer l'email de confirmation de transfert avec ticket
     */
    private function sendTransferConfirmationEmail($transaction, $ticketPath)
    {
        try {
            $user = $transaction->user;
            $ticketUrl = url('storage/tickets/' . $ticketPath);
            
            Mail::to($user->email)->send(new \App\Mail\TransferConfirmationMail(
                $transaction,
                $ticketUrl
            ));
        } catch (\Exception $e) {
            Log::error('Erreur envoi email confirmation transfert: ' . $e->getMessage());
        }
    }

    /**
     * Envoyer l'email d'annulation de transfert
     */
    private function sendTransferCancellationEmail($transaction)
    {
        try {
            $user = $transaction->user;
            
            Mail::to($user->email)->send(new \App\Mail\TransferCancellationMail(
                $transaction
            ));
        } catch (\Exception $e) {
            Log::error('Erreur envoi email annulation transfert: ' . $e->getMessage());
        }
    }

    /**
     * Télécharger le ticket d'un transfert
     */
    public function downloadTransferTicket($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        
        // Vérifier les permissions
        if (!Auth::user()->is_admin && $transaction->user_id !== Auth::id()) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.unauthorized_access')]);
            return;
        }
        
        if ($transaction->type !== 'TRF' || !$transaction->isCompleted()) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('transfers.ticket_not_available')]);
            return;
        }
        
        // Générer ou récupérer le ticket
        $ticketPath = $this->generateTransferTicket($transaction);
        $fullPath = storage_path('app/public/tickets/' . $ticketPath);
        
        if (file_exists($fullPath)) {
            return response()->download($fullPath, 'ticket_transfert_' . $transaction->reference . '.pdf');
        } else {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('transfers.ticket_not_found')]);
        }
    }

    public function render()
    {
        return view('livewire.transaction-list', [
            'transactions' => $this->transactions
        ]);
    }
}

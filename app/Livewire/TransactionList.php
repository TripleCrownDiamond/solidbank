<?php

namespace App\Livewire;

use App\Mail\TransactionNotification;
use App\Models\Account;
use App\Models\Config;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\TransactionReceiptService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionList extends Component
{
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

    // Méthodes pour traiter les transactions par l'admin

    /*
     * public function confirmPendingTransaction($transactionId)
     * {
     *     if (!Auth::user()->is_admin) {
     *         $this->dispatch('alert', [
     *             'type' => 'error',
     *             'message' => __('common.unauthorized_access')
     *         ]);
     *         return;
     *     }
     *
     *     $transaction = null;  // Initialize $transaction outside the closure
     *     try {
     *         DB::transaction(function () use ($transactionId, &$transaction) {
     *             $transaction = Transaction::findOrFail($transactionId);
     *
     *             if ($transaction->status !== 'PENDING') {
     *                 throw new \Exception(__('common.transaction_cannot_be_confirmed'));
     *             }
     *
     *             // Traiter selon le type de transaction
     *             if ($transaction->type === 'DEPOSIT') {
     *                 $this->processDeposit($transaction);
     *             } elseif ($transaction->type === 'WITHDRAWAL') {
     *                 $this->processWithdrawal($transaction);
     *             }
     *
     *             // Mettre à jour le statut
     *             $transaction->update([
     *                 'status' => 'COMPLETED',
     *                 'processed_by_admin_id' => Auth::id(),
     *                 'processed_at' => now()
     *             ]);
     *
     *             dd($transaction->type);
     *
     *             // Envoyer un email de confirmation
     *             if ($transaction->type !== 'WITHDRAWAL') {
     *                 $this->sendTransactionEmail($transaction, 'confirmed');
     *             }
     *         });
     *
     *         $message = '';
     *         if ($transaction?->type === 'DEPOSIT') {
     *             $message = __('common.deposit_confirmed_successfully');
     *         } elseif ($transaction?->type === 'WITHDRAWAL') {
     *             $message = __('common.withdrawal_confirmed_successfully');
     *         } else {
     *             $message = __('common.transaction_confirmed_successfully');
     *         }
     *
     *         $this->dispatch('alert', [
     *             'type' => 'success',
     *             'message' => $message
     *         ]);
     *         $this->dispatch('transactionUpdated');
     *     } catch (\Exception $e) {
     *         $this->dispatch('alert', [
     *             'type' => 'error',
     *             'message' => $e->getMessage()
     *         ]);
     *     }
     * }
     *
     * public function cancelPendingTransaction($transactionId)
     * {
     *     if (!Auth::user()->is_admin) {
     *         $this->dispatch('alert', [
     *             'type' => 'error',
     *             'message' => __('common.unauthorized_access')
     *         ]);
     *         return;
     *     }
     *
     *     try {
     *         $transaction = Transaction::findOrFail($transactionId);
     *
     *         if ($transaction->status !== 'PENDING') {
     *             throw new \Exception(__('common.transaction_cannot_be_cancelled'));
     *         }
     *
     *         $transaction->update([
     *             'status' => 'CANCELLED',
     *             'processed_by_admin_id' => Auth::id(),
     *             'processed_at' => now()
     *         ]);
     *
     *         // Envoyer un email d'annulation
     *         if ($transaction->type !== 'WITHDRAWAL') {
     *             $this->sendTransactionEmail($transaction, 'cancelled');
     *         }
     *
     *         $this->dispatch('alert', [
     *             'type' => 'success',
     *             'message' => __('common.transaction_cancelled_successfully')
     *         ]);
     *
     *         $this->dispatch('transactionUpdated');
     *     } catch (\Exception $e) {
     *         Log::error('Transaction cancellation failed: ' . $e->getMessage());
     *         $this->dispatch('alert', [
     *             'type' => 'error',
     *             'message' => $e->getMessage()
     *         ]);
     *     }
     * }
     */

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
            // Ne pas envoyer d'email pour les retraits (WITHDRAWAL)
            if ($transaction->type === 'WITHDRAWAL') {
                return;
            }

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
                $emailTemplate = 'emails.transaction-confirmed';
                $emailSubject = 'common.transaction_confirmed_subject';  // Valeur par défaut
                $messageKey = 'transaction_confirmed_email_message';  // Valeur par défaut

                if ($transaction->type === 'DEPOSIT') {
                    $emailSubject = 'common.deposit_confirmed_email_subject';
                    $messageKey = 'deposit_confirmed_email_message';
                } elseif ($transaction->type === 'WITHDRAWAL') {
                    $emailSubject = 'common.withdrawal_confirmed_email_subject';
                    $messageKey = 'withdrawal_confirmed_email_message';
                } elseif ($transaction->type === 'TRANSFER_BANK') {
                    $emailSubject = 'transfers.bank_transfer_confirmed_subject';
                    $messageKey = 'bank_transfer_confirmed_message';
                } elseif ($transaction->type === 'TRANSFER_CRYPTO') {
                    $emailSubject = 'transfers.crypto_transfer_confirmed_subject';
                    $messageKey = 'crypto_transfer_confirmed_message';
                } elseif ($transaction->type === 'TRANSFER_EXTERNAL') {
                    $emailSubject = 'transfers.external_transfer_confirmed_subject';
                    $messageKey = 'external_transfer_confirmed_message';
                }

                $emailMessage = __('common.' . $messageKey, ['amount' => "{$amount} {$currency}"]);
                Mail::to($user->email)->send(new TransactionNotification(
                    __($emailSubject),
                    $emailMessage,
                    $user,
                    $transaction,
                    $amount,
                    $type === 'confirmed' ? 'emails.transaction-confirmed' : 'emails.transaction-cancelled',
                    $currency
                ));
            } elseif ($type === 'cancelled') {
                $emailSubject = 'common.transaction_cancelled_subject';  // Valeur par défaut
                $messageKey = 'transaction_cancelled_email_message';  // Valeur par défaut

                if ($transaction->type === 'DEPOSIT') {
                    $emailSubject = 'common.deposit_cancelled_email_subject';
                    $messageKey = 'deposit_cancelled_email_message';
                } elseif (in_array($transaction->type, ['TRANSFER_BANK', 'TRANSFER_CRYPTO', 'TRANSFER_EXTERNAL'])) {
                    $emailSubject = 'common.transfer_cancelled_email_subject';
                    $messageKey = 'transfer_cancelled_email_message';
                }

                $emailMessage = __('common.' . $messageKey, ['amount' => "{$amount} {$currency}"]);
                Mail::to($user->email)->send(new TransactionNotification(
                    __($emailSubject),
                    $emailMessage,
                    $user,
                    $transaction,
                    $amount,
                    $type === 'confirmed' ? 'emails.transaction-confirmed' : 'emails.transaction-cancelled',
                    $currency
                ));
            }
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas faire échouer la transaction
            Log::error(__('common.email_sending_error') . ': ' . $e->getMessage());
        }
    }

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
        $transaction = Transaction::find($transactionId);

        if ($transaction && $transaction->status === 'BLOCKED') {
            $this->selectedTransaction = Transaction::with([
                'user',
                'account.rib',
                'toAccount.rib.user',
                'toAccount.user',
                'blockedAtTransferStep.transferStepGroup.transferSteps',
                'transferStepCompletions'
            ])->find($transactionId);
        } else {
            $this->selectedTransaction = $transaction;
        }

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

        // Vérifier si c'est une transaction de transfert
        if (in_array($transaction->type, ['TRANSFER_BANK', 'TRANSFER_CRYPTO', 'TRANSFER_EXTERNAL'])) {
            $this->confirmTransferTransaction($transactionId);
        } else {
            $this->processConfirmTransaction($transactionId);
        }
    }

    /**
     * Confirmer une transaction de transfert spécifiquement
     */
    public function confirmTransferTransaction($transactionId)
    {
        if (!Auth::user()->is_admin) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.unauthorized_access')]);
            return;
        }

        try {
            $transaction = Transaction::find($transactionId);
            if (!$transaction || !$transaction->isPending()) {
                throw new \Exception(__('messages.invalid_or_processed_transaction'));
            }

            // Vérifier que c'est bien une transaction de transfert
            if (!in_array($transaction->type, ['TRANSFER_BANK', 'TRANSFER_CRYPTO', 'TRANSFER_EXTERNAL'])) {
                throw new \Exception(__('messages.not_a_transfer_transaction'));
            }

            // Vérifier le solde disponible
            if ($transaction->account_id) {
                $account = Account::findOrFail($transaction->account_id);
                if ($account->balance < $transaction->amount) {
                    throw new \Exception(__('messages.insufficient_balance_transfer'));
                }
            } elseif ($transaction->wallet_id) {
                $wallet = Wallet::findOrFail($transaction->wallet_id);
                if ($wallet->balance < $transaction->amount) {
                    throw new \Exception(__('messages.insufficient_balance_transfer'));
                }
            } else {
                throw new \Exception(__('messages.no_source_account_or_wallet'));
            }

            DB::transaction(function () use ($transaction) {
                // Effectuer le retrait du solde
                if ($transaction->account_id) {
                    $account = Account::findOrFail($transaction->account_id);
                    $account->decrement('balance', $transaction->amount);
                } elseif ($transaction->wallet_id) {
                    $wallet = Wallet::findOrFail($transaction->wallet_id);
                    $wallet->decrement('balance', $transaction->amount);
                }

                // Mettre à jour le statut de la transaction
                $transaction->update([
                    'status' => Transaction::STATUS_COMPLETED,
                    'processed_by_admin_id' => Auth::id(),
                    'processed_at' => now()
                ]);

                // Générer le bordereau PDF
                $receiptService = new TransactionReceiptService();
                $receiptPath = $receiptService->generateReceipt($transaction);
                
                // Sauvegarder le chemin du bordereau dans la transaction
                $transaction->update(['receipt_path' => $receiptPath]);

                // Envoyer l'email de confirmation avec le bordereau
                $this->sendTransactionEmailWithReceipt($transaction, $receiptPath);
            });

            $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.transfer_confirmed_successfully')]);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.transfer_confirmation_error', ['error' => $e->getMessage()])]);
        }

        $this->dispatch('action-completed');
    }

    public function processConfirmTransaction($transactionId)
    {
        try {
            $transaction = Transaction::find($transactionId);
            if (!$transaction || !$transaction->isPending()) {
                throw new \Exception(__('messages.invalid_or_processed_transaction'));
            }

            // Vérifier le solde avant le retrait
            if ($transaction->type === 'WITHDRAWAL') {
                if ($transaction->account_id) {
                    $account = Account::findOrFail($transaction->account_id);
                    if ($account->balance < $transaction->amount) {
                        throw new \Exception(__('common.insufficient_balance_withdrawal'));
                    }
                } elseif ($transaction->wallet_id) {
                    $wallet = Wallet::findOrFail($transaction->wallet_id);
                    if ($wallet->balance < $transaction->amount) {
                        throw new \Exception(__('common.insufficient_balance_withdrawal'));
                    }
                }
            }

            // Utiliser la méthode confirm du modèle (qui gère automatiquement la mise à jour du solde et l'envoi d'email)
            $transaction->confirm(Auth::id());

            // Générer le bordereau PDF
            $receiptService = new TransactionReceiptService();
            $receiptPath = $receiptService->generateReceipt($transaction);
            
            // Sauvegarder le chemin du bordereau dans la transaction
            $transaction->update(['receipt_path' => $receiptPath]);

            // Envoyer l'email avec le bordereau en pièce jointe
            $this->sendTransactionEmailWithReceipt($transaction, $receiptPath);

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

        // Vérifier si c'est une transaction de transfert
        if (in_array($transaction->type, ['TRANSFER_BANK', 'TRANSFER_CRYPTO', 'TRANSFER_EXTERNAL'])) {
            $this->cancelTransferTransaction($transactionId);
        } else {
            $this->processCancelTransaction($transactionId);
        }
    }

    /**
     * Annuler une transaction de transfert spécifiquement
     */
    public function cancelTransferTransaction($transactionId)
    {
        if (!Auth::user()->is_admin) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.unauthorized_access')]);
            return;
        }

        try {
            $transaction = Transaction::find($transactionId);
            if (!$transaction || !$transaction->isPending()) {
                throw new \Exception(__('messages.invalid_or_processed_transaction'));
            }

            // Vérifier que c'est bien une transaction de transfert
            if (!in_array($transaction->type, ['TRANSFER_BANK', 'TRANSFER_CRYPTO', 'TRANSFER_EXTERNAL'])) {
                throw new \Exception(__('messages.not_a_transfer_transaction'));
            }

            // Mettre à jour le statut de la transaction
            $transaction->update([
                'status' => Transaction::STATUS_CANCELLED,
                'processed_by_admin_id' => Auth::id(),
                'processed_at' => now()
            ]);

            // Envoyer l'email d'annulation
            $this->sendTransferCancellationEmail($transaction);

            $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.transfer_cancelled_successfully')]);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.transfer_cancellation_error', ['error' => $e->getMessage()])]);
        }

        $this->dispatch('action-completed');
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
     * Générer un reçu PDF pour une transaction de transfert
     */
    private function generateTransferReceipt($transaction)
    {
        try {
            // Récupérer les informations de la banque depuis la config
            $config = \App\Models\Config::first();

            $currency = $transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR'));
            $amount = number_format($transaction->amount, 2);

            // Données pour le PDF
            $data = [
                'transaction' => $transaction,
                'user' => $transaction->user,
                'config' => $config,
                'amount' => $amount,
                'currency' => $currency,
                'date' => $transaction->created_at->format('d/m/Y H:i'),
                'reference' => $transaction->reference ?: 'TR-' . $transaction->id
            ];

            // Créer le nom du fichier
            $filename = 'transfer_receipt_' . $transaction->id . '_' . time() . '.pdf';
            $filepath = storage_path('app/public/receipts/' . $filename);

            // Créer le répertoire s'il n'existe pas
            if (!file_exists(dirname($filepath))) {
                mkdir(dirname($filepath), 0755, true);
            }

            // Générer le contenu HTML du reçu
            $html = view('pdf.transfer-receipt', $data)->render();

            // Pour l'instant, on sauvegarde le HTML dans un fichier temporaire
            // Dans un vrai projet, on utiliserait une librairie comme DomPDF ou wkhtmltopdf
            file_put_contents($filepath . '.html', $html);

            // Stocker le chemin du reçu dans la transaction
            $transaction->update([
                'receipt_path' => 'receipts/' . $filename . '.html'
            ]);

            Log::info(__('messages.transfer_receipt_generated'), [
                'transaction_id' => $transaction->id,
                'receipt_path' => $filepath
            ]);
        } catch (\Exception $e) {
            Log::error(__('messages.transfer_receipt_generation_error') . ': ' . $e->getMessage());
        }
    }

    /**
     * Envoyer un email de confirmation de transfert
     */
    private function sendTransferConfirmationEmail($transaction)
    {
        try {
            $user = $transaction->user;
            $currency = $transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR'));
            $amount = number_format($transaction->amount, 2);
            $amountWithCurrency = $amount . ' ' . $currency;

            // Déterminer le sujet selon le type de transfert
            $emailSubject = '';
            $messageKey = '';

            switch ($transaction->type) {
                case 'TRANSFER_BANK':
                    $emailSubject = __('messages.bank_transfer_confirmed_subject');
                    $messageKey = 'bank_transfer_confirmed_message';
                    break;
                case 'TRANSFER_CRYPTO':
                    $emailSubject = __('messages.crypto_transfer_confirmed_subject');
                    $messageKey = 'crypto_transfer_confirmed_message';
                    break;
                case 'TRANSFER_EXTERNAL':
                    $emailSubject = __('messages.external_transfer_confirmed_subject');
                    $messageKey = 'external_transfer_confirmed_message';
                    break;
                default:
                    $emailSubject = __('messages.transfer_confirmed_subject');
                    $messageKey = 'transfer_confirmed_message';
            }

            $emailMessage = __('messages.' . $messageKey, ['amount' => $amountWithCurrency]);

            // Créer le lien de téléchargement du reçu
            $receiptUrl = '';
            if ($transaction->receipt_path) {
                $receiptUrl = url('storage/' . $transaction->receipt_path);
            }

            Mail::to($user->email)->send(new TransactionNotification(
                $emailSubject,
                $emailMessage,
                $user,
                $transaction,
                $amount,
                'emails.transaction-confirmed',
                $currency,
                $receiptUrl
            ));
        } catch (\Exception $e) {
            Log::error(__('messages.transfer_confirmation_email_error') . ': ' . $e->getMessage());
        }
    }

    /**
     * Envoyer un email d'annulation de transfert
     */
    private function sendTransferCancellationEmail($transaction)
    {
        try {
            $user = $transaction->user;
            $currency = $transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR'));
            $amount = number_format($transaction->amount, 2);
            $amountWithCurrency = $amount . ' ' . $currency;

            // Déterminer le sujet selon le type de transfert
            $emailSubject = '';
            $messageKey = '';

            switch ($transaction->type) {
                case 'TRANSFER_BANK':
                    $emailSubject = __('messages.bank_transfer_cancelled_subject');
                    $messageKey = 'bank_transfer_cancelled_message';
                    break;
                case 'TRANSFER_CRYPTO':
                    $emailSubject = __('messages.crypto_transfer_cancelled_subject');
                    $messageKey = 'crypto_transfer_cancelled_message';
                    break;
                case 'TRANSFER_EXTERNAL':
                    $emailSubject = __('messages.external_transfer_cancelled_subject');
                    $messageKey = 'external_transfer_cancelled_message';
                    break;
                default:
                    $emailSubject = __('messages.transfer_cancelled_subject');
                    $messageKey = 'transfer_cancelled_message';
            }

            $emailMessage = __('messages.' . $messageKey, ['amount' => $amountWithCurrency]);

            Mail::to($user->email)->send(new TransactionNotification(
                $emailSubject,
                $emailMessage,
                $user,
                $transaction,
                $amount,
                'emails.transaction-cancelled',
                $currency
            ));
        } catch (\Exception $e) {
            Log::error(__('messages.transfer_cancellation_email_error') . ': ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une transaction COMPLETED et reverser les opérations
     */
    public function deleteTransaction($transactionId)
    {
        if (!Auth::user()->is_admin) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.unauthorized_access')]);
            return;
        }

        try {
            $transaction = Transaction::find($transactionId);
            if (!$transaction) {
                throw new \Exception(__('messages.transaction_not_found'));
            }

            if ($transaction->status !== 'COMPLETED') {
                throw new \Exception(__('messages.only_completed_transactions_can_be_deleted'));
            }

            DB::transaction(function () use ($transaction) {
                // Reverser les opérations selon le type de transaction
                $this->reverseTransactionOperations($transaction);

                // Supprimer la transaction
                $transaction->delete();
            });

            $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.transaction_deleted_successfully')]);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.transaction_deletion_error', ['error' => $e->getMessage()])]);
        }

        $this->dispatch('action-completed');
    }

    /**
     * Reverser les opérations d'une transaction
     */
    private function reverseTransactionOperations($transaction)
    {
        switch ($transaction->type) {
            case 'DEPOSIT':
                // Pour un dépôt, on retire le montant du solde
                $this->reverseDeposit($transaction);
                break;

            case 'WITHDRAWAL':
                // Pour un retrait, on remet le montant dans le solde
                $this->reverseWithdrawal($transaction);
                break;

            case 'TRANSFER_BANK':
            case 'TRANSFER_CRYPTO':
            case 'TRANSFER_EXTERNAL':
                // Pour un transfert, on remet le montant dans le compte/wallet source
                $this->reverseTransfer($transaction);
                break;

            default:
                Log::warning('Type de transaction non géré pour la suppression: ' . $transaction->type);
        }
    }

    /**
     * Reverser un dépôt (retirer le montant du solde)
     */
    private function reverseDeposit($transaction)
    {
        if ($transaction->account_id) {
            $account = Account::findOrFail($transaction->account_id);
            if ($account->balance < $transaction->amount) {
                throw new \Exception(__('messages.insufficient_balance_to_reverse_deposit'));
            }
            $account->decrement('balance', $transaction->amount);
        } elseif ($transaction->wallet_id) {
            $wallet = Wallet::findOrFail($transaction->wallet_id);
            if ($wallet->balance < $transaction->amount) {
                throw new \Exception(__('messages.insufficient_balance_to_reverse_deposit'));
            }
            $wallet->decrement('balance', $transaction->amount);
        }
    }

    /**
     * Reverser un retrait (remettre le montant dans le solde)
     */
    private function reverseWithdrawal($transaction)
    {
        if ($transaction->account_id) {
            $account = Account::findOrFail($transaction->account_id);
            $account->increment('balance', $transaction->amount);
        } elseif ($transaction->wallet_id) {
            $wallet = Wallet::findOrFail($transaction->wallet_id);
            $wallet->increment('balance', $transaction->amount);
        }
    }

    /**
     * Reverser un transfert (remettre le montant dans le compte/wallet source)
     */
    private function reverseTransfer($transaction)
    {
        if ($transaction->account_id) {
            $account = Account::findOrFail($transaction->account_id);
            $account->increment('balance', $transaction->amount);
        } elseif ($transaction->wallet_id) {
            $wallet = Wallet::findOrFail($transaction->wallet_id);
            $wallet->increment('balance', $transaction->amount);
        }
    }

    /**
     * Envoyer un email avec le bordereau PDF en pièce jointe
     */
    private function sendTransactionEmailWithReceipt($transaction, $receiptPath)
    {
        try {
            $receiptFullPath = storage_path('app/public/' . $receiptPath);
            
            // Préparer les données pour l'email
            $user = $transaction->user;
            $currency = $transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR'));
            $amount = number_format($transaction->amount, 2);
            $amountWithCurrency = $amount . ' ' . $currency;
            $emailSubject = __('common.transaction_confirmed_email_subject');
            $emailMessage = __('common.transaction_confirmed_email_message', ['amount' => $amountWithCurrency]);
            $viewName = 'emails.transaction-confirmed';
            
            if (file_exists($receiptFullPath)) {
                Mail::to($user->email)->send(
                    new TransactionNotification(
                        $emailSubject,
                        $emailMessage,
                        $user,
                        $transaction,
                        $amount,
                        $viewName,
                        $currency,
                        $receiptFullPath
                    )
                );
            } else {
                // Fallback: envoyer l'email sans pièce jointe
                Mail::to($user->email)->send(
                    new TransactionNotification(
                        $emailSubject,
                        $emailMessage,
                        $user,
                        $transaction,
                        $amount,
                        $viewName,
                        $currency
                    )
                );
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email avec bordereau: ' . $e->getMessage());
        }
    }

    /**
     * Télécharger le bordereau PDF d'une transaction
     */
    public function downloadReceipt($transactionId)
    {
        try {
            $transaction = Transaction::findOrFail($transactionId);
            
            if (!$transaction->receipt_path) {
                $this->dispatch('alert', [
                    'type' => 'error',
                    'message' => __('messages.receipt_not_available')
                ]);
                return;
            }
            
            $receiptService = new TransactionReceiptService();
            $fullPath = $receiptService->getReceiptPath($transaction->receipt_path);
            
            if (!$receiptService->receiptExists($transaction->receipt_path)) {
                $this->dispatch('alert', [
                    'type' => 'error',
                    'message' => __('messages.receipt_file_not_found')
                ]);
                return;
            }
            
            return response()->download($fullPath, 'bordereau_transaction_' . $transaction->id . '.pdf');
        } catch (\Exception $e) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => __('messages.receipt_download_error')
            ]);
        }
    }

    public function render()
    {
        return view('livewire.transaction-list', [
            'transactions' => $this->transactions
        ]);
    }
}

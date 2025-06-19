<?php

namespace App\Livewire;

use App\Mail\TransactionNotification;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

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

        $this->processConfirmTransaction($transactionId);
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

        $this->processCancelTransaction($transactionId);
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

    public function render()
    {
        return view('livewire.transaction-list', [
            'transactions' => $this->transactions
        ]);
    }
}

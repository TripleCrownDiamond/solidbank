<?php

namespace App\Livewire;

use App\Mail\AccountStatusNotification;
use App\Mail\CardRequestNotification;
use App\Models\Account;
use App\Models\Card;
use App\Models\Config;
use App\Models\Cryptocurrency;
use App\Models\Rib;
use App\Models\TransferStepGroup;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class UserDetailManagement extends Component
{
    public $user;
    public $showCardDetails = [];
    public $showWalletDetails = [];
    public $showSuspensionModal = false;
    public $suspensionReason = '';
    public $suspensionInstructions = '';
    public $showTransferGroupModal = false;
    public $selectedAccountId = null;
    public $selectedWalletId = null;
    public $selectedTransferGroupId = null;
    public $transferGroupType = 'account';  // 'account' or 'wallet'
    // Card management properties
    public $showAddCardModal = false;
    public $cardType = 'VISA';
    public $cardHolderName = '';
    public $selectedAccountForCard = null;
    public $selectedCardRequest = null;
    // Wallet management properties
    public $showAddWalletModal = false;
    public $selectedCryptocurrency = null;
    public $loadingAction = null;
    
    protected $listeners = ['execute-method' => 'executeMethod'];

    public function mount(User $user)
    {
        // Vérifier que l'utilisateur connecté est un administrateur
        if (!Auth::user()->is_admin) {
            abort(403, 'Accès non autorisé');
        }

        $this->user = $user;
    }

    public function executeMethod($method, $params = [])
    {
        if (method_exists($this, $method)) {
            call_user_func_array([$this, $method], $params);
        }
    }
    
    public function render()
    {
        $transferGroups = TransferStepGroup::where('is_active', true)->get();
        $cryptocurrencies = Cryptocurrency::getGroupedBySymbol();

        // Charger les relations transferStepGroups pour les comptes et portefeuilles
        $this->user->load(['accounts.transferStepGroups', 'wallets.transferStepGroups']);

        return view('livewire.user-detail-management', compact('transferGroups', 'cryptocurrencies'));
    }

    public function toggleCardDetails($cardId)
    {
        if (isset($this->showCardDetails[$cardId])) {
            unset($this->showCardDetails[$cardId]);
        } else {
            $this->showCardDetails[$cardId] = true;
        }
    }

    public function toggleWalletDetails($walletId)
    {
        if (isset($this->showWalletDetails[$walletId])) {
            unset($this->showWalletDetails[$walletId]);
        } else {
            $this->showWalletDetails[$walletId] = true;
        }
    }

    public function copyToClipboard($text)
    {
        $this->dispatch('copy-to-clipboard', $text);
    }

    public function activateUser()
    {
        if ($this->user->is_admin) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.cannot_activate_admin')]);
            return;
        }

        // Activer tous les comptes de l'utilisateur
        $this->user->accounts()->update(['status' => 'ACTIVE']);

        // Générer les RIB pour les comptes qui n'en ont pas
        foreach ($this->user->accounts as $account) {
            if (!$account->rib) {
                $this->generateRib($account);
            }

            // Send email notification for each activated account
            try {
                Mail::to($this->user->email)->send(new AccountStatusNotification($this->user, $account, 'activated'));
            } catch (\Exception $e) {
                // Log email error but don't fail the operation
                Log::error('Failed to send account activation email: ' . $e->getMessage());
            }
        }

        // Rafraîchir les données
        $this->user = $this->user->fresh(['accounts']);
        
        // Dispatch success message
        $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.user_activated_successfully')]);
    }

    public function suspendUser()
    {
        $this->showSuspensionModal = true;
        $this->suspensionReason = '';
        $this->suspensionInstructions = '';
    }

    public function confirmSuspension()
    {
        $this->validate([
            'suspensionReason' => 'required|string|max:500',
            'suspensionInstructions' => 'nullable|string|max:1000',
        ]);

        if ($this->user->is_admin) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.cannot_suspend_admin')]);
            return;
        }

        $this->user->accounts()->update([
            'status' => 'SUSPENDED',
            'suspension_reason' => $this->suspensionReason,
            'suspension_instructions' => $this->suspensionInstructions,
        ]);

        // Send email notification for each suspended account
        foreach ($this->user->accounts as $account) {
            try {
                Mail::to($this->user->email)->send(new AccountStatusNotification($this->user, $account, 'suspended'));
            } catch (\Exception $e) {
                Log::error('Failed to send account suspension email: ' . $e->getMessage());
            }
        }

        // Rafraîchir les données
        $this->user = $this->user->fresh(['accounts']);
        $this->cancelSuspension();
        
        // Dispatch success message
        $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.user_suspended_successfully')]);
    }

    public function cancelSuspension()
    {
        $this->showSuspensionModal = false;
        $this->suspensionReason = '';
        $this->suspensionInstructions = '';
    }

    public function deleteUser()
    {
        if ($this->user->is_admin) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.cannot_delete_admin')]);
            return;
        }

        if ($this->user->id === Auth::id()) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.cannot_delete_yourself')]);
            return;
        }

        try {
            DB::transaction(function () {
                // Send email notification before deletion
                foreach ($this->user->accounts as $account) {
                    try {
                        Mail::to($this->user->email)->send(new AccountStatusNotification($this->user, $account, 'deleted'));
                    } catch (\Exception $e) {
                        Log::error('Failed to send account deletion email: ' . $e->getMessage());
                    }
                }

                // Delete user (related models will be deleted automatically via model boot method)
                $this->user->delete();
            });

            // Dispatch success message before redirect
            session()->flash('alert', ['type' => 'success', 'message' => __('messages.user_deleted_successfully')]);
            
            $locale = app()->getLocale();
            return redirect()->route('admin.users', ['locale' => $locale]);
        } catch (\Exception $e) {
            Log::error('User deletion failed: ' . $e->getMessage());
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.user_deletion_failed')]);
        }
    }

    public function openTransferGroupModal($type, $id)
    {
        $this->transferGroupType = $type;
        if ($type === 'account') {
            $this->selectedAccountId = $id;
            $this->selectedWalletId = null;
        } else {
            $this->selectedWalletId = $id;
            $this->selectedAccountId = null;
        }
        $this->selectedTransferGroupId = null;
        $this->showTransferGroupModal = true;
    }

    public function updatedTransferGroupType($value)
    {
        // Simuler un petit délai pour l'effet de loading
        usleep(300000);  // 300ms

        // Réinitialiser les sélections quand le type change
        if ($value === 'account') {
            $this->selectedWalletId = null;
        } else {
            $this->selectedAccountId = null;
        }
        $this->selectedTransferGroupId = null;
    }

    public function applyTransferGroup()
    {
        $this->validate([
            'selectedTransferGroupId' => 'required|exists:transfer_step_groups,id',
        ]);

        $transferGroup = TransferStepGroup::find($this->selectedTransferGroupId);

        if ($this->transferGroupType === 'account' && $this->selectedAccountId) {
            $account = Account::find($this->selectedAccountId);
            if ($account) {
                $account->transferStepGroups()->sync([$this->selectedTransferGroupId]);
                $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.transfer_group_applied_to_account')]);
            }
        } elseif ($this->transferGroupType === 'wallet' && $this->selectedWalletId) {
            $wallet = Wallet::find($this->selectedWalletId);
            if ($wallet) {
                $wallet->transferStepGroups()->sync([$this->selectedTransferGroupId]);
                $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.transfer_group_applied_to_wallet')]);
            }
        }

        $this->closeTransferGroupModal();
    }

    public function removeTransferGroup()
    {
        if ($this->transferGroupType === 'account' && $this->selectedAccountId) {
            $account = Account::find($this->selectedAccountId);
            if ($account) {
                $account->transferStepGroups()->detach();
                $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.transfer_group_removed_from_account')]);
            }
        } elseif ($this->transferGroupType === 'wallet' && $this->selectedWalletId) {
            $wallet = Wallet::find($this->selectedWalletId);
            if ($wallet) {
                $wallet->transferStepGroups()->detach();
                $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.transfer_group_removed_from_wallet')]);
            }
        }

        $this->closeTransferGroupModal();
    }

    public function removeSpecificTransferGroup($type, $id, $groupId)
    {
        if ($type === 'account') {
            $account = Account::find($id);
            if ($account) {
                $account->transferStepGroups()->detach($groupId);
                $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.transfer_group_removed_from_account')]);
            }
        } elseif ($type === 'wallet') {
            $wallet = Wallet::find($id);
            if ($wallet) {
                $wallet->transferStepGroups()->detach($groupId);
                $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.transfer_group_removed_from_wallet')]);
            }
        }

        // Recharger les relations pour mettre à jour l'affichage
        $this->user->load(['accounts.transferStepGroups', 'wallets.transferStepGroups']);
    }

    public function closeTransferGroupModal()
    {
        $this->showTransferGroupModal = false;
        $this->selectedAccountId = null;
        $this->selectedWalletId = null;
        $this->selectedTransferGroupId = null;
        $this->transferGroupType = 'account';
    }

    private function generateRib($account)
    {
        $config = \App\Models\Config::first();

        if (!$config) {
            throw new \Exception(__('messages.bank_config_not_found'));
        }

        // Generate IBAN
        $iban = $config->iban_prefix
            . $config->iban_bank_code
            . $config->iban_branch_code
            . str_pad($account->account_number, $config->iban_account_length, '0', STR_PAD_LEFT);

        // Generate SWIFT (BIC)
        $swift = $config->bank_swift;

        // Create RIB
        \App\Models\Rib::create([
            'account_id' => $account->id,
            'iban' => $iban,
            'swift' => $swift,
            'bank_name' => $config->bank_name,
        ]);
    }

    public function openAddCardModal()
    {
        $this->showAddCardModal = true;
        $this->cardType = 'VISA';
        $this->cardHolderName = $this->user->first_name . ' ' . $this->user->last_name;
        $this->selectedAccountForCard = $this->user->accounts->first()?->id;
    }

    public function closeAddCardModal()
    {
        $this->showAddCardModal = false;
        $this->cardType = 'VISA';
        $this->cardHolderName = '';
        $this->selectedAccountForCard = null;
        $this->selectedCardRequest = null;
    }

    public function addCard()
    {
        // Vérifier qu'une demande de carte est sélectionnée
        if (!$this->selectedCardRequest) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.please_select_card_request')]);
            return;
        }

        $cardRequest = \App\Models\CardRequest::find($this->selectedCardRequest);
        if (!$cardRequest || $cardRequest->account->user_id !== $this->user->id || $cardRequest->status !== 'PENDING') {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.invalid_card_request')]);
            return;
        }

        // Utiliser les informations de la demande
        $this->cardType = $cardRequest->card_type;
        $this->selectedAccountForCard = $cardRequest->account_id;
        $this->cardHolderName = $this->user->first_name . ' ' . $this->user->last_name;

        // Vérifier que le compte appartient bien à l'utilisateur
        $account = $this->user->accounts()->find($this->selectedAccountForCard);
        if (!$account) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.invalid_account')]);
            return;
        }

        try {
            // Générer un numéro de carte unique
            $cardNumber = $this->generateCardNumber($this->cardType);

            // Générer une date d'expiration (5 ans à partir de maintenant)
            $expiryDate = now()->addYears(5);

            // Générer un CVV
            $cvv = str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);

            $card = \App\Models\Card::create([
                'user_id' => $this->user->id,
                'account_id' => $this->selectedAccountForCard,
                'type' => $this->cardType,
                'card_number' => $cardNumber,
                'expiry_month' => $expiryDate->format('m'),
                'expiry_year' => $expiryDate->format('Y'),
                'cvv' => $cvv,
                'card_holder_name' => $this->cardHolderName,
                'balance' => 0,
                'currency' => 'EUR',
            ]);

            // Si une demande de carte était sélectionnée, la marquer comme approuvée
            if ($this->selectedCardRequest) {
                $cardRequest = \App\Models\CardRequest::find($this->selectedCardRequest);
                if ($cardRequest) {
                    $cardRequest->update([
                        'status' => 'APPROVED',
                        'processed_by' => Auth::id(),
                        'processed_at' => now()
                    ]);
                    
                    // Send email notification to user about card request approval
                    try {
                        Mail::to($this->user->email)->send(new AccountStatusNotification($this->user, $cardRequest->account, 'card_request_approved'));
                        Log::info('Card request approval notification sent to user', ['email' => $this->user->email]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send card request approval notification: ' . $e->getMessage());
                    }
                }
            }

            // Send email notification for new card
            try {
                $account = $this->user->accounts()->find($this->selectedAccountForCard);
                if ($account) {
                    Mail::to($this->user->email)->send(new AccountStatusNotification($this->user, $account, 'card_added'));
                }
            } catch (\Exception $e) {
                // Log email error but don't fail the operation
                Log::error('Failed to send card creation email: ' . $e->getMessage());
            }

            $this->user->refresh();
            $this->closeAddCardModal();
            $this->dispatch('alert', ['type' => 'success', 'message' => $this->selectedCardRequest ? __('messages.card_added_and_request_processed') : __('messages.card_added_successfully')]);
        } catch (\Exception $e) {
            Log::error('Card creation failed: ' . $e->getMessage());
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.card_creation_error')]);
        }
    }

    public function deleteCard($cardId)
    {
        $this->processDeleteCard($cardId);
    }
    
    public function processDeleteCard($cardId)
    {
        $this->loadingAction = 'delete_card_' . $cardId;
        
        try {
            $card = \App\Models\Card::findOrFail($cardId);

            // Vérifier que la carte appartient à l'utilisateur actuel
            if ($card->user_id !== $this->user->id) {
                session()->flash('error', __('messages.unauthorized_access'));
                return;
            }

            // Send email notification before deletion
            try {
                $account = $this->user->accounts()->find($card->account_id);
                if ($account) {
                    Mail::to($this->user->email)->send(new AccountStatusNotification($this->user, $account, 'card_deleted'));
                }
            } catch (\Exception $e) {
                // Log email error but don't fail the operation
                Log::error('Failed to send card deletion email: ' . $e->getMessage());
            }

            $card->delete();

            session()->flash('success', __('messages.card_deleted_successfully'));

            // Rafraîchir la vue
            $this->user = $this->user->fresh();
        } catch (\Exception $e) {
            Log::error('Card deletion failed: ' . $e->getMessage());
            session()->flash('error', __('messages.card_deletion_error'));
        }
        
        $this->loadingAction = null;
        $this->dispatch('action-completed');
    }

    // Wallet management methods
    public function openAddWalletModal()
    {
        $this->showAddWalletModal = true;
        $this->resetWalletForm();
    }

    public function closeAddWalletModal()
    {
        $this->showAddWalletModal = false;
        $this->resetWalletForm();
    }

    private function resetWalletForm()
    {
        $this->selectedCryptocurrency = null;
    }

    public function addWallet()
    {
        $this->validate([
            'selectedCryptocurrency' => 'required|exists:cryptocurrencies,id',
        ]);

        try {
            // Get the selected cryptocurrency
            $cryptocurrency = Cryptocurrency::find($this->selectedCryptocurrency);

            // Check if wallet already exists for this user and cryptocurrency
            $existingWallet = Wallet::where('user_id', $this->user->id)
                ->where('cryptocurrency_id', $this->selectedCryptocurrency)
                ->first();

            if ($existingWallet) {
                $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.wallet_already_exists')]);
                return;
            }

            // Create the wallet (address will be generated automatically)
            Wallet::create([
                'user_id' => $this->user->id,
                'cryptocurrency_id' => $this->selectedCryptocurrency,
                'coin' => $cryptocurrency->symbol,
                'network' => $cryptocurrency->network,
                'balance' => 0.0,
            ]);

            $this->closeAddWalletModal();
            $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.wallet_added_successfully')]);

            // Send email notification
            Mail::to($this->user->email)->send(new AccountStatusNotification($this->user, $this->user->accounts->first(), 'wallet_added'));

            // Refresh user data
            $this->user = $this->user->fresh();
        } catch (\Exception $e) {
            Log::error('Wallet creation failed: ' . $e->getMessage());
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.wallet_creation_error')]);
        }
    }

    public function deleteWallet($walletId)
    {
        $this->processDeleteWallet($walletId);
    }
    
    public function processDeleteWallet($walletId)
    {
        $this->loadingAction = 'delete_wallet_' . $walletId;
        
        try {
            $wallet = Wallet::where('id', $walletId)
                ->where('user_id', $this->user->id)
                ->first();

            if (!$wallet) {
                session()->flash('error', __('messages.wallet_not_found'));
                return;
            }
            
            // Vérifier que le solde est à zéro
            if ($wallet->balance > 0) {
                session()->flash('error', __('messages.cannot_delete_wallet_with_balance'));
                return;
            }

            $wallet->delete();

            session()->flash('success', __('messages.wallet_deleted_successfully'));

            // Send email notification
            Mail::to($this->user->email)->send(new AccountStatusNotification($this->user, $this->user->accounts->first(), 'wallet_deleted'));

            // Refresh user data
            $this->user = $this->user->fresh();

            // Remove from visible details if it was visible
            unset($this->showWalletDetails[$walletId]);
        } catch (\Exception $e) {
            Log::error('Wallet deletion failed: ' . $e->getMessage());
            session()->flash('error', __('messages.wallet_deletion_error'));
        }
        
        $this->loadingAction = null;
        $this->dispatch('action-completed');
    }

    public function deleteCardRequest($requestId)
    {
        $this->processDeleteCardRequest($requestId);
    }
    
    public function processDeleteCardRequest($requestId)
    {
        $this->loadingAction = 'delete_request_' . $requestId;
        
        try {
            $cardRequest = \App\Models\CardRequest::find($requestId);

            if (!$cardRequest) {
                session()->flash('error', __('messages.card_request_not_found'));
                return;
            }

            // Vérifier que la demande appartient bien à cet utilisateur
            $userAccountIds = $this->user->accounts->pluck('id')->toArray();
            if (!in_array($cardRequest->account_id, $userAccountIds)) {
                session()->flash('error', __('messages.unauthorized_access'));
                return;
            }

            // Vérifier que la demande est encore en attente
            if ($cardRequest->status !== 'PENDING') {
                session()->flash('error', __('messages.only_pending_requests_can_be_deleted'));
                return;
            }

            // Send email notification to company about cancellation
            try {
                $config = Config::first();
                if ($config && $config->bank_email) {
                    Mail::to($config->bank_email)->send(new CardRequestNotification($this->user, $cardRequest, 'request_cancelled'));
                    Log::info('Card request cancellation notification sent to company', ['email' => $config->bank_email]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send card request cancellation notification: ' . $e->getMessage());
            }

            // Send email notification to user about cancellation by admin
            try {
                Mail::to($this->user->email)->send(new CardRequestNotification($this->user, $cardRequest, 'request_cancelled_by_admin'));
                Log::info('Card request cancellation by admin notification sent to user', ['email' => $this->user->email]);
            } catch (\Exception $e) {
                Log::error('Failed to send card request cancellation by admin notification to user: ' . $e->getMessage());
            }

            $cardRequest->delete();

            session()->flash('success', __('messages.card_request_deleted_successfully'));

            // Refresh user data
            $this->user = $this->user->fresh();
        } catch (\Exception $e) {
            Log::error('Card request deletion failed: ' . $e->getMessage());
            session()->flash('error', __('messages.card_request_deletion_error'));
        }
        
        $this->loadingAction = null;
        $this->dispatch('action-completed');
    }

    private function generateCardNumber($type)
    {
        $prefix = match ($type) {
            'VISA' => '4',
            'MASTERCARD' => '5',
            'AMERICAN_EXPRESS' => '3',
            default => '4'
        };

        $length = $type === 'AMERICAN_EXPRESS' ? 15 : 16;
        $number = $prefix;

        for ($i = 1; $i < $length; $i++) {
            $number .= rand(0, 9);
        }

        return $number;
    }
}

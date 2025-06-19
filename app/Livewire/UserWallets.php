<?php

namespace App\Livewire;

use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserWallets extends Component
{
    public $dashboardView = false;
    public $maxWallets;
    public $user;
    public $adminView = false;
    public $loadingAction = null;
    public $showWalletDetails = [];

    protected $listeners = ['execute-method' => 'executeMethod'];

    public function mount($user = null, $dashboardView = false, $maxWallets = null, $adminView = false)
    {
        $this->user = $user ?: Auth::user();
        $this->dashboardView = $dashboardView;
        $this->maxWallets = $maxWallets;
        $this->adminView = $adminView;
    }

    public function render()
    {
        $user = $this->user;
        $walletsQuery = $user->wallets();

        if ($this->dashboardView && $this->maxWallets) {
            $wallets = $walletsQuery->latest()->take($this->maxWallets)->get();
        } else {
            $wallets = $walletsQuery->get();
        }

        $brandColor = config('app.brand_color', 'blue');

        return view('livewire.user-wallets', compact('wallets', 'brandColor'));
    }

    public function toggleWalletDetails($walletId)
    {
        if (isset($this->showWalletDetails[$walletId])) {
            unset($this->showWalletDetails[$walletId]);
        } else {
            $this->showWalletDetails[$walletId] = true;
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
            $wallet = Wallet::findOrFail($walletId);

            // Vérifier que le portefeuille appartient à l'utilisateur connecté
            if ($wallet->user_id !== Auth::id()) {
                $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.unauthorized_wallet_deletion')]);
                return;
            }

            // Vérifier que le solde est à zéro
            if ($wallet->balance > 0) {
                $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.cannot_delete_wallet_with_balance')]);
                return;
            }

            $wallet->delete();

            $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.wallet_deleted_successfully')]);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.wallet_deletion_error')]);
        }

        $this->loadingAction = null;
        $this->dispatch('action-completed');
    }

    public function executeMethod($method, $params = [])
    {
        if (method_exists($this, $method)) {
            call_user_func_array([$this, $method], $params);
        }
    }
}

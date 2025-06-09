<?php

namespace App\Livewire;

use App\Mail\AccountStatusNotification;
use App\Models\Account;
use App\Models\Config;
use App\Models\Rib;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class LatestInactiveAccounts extends Component
{
    use WithPagination;

    public $loadingAction = null;
    public $showSuspensionModal = false;
    public $suspensionAccountId = null;
    public $suspensionReason = '';
    public $suspensionInstructions = '';

    protected $listeners = ['execute-method' => 'executeMethod'];

    public function render()
    {
        // Force fresh data by clearing query cache and using fresh connection
        DB::purge();
        DB::reconnect();

        // Get only the first inactive account for each user with fresh data
        // Limited to the latest 20 inactive accounts, paginated by 10 (2 pages)
        $inactiveAccountIds = Account::where('status', 'INACTIVE')
            ->whereIn('id', function ($query) {
                $query
                    ->select(DB::raw('MIN(id)'))
                    ->from('accounts')
                    ->where('status', 'INACTIVE')
                    ->groupBy('user_id');
            })
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->pluck('id');

        $inactiveAccounts = Account::whereIn('id', $inactiveAccountIds)
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'email', 'created_at');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.latest-inactive-accounts', [
            'inactiveAccounts' => $inactiveAccounts,
        ]);
    }

    public function activateAccount($accountId)
    {
        $this->performActivateAccount($accountId);
    }

    public function executeMethod($method, $params = [])
    {
        if (method_exists($this, $method)) {
            call_user_func_array([$this, $method], $params);
        }
    }

    public function performActivateAccount($accountId)
    {
        $this->loadingAction = 'activate_' . $accountId;

        try {
            DB::transaction(function () use ($accountId) {
                $account = Account::lockForUpdate()->find($accountId);
                if (!$account) {
                    throw new \Exception(__('messages.account_not_found'));
                }

                $account->status = 'ACTIVE';
                $account->touch();  // Update timestamp to force refresh
                if (!$account->save()) {
                    throw new \Exception(__('messages.failed_to_save_account'));
                }

                // Send email notification with fresh account data
                try {
                    $freshAccount = Account::with('user')->find($accountId);
                    Mail::to($freshAccount->user->email)->send(new AccountStatusNotification($freshAccount->user, $freshAccount, 'activated'));
                } catch (\Exception $e) {
                    Log::error(__('messages.failed_to_send_activation_email') . ': ' . $e->getMessage());
                }

                // Check if RIB already exists for this account
                if (!\App\Models\Rib::where('account_id', $account->id)->exists()) {
                    $this->generateRib($account);
                }
            });

            // Use AlertManager instead of session flash
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => __('common.account_activated_successfully'),
                'dismissible' => true
            ]);

            // Force refresh without page reload
            $this->resetPage();
        } catch (\Exception $e) {
            Log::error('Account activation failed: ' . $e->getMessage());
            if (str_contains($e->getMessage(), 'RIB')) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => __('common.rib_generation_failed'),
                    'dismissible' => true
                ]);
            } else {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => __('common.account_activation_failed'),
                    'dismissible' => true
                ]);
            }
        }

        $this->loadingAction = null;
        $this->dispatch('action-completed');
    }

    private function generateRib(Account $account)
    {
        $config = \App\Models\Config::first();  // Assuming a single config entry

        if (!$config) {
            throw new \Exception(__('common.bank_config_not_found'));
        }

        // Generate IBAN
        $iban = $config->iban_prefix
            . $config->iban_bank_code
            . $config->iban_branch_code
            . str_pad($account->account_number, $config->iban_account_length, '0', STR_PAD_LEFT);

        // In a real-world scenario, you'd calculate the IBAN checksum here.
        // For this example, we'll use a placeholder or a simplified approach.
        // A full IBAN generation involves modulo 97-10 calculation.
        // For simplicity, let's assume the prefix and account number form the IBAN for now.
        // Example: FR76307590000100000000000

        // Generate SWIFT (BIC)
        $swift = $config->bank_swift;

        // Create RIB entry
        \App\Models\Rib::create([
            'account_id' => $account->id,
            'iban' => $iban,
            'swift' => $swift,
            'bank_name' => $config->bank_name,
        ]);
    }

    public function suspendAccount($accountId)
    {
        $this->showSuspensionForm($accountId);
    }

    public function showSuspensionForm($accountId)
    {
        $this->suspensionAccountId = $accountId;
        $this->suspensionReason = '';
        $this->suspensionInstructions = '';
        $this->showSuspensionModal = true;
    }

    public function confirmSuspension()
    {
        $this->validate([
            'suspensionReason' => 'required|string|max:1000',
            'suspensionInstructions' => 'nullable|string|max:2000',
        ]);

        $this->loadingAction = 'suspend_' . $this->suspensionAccountId;
        $this->performSuspendAccount($this->suspensionAccountId);
        $this->loadingAction = null;
    }

    public function cancelSuspension()
    {
        $this->showSuspensionModal = false;
        $this->suspensionAccountId = null;
        $this->suspensionReason = '';
        $this->suspensionInstructions = '';
    }

    public function performSuspendAccount($accountId)
    {
        try {
            DB::transaction(function () use ($accountId) {
                $account = Account::lockForUpdate()->find($accountId);
                if (!$account) {
                    throw new \Exception(__('messages.account_not_found'));
                }

                // Store current account data for email before update
                $currentAccount = $account->replicate();
                $currentUser = $account->user;

                $account->status = 'SUSPENDED';
                $account->suspension_reason = $this->suspensionReason;
                $account->suspension_instructions = $this->suspensionInstructions;
                $account->suspended_at = now();
                $account->touch();  // Update timestamp to force refresh

                if (!$account->save()) {
                    throw new \Exception(__('messages.failed_to_save_account'));
                }

                // Send email notification with fresh account data
                try {
                    $freshAccount = Account::with('user')->find($accountId);
                    Mail::to($freshAccount->user->email)->send(new AccountStatusNotification($freshAccount->user, $freshAccount, 'suspended'));
                } catch (\Exception $e) {
                    Log::error(__('messages.failed_to_send_suspension_email') . ': ' . $e->getMessage());
                }
            });

            // Close modal and reset suspension form data only on success
            $this->showSuspensionModal = false;
            $this->suspensionReason = '';
            $this->suspensionInstructions = '';
            $this->suspensionAccountId = null;

            // Use AlertManager instead of session flash
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => __('common.account_suspended_successfully'),
                'dismissible' => true
            ]);

            // Force refresh without page reload
            $this->resetPage();
        } catch (\Exception $e) {
            Log::error('Account suspension failed: ' . $e->getMessage());
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => __('common.account_suspension_failed'),
                'dismissible' => true
            ]);
        }

        $this->dispatch('action-completed');
    }

    public function deleteAccount($accountId)
    {
        $this->processDeleteAccount($accountId);
    }

    public function processDeleteAccount($accountId)
    {
        $this->loadingAction = 'delete_' . $accountId;
        $this->performDeleteAccount($accountId);
        $this->loadingAction = null;
    }

    public function performDeleteAccount($accountId)
    {
        try {
            $account = Account::with('user')->find($accountId);
            if (!$account) {
                throw new \Exception(__('messages.account_not_found'));
            }

            // Store account and user data for email before deletion
            $user = $account->user;
            $accountData = $account->toArray();
            $userData = $user->toArray();

            DB::transaction(function () use ($account, $user, $accountData, $userData) {
                // Send email notification BEFORE deletion while data is still available
                try {
                    Mail::to($user->email)->send(new AccountStatusNotification($user, $account, 'deleted'));
                } catch (\Exception $e) {
                    Log::error(__('messages.failed_to_send_deletion_email') . ': ' . $e->getMessage());
                }

                // Delete the user (this will cascade delete accounts, wallets, cards)
                // The User model's boot method handles file deletion and related cleanup
                // Transfer steps are preserved as they can be linked to multiple accounts
                $deleted = $user->delete();
                if (!$deleted) {
                    throw new \Exception(__('messages.failed_to_delete_user'));
                }
            });

            // Use AlertManager instead of session flash
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => __('common.account_deleted_successfully'),
                'dismissible' => true
            ]);

            // Force refresh without page reload
            $this->resetPage();
        } catch (\Exception $e) {
            Log::error('Account deletion failed: ' . $e->getMessage());
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => __('messages.failed_to_delete_account') . ': ' . $e->getMessage(),
                'dismissible' => true
            ]);
        }

        $this->dispatch('action-completed');
    }

    public function copyAccount($accountNumber)
    {
        $this->dispatch('copy-to-clipboard', ['accountNumber' => $accountNumber, 'message' => __('common.account_number_copied')]);
    }
}

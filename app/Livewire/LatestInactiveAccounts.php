<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Config;
use App\Models\Rib;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class LatestInactiveAccounts extends Component
{
    use WithPagination;

    public function render()
    {
        $inactiveAccounts = Account::where('status', 'INACTIVE')->paginate(10);

        return view('livewire.latest-inactive-accounts', [
            'inactiveAccounts' => $inactiveAccounts,
        ]);
    }

    public function activateAccount(Account $account)
    {
        $this->performActivateAccount($account->id);
    }

    public function performActivateAccount($accountId)
    {
        $account = Account::find($accountId);
        if ($account) {
            $account->status = 'ACTIVE';
            if ($account->save()) {
                // Check if RIB already exists for this account
                if (!\App\Models\Rib::where('account_id', $account->id)->exists()) {
                    try {
                        $this->generateRib($account);
                        $alertPayload = ['type' => 'success', 'message' => __('common.account_activated_successfully')];
                        $this->dispatch('alert', $alertPayload);
                    } catch (\Exception $e) {
                        $alertPayload = ['type' => 'error', 'message' => __('common.rib_generation_failed')];
                        $this->dispatch('alert', $alertPayload);
                    }
                } else {
                    $alertPayload = ['type' => 'success', 'message' => __('common.account_activated_successfully')];
                    $this->dispatch('alert', $alertPayload);
                }

                $this->resetPage();
                $this->dispatch('refreshComponent');
            } else {
                $alertPayload = ['type' => 'error', 'message' => __('common.account_activation_failed')];
                $this->dispatch('alert', $alertPayload);
            }
        }
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

    public function suspendAccount(Account $account)
    {
        $this->performSuspendAccount($account->id);
    }

    public function performSuspendAccount($accountId)
    {
        $account = Account::find($accountId);
        if ($account) {
            $account->status = 'SUSPENDED';
            $account->save();

            $alertPayload = ['type' => 'success', 'message' => __('common.account_suspended_successfully')];
            $this->dispatch('alert', $alertPayload);
            $this->resetPage();
            $this->dispatch('refreshComponent');
        }
    }

    public function deleteAccount(Account $account)
    {
        $this->performDeleteAccount($account->id);
    }

    public function performDeleteAccount($accountId)
    {
        $account = Account::find($accountId);
        if ($account) {
            $account->delete();

            $alertPayload = ['type' => 'success', 'message' => __('common.account_deleted_successfully')];
            $this->dispatch('alert', $alertPayload);
            $this->resetPage();
            $this->dispatch('refreshComponent');
        }
    }

    public function copyAccount($accountNumber)
    {
        $this->dispatch('copy-to-clipboard', ['accountNumber' => $accountNumber, 'message' => __('common.account_number_copied')]);
    }
}

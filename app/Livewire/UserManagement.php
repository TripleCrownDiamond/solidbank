<?php

namespace App\Livewire;

use App\Mail\AccountStatusNotification;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    // Search and filters
    public $search = '';
    public $statusFilter = 'all';
    public $adminFilter = 'all';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    // Bulk actions
    public $selectedUsers = [];
    public $selectAll = false;
    // Modal states
    public $showSuspensionModal = false;
    public $suspensionUserId = null;
    public $suspensionReason = '';
    public $suspensionInstructions = '';
    // Loading states
    public $loadingAction = null;

    protected $listeners = ['execute-method' => 'executeMethod'];

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'adminFilter' => ['except' => 'all'],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Accès non autorisé');
        }
    }

    public function executeMethod($method, $params = [])
    {
        if (method_exists($this, $method)) {
            call_user_func_array([$this, $method], $params);
        }
    }

    public function render()
    {
        $users = $this->getUsers();

        return view('livewire.user-management', [
            'users' => $users,
        ]);
    }

    private function getUsers()
    {
        $query = User::query()->with(['accounts']);

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereHas('accounts', function ($accountQuery) {
                        $accountQuery->where('account_number', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Admin filter
        if ($this->adminFilter !== 'all') {
            $query->where('is_admin', $this->adminFilter === 'admin');
        }

        // Status filter (based on accounts)
        if ($this->statusFilter !== 'all') {
            $query->whereHas('accounts', function ($accountQuery) {
                $accountQuery->where('status', strtoupper($this->statusFilter));
            });
        }

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedAdminFilter()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedUsers = $this->getUsers()->pluck('id')->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    public function activateUser($userId)
    {
        $this->processActivateUser($userId);
    }

    public function processActivateUser($userId)
    {
        $this->loadingAction = 'activate_' . $userId;

        $user = User::find($userId);
        if ($user && !$user->is_admin) {
            // Activer tous les comptes de l'utilisateur
            $user->accounts()->update(['status' => 'ACTIVE']);

            // Générer les RIB manquants
            foreach ($user->accounts as $account) {
                if (!$account->rib) {
                    $this->generateRib($account);
                }

                // Send email notification for each activated account
                try {
                    Mail::to($user->email)->send(new AccountStatusNotification($user, $account, 'activated'));
                } catch (\Exception $e) {
                    // Log email error but don't fail the operation
                    Log::error('Failed to send account activation email: ' . $e->getMessage());
                }
            }

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => __('messages.user_activated_successfully'),
                'dismissible' => true
            ]);
        }

        $this->loadingAction = null;
        $this->dispatch('action-completed');
    }

    public function suspendUser($userId)
    {
        $this->suspensionUserId = $userId;
        $this->showSuspensionModal = true;
        $this->suspensionReason = '';
        $this->suspensionInstructions = '';
    }

    public function processSuspendUser($userId)
    {
        $this->suspensionUserId = $userId;
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

        $suspendedCount = 0;
        $isBulk = $this->suspensionUserId === 'bulk';

        if ($isBulk) {
            // Suspension en lot
            $users = User::whereIn('id', $this->selectedUsers)->where('is_admin', false)->get();
            $suspendedCount = count($users);

            foreach ($users as $user) {
                $user->accounts()->update([
                    'status' => 'SUSPENDED',
                    'suspension_reason' => $this->suspensionReason,
                    'suspension_instructions' => $this->suspensionInstructions,
                ]);

                // Send email notification for each suspended account
                foreach ($user->accounts as $account) {
                    try {
                        Mail::to($user->email)->send(new AccountStatusNotification($user, $account, 'suspended'));
                    } catch (\Exception $e) {
                        // Log email error but don't fail the operation
                        Log::error('Failed to send account suspension email: ' . $e->getMessage());
                    }
                }
            }

            $this->selectedUsers = [];
            $this->selectAll = false;
        } else {
            // Suspension individuelle
            $user = User::find($this->suspensionUserId);
            if ($user && !$user->is_admin) {
                $user->accounts()->update([
                    'status' => 'SUSPENDED',
                    'suspension_reason' => $this->suspensionReason,
                    'suspension_instructions' => $this->suspensionInstructions,
                ]);

                // Send email notification for each suspended account
                foreach ($user->accounts as $account) {
                    try {
                        Mail::to($user->email)->send(new AccountStatusNotification($user, $account, 'suspended'));
                    } catch (\Exception $e) {
                        // Log email error but don't fail the operation
                        Log::error('Failed to send account suspension email: ' . $e->getMessage());
                    }
                }
            }
        }

        $this->cancelSuspension();

        // Use AlertManager to show success message
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => $isBulk
                ? __('messages.users_suspended_count', ['count' => $suspendedCount])
                : __('messages.user_suspended_successfully'),
            'dismissible' => true
        ]);

        // Force refresh without page reload
        $this->resetPage();
    }

    public function processConfirmSuspension($userId)
    {
        $this->loadingAction = 'suspend_' . $userId;

        try {
            $user = User::findOrFail($userId);

            // Toggle suspension status
            $user->is_suspended = !$user->is_suspended;
            $user->save();

            $status = $user->is_suspended ? __('messages.suspended') : __('messages.reactivated');
            $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.user_status_updated', ['status' => $status])]);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.user_status_update_error')]);
        }

        $this->loadingAction = null;
        $this->dispatch('action-completed');
    }

    public function cancelSuspension()
    {
        $this->showSuspensionModal = false;
        $this->suspensionUserId = null;
        $this->suspensionReason = '';
        $this->suspensionInstructions = '';
    }

    public function deleteUser($userId)
    {
        $this->processDeleteUser($userId);
    }

    public function processDeleteUser($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => __('messages.user_not_found'),
                'dismissible' => true
            ]);
            return;
        }

        if ($user->is_admin) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => __('messages.cannot_delete_admin'),
                'dismissible' => true
            ]);
            return;
        }

        if ($user->id === Auth::id()) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => __('messages.cannot_delete_yourself'),
                'dismissible' => true
            ]);
            return;
        }

        try {
            DB::transaction(function () use ($user) {
                // Send email notification before deletion
                foreach ($user->accounts as $account) {
                    try {
                        Mail::to($user->email)->send(new AccountStatusNotification($user, $account, 'deleted'));
                    } catch (\Exception $e) {
                        // Log email error but don't fail the operation
                        Log::error('Failed to send account deletion email: ' . $e->getMessage());
                    }
                }

                // Delete user (related models will be deleted automatically via model boot method)
                $user->delete();
            });

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => __('messages.user_deleted_successfully'),
                'dismissible' => true
            ]);
        } catch (\Exception $e) {
            Log::error('User deletion failed: ' . $e->getMessage());
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => __('messages.user_deletion_failed'),
                'dismissible' => true
            ]);
        }

        $this->dispatch('action-completed');
    }

    // Bulk actions
    public function bulkActivate()
    {
        if (empty($this->selectedUsers)) {
            $this->dispatch('show-alert', [
                'type' => 'warning',
                'message' => __('messages.select_at_least_one_user'),
                'dismissible' => true
            ]);
            return;
        }

        $users = User::whereIn('id', $this->selectedUsers)->where('is_admin', false)->get();
        $emailsSent = 0;
        $emailsFailed = 0;
        $adminUsersSkipped = 0;

        // Check if any selected users are admins
        $totalSelected = count($this->selectedUsers);
        $adminUsersSkipped = $totalSelected - count($users);

        foreach ($users as $user) {
            Log::info('Attempting to activate user: ' . $user->email . ' (ID: ' . $user->id . ')');

            $user->accounts()->update(['status' => 'ACTIVE']);

            foreach ($user->accounts as $account) {
                if (!$account->rib) {
                    $this->generateRib($account);
                }

                // Send email notification for each activated account
                try {
                    Log::info('Sending activation email to: ' . $user->email . ' for account: ' . $account->account_number);
                    Mail::to($user->email)->send(new AccountStatusNotification($user, $account, 'activated'));
                    $emailsSent++;
                    Log::info('Email sent successfully to: ' . $user->email);
                } catch (\Exception $e) {
                    $emailsFailed++;
                    Log::error('Failed to send account activation email to ' . $user->email . ': ' . $e->getMessage());
                }
            }
        }

        $this->selectedUsers = [];
        $this->selectAll = false;

        // Provide detailed feedback
        $message = __('messages.users_activated_count', ['count' => count($users)]);
        if ($emailsFailed > 0) {
            $message .= ' (' . __('messages.emails_sent_with_failures', ['sent' => $emailsSent, 'failed' => $emailsFailed]) . ')';
        } else if ($emailsSent > 0) {
            $message .= ' (' . __('messages.emails_sent_count', ['count' => $emailsSent]) . ')';
        }
        if ($adminUsersSkipped > 0) {
            $message .= ' (' . __('messages.admins_skipped_count', ['count' => $adminUsersSkipped]) . ')';
        }

        $alertType = $emailsFailed > 0 ? 'warning' : 'success';
        $this->dispatch('show-alert', [
            'type' => $alertType,
            'message' => $message,
            'dismissible' => true
        ]);
    }

    public function bulkSuspend()
    {
        if (empty($this->selectedUsers)) {
            $this->dispatch('show-alert', [
                'type' => 'warning',
                'message' => __('messages.select_at_least_one_user'),
                'dismissible' => true
            ]);
            return;
        }

        $this->processBulkSuspend();
    }

    public function processBulkSuspend()
    {
        $this->showSuspensionModal = true;
        $this->suspensionUserId = 'bulk';
    }

    public function bulkDelete()
    {
        if (empty($this->selectedUsers)) {
            $this->dispatch('show-alert', [
                'type' => 'warning',
                'message' => __('messages.select_at_least_one_user'),
                'dismissible' => true
            ]);
            return;
        }

        $this->processBulkDelete();
    }

    public function processBulkDelete()
    {
        $users = User::whereIn('id', $this->selectedUsers)
            ->where('is_admin', false)
            ->where('id', '!=', Auth::id())
            ->get();

        foreach ($users as $user) {
            try {
                DB::transaction(function () use ($user) {
                    // Send email notification before deletion
                    foreach ($user->accounts as $account) {
                        try {
                            Mail::to($user->email)->send(new AccountStatusNotification($user, $account, 'deleted'));
                        } catch (\Exception $e) {
                            // Log email error but don't fail the operation
                            Log::error('Failed to send account deletion email: ' . $e->getMessage());
                        }

                        // Delete RIB if exists
                        if ($account->rib) {
                            $account->rib->delete();
                        }
                        $account->delete();
                    }
                    $user->delete();
                });
            } catch (\Exception $e) {
                Log::error('Bulk user deletion failed for user ' . $user->id . ': ' . $e->getMessage());
            }
        }

        $this->selectedUsers = [];
        $this->selectAll = false;
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => __('messages.users_deleted_count', ['count' => count($users)]),
            'dismissible' => true
        ]);
    }

    public function manageUser($userId)
    {
        $locale = app()->getLocale();
        return redirect()->route('users.manage', ['locale' => $locale, 'user' => $userId]);
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
}

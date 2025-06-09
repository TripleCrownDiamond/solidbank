<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Country;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;

class AdminDashboardStats extends Component
{
    public $totalAccounts;
    public $activeAccounts;
    public $inactiveAccounts;
    public $totalUsers;
    public $accountsIncrease;
    public $activeAccountsIncrease;
    public $inactiveAccountsDecrease;
    public $usersIncrease;
    public $topCountryUsers;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->totalAccounts = Account::count();
        $this->activeAccounts = Account::where('status', 'ACTIVE')->count();
        $this->inactiveAccounts = Account::where('status', 'INACTIVE')->count();
        $this->totalUsers = User::where('is_admin', false)->count();

        $this->topCountryUsers = User::where('is_admin', false)
            ->whereNotNull('country_id')
            ->select('country_id')
            ->selectRaw('count(*) as user_count')
            ->groupBy('country_id')
            ->orderByDesc('user_count')
            ->with('country')
            ->first();

        // These values would typically come from historical data for comparison
        $this->accountsIncrease = 0;
        $this->activeAccountsIncrease = 0;
        $this->inactiveAccountsDecrease = 0;
        $this->usersIncrease = 0;
    }



    public function render()
    {
        return view('livewire.admin-dashboard-stats', [
            'totalAccounts' => $this->totalAccounts,
            'accountsIncrease' => $this->accountsIncrease,
            'activeAccounts' => $this->activeAccounts,
            'activeAccountsIncrease' => $this->activeAccountsIncrease,
            'inactiveAccounts' => $this->inactiveAccounts,
            'inactiveAccountsDecrease' => $this->inactiveAccountsDecrease,
            'totalUsers' => $this->totalUsers,
            'usersIncrease' => $this->usersIncrease,
            'topCountryUsers' => $this->topCountryUsers,
        ]);
    }
}

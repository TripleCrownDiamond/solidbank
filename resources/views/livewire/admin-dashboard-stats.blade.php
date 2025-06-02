<div>
    <h2 class="dark:text-white text-xl">{{ __('common.stats') }}</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold dark:text-white">{{ __('common.total_accounts') }}</h3>
                <div class="text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                </div>
            </div>
            <p class="text-2xl font-bold dark:text-white">{{ $totalAccounts }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold dark:text-white">{{ __('common.active_accounts') }}</h3>
                <div class="text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
            </div>
            <p class="text-2xl font-bold dark:text-white">{{ $activeAccounts }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold dark:text-white">{{ __('common.inactive_accounts_pending_review') }}</h3>
                <div class="text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
            <p class="text-2xl font-bold dark:text-white">{{ $inactiveAccounts }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold dark:text-white">{{ __('common.total_users') }}</h3>
                <div class="text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <p class="text-2xl font-bold dark:text-white">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold dark:text-white">{{ __('common.top_country_by_users') }}</h3>
                <div class="text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-globe"></i>
                </div>
            </div>
            <p class="text-2xl font-bold dark:text-white">
                @if ($topCountryUsers)
                    {{ $topCountryUsers->country->name }} ({{ $topCountryUsers->user_count }})
                @else
                    N/A
                @endif
            </p>
        </div>
    </div>
</div>
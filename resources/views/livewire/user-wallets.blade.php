<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
    <!-- Header -->
    <div class="bg-gradient-to-r from-brand-primary to-brand-accent p-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold dark:text-white mb-2">{{ __('common.crypto_wallets') }}</h2>
                <div class="w-16 h-1 bg-gray-200 dark:bg-gray-300 rounded-full"></div>
            </div>
        </div>
    </div>

    <div class="p-6">

        @if($wallets->count() > 0)
            @if($dashboardView)
                <!-- Dashboard View - Horizontal Wallets -->
                <x-horizontal-wallet-list :wallets="$wallets" brand-color="brand-primary" />
            @else
                <!-- Full Page View - Wallet Design -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($wallets as $wallet)
                        <x-financial-card 
                            :item="$wallet" 
                            type="wallet"
                            :show-details="isset($showWalletDetails[$wallet->id])" 
                            :admin-view="false" 
                        />
                    @endforeach
                </div>
            @endif
        @else
            <!-- No Wallets State -->
            <div class="text-center py-12">
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-gray-100 dark:bg-gray-700 mb-6">
                    <i class="fa-solid fa-wallet text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('common.no_wallets_yet') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">{{ __('common.no_wallets_description') }}</p>
               
            </div>
        @endif
    </div>
</div>
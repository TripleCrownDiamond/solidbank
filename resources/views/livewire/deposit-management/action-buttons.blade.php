<div class="mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-brand-primary to-brand-accent p-6">
            <h2 class="text-xl font-semibold text-white mb-2">
                <i class="fa-solid fa-exchange-alt mr-2"></i>{{ __('common.actions') }}
            </h2>
            <div class="w-16 h-1 bg-gray-200 dark:bg-gray-300 rounded-full"></div>
        </div>
        <div class="p-6">
        <div class="grid grid-cols-1 {{ Auth::user()->is_admin ? 'md:grid-cols-2' : '' }} gap-4">
            @php
                $userAccount = Auth::user()->account;
                $isAccountInactive = $userAccount && $userAccount->status !== 'ACTIVE';
                $shouldDisableButtons = !Auth::user()->is_admin && $isAccountInactive;
            @endphp
            
            @if(Auth::user()->is_admin)
                <!-- Admin Buttons: Dépôt et Retrait -->
                <button wire:click="openDepositModal" 
                        onclick="console.log('Deposit button clicked');"
                        class="inline-block px-6 py-3 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled" 
                        wire:target="openDepositModal">
                    <x-loader-spinner
                        target="openDepositModal"
                        text="{{ __('common.deposit') }}"
                        position="left"
                    >
                        <i class="fa-solid fa-plus mr-2"></i>{{ __('common.deposit') }}
                    </x-loader-spinner>
                </button>
                <button wire:click="openWithdrawalModal" 
                        class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled" 
                        wire:target="openWithdrawalModal">
                    <x-loader-spinner
                        target="openWithdrawalModal"
                        text="{{ __('common.withdrawal') }}"
                        position="left"
                    >
                        <i class="fa-solid fa-minus mr-2"></i>{{ __('common.withdrawal') }}
                    </x-loader-spinner>
                </button>
            @else
                <!-- User Buttons: Envoyer de l'argent seulement -->
                @if($shouldDisableButtons)
                    <!-- Disabled button for inactive accounts -->
                    <div class="relative">
                        <button disabled
                                class="inline-block px-6 py-3 bg-gray-400 text-gray-600 rounded-lg transition-all duration-200 opacity-50 cursor-not-allowed">
                            <i class="fa-solid fa-paper-plane mr-2"></i>{{ __('transfers.send_money') }}
                        </button>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                {{ __('common.account_inactive') }}
                            </span>
                        </div>
                    </div>
                @else
                    <!-- Active button for active accounts -->
                    <button wire:click="openTransferModal" 
                            class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" 
                            wire:target="openTransferModal">
                        <x-loader-spinner
                            target="openTransferModal"
                            text="{{ __('transfers.send_money') }}"
                            position="left"
                        >
                            <i class="fa-solid fa-paper-plane mr-2"></i>{{ __('transfers.send_money') }}
                        </x-loader-spinner>
                    </button>
                @endif
            @endif
        </div>
        </div>
    </div>
</div>

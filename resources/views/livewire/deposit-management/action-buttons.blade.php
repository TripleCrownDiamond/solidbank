<div class="mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
            <i class="fa-solid fa-exchange-alt mr-2"></i>{{ __('common.actions') }}
        </h3>
        <div class="w-16 h-1 bg-blue-500 dark:bg-blue-400 rounded-full mb-4"></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if(Auth::user()->is_admin)
                <!-- Admin Buttons: Dépôt et Retrait -->
                <button wire:click="openDepositModal" 
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
                <!-- User Buttons: Dépôt et Envoyer de l'argent -->
                <button wire:click="openDepositModal" 
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
        </div>
    </div>
</div>

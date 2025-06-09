@props([
    'item',
    'type' => 'card', // 'card' or 'wallet'
    'showDetails' => false,
    'showActions' => true,
    'compact' => false,
    'adminView' => false,
    'brandColor' => 'brand-primary'
])

@if($compact)
    <!-- Compact View (for dashboard) - Standard Layout -->
    <div class="financial-card flex-none w-64 p-3 rounded-lg shadow-xl hover:shadow-2xl transition-shadow duration-300 border border-gray-200 dark:border-gray-600">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center shadow-lg border border-gray-300 dark:border-gray-500" style="background-color: var(--brand-primary);">
                @if($type === 'card')
                    <i class="fa-solid fa-credit-card text-white dark:text-black text-sm"></i>
                @else
                    <i class="fa-solid fa-wallet text-white dark:text-black text-sm"></i>
                @endif
            </div>
            <div class="text-right">
                @if($type === 'card')
                    <p class="text-lg font-bold text-black dark:text-white">{{ \App\Helpers\NumberHelper::formatCurrency($item->balance, '', true) }}</p>
                <p class="text-xs text-gray-600 dark:text-gray-300">{{ $item->currency }}</p>
                @else
                    <p class="text-lg font-bold text-black dark:text-white">{{ \App\Helpers\NumberHelper::formatCrypto($item->balance) }}</p>
                <p class="text-xs text-gray-600 dark:text-gray-300">{{ $item->cryptocurrency->symbol }}</p>
                @endif
            </div>
        </div>
        <div>
            @if($type === 'card')
                <h3 class="font-bold text-lg text-black dark:text-white mb-1">{{ ucfirst($item->type) }}</h3>
            <p class="text-gray-700 dark:text-gray-300 font-mono text-sm">
                    {{ substr($item->card_number, 0, 4) }} **** **** {{ substr($item->card_number, -4) }}
                </p>
            @else
                <h3 class="font-bold text-lg text-black dark:text-white mb-1">{{ $item->name }}</h3>
            <p class="text-gray-700 dark:text-gray-300 text-sm">
                    {{ $item->cryptocurrency->symbol }} - {{ $item->cryptocurrency->network }}
                </p>
            @endif
        </div>
    </div>
@else
    <!-- Full View -->
    <div class="relative">
        <!-- Card/Wallet Design -->
        <div class="financial-card rounded-xl p-6 text-black dark:text-white shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300 border border-gray-200 dark:border-gray-600">
            <!-- Header -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    @if($type === 'card')
                        <p class="text-gray-700 dark:text-gray-300 text-sm font-medium">{{ getAppName() }}</p>
                        <p class="text-black dark:text-white font-semibold">{{ ucfirst($item->type) }}</p>
                    @else
                        <p class="text-gray-700 dark:text-gray-300 text-sm font-medium">{{ getAppName() }}</p>
                        <p class="text-black dark:text-white font-semibold">{{ $item->name }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-gray-700 dark:text-gray-300 text-sm">{{ __('common.balance') }}</p>
                    @if($type === 'card')
                        <p class="text-xl font-bold text-black dark:text-white">{{ \App\Helpers\NumberHelper::formatCurrency($item->balance, $item->currency, true) }}</p>
                    @else
                        <p class="text-xl font-bold text-black dark:text-white">{{ \App\Helpers\NumberHelper::formatCrypto($item->balance, $item->cryptocurrency->symbol) }}</p>
                    @endif
                </div>
            </div>

            <!-- Main Content -->
            <div class="mb-6">
                @if($type === 'card')
                    <p class="text-gray-700 dark:text-gray-300 text-sm mb-1">{{ __('common.card_number') }}</p>
                    <p class="text-lg font-mono text-black dark:text-white" style="letter-spacing: 0.1em;" id="card-number-{{ $item->id }}">
                        @if($showDetails)
                            {{ chunk_split($item->card_number, 4, ' ') }}
                        @else
                            {{ substr($item->card_number, 0, 4) }} **** **** {{ substr($item->card_number, -4) }}
                        @endif
                    </p>
                @else
                    <p class="text-gray-700 dark:text-gray-300 text-sm mb-1">{{ __('common.wallet_address') }}</p>
                    <p class="text-sm font-mono text-black dark:text-white break-all" id="wallet-address-{{ $item->id }}">
                        @if($showDetails)
                            {{ $item->address }}
                        @else
                            {{ substr($item->address, 0, 8) }}...{{ substr($item->address, -8) }}
                        @endif
                    </p>
                @endif
            </div>

            <!-- Footer -->
            <div class="flex justify-between items-end">
                @if($type === 'card')
                    <div>
                        <p class="text-gray-700 dark:text-gray-300 text-xs">{{ __('common.card_holder') }}</p>
                        <p class="text-sm font-medium text-black dark:text-white">{{ $item->card_holder_name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-700 dark:text-gray-300 text-xs">{{ __('common.expires') }}</p>
                        <p class="text-sm font-medium text-black dark:text-white">
                            @if($showDetails)
                                {{ sprintf('%02d/%02d', $item->expiry_month, $item->expiry_year) }}
                            @else
                                **/**
                            @endif
                        </p>
                    </div>
                @else
                    <div>
                        <p class="text-gray-700 dark:text-gray-300 text-xs">{{ __('common.cryptocurrency') }}</p>
                        <p class="text-sm font-medium text-black dark:text-white">{{ $item->cryptocurrency->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-700 dark:text-gray-300 text-xs">{{ __('common.network') }}</p>
                        <p class="text-sm font-medium text-black dark:text-white">{{ $item->cryptocurrency->network }}</p>
                    </div>
                @endif
            </div>

            <!-- Additional Details -->
            @if($showDetails)
                <div class="mt-4 pt-4 border-t border-gray-600">
                    <div class="flex justify-between items-center">
                        @if($type === 'card')
                            <div>
                                <p class="text-gray-700 dark:text-gray-300 text-xs">{{ __('common.cvv') }}</p>
                                <p class="text-sm font-medium text-black dark:text-white">{{ $item->cvv }}</p>
                            </div>
                        @elseif($item->private_key)
                            <div>
                                <p class="text-gray-700 dark:text-gray-300 text-xs">{{ __('common.private_key') }}</p>
                                <p class="text-sm font-mono text-black dark:text-white break-all">{{ $item->private_key }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Account Info -->
        <div class="mt-3 p-3 rounded-lg bg-gray-100 dark:bg-gray-700">
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 dark:text-gray-400">{{ __('common.linked_to_account') }}:</span>
                @if($type === 'card')
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $item->account->account_number }}</span>
                @elseif($item->user && $item->user->account)
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $item->user->account->account_number }}</span>
                @endif
            </div>
            <div class="flex justify-between items-center text-sm mt-1">
                <span class="text-gray-600 dark:text-gray-400">{{ __('common.account_type') }}:</span>
                @if($type === 'card')
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ trans('common.account_types.' . $item->account->type) ?: ucfirst(strtolower($item->account->type)) }}</span>
                @elseif($item->user && $item->user->account)
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ trans('common.account_types.' . $item->user->account->type) ?: ucfirst(strtolower($item->user->account->type)) }}</span>
                @endif
            </div>
        </div>
        
        @if($showActions)
            <!-- Action Buttons -->
            <div class="mt-3 flex gap-2" x-data="{ copied: false }">
                <!-- Copy Button -->
                @if($type === 'card')
                    <button x-on:click="navigator.clipboard.writeText('{{ $item->card_number }}').then(() => { copied = true; setTimeout(() => copied = false, 2000); })"
                            class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200"
                            title="{{ __('common.copy_card_number') }}">
                        <i class="fa-solid fa-copy" x-show="!copied"></i>
                        <i class="fa-solid fa-check" x-show="copied" x-cloak></i>
                    </button>
                @else
                    <button x-on:click="navigator.clipboard.writeText('{{ $item->address }}').then(() => { copied = true; setTimeout(() => copied = false, 2000); })"
                            class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200"
                            title="{{ __('common.copy_wallet_address') }}">
                        <i class="fa-solid fa-copy" x-show="!copied"></i>
                        <i class="fa-solid fa-check" x-show="copied" x-cloak></i>
                    </button>
                @endif
                
                <!-- Toggle Details Button -->
                @if($type === 'card')
                    <button wire:click="toggleCardDetails({{ $item->id }})"
                            class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200"
                            title="{{ $showDetails ? __('common.hide_card_details') : __('common.show_card_details') }}">
                        <i class="fa-solid {{ $showDetails ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                    </button>
                @else
                    <button wire:click="toggleWalletDetails({{ $item->id }})"
                            class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200"
                            title="{{ $showDetails ? __('common.hide_wallet_details') : __('common.show_wallet_details') }}">
                        <i class="fa-solid {{ $showDetails ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                    </button>
                @endif
                
                <!-- Delete Button for Admin -->
                @if(auth()->user() && auth()->user()->is_admin)
                    @if($type === 'card')
                        <button wire:click="deleteCard({{ $item->id }})"
                                wire:confirm="{{ __('messages.confirm_delete_card') }}"
                                wire:loading.attr="disabled" 
                                wire:target="deleteCard({{ $item->id }})"
                                class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200 disabled:opacity-50"
                                title="{{ __('common.delete') }}">
                            <span wire:loading.remove wire:target="deleteCard({{ $item->id }})">
                                <i class="fa-solid fa-trash"></i>
                            </span>
                            <span wire:loading wire:target="deleteCard({{ $item->id }})">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    @else
                        <button wire:click="deleteWallet({{ $item->id }})"
                                wire:confirm="{{ __('messages.confirm_delete_wallet') }}"
                                wire:loading.attr="disabled" 
                                wire:target="deleteWallet({{ $item->id }})"
                                class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200 disabled:opacity-50"
                                title="{{ __('common.delete') }}">
                            <span wire:loading.remove wire:target="deleteWallet({{ $item->id }})">
                                <i class="fa-solid fa-trash"></i>
                            </span>
                            <span wire:loading wire:target="deleteWallet({{ $item->id }})">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    @endif
                @endif
            </div>
        @endif
    </div>
@endif
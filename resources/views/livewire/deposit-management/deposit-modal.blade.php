<div x-data="{}" x-cloak @if(!$showDepositModal) style="display: none;" @endif>
    @if($showDepositModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            <i class="fa-solid fa-plus mr-2"></i>{{ __('common.deposit') }}
                        </h3>
                        <button wire:click="closeDepositModal" 
                                class="text-gray-400 hover:text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="closeDepositModal">
                            <span wire:loading.remove wire:target="closeDepositModal">
                                <i class="fa-solid fa-times"></i>
                            </span>
                            <span wire:loading wire:target="closeDepositModal">
                                <i class="fa-solid fa-spinner fa-spin text-gray-800 dark:text-white"></i>
                            </span>
                        </button>
                    </div>
                    
                    <form wire:submit.prevent="submitTransaction" class="space-y-4">
                        <!-- Type Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('common.deposit_type') }}</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" wire:model.live="depositType" value="account" class="mr-2 text-brand-primary focus:ring-brand-primary">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('admin.account') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" wire:model.live="depositType" value="wallet" class="mr-2 text-brand-primary focus:ring-brand-primary">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('admin.wallet') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Loading State -->
                        <div wire:loading wire:target="depositType" class="flex items-center justify-center py-4">
                            <x-loader-spinner
                                text="{{ __('admin.loading') }}..."
                                position="left"
                                size="md"
                            >
                                {{ __('common.processing') }}
                            </x-loader-spinner>
                        </div>

                        <!-- Account/Wallet Selection -->
                        <div wire:loading.remove wire:target="depositType">
                            @if($depositType === 'account')
                                @if(Auth::user()->is_admin)
                                    <!-- Admin: Account Number Input -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('common.account_number_label') }}</label>
                                        <input type="text" wire:model.live="accountNumber" 
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                               placeholder="{{ __('common.enter_account_number') }}">
                                        @error('accountNumber') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        
                                        @if($detectedUserName && Auth::user()->is_admin)
                                            <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                                <p class="text-sm text-blue-800 dark:text-blue-200">
                                                    <i class="fa-solid fa-info-circle mr-2"></i>
                                                    @if($transactionType === 'deposit')
                                                        {{ __('common.deposit_on_account_of') }} <strong>{{ $detectedUserName }}</strong>
                                                    @else
                                                        {{ __('common.withdraw_from_account_of') }} <strong>{{ $detectedUserName }}</strong>
                                                    @endif
                                                    @if($availableBalance !== null)
                                                        <br><span class="text-xs">{{ __('common.available_balance') }} {{ number_format($availableBalance, 2) }} {{ $currency }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <!-- User: Account Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.select_account') }}</label>
                                        <select wire:model.live="selectedAccountId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                            <option value="">{{ __('admin.select_account') }}</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->account_number }} ({{ $account->type ?? __('common.standard') }})</option>
                                            @endforeach
                                        </select>
                                        @error('selectedAccountId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                            @endif

                            @if($depositType === 'wallet')
                                @if(Auth::user()->is_admin)
                                    <!-- Admin: Wallet Address Input -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('common.wallet_address') }}</label>
                                        <input type="text" wire:model.live="walletAddress" 
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                               placeholder="{{ __('common.enter_wallet_address') }}">
                                        @error('walletAddress') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        
                                        @if($detectedUserName && Auth::user()->is_admin)
                                            <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                                <p class="text-sm text-blue-800 dark:text-blue-200">
                                                    <i class="fa-solid fa-info-circle mr-2"></i>
                                                    @if($transactionType === 'deposit')
                                                        {{ __('common.deposit_on_wallet_of') }} <strong>{{ $detectedUserName }}</strong>
                                                    @else
                                                        {{ __('common.withdraw_from_wallet_of') }} <strong>{{ $detectedUserName }}</strong>
                                                    @endif
                                                    @if($availableBalance !== null)
                                                        <br><span class="text-xs">{{ __('common.available_balance') }} {{ number_format($availableBalance, 2) }} {{ $currency }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <!-- User: Wallet Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.select_wallet') }}</label>
                                        <select wire:model.live="selectedWalletId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                            <option value="">{{ __('admin.select_wallet') }}</option>
                                            @foreach($wallets as $wallet)
                                                <option value="{{ $wallet->id }}">{{ strtoupper($wallet->coin) }} - {{ substr($wallet->address, 0, 10) }}...{{ substr($wallet->address, -6) }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedWalletId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Amount Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('common.amount') }}
                                @if($currency)
                                    <span class="text-brand-primary font-semibold">({{ $currency }})</span>
                                @endif
                            </label>
                            <input type="number" step="0.01" min="0" wire:model.live="amount" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                   placeholder="{{ __('common.enter_amount') }}">
                            @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            
                            @if($balanceError)
                                <div class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                    <p class="text-sm text-red-800 dark:text-red-200">
                                        <i class="fa-solid fa-exclamation-triangle mr-2"></i>
                                        {{ $balanceError }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Reason Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('common.deposit_reason') }}</label>
                            <textarea wire:model="reason" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                      placeholder="{{ __('common.describe_deposit_reason') }}"></textarea>
                            @error('reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-between items-center mt-6">
                            <button type="button" wire:click="closeDepositModal" 
                                    class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled" 
                                    wire:target="closeDepositModal">
                                <x-loader-spinner
                                    target="closeDepositModal"
                                    text=""
                                    position="left"
                                >
                                    {{ __('common.cancel') }}
                                </x-loader-spinner>
                            </button>
                            
                            <button type="button" 
                                    wire:click="submitTransaction"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled" 
                                    wire:target="submitTransaction">
                                <x-loader-spinner
                                    target="submitTransaction"
                                    text=""
                                    position="left"
                                >
                                    <i class="fa-solid fa-plus mr-2"></i>{{ __('actions.add_deposit') }}
                                </x-loader-spinner>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
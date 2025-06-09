<div>

    <!-- Action Buttons -->
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
                        <span wire:loading.remove wire:target="openDepositModal">
                            <i class="fa-solid fa-plus mr-2"></i>{{ __('common.deposit') }}
                        </span>
                        <span wire:loading wire:target="openDepositModal">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.deposit') }}
                        </span>
                    </button>
                    <button wire:click="openWithdrawalModal" 
                            class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" 
                            wire:target="openWithdrawalModal">
                        <span wire:loading.remove wire:target="openWithdrawalModal">
                            <i class="fa-solid fa-minus mr-2"></i>{{ __('common.withdrawal') }}
                        </span>
                        <span wire:loading wire:target="openWithdrawalModal">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.withdrawal') }}
                        </span>
                    </button>
                @else
                    <!-- User Buttons: Dépôt et Envoyer de l'argent -->
                    <button wire:click="openDepositModal" 
                            class="inline-block px-6 py-3 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" 
                            wire:target="openDepositModal">
                        <span wire:loading.remove wire:target="openDepositModal">
                            <i class="fa-solid fa-plus mr-2"></i>{{ __('common.deposit') }}
                        </span>
                        <span wire:loading wire:target="openDepositModal">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.deposit') }}
                        </span>
                    </button>
                    <button wire:click="openTransferModal" 
                            class="inline-block px-6 py-3 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" 
                            wire:target="openTransferModal">
                        <span wire:loading.remove wire:target="openTransferModal">
                            <i class="fa-solid fa-paper-plane mr-2"></i>{{ __('transfers.send_money') }}
                        </span>
                        <span wire:loading wire:target="openTransferModal">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('transfers.send_money') }}
                        </span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Deposit Modal -->
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
                                <i class="fa-solid fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>
                    
                    <form wire:submit.prevent="submitDeposit" class="space-y-4">
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
                            <i class="fa-solid fa-spinner fa-spin text-brand-primary mr-2"></i>
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('admin.loading') }}...</span>
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
                                <span wire:loading.remove wire:target="closeDepositModal">{{ __('common.cancel') }}</span>
                                <span wire:loading wire:target="closeDepositModal">
                                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.cancel') }}
                                </span>
                            </button>
                            
                            <button type="button" 
                                    wire:click="submitTransaction"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled" 
                                    wire:target="submitTransaction">
                                <span wire:loading.remove wire:target="submitTransaction">
                                    <i class="fa-solid fa-plus mr-2"></i>{{ __('actions.add_deposit') }}
                                </span>
                                <span wire:loading wire:target="submitTransaction">
                                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.processing') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Withdrawal Modal -->
    @if($showWithdrawalModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            <i class="fa-solid fa-minus mr-2"></i>{{ __('common.withdrawal') }}
                        </h3>
                        <button wire:click="closeWithdrawalModal" 
                                class="text-gray-400 hover:text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="closeWithdrawalModal">
                            <span wire:loading.remove wire:target="closeWithdrawalModal">
                                <i class="fa-solid fa-times"></i>
                            </span>
                            <span wire:loading wire:target="closeWithdrawalModal">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>
                    
                    <form wire:submit.prevent="submitTransaction" class="space-y-4">
                        <!-- Type Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('common.withdrawal_type') }}</label>
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
                            <i class="fa-solid fa-spinner fa-spin text-brand-primary mr-2"></i>
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('admin.loading') }}...</span>
                        </div>

                        <!-- Account/Wallet Selection -->
                        <div wire:loading.remove wire:target="depositType">
                            @if($depositType === 'account')
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
                                                {{ __('common.withdraw_from_account_of') }} <strong>{{ $detectedUserName }}</strong>
                                                    @if($availableBalance !== null)
                                                        <br><span class="text-xs">{{ __('common.available_balance') }} {{ number_format($availableBalance, 2) }} {{ $currency }}</span>
                                                    @endif
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($depositType === 'wallet')
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
                                                {{ __('common.withdraw_from_wallet_of') }} <strong>{{ $detectedUserName }}</strong>
                                                    @if($availableBalance !== null)
                                                        <br><span class="text-xs">{{ __('common.available_balance') }} {{ number_format($availableBalance, 2) }} {{ $currency }}</span>
                                                    @endif
                                            </p>
                                        </div>
                                    @endif
                                </div>
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

                        <!-- Reason Field (Optional for withdrawals) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('common.withdrawal_reason') }} <span class="text-gray-500 text-xs">({{ __('common.optional') }})</span>
                            </label>
                            <textarea wire:model="reason" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                      placeholder="{{ __('common.describe_withdrawal_reason') }}"></textarea>
                            @error('reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-between items-center mt-6">
                            <button type="button" wire:click="closeWithdrawalModal" 
                                    class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled" 
                                    wire:target="closeWithdrawalModal">
                                <span wire:loading.remove wire:target="closeWithdrawalModal">{{ __('common.cancel') }}</span>
                                <span wire:loading wire:target="closeWithdrawalModal">
                                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.cancel') }}
                                </span>
                            </button>
                            
                            <button type="button" 
                                    wire:click="submitTransaction"
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled" 
                                    wire:target="submitTransaction"
                                    @if($balanceError) disabled @endif>
                                <span wire:loading.remove wire:target="submitTransaction">
                                    <i class="fa-solid fa-minus mr-2"></i>{{ __('common.process_withdrawal') }}
                                </span>
                                <span wire:loading wire:target="submitTransaction">
                                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.processing') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Deposit/Withdrawal Recap Modal -->
    @if($showRecapModal && $recapData && !isset($recapData['transfer_type']))
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            <i class="fa-solid fa-file-text mr-2"></i>{{ __('common.transaction_summary') }}
                        </h3>
                        <button wire:click="closeRecapModal" 
                                class="text-gray-400 hover:text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="closeRecapModal">
                            <span wire:loading.remove wire:target="closeRecapModal">
                                <i class="fa-solid fa-times"></i>
                            </span>
                            <span wire:loading wire:target="closeRecapModal">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>
                    
                    <!-- Transaction Summary Card -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                        <div class="text-center mb-4">
                            @if($recapData['type'] === 'deposit')
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full mb-2">
                                    <i class="fa-solid fa-plus text-green-600 dark:text-green-400 text-xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-green-600 dark:text-green-400">{{ __('common.deposit') }}</h4>
                            @else
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full mb-2">
                                    <i class="fa-solid fa-minus text-red-600 dark:text-red-400 text-xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-red-600 dark:text-red-400">{{ __('common.withdrawal') }}</h4>
                            @endif
                        </div>
                        
                        <!-- Amount Highlight -->
                        <div class="text-center py-3 border-t border-gray-200 dark:border-gray-600">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ __('common.amount') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ number_format($recapData['amount'], 2) }} {{ $recapData['currency'] }}
                            </p>
                        </div>
                    </div>

                    <!-- Transaction Details -->
                    <div class="space-y-3">
                        <!-- Target -->
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('common.target') }}</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                @if($recapData['depositType'] === 'account')
                                    <i class="fa-solid fa-university mr-1 text-blue-500"></i>{{ __('admin.account') }}
                                @else
                                    <i class="fa-solid fa-wallet mr-1 text-purple-500"></i>{{ __('admin.wallet') }}
                                @endif
                            </span>
                        </div>
                        
                        <!-- User -->
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('common.user') }}</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                <i class="fa-solid fa-user mr-1 text-gray-500"></i>{{ $recapData['userInfo'] }}
                            </span>
                        </div>
                        
                        <!-- Account/Wallet -->
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                {{ $recapData['depositType'] === 'account' ? __('common.account') : __('common.wallet') }}
                            </span>
                            <span class="text-sm text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">
                                {{ $recapData['accountOrWallet'] }}
                            </span>
                        </div>
                        
                        <!-- Reason -->
                        @if($recapData['reason'])
                        <div class="py-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400 block mb-1">{{ __('common.reason') }}</span>
                            <div class="bg-gray-100 dark:bg-gray-600 rounded-lg p-3">
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $recapData['reason'] }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if(!Auth::user()->is_admin)
                            <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    <i class="fa-solid fa-info-circle mr-2"></i>
                                    <strong>{{ __('common.important') }}:</strong> {{ __('common.important_transaction_note') }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center mt-6">
                        <button type="button" wire:click="closeRecapModal" 
                                class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="closeRecapModal">
                            <span wire:loading.remove wire:target="closeRecapModal">{{ __('common.cancel') }}</span>
                            <span wire:loading wire:target="closeRecapModal">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.cancel') }}
                            </span>
                        </button>
                        
                        <button type="button" 
                                wire:click="confirmTransaction"
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="confirmTransaction">
                            <span wire:loading.remove wire:target="confirmTransaction">
                                <i class="fa-solid fa-check mr-2"></i>{{ __('common.confirm_transaction') }}
                            </span>
                            <span wire:loading wire:target="confirmTransaction">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.processing') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Transfer Modal -->
    @if($showTransferModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            <i class="fa-solid fa-paper-plane mr-2"></i>{{ __('transfers.send_money') }}
                        </h3>
                        <button wire:click="closeTransferModal" 
                                class="text-gray-400 hover:text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="closeTransferModal">
                            <span wire:loading.remove wire:target="closeTransferModal">
                                <i class="fa-solid fa-times"></i>
                            </span>
                            <span wire:loading wire:target="closeTransferModal">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>
                    
                    <!-- Step Progress Indicator -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            @for($i = 1; $i <= $maxTransferStep; $i++)
                                <div class="flex items-center {{ $i < $maxTransferStep ? 'flex-1' : '' }}">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $transferStep >= $i ? 'bg-brand-primary text-white' : 'bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400' }}">
                                        @if($transferStep > $i)
                                            <i class="fa-solid fa-check text-sm"></i>
                                        @else
                                            {{ $i }}
                                        @endif
                                    </div>
                                    @if($i < $maxTransferStep)
                                        <div class="flex-1 h-0.5 mx-2 {{ $transferStep > $i ? 'bg-brand-primary' : 'bg-gray-200 dark:bg-gray-600' }}"></div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-gray-500 dark:text-gray-400">
                            <span>{{ __('transfers.step_source') }}</span>
                            <span>{{ __('transfers.step_recipient') }}</span>
                            <span>{{ __('transfers.step_amount') }}</span>
                        </div>
                    </div>

                    <form wire:submit.prevent="{{ $transferStep < $maxTransferStep ? 'nextTransferStepModal' : 'submitTransferStep' }}" class="space-y-4">
                        <!-- Step 1: Source Selection -->
                        @if($transferStep === 1)
                            <!-- Source Type Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.source_type') }}</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" wire:model.live="sourceType" value="account" class="mr-2 text-brand-primary focus:ring-brand-primary">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('admin.account') }}</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" wire:model.live="sourceType" value="wallet" class="mr-2 text-brand-primary focus:ring-brand-primary">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('admin.wallet') }}</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Loading State -->
                            <div wire:loading wire:target="sourceType" class="flex items-center justify-center py-4">
                                <i class="fa-solid fa-spinner fa-spin text-brand-primary mr-2"></i>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('admin.loading') }}...</span>
                            </div>

                            <!-- Source Selection -->
                            <div wire:loading.remove wire:target="sourceType">
                                @if($sourceType === 'account')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.select_account') }}</label>
                                        <select wire:model.live="selectedSourceId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                            <option value="">{{ __('transfers.choose_source') }}</option>
                                            @foreach($userAccounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->account_number }} ({{ $account->type ?? __('common.standard') }})</option>
                                            @endforeach
                                        </select>
                                        @error('selectedSourceId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        
                                        @if($availableBalance > 0)
                                            <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                                <p class="text-sm text-blue-800 dark:text-blue-200">
                                                    <i class="fa-solid fa-info-circle mr-2"></i>
                                                    {{ __('common.available_balance') }} {{ number_format($availableBalance, 2) }} {{ $transferCurrency }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if($sourceType === 'wallet')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.select_wallet') }}</label>
                                        <select wire:model.live="selectedSourceId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                            <option value="">{{ __('transfers.choose_source') }}</option>
                                            @foreach($userWallets as $wallet)
                                                <option value="{{ $wallet->id }}">{{ strtoupper($wallet->coin) }} - {{ substr($wallet->address, 0, 10) }}...{{ substr($wallet->address, -6) }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedSourceId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        
                                        @if($availableBalance > 0)
                                            <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                                <p class="text-sm text-blue-800 dark:text-blue-200">
                                                    <i class="fa-solid fa-info-circle mr-2"></i>
                                                    {{ __('common.available_balance') }} {{ number_format($availableBalance, 8) }} {{ $transferCurrency }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Transfer Type Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.transfer_type') }}</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" wire:model.live="transferType" value="internal" class="mr-2 text-brand-primary focus:ring-brand-primary">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('transfers.internal_transfer') }}</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" wire:model.live="transferType" value="external" class="mr-2 text-brand-primary focus:ring-brand-primary">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('transfers.external_transfer') }}</span>
                                    </label>
                                </div>
                            </div>
                        
                        <!-- Step 2: Recipient Information -->
                        @elseif($transferStep === 2)
                            <!-- Internal Transfer Fields -->
                            @if($transferType === 'internal')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ $sourceType === 'account' ? __('transfers.recipient_account_number') : __('transfers.recipient_wallet_address') }}
                                    </label>
                                    <input type="text" wire:model.live="recipientIdentifier" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                                        placeholder="{{ $sourceType === 'account' ? __('transfers.enter_account_number') : __('transfers.enter_wallet_address') }}">
                                    @error('recipientIdentifier')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    @if($recipientError)
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $recipientError }}</p>
                                    @endif
                                    
                                    @if($detectedRecipientName)
                                        <div class="mt-2 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-check-circle text-green-500 mr-2"></i>
                                                <span class="text-sm text-green-700 dark:text-green-300">
                                                    {{ __('transfers.recipient_found') }}: {{ $detectedRecipientName }}
                                                    @if($detectedRecipientCurrency)
                                                        ({{ $detectedRecipientCurrency }})
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($recipientError)
                                        <div class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-exclamation-circle text-red-500 mr-2"></i>
                                                <span class="text-sm text-red-700 dark:text-red-300">{{ $recipientError }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- External Transfer Fields -->
                            @if($transferType === 'external')
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.recipient_name') }}</label>
                                        <input type="text" wire:model="recipientName" 
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                                            placeholder="{{ __('transfers.enter_recipient_name') }}">
                                        @error('recipientName')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.country') }}</label>
                                        <input type="text" wire:model="recipientCountry" 
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                                            placeholder="{{ __('transfers.enter_country') }}">
                                        @error('recipientCountry')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    @if($sourceType === 'account')
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.iban_rib') }}</label>
                                            <input type="text" wire:model="recipientIban" 
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                                                placeholder="{{ __('transfers.enter_iban_rib') }}">
                                            @error('recipientIban')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.bank') }}</label>
                                            <input type="text" wire:model="recipientBank" 
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                                                placeholder="{{ __('transfers.enter_bank') }}">
                                            @error('recipientBank')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @else
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.network') }}</label>
                                            <input type="text" wire:model="cryptoNetwork" 
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                                                placeholder="{{ __('transfers.enter_network') }}">
                                            @error('cryptoNetwork')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.wallet_address') }}</label>
                                            <input type="text" wire:model="cryptoAddress" 
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                                                placeholder="{{ __('transfers.enter_wallet_address') }}">
                                            @error('cryptoAddress')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif
                                </div>
                            @endif

                        <!-- Step 3: Amount and Reason -->
                        @elseif($transferStep === 3)
                            <!-- Amount -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('transfers.amount') }}
                                    @if($selectedSourceCurrency)
                                        <span class="text-brand-primary font-semibold">({{ $selectedSourceCurrency }})</span>
                                    @endif
                                </label>
                                <input type="number" step="0.01" wire:model="transferAmount" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                                    placeholder="{{ __('transfers.enter_amount') }}">
                                @error('transferAmount')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                
                                @if($availableBalance > 0)
                                    <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                        <p class="text-sm text-blue-800 dark:text-blue-200">
                                            <i class="fa-solid fa-info-circle mr-2"></i>
                                            {{ __('common.available_balance') }} {{ number_format($availableBalance, 2) }} {{ $selectedSourceCurrency }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Reason -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.reason') }} ({{ __('transfers.optional') }})</label>
                                <textarea wire:model="transferReason" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                                        placeholder="{{ __('transfers.enter_reason') }}"></textarea>
                                @error('transferReason')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex justify-between pt-6">
                            <div class="flex space-x-3">
                                <button type="button" wire:click="closeTransferModal" 
                                        class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md transition-colors"
                                        wire:loading.attr="disabled">
                                    {{ __('common.cancel') }}
                                </button>
                                
                                @if($transferStep > 1)
                                    <button type="button" wire:click="previousTransferStepModal" 
                                            class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md transition-colors"
                                            wire:loading.attr="disabled">
                                        <i class="fa-solid fa-arrow-left mr-2"></i>
                                        {{ __('common.previous') }}
                                    </button>
                                @endif
                            </div>
                            
                            <div>
                                @if($transferStep < $maxTransferStep)
                                    <button type="button" wire:click="nextTransferStepModal" 
                                            class="px-6 py-2 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            wire:loading.attr="disabled">
                                        <span wire:loading.remove>
                                            {{ __('common.next') }}
                                            <i class="fa-solid fa-arrow-right ml-2"></i>
                                        </span>
                                        <span wire:loading class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            {{ __('common.processing') }}
                                        </span>
                                    </button>
                                @else
                                    <button type="submit" 
                                            class="px-6 py-2 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            wire:loading.attr="disabled">
                                        <span wire:loading.remove>{{ __('transfers.continue') }}</span>
                                        <span wire:loading class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            {{ __('common.processing') }}
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Transfer Recap Modal -->
    @if($showRecapModal && $recapData && isset($recapData['transfer_type']))
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('transfers.transfer_summary') }}</h3>
                        <button wire:click="$set('showRecapModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fa-solid fa-times text-xl"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Source -->
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('transfers.from') }}:</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                @if($recapData['source_type'] === 'account')
                                    {{ $recapData['source']->account_number ?? '' }}
                                @else
                                    {{ Str::limit($recapData['source']->address ?? '', 20) }}
                                @endif
                            </span>
                        </div>

                        <!-- Recipient -->
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('transfers.to') }}:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $recapData['recipient_name'] ?? '' }}</span>
                        </div>

                        <!-- Transfer Type -->
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('transfers.type') }}:</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $recapData['transfer_type'] === 'internal' ? __('transfers.internal_transfer') : __('transfers.external_transfer') }}
                            </span>
                        </div>

                        <!-- Amount -->
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('transfers.amount') }}:</span>
                            <span class="font-bold text-lg text-gray-900 dark:text-white">
                                {{ number_format($recapData['amount'] ?? 0, 2) }}
                                @if($recapData['source_type'] === 'account')
                                    {{ $recapData['source']->currency ?? '' }}
                                @else
                                    {{ $recapData['source']->symbol ?? '' }}
                                @endif
                            </span>
                        </div>

                        @if($recapData['reason'] ?? '')
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('transfers.reason') }}:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $recapData['reason'] }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button wire:click="$set('showRecapModal', false)" 
                                class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                            {{ __('common.back') }}
                        </button>
                        <button wire:click="confirmTransfer" 
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="confirmTransfer">
                            <span wire:loading.remove wire:target="confirmTransfer">
                                <i class="fa-solid fa-check mr-2"></i>{{ __('transfers.confirm_transfer') }}
                            </span>
                            <span wire:loading wire:target="confirmTransfer">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.processing') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Transfer Steps Modal -->
    @if($showTransferStepsModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('transfers.transfer_steps') }}</h3>
                        <button wire:click="closeTransferStepsModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fa-solid fa-times text-xl"></i>
                        </button>
                    </div>

                    @if($transferSteps && count($transferSteps) > 0)
                        <div class="space-y-4">
                            @foreach($transferSteps as $index => $step)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-brand-primary text-white rounded-full flex items-center justify-center text-sm font-medium">
                                            {{ $index + 1 }}
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $step->title }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $step->description }}</p>
                                        @if($step->amount)
                                            <p class="text-sm font-medium text-brand-primary mt-1">
                                                {{ __('transfers.amount') }}: {{ number_format($step->amount, 2) }} {{ $step->currency }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-start">
                                <i class="fa-solid fa-info-circle text-blue-500 mr-2 mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-blue-800 dark:text-blue-200 font-medium">{{ __('transfers.important_note') }}</p>
                                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">{{ __('transfers.follow_steps_carefully') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button wire:click="closeTransferStepsModal" 
                                    class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                                {{ __('common.close') }}
                            </button>
                            <button wire:click="proceedWithTransfer" 
                                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled" 
                                    wire:target="proceedWithTransfer">
                                <span wire:loading.remove wire:target="proceedWithTransfer">
                                    <i class="fa-solid fa-arrow-right mr-2"></i>{{ __('transfers.proceed') }}
                                </span>
                                <span wire:loading wire:target="proceedWithTransfer">
                                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.processing') }}
                                </span>
                            </button>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fa-solid fa-info-circle text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-600 dark:text-gray-400">{{ __('transfers.no_steps_required') }}</p>
                            <button wire:click="proceedWithTransfer" 
                                    class="mt-4 px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled" 
                                    wire:target="proceedWithTransfer">
                                <span wire:loading.remove wire:target="proceedWithTransfer">
                                    <i class="fa-solid fa-check mr-2"></i>{{ __('transfers.proceed_directly') }}
                                </span>
                                <span wire:loading wire:target="proceedWithTransfer">
                                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.processing') }}
                                </span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Progress Modal -->
    @if($showProgressModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="text-center">
                        <div class="mb-4">
                            @if($transferCompleted)
                                <div class="w-16 h-16 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-check text-green-600 dark:text-green-400 text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ __('transfers.transfer_completed') }}</h3>
                                <p class="text-gray-600 dark:text-gray-400">{{ __('transfers.transfer_success_message') }}</p>
                            @else
                                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-spinner fa-spin text-brand-primary text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ __('transfers.processing_transfer') }}</h3>
                                <p class="text-gray-600 dark:text-gray-400">{{ __('transfers.please_wait') }}</p>
                                
                                <!-- Progress Bar -->
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-4">
                                    <div class="bg-brand-primary h-2 rounded-full transition-all duration-300" style="width: {{ (int) $progressPercentage }}%"></div>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $progressPercentage }}% {{ __('common.complete') }}</p>
                            @endif
                        </div>
                        
                        @if($transferCompleted)
                            <button wire:click="closeProgressModal" 
                                    class="px-6 py-2 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-lg transition-colors">
                                {{ __('common.close') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
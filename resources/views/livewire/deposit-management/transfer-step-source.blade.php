<div>
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
    <div wire:loading wire:target="sourceType" class="flex items-center justify-center py-4 dark:text-white">
        <x-loader-spinner
            text="{{ __('admin.loading') }}..."
            position="left"
            size="md"
        >
            {{ __('common.processing') }}
        </x-loader-spinner>
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
                
               @if($availableBalance !== null && $availableBalance >= 0)
                    <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            <i class="fa-solid fa-info-circle mr-2"></i>
                            {{ __('common.available_balance') }} 
                            {{ $sourceType === 'account' ? number_format($availableBalance, 2) : number_format($availableBalance, 8) }} 
                            {{ $transferCurrency }}
                        </p>
                    </div>
                @endif
            </div>
        @elseif($sourceType === 'wallet')
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.select_wallet') }}</label>
                <select wire:model.live="selectedSourceId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                    <option value="">{{ __('transfers.choose_source') }}</option>
                    @foreach($userWallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ strtoupper($wallet->coin) }} - {{ substr($wallet->address, 0, 10) }}...{{ substr($wallet->address, -6) }}</option>
                    @endforeach
                </select>
                @error('selectedSourceId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                
                @if($availableBalance !== null && $availableBalance >= 0)
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
</div>
<div>
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.recipient_name') }}</label>
            <input type="text" wire:model.live="recipientName" 
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                placeholder="{{ __('transfers.enter_recipient_name') }}">
            @error('recipientName')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        
        @if($showCountryField)
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.country') }}</label>
            <input type="text" wire:model.live="recipientCountry" 
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                placeholder="{{ __('transfers.enter_country') }}">
            @error('recipientCountry')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        @endif
        
        @if($sourceType === 'account')
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.iban_rib') }}</label>
                <input type="text" wire:model.live="recipientIban" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                    placeholder="{{ __('transfers.enter_iban_rib') }}">
                @error('recipientIban')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.bank') }}</label>
                <input type="text" wire:model.live="recipientBank" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                    placeholder="{{ __('transfers.enter_bank') }}">
                @error('recipientBank')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        @else
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.network') }}</label>
                <input type="text" wire:model.live="cryptoNetwork" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                    placeholder="{{ __('transfers.enter_network') }}">
                @error('cryptoNetwork')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.wallet_address') }}</label>
                <input type="text" wire:model.live="cryptoAddress" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                    placeholder="{{ __('transfers.enter_wallet_address') }}">
                @error('cryptoAddress')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        @endif
    </div>
</div>
<div>
    <!-- Amount -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ __('transfers.amount') }}
            @if($selectedSourceCurrency)
                <span class="text-brand-primary font-semibold">({{ $selectedSourceCurrency }})</span>
            @endif
        </label>
        <input type="number" 
               step="0.01" 
               wire:model.live="transferAmount"
               class="w-full px-3 py-2 border {{ $amountError ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
               placeholder="{{ __('transfers.enter_amount') }}">
        
        @if($amountError)
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                <i class="fa-solid fa-exclamation-circle mr-1"></i>
                {{ __('transfers.insufficient_balance') }}
            </p>
        @else
            @error('transferAmount')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        @endif
        
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
</div>
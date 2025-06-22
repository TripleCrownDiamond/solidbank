@props(['transaction'])

@if($transaction)
<div class="bg-white dark:bg-gray-800 dark:border dark:border-gray-700 rounded-2xl shadow-xl p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('transfers.transfer_details') }}</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('transfers.amount_label') }}:</span>
            <p class="font-semibold text-gray-900 dark:text-white">{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</p>
        </div>
        <div>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('transfers.reference_label') }}:</span>
            <p class="font-semibold text-gray-900 dark:text-white">{{ $transaction->reference }}</p>
        </div>
        <div>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('transfers.description_label') }}:</span>
            <p class="font-semibold text-gray-900 dark:text-white">{{ $transaction->description }}</p>
        </div>

    </div>
</div>
@endif
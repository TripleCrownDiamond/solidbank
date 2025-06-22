@props(['isCompleted'])

@if($isCompleted)
<div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-6 text-center">
    <div class="w-16 h-16 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>
    <h3 class="text-xl font-semibold text-green-800 dark:text-green-200 mb-2">
        {{ __('transfers.transfer_completed_success') }}
    </h3>
    <p class="text-green-600 dark:text-green-400 mb-6">
        {{ __('transfers.transfer_processed_successfully') }}
    </p>
    <button 
        wire:click="redirectToTransactions"
        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl"
    >
        {{ __('transfers.back_to_transactions') }}
    </button>
</div>
@endif
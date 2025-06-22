<div class="flex flex-col items-center mb-8" id="transfer-progress-container">
    <div class="relative w-48 h-48 mb-6">
        <!-- Background Circle -->
        <svg class="w-48 h-48 transform -rotate-90" viewBox="0 0 100 100">
            <circle
                cx="50"
                cy="50"
                r="45"
                stroke="currentColor"
                stroke-width="8"
                fill="none"
                class="text-gray-200 dark:text-gray-700"
            />
            <!-- Progress Circle -->
            <circle
                cx="50"
                cy="50"
                r="45"
                stroke="currentColor"
                stroke-width="8"
                fill="none"
                stroke-linecap="round"
                class="text-blue-600 dark:text-blue-400 transition-all duration-1000 ease-out"
                style="stroke-dasharray: 314; stroke-dashoffset: {{ 314 - ($progress * 314 / 100) }};"
            />
        </svg>
        
        <!-- Percentage Text -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center">
                <div class="text-4xl font-bold text-gray-900 dark:text-white">
                    {{ $progress }}%
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ __('transfers.progress_label') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Status Message -->
    <div class="text-xl font-semibold text-gray-800 dark:text-gray-200 text-center mb-4">
        <span>{{ $statusMessage }}</span>
    </div>

    <!-- Manual Start Button -->
    <div class="text-center mb-4">
        @if(!$isTransferStarted)
            <button 
                wire:click="startTransfer"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200"
            >
                {{ __('transfers.start_transfer') }}
            </button>
        @endif
    </div>

    <!-- Unlock Button (shown when blocked) -->
    <div class="text-center mb-4" style="display: none;" data-unlock-button>
        <button 
            wire:click="reopenModal"
            class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center mx-auto"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
            </svg>
            {{ __('transfers.unlock_step') }}
        </button>
    </div>
</div>
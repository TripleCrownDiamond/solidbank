@props(['transferStatus', 'showStartButton', 'isTransferBlocked', 'transaction'])

@if($transferStatus === 'ready')
    <div class="flex justify-center mb-6">
        <button 
            wire:click="startTransfer" 
            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            wire:loading.attr="disabled" 
            wire:target="startTransfer"
        >
        <span wire:loading.remove wire:target="startTransfer">
            @php
                $hasCompletedSteps = $transaction && $transaction->getCompletedStepsCount() > 0;
            @endphp
            @if($hasCompletedSteps)
                <i class="fa-solid fa-play mr-2"></i>
                {{ __('transfers.continue_transfer') }}
            @else
                <i class="fa-solid fa-play mr-2"></i>
                {{ __('transfers.start_transfer') }}
            @endif
        </span>
        <span wire:loading wire:target="startTransfer">
            <i class="fa-solid fa-spinner fa-spin mr-2"></i>
            {{ __('transfers.starting_transfer') }}
        </span>
        </button>
    </div>
@elseif($transferStatus === 'starting')
    <div class="flex justify-center mb-6">
        <button 
            disabled
            class="inline-flex items-center px-6 py-3 bg-blue-600 dark:bg-blue-500 text-white font-medium rounded-lg opacity-75 cursor-not-allowed"
        >
        <i class="fa-solid fa-spinner fa-spin mr-2"></i>
        {{ __('transfers.starting_transfer') }}
        </button>
    </div>
@elseif($transferStatus === 'in_progress')
    <div class="flex justify-center mb-6">
        <div class="inline-flex items-center px-6 py-3 text-green-600 dark:text-green-400 font-medium">
        <i class="fa-solid fa-clock mr-2"></i>
        {{ __('transfers.transfer_in_progress') }}
        </div>
    </div>
@elseif($transferStatus === 'blocked')
    @php
    $progressPercentage = $transaction ? $transaction->progress_percentage : 0;
    $allStepsCompleted = $transaction ? $transaction->areAllStepsCompleted() : false;
@endphp
    @if($allStepsCompleted || $progressPercentage >= 100)
        <div class="flex justify-center mb-6">
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4 text-center">
                <p class="text-green-600 dark:text-green-400 font-medium">
                    {{ __('transfers.transfer_submitted_successfully') }}
                </p>
            </div>
        </div>
    @else
        <div class="flex justify-center mb-6">
            <button 
                wire:click="startTransfer" 
                class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-semibold py-3 px-8 rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl"
                {{ (!$allStepsCompleted && $progressPercentage < 100) ? '' : 'disabled' }}
                wire:loading.attr="disabled" 
                wire:target="startTransfer"
            >
                <span wire:loading.remove wire:target="startTransfer">
                    @php
                        $hasCompletedSteps = $transaction && $transaction->getCompletedStepsCount() > 0;
                    @endphp
                    @if($hasCompletedSteps)
                        <i class="fa-solid fa-play mr-2"></i>
                        {{ __('transfers.continue_transfer') }}
                    @else
                        <i class="fa-solid fa-play mr-2"></i>
                        {{ __('transfers.start_transfer') }}
                    @endif
                </span>
                <span wire:loading wire:target="startTransfer">
                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                    {{ __('transfers.starting_transfer') }}
                </span>
            </button>
        </div>
    @endif
@endif
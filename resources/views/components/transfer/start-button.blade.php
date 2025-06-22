@props(['transferStatus', 'showStartButton', 'isTransferBlocked'])

@if($transferStatus === 'ready')
    <div class="flex justify-center mb-6">
        <button 
            wire:click="startTransfer" 
            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            wire:loading.attr="disabled" 
            wire:target="startTransfer"
        >
        <span wire:loading.remove wire:target="startTransfer">
            <i class="fa-solid fa-play mr-2"></i>
            {{ __('transfers.start_transfer') }}
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
    <div class="flex justify-center mb-6">
        <div class="inline-flex items-center px-6 py-3 text-red-600 dark:text-red-400 font-medium">
            <i class="fa-solid fa-exclamation-triangle mr-2"></i>
            {{ __('transfers.transfer_blocked') }}
        </div>
    </div>
@endif
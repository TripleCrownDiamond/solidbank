<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <x-transfer.progress-header />

        <!-- Transaction Details Card -->
        <x-transfer.transaction-details :transaction="$transaction" />

        <!-- Start Button -->
        <x-transfer.start-button 
            :transferStatus="$transferStatus" 
            :showStartButton="$showStartButton" 
            :isTransferBlocked="$isTransferBlocked" 
        />
        
        <!-- Progress Card -->
        @if($showProgressBar)
        <x-transfer.progress-card 
            :progress="$progress" 
            :statusMessage="$statusMessage" 
            :isTransferStarted="$isTransferStarted" 
        />
        @endif

        <!-- Completion Message -->
        <x-transfer.completion-message :isCompleted="$isCompleted" />
        
        <!-- Blocked Transfer Card -->
        @if($isTransferBlocked && $currentStepData)
        <div class="mt-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-red-200 dark:border-red-900/50">
                <!-- Card Header -->
                <div class="bg-red-600 dark:bg-red-900/80 px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-lock text-white text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-white">
                                {{ __('transfers.transfer_blocked') }}
                            </h3>
                        </div>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="p-6">
                    <!-- Step Title -->
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        {{ $currentStepData['step']->title ?? __('transfers.step_blocked') }}
                    </h4>
                    
                    <!-- Step Description -->
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        {{ $currentStepData['step']->description ?? __('transfers.step_blocked_description') }}
                    </p>
                    
                    <!-- Unlock Form -->
                    <div class="mt-4">
                        <label for="unlock-code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('transfers.enter_unlock_code') }}
                        </label>
                        <div class="flex space-x-2">
                            <input
                                type="text"
                                id="unlock-code"
                                wire:model.defer="unlockCode"
                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="{{ __('transfers.enter_code_placeholder') }}"
                                wire:keydown.enter="verifyUnlockCode"
                            >
                            <button
                                type="button"
                                wire:click="verifyUnlockCode"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled"
                                wire:target="verifyUnlockCode"
                            >
                                <span wire:loading.remove wire:target="verifyUnlockCode">
                                    <i class="fas fa-unlock mr-2"></i>
                                    {{ __('transfers.unlock_step') }}
                                </span>
                                <span wire:loading wire:target="verifyUnlockCode">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    {{ __('common.processing') }}
                                </span>
                            </button>
                        </div>
                        
                        @error('unlockCode')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
                
                <!-- Card Footer -->
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-3 text-right">
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ __('transfers.step_x_of_y', ['current' => $currentStep, 'total' => count($steps)]) }}
                    </span>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Gestionnaire de progression Livewire -->
        <div x-data="{ progress: @entangle('progress') }">
         
         <!-- Modal de déblocage d'étape -->
         @if($showStepModal && $currentStepData)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto h-full w-full">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                <i class="fa-solid fa-lock mr-2"></i>{{ $currentStepData['step']->title ?? __('transfers.transfer_step') }}
                            </h3>
                            <button wire:click="closeStepModal" 
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled" 
                                    wire:target="closeStepModal">
                                <span wire:loading.remove wire:target="closeStepModal">
                                    <i class="fa-solid fa-times"></i>
                                </span>
                                <span wire:loading wire:target="closeStepModal">
                                    <i class="fa-solid fa-spinner fa-spin text-gray-800 dark:text-white"></i>
                                </span>
                            </button>
                        </div>
                        
                        <!-- Description -->
                        <div class="mt-2 px-4 py-3">
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                {{ $currentStepData['step']->description ?? __('transfers.enter_unlock_code') }}
                            </p>
                            
                            <!-- Champ de saisie du code -->
                            <div class="mb-4">
                                <label for="stepCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('transfers.unlock_code') }}
                                </label>
                                <input 
                                    type="text" 
                                    id="stepCode" 
                                    wire:model="stepCode" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                    placeholder="{{ __('transfers.enter_code_placeholder') }}"
                                    wire:keydown.enter="verifyStepCode"
                                >
                                
                                <!-- Message d'erreur -->
                                @if($stepCodeError)
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    <i class="fa-solid fa-exclamation-triangle mr-1"></i>
                                    {{ $stepCodeError }}
                                </p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Boutons -->
                        <div class="flex justify-between items-center mt-6">
                            <button 
                                wire:click="closeStepModal" 
                                class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="closeStepModal"
                            >
                                <span wire:loading.remove wire:target="closeStepModal">
                                    {{ __('common.cancel') }}
                                </span>
                                <span wire:loading wire:target="closeStepModal">
                                    <i class="fa-solid fa-spinner fa-spin text-gray-800 dark:text-white"></i>
                                </span>
                            </button>
                            
                            <button 
                                wire:click="verifyStepCode" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="verifyStepCode"
                            >
                                <span wire:loading.remove wire:target="verifyStepCode">
                                    <i class="fa-solid fa-check mr-2"></i>{{ __('common.validate') }}
                                </span>
                                <span wire:loading wire:target="verifyStepCode">
                                    <i class="fa-solid fa-spinner fa-spin"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                 </div>
             </div>
         @endif
 </div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('show-modal-after-delay', (event) => {
            const { stepData, delay } = event;
            setTimeout(() => {
                // Vérifier que stepData est valide avant l'appel
                if (stepData && stepData.step) {
                    @this.call('showModalAtPercentage', stepData);
                } else {
                    console.error('Invalid stepData received:', stepData);
                }
            }, delay);
        });
        
        Livewire.on('change-status-message-after-delay', (event) => {
             const { message, delay } = event;
             console.log('Event change-status-message-after-delay received:', { message, delay });
             setTimeout(() => {
                 console.log('Calling changeStatusMessage with:', message);
                 @this.call('changeStatusMessage', message);
             }, delay);
         });
         
         Livewire.on('start-transfer-progression', (event) => {
             console.log('=== TRANSFER PROGRESSION SCHEDULED ===');
             console.log('Event data:', event);
             
             const { delay } = event;
             
             console.log('Starting transfer progression in', delay, 'ms');
             setTimeout(() => {
                 console.log('Beginning transfer progression now');
                 @this.call('beginTransferProgression');
             }, delay);
         });
         
         Livewire.on('block-transfer-after-delay', (event) => {
             console.log('=== TRANSFER BLOCKING SCHEDULED ===');
             console.log('Event data:', event);
             
             const { transactionId, stepId, stepTitle, delay } = event;
             
             // Vérifier que les valeurs sont valides
             if (!transactionId || !stepId) {
                 console.error('Invalid block transfer parameters:', { transactionId, stepId });
                 return;
             }
             
             console.log('Scheduling transfer blocking in', delay, 'ms');
             setTimeout(() => {
                 console.log('Blocking transfer now');
                 @this.call('blockTransfer', transactionId, stepId, stepTitle);
             }, delay);
         });
    });
</script>

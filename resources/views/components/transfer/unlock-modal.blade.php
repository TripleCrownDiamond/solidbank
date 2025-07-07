@props([
    'showStepModal' => false,
    'unlockCode' => '',
    'unlockError' => '',
    'stepCode' => '',
    'isVerifying' => false,
    'currentStepData' => null
])

@if($showStepModal)
<div 
    x-data="{
        unlockCode: @entangle('unlockCode').live,
        unlockError: @entangle('unlockError').live,
        isVerifying: @entangle('isVerifying').live,
        showError: false,
        
        init() {
            this.$watch('unlockError', (value) => {
                this.showError = value && value.trim() !== '';
                if (this.showError) {
                    this.$nextTick(() => {
                        console.log(window.translations?.transfers?.error_detected || 'Erreur détectée:', value);
                    });
                }
            });
        },
        
        closeModal() {
            $wire.closeStepModal();
        },
        
        async verifyCode() {
            if (!this.unlockCode?.trim()) {
                this.unlockError = '{{ __('transfers.unlock_code_required') }}';
                this.showError = true;
                return;
            }
            
            this.isVerifying = true;
            this.unlockError = '';
            this.showError = false;
            
            try {
                await $wire.verifyUnlockCode();
            } catch (error) {
                console.error(window.translations?.transfers?.error_during_verification || 'Erreur lors de la vérification:', error);
                this.unlockError = '{{ __('transfers.verification_error') }}';
                this.showError = true;
            } finally {
                this.isVerifying = false;
            }
        },
        
        handleKeydown(event) {
            if (event.key === 'Enter' && !this.isVerifying && this.unlockCode?.trim()) {
                event.preventDefault();
                this.verifyCode();
            } else if (event.key === 'Escape') {
                event.preventDefault();
                this.closeModal();
            }
        },
        
        clearError() {
            if (this.unlockError) {
                this.unlockError = '';
            }
        }
    }" 
    @keydown.window="handleKeydown($event)"
    class="fixed inset-0 z-50 overflow-y-auto"
    x-transition:enter="ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-cloak>
    
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-200"
         @click="closeModal()"></div>
    
    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.stop>
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-lock text-blue-600 dark:text-blue-400 text-lg" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">
                                {{ __('transfers.unlock_required') }}
                            </h3>
                            <p class="text-blue-100 text-sm">
                                @if($currentStepData && isset($currentStepData['step']))
                                    {{ $currentStepData['step']->title ?? __('transfers.transfer_step') }}
                                @else
                                    {{ __('transfers.transfer_step') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <button @click="closeModal()" 
                            class="text-white hover:text-blue-100 transition-colors duration-150 p-1 rounded-full hover:bg-white hover:bg-opacity-20 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50"
                            aria-label="{{ __('transfers.close') }}">
                        <i class="fa-solid fa-times text-xl" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            
            <!-- Body -->
            <div class="px-6 py-6">
                <!-- Step Description -->
                <div class="mb-6">
                    <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                        @if($currentStepData && isset($currentStepData['step']))
                            {{ $currentStepData['step']->description ?? __('transfers.enter_unlock_code') }}
                        @else
                            {{ __('transfers.enter_unlock_code') }}
                        @endif
                    </p>
                </div>
                
                <!-- Progress Indicator -->
                @if($currentStepData && isset($currentStepData['percentage']))
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('transfers.progress') }}
                        </span>
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                            {{ number_format($currentStepData['percentage'], 1) }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-300 ease-out" 
                             style="width: {{ $currentStepData['percentage'] }}%"></div>
                    </div>
                </div>
                @endif
                
                <!-- Bank Contact Information -->
                @php
                    $config = \App\Models\Config::first();
                    $bankEmail = $config->bank_email ?? $config->notification_email ?? 'support@bank.com';
                @endphp
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-info-circle text-blue-600 dark:text-blue-400 text-lg" aria-hidden="true"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-1">
                                {{ __('transfers.need_help') }}
                            </h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300 leading-relaxed">
                                {{ __('transfers.contact_bank_for_unlock') }}
                                <a href="mailto:{{ $bankEmail }}" class="font-medium underline hover:no-underline">
                                    {{ $bankEmail }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Code Input -->
                <div class="mb-6">
                    <label for="unlock-code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('transfers.unlock_code') }}
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="unlock-code"
                               x-ref="codeInput"
                               x-model="unlockCode"
                               @input="clearError()"
                               @keydown.enter="verifyCode()"
                               :disabled="isVerifying"
                               class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                               placeholder="{{ __('transfers.enter_code_placeholder') }}"
                               autocomplete="off"
                               spellcheck="false"
                               maxlength="20">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fa-solid fa-key text-gray-400 dark:text-gray-500" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4">
                <!-- Error Message in Footer -->
                <div x-show="showError && unlockError" 
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                     x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
                     class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 border-2 border-red-300 dark:border-red-700 rounded-lg shadow-sm"
                     role="alert">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-exclamation-triangle text-red-600 dark:text-red-400 text-lg" aria-hidden="true"></i>
                        </div>
                        <div class="flex-1">
                            <span class="text-sm font-semibold text-red-800 dark:text-red-200" x-text="unlockError"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Buttons Side by Side -->
                <div class="flex flex-row justify-end space-x-3">
                    <button @click="closeModal()" 
                            :disabled="isVerifying"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ __('transfers.cancel') }}
                    </button>
                    
                    <button @click="verifyCode()" 
                            :disabled="isVerifying || !unlockCode?.trim()"
                            class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 dark:from-blue-500 dark:to-blue-600 dark:hover:from-blue-600 dark:hover:to-blue-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                        <span x-show="!isVerifying">{{ __('transfers.verify') }}</span>
                        <span x-show="isVerifying" class="flex items-center space-x-2">
                            <i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                            <span>{{ __('transfers.verifying') }}</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Scripts optimisés pour l'expérience utilisateur -->
<script>
    // Optimisation: Initialisation plus efficace
    document.addEventListener('livewire:initialized', () => {
        let focusTimeout = null;
        
        // Optimisation: Gestionnaire d'événements avec cleanup
        const handleModalShow = () => {
            if (focusTimeout) {
                clearTimeout(focusTimeout);
            }
            
            focusTimeout = setTimeout(() => {
                requestAnimationFrame(() => {
                    const input = document.getElementById('unlock-code');
                    if (input && document.contains(input)) {
                        input.focus();
                        input.select();
                    }
                });
            }, 150); // Délai optimisé
        };
        
        Livewire.on('show-step-modal', handleModalShow);
        
        // Optimisation: Nettoyage lors du déchargement
        window.addEventListener('beforeunload', () => {
            if (focusTimeout) {
                clearTimeout(focusTimeout);
            }
        });
    });
    
    // Optimisation: Préchargement des transitions CSS
    document.addEventListener('DOMContentLoaded', () => {
        const style = document.createElement('style');
        style.textContent = `
            .modal-transition {
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .progress-bar-transition {
                transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
        `;
        document.head.appendChild(style);
    });
</script>
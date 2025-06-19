<div>
@if($showTransferModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fa-solid fa-paper-plane mr-2"></i>{{ __('transfers.send_money') }}
                    </h3>
                    <button wire:click="closeTransferModal" 
                            class="text-gray-400 hover:text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" 
                            wire:target="closeTransferModal">
                        <span wire:loading.remove wire:target="closeTransferModal">
                            <i class="fa-solid fa-times"></i>
                        </span>
                        <span wire:loading wire:target="closeTransferModal">
                            <i class="fa-solid fa-spinner fa-spin text-gray-800 dark:text-white"></i>
                        </span>
                    </button>
                </div>
                
                <!-- Step Progress Indicator -->
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        @for($i = 1; $i <= $maxTransferStep; $i++)
                            <div class="flex items-center {{ $i < $maxTransferStep ? 'flex-1' : '' }}">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $transferStep >= $i ? 'bg-brand-primary text-white' : 'bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400' }}">
                                    @if($transferStep > $i)
                                        <i class="fa-solid fa-check text-sm"></i>
                                    @else
                                        {{ $i }}
                                    @endif
                                </div>
                                @if($i < $maxTransferStep)
                                    <div class="flex-1 h-0.5 mx-2 {{ $transferStep > $i ? 'bg-brand-primary' : 'bg-gray-200 dark:bg-gray-600' }}"></div>
                                @endif
                            </div>
                        @endfor
                    </div>

                </div>

                <div class="space-y-4">
                    @if($transferStep === 1)
                        <livewire:deposit-management.transfer-step-source
                            :sourceType="$sourceType"
                            :userAccounts="$userAccounts"
                            :userWallets="$userWallets"

                            :availableBalance="$availableBalance"
                            :transferCurrency="$transferCurrency"
                            wire:model="selectedSourceId"
                        />
                    @elseif($transferStep === 2)
                        <livewire:deposit-management.transfer-step-recipient
                            :sourceType="$sourceType"
                            wire:model="recipientName"
                            wire:model.live="recipientCountry"
                            wire:model.live="recipientIban"
                            wire:model.live="recipientBank"
                            wire:model.live="cryptoNetwork"
                            wire:model.live="cryptoAddress"
                        />
                    @elseif($transferStep === 3)
                        <livewire:deposit-management.transfer-step-amount
                            :selectedSourceCurrency="$transferCurrency"
                            :availableBalance="$availableBalance"
                            wire:model="transferAmount"
                            wire:model.live="transferReason"
                        />
                    @elseif($transferStep === 4)
                        <livewire:components.otp-verification />
                    @endif

                    <!-- Actions -->
                    <div class="flex justify-between pt-6">
                        <div class="flex space-x-3">
                            <button type="button" wire:click="closeTransferModal" 
                                    class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md transition-colors"
                                    wire:loading.attr="disabled"
                                    wire:target="closeTransferModal">
                                <x-loader-spinner
                                    target="closeTransferModal"
                                    text=""
                                    position="left"
                                >
                                    {{ __('common.cancel') }}
                                </x-loader-spinner>
                            </button>
                            
                            @if($transferStep > 1 && $transferStep <= $maxTransferStep)
                                <button type="button" wire:click="previousTransferStepModal" 
                                        class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                        wire:loading.attr="disabled"
                                        wire:target="previousTransferStepModal"
                                        @disabled(!$canGoBack)>
                                    <x-loader-spinner
                                        target="previousTransferStepModal"
                                        text=""
                                        position="left"
                                    >
                                        <i class="fa-solid fa-arrow-left mr-2"></i>
                                        {{ __('common.previous') }}
                                    </x-loader-spinner>
                                </button>
                            @endif
                        </div>
                        
                        <div>
                            @if($transferStep < $maxTransferStep)
                                @if($transferStep === 1)
                                    <button type="button" 
                                            wire:click="nextTransferStepModal" 
                                            wire:loading.attr="disabled"
                                            wire:target="nextTransferStepModal"
                                            @disabled(!$sourceStepValid)
                                            class="px-6 py-2 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            id="nextBtn1">
                                        <span wire:loading.remove wire:target="nextTransferStepModal">
                                            {{ __('common.next') }}
                                            <i class="fa-solid fa-arrow-right ml-2"></i>
                                        </span>
                                        <span wire:loading wire:target="nextTransferStepModal" class="flex items-center">
                                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                                            {{ __('common.processing') }}
                                        </span>
                                    </button>
                                @elseif($transferStep === 2)
                                    <button type="button" 
                                            wire:click="nextTransferStepModal" 
                                            wire:loading.attr="disabled"
                                            wire:target="nextTransferStepModal,validate-recipient-step"
                                            @disabled(!$recipientStepValid)
                                            class="px-6 py-2 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            id="nextBtn2">
                                        <span wire:loading.remove wire:target="nextTransferStepModal,validate-recipient-step">
                                            {{ __('common.next') }}
                                            <i class="fa-solid fa-arrow-right ml-2"></i>
                                        </span>
                                        <span wire:loading wire:target="nextTransferStepModal,validate-recipient-step" class="flex items-center">
                                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                                            {{ __('common.processing') }}
                                        </span>
                                    </button>
                                @elseif($transferStep === 3)
                                    <button type="button" 
                                            wire:click="nextTransferStepModal" 
                                            wire:loading.attr="disabled"
                                            wire:target="nextTransferStepModal,validate-amount-step,sendTransferOtp"
                                            @disabled(!$amountStepValid)
                                            class="px-6 py-2 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            id="nextBtn3">
                                        <span wire:loading.remove wire:target="nextTransferStepModal,validate-amount-step,sendTransferOtp">
                                            {{ __('common.next') }}
                                            <i class="fa-solid fa-arrow-right ml-2"></i>
                                        </span>
                                        <span wire:loading wire:target="nextTransferStepModal,validate-amount-step,sendTransferOtp" class="flex items-center">
                                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                                            {{ __('common.processing') }}
                                        </span>
                                    </button>
                                @endif
                            @elseif($transferStep === $maxTransferStep)
                                <!-- Le bouton de confirmation est géré dans le composant otp-verification -->
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif


</div>
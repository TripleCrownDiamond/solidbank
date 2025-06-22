@props(['stepCodeError', 'currentBlockedStep'])

<div data-modal
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 opacity-0 scale-95 transition-all duration-300" 
     style="display: none;">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full p-6">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                {{ __('transfers.transaction_blocked') }}
            </h3>
            @php
                // Récupérer les informations de l'étape bloquée depuis la transaction ou currentBlockedStep
                $blockedStep = null;
                if ($currentBlockedStep) {
                    $blockedStep = $currentBlockedStep;
                } elseif (isset($transaction) && $transaction->blockedAtTransferStep) {
                    $blockedStep = [
                        'title' => $transaction->blockedAtTransferStep->title,
                        'description' => $transaction->blockedAtTransferStep->description,
                        'type' => $transaction->blockedAtTransferStep->type,
                        'code' => $transaction->blockedAtTransferStep->code,
                        'order' => $transaction->blockedAtTransferStep->order
                    ];
                }
            @endphp
            
            @if($blockedStep)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-900 dark:text-white">
                            {{ $blockedStep['title'] ?? __('transfers.step_title_unavailable') }}
                        </h4>
                        <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full">
                            {{ __('transfers.step') }} {{ $blockedStep['order'] ?? 'N/A' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                        {{ $blockedStep['description'] ?? __('transfers.step_description_unavailable') }}
                    </p>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="text-gray-500 dark:text-gray-400">
                            <span class="font-medium">{{ __('transfers.step_type') }}:</span><br>
                            <span class="text-gray-700 dark:text-gray-300">{{ $blockedStep['type'] ?? __('transfers.step_type_unavailable') }}</span>
                        </div>
                        <div class="text-gray-500 dark:text-gray-400">
                            <span class="font-medium">{{ __('transfers.step_code') }}:</span><br>
                            <span class="text-gray-700 dark:text-gray-300 font-mono">{{ $blockedStep['code'] ?? __('transfers.step_code_unavailable') }}</span>
                        </div>
                    </div>
                    @if(isset($transaction) && $transaction->blocked_reason)
                         <div class="mt-3 p-2 bg-orange-50 dark:bg-orange-900 rounded text-xs">
                             <span class="font-medium text-orange-800 dark:text-orange-200">{{ __('common.reason') }}:</span>
                             <span class="text-orange-700 dark:text-orange-300">{{ $transaction->blocked_reason }}</span>
                         </div>
                     @endif
                     
                     <!-- Informations supplémentaires sur la transaction -->
                     @if(isset($transaction))
                         <div class="mt-4 space-y-3">
                             <!-- Informations bancaires du destinataire -->
                             @if($transaction->external_bank_info)
                                 <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-3">
                                     <h5 class="font-medium text-blue-800 dark:text-blue-200 mb-2 text-xs">
                                         <i class="fas fa-university mr-1"></i>{{ __('common.destination_bank_info') }}
                                     </h5>
                                     <div class="grid grid-cols-1 gap-2 text-xs">
                                         @if(isset($transaction->external_bank_info['recipient_name']))
                                             <div><span class="font-medium">{{ __('common.recipient_name') }}:</span> {{ $transaction->external_bank_info['recipient_name'] }}</div>
                                         @endif
                                         @if(isset($transaction->external_bank_info['recipient_iban']))
                                             <div><span class="font-medium">IBAN:</span> <span class="font-mono">{{ $transaction->external_bank_info['recipient_iban'] }}</span></div>
                                         @endif
                                         @if(isset($transaction->external_bank_info['recipient_bank']))
                                             <div><span class="font-medium">{{ __('common.bank') }}:</span> {{ $transaction->external_bank_info['recipient_bank'] }}</div>
                                         @endif
                                         @if(isset($transaction->external_bank_info['recipient_country']))
                                             <div><span class="font-medium">{{ __('common.country') }}:</span> {{ $transaction->external_bank_info['recipient_country'] }}</div>
                                         @endif
                                     </div>
                                 </div>
                             @endif
                             
                             <!-- Informations du compte source -->
                             @if($transaction->account && $transaction->account->rib)
                                 <div class="bg-green-50 dark:bg-green-900 rounded-lg p-3">
                                     <h5 class="font-medium text-green-800 dark:text-green-200 mb-2 text-xs">
                                         <i class="fas fa-credit-card mr-1"></i>{{ __('common.source_account_rib') }}
                                     </h5>
                                     <div class="grid grid-cols-1 gap-2 text-xs">
                                         <div><span class="font-medium">{{ __('common.bank') }}:</span> {{ $transaction->account->rib->bank_name }}</div>
                                         <div><span class="font-medium">IBAN:</span> <span class="font-mono">{{ $transaction->account->rib->iban }}</span></div>
                                         @if($transaction->account->rib->swift_code)
                                             <div><span class="font-medium">BIC/SWIFT:</span> <span class="font-mono">{{ $transaction->account->rib->swift_code }}</span></div>
                                         @endif
                                     </div>
                                 </div>
                             @endif
                             
                             <!-- Détails de la transaction -->
                             <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                 <h5 class="font-medium text-gray-800 dark:text-gray-200 mb-2 text-xs">
                                     <i class="fas fa-info-circle mr-1"></i>{{ __('common.transaction_details') }}
                                 </h5>
                                 <div class="grid grid-cols-2 gap-2 text-xs">
                                     <div><span class="font-medium">{{ __('common.amount_label') }}:</span> {{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</div>
                                     <div><span class="font-medium">{{ __('common.reference_short') }}:</span> <span class="font-mono">{{ $transaction->reference }}</span></div>
                                     <div><span class="font-medium">{{ __('common.date') }}:</span> {{ $transaction->created_at->format('d/m/Y H:i') }}</div>
                                     @if($transaction->blocked_at)
                                         <div><span class="font-medium">{{ __('common.blocked_at') }}:</span> {{ $transaction->blocked_at->format('d/m/Y H:i') }}</div>
                                     @endif
                                 </div>
                             </div>
                         </div>
                     @endif
                </div>
            @else
                <div class="bg-red-50 dark:bg-red-900 rounded-lg p-4 mb-4">
                    <p class="text-sm text-red-600 dark:text-red-400">
                        {{ __('transfers.step_info_unavailable') }}
                    </p>
                </div>
            @endif
            <p class="text-sm text-red-600 dark:text-red-400">
                {{ __('transfers.enter_unlock_code') }}
            </p>
        </div>

        <div class="mb-4">
            <input 
                type="text" 
                wire:model="stepCode"
                placeholder="{{ __('transfers.verification_code_placeholder') }}"
                class="w-full text-center text-lg font-mono py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                autofocus
            >
            @if($stepCodeError)
                <p class="text-red-500 text-sm mt-2 text-center">{{ $stepCodeError }}</p>
            @endif
        </div>

        <div class="flex justify-center space-x-4">
            <button 
                data-cancel-transfer
                class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200"
            >
                {{ __('transfers.cancel') }}
            </button>
            <button 
                data-verify-code
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors duration-200"
            >
                {{ __('transfers.verify_and_continue') }}
            </button>
        </div>
    </div>
</div>
<div>

    <!-- Filtres et recherche -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Recherche -->
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-search mr-1"></i>{{ __('common.search') }}
                </label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       id="search"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                       placeholder="{{ __('common.search_placeholder') }}">
            </div>

            <!-- Filtre par statut -->
            <div>
                <label for="statusFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-filter mr-1"></i>{{ __('common.status') }}
                </label>
                <select wire:model.live="statusFilter" 
                        id="statusFilter"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="all">{{ __('common.all_statuses') }}</option>
                    <option value="pending">{{ __('common.pending') }}</option>
                            <option value="completed">{{ __('common.completed') }}</option>
                            <option value="failed">{{ __('common.failed') }}</option>
                            <option value="cancelled">{{ __('common.cancelled') }}</option>
                            <option value="blocked">{{ __('common.status_blocked') }}</option>
                </select>
            </div>

            <!-- Filtre par type -->
            <div>
                <label for="typeFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-tags mr-1"></i>{{ __('common.type') }}
                </label>
                <select wire:model.live="typeFilter" 
                        id="typeFilter"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="all">{{ __('common.all_types') }}</option>
                    <option value="deposit">{{ __('common.deposit_type_transaction') }}</option>
                    <option value="withdrawal">{{ __('common.withdrawal_type') }}</option>
                    <option value="transfer">{{ __('common.transfer_type') }}</option>
                    <option value="payment">{{ __('common.payment_type') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Liste des transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                <i class="fas fa-exchange-alt mr-2"></i>{{ __('common.transactions') }}
            </h3>
            <div class="w-16 h-1 bg-blue-500 rounded-full mt-2"></div>
        </div>

        @if($transactions->count() > 0)
            <!-- Table responsive -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('common.date') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('common.type') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('common.description') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('common.amount_label') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Statut
                            </th>
                            @if(Auth::user()->is_admin)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('common.user') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('common.transaction_actions') }}
                            </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($transactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <div>
                                        <div class="font-medium">{{ $transaction->created_at->format('d/m/Y') }}</div>
                                        <div class="text-gray-500 dark:text-gray-400">{{ $transaction->created_at->format('H:i') }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($transaction->type === 'DEPOSIT') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($transaction->type === 'WITHDRAWAL') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @elseif(in_array($transaction->type, ['TRANSFER_BANK', 'TRANSFER_CRYPTO', 'TRANSFER_EXTERNAL'])) bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                        @endif">
                                        @if($transaction->type === 'DEPOSIT')
                                            <i class="fas fa-arrow-down mr-1"></i>{{ __('common.deposit_type_transaction') }}
                                        @elseif($transaction->type === 'WITHDRAWAL')
                                            <i class="fas fa-arrow-up mr-1"></i>{{ __('common.withdrawal_type') }}
                                        @elseif(in_array($transaction->type, ['TRANSFER_BANK', 'TRANSFER_CRYPTO', 'TRANSFER_EXTERNAL']))
                                            <i class="fas fa-exchange-alt mr-1"></i>{{ __('common.transfer_type') }}
                                        @else
                                            <i class="fas fa-credit-card mr-1"></i>{{ ucfirst(strtolower($transaction->type)) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    <div class="max-w-xs">
                                        <div class="font-medium truncate">{{ $transaction->description }}</div>
                                        @if($transaction->reference)
                                            <div class="text-gray-500 dark:text-gray-400 text-xs">{{ __('common.reference_short') }}: {{ $transaction->reference }}</div>
                                        @endif
                                        @if($transaction->account)
                                            <div class="text-gray-500 dark:text-gray-400 text-xs">
                                                <i class="fas fa-university mr-1"></i>{{ $transaction->account->account_number }}
                                            </div>
                                        @endif
                                        @if($transaction->wallet)
                                            <div class="text-gray-500 dark:text-gray-400 text-xs">
                                                <i class="fas fa-wallet mr-1"></i>{{ substr($transaction->wallet->address, 0, 8) }}...{{ substr($transaction->wallet->address, -6) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="text-right">
                                        <div class="@if($transaction->type === 'DEPOSIT') text-green-600 dark:text-green-400 @else text-red-600 dark:text-red-400 @endif">
                                            @if($transaction->type === 'DEPOSIT')+@else-@endif{{ number_format($transaction->amount, 2) }} {{ $transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'USD')) }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($transaction->status === 'COMPLETED') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($transaction->status === 'PENDING') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @elseif($transaction->status === 'FAILED') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @elseif($transaction->status === 'CANCELLED') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                        @elseif($transaction->status === 'BLOCKED') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                        @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @endif">
                                        @if($transaction->status === 'COMPLETED')
                                            <i class="fas fa-check-circle mr-1"></i>{{ __('common.completed') }}
                                        @elseif($transaction->status === 'PENDING')
                                            <i class="fas fa-clock mr-1"></i>{{ __('common.pending') }}
                                        @elseif($transaction->status === 'FAILED')
                                            <i class="fas fa-times-circle mr-1"></i>{{ __('common.failed') }}
                                        @elseif($transaction->status === 'CANCELLED')
                                            <i class="fas fa-ban mr-1"></i>{{ __('common.cancelled') }}
                                        @elseif($transaction->status === 'BLOCKED')
                                            <i class="fas fa-lock mr-1"></i>{{ __('common.status_blocked') }}
                                        @else
                                            <i class="fas fa-question-circle mr-1"></i>{{ ucfirst(strtolower($transaction->status)) }}
                                        @endif
                                    </span>
                                </td>
                                @if(Auth::user()->is_admin)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    @if($transaction->user)
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-600 dark:text-gray-300 text-xs"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-medium">{{ $transaction->user->name }}</div>
                                                <div class="text-gray-500 dark:text-gray-400 text-xs">{{ $transaction->user->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($transaction->status === 'PENDING')
                                        <div class="flex space-x-2">
                                            <button wire:click="confirmTransaction({{ $transaction->id }})" 
                                                    wire:confirm="{{ __('messages.confirm_transaction_confirmation') }}"
                                                    wire:loading.attr="disabled"
                                                    wire:target="confirmTransaction({{ $transaction->id }})"
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                    title="{{ __('common.confirm_transaction') }}">
                                                <span wire:loading.remove wire:target="confirmTransaction({{ $transaction->id }})">
                                                    <i class="fas fa-check mr-1"></i>
                                                    {{ __('common.confirm_transaction') }}
                                                </span>
                                                <span wire:loading wire:target="confirmTransaction({{ $transaction->id }})">
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                </span>
                                            </button>
                                            <button wire:click="cancelTransaction({{ $transaction->id }})" 
                                                    wire:confirm="{{ __('messages.confirm_transaction_cancellation') }}"
                                                    wire:loading.attr="disabled"
                                                    wire:target="cancelTransaction({{ $transaction->id }})"
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                    title="{{ __('common.cancel_transaction') }}">
                                                <span wire:loading.remove wire:target="cancelTransaction({{ $transaction->id }})">
                                                    <i class="fas fa-times mr-1"></i>
                                                    {{ __('common.cancel_transaction') }}
                                                </span>
                                                <span wire:loading wire:target="cancelTransaction({{ $transaction->id }})">
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                </span>
                                            </button>
                                        </div>
                                    @elseif($transaction->status === 'BLOCKED')
                                        <button wire:click="showBlockedTransactionDetails({{ $transaction->id }})" 
                                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium text-xs inline-flex items-center">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            {{ __('common.view_details') }}
                                        </button>
                                    @else
                                        <div class="flex items-center space-x-2">
                                            @if($transaction->type === 'TRF' && $transaction->status === 'COMPLETED')
                                                <button wire:click="downloadTransferTicket({{ $transaction->id }})" 
                                                        wire:loading.attr="disabled"
                                                        wire:target="downloadTransferTicket({{ $transaction->id }})"
                                                        class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                        title="{{ __('transfers.download_ticket') }}">
                                                    <span wire:loading.remove wire:target="downloadTransferTicket({{ $transaction->id }})">
                                                        <i class="fas fa-download mr-1"></i>
                                                        {{ __('transfers.download_ticket') }}
                                                    </span>
                                                    <span wire:loading wire:target="downloadTransferTicket({{ $transaction->id }})">
                                                        <i class="fas fa-spinner fa-spin"></i>
                                                    </span>
                                                </button>
                                            @endif
                                            <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">
                                                {{ __('common.processed') }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $transactions->links() }}
            </div>
        @else
            <!-- Aucune transaction -->
            <div class="text-center py-12">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                    <i class="fas fa-exchange-alt text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('common.no_transactions_found') }}</h3>
                <p class="text-gray-500 dark:text-gray-400">{{ __('common.no_transactions_message') }}</p>
            </div>
        @endif
    </div>

    <!-- Modal for Blocked Transaction Details -->
    @if($showBlockedDetailsModal && $selectedTransaction)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-60 dark:bg-opacity-60 flex items-center justify-center z-50" x-data="{ show: @entangle('showBlockedDetailsModal') }" x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl w-full max-w-xl mx-4" @click.away="$wire.closeBlockedDetailsModal()">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-info-circle mr-2"></i>{{ __('common.transaction_details') }}
                </h3>
                <button wire:click="closeBlockedDetailsModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>
            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                <!-- Transaction Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-900 dark:text-gray-100">
                    <div><strong>{{ __('common.date') }}:</strong> <span class="text-gray-700 dark:text-gray-300">{{ $selectedTransaction->created_at->format('d/m/Y H:i') }}</span></div>
                    <div><strong>{{ __('common.status') }}:</strong> <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200"><i class="fas fa-lock mr-1"></i>{{ __('common.status_blocked') }}</span></div>
                    <div><strong>{{ __('common.amount_label') }}:</strong> <span class="font-mono text-gray-700 dark:text-gray-300">{{ number_format($selectedTransaction->amount, 2) }} {{ $selectedTransaction->currency }}</span></div>
                    <div><strong>{{ __('common.user') }}:</strong> <span class="text-gray-700 dark:text-gray-300">{{ $selectedTransaction->user->name }} ({{ $selectedTransaction->user->email }})</span></div>
                </div>

                <!-- RIB Details - Source Account -->
                @if($selectedTransaction->account && $selectedTransaction->account->rib)
                <div>
                    <h4 class="font-semibold text-md text-gray-900 dark:text-gray-100 mb-2 border-t pt-4">{{ __('common.source_account_rib') }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-gray-900 dark:text-gray-100">
                        <div><strong>{{ __('common.bank') }}:</strong> <span class="text-gray-700 dark:text-gray-300">{{ $selectedTransaction->account->rib->bank_name }}</span></div>
                        <div><strong>IBAN:</strong> <span class="font-mono text-gray-700 dark:text-gray-300">{{ $selectedTransaction->account->rib->iban }}</span></div>
                        <div><strong>BIC/SWIFT:</strong> <span class="font-mono text-gray-700 dark:text-gray-300">{{ $selectedTransaction->account->rib->swift }}</span></div>
                    </div>
                </div>
                @endif

                <!-- RIB Details - Destination Account -->
                @if($selectedTransaction->toAccount && $selectedTransaction->toAccount->rib)
                <div>
                    <h4 class="font-semibold text-md text-gray-900 dark:text-gray-100 mb-2 border-t pt-4">{{ __('common.destination_account_rib') }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm bg-blue-50 dark:bg-blue-900 p-4 rounded-lg text-gray-900 dark:text-gray-100">
                        <div><strong>{{ __('common.bank') }}:</strong> <span class="text-gray-700 dark:text-gray-300">{{ $selectedTransaction->toAccount->rib->bank_name }}</span></div>
                        <div><strong>IBAN:</strong> <span class="font-mono text-gray-700 dark:text-gray-300">{{ $selectedTransaction->toAccount->rib->iban }}</span></div>
                        <div><strong>BIC/SWIFT:</strong> <span class="font-mono text-gray-700 dark:text-gray-300">{{ $selectedTransaction->toAccount->rib->swift }}</span></div>
                        <div><strong>{{ __('common.account_holder') }}:</strong> <span class="text-gray-700 dark:text-gray-300">{{ $selectedTransaction->toAccount->user->name }}</span></div>
                    </div>
                </div>
                @elseif($selectedTransaction->external_bank_info)
                <div>
                    <h4 class="font-semibold text-md text-gray-900 dark:text-gray-100 mb-2 border-t pt-4">{{ __('common.destination_bank_info') }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm bg-blue-50 dark:bg-blue-900 p-4 rounded-lg text-gray-900 dark:text-gray-100">
                        <!-- Champs standard -->
                        @if(isset($selectedTransaction->external_bank_info['bank_name']))
                        <div><strong>{{ __('common.bank') }}:</strong> <span class="text-gray-700 dark:text-gray-300">{{ $selectedTransaction->external_bank_info['bank_name'] }}</span></div>
                        @endif
                        @if(isset($selectedTransaction->external_bank_info['iban']))
                        <div><strong>IBAN:</strong> <span class="font-mono text-gray-700 dark:text-gray-300">{{ $selectedTransaction->external_bank_info['iban'] }}</span></div>
                        @endif
                        @if(isset($selectedTransaction->external_bank_info['swift_code']))
                        <div><strong>BIC/SWIFT:</strong> <span class="font-mono text-gray-700 dark:text-gray-300">{{ $selectedTransaction->external_bank_info['swift_code'] }}</span></div>
                        @endif
                        @if(isset($selectedTransaction->external_bank_info['account_holder']))
                        <div><strong>{{ __('common.account_holder') }}:</strong> <span class="text-gray-700 dark:text-gray-300">{{ $selectedTransaction->external_bank_info['account_holder'] }}</span></div>
                        @endif
                        
                        <!-- Nouveaux champs pour les informations du destinataire -->
                        @if(isset($selectedTransaction->external_bank_info['recipient_name']))
                        <div><strong>{{ __('common.recipient_name') }}:</strong> <span class="text-gray-700 dark:text-gray-300">{{ $selectedTransaction->external_bank_info['recipient_name'] }}</span></div>
                        @endif
                        @if(isset($selectedTransaction->external_bank_info['recipient_iban']))
                        <div><strong>{{ __('common.recipient_iban') }}:</strong> <span class="font-mono text-gray-700 dark:text-gray-300">{{ $selectedTransaction->external_bank_info['recipient_iban'] }}</span></div>
                        @endif
                        @if(isset($selectedTransaction->external_bank_info['recipient_bank']))
                        <div><strong>{{ __('common.recipient_bank') }}:</strong> <span class="text-gray-700 dark:text-gray-300">{{ $selectedTransaction->external_bank_info['recipient_bank'] }}</span></div>
                        @endif
                        @if(isset($selectedTransaction->external_bank_info['recipient_country']))
                        <div><strong>{{ __('common.recipient_country') }}:</strong> <span class="text-gray-700 dark:text-gray-300">{{ $selectedTransaction->external_bank_info['recipient_country'] }}</span></div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Transfer Steps -->
                <div>
                    <h4 class="font-semibold text-md text-gray-900 dark:text-gray-100 mb-2 border-t pt-4">{{ __('common.transfer_progress') }}</h4>
                    @if($selectedTransaction && ($selectedTransaction->blockedAtTransferStep || $selectedTransaction->blockedAtTransferStepGroup || $selectedTransaction->transferStepCompletions->count() > 0))
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <!-- Informations sur l'étape bloquée -->
                            @if($selectedTransaction->blockedAtTransferStep)
                            <div class="mb-4 p-3 bg-orange-100 dark:bg-orange-900 rounded-lg border-l-4 border-orange-500">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-lock text-orange-600 mr-2"></i>
                                    <span class="font-semibold text-orange-800 dark:text-orange-200">{{ __('transfers.currently_blocked_at') }}</span>
                                </div>
                                <div class="text-sm text-orange-700 dark:text-orange-300">
                                    <div><strong>{{ __('transfers.step') }}:</strong> {{ $selectedTransaction->blockedAtTransferStep->title }}</div>
                                    <div><strong>{{ __('transfers.step_description') }}:</strong> {{ $selectedTransaction->blockedAtTransferStep->description }}</div>
                                    <div><strong>{{ __('transfers.step_type') }}:</strong> {{ ucfirst($selectedTransaction->blockedAtTransferStep->type) }}</div>
                                    <div><strong>{{ __('transfers.step_code') }}:</strong> <span class="font-mono">{{ $selectedTransaction->blockedAtTransferStep->code }}</span></div>
                                    @if($selectedTransaction->blocked_reason)
                                    <div><strong>{{ __('common.reason') }}:</strong> {{ $selectedTransaction->blocked_reason }}</div>
                                    @endif
                                    <div><strong>{{ __('common.blocked_at') }}:</strong> {{ $selectedTransaction->blocked_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                            @elseif($selectedTransaction->blockedAtTransferStepGroup)
                            <div class="mb-4 p-3 bg-orange-100 dark:bg-orange-900 rounded-lg border-l-4 border-orange-500">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-lock text-orange-600 mr-2"></i>
                                    <span class="font-semibold text-orange-800 dark:text-orange-200">{{ __('transfers.blocked_in_group') }}</span>
                                </div>
                                <div class="text-sm text-orange-700 dark:text-orange-300">
                                    <div><strong>{{ __('transfers.group') }}:</strong> {{ $selectedTransaction->blockedAtTransferStepGroup->name }}</div>
                                    <div><strong>{{ __('transfers.group_description') }}:</strong> {{ $selectedTransaction->blockedAtTransferStepGroup->description }}</div>
                                    @if($selectedTransaction->blocked_reason)
                                    <div><strong>{{ __('common.reason') }}:</strong> {{ $selectedTransaction->blocked_reason }}</div>
                                    @endif
                                    <div><strong>{{ __('common.blocked_at') }}:</strong> {{ $selectedTransaction->blocked_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Liste des étapes avec progression -->
                            <ul class="space-y-3">
                                @php
                                    $transferSteps = collect();
                                    $completedStepIds = $selectedTransaction->transferStepCompletions->pluck('transfer_step_id');
                                    
                                    if ($selectedTransaction->blockedAtTransferStepGroup) {
                                        $transferSteps = $selectedTransaction->blockedAtTransferStepGroup->transferSteps;
                                    } elseif ($selectedTransaction->blockedAtTransferStep) {
                                        $transferSteps = $selectedTransaction->blockedAtTransferStep->transferStepGroup->transferSteps;
                                    } elseif ($selectedTransaction->transferStepCompletions->count() > 0) {
                                        // Récupérer toutes les étapes du groupe à partir des étapes complétées
                                        $firstCompletion = $selectedTransaction->transferStepCompletions->first();
                                        if ($firstCompletion && $firstCompletion->transferStep && $firstCompletion->transferStep->transferStepGroup) {
                                            $transferSteps = $firstCompletion->transferStep->transferStepGroup->transferSteps;
                                        }
                                    }
                                    
                                    // Calculer les étapes non complétées
                                    $nonCompletedStepIds = $transferSteps->pluck('id')->diff($completedStepIds);
                                @endphp
                                @foreach($transferSteps->sortBy('order') as $step)
                                    <li class="flex items-start text-sm border-l-2 pl-4 py-2 
                                        @if($completedStepIds->contains($step->id)) border-green-500 bg-green-50 dark:bg-green-900
                                        @elseif($selectedTransaction->blocked_at_transfer_step_id == $step->id) border-orange-500 bg-orange-50 dark:bg-orange-900
                                        @elseif($nonCompletedStepIds->contains($step->id)) border-red-300 bg-red-50 dark:bg-red-900
                                        @else border-gray-300 dark:border-gray-600 @endif">
                                        <div class="flex-shrink-0 mr-3 mt-0.5">
                                            @if($completedStepIds->contains($step->id))
                                                <i class="fas fa-check-circle text-green-500"></i>
                                            @elseif($selectedTransaction->blocked_at_transfer_step_id == $step->id)
                                                <i class="fas fa-lock text-orange-500"></i>
                                            @elseif($nonCompletedStepIds->contains($step->id))
                                                <i class="fas fa-times-circle text-red-500"></i>
                                            @else
                                                <i class="far fa-circle text-gray-400"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow">
                                            <div class="font-medium 
                                                @if($completedStepIds->contains($step->id)) text-green-700 dark:text-green-300 line-through
                                                @elseif($selectedTransaction->blocked_at_transfer_step_id == $step->id) text-orange-800 dark:text-orange-200
                                                @elseif($nonCompletedStepIds->contains($step->id)) text-red-700 dark:text-red-300
                                                @else text-gray-500 dark:text-gray-400 @endif">
                                                {{ $step->order }}. {{ $step->title }}
                                            </div>
                                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                {{ $step->description }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                    @if($step->type === 'verification') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                                    {{ ucfirst($step->type) }}
                                                </span>
                                                @if($completedStepIds->contains($step->id))
                                                    @php
                                                        $completion = $selectedTransaction->transferStepCompletions->where('transfer_step_id', $step->id)->first();
                                                    @endphp
                                                    @if($completion)
                                                        <span class="ml-2 text-green-600 dark:text-green-400">
                                                            <i class="fas fa-clock mr-1"></i>{{ $completion->completed_at->format('d/m/Y H:i') }}
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('common.no_transfer_progress_info') }}</p>
                    @endif
                </div>

            </div>
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 text-right">
                <button wire:click="closeBlockedDetailsModal" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">{{ __('common.close') }}</button>
            </div>
        </div>
    </div>
    @endif
</div>
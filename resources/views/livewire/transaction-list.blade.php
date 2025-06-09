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
                                            <button wire:click="confirmPendingTransaction({{ $transaction->id }})" 
                                                    wire:confirm="{{ __('messages.confirm_transaction_confirmation') }}"
                                                    wire:loading.attr="disabled"
                                                    wire:target="confirmPendingTransaction({{ $transaction->id }})"
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                    title="{{ __('common.confirm_transaction') }}">
                                                <span wire:loading.remove wire:target="confirmPendingTransaction({{ $transaction->id }})">
                                                    <i class="fas fa-check mr-1"></i>
                                                    {{ __('common.confirm_transaction') }}
                                                </span>
                                                <span wire:loading wire:target="confirmPendingTransaction({{ $transaction->id }})">
                                                    <i class="fas fa-spinner fa-spin mr-1"></i>
                                                    @if($transaction->type === 'DEPOSIT')
                                                        {{ __('common.processing') }}
                                                    @elseif($transaction->type === 'WITHDRAWAL')
                                                        {{ __('common.processing') }}
                                                    @else
                                                        {{ __('common.processing') }}
                                                    @endif
                                                </span>
                                            </button>
                                            <button wire:click="cancelPendingTransaction({{ $transaction->id }})" 
                                                    wire:confirm="{{ __('messages.confirm_transaction_cancellation') }}"
                                                    wire:loading.attr="disabled"
                                                    wire:target="cancelPendingTransaction({{ $transaction->id }})"
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                    title="{{ __('common.cancel_transaction') }}">
                                                <span wire:loading.remove wire:target="cancelPendingTransaction({{ $transaction->id }})">
                                                    <i class="fas fa-times mr-1"></i>
                                                    {{ __('common.cancel_transaction') }}
                                                </span>
                                                <span wire:loading wire:target="cancelPendingTransaction({{ $transaction->id }})">
                                                    <i class="fas fa-spinner fa-spin mr-1"></i>
                                                    @if($transaction->type === 'DEPOSIT')
                                                        {{ __('messages.processing_deposit') }}
                                                    @elseif($transaction->type === 'WITHDRAWAL')
                                                        {{ __('messages.processing_withdrawal') }}
                                                    @else
                                                        {{ __('common.processing') }}
                                                    @endif
                                                </span>
                                            </button>
                                        </div>
                                    @elseif($transaction->status === 'BLOCKED')
                                        <span class="text-orange-600 dark:text-orange-400 text-sm font-medium">
                                            <i class="fas fa-lock mr-1"></i>{{ __('common.status_blocked') }}
                                        </span>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">
                                            {{ __('common.processed') }}
                                        </span>
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
</div>
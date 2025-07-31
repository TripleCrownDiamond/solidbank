<div>

    <!-- Filtres et recherche -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Recherche -->
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-search mr-1"></i><?php echo e(__('common.search')); ?>

                </label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       id="search"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                       placeholder="<?php echo e(__('common.search_placeholder')); ?>">
            </div>

            <!-- Filtre par statut -->
            <div>
                <label for="statusFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-filter mr-1"></i><?php echo e(__('common.status')); ?>

                </label>
                <select wire:model.live="statusFilter" 
                        id="statusFilter"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="all"><?php echo e(__('common.all_statuses')); ?></option>
                    <option value="pending"><?php echo e(__('common.pending')); ?></option>
                            <option value="completed"><?php echo e(__('common.completed')); ?></option>
                            <option value="failed"><?php echo e(__('common.failed')); ?></option>
                            <option value="cancelled"><?php echo e(__('common.cancelled')); ?></option>
                            <option value="blocked"><?php echo e(__('common.status_blocked')); ?></option>
                </select>
            </div>

            <!-- Filtre par type -->
            <div>
                <label for="typeFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-tags mr-1"></i><?php echo e(__('common.type')); ?>

                </label>
                <select wire:model.live="typeFilter" 
                        id="typeFilter"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="all"><?php echo e(__('common.all_types')); ?></option>
                    <option value="deposit"><?php echo e(__('common.deposit_type_transaction')); ?></option>
                    <option value="withdrawal"><?php echo e(__('common.withdrawal_type')); ?></option>
                    <option value="transfer_bank"><?php echo e(__('common.transfer_bank_type')); ?></option>
                    <option value="transfer_crypto"><?php echo e(__('common.transfer_crypto_type')); ?></option>
                    <option value="transfer_external"><?php echo e(__('common.transfer_external_type')); ?></option>
                    <option value="payment"><?php echo e(__('common.payment_type')); ?></option>
                </select>
            </div>
        </div>
    </div>

    <!-- Liste des transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-brand-primary to-brand-accent p-6">
            <h2 class="text-xl font-semibold text-white mb-2">
                <i class="fas fa-exchange-alt mr-2"></i><?php echo e(__('common.transactions')); ?>

            </h2>
            <div class="w-16 h-1 bg-gray-200 dark:bg-gray-300 rounded-full"></div>
        </div>
        <div class="p-6">

        <!--[if BLOCK]><![endif]--><?php if($transactions->count() > 0): ?>
            <!-- Table responsive -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <?php echo e(__('common.date')); ?>

                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <?php echo e(__('common.type')); ?>

                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <?php echo e(__('common.description')); ?>

                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <?php echo e(__('common.amount_label')); ?>

                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Statut
                            </th>
                            <!--[if BLOCK]><![endif]--><?php if(Auth::user()->is_admin): ?>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <?php echo e(__('common.user')); ?>

                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <?php echo e(__('common.transaction_actions')); ?>

                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Suppression
                            </th>
                            <?php else: ?>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <div>
                                        <div class="font-medium"><?php echo e($transaction->created_at->format('d/m/Y')); ?></div>
                                        <div class="text-gray-500 dark:text-gray-400"><?php echo e($transaction->created_at->format('H:i')); ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        <?php if($transaction->type === 'DEPOSIT'): ?> bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        <?php elseif($transaction->type === 'WITHDRAWAL'): ?> bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        <?php elseif(in_array($transaction->type, ['TRANSFER_BANK', 'TRANSFER_CRYPTO', 'TRANSFER_EXTERNAL'])): ?> bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        <?php else: ?> bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                        <?php endif; ?>">
                                        <!--[if BLOCK]><![endif]--><?php if($transaction->type === 'DEPOSIT'): ?>
                                            <i class="fas fa-arrow-down mr-1"></i><?php echo e(__('common.deposit_type_transaction')); ?>

                                        <?php elseif($transaction->type === 'WITHDRAWAL'): ?>
                                            <i class="fas fa-arrow-up mr-1"></i><?php echo e(__('common.withdrawal_type')); ?>

                                        <?php elseif($transaction->type === 'TRANSFER_BANK'): ?>
                                            <i class="fas fa-exchange-alt mr-1"></i><?php echo e(__('common.transfer_bank_type')); ?>

                                        <?php elseif($transaction->type === 'TRANSFER_CRYPTO'): ?>
                                            <i class="fas fa-exchange-alt mr-1"></i><?php echo e(__('common.transfer_crypto_type')); ?>

                                        <?php elseif($transaction->type === 'TRANSFER_EXTERNAL'): ?>
                                            <i class="fas fa-exchange-alt mr-1"></i><?php echo e(__('common.transfer_external_type')); ?>

                                        <?php else: ?>
                                            <i class="fas fa-credit-card mr-1"></i><?php echo e(ucfirst(strtolower($transaction->type))); ?>

                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    <div class="max-w-xs">
                                        <div class="font-medium truncate"><?php echo e($transaction->description); ?></div>
                                        <!--[if BLOCK]><![endif]--><?php if($transaction->reference): ?>
                                            <div class="text-gray-500 dark:text-gray-400 text-xs"><?php echo e(__('common.reference_short')); ?>: <?php echo e($transaction->reference); ?></div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <!--[if BLOCK]><![endif]--><?php if($transaction->account): ?>
                                            <div class="text-gray-500 dark:text-gray-400 text-xs">
                                                <i class="fas fa-university mr-1"></i><?php echo e($transaction->account->account_number); ?>

                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <!--[if BLOCK]><![endif]--><?php if($transaction->wallet): ?>
                                            <div class="text-gray-500 dark:text-gray-400 text-xs">
                                                <i class="fas fa-wallet mr-1"></i><?php echo e(substr($transaction->wallet->address, 0, 8)); ?>...<?php echo e(substr($transaction->wallet->address, -6)); ?>

                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="text-right">
                                        <div class="<?php if($transaction->type === 'DEPOSIT'): ?> text-green-600 dark:text-green-400 <?php else: ?> text-red-600 dark:text-red-400 <?php endif; ?>">
                                            <!--[if BLOCK]><![endif]--><?php if($transaction->type === 'DEPOSIT'): ?>+<?php else: ?>-<?php endif; ?><!--[if ENDBLOCK]><![endif]--><?php echo e(number_format($transaction->amount, 2)); ?> <?php echo e($transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'USD'))); ?>

                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        <?php if($transaction->status === 'COMPLETED'): ?> bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        <?php elseif($transaction->status === 'PENDING'): ?> bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        <?php elseif($transaction->status === 'FAILED'): ?> bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        <?php elseif($transaction->status === 'CANCELLED'): ?> bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                        <?php elseif($transaction->status === 'BLOCKED'): ?> bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                        <?php else: ?> bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        <?php endif; ?>">
                                        <!--[if BLOCK]><![endif]--><?php if($transaction->status === 'COMPLETED'): ?>
                                            <i class="fas fa-check-circle mr-1"></i><?php echo e(__('common.completed')); ?>

                                        <?php elseif($transaction->status === 'PENDING'): ?>
                                            <i class="fas fa-clock mr-1"></i><?php echo e(__('common.pending')); ?>

                                        <?php elseif($transaction->status === 'FAILED'): ?>
                                            <i class="fas fa-times-circle mr-1"></i><?php echo e(__('common.failed')); ?>

                                        <?php elseif($transaction->status === 'CANCELLED'): ?>
                                            <i class="fas fa-ban mr-1"></i><?php echo e(__('common.cancelled')); ?>

                                        <?php elseif($transaction->status === 'BLOCKED'): ?>
                                            <i class="fas fa-lock mr-1"></i><?php echo e(__('common.status_blocked')); ?>

                                        <?php else: ?>
                                            <i class="fas fa-question-circle mr-1"></i><?php echo e(ucfirst(strtolower($transaction->status))); ?>

                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </span>
                                </td>
                                <!--[if BLOCK]><![endif]--><?php if(Auth::user()->is_admin): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <!--[if BLOCK]><![endif]--><?php if($transaction->user): ?>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-600 dark:text-gray-300 text-xs"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-medium"><?php echo e($transaction->user->name); ?></div>
                                                <div class="text-gray-500 dark:text-gray-400 text-xs"><?php echo e($transaction->user->email); ?></div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-gray-500 dark:text-gray-400">-</span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <!--[if BLOCK]><![endif]--><?php if($transaction->status === 'PENDING'): ?>
                                        <div class="flex space-x-2">
                                            <!--[if BLOCK]><![endif]--><?php if(in_array($transaction->type, ['TRANSFER_BANK', 'TRANSFER_CRYPTO', 'TRANSFER_EXTERNAL'])): ?>
                                                <!-- Boutons pour les transactions de transfert -->
                                                <button wire:click="confirmTransferTransaction(<?php echo e($transaction->id); ?>)" 
                                                        wire:confirm="<?php echo e(__('messages.confirm_transaction_confirmation')); ?>"
                                                        wire:loading.attr="disabled"
                                                        wire:target="confirmTransferTransaction(<?php echo e($transaction->id); ?>)"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                        title="<?php echo e(__('common.confirm_transaction')); ?>">
                                                    <span wire:loading.remove wire:target="confirmTransferTransaction(<?php echo e($transaction->id); ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </span>
                                                    <span wire:loading wire:target="confirmTransferTransaction(<?php echo e($transaction->id); ?>)">
                                                        <i class="fas fa-spinner fa-spin"></i>
                                                    </span>
                                                </button>
                                                <button wire:click="cancelTransferTransaction(<?php echo e($transaction->id); ?>)" 
                                                        wire:confirm="<?php echo e(__('messages.confirm_transaction_cancellation')); ?>"
                                                        wire:loading.attr="disabled"
                                                        wire:target="cancelTransferTransaction(<?php echo e($transaction->id); ?>)"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                        title="<?php echo e(__('common.cancel_transaction')); ?>">
                                                    <span wire:loading.remove wire:target="cancelTransferTransaction(<?php echo e($transaction->id); ?>)">
                                                        <i class="fas fa-times"></i>
                                                    </span>
                                                    <span wire:loading wire:target="cancelTransferTransaction(<?php echo e($transaction->id); ?>)">
                                                        <i class="fas fa-spinner fa-spin"></i>
                                                    </span>
                                                </button>
                                            <?php else: ?>
                                                <!-- Boutons pour les transactions de dépôt et retrait -->
                                                <button wire:click="confirmTransaction(<?php echo e($transaction->id); ?>)" 
                                                        wire:confirm="<?php echo e(__('messages.confirm_transaction_confirmation')); ?>"
                                                        wire:loading.attr="disabled"
                                                        wire:target="confirmTransaction(<?php echo e($transaction->id); ?>)"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                        title="<?php echo e(__('common.confirm_transaction')); ?>">
                                                    <span wire:loading.remove wire:target="confirmTransaction(<?php echo e($transaction->id); ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </span>
                                                    <span wire:loading wire:target="confirmTransaction(<?php echo e($transaction->id); ?>)">
                                                        <i class="fas fa-spinner fa-spin"></i>
                                                    </span>
                                                </button>
                                                <button wire:click="cancelTransaction(<?php echo e($transaction->id); ?>)" 
                                                        wire:confirm="<?php echo e(__('messages.confirm_transaction_cancellation')); ?>"
                                                        wire:loading.attr="disabled"
                                                        wire:target="cancelTransaction(<?php echo e($transaction->id); ?>)"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                        title="<?php echo e(__('common.cancel_transaction')); ?>">
                                                    <span wire:loading.remove wire:target="cancelTransaction(<?php echo e($transaction->id); ?>)">
                                                        <i class="fas fa-times"></i>
                                                    </span>
                                                    <span wire:loading wire:target="cancelTransaction(<?php echo e($transaction->id); ?>)">
                                                        <i class="fas fa-spinner fa-spin"></i>
                                                    </span>
                                                </button>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    <?php elseif($transaction->status === 'BLOCKED'): ?>
                                        <button wire:click="showBlockedTransactionDetails(<?php echo e($transaction->id); ?>)" 
                                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium text-xs inline-flex items-center">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            <?php echo e(__('common.view_details')); ?>

                                        </button>
                                    <?php elseif($transaction->status === 'COMPLETED'): ?>
                                        <div class="flex space-x-2">
                                            <!--[if BLOCK]><![endif]--><?php if($transaction->receipt_path): ?>
                                                <button wire:click="downloadReceipt(<?php echo e($transaction->id); ?>)" 
                                                        wire:loading.attr="disabled"
                                                        wire:target="downloadReceipt(<?php echo e($transaction->id); ?>)"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                        title="<?php echo e(__('common.download_receipt')); ?>">
                                                    <span wire:loading.remove wire:target="downloadReceipt(<?php echo e($transaction->id); ?>)">
                                                        <i class="fas fa-download"></i>
                                                    </span>
                                                    <span wire:loading wire:target="downloadReceipt(<?php echo e($transaction->id); ?>)">
                                                        <i class="fas fa-spinner fa-spin"></i>
                                                    </span>
                                                </button>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    <?php else: ?>
                                        <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">
                                            <?php echo e(__('common.processed')); ?>

                                        </span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                     <!--[if BLOCK]><![endif]--><?php if($transaction->status === 'COMPLETED'): ?>
                                         <button wire:click="deleteTransaction(<?php echo e($transaction->id); ?>)" 
                                                 wire:confirm="<?php echo e(__('common.confirm_delete_transaction')); ?>"
                                                 wire:loading.attr="disabled"
                                                 wire:target="deleteTransaction(<?php echo e($transaction->id); ?>)"
                                                 class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                 title="<?php echo e(__('common.delete_transaction')); ?>">
                                             <span wire:loading.remove wire:target="deleteTransaction(<?php echo e($transaction->id); ?>)">
                                                 <i class="fas fa-trash"></i>
                                             </span>
                                             <span wire:loading wire:target="deleteTransaction(<?php echo e($transaction->id); ?>)">
                                                 <i class="fas fa-spinner fa-spin"></i>
                                             </span>
                                         </button>
                                     <?php else: ?>
                                         <span class="text-gray-500 dark:text-gray-400">-</span>
                                     <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                 </td>
                                <?php else: ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <!--[if BLOCK]><![endif]--><?php if($transaction->status === 'COMPLETED' && $transaction->receipt_path): ?>
                                        <button wire:click="downloadReceipt(<?php echo e($transaction->id); ?>)" 
                                                wire:loading.attr="disabled"
                                                wire:target="downloadReceipt(<?php echo e($transaction->id); ?>)"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                title="<?php echo e(__('common.download_receipt')); ?>">
                                            <span wire:loading.remove wire:target="downloadReceipt(<?php echo e($transaction->id); ?>)">
                                                <i class="fas fa-download"></i>
                                            </span>
                                            <span wire:loading wire:target="downloadReceipt(<?php echo e($transaction->id); ?>)">
                                                <i class="fas fa-spinner fa-spin"></i>
                                            </span>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-gray-500 dark:text-gray-400">-</span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <?php echo e($transactions->links()); ?>

            </div>
        <?php else: ?>
            <!-- Aucune transaction -->
            <div class="text-center py-12">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                    <i class="fas fa-exchange-alt text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2"><?php echo e(__('common.no_transactions_found')); ?></h3>
                <p class="text-gray-500 dark:text-gray-400"><?php echo e(__('common.no_transactions_message')); ?></p>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    <!-- Modal for Blocked Transaction Details -->
    <!--[if BLOCK]><![endif]--><?php if($showBlockedDetailsModal && $selectedTransaction): ?>
    <div class="fixed inset-0 bg-gray-900 bg-opacity-60 dark:bg-opacity-60 flex items-center justify-center z-50" x-data="{ show: true }" x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl w-full max-w-xl mx-4" @click.away="$wire.closeBlockedDetailsModal()">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-info-circle mr-2"></i><?php echo e(__('common.transaction_details')); ?>

                </h3>
                <button wire:click="closeBlockedDetailsModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>
            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                <!-- Transaction Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-900 dark:text-gray-100">
                    <div><strong><?php echo e(__('common.date')); ?>:</strong> <span class="text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->created_at->format('d/m/Y H:i')); ?></span></div>
                    <div><strong><?php echo e(__('common.status')); ?>:</strong> <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200"><i class="fas fa-lock mr-1"></i><?php echo e(__('common.status_blocked')); ?></span></div>
                    <div><strong><?php echo e(__('common.amount_label')); ?>:</strong> <span class="font-mono text-gray-700 dark:text-gray-300"><?php echo e(number_format($selectedTransaction->amount, 2)); ?> <?php echo e($selectedTransaction->currency); ?></span></div>
                    <div><strong><?php echo e(__('common.user')); ?>:</strong> <span class="text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->user->name); ?> (<?php echo e($selectedTransaction->user->email); ?>)</span></div>
                </div>

                <!-- RIB Details - Source Account -->
                <!--[if BLOCK]><![endif]--><?php if($selectedTransaction->account && $selectedTransaction->account->rib): ?>
                <div>
                    <h4 class="font-semibold text-md text-gray-900 dark:text-gray-100 mb-2 border-t pt-4"><?php echo e(__('common.source_account_rib')); ?></h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-gray-900 dark:text-gray-100">
                        <div><strong><?php echo e(__('common.bank')); ?>:</strong> <span class="text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->account->rib->bank_name); ?></span></div>
                        <div><strong>IBAN:</strong> <span class="font-mono text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->account->rib->iban); ?></span></div>
                        <div><strong>BIC/SWIFT:</strong> <span class="font-mono text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->account->rib->swift_code); ?></span></div>
                    </div>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!-- RIB Details - Destination Account -->
                <!--[if BLOCK]><![endif]--><?php if($selectedTransaction->toAccount && $selectedTransaction->toAccount->rib): ?>
                <div>
                    <h4 class="font-semibold text-md text-gray-900 dark:text-gray-100 mb-2 border-t pt-4"><?php echo e(__('common.destination_account_rib')); ?></h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm bg-blue-50 dark:bg-blue-900 p-4 rounded-lg text-gray-900 dark:text-gray-100">
                        <div><strong><?php echo e(__('common.bank')); ?>:</strong> <span class="text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->toAccount->rib->bank_name); ?></span></div>
                        <div><strong>IBAN:</strong> <span class="font-mono text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->toAccount->rib->iban); ?></span></div>
                        <div><strong>BIC/SWIFT:</strong> <span class="font-mono text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->toAccount->rib->swift_code); ?></span></div>
                        <div><strong><?php echo e(__('common.account_holder')); ?>:</strong> <span class="text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->toAccount->user->name); ?></span></div>
                    </div>
                </div>
                <?php elseif($selectedTransaction->external_bank_info): ?>
                <div>
                    <h4 class="font-semibold text-md text-gray-900 dark:text-gray-100 mb-2 border-t pt-4"><?php echo e(__('common.destination_bank_info')); ?></h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm bg-blue-50 dark:bg-blue-900 p-4 rounded-lg text-gray-900 dark:text-gray-100">
                        <!-- Champs standard -->
                        <!--[if BLOCK]><![endif]--><?php if(isset($selectedTransaction->external_bank_info['bank_name'])): ?>
                        <div><strong><?php echo e(__('common.bank')); ?>:</strong> <span class="text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->external_bank_info['bank_name']); ?></span></div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if(isset($selectedTransaction->external_bank_info['iban'])): ?>
                        <div><strong>IBAN:</strong> <span class="font-mono text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->external_bank_info['iban']); ?></span></div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if(isset($selectedTransaction->external_bank_info['swift_code'])): ?>
                        <div><strong>BIC/SWIFT:</strong> <span class="font-mono text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->external_bank_info['swift_code']); ?></span></div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if(isset($selectedTransaction->external_bank_info['account_holder'])): ?>
                        <div><strong><?php echo e(__('common.account_holder')); ?>:</strong> <span class="text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->external_bank_info['account_holder']); ?></span></div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        
                        <!-- Nouveaux champs pour les informations du destinataire -->
                        <!--[if BLOCK]><![endif]--><?php if(isset($selectedTransaction->external_bank_info['recipient_name'])): ?>
                        <div><strong><?php echo e(__('common.recipient_name')); ?>:</strong> <span class="text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->external_bank_info['recipient_name']); ?></span></div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if(isset($selectedTransaction->external_bank_info['recipient_iban'])): ?>
                        <div><strong><?php echo e(__('common.recipient_iban')); ?>:</strong> <span class="font-mono text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->external_bank_info['recipient_iban']); ?></span></div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if(isset($selectedTransaction->external_bank_info['recipient_bank'])): ?>
                        <div><strong><?php echo e(__('common.recipient_bank')); ?>:</strong> <span class="text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->external_bank_info['recipient_bank']); ?></span></div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if(isset($selectedTransaction->external_bank_info['recipient_country'])): ?>
                        <div><strong><?php echo e(__('common.recipient_country')); ?>:</strong> <span class="text-gray-700 dark:text-gray-300"><?php echo e($selectedTransaction->external_bank_info['recipient_country']); ?></span></div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!-- Transfer Steps -->
                <div>
                    <h4 class="font-semibold text-md text-gray-900 dark:text-gray-100 mb-2 border-t pt-4"><?php echo e(__('common.transfer_progress')); ?></h4>
                    <!--[if BLOCK]><![endif]--><?php if($selectedTransaction && $selectedTransaction->blockedAtTransferStep): ?>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <!--[if BLOCK]><![endif]--><?php if(!$selectedTransaction->areAllTransferStepsCompleted()): ?>
                            <!-- Informations sur l'étape bloquée -->
                            <div class="mb-4 p-3 bg-orange-100 dark:bg-orange-900 rounded-lg border-l-4 border-orange-500">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-lock text-orange-600 mr-2"></i>
                                    <span class="font-semibold text-orange-800 dark:text-orange-200"><?php echo e(__('transfers.currently_blocked_at')); ?></span>
                                </div>
                                <div class="text-sm text-orange-700 dark:text-orange-300">
                                    <div><strong><?php echo e(__('transfers.step')); ?>:</strong> <?php echo e($selectedTransaction->blockedAtTransferStep->title); ?></div>
                                    <div><strong><?php echo e(__('transfers.step_description')); ?>:</strong> <?php echo e($selectedTransaction->blockedAtTransferStep->description); ?></div>
                                    <div><strong><?php echo e(__('transfers.step_type')); ?>:</strong> <?php echo e(ucfirst($selectedTransaction->blockedAtTransferStep->type)); ?></div>
                                    <div><strong><?php echo e(__('transfers.step_code')); ?>:</strong> <span class="font-mono"><?php echo e($selectedTransaction->blockedAtTransferStep->code); ?></span></div>
                                    <!--[if BLOCK]><![endif]--><?php if($selectedTransaction->blocked_reason): ?>
                                    <div><strong><?php echo e(__('common.reason')); ?>:</strong> <?php echo e($selectedTransaction->blocked_reason); ?></div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <div><strong><?php echo e(__('common.blocked_at')); ?>:</strong> <?php echo e($selectedTransaction->blocked_at->format('d/m/Y H:i')); ?></div>
                                </div>
                            </div>
                            <?php else: ?>
                            <!-- Message de succès quand toutes les étapes sont complétées -->
                            <div class="mb-4 p-3 bg-green-100 dark:bg-green-900 rounded-lg border-l-4 border-green-500">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                    <span class="font-semibold text-green-800 dark:text-green-200"><?php echo e(__('transfers.all_steps_completed')); ?></span>
                                </div>
                                <div class="text-sm text-green-700 dark:text-green-300">
                                    <?php echo e(__('transfers.transfer_steps_completed_successfully')); ?>

                                </div>
                            </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            
                            <!-- Liste des étapes avec progression -->
                            <ul class="space-y-3">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $selectedTransaction->blockedAtTransferStep->transferStepGroup->transferSteps->sortBy('order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $isCompleted = $selectedTransaction->isStepCompleted($step->id);
                                        $allStepsCompleted = $selectedTransaction->areAllTransferStepsCompleted();
                                        $isCurrentBlocked = $selectedTransaction->blocked_at_transfer_step_id == $step->id;
                                        $isBlocked = !$allStepsCompleted && !$isCompleted && ($isCurrentBlocked || $step->order > ($selectedTransaction->blockedAtTransferStep->order ?? 0));
                                    ?>
                                    <li class="flex items-start text-sm border-l-2 pl-4 py-2 
                                        <?php if($isCompleted || $allStepsCompleted): ?> border-green-500 bg-green-50 dark:bg-green-900
                                        <?php elseif($isCurrentBlocked && !$allStepsCompleted): ?> border-red-500 bg-red-50 dark:bg-red-900
                                        <?php elseif($isBlocked): ?> border-orange-500 bg-orange-50 dark:bg-orange-900
                                        <?php else: ?> border-gray-300 dark:border-gray-600 <?php endif; ?>">
                                        <div class="flex-shrink-0 mr-3 mt-0.5">
                                            <!--[if BLOCK]><![endif]--><?php if($isCompleted || $allStepsCompleted): ?>
                                                <i class="fas fa-check-circle text-green-500"></i>
                                            <?php elseif($isCurrentBlocked && !$allStepsCompleted): ?>
                                                <i class="fas fa-exclamation-triangle text-red-500"></i>
                                            <?php elseif($isBlocked): ?>
                                                <i class="fas fa-lock text-orange-500"></i>
                                            <?php else: ?>
                                                <i class="far fa-circle text-gray-400"></i>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                        <div class="flex-grow">
                                            <div class="font-medium 
                                                <?php if($isCompleted || $allStepsCompleted): ?> text-green-700 dark:text-green-300 line-through
                                                <?php elseif($isCurrentBlocked && !$allStepsCompleted): ?> text-red-800 dark:text-red-200
                                                <?php elseif($isBlocked): ?> text-orange-800 dark:text-orange-200
                                                <?php else: ?> text-gray-500 dark:text-gray-400 <?php endif; ?>">
                                                <?php echo e($step->order); ?>. <?php echo e($step->title); ?>

                                            </div>
                                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                <?php echo e($step->description); ?>

                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                    <?php if($step->type === 'verification'): ?> bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    <?php else: ?> bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 <?php endif; ?>">
                                                    <?php echo e(ucfirst($step->type)); ?>

                                                </span>
                                                <!--[if BLOCK]><![endif]--><?php if($selectedTransaction->isStepCompleted($step->id)): ?>
                                                    <?php
                                                        $completion = $selectedTransaction->transferStepCompletions->where('transfer_step_id', $step->id)->first();
                                                    ?>
                                                    <!--[if BLOCK]><![endif]--><?php if($completion): ?>
                                                        <span class="ml-2 text-green-600 dark:text-green-400">
                                                            <i class="fas fa-clock mr-1"></i><?php echo e($completion->completed_at->format('d/m/Y H:i')); ?>

                                                        </span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </ul>
                        </div>
                    <?php else: ?>
                        <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e(__('common.no_transfer_progress_info')); ?></p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

            </div>
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 text-right">
                <button wire:click="closeBlockedDetailsModal" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"><?php echo e(__('common.close')); ?></button>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/livewire/transaction-list.blade.php ENDPATH**/ ?>
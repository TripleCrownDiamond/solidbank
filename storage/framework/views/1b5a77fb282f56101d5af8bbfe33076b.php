<div>
    <!--[if BLOCK]><![endif]--><?php if($showRecapModal): ?>
    <div
        wire:keydown.escape="closeRecapModal"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             wire:click="closeRecapModal">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4"
                  wire:click.stop>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            <i class="fa-solid fa-file-text mr-2" aria-hidden="true"></i>
                            <?php echo e(__('common.transaction_summary')); ?>

                        </h3>
                        <button wire:click="closeRecapModal"
                                type="button"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled"
                                wire:target="closeRecapModal"
                                aria-label="<?php echo e(__('common.close')); ?>">
                            <span wire:loading.remove wire:target="closeRecapModal">
                                <i class="fa-solid fa-times" aria-hidden="true"></i>
                            </span>
                            <span wire:loading wire:target="closeRecapModal">
                                <i class="fa-solid fa-spinner fa-spin text-black dark:text-white"></i>
                            </span>
                        </button>
                    </div>

                    <!-- Transaction Summary Card -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                        <div class="text-center mb-4">
                            <!--[if BLOCK]><![endif]--><?php if(isset($recapData['type']) && $recapData['type'] === 'deposit'): ?>
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full mb-2">
                                    <i class="fa-solid fa-plus text-green-600 dark:text-green-400 text-xl" aria-hidden="true"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-green-600 dark:text-green-400">
                                    <?php echo e(__('common.deposit')); ?>

                                </h4>
                            <?php else: ?>
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full mb-2">
                                    <i class="fa-solid fa-minus text-red-600 dark:text-red-400 text-xl" aria-hidden="true"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-red-600 dark:text-red-400">
                                    <?php echo e(__('common.withdrawal')); ?>

                                </h4>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- Amount Highlight -->
                        <div class="text-center py-3 border-t border-gray-200 dark:border-gray-600">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1"><?php echo e(__('common.amount')); ?></p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                <?php echo e(isset($recapData['amount']) ? number_format($recapData['amount'], 2) : '0.00'); ?>

                                <?php echo e($recapData['currency'] ?? 'USD'); ?>

                            </p>
                        </div>
                    </div>

                    <!-- Transaction Details -->
                    <div class="space-y-3">
                        <!-- Target -->
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                <?php echo e(__('common.target')); ?>

                            </span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                <!--[if BLOCK]><![endif]--><?php if((isset($recapData['depositType']) && $recapData['depositType'] === 'account') || (isset($recapData['withdrawalType']) && $recapData['withdrawalType'] === 'account')): ?>
                                    <i class="fa-solid fa-university mr-1 text-blue-500" aria-hidden="true"></i>
                                    <?php echo e(__('admin.account')); ?>

                                <?php else: ?>
                                    <i class="fa-solid fa-wallet mr-1 text-purple-500" aria-hidden="true"></i>
                                    <?php echo e(__('admin.wallet')); ?>

                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </span>
                        </div>

                        <!-- User -->
                        <!--[if BLOCK]><![endif]--><?php if(isset($recapData['userInfo'])): ?>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                <?php echo e(__('common.user')); ?>

                            </span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                <i class="fa-solid fa-user mr-1 text-gray-500" aria-hidden="true"></i>
                                <?php echo e($recapData['userInfo']); ?>

                            </span>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!-- Account/Wallet -->
                        <!--[if BLOCK]><![endif]--><?php if(isset($recapData['accountOrWallet'])): ?>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                <?php
                                    $isAccount = false;
                                    if (isset($recapData['depositType'])) {
                                        $isAccount = $recapData['depositType'] === 'account';
                                    } elseif (isset($recapData['withdrawalType'])) {
                                        $isAccount = $recapData['withdrawalType'] === 'account';
                                    }
                                ?>
                                <?php echo e($isAccount ? __('common.account') : __('common.wallet')); ?>

                            </span>
                            <span class="text-sm text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded break-all">
                                <?php echo e($recapData['accountOrWallet']); ?>

                            </span>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!-- Reason -->
                        <!--[if BLOCK]><![endif]--><?php if(isset($recapData['reason']) && !empty($recapData['reason'])): ?>
                        <div class="py-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400 block mb-1">
                                <?php echo e(__('common.reason')); ?>

                            </span>
                            <div class="bg-gray-100 dark:bg-gray-600 rounded-lg p-3">
                                <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap"><?php echo e($recapData['reason']); ?></p>
                            </div>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center mt-6">
                        <button type="button"
                                wire:click="closeRecapModal"
                                class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled"
                                wire:target="closeRecapModal">
                            <span wire:loading.remove wire:target="closeRecapModal">
                                 <?php echo e(__('common.cancel')); ?>

                             </span>
                            <span wire:loading wire:target="closeRecapModal">
                                <i class="fa-solid fa-spinner fa-spin text-black dark:text-white"></i>
                            </span>
                        </button>
                        
                        <button type="button"
                                wire:click="confirmTransaction"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled"
                                wire:target="confirmTransaction">
                            <span wire:loading.remove wire:target="confirmTransaction">
                                <i class="fa-solid fa-check mr-2" aria-hidden="true"></i>
                                <?php echo e(__('common.confirm_transaction')); ?>

                            </span>
                            <span wire:loading wire:target="confirmTransaction">
                                <i class="fa-solid fa-spinner fa-spin text-black dark:text-white"></i>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/livewire/deposit-management/recap-modal.blade.php ENDPATH**/ ?>
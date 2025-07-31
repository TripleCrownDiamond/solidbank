<div>
    <!-- Amount -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            <?php echo e(__('transfers.amount')); ?>

            <!--[if BLOCK]><![endif]--><?php if($selectedSourceCurrency): ?>
                <span class="text-brand-primary font-semibold">(<?php echo e($selectedSourceCurrency); ?>)</span>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </label>
        <input type="number" 
               step="0.01" 
               wire:model.live="transferAmount"
               class="w-full px-3 py-2 border <?php echo e($amountError ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'); ?> rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
               placeholder="<?php echo e(__('transfers.enter_amount')); ?>">
        
        <!--[if BLOCK]><![endif]--><?php if($amountError): ?>
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                <i class="fa-solid fa-exclamation-circle mr-1"></i>
                <?php echo e(__('transfers.insufficient_balance')); ?>

            </p>
        <?php else: ?>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['transferAmount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        
        <!--[if BLOCK]><![endif]--><?php if($availableBalance > 0): ?>
            <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <i class="fa-solid fa-info-circle mr-2"></i>
                    <?php echo e(__('common.available_balance')); ?> <?php echo e(number_format($availableBalance, 2)); ?> <?php echo e($selectedSourceCurrency); ?>

                </p>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!-- Reason -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('transfers.reason')); ?> (<?php echo e(__('transfers.optional')); ?>)</label>
        <textarea wire:model="transferReason" rows="3"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                placeholder="<?php echo e(__('transfers.enter_reason')); ?>"></textarea>
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['transferReason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/livewire/deposit-management/transfer-step-amount.blade.php ENDPATH**/ ?>
<div>
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('transfers.recipient_name')); ?></label>
            <input type="text" wire:model.live="recipientName" 
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                placeholder="<?php echo e(__('transfers.enter_recipient_name')); ?>">
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['recipientName'];
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
        
        <!--[if BLOCK]><![endif]--><?php if($showCountryField): ?>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('transfers.country')); ?></label>
            <input type="text" wire:model.live="recipientCountry" 
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                placeholder="<?php echo e(__('transfers.enter_country')); ?>">
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['recipientCountry'];
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
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        
        <!--[if BLOCK]><![endif]--><?php if($sourceType === 'account'): ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('transfers.iban_rib')); ?></label>
                <input type="text" wire:model.live="recipientIban" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                    placeholder="<?php echo e(__('transfers.enter_iban_rib')); ?>">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['recipientIban'];
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
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('transfers.bank')); ?></label>
                <input type="text" wire:model.live="recipientBank" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                    placeholder="<?php echo e(__('transfers.enter_bank')); ?>">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['recipientBank'];
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
        <?php else: ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('transfers.network')); ?></label>
                <input type="text" wire:model.live="cryptoNetwork" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                    placeholder="<?php echo e(__('transfers.enter_network')); ?>">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['cryptoNetwork'];
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
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('transfers.wallet_address')); ?></label>
                <input type="text" wire:model.live="cryptoAddress" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                    placeholder="<?php echo e(__('transfers.enter_wallet_address')); ?>">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['cryptoAddress'];
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
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/livewire/deposit-management/transfer-step-recipient.blade.php ENDPATH**/ ?>
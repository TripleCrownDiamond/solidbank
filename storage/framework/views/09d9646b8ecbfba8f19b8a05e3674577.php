<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['transaction']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['transaction']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<!--[if BLOCK]><![endif]--><?php if($transaction): ?>
<div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg dark:border dark:border-gray-700/50 rounded-2xl shadow-xl p-6 mb-8 border border-gray-200/50">
    <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400 mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
        </svg>
        <?php echo e(__('transfers.transfer_details')); ?>

    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo e(__('transfers.amount_label')); ?>:</span>
            <p class="font-semibold text-gray-900 dark:text-white"><?php echo e(number_format($transaction->amount, 2)); ?> <?php echo e($transaction->currency); ?></p>
        </div>
        <div>
            <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo e(__('transfers.reference_label')); ?>:</span>
            <p class="font-semibold text-gray-900 dark:text-white"><?php echo e($transaction->reference); ?></p>
        </div>
        <div>
            <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo e(__('transfers.description_label')); ?>:</span>
            <p class="font-semibold text-gray-900 dark:text-white"><?php echo e($transaction->description ?: __('common.no_description')); ?></p>
        </div>
        
        <!-- Informations du récepteur -->
        <!--[if BLOCK]><![endif]--><?php if(isset($transaction->external_bank_info['recipient_name']) || isset($transaction->external_bank_info['recipient_iban']) || isset($transaction->external_bank_info['recipient_bank']) || isset($transaction->external_bank_info['swift_code']) || isset($transaction->external_bank_info['recipient_country'])): ?>
        <div class="col-span-1 md:col-span-2 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 dark:from-blue-900/20 dark:to-indigo-900/20 backdrop-blur-sm border border-blue-200/30 dark:border-blue-700/30 rounded-xl p-4 mt-4">
            <h4 class="text-sm font-medium text-blue-600 dark:text-blue-400 mb-4 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                <?php echo e(__('common.recipient_info')); ?>

            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php if(isset($transaction->external_bank_info['recipient_name'])): ?>
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo e(__('common.recipient_name')); ?>:</span>
                    <p class="font-semibold text-gray-900 dark:text-white"><?php echo e($transaction->external_bank_info['recipient_name']); ?></p>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <!--[if BLOCK]><![endif]--><?php if(isset($transaction->external_bank_info['recipient_iban'])): ?>
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo e(__('common.recipient_iban')); ?>:</span>
                    <p class="font-mono font-semibold text-gray-900 dark:text-white"><?php echo e($transaction->external_bank_info['recipient_iban']); ?></p>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <!--[if BLOCK]><![endif]--><?php if(isset($transaction->external_bank_info['recipient_bank'])): ?>
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo e(__('common.recipient_bank')); ?>:</span>
                    <p class="font-semibold text-gray-900 dark:text-white"><?php echo e($transaction->external_bank_info['recipient_bank']); ?></p>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <!--[if BLOCK]><![endif]--><?php if(isset($transaction->external_bank_info['swift_code'])): ?>
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">BIC/SWIFT:</span>
                    <p class="font-mono font-semibold text-gray-900 dark:text-white"><?php echo e($transaction->external_bank_info['swift_code']); ?></p>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <!--[if BLOCK]><![endif]--><?php if(isset($transaction->external_bank_info['recipient_country'])): ?>
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo e(__('common.recipient_country')); ?>:</span>
                    <p class="font-semibold text-gray-900 dark:text-white"><?php echo e($transaction->external_bank_info['recipient_country']); ?></p>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    </div>
</div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]--><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/components/transfer/transaction-details.blade.php ENDPATH**/ ?>
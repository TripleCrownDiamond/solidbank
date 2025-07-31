<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['transferStatus', 'showStartButton', 'isTransferBlocked', 'showStepModal' => false]));

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

foreach (array_filter((['transferStatus', 'showStartButton', 'isTransferBlocked', 'showStepModal' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div>

<!--[if BLOCK]><![endif]--><?php if($transferStatus === 'ready'): ?>
    <div class="flex justify-center mb-6">
        <button 
            wire:click="startTransfer" 
            @click="isClicking = true; setTimeout(() => isClicking = false, 3000)"
            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            wire:loading.attr="disabled" 
            wire:target="startTransfer"
        >
        <span wire:loading.remove wire:target="startTransfer">
            <i class="fa-solid fa-play mr-2"></i>
            <?php echo e(__('transfers.start_transfer')); ?>

        </span>
        <span wire:loading wire:target="startTransfer">
            <i class="fa-solid fa-spinner fa-spin mr-2"></i>
            <?php echo e(__('transfers.starting_transfer')); ?>

        </span>
        </button>
    </div>
<?php elseif($transferStatus === 'resumable'): ?>
    <div class="flex justify-center mb-6">
        <button 
            wire:click="startTransfer" 
            @click="isClicking = true; setTimeout(() => isClicking = false, 3000)"
            class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white font-medium rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            wire:loading.attr="disabled" 
            wire:target="startTransfer"
        >
        <span wire:loading.remove wire:target="startTransfer">
            <i class="fa-solid fa-forward mr-2"></i>
            <?php echo e(__('transfers.continue_transfer')); ?>

        </span>
        <span wire:loading wire:target="startTransfer">
            <i class="fa-solid fa-spinner fa-spin mr-2"></i>
            <?php echo e(__('transfers.continuing_transfer')); ?>

        </span>
        </button>
    </div>
<?php elseif($transferStatus === 'starting'): ?>
    <div class="flex justify-center mb-6">
        <button 
            disabled
            class="inline-flex items-center px-6 py-3 bg-blue-600 dark:bg-blue-500 text-white font-medium rounded-lg opacity-75 cursor-not-allowed"
        >
        <i class="fa-solid fa-spinner fa-spin mr-2"></i>
        <?php echo e(__('transfers.starting_transfer')); ?>

        </button>
    </div>
<?php elseif($transferStatus === 'in_progress'): ?>
    <div class="flex justify-center mb-6">
        <div class="inline-flex items-center px-6 py-3 text-green-600 dark:text-green-400 font-medium">
        <i class="fa-solid fa-clock mr-2"></i>
        <?php echo e(__('transfers.transfer_in_progress')); ?>

        </div>
    </div>
<?php elseif($transferStatus === 'blocked'): ?>
    <div class="flex justify-center mb-6">
        <!--[if BLOCK]><![endif]--><?php if(!$showStepModal): ?>
            <button 
                wire:click="$dispatch('show-step-modal')"
                @click="console.log('DEBUG: Bouton Unlock cliqué, showStepModal =', <?php echo \Illuminate\Support\Js::from($showStepModal)->toHtml() ?>);"
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                wire:loading.attr="disabled" 
                wire:target="$dispatch('show-step-modal')"
            >
                <span wire:loading.remove wire:target="$dispatch('show-step-modal')">
                    <i class="fa-solid fa-unlock mr-2"></i>
                    <?php echo e(__('transfers.unlock_transaction')); ?>

                </span>
                <span wire:loading wire:target="$dispatch('show-step-modal')">
                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                    <?php echo e(__('transfers.opening_modal')); ?>

                </span>
            </button>
        <?php else: ?>
            <div class="inline-flex items-center px-6 py-3 text-red-600 dark:text-red-400 font-medium">
                <i class="fa-solid fa-exclamation-triangle mr-2"></i>
                <?php echo e(__('transfers.transfer_blocked')); ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
<?php elseif($transferStatus === 'completed'): ?>
    <div class="flex justify-center mb-6">
        <div class="inline-flex items-center px-6 py-3 text-green-600 dark:text-green-400 font-medium bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
            <i class="fa-solid fa-check-circle mr-2"></i>
            <?php echo e(__('transfers.transfer_submitted_successfully')); ?>

        </div>
    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->

</div><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/components/transfer/start-button.blade.php ENDPATH**/ ?>
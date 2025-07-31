<div>
<!--[if BLOCK]><![endif]--><?php if($showTransferModal): ?>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fa-solid fa-paper-plane mr-2"></i><?php echo e(__('transfers.send_money')); ?>

                    </h3>
                    <button wire:click="closeTransferModal" 
                            class="text-gray-400 hover:text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" 
                            wire:target="closeTransferModal">
                        <span wire:loading.remove wire:target="closeTransferModal">
                            <i class="fa-solid fa-times"></i>
                        </span>
                        <span wire:loading wire:target="closeTransferModal">
                            <i class="fa-solid fa-spinner fa-spin text-black dark:text-white"></i>
                        </span>
                    </button>
                </div>
                
                <!-- Step Progress Indicator -->
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <!--[if BLOCK]><![endif]--><?php for($i = 1; $i <= $maxTransferStep; $i++): ?>
                            <div class="flex items-center <?php echo e($i < $maxTransferStep ? 'flex-1' : ''); ?>">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full <?php echo e($transferStep >= $i ? 'bg-brand-primary text-white' : 'bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400'); ?>">
                                    <!--[if BLOCK]><![endif]--><?php if($transferStep > $i): ?>
                                        <i class="fa-solid fa-check text-sm"></i>
                                    <?php else: ?>
                                        <?php echo e($i); ?>

                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <!--[if BLOCK]><![endif]--><?php if($i < $maxTransferStep): ?>
                                    <div class="flex-1 h-0.5 mx-2 <?php echo e($transferStep > $i ? 'bg-brand-primary' : 'bg-gray-200 dark:bg-gray-600'); ?>"></div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                </div>

                <div class="space-y-4">
                    <!--[if BLOCK]><![endif]--><?php if($transferStep === 1): ?>
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('deposit-management.transfer-step-source', ['sourceType' => $sourceType,'userAccounts' => $userAccounts,'userWallets' => $userWallets,'availableBalance' => $availableBalance,'transferCurrency' => $transferCurrency,'wire:model' => 'selectedSourceId']);

$__html = app('livewire')->mount($__name, $__params, 'lw-224619886-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    <?php elseif($transferStep === 2): ?>
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('deposit-management.transfer-step-recipient', ['sourceType' => $sourceType,'wire:model' => 'recipientName','wire:model.live' => 'cryptoAddress']);

$__html = app('livewire')->mount($__name, $__params, 'lw-224619886-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    <?php elseif($transferStep === 3): ?>
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('deposit-management.transfer-step-amount', ['selectedSourceCurrency' => $transferCurrency,'availableBalance' => $availableBalance,'wire:model' => 'transferAmount','wire:model.live' => 'transferReason']);

$__html = app('livewire')->mount($__name, $__params, 'lw-224619886-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <!-- Actions -->
                    <div class="flex justify-between pt-6">
                        <div class="flex space-x-3">
                            <button type="button" wire:click="closeTransferModal" 
                                    class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md transition-colors"
                                    wire:loading.attr="disabled"
                                    wire:target="closeTransferModal">
                                <?php if (isset($component)) { $__componentOriginal0815f831dc043e6944fc6db7fd582e31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0815f831dc043e6944fc6db7fd582e31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.loader-spinner','data' => ['target' => 'closeTransferModal','text' => '','position' => 'left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('loader-spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['target' => 'closeTransferModal','text' => '','position' => 'left']); ?>
                                    <?php echo e(__('common.cancel')); ?>

                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0815f831dc043e6944fc6db7fd582e31)): ?>
<?php $attributes = $__attributesOriginal0815f831dc043e6944fc6db7fd582e31; ?>
<?php unset($__attributesOriginal0815f831dc043e6944fc6db7fd582e31); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0815f831dc043e6944fc6db7fd582e31)): ?>
<?php $component = $__componentOriginal0815f831dc043e6944fc6db7fd582e31; ?>
<?php unset($__componentOriginal0815f831dc043e6944fc6db7fd582e31); ?>
<?php endif; ?>
                            </button>
                            
                            <!--[if BLOCK]><![endif]--><?php if($transferStep > 1 && $transferStep <= $maxTransferStep): ?>
                                <button type="button" wire:click="previousTransferStepModal" 
                                        class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                        wire:loading.attr="disabled"
                                        wire:target="previousTransferStepModal"
                                        <?php if(!$canGoBack): echo 'disabled'; endif; ?>>
                                    <?php if (isset($component)) { $__componentOriginal0815f831dc043e6944fc6db7fd582e31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0815f831dc043e6944fc6db7fd582e31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.loader-spinner','data' => ['target' => 'previousTransferStepModal','text' => '','position' => 'left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('loader-spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['target' => 'previousTransferStepModal','text' => '','position' => 'left']); ?>
                                        <i class="fa-solid fa-arrow-left mr-2"></i>
                                        <?php echo e(__('common.previous')); ?>

                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0815f831dc043e6944fc6db7fd582e31)): ?>
<?php $attributes = $__attributesOriginal0815f831dc043e6944fc6db7fd582e31; ?>
<?php unset($__attributesOriginal0815f831dc043e6944fc6db7fd582e31); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0815f831dc043e6944fc6db7fd582e31)): ?>
<?php $component = $__componentOriginal0815f831dc043e6944fc6db7fd582e31; ?>
<?php unset($__componentOriginal0815f831dc043e6944fc6db7fd582e31); ?>
<?php endif; ?>
                                </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        
                        <div>
                            <!--[if BLOCK]><![endif]--><?php if($transferStep === 1): ?>
                                <button type="button" 
                                        wire:click="nextTransferStepModal" 
                                        wire:loading.attr="disabled"
                                        wire:target="nextTransferStepModal"
                                        <?php if(!$sourceStepValid): echo 'disabled'; endif; ?>
                                        class="px-6 py-2 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                        id="nextBtn1">
                                    <span wire:loading.remove wire:target="nextTransferStepModal">
                                        <?php echo e(__('common.next')); ?>

                                        <i class="fa-solid fa-arrow-right ml-2"></i>
                                    </span>
                                    <span wire:loading wire:target="nextTransferStepModal" class="flex items-center">
                                        <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                                        <?php echo e(__('common.processing')); ?>

                                    </span>
                                </button>
                            <?php elseif($transferStep === 2): ?>
                                <button type="button" 
                                        wire:click="nextTransferStepModal" 
                                        wire:loading.attr="disabled"
                                        wire:target="nextTransferStepModal,validate-recipient-step"
                                        <?php if(!$recipientStepValid): echo 'disabled'; endif; ?>
                                        class="px-6 py-2 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                        id="nextBtn2">
                                    <span wire:loading.remove wire:target="nextTransferStepModal,validate-recipient-step">
                                        <?php echo e(__('common.next')); ?>

                                        <i class="fa-solid fa-arrow-right ml-2"></i>
                                    </span>
                                    <span wire:loading wire:target="nextTransferStepModal,validate-recipient-step" class="flex items-center">
                                        <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                                        <?php echo e(__('common.processing')); ?>

                                    </span>
                                </button>
                            <?php elseif($transferStep === 3): ?>
                                <button type="button" 
                                        wire:click="confirmTransfer" 
                                        wire:loading.attr="disabled"
                                        wire:target="confirmTransfer"
                                        <?php if(!$amountStepValid || $amountError || empty($transferAmount) || $transferAmount <= 0 || $transferAmount > $availableBalance): echo 'disabled'; endif; ?>
                                        class="px-6 py-2 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                        id="confirmBtn">
                                    <span wire:loading.remove wire:target="confirmTransfer">
                                        <?php echo e(__('common.confirm')); ?>

                                        <i class="fa-solid fa-check ml-2"></i>
                                    </span>
                                    <span wire:loading wire:target="confirmTransfer" class="flex items-center">
                                        <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                                        <?php echo e(__('common.processing')); ?>

                                    </span>
                                </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('redirect-to-transfer-progress', (event) => {
            const transferId = event[0].transferId;
            const locale = document.documentElement.lang || 'fr';
            window.location.href = `/${locale}/transfers/progress/${transferId}`;
        });
    });
</script>

</div><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/livewire/deposit-management/transfer-modal.blade.php ENDPATH**/ ?>
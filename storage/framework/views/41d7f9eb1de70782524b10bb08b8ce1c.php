<div>
    <!-- Source Type Selection -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('transfers.source_type')); ?></label>
        <div class="flex space-x-4">
            <label class="flex items-center">
                <input type="radio" wire:model.live="sourceType" value="account" name="sourceType" class="mr-2 text-brand-primary focus:ring-brand-primary">
                <span class="text-sm text-gray-700 dark:text-gray-300"><?php echo e(__('admin.account')); ?></span>
            </label>
            <!--[if BLOCK]><![endif]--><?php if($cryptoFeaturesEnabled): ?>
                <label class="flex items-center">
                    <input type="radio" wire:model.live="sourceType" value="wallet" name="sourceType" class="mr-2 text-brand-primary focus:ring-brand-primary">
                    <span class="text-sm text-gray-700 dark:text-gray-300"><?php echo e(__('admin.wallet')); ?></span>
                </label>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading wire:target="sourceType" class="flex items-center justify-center py-4 dark:text-white">
        <?php if (isset($component)) { $__componentOriginal0815f831dc043e6944fc6db7fd582e31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0815f831dc043e6944fc6db7fd582e31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.loader-spinner','data' => ['text' => ''.e(__('admin.loading')).'...','position' => 'left','size' => 'md']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('loader-spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['text' => ''.e(__('admin.loading')).'...','position' => 'left','size' => 'md']); ?>
            <?php echo e(__('common.processing')); ?>

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
    </div>

    <!-- Source Selection -->
    <div wire:loading.remove wire:target="sourceType">
        <!--[if BLOCK]><![endif]--><?php if($sourceType === 'account'): ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('transfers.select_account')); ?></label>
                <select wire:model.live="selectedSourceId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                    <option value=""><?php echo e(__('transfers.choose_source')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $userAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($account->id); ?>"><?php echo e($account->account_number); ?> (<?php echo e($account->type ?? __('common.standard')); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedSourceId'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                
               <!--[if BLOCK]><![endif]--><?php if($availableBalance !== null && $availableBalance >= 0): ?>
                    <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            <i class="fa-solid fa-info-circle mr-2"></i>
                            <?php echo e(__('common.available_balance')); ?> 
                            <?php echo e($sourceType === 'account' ? number_format($availableBalance, 2) : number_format($availableBalance, 8)); ?> 
                            <?php echo e($transferCurrency); ?>

                        </p>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        <?php elseif($sourceType === 'wallet'): ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('transfers.select_wallet')); ?></label>
                <select wire:model.live="selectedSourceId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                    <option value=""><?php echo e(__('transfers.choose_source')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $userWallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($wallet->id); ?>"><?php echo e(strtoupper($wallet->coin)); ?> - <?php echo e(substr($wallet->address, 0, 10)); ?>...<?php echo e(substr($wallet->address, -6)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedSourceId'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                
                <!--[if BLOCK]><![endif]--><?php if($availableBalance !== null && $availableBalance >= 0): ?>
                    <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            <i class="fa-solid fa-info-circle mr-2"></i>
                            <?php echo e(__('common.available_balance')); ?> <?php echo e(number_format($availableBalance, 8)); ?> <?php echo e($transferCurrency); ?>

                        </p>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/livewire/deposit-management/transfer-step-source.blade.php ENDPATH**/ ?>
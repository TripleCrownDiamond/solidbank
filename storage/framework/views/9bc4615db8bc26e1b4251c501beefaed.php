<div>
<!-- Withdrawal Modal -->
<!--[if BLOCK]><![endif]--><?php if($showWithdrawalModal): ?>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fa-solid fa-minus mr-2"></i><?php echo e(__('common.withdrawal')); ?>

                    </h3>
                    <button wire:click="closeWithdrawalModal" 
                            class="text-gray-400 hover:text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" 
                            wire:target="closeWithdrawalModal">
                        <span wire:loading.remove wire:target="closeWithdrawalModal">
                            <i class="fa-solid fa-times"></i>
                        </span>
                        <span wire:loading wire:target="closeWithdrawalModal">
                            <i class="fa-solid fa-spinner fa-spin text-black dark:text-white"></i>
                        </span>
                    </button>
                </div>
                
                <form wire:submit.prevent="submitWithdrawal" class="space-y-4">
                    <!-- Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('common.withdrawal_type')); ?></label>
                        <div class="flex space-x-4" wire:key="withdrawal-type-<?php echo e($withdrawalType); ?>">
                            <label class="flex items-center">
                                <input type="radio" wire:model.live="withdrawalType" value="account" name="withdrawalType" class="mr-2 text-brand-primary focus:ring-brand-primary">
                                <span class="text-sm text-gray-700 dark:text-gray-300"><?php echo e(__('admin.account')); ?></span>
                            </label>
                            <!--[if BLOCK]><![endif]--><?php if($cryptoFeaturesEnabled): ?>
                                <label class="flex items-center">
                                    <input type="radio" wire:model.live="withdrawalType" value="wallet" name="withdrawalType" class="mr-2 text-brand-primary focus:ring-brand-primary">
                                    <span class="text-sm text-gray-700 dark:text-gray-300"><?php echo e(__('admin.wallet')); ?></span>
                                </label>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                   <!-- Loading State -->
                   <div wire:loading wire:target="withdrawalType" class="flex items-center justify-center py-4">
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

                    <!-- Account/Wallet Selection -->
                    <div wire:loading.remove wire:target="withdrawalType">
                        <!--[if BLOCK]><![endif]--><?php if($withdrawalType === 'account'): ?>
                            <!-- Admin: Account Number Input -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('common.account_number_label')); ?></label>
                                <input type="text" wire:model.live="accountNumber" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                       placeholder="<?php echo e(__('common.enter_account_number')); ?>">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['accountNumber'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                
                                <!--[if BLOCK]><![endif]--><?php if($detectedUserName && Auth::user()->is_admin): ?>
                                    <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                        <p class="text-sm text-blue-800 dark:text-blue-200">
                                            <i class="fa-solid fa-info-circle mr-2"></i>
                                            <?php echo e(__('common.withdraw_from_account_of')); ?> <strong><?php echo e($detectedUserName); ?></strong>
                                            <!--[if BLOCK]><![endif]--><?php if($availableBalance !== null): ?>
                                                <br><span class="text-xs"><?php echo e(__('common.available_balance')); ?> <?php echo e(number_format($availableBalance, 2)); ?> <?php echo e($currency); ?></span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </p>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php elseif($withdrawalType === 'wallet'): ?>
                            <!-- Admin: Wallet Address Input -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('common.wallet_address')); ?></label>
                                <input type="text" wire:model.live="walletAddress" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                       placeholder="<?php echo e(__('common.enter_wallet_address')); ?>">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['walletAddress'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                
                                <!--[if BLOCK]><![endif]--><?php if($detectedUserName && Auth::user()->is_admin): ?>
                                    <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                        <p class="text-sm text-blue-800 dark:text-blue-200">
                                            <i class="fa-solid fa-info-circle mr-2"></i>
                                            <?php echo e(__('common.withdraw_from_wallet_of')); ?> <strong><?php echo e($detectedUserName); ?></strong>
                                            <!--[if BLOCK]><![endif]--><?php if($availableBalance !== null): ?>
                                                <br><span class="text-xs"><?php echo e(__('common.available_balance')); ?> <?php echo e(number_format($availableBalance, 8)); ?> <?php echo e($currency); ?></span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </p>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!-- Amount Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <?php echo e(__('common.amount')); ?>

                                <!--[if BLOCK]><![endif]--><?php if($currency): ?>
                                    <span class="text-brand-primary font-semibold">(<?php echo e($currency); ?>)</span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </label>
                            <input type="number" step="0.01" min="0" wire:model.live="amount" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                   placeholder="<?php echo e(__('common.enter_amount')); ?>">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            
                            <!--[if BLOCK]><![endif]--><?php if($balanceError): ?>
                                <div class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                    <p class="text-sm text-red-800 dark:text-red-200">
                                        <i class="fa-solid fa-exclamation-triangle mr-2"></i>
                                        <?php echo e($balanceError); ?>

                                    </p>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    <!-- Reason Field (Optional for withdrawals) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(__('common.withdrawal_reason')); ?> <span class="text-gray-500 text-xs">(<?php echo e(__('common.optional')); ?>)</span>
                        </label>
                        <textarea wire:model="reason" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                  placeholder="<?php echo e(__('common.describe_withdrawal_reason')); ?>"></textarea>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-between items-center mt-6">
                        <button type="button" wire:click="closeWithdrawalModal" 
                                class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="closeWithdrawalModal">
                            <?php if (isset($component)) { $__componentOriginal0815f831dc043e6944fc6db7fd582e31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0815f831dc043e6944fc6db7fd582e31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.loader-spinner','data' => ['target' => 'closeWithdrawalModal','text' => '','position' => 'left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('loader-spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['target' => 'closeWithdrawalModal','text' => '','position' => 'left']); ?>
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
                        
                        <button type="submit" 
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="submitWithdrawal"
                                <?php if($balanceError): ?> disabled <?php endif; ?>>
                            <?php if (isset($component)) { $__componentOriginal0815f831dc043e6944fc6db7fd582e31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0815f831dc043e6944fc6db7fd582e31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.loader-spinner','data' => ['target' => 'submitWithdrawal','text' => '','position' => 'left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('loader-spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['target' => 'submitWithdrawal','text' => '','position' => 'left']); ?>
                                <i class="fa-solid fa-minus mr-2"></i><?php echo e(__('common.process_withdrawal')); ?>

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
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/livewire/deposit-management/withdrawal-modal.blade.php ENDPATH**/ ?>
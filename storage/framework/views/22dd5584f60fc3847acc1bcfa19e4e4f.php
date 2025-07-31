<div>
    <!--[if BLOCK]><![endif]--><?php if($showDepositModal): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            <i class="fa-solid fa-plus mr-2"></i><?php echo e(__('common.deposit')); ?>

                        </h3>
                        <button wire:click="closeDepositModal" 
                                class="text-gray-400 hover:text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="closeDepositModal">
                            <span wire:loading.remove wire:target="closeDepositModal">
                                <i class="fa-solid fa-times"></i>
                            </span>
                            <span wire:loading wire:target="closeDepositModal">
                                <i class="fa-solid fa-spinner fa-spin text-black dark:text-white"></i>
                            </span>
                        </button>
                    </div>
                    
                    <form wire:submit.prevent="submitTransaction" class="space-y-4">
                        <!-- Type Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('common.deposit_type')); ?></label>
                            <div class="flex space-x-4" wire:key="deposit-type-<?php echo e($depositType); ?>">
                                <label class="flex items-center">
                                    <input type="radio" wire:model.live="depositType" value="account" name="depositType" class="mr-2 text-brand-primary focus:ring-brand-primary">
                                    <span class="text-sm text-gray-700 dark:text-gray-300"><?php echo e(__('admin.account')); ?></span>
                                </label>
                                <!--[if BLOCK]><![endif]--><?php if($cryptoFeaturesEnabled): ?>
                                    <label class="flex items-center">
                                        <input type="radio" wire:model.live="depositType" value="wallet" name="depositType" class="mr-2 text-brand-primary focus:ring-brand-primary">
                                        <span class="text-sm text-gray-700 dark:text-gray-300"><?php echo e(__('admin.wallet')); ?></span>
                                    </label>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>

                        <!-- Loading State -->
                        <div wire:loading wire:target="depositType" class="flex items-center justify-center py-4">
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
                        <div wire:loading.remove wire:target="depositType">
                            <!--[if BLOCK]><![endif]--><?php if($depositType === 'account'): ?>
                                <!--[if BLOCK]><![endif]--><?php if(Auth::user()->is_admin): ?>
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
                                                    <!--[if BLOCK]><![endif]--><?php if($transactionType === 'deposit'): ?>
                                                        <?php echo e(__('common.deposit_on_account_of')); ?> <strong><?php echo e($detectedUserName); ?></strong>
                                                    <?php else: ?>
                                                        <?php echo e(__('common.withdraw_from_account_of')); ?> <strong><?php echo e($detectedUserName); ?></strong>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <!--[if BLOCK]><![endif]--><?php if($availableBalance !== null): ?>
                                                        <br><span class="text-xs"><?php echo e(__('common.available_balance')); ?> <?php echo e(number_format($availableBalance, 2)); ?> <?php echo e($currency); ?></span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </p>
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                <?php else: ?>
                                    <!-- User: Account Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('admin.select_account')); ?></label>
                                        <select wire:model.live="selectedAccountId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                            <option value=""><?php echo e(__('admin.select_account')); ?></option>
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($account->id); ?>"><?php echo e($account->account_number); ?> (<?php echo e($account->type ?? __('common.standard')); ?>)</option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </select>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedAccountId'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            <!--[if BLOCK]><![endif]--><?php if($depositType === 'wallet'): ?>
                                <!--[if BLOCK]><![endif]--><?php if(Auth::user()->is_admin): ?>
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
                                                    <!--[if BLOCK]><![endif]--><?php if($transactionType === 'deposit'): ?>
                                                        <?php echo e(__('common.deposit_on_wallet_of')); ?> <strong><?php echo e($detectedUserName); ?></strong>
                                                    <?php else: ?>
                                                        <?php echo e(__('common.withdraw_from_wallet_of')); ?> <strong><?php echo e($detectedUserName); ?></strong>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <!--[if BLOCK]><![endif]--><?php if($availableBalance !== null): ?>
                                                        <br><span class="text-xs"><?php echo e(__('common.available_balance')); ?> <?php echo e(number_format($availableBalance, 2)); ?> <?php echo e($currency); ?></span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </p>
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                <?php else: ?>
                                    <!-- User: Wallet Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('admin.select_wallet')); ?></label>
                                        <select wire:model.live="selectedWalletId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                            <option value=""><?php echo e(__('admin.select_wallet')); ?></option>
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($wallet->id); ?>"><?php echo e(strtoupper($wallet->coin)); ?> - <?php echo e(substr($wallet->address, 0, 10)); ?>...<?php echo e(substr($wallet->address, -6)); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </select>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedWalletId'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

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

                        <!-- Reason Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('common.deposit_reason')); ?></label>
                            <textarea wire:model="reason" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                      placeholder="<?php echo e(__('common.describe_deposit_reason')); ?>"></textarea>
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
                            <button type="button" wire:click="closeDepositModal" 
                                    class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled" 
                                    wire:target="closeDepositModal">
                                <?php if (isset($component)) { $__componentOriginal0815f831dc043e6944fc6db7fd582e31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0815f831dc043e6944fc6db7fd582e31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.loader-spinner','data' => ['target' => 'closeDepositModal','text' => '','position' => 'left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('loader-spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['target' => 'closeDepositModal','text' => '','position' => 'left']); ?>
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
                            
                            <button type="button" 
                                    wire:click="submitTransaction"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled" 
                                    wire:target="submitTransaction">
                                <?php if (isset($component)) { $__componentOriginal0815f831dc043e6944fc6db7fd582e31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0815f831dc043e6944fc6db7fd582e31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.loader-spinner','data' => ['target' => 'submitTransaction','text' => '','position' => 'left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('loader-spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['target' => 'submitTransaction','text' => '','position' => 'left']); ?>
                                    <i class="fa-solid fa-plus mr-2"></i><?php echo e(__('actions.add_deposit')); ?>

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
</div><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/livewire/deposit-management/deposit-modal.blade.php ENDPATH**/ ?>
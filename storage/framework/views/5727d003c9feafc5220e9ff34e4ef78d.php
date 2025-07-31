<div class="mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-brand-primary to-brand-accent p-6">
            <h2 class="text-xl font-semibold text-white mb-2">
                <i class="fa-solid fa-exchange-alt mr-2"></i><?php echo e(__('common.actions')); ?>

            </h2>
            <div class="w-16 h-1 bg-gray-200 dark:bg-gray-300 rounded-full"></div>
        </div>
        <div class="p-6">
        <div class="grid grid-cols-1 <?php echo e(Auth::user()->is_admin ? 'md:grid-cols-2' : ''); ?> gap-4">
            <?php
                $userAccount = Auth::user()->account;
                $isAccountInactive = $userAccount && $userAccount->status !== 'ACTIVE';
                $shouldDisableButtons = !Auth::user()->is_admin && $isAccountInactive;
            ?>
            
            <!--[if BLOCK]><![endif]--><?php if(Auth::user()->is_admin): ?>
                <!-- Admin Buttons: Dépôt et Retrait -->
                <button wire:click="openDepositModal" 
                        onclick="console.log('Deposit button clicked');"
                        class="inline-block px-6 py-3 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled" 
                        wire:target="openDepositModal">
                    <?php if (isset($component)) { $__componentOriginal0815f831dc043e6944fc6db7fd582e31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0815f831dc043e6944fc6db7fd582e31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.loader-spinner','data' => ['target' => 'openDepositModal','text' => ''.e(__('common.deposit')).'','position' => 'left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('loader-spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['target' => 'openDepositModal','text' => ''.e(__('common.deposit')).'','position' => 'left']); ?>
                        <i class="fa-solid fa-plus mr-2"></i><?php echo e(__('common.deposit')); ?>

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
                <button wire:click="openWithdrawalModal" 
                        class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled" 
                        wire:target="openWithdrawalModal">
                    <?php if (isset($component)) { $__componentOriginal0815f831dc043e6944fc6db7fd582e31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0815f831dc043e6944fc6db7fd582e31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.loader-spinner','data' => ['target' => 'openWithdrawalModal','text' => ''.e(__('common.withdrawal')).'','position' => 'left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('loader-spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['target' => 'openWithdrawalModal','text' => ''.e(__('common.withdrawal')).'','position' => 'left']); ?>
                        <i class="fa-solid fa-minus mr-2"></i><?php echo e(__('common.withdrawal')); ?>

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
            <?php else: ?>
                <!-- User Buttons: Envoyer de l'argent seulement -->
                <!--[if BLOCK]><![endif]--><?php if($shouldDisableButtons): ?>
                    <!-- Disabled button for inactive accounts -->
                    <div class="relative">
                        <button disabled
                                class="inline-block px-6 py-3 bg-gray-400 text-gray-600 rounded-lg transition-all duration-200 opacity-50 cursor-not-allowed">
                            <i class="fa-solid fa-paper-plane mr-2"></i><?php echo e(__('transfers.send_money')); ?>

                        </button>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                <?php echo e(__('common.account_inactive')); ?>

                            </span>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Active button for active accounts -->
                    <button wire:click="openTransferModal" 
                            class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" 
                            wire:target="openTransferModal">
                        <?php if (isset($component)) { $__componentOriginal0815f831dc043e6944fc6db7fd582e31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0815f831dc043e6944fc6db7fd582e31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.loader-spinner','data' => ['target' => 'openTransferModal','text' => ''.e(__('transfers.send_money')).'','position' => 'left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('loader-spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['target' => 'openTransferModal','text' => ''.e(__('transfers.send_money')).'','position' => 'left']); ?>
                            <i class="fa-solid fa-paper-plane mr-2"></i><?php echo e(__('transfers.send_money')); ?>

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
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        </div>
    </div>
</div>
<?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/livewire/deposit-management/action-buttons.blade.php ENDPATH**/ ?>
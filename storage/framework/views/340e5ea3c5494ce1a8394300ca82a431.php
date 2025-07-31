<!--[if BLOCK]><![endif]--><?php if(isCryptoEnabled()): ?>
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
    <!-- Header -->
    <div class="bg-gradient-to-r from-brand-primary to-brand-accent p-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-white mb-2"><?php echo e(__('common.crypto_wallets')); ?></h2>
                <div class="w-16 h-1 bg-gray-200 dark:bg-gray-300 rounded-full"></div>
            </div>
        </div>
    </div>

    <div class="p-6">

        <!--[if BLOCK]><![endif]--><?php if($wallets->count() > 0): ?>
            <!--[if BLOCK]><![endif]--><?php if($dashboardView): ?>
                <!-- Dashboard View - Horizontal Wallets -->
                <?php if (isset($component)) { $__componentOriginalca271d83fa3209f3a35e31f041366e89 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalca271d83fa3209f3a35e31f041366e89 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.horizontal-wallet-list','data' => ['wallets' => $wallets,'brandColor' => 'brand-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('horizontal-wallet-list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wallets' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($wallets),'brand-color' => 'brand-primary']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalca271d83fa3209f3a35e31f041366e89)): ?>
<?php $attributes = $__attributesOriginalca271d83fa3209f3a35e31f041366e89; ?>
<?php unset($__attributesOriginalca271d83fa3209f3a35e31f041366e89); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalca271d83fa3209f3a35e31f041366e89)): ?>
<?php $component = $__componentOriginalca271d83fa3209f3a35e31f041366e89; ?>
<?php unset($__componentOriginalca271d83fa3209f3a35e31f041366e89); ?>
<?php endif; ?>
            <?php else: ?>
                <!-- Full Page View - Wallet Design -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if (isset($component)) { $__componentOriginal3f21310ba3b2fac0111568d07efcc09f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3f21310ba3b2fac0111568d07efcc09f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.financial-card','data' => ['item' => $wallet,'type' => 'wallet','showDetails' => isset($showWalletDetails[$wallet->id]),'adminView' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('financial-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($wallet),'type' => 'wallet','show-details' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(isset($showWalletDetails[$wallet->id])),'admin-view' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3f21310ba3b2fac0111568d07efcc09f)): ?>
<?php $attributes = $__attributesOriginal3f21310ba3b2fac0111568d07efcc09f; ?>
<?php unset($__attributesOriginal3f21310ba3b2fac0111568d07efcc09f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3f21310ba3b2fac0111568d07efcc09f)): ?>
<?php $component = $__componentOriginal3f21310ba3b2fac0111568d07efcc09f; ?>
<?php unset($__componentOriginal3f21310ba3b2fac0111568d07efcc09f); ?>
<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php else: ?>
            <!-- No Wallets State -->
            <div class="text-center py-12">
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-gray-100 dark:bg-gray-700 mb-6">
                    <i class="fa-solid fa-wallet text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2"><?php echo e(__('common.no_wallets_yet')); ?></h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6"><?php echo e(__('common.no_wallets_description')); ?></p>
               
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]--><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/livewire/user-wallets.blade.php ENDPATH**/ ?>
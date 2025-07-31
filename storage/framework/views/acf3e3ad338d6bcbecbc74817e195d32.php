<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php
        $hasActiveBlock = false;
        if (!Auth::user()->is_admin) {
            $hasActiveBlock = Auth::user()->accounts()->whereHas('activeAccountBlocks')->exists();
        }
        if ($hasActiveBlock) {
            return redirect()->route('dashboard')->with('error', __('common.access_denied'));
        }
    ?>

     <?php $__env->slot('header', null, []); ?> 
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-700 text-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">
                        <i class="fa-solid fa-exchange-alt mr-2"></i><?php echo e(__('common.transactions')); ?>

                    </h1>
                   
                </div>
                <div class="text-right">
                    <p class="text-blue-100 text-sm font-medium">
                        <i class="fa-solid fa-<?php echo e(Auth::user()->is_admin ? 'user-shield' : 'user'); ?> mr-1"></i>
                        <?php echo e(Auth::user()->is_admin ? __('common.admin_space') : __('common.user_space')); ?>

                    </p>
                </div>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if(!Auth::user()->hasVerifiedEmail()): ?>
                <div class="max-w-3xl mx-auto bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-700 p-8 rounded-xl shadow-lg mb-8">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-800 mb-6">
                            <i class="fas fa-envelope-open-text text-2xl text-blue-600 dark:text-blue-300"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-blue-900 dark:text-blue-100 mb-4"><?php echo e(__('common.email_verification_required_title')); ?></h2>
                        <p class="text-blue-800 dark:text-blue-200 mb-6 leading-relaxed">
                            <?php echo e(__('common.email_verification_dashboard_message')); ?>

                        </p>
                        <div class="space-y-4">
                            <form method="POST" action="<?php echo e(route('verification.send', ['locale' => app()->getLocale()])); ?>" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 dark:from-blue-500 dark:to-indigo-500 dark:hover:from-blue-600 dark:hover:to-indigo-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    <?php echo e(__('common.resend_verification_email')); ?>

                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Action Buttons -->
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('deposit-management');

$__html = app('livewire')->mount($__name, $__params, 'lw-868901723-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

                <!-- Transactions List -->
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('transaction-list');

$__html = app('livewire')->mount($__name, $__params, 'lw-868901723-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/dashboard/transactions.blade.php ENDPATH**/ ?>
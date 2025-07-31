<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'icon' => 'fa-solid fa-home', 'showAdminSpace' => true]));

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

foreach (array_filter((['title', 'icon' => 'fa-solid fa-home', 'showAdminSpace' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-700 text-white p-6 rounded-lg shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold mb-2">
                <i class="<?php echo e($icon); ?> mr-2"></i>
                <?php echo e($title); ?>

            </h1>
        </div>
        <?php if($showAdminSpace && Auth::check() && Auth::user()->is_admin): ?>
             <div class="text-right">
                 <p class="text-blue-100 text-sm font-medium">
                     <i class="fa-solid fa-shield-halved mr-1"></i>
                     <?php echo e(__('admin.administrator_space')); ?>

                 </p>
             </div>
         <?php endif; ?>
    </div>
</div><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/components/admin-header.blade.php ENDPATH**/ ?>
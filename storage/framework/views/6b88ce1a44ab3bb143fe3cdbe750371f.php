<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'text' => '',
    'defaultText' => '',
    'size' => 'md',
    'position' => 'left',
    'target' => null,
    'loadingClass' => ''
]));

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

foreach (array_filter(([
    'text' => '',
    'defaultText' => '',
    'size' => 'md',
    'position' => 'left',
    'target' => null,
    'loadingClass' => ''
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<span <?php echo e($attributes->merge(['class' => 'inline-flex items-center'])); ?>>
    <span wire:loading.remove <?php echo e($target ? "wire:target=\"$target\"" : ''); ?>>
        <?php echo e($defaultText ?: $slot); ?>

    </span>
    <span wire:loading <?php echo e($target ? "wire:target=\"$target\"" : ''); ?> class="inline-flex items-center <?php echo e($loadingClass); ?>">
        <!--[if BLOCK]><![endif]--><?php if($position === 'left'): ?>
            <i class="fa-solid fa-spinner fa-spin <?php echo e($size === 'sm' ? 'text-sm' : ($size === 'lg' ? 'text-lg' : '')); ?> mr-2 text-black dark:text-white"></i>
            <span class="text-black dark:text-white"><?php echo e($text ?: $slot); ?></span>
        <?php else: ?>
            <span class="text-black dark:text-white"><?php echo e($text ?: $slot); ?></span>
            <i class="fa-solid fa-spinner fa-spin <?php echo e($size === 'sm' ? 'text-sm' : ($size === 'lg' ? 'text-lg' : '')); ?> ml-2 text-black dark:text-white"></i>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </span>
</span><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/components/loader-spinner.blade.php ENDPATH**/ ?>
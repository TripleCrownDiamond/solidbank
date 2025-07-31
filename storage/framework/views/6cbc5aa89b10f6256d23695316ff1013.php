<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="<?php echo e(session('theme', 'light')); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(config('app.name', 'Laravel')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <!-- Navbar -->
    <?php if (isset($component)) { $__componentOriginal56bae92ec9cbbab5dd894ccd923de386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56bae92ec9cbbab5dd894ccd923de386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.welcome-navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('welcome-navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56bae92ec9cbbab5dd894ccd923de386)): ?>
<?php $attributes = $__attributesOriginal56bae92ec9cbbab5dd894ccd923de386; ?>
<?php unset($__attributesOriginal56bae92ec9cbbab5dd894ccd923de386); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56bae92ec9cbbab5dd894ccd923de386)): ?>
<?php $component = $__componentOriginal56bae92ec9cbbab5dd894ccd923de386; ?>
<?php unset($__componentOriginal56bae92ec9cbbab5dd894ccd923de386); ?>
<?php endif; ?>

    <!-- Contenu principal -->
    <main class="container mx-auto px-4">
        <?php echo e($slot); ?>

    </main>

    <!-- Footer -->
    <?php if (isset($component)) { $__componentOriginaleec10ba14b2bf681e3a47069f3fb3d11 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaleec10ba14b2bf681e3a47069f3fb3d11 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.welcome-footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('welcome-footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaleec10ba14b2bf681e3a47069f3fb3d11)): ?>
<?php $attributes = $__attributesOriginaleec10ba14b2bf681e3a47069f3fb3d11; ?>
<?php unset($__attributesOriginaleec10ba14b2bf681e3a47069f3fb3d11); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaleec10ba14b2bf681e3a47069f3fb3d11)): ?>
<?php $component = $__componentOriginaleec10ba14b2bf681e3a47069f3fb3d11; ?>
<?php unset($__componentOriginaleec10ba14b2bf681e3a47069f3fb3d11); ?>
<?php endif; ?>

     <!-- Alert Manager -->
     <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('alert-manager');

$__html = app('livewire')->mount($__name, $__params, 'lw-2434015544-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
     <!-- Before the closing body tag in welcome.blade.php -->
     <?php echo $__env->yieldPushContent('scripts'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?></body>

</html>
<?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/layouts/welcome.blade.php ENDPATH**/ ?>
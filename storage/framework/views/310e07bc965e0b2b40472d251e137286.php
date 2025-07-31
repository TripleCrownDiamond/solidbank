<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="<?php echo e(getFaviconUrl()); ?>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

        <!-- Styles -->
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>


        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
        <?php if (isset($component)) { $__componentOriginalff9615640ecc9fe720b9f7641382872b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalff9615640ecc9fe720b9f7641382872b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.banner','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalff9615640ecc9fe720b9f7641382872b)): ?>
<?php $attributes = $__attributesOriginalff9615640ecc9fe720b9f7641382872b; ?>
<?php unset($__attributesOriginalff9615640ecc9fe720b9f7641382872b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalff9615640ecc9fe720b9f7641382872b)): ?>
<?php $component = $__componentOriginalff9615640ecc9fe720b9f7641382872b; ?>
<?php unset($__componentOriginalff9615640ecc9fe720b9f7641382872b); ?>
<?php endif; ?>

        <div class="min-h-screen">
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('navigation-menu');

$__html = app('livewire')->mount($__name, $__params, 'lw-1493885422-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

            <!-- Page Heading -->
            <?php if(isset($header)): ?>
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            <!-- Page Content -->
            <main>
                <?php echo e($slot); ?>

            </main>

        <?php echo $__env->yieldPushContent('modals'); ?>
        
        <!-- Alert Manager -->
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('alert-manager');

$__html = app('livewire')->mount($__name, $__params, 'lw-1493885422-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        
        <!-- Custom Scripts -->
        <?php echo $__env->yieldPushContent('scripts'); ?>

        <script>
            document.addEventListener('livewire:init', () => {
                // Gestion de l'événement refresh-page
                Livewire.on('refresh-page', () => {
                    setTimeout(() => {
                        window.location.reload();
                    }, 100);
                });
                
                // Gestion de l'événement refresh-page-delayed
                Livewire.on('refresh-page-delayed', (data) => {
                    const delay = data.delay || 2000;
                    setTimeout(() => {
                        window.location.reload();
                    }, delay);
                });
                
                // Gestion de l'événement csrf-token-expired
                Livewire.on('csrf-token-expired', () => {
                    console.warn('Token CSRF expiré détecté par Livewire');
                    if (window.csrfHandler && window.csrfHandler.refresh) {
                        window.csrfHandler.refresh();
                    }
                });
                
                // Gestion de l'événement copy-to-clipboard
                Livewire.on('copy-to-clipboard', (...args) => {
                    console.log('Arguments reçus:', args);
                    console.log('Nombre d\'arguments:', args.length);
                    
                    // Gérer différents formats d'événement Livewire
                    let textToCopy, message;
                    
                    if (args.length > 0) {
                        const eventData = args[0];
                        console.log('Premier argument:', eventData);
                        
                        if (typeof eventData === 'object' && eventData !== null) {
                            // Si c'est un objet direct
                            textToCopy = eventData.accountNumber;
                            message = eventData.message || 'Copié dans le presse-papiers';
                        } else if (typeof eventData === 'string') {
                            // Si c'est juste une chaîne
                            textToCopy = eventData;
                            message = 'Copié dans le presse-papiers';
                        }
                    }
                    
                    console.log('Texte à copier:', textToCopy);
                    console.log('Message:', message);
                    
                    if (textToCopy) {
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            navigator.clipboard.writeText(textToCopy).then(() => {
                                console.log('Texte copié avec succès:', textToCopy);
                                
                                // Afficher une notification de succès
                                window.Livewire.dispatch('alert', {
                                    type: 'success',
                                    message: message
                                });
                            }).catch(err => {
                                console.error('Erreur lors de la copie:', err);
                                fallbackCopy(textToCopy, message);
                            });
                        } else {
                            // Fallback pour les navigateurs plus anciens
                            fallbackCopy(textToCopy, message);
                        }
                    } else {
                        console.error('Aucun texte à copier trouvé dans l\'événement');
                    }
                });
                
                // Fonction de fallback pour la copie
                function fallbackCopy(text, message) {
                    try {
                        const textArea = document.createElement('textarea');
                        textArea.value = text;
                        textArea.style.position = 'fixed';
                        textArea.style.opacity = '0';
                        document.body.appendChild(textArea);
                        textArea.select();
                        const successful = document.execCommand('copy');
                        document.body.removeChild(textArea);
                        
                        if (successful) {
                            console.log('Texte copié avec fallback:', text);
                            window.Livewire.dispatch('alert', {
                                type: 'success',
                                message: message
                            });
                        } else {
                            throw new Error('Commande de copie échouée');
                        }
                    } catch (err) {
                        console.error('Erreur lors de la copie fallback:', err);
                        window.Livewire.dispatch('alert', {
                            type: 'error',
                            message: 'Erreur lors de la copie'
                        });
                    }
                }
            });
        </script>

    </body>
</html>
<?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/layouts/app.blade.php ENDPATH**/ ?>
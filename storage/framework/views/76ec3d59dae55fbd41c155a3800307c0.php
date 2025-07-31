<?php
    // Récupérer dynamiquement les langues disponibles (même logique que SetLocale middleware)
    $availableLocales = collect(File::directories(base_path('lang')))
        ->map(fn($dir) => basename($dir))
        ->toArray();
    
    // Noms des langues pour l'affichage
    $languageNames = [
        'fr' => 'Français',
        'en' => 'English',
        'es' => 'Español',
        'de' => 'Deutsch',
        'pt' => 'Português',
    ];
    
    $currentLocale = app()->getLocale();
?>

<!-- resources/views/components/shared/language-switcher.blade.php -->
<div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false">
    <button @click="open = !open" type="button"
        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white dark:border-gray-600">
        <i class="fas fa-globe mr-2"></i>
        <?php echo e($languageNames[$currentLocale] ?? strtoupper($currentLocale)); ?>

        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
            aria-hidden="true">
            <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
        </svg>
    </button>
    <div x-show="open" x-transition
        class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:bg-gray-800 dark:ring-gray-700 z-50"
        role="menu" aria-orientation="vertical">
        <div class="py-1" role="menuitem">
            <?php $__currentLoopData = $availableLocales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $localeCode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $languageName = $languageNames[$localeCode] ?? strtoupper($localeCode);
                    $isCurrentLocale = $localeCode === $currentLocale;
                ?>
                <a href="<?php echo e(url('/set-locale/' . $localeCode)); ?>"
                    class="block px-4 py-2 text-sm <?php echo e($isCurrentLocale ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white font-medium' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white'); ?>">
                    <?php if($isCurrentLocale): ?>
                        <i class="fas fa-check mr-2 text-green-500"></i>
                    <?php else: ?>
                        <i class="fas fa-language mr-2 text-gray-400"></i>
                    <?php endif; ?>
                    <?php echo e($languageName); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/components/shared/language-switcher.blade.php ENDPATH**/ ?>
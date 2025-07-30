<nav class="bg-white dark:bg-gray-900 shadow-sm">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
        <a href="<?php echo e(url(app()->getLocale())); ?>" class="flex items-center">
            <img src="<?php echo e(getLogoUrl()); ?>" alt="<?php echo e(getAppName()); ?>" class="h-8">
        </a>
        <?php
            $current = Route::currentRouteName();
        ?>
        <!-- Menu Principal (Visible sur Desktop) -->
        <div class="hidden md:flex space-x-6 items-center">
            <a href="<?php echo e(url(app()->getLocale())); ?>"
                class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white <?php echo e($current === 'home' ? 'font-bold text-indigo-600 border-b-2 border-indigo-600' : ''); ?>"><?php echo e(__('nav.home')); ?></a>
            <a href="<?php echo e(route('services', ['locale' => app()->getLocale()])); ?>"
                class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white <?php echo e($current === 'services' ? 'font-bold text-indigo-600 border-b-2 border-indigo-600' : ''); ?>"><?php echo e(__('common.services')); ?></a>
            <a href="<?php echo e(route('loan-request', ['locale' => app()->getLocale()])); ?>"
                class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white <?php echo e($current === 'loan-request' ? 'font-bold text-indigo-600 border-b-2 border-indigo-600' : ''); ?>"><?php echo e(__('loan.request_loan')); ?></a>
            <?php if(isCryptoEnabled()): ?>
            <a href="<?php echo e(route('crypto-refund', ['locale' => app()->getLocale()])); ?>"
                class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white <?php echo e($current === 'crypto-refund' ? 'font-bold text-indigo-600 border-b-2 border-indigo-600' : ''); ?>"><?php echo e(__('common.crypto_refund')); ?></a>
            <?php endif; ?>
            <a href="<?php echo e(route('contact', ['locale' => app()->getLocale()])); ?>"
                class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white <?php echo e($current === 'contact' ? 'font-bold text-indigo-600 border-b-2 border-indigo-600' : ''); ?>"><?php echo e(__('common.contact')); ?></a>
        </div>

        <!-- Auth Buttons + Composants Partagés -->
        <div class="hidden md:flex items-center space-x-4">
            <!-- Auth Buttons -->
            <div class="flex items-center space-x-4">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard', ['locale' => app()->getLocale()])); ?>"
                        class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"><?php echo e(__('nav.dashboard')); ?></a>
                <?php else: ?>
                    <a href="<?php echo e(route('locale.login', ['locale' => app()->getLocale()])); ?>" class="text-sm text-gray-700 dark:text-gray-500 underline"><?php echo e(__('auth.login')); ?></a>
                <a href="<?php echo e(route('locale.register', ['locale' => app()->getLocale()])); ?>" class="ml-4 inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary-hover transition text-sm"><?php echo e(__('auth.register')); ?></a>
                <?php endif; ?>
            </div>

            <!-- Language Switcher -->
            <?php echo $__env->make('components.shared.language-switcher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Theme Toggle -->
            <?php echo $__env->make('components.shared.theme-toggle', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <!-- Mobile Menu Toggle -->
        <button id="mobile-menu-toggle"
            class="md:hidden p-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg id="hamburger-icon" class="h-6 w-6 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
            <svg id="close-icon" class="h-6 w-6 text-gray-700 dark:text-gray-300 hidden"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden bg-white dark:bg-gray-800 p-4">
        <div class="flex flex-col gap-4 items-center justify-center">
            <a href="<?php echo e(url(app()->getLocale())); ?>"
                class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center"><?php echo e(__('nav.home')); ?></a>
            <a href="<?php echo e(route('services', ['locale' => app()->getLocale()])); ?>"
                class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center"><?php echo e(__('common.services')); ?></a>
            <a href="<?php echo e(route('loan-request', ['locale' => app()->getLocale()])); ?>"
                class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center"><?php echo e(__('loan.request_loan')); ?></a>
            <?php if(isCryptoEnabled()): ?>
            <a href="<?php echo e(route('crypto-refund', ['locale' => app()->getLocale()])); ?>"
                class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center"><?php echo e(__('common.crypto_refund')); ?></a>
            <?php endif; ?>
            <a href="<?php echo e(route('contact', ['locale' => app()->getLocale()])); ?>"
                class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center"><?php echo e(__('common.contact')); ?></a>
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('dashboard', ['locale' => app()->getLocale()])); ?>"
                    class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center"><?php echo e(__('nav.dashboard')); ?></a>
            <?php else: ?>
                <a href="<?php echo e(route('locale.login', ['locale' => app()->getLocale()])); ?>"
                    class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center"><?php echo e(__('nav.login')); ?></a>
                <a href="<?php echo e(route('locale.register', ['locale' => app()->getLocale()])); ?>"
                    class="block w-full bg-brand-primary text-white px-4 py-2 rounded hover:bg-brand-primary-hover text-center"><?php echo e(__('nav.register')); ?></a>
            <?php endif; ?>

            <!-- Language Switcher for Mobile -->
            <?php echo $__env->make('components.shared.language-switcher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Theme Toggle for Mobile -->
            <?php echo $__env->make('components.shared.theme-toggle', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</nav>
<?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/components/welcome-navbar.blade.php ENDPATH**/ ?>
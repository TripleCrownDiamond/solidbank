<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php if (isset($component)) { $__componentOriginalf7b62739b7076c0563d3ad4515ad2917 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf7b62739b7076c0563d3ad4515ad2917 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.authentication-card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('authentication-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
         <?php $__env->slot('logo', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal1a590bee94ab2d9c08b342367154fca0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1a590bee94ab2d9c08b342367154fca0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.authentication-card-logo','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('authentication-card-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1a590bee94ab2d9c08b342367154fca0)): ?>
<?php $attributes = $__attributesOriginal1a590bee94ab2d9c08b342367154fca0; ?>
<?php unset($__attributesOriginal1a590bee94ab2d9c08b342367154fca0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1a590bee94ab2d9c08b342367154fca0)): ?>
<?php $component = $__componentOriginal1a590bee94ab2d9c08b342367154fca0; ?>
<?php unset($__componentOriginal1a590bee94ab2d9c08b342367154fca0); ?>
<?php endif; ?>
         <?php $__env->endSlot(); ?>

        <div>
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e(__('login.title')); ?></h2>
                <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e(__('login.subtitle')); ?></p>
            </div>

            <!-- Validation Errors -->
            <?php if($errors->any()): ?>
                <div class="mb-4">
                    <div class="font-medium text-red-600 dark:text-red-400">
                        <?php echo e(__('Whoops! Something went wrong.')); ?>

                    </div>

                    <ul class="mt-3 list-disc list-inside text-sm text-red-600 dark:text-red-400">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="<?php echo e(route('locale.login.store', app()->getLocale())); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white">
                        <?php echo e(__('login.email')); ?>

                    </label>
                    <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-primary focus:ring-brand-primary <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                    required autofocus autocomplete="username" />
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-900 dark:text-white">
                        <?php echo e(__('login.password')); ?>

                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 pr-10 focus:border-brand-primary focus:ring-brand-primary <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                    required autocomplete="current-password" />
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
                        >
                            <span id="password-toggle-text" class="text-sm text-brand-primary hover:text-brand-primary-hover dark:text-brand-primary dark:hover:text-brand-primary-hover">
                                <?php echo e(__('login.show')); ?>

                            </span>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Remember Me -->
                <div class="block">
                    <label for="remember" class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>

                        class="rounded border-gray-300 text-brand-primary shadow-sm focus:ring-brand-primary dark:bg-gray-900 dark:border-gray-700 dark:checked:bg-brand-primary dark:checked:border-brand-primary" />
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('login.remember_me')); ?></span>
                    </label>
                </div>

                <!-- Forgot Password & Login Button -->
                <div class="flex items-center justify-between mt-4">
                    <?php if(Route::has('locale.password.request')): ?>
                        <a class="underline text-sm text-brand-primary hover:text-brand-primary-hover dark:text-brand-primary dark:hover:text-brand-primary-hover rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary dark:focus:ring-offset-gray-800" href="<?php echo e(route('locale.password.request', app()->getLocale())); ?>">
                            <?php echo e(__('login.forgot_password')); ?>

                        </a>
                    <?php endif; ?>

                    <button type="submit" 
                        class="px-4 py-2 bg-brand-primary hover:bg-brand-primary-hover dark:bg-brand-primary dark:hover:bg-brand-primary-hover text-white rounded-md transition flex items-center">
                        <?php echo e(__('login.log_in')); ?>

                    </button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <?php echo e(__('login.no_account')); ?>

                    <a href="<?php echo e(route('locale.register', ['locale' => app()->getLocale()])); ?>" class="text-brand-primary hover:text-brand-primary-hover dark:text-brand-primary dark:hover:text-brand-primary-hover">
                        <?php echo e(__('login.register_now')); ?>

                    </a>
                </p>
            </div>
        </div>

        <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleText = document.getElementById('password-toggle-text');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleText.textContent = '<?php echo e(__('login.hide')); ?>';
            } else {
                passwordField.type = 'password';
                toggleText.textContent = '<?php echo e(__('login.show')); ?>';
            }
        }
        </script>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf7b62739b7076c0563d3ad4515ad2917)): ?>
<?php $attributes = $__attributesOriginalf7b62739b7076c0563d3ad4515ad2917; ?>
<?php unset($__attributesOriginalf7b62739b7076c0563d3ad4515ad2917); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf7b62739b7076c0563d3ad4515ad2917)): ?>
<?php $component = $__componentOriginalf7b62739b7076c0563d3ad4515ad2917; ?>
<?php unset($__componentOriginalf7b62739b7076c0563d3ad4515ad2917); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/auth/login.blade.php ENDPATH**/ ?>
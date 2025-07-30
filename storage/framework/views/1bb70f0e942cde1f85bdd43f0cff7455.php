<div>
    <!--[if BLOCK]><![endif]--><?php if($message): ?>
        <div class="mb-4 p-4 rounded-lg animate-fade-in <?php echo e($messageType === 'success' ? 'bg-white/95 text-green-800 border-2 border-green-400 shadow-lg backdrop-blur-sm' : 'bg-white/95 text-red-800 border-2 border-red-400 shadow-lg backdrop-blur-sm'); ?>">
            <div class="flex items-center">
                <!--[if BLOCK]><![endif]--><?php if($messageType === 'success'): ?>
                    <svg class="w-6 h-6 mr-3 text-green-600 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                <?php else: ?>
                    <svg class="w-6 h-6 mr-3 text-red-600 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <span class="font-semibold"><?php echo e($message); ?></span>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if(!$isSubscribed): ?>
        <form wire:submit="subscribe" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input 
                    type="email" 
                    wire:model="email"
                    placeholder="<?php echo e(__('common.enter_email')); ?>"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brand-primary focus:border-transparent text-gray-900 placeholder-gray-500"
                    required
                >
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <button 
                type="submit" 
                class="px-6 py-3 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors duration-200 font-medium whitespace-nowrap"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
            >
                <?php echo e(__('common.subscribe')); ?>

            </button>
        </form>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/livewire/newsletter-subscription.blade.php ENDPATH**/ ?>
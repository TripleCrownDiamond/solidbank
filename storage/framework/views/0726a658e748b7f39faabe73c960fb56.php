<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

    <!-- Header -->
    <div class="bg-gradient-to-r from-brand-primary to-brand-accent p-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-white mb-2"><?php echo e(__('common.bank_cards')); ?></h2>
                <div class="w-16 h-1 bg-gray-200 dark:bg-gray-300 rounded-full"></div>
            </div>
        </div>
    </div>

    <div class="p-6">
        <!--[if BLOCK]><![endif]--><?php if($cards->count() > 0): ?>
            <!--[if BLOCK]><![endif]--><?php if($dashboardView): ?>
                <!-- Dashboard View - Horizontal Cards -->
                <?php if (isset($component)) { $__componentOriginal6cedd6112c7a60f1a29a1a9068c46eab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6cedd6112c7a60f1a29a1a9068c46eab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.horizontal-card-list','data' => ['cards' => $cards,'brandColor' => 'brand-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('horizontal-card-list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['cards' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cards),'brand-color' => 'brand-primary']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6cedd6112c7a60f1a29a1a9068c46eab)): ?>
<?php $attributes = $__attributesOriginal6cedd6112c7a60f1a29a1a9068c46eab; ?>
<?php unset($__attributesOriginal6cedd6112c7a60f1a29a1a9068c46eab); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6cedd6112c7a60f1a29a1a9068c46eab)): ?>
<?php $component = $__componentOriginal6cedd6112c7a60f1a29a1a9068c46eab; ?>
<?php unset($__componentOriginal6cedd6112c7a60f1a29a1a9068c46eab); ?>
<?php endif; ?>
            <?php else: ?>
                <!-- Full Page View - Card Design -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if (isset($component)) { $__componentOriginal3f21310ba3b2fac0111568d07efcc09f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3f21310ba3b2fac0111568d07efcc09f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.financial-card','data' => ['item' => $card,'type' => 'card','showDetails' => isset($showCardDetails[$card->id]),'adminView' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('financial-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($card),'type' => 'card','show-details' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(isset($showCardDetails[$card->id])),'admin-view' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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

            <!--[if BLOCK]><![endif]--><?php if($cards->count() > 0): ?>
                <!-- Request Additional Card Button -->
                <div class="mt-6 text-center">
                    <!--[if BLOCK]><![endif]--><?php if($this->hasInactiveAccounts()): ?>
                         <button disabled class="inline-block px-6 py-3 bg-gray-400 text-gray-600 rounded-lg cursor-not-allowed opacity-50">
                             <i class="fa-solid fa-ban mr-2"></i><?php echo e(__('common.account_inactive')); ?>

                         </button>
                     <?php else: ?>
                         <button wire:click="requestCard" wire:loading.attr="disabled" wire:target="requestCard" class="inline-block px-6 py-3 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-lg transition-all duration-200 disabled:opacity-50">
                             <span wire:loading.remove wire:target="requestCard">
                                 <i class="fa-solid fa-plus mr-2"></i><?php echo e(__('common.request_additional_card')); ?>

                             </span>
                             <span wire:loading wire:target="requestCard">
                                 <i class="fa-solid fa-spinner fa-spin mr-2"></i><?php echo e(__('common.loading')); ?>...
                             </span>
                         </button>
                     <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->


        <?php else: ?>
            <!-- No Cards State -->
            <div class="text-center py-12">
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-gray-100 dark:bg-gray-700 mb-6">
                    <i class="fa-solid fa-credit-card text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2"><?php echo e(__('common.no_cards_yet')); ?></h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6"><?php echo e(__('common.no_cards_description')); ?></p>
                <!--[if BLOCK]><![endif]--><?php if($this->hasInactiveAccounts()): ?>
                    <button disabled class="inline-block px-6 py-3 bg-gray-400 text-gray-600 rounded-lg cursor-not-allowed opacity-50">
                        <i class="fa-solid fa-ban mr-2"></i><?php echo e(__('common.account_inactive')); ?>

                    </button>
                    <p class="text-red-600 dark:text-red-400 mt-3 text-sm"><?php echo e(__('common.account_inactive_message')); ?></p>
                <?php else: ?>
                    <button wire:click="requestCard" wire:loading.attr="disabled" wire:target="requestCard" class="inline-block px-6 py-3 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-lg transition-all duration-200 disabled:opacity-50">
                        <span wire:loading.remove wire:target="requestCard">
                            <i class="fa-solid fa-plus mr-2"></i><?php echo e(__('common.request_first_card')); ?>

                        </span>
                        <span wire:loading wire:target="requestCard">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i><?php echo e(__('common.loading')); ?>...
                        </span>
                    </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Card Requests Section -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                <i class="fa-solid fa-clock mr-2 text-brand-primary"></i><?php echo e(__('common.card_requests')); ?>

            </h3>
            <!--[if BLOCK]><![endif]--><?php if($cardRequests->count() > 0): ?>
                <div class="space-y-4">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $cardRequests->take($dashboardView ? 2 : $cardRequests->count()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                        <i class="fa-solid fa-credit-card text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100"><?php echo e(ucfirst($request->card_type)); ?> <?php echo e(__('common.card')); ?></h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('common.requested_on')); ?> <?php echo e($request->created_at->format('d/m/Y H:i')); ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                        <?php if($request->status === 'PENDING'): ?> bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        <?php elseif($request->status === 'APPROVED'): ?> bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        <?php else: ?> bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        <?php endif; ?>">
                                        <?php echo e(__('common.' . $request->status_lower)); ?>

                                    </span>
                                    <!--[if BLOCK]><![endif]--><?php if($request->status === 'PENDING'): ?>
                                        <button 
                                            wire:click="deleteCardRequest(<?php echo e($request->id); ?>)"
                                            wire:confirm="<?php echo e(__('messages.confirm_delete_card_request')); ?>"
                                            wire:loading.attr="disabled"
                                            wire:target="deleteCardRequest(<?php echo e($request->id); ?>)"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 disabled:opacity-50"
                                            title="<?php echo e(__('common.delete_request')); ?>">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <!--[if BLOCK]><![endif]--><?php if($dashboardView && $cardRequests->count() > 2): ?>
                        <div class="text-center mt-4">
                            <a href="<?php echo e(route('user.cards', ['locale' => app()->getLocale()])); ?>" class="text-brand-primary hover:text-brand-primary/80 text-sm font-medium">
                                <?php echo e(__('common.view_all_requests')); ?> (<?php echo e($cardRequests->count()); ?>)
                                <i class="fa-solid fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                        <i class="fa-solid fa-clock text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400"><?php echo e(__('common.no_card_requests')); ?></p>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    <!-- Request Card Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showRequestCardModal): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" 
             wire:ignore.self>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4" wire:click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100"><?php echo e(__('common.request_new_card')); ?></h3>
                    <button wire:click="closeModal()" class="text-gray-400 hover:text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled" wire:target="closeModal">
                        <span wire:loading.remove wire:target="closeModal">
                            <i class="fa-solid fa-times"></i>
                        </span>
                        <span wire:loading wire:target="closeModal">
                            <i class="fa-solid fa-spinner fa-spin text-gray-800 dark:text-white"></i>
                        </span>
                    </button>
                </div>
                    
                    <form wire:submit.prevent="submitCardRequest">
                        <div class="mb-4">
                            <label for="cardType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <?php echo e(__('common.card_type')); ?> *
                            </label>
                            <select 
                                wire:model="cardType" 
                                id="cardType" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-brand-primary focus:border-brand-primary dark:bg-gray-700 dark:text-gray-100"
                            >
                                <option value=""><?php echo e(__('common.select_card_type')); ?></option>
                                <option value="VISA">VISA</option>
                                <option value="MASTERCARD">MASTERCARD</option>
                                <option value="AMERICAN_EXPRESS">AMERICAN EXPRESS</option>
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['cardType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                <span class="text-red-500 text-sm"><?php echo e($message); ?></span> 
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        
                        <div class="mb-4">
                            <label for="phoneNumber" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <?php echo e(__('common.phone_number')); ?> *
                            </label>
                            <input 
                                type="tel" 
                                wire:model="phoneNumber" 
                                id="phoneNumber" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-brand-primary focus:border-brand-primary dark:bg-gray-700 dark:text-gray-100"
                                placeholder="<?php echo e(__('common.phone_number_placeholder')); ?>"
                            >
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['phoneNumber'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                <span class="text-red-500 text-sm"><?php echo e($message); ?></span> 
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div class="mb-4">
                            <label for="requestMessage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <?php echo e(__('common.request_message')); ?>

                            </label>
                            <textarea 
                                wire:model="requestMessage" 
                                id="requestMessage" 
                                rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-brand-primary focus:border-brand-primary dark:bg-gray-700 dark:text-gray-100"
                                placeholder="<?php echo e(__('common.request_message_placeholder')); ?>"
                            ></textarea>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['requestMessage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                <span class="text-red-500 text-sm"><?php echo e($message); ?></span> 
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" wire:click="closeModal()" wire:loading.attr="disabled" wire:target="submitCardRequest" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md transition-colors duration-200 disabled:opacity-50">
                                <?php echo e(__('common.cancel')); ?>

                            </button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="submitCardRequest" class="px-4 py-2 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-md transition-colors duration-200 disabled:opacity-50">
                                <span wire:loading.remove wire:target="submitCardRequest">
                                    <?php echo e(__('common.send_request')); ?>

                                </span>
                                <span wire:loading wire:target="submitCardRequest">
                                    <i class="fa-solid fa-spinner fa-spin mr-2"></i><?php echo e(__('common.sending')); ?>...
                                </span>
                            </button>
                        </div>
                    </form>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Recharge Card Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showRechargeModal): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" 
             wire:ignore.self>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4" wire:click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100"><?php echo e(__('common.recharge_card')); ?></h3>
                    <button wire:click="closeRechargeModal()" class="text-gray-400 hover:text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled" wire:target="closeRechargeModal">
                        <span wire:loading.remove wire:target="closeRechargeModal">
                            <i class="fa-solid fa-times"></i>
                        </span>
                        <span wire:loading wire:target="closeRechargeModal">
                            <i class="fa-solid fa-spinner fa-spin text-gray-800 dark:text-white"></i>
                        </span>
                    </button>
                </div>
                    
                <form wire:submit.prevent="rechargeCard">
                    <div class="mb-4">
                        <label for="rechargeAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(__('common.amount')); ?> *
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                wire:model="rechargeAmount" 
                                id="rechargeAmount" 
                                step="0.01"
                                min="1"
                                max="999999.99"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-brand-primary focus:border-brand-primary dark:bg-gray-700 dark:text-gray-100"
                                placeholder="<?php echo e(__('common.enter_amount')); ?>"
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 text-sm">€</span>
                            </div>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['rechargeAmount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                            <span class="text-red-500 text-sm"><?php echo e($message); ?></span> 
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    
                    <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="flex items-center text-blue-800 dark:text-blue-200">
                            <i class="fa-solid fa-info-circle mr-2"></i>
                            <span class="text-sm"><?php echo e(__('common.recharge_info')); ?></span>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" wire:click="closeRechargeModal()" wire:loading.attr="disabled" wire:target="rechargeCard" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md transition-colors duration-200 disabled:opacity-50">
                            <?php echo e(__('common.cancel')); ?>

                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="rechargeCard" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors duration-200 disabled:opacity-50">
                            <span wire:loading.remove wire:target="rechargeCard">
                                <i class="fa-solid fa-plus mr-2"></i><?php echo e(__('common.recharge')); ?>

                            </span>
                            <span wire:loading wire:target="rechargeCard">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i><?php echo e(__('common.recharging')); ?>...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/livewire/user-bank-cards.blade.php ENDPATH**/ ?>
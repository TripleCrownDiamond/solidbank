<div class="w-full bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="bg-gradient-to-r from-brand-primary to-brand-accent p-6">
        <h2 class="text-xl font-semibold dark:text-white mb-2"><?php echo e(__('common.latest_inactive_accounts')); ?></h2>
        <div class="w-16 h-1 bg-gray-200 dark:bg-gray-300 rounded-full"></div>
    </div>

    <div class="p-6">
        <!-- AlertManager for messages -->
        
        <!--[if BLOCK]><![endif]--><?php if($inactiveAccounts->isEmpty()): ?>
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 bg-brand-secondary/10 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-users text-2xl text-brand-secondary"></i>
                </div>
                <p class="text-brand-secondary font-medium"><?php echo e(__('common.no_inactive_accounts_found')); ?></p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('common.account_number')); ?></th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('common.username')); ?></th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('common.registration_date')); ?></th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('common.status')); ?></th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('common.actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $inactiveAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr wire:key="account-<?php echo e($account->id); ?>-<?php echo e($account->updated_at->timestamp); ?>" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 bg-brand-accent rounded-full"></div>
                                        <span data-account-number="<?php echo e($account->account_number); ?>" class="font-mono"><?php echo e($account->account_number); ?></span>
                                        <button x-data="{ copied: false }" 
                                                x-on:click="navigator.clipboard.writeText('<?php echo e($account->account_number); ?>').then(() => { copied = true; setTimeout(() => copied = false, 2000); })"
                                                title="<?php echo e(__('common.copy_account_number')); ?>"
                                                class="text-brand-secondary hover:text-brand-primary dark:text-gray-400 dark:hover:text-brand-accent transition-colors duration-200">
                                            <i class="fa-solid fa-copy w-4 h-4" x-show="!copied"></i>
                                            <i class="fa-solid fa-check w-4 h-4 text-green-500" x-show="copied" x-cloak></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 font-medium"><?php echo e($account->user->name); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-brand-secondary dark:text-gray-400"><?php echo e($account->user->created_at->diffForHumans()); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm" wire:key="status-<?php echo e($account->id); ?>">
                                    <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-brand-warning/10 text-brand-warning border border-brand-warning/20" wire:ignore.self>
                                        <i class="fa-solid fa-clock w-3 h-3"></i>
                                        <span><?php echo e(__('common.inactive')); ?></span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" wire:key="actions-<?php echo e($account->id); ?>">
                                    <div class="flex justify-end space-x-2">
                                        <button wire:click="activateAccount(<?php echo e($account->id); ?>)" wire:confirm="<?php echo e(__('common.confirm_activate_account_text')); ?>" title="<?php echo e(__('common.activate')); ?>" class="p-2 rounded-lg bg-brand-success/10 text-brand-success hover:bg-brand-success hover:text-white transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled" wire:target="activateAccount(<?php echo e($account->id); ?>)" wire:key="activate-btn-<?php echo e($account->id); ?>">
                                            <div wire:loading.remove wire:target="activateAccount(<?php echo e($account->id); ?>)">
                                                <i class="fa-solid fa-check-circle w-4 h-4"></i>
                                            </div>
                                            <div wire:loading wire:target="activateAccount(<?php echo e($account->id); ?>)" class="animate-spin">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </button>
                                        <button wire:click="suspendAccount(<?php echo e($account->id); ?>)" title="<?php echo e(__('common.suspend')); ?>" class="p-2 rounded-lg bg-brand-warning/10 text-brand-warning hover:bg-brand-warning hover:text-white transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled" wire:target="suspendAccount(<?php echo e($account->id); ?>)" wire:key="suspend-btn-<?php echo e($account->id); ?>">
                                            <div wire:loading.remove wire:target="suspendAccount(<?php echo e($account->id); ?>)">
                                                <i class="fa-solid fa-pause-circle w-4 h-4"></i>
                                            </div>
                                            <div wire:loading wire:target="suspendAccount(<?php echo e($account->id); ?>)" class="animate-spin">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </button>
                                        <button wire:click="deleteAccount(<?php echo e($account->id); ?>)" wire:confirm="<?php echo e(__('messages.confirm_delete_account')); ?>" title="<?php echo e(__('common.delete')); ?>" class="p-2 rounded-lg bg-brand-error/10 text-brand-error hover:bg-brand-error hover:text-white transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled" wire:target="deleteAccount(<?php echo e($account->id); ?>)" wire:key="delete-btn-<?php echo e($account->id); ?>">
                                            <div wire:loading.remove wire:target="deleteAccount(<?php echo e($account->id); ?>)">
                                                <i class="fa-solid fa-trash-can w-4 h-4"></i>
                                            </div>
                                            <div wire:loading wire:target="deleteAccount(<?php echo e($account->id); ?>)" class="animate-spin">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <?php echo e($inactiveAccounts->links()); ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!-- Suspension Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showSuspensionModal): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo e(__('admin.suspend_user')); ?></h3>
                        <button wire:click="cancelSuspension" class="text-gray-400 hover:text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled" wire:target="cancelSuspension">
                            <span wire:loading.remove wire:target="cancelSuspension">
                                <i class="fa-solid fa-times"></i>
                            </span>
                            <span wire:loading wire:target="cancelSuspension">
                                <i class="fa-solid fa-spinner fa-spin text-gray-800 dark:text-white"></i>
                            </span>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('admin.suspension_reason')); ?></label>
                            <textarea wire:model="suspensionReason" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100 <?php $__errorArgs = ['suspensionReason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      rows="3" 
                                      placeholder="<?php echo e(__('admin.enter_suspension_reason')); ?>"></textarea>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['suspensionReason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('admin.suspension_instructions')); ?></label>
                            <textarea wire:model="suspensionInstructions" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100 <?php $__errorArgs = ['suspensionInstructions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      rows="3" 
                                      placeholder="<?php echo e(__('admin.enter_suspension_instructions')); ?>"></textarea>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['suspensionInstructions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button wire:click="cancelSuspension" 
                                class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                            <?php echo e(__('common.cancel')); ?>

                        </button>
                        <button wire:click="confirmSuspension" 
                                class="px-4 py-2 bg-brand-warning text-white rounded-lg hover:bg-brand-warning/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="confirmSuspension">
                            <span wire:loading.remove wire:target="confirmSuspension"><?php echo e(__('admin.confirm_suspension')); ?></span>
                            <span wire:loading wire:target="confirmSuspension">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i><?php echo e(__('admin.suspending')); ?>

                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Modal RIB Manuel -->
    <!--[if BLOCK]><![endif]--><?php if($showRibModal): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <?php echo e(__('admin.manual_rib_entry')); ?>

                    </h3>
                    <button wire:click="closeRibModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    <?php echo e(__('admin.manual_rib_description')); ?>

                </p>
                
                <div class="space-y-4">
                    <div>
                        <label for="ribIban" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(__('admin.iban_label')); ?>

                        </label>
                        <input type="text" 
                               wire:model="ribIban"
                               id="ribIban"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                               placeholder="<?php echo e(__('admin.iban_placeholder')); ?>">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['ribIban'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    
                    <div>
                        <label for="ribSwift" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(__('admin.swift_label')); ?>

                        </label>
                        <input type="text" 
                               wire:model="ribSwift"
                               id="ribSwift"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                               placeholder="<?php echo e(__('admin.swift_placeholder')); ?>">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['ribSwift'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    
                    <div>
                        <label for="ribBankName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(__('admin.bank_name_label')); ?>

                        </label>
                        <input type="text" 
                               wire:model="ribBankName"
                               id="ribBankName"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                               placeholder="<?php echo e(__('admin.bank_name_placeholder')); ?>">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['ribBankName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button wire:click="closeRibModal" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                        <?php echo e(__('common.cancel')); ?>

                    </button>
                    <button wire:click="processActivateAccountWithManualRib"
                            class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" 
                            wire:target="processActivateAccountWithManualRib">
                        <span wire:loading.remove wire:target="processActivateAccountWithManualRib">
                            <?php echo e(__('admin.activate_with_rib')); ?>

                        </span>
                        <span wire:loading wire:target="processActivateAccountWithManualRib">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i><?php echo e(__('admin.activating')); ?>

                        </span>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

</div>
<?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/livewire/latest-inactive-accounts.blade.php ENDPATH**/ ?>
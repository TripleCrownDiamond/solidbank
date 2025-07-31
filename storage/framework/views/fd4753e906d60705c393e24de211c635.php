<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
    <!-- Header -->
    <div class="bg-gradient-to-r from-brand-primary to-brand-accent p-6">
        <h2 class="text-xl font-semibold dark:text-white mb-2"><?php echo e(__('admin.user_details')); ?></h2>
        <div class="w-16 h-1 bg-gray-200 dark:bg-gray-300 rounded-full"></div>
    </div>

    <div class="p-6">

        <!--[if BLOCK]><![endif]--><?php if($user): ?>

            <!-- User Profile Section -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                <!-- Profile Photo & Basic Info -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-center">
                        <div class="w-24 h-24 mx-auto mb-4 bg-brand-primary/10 rounded-full flex items-center justify-center">
                            <!--[if BLOCK]><![endif]--><?php if($user->profile_photo_url): ?>
                                <img src="<?php echo e($user->profile_photo_url); ?>" alt="<?php echo e($user->name); ?>" class="w-24 h-24 rounded-full object-cover">
                            <?php else: ?>
                                <i class="fa-solid fa-user text-3xl text-brand-primary"></i>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2"><?php echo e($user->name); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4"><?php echo e($user->email); ?></p>
                        
                        <!-- Status Badge -->
                        <!--[if BLOCK]><![endif]--><?php if($user->accounts->count() > 0): ?>
                            <?php
                                $primaryAccount = $user->accounts->first();
                                $status = $primaryAccount->status ?? 'INACTIVE';
                            ?>
                            <!--[if BLOCK]><![endif]--><?php if($status === 'ACTIVE'): ?>
                                <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-brand-success/10 text-brand-success border border-brand-success/20">
                                    <i class="fa-solid fa-check-circle w-3 h-3"></i>
                                    <span><?php echo e(__('common.active')); ?></span>
                                </span>
                            <?php elseif($status === 'SUSPENDED'): ?>
                                <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-brand-error/10 text-brand-error border border-brand-error/20">
                                    <i class="fa-solid fa-pause-circle w-3 h-3"></i>
                                    <span><?php echo e(__('common.suspended')); ?></span>
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-brand-warning/10 text-brand-warning border border-brand-warning/20">
                                    <i class="fa-solid fa-clock w-3 h-3"></i>
                                    <span><?php echo e(__('common.inactive')); ?></span>
                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php else: ?>
                            <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                <i class="fa-solid fa-user-slash w-3 h-3"></i>
                                <span><?php echo e(__('admin.no_accounts')); ?></span>
                            </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        
                        <!--[if BLOCK]><![endif]--><?php if($user->is_admin): ?>
                            <div class="mt-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-brand-primary/20 text-brand-primary">
                                    <i class="fa-solid fa-crown mr-1"></i><?php echo e(__('admin.administrator')); ?>

                                </span>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        
                        <!-- Action Buttons -->
                        <!--[if BLOCK]><![endif]--><?php if(!$user->is_admin): ?>
                            <div class="mt-4 grid grid-cols-3 gap-2">
                                <?php
                                    $hasActiveAccounts = $user->accounts->where('status', 'ACTIVE')->count() > 0;
                                    $hasSuspendedAccounts = $user->accounts->where('status', 'SUSPENDED')->count() > 0;
                                    $allAccountsActive = $user->accounts->count() > 0 && $user->accounts->where('status', 'ACTIVE')->count() === $user->accounts->count();
                                    $allAccountsSuspended = $user->accounts->count() > 0 && $user->accounts->where('status', 'SUSPENDED')->count() === $user->accounts->count();
                                ?>
                                
                                <button wire:click="activateUser" 
                                        wire:confirm="<?php echo e(__('messages.confirm_activate_user')); ?>"
                                        class="px-2 py-2 bg-brand-success/10 text-brand-success hover:bg-brand-success hover:text-white transition-all duration-200 rounded-lg text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed" 
                                        wire:loading.attr="disabled" 
                                        wire:target="activateUser"
                                        <?php if($allAccountsActive): ?> disabled <?php endif; ?>>
                                    <span wire:loading.remove wire:target="activateUser">
                                        <i class="fa-solid fa-check-circle mr-1"></i><?php echo e(__('common.activate')); ?>

                                    </span>
                                    <span wire:loading wire:target="activateUser">
                                        <i class="fa-solid fa-spinner fa-spin mr-1"></i><?php echo e(__('admin.activating')); ?>

                                    </span>
                                </button>
                                
                                <button wire:click="suspendUser" 
                                        class="px-2 py-2 bg-brand-warning/10 text-brand-warning hover:bg-brand-warning hover:text-white transition-all duration-200 rounded-lg text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                        wire:loading.attr="disabled" 
                                        wire:target="suspendUser"
                                        <?php if($allAccountsSuspended): ?> disabled <?php endif; ?>>
                                    <span wire:loading.remove wire:target="suspendUser">
                                        <i class="fa-solid fa-pause-circle mr-1"></i><?php echo e(__('common.suspend')); ?>

                                    </span>
                                    <span wire:loading wire:target="suspendUser">
                                        <i class="fa-solid fa-spinner fa-spin mr-1"></i><?php echo e(__('admin.suspending')); ?>

                                    </span>
                                </button>
                                
                                <button wire:click="deleteUser" 
                                        wire:confirm="<?php echo e(__('messages.confirm_delete_user')); ?>"
                                        class="px-2 py-2 bg-brand-error/10 text-brand-error hover:bg-brand-error hover:text-white transition-all duration-200 rounded-lg text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                        wire:loading.attr="disabled" 
                                        wire:target="deleteUser">
                                    <span wire:loading.remove wire:target="deleteUser">
                                        <i class="fa-solid fa-trash-can mr-1"></i><?php echo e(__('common.delete')); ?>

                                    </span>
                                    <span wire:loading wire:target="deleteUser">
                                        <i class="fa-solid fa-spinner fa-spin mr-1"></i><?php echo e(__('admin.deleting')); ?>

                                    </span>
                                </button>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="lg:col-span-3">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fa-solid fa-user mr-2 text-brand-primary"></i><?php echo e(__('admin.personal_information')); ?>

                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e(__('admin.first_name')); ?></label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border"><?php echo e($user->first_name ?? __('common.not_specified')); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e(__('admin.last_name')); ?></label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border"><?php echo e($user->last_name ?? __('common.not_specified')); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e(__('admin.gender')); ?></label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border"><?php echo e($user->gender ?? __('common.not_specified')); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e(__('admin.birth_date')); ?></label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border"><?php echo e($user->birth_date ?? __('common.not_specified')); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e(__('admin.marital_status')); ?></label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border"><?php echo e($user->marital_status ?? __('common.not_specified')); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e(__('admin.profession')); ?></label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border"><?php echo e($user->profession ?? __('common.not_specified')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fa-solid fa-phone mr-2 text-brand-primary"></i><?php echo e(__('admin.contact_information')); ?>

                    </h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e(__('admin.email')); ?></label>
                            <div class="flex items-center space-x-2">
                                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border flex-1"><?php echo e($user->email); ?></p>
                                <!--[if BLOCK]><![endif]--><?php if($user->email_verified_at): ?>
                                    <span class="text-brand-success" title="<?php echo e(__('admin.email_verified')); ?>">
                                        <i class="fa-solid fa-check-circle"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="text-brand-error" title="<?php echo e(__('admin.email_not_verified')); ?>">
                                        <i class="fa-solid fa-exclamation-circle"></i>
                                    </span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e(__('admin.phone_number')); ?></label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border"><?php echo e($user->phone_number ?? __('common.not_specified')); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fa-solid fa-map-marker-alt mr-2 text-brand-primary"></i><?php echo e(__('admin.address_information')); ?>

                    </h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e(__('admin.country')); ?></label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border"><?php echo e($user->country->english_name ?? __('common.not_specified')); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e(__('admin.region')); ?></label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border"><?php echo e($user->region ?? __('common.not_specified')); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e(__('admin.city')); ?></label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border"><?php echo e($user->city ?? __('common.not_specified')); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e(__('admin.postal_code')); ?></label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border"><?php echo e($user->postal_code ?? __('common.not_specified')); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e(__('admin.address')); ?></label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border"><?php echo e($user->address ?? __('common.not_specified')); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    <i class="fa-solid fa-file-alt mr-2 text-brand-primary"></i><?php echo e(__('admin.documents')); ?>

                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('admin.identity_document')); ?></label>
                        <!--[if BLOCK]><![endif]--><?php if($user->identity_document_url): ?>
                            <?php
                                $identityDocExists = Storage::disk('public')->exists($user->identity_document_url);
                            ?>
                            <div class="flex items-center space-x-2">
                                <!--[if BLOCK]><![endif]--><?php if($identityDocExists): ?>
                                    <span class="text-brand-success"><i class="fa-solid fa-check-circle"></i></span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100"><?php echo e(__('admin.document_uploaded')); ?></span>
                                    <a href="<?php echo e(Storage::url($user->identity_document_url)); ?>" target="_blank" class="text-brand-primary hover:text-brand-primary/80" title="<?php echo e(__('admin.view_document')); ?>">
                                        <i class="fa-solid fa-external-link-alt"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-brand-warning"><i class="fa-solid fa-exclamation-triangle"></i></span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('admin.document_not_found')); ?></span>
                                    <span class="text-xs text-gray-500 dark:text-gray-500">(<?php echo e(basename($user->identity_document_url)); ?>)</span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php else: ?>
                            <div class="flex items-center space-x-2">
                                <span class="text-brand-error"><i class="fa-solid fa-times-circle"></i></span>
                                <span class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('admin.no_document')); ?></span>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('admin.address_document')); ?></label>
                        <!--[if BLOCK]><![endif]--><?php if($user->address_document_url): ?>
                            <?php
                                $addressDocExists = Storage::disk('public')->exists($user->address_document_url);
                            ?>
                            <div class="flex items-center space-x-2">
                                <!--[if BLOCK]><![endif]--><?php if($addressDocExists): ?>
                                    <span class="text-brand-success"><i class="fa-solid fa-check-circle"></i></span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100"><?php echo e(__('admin.document_uploaded')); ?></span>
                                    <a href="<?php echo e(Storage::url($user->address_document_url)); ?>" target="_blank" class="text-brand-primary hover:text-brand-primary/80" title="<?php echo e(__('admin.view_document')); ?>">
                                        <i class="fa-solid fa-external-link-alt"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-brand-warning"><i class="fa-solid fa-exclamation-triangle"></i></span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('admin.document_not_found')); ?></span>
                                    <span class="text-xs text-gray-500 dark:text-gray-500">(<?php echo e(basename($user->address_document_url)); ?>)</span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php else: ?>
                            <div class="flex items-center space-x-2">
                                <span class="text-brand-error"><i class="fa-solid fa-times-circle"></i></span>
                                <span class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('admin.no_document')); ?></span>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>

            <!-- Accounts Section -->
            <!--[if BLOCK]><![endif]--><?php if($user->accounts->count() > 0): ?>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fa-solid fa-university mr-2 text-brand-primary"></i><?php echo e(__('admin.accounts')); ?> (<?php echo e($user->accounts->count()); ?>)
                    </h4>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('common.account_number')); ?></th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('common.status')); ?></th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('admin.balance')); ?></th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('admin.created_at')); ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-600">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $user->accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-2 h-2 bg-brand-accent rounded-full"></div>
                                                <span class="font-mono"><?php echo e($account->account_number); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <!--[if BLOCK]><![endif]--><?php if($account->status === 'ACTIVE'): ?>
                                                <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-brand-success/10 text-brand-success border border-brand-success/20">
                                                    <i class="fa-solid fa-check-circle w-3 h-3"></i>
                                                    <span><?php echo e(__('common.active')); ?></span>
                                                </span>
                                            <?php elseif($account->status === 'SUSPENDED'): ?>
                                                <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-brand-error/10 text-brand-error border border-brand-error/20">
                                                    <i class="fa-solid fa-pause-circle w-3 h-3"></i>
                                                    <span><?php echo e(__('common.suspended')); ?></span>
                                                </span>
                                            <?php else: ?>
                                                <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-brand-warning/10 text-brand-warning border border-brand-warning/20">
                                                    <i class="fa-solid fa-clock w-3 h-3"></i>
                                                    <span><?php echo e(__('common.inactive')); ?></span>
                                                </span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-mono">
                                            <?php echo e(number_format($account->balance ?? 0, 2)); ?> <?php echo e($account->currency); ?>

                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo e($account->created_at->diffForHumans()); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- RIB Section -->
            <!--[if BLOCK]><![endif]--><?php if($user->accounts->count() > 0): ?>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            <i class="fa-solid fa-credit-card mr-2 text-brand-primary"></i><?php echo e(__('admin.rib_information')); ?>

                        </h4>
                    </div>
                    
                    <?php
                        $ribs = collect();
                        foreach($user->accounts as $account) {
                            if($account->rib) {
                                $ribs->push($account->rib);
                            }
                        }
                    ?>
                    
                    <!--[if BLOCK]><![endif]--><?php if($ribs->count() > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('admin.account_number')); ?></th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('admin.iban')); ?></th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('admin.swift')); ?></th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('admin.bank_name')); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-600">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $ribs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rib): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <span class="font-mono"><?php echo e($rib->account->account_number); ?></span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-mono">
                                                <?php echo e($rib->iban ?? __('common.not_specified')); ?>

                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                <?php echo e($rib->swift ?? __('common.not_specified')); ?>

                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                <?php echo e($rib->bank_name ?? __('common.not_specified')); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <div class="w-12 h-12 mx-auto mb-4 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-credit-card text-xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400"><?php echo e(__('admin.no_rib_found')); ?></p>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Account Blocks Section -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fa-solid fa-ban mr-2 text-brand-primary"></i><?php echo e(__('admin.account_blocks')); ?>

                    </h4>

                </div>
                
                <?php
                    // Récupérer tous les blocs de compte associés aux comptes de l'utilisateur
                    $accountBlocks = collect();
                    foreach($user->accounts as $account) {
                        $accountBlocks = $accountBlocks->merge($account->accountBlocks);
                    }
                    // Éliminer les doublons par ID
                    $accountBlocks = $accountBlocks->unique('id');
                ?>
                
                <!--[if BLOCK]><![endif]--><?php if($accountBlocks->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('admin.reason')); ?></th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('admin.amount')); ?></th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('common.status')); ?></th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('admin.created_at')); ?></th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider"><?php echo e(__('admin.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-600">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $accountBlocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <?php
                                                // Récupérer le statut depuis la table pivot pour le point
                                                $firstUserAccount = $user->accounts->first();
                                                $pivotData = $firstUserAccount ? $firstUserAccount->accountBlocks()->where('account_block_id', $block->id)->first() : null;
                                                $blockStatus = $pivotData ? $pivotData->pivot->status : 'active';
                                            ?>
                                            <div class="flex items-center space-x-2">
                                                <!--[if BLOCK]><![endif]--><?php if($blockStatus === 'active'): ?>
                                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                                <?php else: ?>
                                                    <div class="w-2 h-2 bg-brand-error rounded-full"></div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                <span class="font-medium"><?php echo e($block->reason); ?></span>
                                            </div>
                                            <!--[if BLOCK]><![endif]--><?php if($block->description): ?>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?php echo e(Str::limit($block->description, 50)); ?></p>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <!--[if BLOCK]><![endif]--><?php if($block->amount_to_pay > 0): ?>
                                                <?php echo e(number_format($block->amount_to_pay, 2)); ?> <?php echo e($block->currency); ?>

                                            <?php else: ?>
                                                <span class="text-gray-400">-</span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <?php
                                                // Récupérer le statut depuis la table pivot
                                                $firstUserAccount = $user->accounts->first();
                                                $pivotData = $firstUserAccount ? $firstUserAccount->accountBlocks()->where('account_block_id', $block->id)->first() : null;
                                                $blockStatus = $pivotData ? $pivotData->pivot->status : 'active';
                                            ?>
                                            <!--[if BLOCK]><![endif]--><?php if($blockStatus === 'active'): ?>
                                                <span class="px-3 py-1 inline-flex items-center text-xs font-semibold rounded-full bg-transparent text-green-600 border border-green-500">
                                                    <span><?php echo e(__('common.active')); ?></span>
                                                </span>
                                            <?php else: ?>
                                                <span class="px-3 py-1 inline-flex items-center text-xs font-semibold rounded-full bg-transparent text-brand-error border border-brand-error">
                                                    <span><?php echo e(__('common.inactive')); ?></span>
                                                </span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo e($block->created_at->diffForHumans()); ?>

                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <div class="flex items-center space-x-2">
                                                <button wire:click="editBlock(<?php echo e($block->id); ?>)" 
                                                        class="p-2 text-brand-primary hover:text-white hover:bg-brand-primary hover:scale-110 transition-all duration-200 ease-in-out rounded-lg hover:shadow-lg" 
                                                        title="<?php echo e(__('admin.edit')); ?>">
                                                    <i class="fa-solid fa-edit"></i>
                                                </button>
                                                <!--[if BLOCK]><![endif]--><?php if($blockStatus === 'active'): ?>
                                                    <button wire:click="deactivateBlock(<?php echo e($block->id); ?>)" 
                                                            wire:confirm="<?php echo e(__('admin.confirm_deactivate_block')); ?>"
                                                            class="p-2 text-brand-warning hover:text-white hover:bg-brand-warning hover:scale-110 transition-all duration-200 ease-in-out rounded-lg hover:shadow-lg" 
                                                            title="<?php echo e(__('admin.deactivate')); ?>">
                                                        <i class="fa-solid fa-pause"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button wire:click="activateBlock(<?php echo e($block->id); ?>)" 
                                                            wire:confirm="<?php echo e(__('admin.confirm_activate_block')); ?>"
                                                            class="p-2 text-brand-success hover:text-white hover:bg-brand-success hover:scale-110 transition-all duration-200 ease-in-out rounded-lg hover:shadow-lg" 
                                                            title="<?php echo e(__('admin.activate')); ?>">
                                                        <i class="fa-solid fa-play"></i>
                                                    </button>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                <button wire:click="deleteBlock(<?php echo e($block->id); ?>)" 
                                                        wire:confirm="<?php echo e(__('admin.confirm_delete_block')); ?>"
                                                        class="p-2 text-brand-error hover:text-white hover:bg-brand-error hover:scale-110 transition-all duration-200 ease-in-out rounded-lg hover:shadow-lg" 
                                                        title="<?php echo e(__('admin.delete')); ?>">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <div class="w-12 h-12 mx-auto mb-4 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-ban text-xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400"><?php echo e(__('admin.no_blocks_found')); ?></p>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Card Requests Section -->
            <?php
                $cardRequests = $user->accounts->flatMap(function($account) {
                    return $account->cardRequests()->with(['account', 'processedBy'])->get();
                });
            ?>
            <!--[if BLOCK]><![endif]--><?php if($cardRequests->count() > 0): ?>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                    <div class="flex items-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            <i class="fa-solid fa-clock mr-2 text-brand-primary"></i><?php echo e(__('common.card_requests')); ?> (<?php echo e($cardRequests->count()); ?>)
                        </h4>
                    </div>
                    
                    <div class="space-y-4">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $cardRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white dark:bg-gray-600 rounded-lg p-4 border border-gray-200 dark:border-gray-500">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-brand-primary/10 rounded-full flex items-center justify-center">
                                                <i class="fa-solid fa-credit-card text-brand-primary"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-gray-100"><?php echo e($request->card_type); ?></h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($request->phone_number); ?></p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500"><?php echo e($request->created_at->format('d/m/Y H:i')); ?></p>
                                                <!--[if BLOCK]><![endif]--><?php if($request->processedBy): ?>
                                                    <p class="text-xs text-gray-500 dark:text-gray-500"><?php echo e(__('admin.processed_by')); ?>: <?php echo e($request->processedBy->name); ?></p>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                        </div>
                                        <!--[if BLOCK]><![endif]--><?php if($request->message): ?>
                                            <div class="mt-2 ml-14">
                                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($request->message); ?></p>
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                    <div class="text-right flex items-center space-x-2">
                                        <!--[if BLOCK]><![endif]--><?php if($request->status === 'PENDING'): ?>
                                            <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                <i class="fa-solid fa-clock w-3 h-3"></i>
                                                <span><?php echo e(__('common.pending')); ?></span>
                                            </span>
                                            <button wire:click="deleteCardRequest(<?php echo e($request->id); ?>)" 
                                                    wire:confirm="<?php echo e(__('messages.confirm_delete_card_request')); ?>"
                                                    class="px-2 py-1 bg-red-100 hover:bg-red-200 text-red-800 rounded-md transition-colors duration-200 text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                                    wire:loading.attr="disabled" 
                                                    wire:target="deleteCardRequest">
                                                <span wire:loading.remove wire:target="deleteCardRequest">
                                                    <i class="fa-solid fa-trash mr-1"></i><?php echo e(__('common.delete')); ?>

                                                </span>
                                                <span wire:loading wire:target="deleteCardRequest">
                                                     <i class="fa-solid fa-spinner fa-spin mr-1"></i><?php echo e(__('admin.deleting')); ?>

                                                 </span>
                                            </button>
                                        <?php elseif($request->status === 'APPROVED'): ?>
                                            <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                <i class="fa-solid fa-check-circle w-3 h-3"></i>
                                                <span><?php echo e(__('common.approved')); ?></span>
                                            </span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                <i class="fa-solid fa-times-circle w-3 h-3"></i>
                                                <span><?php echo e(__('common.rejected')); ?></span>
                                            </span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Cards Section -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fa-solid fa-credit-card mr-2 text-brand-primary"></i><?php echo e(__('admin.cards')); ?> (<?php echo e($user->cards->count()); ?>)
                    </h4>
                    <button wire:click="openAddCardModal" 
                            class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors duration-200 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled"
                            wire:target="openAddCardModal"
                            <?php if(!$hasActiveAccounts): ?> disabled title="<?php echo e(__('admin.account_must_be_active')); ?>" <?php endif; ?>>
                        <span wire:loading.remove wire:target="openAddCardModal">
                            <i class="fa-solid fa-plus mr-2"></i><?php echo e(__('admin.add_card')); ?>

                        </span>
                        <span wire:loading wire:target="openAddCardModal">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i><?php echo e(__('admin.add_card')); ?>

                        </span>
                    </button>
                </div>
                
                <!--[if BLOCK]><![endif]--><?php if($user->cards->count() > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $user->cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if (isset($component)) { $__componentOriginal3f21310ba3b2fac0111568d07efcc09f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3f21310ba3b2fac0111568d07efcc09f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.financial-card','data' => ['item' => $card,'type' => 'card','showDetails' => isset($showCardDetails[$card->id]),'adminView' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('financial-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($card),'type' => 'card','show-details' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(isset($showCardDetails[$card->id])),'admin-view' => true]); ?>
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
                <?php else: ?>
                    <div class="text-center py-8">
                        <div class="w-12 h-12 mx-auto mb-4 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-credit-card text-xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 mb-4"><?php echo e(__('admin.no_cards_found')); ?></p>
                        <button wire:click="openAddCardModal"
                                class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors duration-200 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled"
                                wire:target="openAddCardModal"
                                <?php if(!$hasActiveAccounts): ?> disabled title="<?php echo e(__('admin.account_must_be_active')); ?>" <?php endif; ?>>
                            <span wire:loading.remove wire:target="openAddCardModal">
                                <i class="fa-solid fa-plus mr-2"></i><?php echo e(__('admin.add_first_card')); ?>

                            </span>
                            <span wire:loading wire:target="openAddCardModal">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i><?php echo e(__('admin.add_first_card')); ?>

                            </span>
                        </button>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Wallets Section -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fa-solid fa-wallet mr-2 text-brand-primary"></i><?php echo e(__('admin.wallets')); ?> (<?php echo e($user->wallets->count()); ?>)
                    </h4>
                    <button wire:click="openAddWalletModal" 
                            class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors duration-200 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled"
                            wire:target="openAddWalletModal"
                            <?php if(!$hasActiveAccounts): ?> disabled title="<?php echo e(__('admin.account_must_be_active')); ?>" <?php endif; ?>>
                        <span wire:loading.remove wire:target="openAddWalletModal">
                            <i class="fa-solid fa-plus mr-2"></i><?php echo e(__('admin.add_wallet')); ?>

                        </span>
                        <span wire:loading wire:target="openAddWalletModal">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i><?php echo e(__('admin.add_wallet')); ?>

                        </span>
                    </button>
                </div>
                
                <!--[if BLOCK]><![endif]--><?php if($user->wallets->count() > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $user->wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if (isset($component)) { $__componentOriginal3f21310ba3b2fac0111568d07efcc09f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3f21310ba3b2fac0111568d07efcc09f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.financial-card','data' => ['item' => $wallet,'type' => 'wallet','showDetails' => isset($showWalletDetails[$wallet->id]),'adminView' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('financial-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($wallet),'type' => 'wallet','show-details' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(isset($showWalletDetails[$wallet->id])),'admin-view' => true]); ?>
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
                <?php else: ?>
                    <div class="text-center py-8">
                        <div class="w-12 h-12 mx-auto mb-4 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-wallet text-xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 mb-4"><?php echo e(__('admin.no_wallets_found')); ?></p>
                        <button wire:click="openAddWalletModal"
                                class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors duration-200 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled"
                                wire:target="openAddWalletModal"
                                <?php if(!$hasActiveAccounts): ?> disabled title="<?php echo e(__('admin.account_must_be_active')); ?>" <?php endif; ?>>
                            <span wire:loading.remove wire:target="openAddWalletModal">
                                <i class="fa-solid fa-plus mr-2"></i><?php echo e(__('admin.add_first_wallet')); ?>

                            </span>
                            <span wire:loading wire:target="openAddWalletModal">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i><?php echo e(__('admin.add_first_wallet')); ?>

                            </span>
                        </button>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Registration Information -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    <i class="fa-solid fa-calendar mr-2 text-brand-primary"></i><?php echo e(__('admin.registration_information')); ?>

                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e(__('admin.registration_date')); ?></label>
                        <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border"><?php echo e($user->created_at->format('d/m/Y H:i')); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e(__('admin.last_update')); ?></label>
                        <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border"><?php echo e($user->updated_at->format('d/m/Y H:i')); ?></p>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php if($user->email_verified_at): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e(__('admin.email_verified_at')); ?></label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border"><?php echo e($user->email_verified_at->format('d/m/Y H:i')); ?></p>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                </div>
            </div>
            
            <!-- Transfer Groups Section -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fa-solid fa-exchange-alt mr-2 text-brand-primary"></i><?php echo e(__('admin.transfer_groups')); ?>

                    </h4>
                    <button wire:click="openTransferGroupModal('account', null)"
                            class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors duration-200 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                            <?php if(!$hasActiveAccounts): ?> disabled title="<?php echo e(__('admin.account_must_be_active')); ?>" <?php endif; ?>>
                        <i class="fa-solid fa-plus mr-2"></i><?php echo e(__('admin.apply_transfer_group')); ?>

                    </button>
                </div>
                
                <!-- Accounts with Transfer Groups -->
                <!--[if BLOCK]><![endif]--><?php if($user->accounts->count() > 0): ?>
                    <div class="mb-6">
                        <h5 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3"><?php echo e(__('admin.accounts_transfer_groups')); ?></h5>
                        <div class="space-y-3">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $user->accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-white dark:bg-gray-600 rounded-lg p-4 border border-gray-200 dark:border-gray-500">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h6 class="font-medium text-gray-900 dark:text-gray-100"><?php echo e($account->account_number); ?></h6>
                                            <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($account->type ?? __('common.standard')); ?> - <?php echo e($account->currency ?? 'EUR'); ?></p>
                                        </div>
                                        <div class="text-right">
                                            <!--[if BLOCK]><![endif]--><?php if($account->transferStepGroups && $account->transferStepGroups->count() > 0): ?>
                                                <div class="flex flex-wrap gap-2">
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $account->transferStepGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="flex items-center gap-1">
                                                            <span class="px-2 py-1 text-xs rounded-full bg-brand-primary/20 text-brand-primary">
                                                                <?php echo e($group->name); ?>

                                                            </span>
                                                            <button 
                                                                wire:click="removeSpecificTransferGroup('account', <?php echo e($account->id); ?>, <?php echo e($group->id); ?>)"
                                                                wire:confirm="<?php echo e(__('messages.confirm_remove_transfer_group')); ?>"
                                                                wire:loading.attr="disabled"
                                                                wire:target="removeSpecificTransferGroup"
                                                                class="text-red-500 hover:text-red-700 text-xs p-1 rounded transition-colors duration-200"
                                                                title="<?php echo e(__('admin.remove_transfer_group')); ?>"
                                                            >
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            <?php else: ?>
                                                <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo e(__('admin.no_transfer_groups')); ?></span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <!-- Wallets with Transfer Groups -->
                <!--[if BLOCK]><![endif]--><?php if($user->wallets->count() > 0): ?>
                    <div>
                        <h5 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3"><?php echo e(__('admin.wallets_transfer_groups')); ?></h5>
                        <div class="space-y-3">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $user->wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-white dark:bg-gray-600 rounded-lg p-4 border border-gray-200 dark:border-gray-500">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h6 class="font-medium text-gray-900 dark:text-gray-100"><?php echo e(strtoupper($wallet->coin)); ?> <?php echo e(__('admin.wallet')); ?></h6>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 font-mono"><?php echo e(substr($wallet->address, 0, 10)); ?>...<?php echo e(substr($wallet->address, -10)); ?></p>
                                        </div>
                                        <div class="text-right">
                                            <!--[if BLOCK]><![endif]--><?php if($wallet->transferStepGroups && $wallet->transferStepGroups->count() > 0): ?>
                                                <div class="flex flex-wrap gap-2">
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $wallet->transferStepGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="flex items-center gap-1">
                                                            <span class="px-2 py-1 text-xs rounded-full bg-brand-primary/20 text-brand-primary">
                                                                <?php echo e($group->name); ?>

                                                            </span>
                                                            <button 
                                                                wire:click="removeSpecificTransferGroup('wallet', <?php echo e($wallet->id); ?>, <?php echo e($group->id); ?>)"
                                                                wire:confirm="<?php echo e(__('messages.confirm_remove_transfer_group')); ?>"
                                                                wire:loading.attr="disabled"
                                                                wire:target="removeSpecificTransferGroup"
                                                                class="text-red-500 hover:text-red-700 text-xs p-1 rounded transition-colors duration-200"
                                                                title="<?php echo e(__('admin.remove_transfer_group')); ?>"
                                                            >
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            <?php else: ?>
                                                <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo e(__('admin.no_transfer_groups')); ?></span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 bg-brand-secondary/10 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-user-slash text-2xl text-brand-secondary"></i>
                </div>
                <p class="text-brand-secondary font-medium"><?php echo e(__('admin.user_not_found')); ?></p>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
    
    <!-- Transfer Group Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showTransferGroupModal): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo e(__('admin.apply_transfer_group')); ?></h3>
                        <button wire:click="closeTransferGroupModal" class="text-gray-400 hover:text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled" wire:target="closeTransferGroupModal">
                            <span wire:loading.remove wire:target="closeTransferGroupModal">
                                <i class="fa-solid fa-times"></i>
                            </span>
                            <span wire:loading wire:target="closeTransferGroupModal">
                                <i class="fa-solid fa-spinner fa-spin text-gray-800 dark:text-white"></i>
                            </span>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Type Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('admin.apply_to')); ?></label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" wire:model.live="transferGroupType" value="account" name="transferGroupType" class="mr-2 text-brand-primary focus:ring-brand-primary">
                                    <span class="text-sm text-gray-700 dark:text-gray-300"><?php echo e(__('admin.account')); ?></span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" wire:model.live="transferGroupType" value="wallet" name="transferGroupType" class="mr-2 text-brand-primary focus:ring-brand-primary">
                                    <span class="text-sm text-gray-700 dark:text-gray-300"><?php echo e(__('admin.wallet')); ?></span>
                                </label>
                            </div>
                        </div>

                        <!-- Loading State -->
                        <div wire:loading wire:target="transferGroupType" class="flex items-center justify-center py-4">
                            <i class="fa-solid fa-spinner fa-spin text-brand-primary mr-2"></i>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('admin.loading')); ?>...</span>
                        </div>

                        <!-- Account Selection -->
                        <div wire:loading.remove wire:target="transferGroupType">
                            <!--[if BLOCK]><![endif]--><?php if($transferGroupType === 'account'): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('admin.select_account')); ?></label>
                                    <select wire:model="selectedAccountId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                        <option value=""><?php echo e(__('admin.select_account')); ?></option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $user->accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($account->id); ?>"><?php echo e($account->account_number); ?> (<?php echo e($account->type ?? __('common.standard')); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            <!-- Wallet Selection -->
                            <!--[if BLOCK]><![endif]--><?php if($transferGroupType === 'wallet'): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('admin.select_wallet')); ?></label>
                                    <select wire:model="selectedWalletId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                        <option value=""><?php echo e(__('admin.select_wallet')); ?></option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $user->wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($wallet->id); ?>"><?php echo e(strtoupper($wallet->coin)); ?> - <?php echo e(substr($wallet->address, 0, 10)); ?>...<?php echo e(substr($wallet->address, -6)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('admin.select_transfer_group')); ?></label>
                            <select wire:model="selectedTransferGroupId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                <option value=""><?php echo e(__('admin.select_transfer_group')); ?></option>
                                <!--[if BLOCK]><![endif]--><?php if(class_exists('\App\Models\TransferStepGroup')): ?>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = \App\Models\TransferStepGroup::where('is_active', true)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($group->id); ?>"><?php echo e($group->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center mt-6">
                        <button wire:click="closeTransferGroupModal" 
                                class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                            <?php echo e(__('common.cancel')); ?>

                        </button>
                        
                        <button wire:click="applyTransferGroup" 
                                class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="applyTransferGroup">
                            <span wire:loading.remove wire:target="applyTransferGroup"><?php echo e(__('admin.apply')); ?></span>
                            <span wire:loading wire:target="applyTransferGroup">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i><?php echo e(__('admin.applying')); ?>

                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
    <!-- Suspension Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showSuspensionModal): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo e(__('admin.suspend_user')); ?></h3>
                        <button wire:click="cancelSuspension" class="text-gray-400 hover:text-gray-600">
                            <i class="fa-solid fa-times"></i>
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

    <!-- Add Card Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showAddCardModal): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo e(__('admin.add_card')); ?></h3>
                    <button wire:click="closeAddCardModal" class="text-gray-400 hover:text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled" wire:target="closeAddCardModal">
                        <span wire:loading.remove wire:target="closeAddCardModal">
                            <i class="fa-solid fa-times"></i>
                        </span>
                        <span wire:loading wire:target="closeAddCardModal">
                            <i class="fa-solid fa-spinner fa-spin text-gray-800 dark:text-white"></i>
                        </span>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <!--[if BLOCK]><![endif]--><?php if($user->cardRequests()->pending()->count() > 0): ?>
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                            <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-3"><?php echo e(__('admin.existing_card_requests')); ?></h4>
                            <div class="space-y-2">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $user->cardRequests()->pending()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded border">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <input type="radio" 
                                                       wire:model="selectedCardRequest" 
                                                       value="<?php echo e($request->id); ?>"
                                                       id="request_<?php echo e($request->id); ?>"
                                                       class="text-brand-primary focus:ring-brand-primary">
                                                <label for="request_<?php echo e($request->id); ?>" class="flex-1 cursor-pointer">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        <?php echo e($request->card_type); ?> - <?php echo e($request->phone_number); ?>

                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        <?php echo e(__('admin.requested_on')); ?>: <?php echo e($request->created_at->format('d/m/Y H:i')); ?>

                                                    </div>
                                                    <!--[if BLOCK]><![endif]--><?php if($request->message): ?>
                                                        <div class="text-xs text-gray-600 dark:text-gray-300 mt-1">
                                                            <?php echo e($request->message); ?>

                                                        </div>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <div class="mt-3 text-xs text-blue-700 dark:text-blue-300">
                                <?php echo e(__('admin.select_request_to_process')); ?>

                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                <i class="fa-solid fa-clock text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400"><?php echo e(__('admin.no_pending_card_requests')); ?></p>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button wire:click="closeAddCardModal" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                        <?php echo e(__('common.cancel')); ?>

                    </button>
                    <button wire:click="addCard" 
                            class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" 
                            wire:target="addCard">
                        <span wire:loading.remove wire:target="addCard"><?php echo e(__('admin.add_card')); ?></span>
                        <span wire:loading wire:target="addCard">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i><?php echo e(__('admin.adding')); ?>

                        </span>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Add Wallet Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showAddWalletModal): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo e(__('admin.add_wallet')); ?></h3>
                    <button wire:click="closeAddWalletModal" class="text-gray-400 hover:text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled" wire:target="closeAddWalletModal">
                        <span wire:loading.remove wire:target="closeAddWalletModal">
                            <i class="fa-solid fa-times"></i>
                        </span>
                        <span wire:loading wire:target="closeAddWalletModal">
                            <i class="fa-solid fa-spinner fa-spin text-gray-800 dark:text-white"></i>
                        </span>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('admin.cryptocurrency')); ?></label>
                        <select wire:model="selectedCryptocurrency" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                            <option value=""><?php echo e(__('admin.select_cryptocurrency')); ?></option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $cryptocurrencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $symbol => $cryptos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <optgroup label="<?php echo e($symbol); ?>">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $cryptos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $crypto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($crypto->id); ?>"><?php echo e($crypto->name); ?> (<?php echo e($crypto->network); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </optgroup>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                    
                    <!-- Auto-generated Address Info -->
                    <div class="bg-brand-primary/10 dark:bg-brand-primary/20 p-4 rounded-lg border border-brand-primary/20 dark:border-brand-primary/30">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-brand-primary mr-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-2">
                                <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">Adresse générée automatiquement</p>
                                <p class="text-xs text-gray-700 dark:text-gray-200">Une adresse unique sera créée automatiquement selon le type de cryptomonnaie sélectionné</p>
                                <!--[if BLOCK]><![endif]--><?php if($selectedCryptocurrency): ?>
                                    <?php
                                        $selectedCrypto = $cryptocurrencies->flatten()->firstWhere('id', $selectedCryptocurrency);
                                    ?>
                                    <!--[if BLOCK]><![endif]--><?php if($selectedCrypto): ?>
                                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-2">
                                            <span class="font-medium">Format:</span> <?php echo e($selectedCrypto->address_example); ?>

                                        </p>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button wire:click="closeAddWalletModal" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                        <?php echo e(__('common.cancel')); ?>

                    </button>
                    <button wire:click="addWallet" 
                            class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" 
                            wire:target="addWallet">
                        <span wire:loading.remove wire:target="addWallet"><?php echo e(__('admin.add_wallet')); ?></span>
                        <span wire:loading wire:target="addWallet">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i><?php echo e(__('admin.adding')); ?>

                        </span>
                    </button>
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
                    <button wire:click="processActivateUserWithManualRib"
                            class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" 
                            wire:target="processActivateUserWithManualRib">
                        <span wire:loading.remove wire:target="processActivateUserWithManualRib">
                            <?php echo e(__('admin.activate_with_rib')); ?>

                        </span>
                        <span wire:loading wire:target="processActivateUserWithManualRib">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i><?php echo e(__('admin.activating')); ?>

                        </span>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Modal d'ajout de blocage -->
    <!--[if BLOCK]><![endif]--><?php if($showAddBlockModal): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <?php echo e(__('admin.add_account_block')); ?>

                    </h3>
                    <button wire:click="closeAddBlockModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label for="blockReason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(__('admin.block_reason')); ?> <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               wire:model="blockReason"
                               id="blockReason"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                               placeholder="<?php echo e(__('admin.block_reason_placeholder')); ?>">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['blockReason'];
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
                        <label for="blockDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(__('admin.block_description')); ?>

                        </label>
                        <textarea wire:model="blockDescription"
                                  id="blockDescription"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                  placeholder="<?php echo e(__('admin.block_description_placeholder')); ?>"></textarea>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['blockDescription'];
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
                        <label for="blockInstructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(__('admin.block_instructions')); ?>

                        </label>
                        <textarea wire:model="blockInstructions"
                                  id="blockInstructions"
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                  placeholder="<?php echo e(__('admin.block_instructions_placeholder')); ?>"></textarea>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['blockInstructions'];
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="blockAmountToPay" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <?php echo e(__('admin.amount_to_pay')); ?>

                                </label>
                                <input type="number" 
                                       wire:model="blockAmountToPay"
                                       id="blockAmountToPay"
                                       step="0.01"
                                       min="0"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                       placeholder="0.00">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['blockAmountToPay'];
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
                                <label for="blockCurrency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <?php echo e(__('register.currency')); ?>

                                </label>
                                <select wire:model="blockCurrency" 
                                        id="blockCurrency"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                    <option value="EUR"><?php echo e(__('register.eur')); ?></option>
                                    <option value="USD"><?php echo e(__('register.usd')); ?></option>
                                    <option value="GBP"><?php echo e(__('register.gbp')); ?></option>
                                    <option value="CAD"><?php echo e(__('register.cad')); ?></option>
                                    <option value="CHF"><?php echo e(__('register.chf')); ?></option>
                                </select>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['blockCurrency'];
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
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <?php echo e(__('admin.block_status')); ?>

                            </label>
                            <select wire:model="blockStatus" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                <option value="active"><?php echo e(__('common.active')); ?></option>
                                <option value="inactive"><?php echo e(__('common.inactive')); ?></option>
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['blockStatus'];
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   wire:model="blockShowRib"
                                   id="blockShowRib"
                                   class="h-4 w-4 text-brand-primary focus:ring-brand-primary border-gray-300 rounded">
                            <label for="blockShowRib" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                <?php echo e(__('admin.show_rib')); ?>

                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   wire:model="blockRequestIdDocument"
                                   id="blockRequestIdDocument"
                                   class="h-4 w-4 text-brand-primary focus:ring-brand-primary border-gray-300 rounded">
                            <label for="blockRequestIdDocument" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                <?php echo e(__('admin.request_id_document')); ?>

                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button wire:click="closeAddBlockModal" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                        <?php echo e(__('common.cancel')); ?>

                    </button>
                    <button wire:click="addBlock" 
                            class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" 
                            wire:target="addBlock">
                        <span wire:loading.remove wire:target="addBlock"><?php echo e(__('admin.add_block')); ?></span>
                        <span wire:loading wire:target="addBlock">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i><?php echo e(__('admin.adding')); ?>

                        </span>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Modal d'édition de blocage -->
    <!--[if BLOCK]><![endif]--><?php if($showEditBlockModal): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <?php echo e(__('admin.edit_account_block')); ?>

                    </h3>
                    <button wire:click="closeEditBlockModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label for="editBlockReason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(__('admin.block_reason')); ?> <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               wire:model="editBlockReason"
                               id="editBlockReason"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                               placeholder="<?php echo e(__('admin.block_reason_placeholder')); ?>">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['editBlockReason'];
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
                        <label for="editBlockDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(__('admin.block_description')); ?>

                        </label>
                        <textarea wire:model="editBlockDescription"
                                  id="editBlockDescription"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                  placeholder="<?php echo e(__('admin.block_description_placeholder')); ?>"></textarea>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['editBlockDescription'];
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
                        <label for="editBlockInstructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(__('admin.block_instructions')); ?>

                        </label>
                        <textarea wire:model="editBlockInstructions"
                                  id="editBlockInstructions"
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                  placeholder="<?php echo e(__('admin.block_instructions_placeholder')); ?>"></textarea>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['editBlockInstructions'];
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="editBlockAmountToPay" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <?php echo e(__('admin.amount_to_pay')); ?>

                                </label>
                                <input type="number" 
                                       wire:model="editBlockAmountToPay"
                                       id="editBlockAmountToPay"
                                       step="0.01"
                                       min="0"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                       placeholder="0.00">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['editBlockAmountToPay'];
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
                                <label for="editBlockCurrency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <?php echo e(__('register.currency')); ?>

                                </label>
                                <select wire:model="editBlockCurrency" 
                                        id="editBlockCurrency"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                    <option value="EUR"><?php echo e(__('register.eur')); ?></option>
                                    <option value="USD"><?php echo e(__('register.usd')); ?></option>
                                    <option value="GBP"><?php echo e(__('register.gbp')); ?></option>
                                    <option value="CAD"><?php echo e(__('register.cad')); ?></option>
                                    <option value="CHF"><?php echo e(__('register.chf')); ?></option>
                                </select>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['editBlockCurrency'];
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
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <?php echo e(__('admin.block_status')); ?>

                            </label>
                            <select wire:model="editBlockStatus" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                <option value="active"><?php echo e(__('common.active')); ?></option>
                                <option value="inactive"><?php echo e(__('common.inactive')); ?></option>
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['editBlockStatus'];
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   wire:model="editBlockShowRib"
                                   id="editBlockShowRib"
                                   class="h-4 w-4 text-brand-primary focus:ring-brand-primary border-gray-300 rounded">
                            <label for="editBlockShowRib" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                <?php echo e(__('admin.show_rib')); ?>

                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   wire:model="editBlockRequestIdDocument"
                                   id="editBlockRequestIdDocument"
                                   class="h-4 w-4 text-brand-primary focus:ring-brand-primary border-gray-300 rounded">
                            <label for="editBlockRequestIdDocument" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                <?php echo e(__('admin.request_id_document')); ?>

                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button wire:click="closeEditBlockModal" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                        <?php echo e(__('common.cancel')); ?>

                    </button>
                    <button wire:click="updateBlock" 
                            class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" 
                            wire:target="updateBlock">
                        <span wire:loading.remove wire:target="updateBlock"><?php echo e(__('admin.update_block')); ?></span>
                        <span wire:loading wire:target="updateBlock">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i><?php echo e(__('admin.updating')); ?>

                        </span>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/livewire/user-detail-management.blade.php ENDPATH**/ ?>
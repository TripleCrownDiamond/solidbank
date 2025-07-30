<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php
        $hasActiveBlock = false;
        if (!Auth::user()->is_admin) {
            $hasActiveBlock = Auth::user()->accounts()->whereHas('activeAccountBlocks')->exists();
        }
    ?>

    <?php if(!$hasActiveBlock): ?>
         <?php $__env->slot('header', null, []); ?> 
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-700 text-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold mb-2"><?php echo e(__('common.welcome_name', ['name' => Auth::user()->name])); ?></h1>
                        <?php if(!Auth::user()->is_admin): ?>
                            <?php
                                $account = Auth::user()->accounts()->first();
                            ?>
                            <?php if($account): ?>
                                <div class="mt-2">
                                    <?php if($account->status === 'INACTIVE'): ?>
                                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
                                            <?php echo e(__('common.account_creation_under_review')); ?>

                                        </span>
                                    <?php elseif($account->status === 'ACTIVE'): ?>
                                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                            <?php echo e(__('common.active')); ?>

                                        </span>
                                    <?php elseif($account->status === 'SUSPENDED'): ?>
                                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">
                                            <?php echo e(__('common.suspended')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="mt-2 text-blue-100">
                                    <?php echo e(__('common.no_account_found')); ?>

                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <?php if(!Auth::user()->is_admin): ?>
                        <div class="text-right">
                            <p class="text-blue-100 text-sm font-medium">
                                <i class="fa-solid fa-user mr-1"></i>
                                <?php echo e(__('common.user_space')); ?>

                            </p>
                        </div>
                    <?php else: ?>
                        <div class="text-right">
                            <p class="text-blue-100 text-sm font-medium">
                                <i class="fa-solid fa-shield-halved mr-1"></i>
                                <?php echo e(__('admin.administrator_space')); ?>

                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
         <?php $__env->endSlot(); ?>
    <?php endif; ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if(Auth::user()->is_admin): ?>
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('admin-dashboard-stats');

$__html = app('livewire')->mount($__name, $__params, 'lw-1542547183-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                <div class="mt-8">
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('latest-inactive-accounts');

$__html = app('livewire')->mount($__name, $__params, 'lw-1542547183-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                </div> 
            <?php else: ?>
                <?php
                    $suspendedAccount = Auth::user()->accounts()->where('status', 'SUSPENDED')->first();
                    $hasActiveBlock = Auth::user()->accounts()->whereHas('activeAccountBlocks')->exists();
                    $activeBlock = null;
                    $blockedAccount = null;
                    if ($hasActiveBlock) {
                        $blockedAccount = Auth::user()->accounts()->whereHas('activeAccountBlocks')->first();
                        $activeBlock = $blockedAccount->activeAccountBlocks()->first();
                    }
                ?>

                <?php if($hasActiveBlock && $activeBlock): ?>
                    <!-- Affichage complet du blocage qui cache tout le reste -->
                    <div class="max-w-6xl mx-auto bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/30 dark:to-red-800/20 border border-red-200 dark:border-red-700 rounded-xl shadow-2xl overflow-hidden backdrop-blur-sm">
                        <div class="p-4 md:p-5">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 dark:from-red-600 dark:to-red-700 rounded-full flex items-center justify-center shadow-lg">
                                        <i class="fas fa-exclamation-triangle text-white text-sm animate-pulse"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <h1 class="text-xl font-bold text-red-800 dark:text-red-200 mb-1"><?php echo e(__('common.account_blocked')); ?></h1>
                                    <p class="text-sm text-red-600 dark:text-red-400 font-medium"><?php echo e(__('common.welcome')); ?>, <?php echo e(Auth::user()->name); ?></p>
                                </div>
                            </div>
                            
                            <?php if($activeBlock->reason): ?>
                                <div class="mb-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-3 rounded-lg border border-red-200 dark:border-red-700 shadow-md hover:shadow-lg transition-shadow">
                                    <h4 class="font-semibold text-sm text-red-700 dark:text-red-300 mb-1 flex items-center">
                                        <i class="fas fa-info-circle w-4 h-4 mr-2 text-red-500"></i>
                                        <?php echo e(__('common.reason')); ?>

                                    </h4>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"><?php echo e($activeBlock->reason); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($activeBlock->description): ?>
                                <div class="mb-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-3 rounded-lg border border-red-200 dark:border-red-700 shadow-md hover:shadow-lg transition-shadow">
                                    <h4 class="font-semibold text-sm text-red-700 dark:text-red-300 mb-1 flex items-center">
                                        <i class="fas fa-file-text w-4 h-4 mr-2 text-red-500"></i>
                                        <?php echo e(__('common.description')); ?>

                                    </h4>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"><?php echo e($activeBlock->description); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($activeBlock->instructions): ?>
                                <div class="mb-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-3 rounded-lg border border-red-200 dark:border-red-700 shadow-md hover:shadow-lg transition-shadow">
                                    <h4 class="font-semibold text-sm text-red-700 dark:text-red-300 mb-1 flex items-center">
                                        <i class="fas fa-clipboard-list w-4 h-4 mr-2 text-red-500"></i>
                                        <?php echo e(__('common.instructions')); ?>

                                    </h4>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"><?php echo e($activeBlock->instructions); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($activeBlock->amount_to_pay): ?>
                                <div class="mb-3 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 p-3 rounded-lg border border-orange-200 dark:border-orange-700 shadow-md">
                                    <h4 class="font-semibold text-sm text-orange-700 dark:text-orange-300 mb-1 flex items-center">
                                        <i class="fas fa-euro-sign w-4 h-4 mr-2 text-orange-500"></i>
                                        <?php echo e(__('common.amount_to_pay')); ?>

                                    </h4>
                                    <p class="text-lg font-bold text-orange-600 dark:text-orange-400 bg-white/50 dark:bg-gray-800/50 p-2 rounded-lg text-center">
                                        <?php echo e(number_format($activeBlock->amount_to_pay, 2)); ?> <?php echo e($activeBlock->currency ?? 'EUR'); ?>

                                    </p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($activeBlock->show_rib && $blockedAccount->rib): ?>
                                <div class="mb-3 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-3 rounded-lg border border-blue-200 dark:border-blue-700 shadow-md">
                                    <h4 class="font-medium text-sm text-blue-700 dark:text-blue-300 mb-2 flex items-center">
                                        <i class="fas fa-university w-4 h-4 mr-2 text-blue-500"></i>
                                        RIB de ce compte
                                    </h4>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between bg-white/80 dark:bg-gray-800/80 p-2 rounded-lg border border-blue-100 dark:border-blue-800">
                                            <div class="flex-1">
                                                <span class="text-xs font-medium text-blue-600 dark:text-blue-400 block mb-1">IBAN:</span>
                                                <p class="font-mono text-xs text-gray-900 dark:text-white font-medium" id="iban"><?php echo e($blockedAccount->rib->iban); ?></p>
                                            </div>
                                            <button onclick="copyToClipboard('iban')" class="ml-2 px-3 py-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-xs font-medium rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                                <i class="fas fa-copy mr-1"></i>
                                                Copier
                                            </button>
                                        </div>
                                        <div class="flex items-center justify-between bg-white/80 dark:bg-gray-800/80 p-2 rounded-lg border border-blue-100 dark:border-blue-800">
                                            <div class="flex-1">
                                                <span class="text-xs font-medium text-blue-600 dark:text-blue-400 block mb-1">SWIFT/BIC:</span>
                                                <p class="font-mono text-xs text-gray-900 dark:text-white font-medium" id="swift"><?php echo e($blockedAccount->rib->swift); ?></p>
                                            </div>
                                            <button onclick="copyToClipboard('swift')" class="ml-2 px-3 py-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-xs font-medium rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                                <i class="fas fa-copy mr-1"></i>
                                                Copier
                                            </button>
                                        </div>
                                        <div class="bg-white/80 dark:bg-gray-800/80 p-2 rounded-lg border border-blue-100 dark:border-blue-800">
                                            <span class="text-xs font-medium text-blue-600 dark:text-blue-400 block mb-1"><?php echo e(__('common.bank_name')); ?>:</span>
                                            <p class="text-xs text-gray-900 dark:text-white font-medium"><?php echo e($blockedAccount->rib->bank_name); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($activeBlock->request_id_document): ?>
                                <div class="text-center mt-3">
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 p-3 rounded-lg border border-green-200 dark:border-green-700">
                                        <p class="text-green-700 dark:text-green-300 text-xs mb-2 flex items-center justify-center">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Vérification de documents requise
                                        </p>
                                        <a href="<?php echo e(route('locale.profile.show', ['locale' => app()->getLocale()])); ?>" 
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white text-sm font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                            <i class="fas fa-user-check mr-1"></i>
                                            <?php echo e(__('common.verify_documents')); ?>

                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <script>
                        function copyToClipboard(elementId) {
                            const element = document.getElementById(elementId);
                            const text = element.textContent;
                            
                            if (navigator.clipboard) {
                                navigator.clipboard.writeText(text).then(function() {
                                    showCopySuccess();
                                }).catch(function(err) {
                                    fallbackCopyTextToClipboard(text);
                                });
                            } else {
                                fallbackCopyTextToClipboard(text);
                            }
                        }
                        
                        function fallbackCopyTextToClipboard(text) {
                            const textArea = document.createElement("textarea");
                            textArea.value = text;
                            textArea.style.top = "0";
                            textArea.style.left = "0";
                            textArea.style.position = "fixed";
                            
                            document.body.appendChild(textArea);
                            textArea.focus();
                            textArea.select();
                            
                            try {
                                const successful = document.execCommand('copy');
                                if (successful) {
                                    showCopySuccess();
                                }
                            } catch (err) {
                                console.error('Fallback: Oops, unable to copy', err);
                            }
                            
                            document.body.removeChild(textArea);
                        }
                        
                        function showCopySuccess() {
                            const notification = document.createElement('div');
                            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50';
                            notification.textContent = 'Copié dans le presse-papiers!';
                            document.body.appendChild(notification);
                            
                            setTimeout(() => {
                                document.body.removeChild(notification);
                            }, 2000);
                        }
                    </script>
                <?php elseif(!Auth::user()->hasVerifiedEmail()): ?>
                    <div class="max-w-3xl mx-auto bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-700 p-8 rounded-xl shadow-lg">
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-800 mb-6">
                                <i class="fas fa-envelope-open-text text-2xl text-blue-600 dark:text-blue-300"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-blue-900 dark:text-blue-100 mb-4"><?php echo e(__('common.email_verification_required_title')); ?></h2>
                            <p class="text-blue-800 dark:text-blue-200 mb-6 leading-relaxed">
                                <?php echo e(__('common.email_verification_dashboard_message')); ?>

                            </p>
                            <div class="space-y-4">
                                <form method="POST" action="<?php echo e(route('verification.send', ['locale' => app()->getLocale()])); ?>" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 dark:from-blue-500 dark:to-indigo-500 dark:hover:from-blue-600 dark:hover:to-indigo-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        <?php echo e(__('common.resend_verification_email')); ?>

                                    </button>
                                </form>
                                <p class="text-sm text-blue-600 dark:text-blue-300">
                                    <?php echo e(__('common.check_spam_folder')); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                <?php elseif($suspendedAccount && ($suspendedAccount->suspension_reason || $suspendedAccount->suspension_instructions)): ?>
                    <div class="max-w-3xl mx-auto bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 p-6 rounded-xl shadow-md">
                        <h2 class="text-2xl font-bold text-red-700 dark:text-red-300 mb-6"><?php echo e(__('common.account_suspended')); ?></h2>

                        <?php if($suspendedAccount->suspension_reason): ?>
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-2">
                                    <?php echo e(__('common.suspension_reason')); ?>

                                </h3>
                                <p class="text-red-800 dark:text-red-200 leading-relaxed">
                                    <?php echo e($suspendedAccount->suspension_reason); ?>

                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if($suspendedAccount->suspension_instructions): ?>
                            <div>
                                <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-2">
                                    <?php echo e(__('common.suspension_instructions')); ?>

                                </h3>
                                <p class="text-red-800 dark:text-red-200 leading-relaxed">
                                    <?php echo e($suspendedAccount->suspension_instructions); ?>

                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php
                        $blockedTransactions = Auth::user()->transactions()
                            ->where('status', 'BLOCKED')
                            ->get();
                    ?>
                    
                    <?php if($blockedTransactions->count() > 0): ?>
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-8">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                <?php echo e(__('transfers.blocked_transactions_alert')); ?>

                                            </h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <?php echo e(trans_choice('transfers.blocked_transactions_message', $blockedTransactions->count(), ['count' => $blockedTransactions->count()])); ?>

                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <?php $__currentLoopData = $blockedTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-800/50 flex-shrink-0 flex items-center justify-center">
                                                    <i class="fas fa-hourglass-half text-red-600 dark:text-red-400 text-sm"></i>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900 dark:text-white">
                                                        <?php echo e($transaction->reference); ?>

                                                    </p>
                                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                                        <?php echo e(number_format($transaction->amount, 2)); ?> <?php echo e($transaction->currency); ?>

                                                    </p>
                                                </div>
                                            </div>
                                            <a href="<?php echo e(route('transfers.progress.resume', ['locale' => app()->getLocale(), 'transferId' => $transaction->id])); ?>" 
                                               class="inline-flex items-center px-4 py-2 bg-brand-primary hover:bg-brand-primary-dark text-white font-medium rounded-lg transition-all duration-200 text-sm">
                                                <?php echo e(__('transfers.continue')); ?>

                                                <i class="fas fa-arrow-right ml-2"></i>
                                            </a>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user-dashboard-stats');

$__html = app('livewire')->mount($__name, $__params, 'lw-1542547183-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    
                    <!-- Affichage normal du dashboard -->
                    <div class="mt-8">
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user-account-ribs');

$__html = app('livewire')->mount($__name, $__params, 'lw-1542547183-3', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    </div>
                    <div class="mt-8">
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user-bank-cards', ['dashboardView' => true, 'maxCards' => 4]);

$__html = app('livewire')->mount($__name, $__params, 'lw-1542547183-4', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    </div>
                    <?php if(isCryptoEnabled()): ?>
                        <div class="mt-8">
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user-wallets', ['dashboardView' => true, 'maxWallets' => 4]);

$__html = app('livewire')->mount($__name, $__params, 'lw-1542547183-5', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/dashboard/index.blade.php ENDPATH**/ ?>
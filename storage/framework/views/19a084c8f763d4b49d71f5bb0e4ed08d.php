<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <?php if (isset($component)) { $__componentOriginalf34c866bdffd2bce53ae480cb6988b04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf34c866bdffd2bce53ae480cb6988b04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.transfer.progress-header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('transfer.progress-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf34c866bdffd2bce53ae480cb6988b04)): ?>
<?php $attributes = $__attributesOriginalf34c866bdffd2bce53ae480cb6988b04; ?>
<?php unset($__attributesOriginalf34c866bdffd2bce53ae480cb6988b04); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf34c866bdffd2bce53ae480cb6988b04)): ?>
<?php $component = $__componentOriginalf34c866bdffd2bce53ae480cb6988b04; ?>
<?php unset($__componentOriginalf34c866bdffd2bce53ae480cb6988b04); ?>
<?php endif; ?>

        

     
        <!-- Start Button -->
        <?php if (isset($component)) { $__componentOriginal5c15c881670413cd45bc8156d51af768 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c15c881670413cd45bc8156d51af768 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.transfer.start-button','data' => ['transferStatus' => $transferStatus,'showStartButton' => $showStartButton,'isTransferBlocked' => $isTransferBlocked,'showStepModal' => $showStepModal]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('transfer.start-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['transferStatus' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($transferStatus),'showStartButton' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showStartButton),'isTransferBlocked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isTransferBlocked),'showStepModal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showStepModal)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c15c881670413cd45bc8156d51af768)): ?>
<?php $attributes = $__attributesOriginal5c15c881670413cd45bc8156d51af768; ?>
<?php unset($__attributesOriginal5c15c881670413cd45bc8156d51af768); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c15c881670413cd45bc8156d51af768)): ?>
<?php $component = $__componentOriginal5c15c881670413cd45bc8156d51af768; ?>
<?php unset($__componentOriginal5c15c881670413cd45bc8156d51af768); ?>
<?php endif; ?>

        
        <!-- Progress Card -->
        <!--[if BLOCK]><![endif]--><?php if($showProgressBar): ?>
        <?php if (isset($component)) { $__componentOriginal2c55504306e92857d55a009735bbbb79 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2c55504306e92857d55a009735bbbb79 = $attributes; } ?>
<?php $component = App\View\Components\Transfer\ProgressCard::resolve(['progress' => $progress,'statusMessage' => $statusMessage,'isTransferStarted' => $isTransferStarted] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('transfer.progress-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Transfer\ProgressCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2c55504306e92857d55a009735bbbb79)): ?>
<?php $attributes = $__attributesOriginal2c55504306e92857d55a009735bbbb79; ?>
<?php unset($__attributesOriginal2c55504306e92857d55a009735bbbb79); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2c55504306e92857d55a009735bbbb79)): ?>
<?php $component = $__componentOriginal2c55504306e92857d55a009735bbbb79; ?>
<?php unset($__componentOriginal2c55504306e92857d55a009735bbbb79); ?>
<?php endif; ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Transaction Details Card -->
        <?php if (isset($component)) { $__componentOriginal9c27275000d822fc8fcadc507311a541 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9c27275000d822fc8fcadc507311a541 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.transfer.transaction-details','data' => ['transaction' => $transaction]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('transfer.transaction-details'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['transaction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($transaction)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9c27275000d822fc8fcadc507311a541)): ?>
<?php $attributes = $__attributesOriginal9c27275000d822fc8fcadc507311a541; ?>
<?php unset($__attributesOriginal9c27275000d822fc8fcadc507311a541); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9c27275000d822fc8fcadc507311a541)): ?>
<?php $component = $__componentOriginal9c27275000d822fc8fcadc507311a541; ?>
<?php unset($__componentOriginal9c27275000d822fc8fcadc507311a541); ?>
<?php endif; ?>

       
    </div>
    
    <!-- Gestionnaire de progression Livewire optimisé -->
    <div>
        <!-- Modal de déblocage d'étape optimisée -->
        <?php if (isset($component)) { $__componentOriginalaecb4ce12ab0753de85ab6b0535e9de3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaecb4ce12ab0753de85ab6b0535e9de3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.transfer.unlock-modal','data' => ['showStepModal' => $showStepModal,'unlockCode' => $unlockCode,'unlockError' => $unlockError,'stepCode' => $stepCode,'isVerifying' => $isVerifying,'currentStepData' => $currentStepData]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('transfer.unlock-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['showStepModal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showStepModal),'unlockCode' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($unlockCode),'unlockError' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($unlockError),'stepCode' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stepCode),'isVerifying' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isVerifying),'currentStepData' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentStepData)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaecb4ce12ab0753de85ab6b0535e9de3)): ?>
<?php $attributes = $__attributesOriginalaecb4ce12ab0753de85ab6b0535e9de3; ?>
<?php unset($__attributesOriginalaecb4ce12ab0753de85ab6b0535e9de3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaecb4ce12ab0753de85ab6b0535e9de3)): ?>
<?php $component = $__componentOriginalaecb4ce12ab0753de85ab6b0535e9de3; ?>
<?php unset($__componentOriginalaecb4ce12ab0753de85ab6b0535e9de3); ?>
<?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        let lastProgressUpdate = 0;
        let progressAnimationFrame = null;
        let statusUpdateTimeout = null;

        let progressAnimationCompleted = false;
        let pendingModalAction = null;

        function updateProgressOptimized(progress) {
            const now = performance.now();
            if (now - lastProgressUpdate >= 16) {
                lastProgressUpdate = now;
                if (progressAnimationFrame) {
                    cancelAnimationFrame(progressAnimationFrame);
                }
                progressAnimationFrame = requestAnimationFrame(() => {
                    const progressCircle = document.querySelector('.progress-circle');
                    if (progressCircle) {
                        progressAnimationCompleted = false;
                        const offset = 282.6 - (progress * 2.826);
                        progressCircle.style.transition = 'stroke-dashoffset 7.2s cubic-bezier(0.4, 0, 0.2, 1)';
                        progressCircle.style.strokeDashoffset = offset;
                        const percentageElement = document.querySelector('.progress-percentage');
                        if (percentageElement) {
                            percentageElement.textContent = Math.round(progress) + '%';
                        }
                        
                        // Écouter la fin de l'animation
                        progressCircle.addEventListener('transitionend', function onTransitionEnd() {
                            progressAnimationCompleted = true;
                            progressCircle.removeEventListener('transitionend', onTransitionEnd);
                            
                            // Si une action modale est en attente, l'exécuter maintenant
                            if (pendingModalAction) {
                                pendingModalAction();
                                pendingModalAction = null;
                            }
                        });
                    }
                });
            }
        }

        function waitForAnimationThenExecute(callback) {
            if (progressAnimationCompleted) {
                // L'animation est déjà terminée, exécuter immédiatement
                callback();
            } else {
                // Attendre la fin de l'animation
                pendingModalAction = callback;
            }
        }

        Livewire.on('progress-updated', (event) => {
            updateProgressOptimized(event.progress);
        });

        Livewire.on('status-message-updated', (event) => {
            clearTimeout(statusUpdateTimeout);
            statusUpdateTimeout = setTimeout(() => {
                const statusElement = document.querySelector('.status-message');
                if (statusElement && statusElement.textContent !== event.message) {
                    statusElement.textContent = event.message;
                }
            }, 50);
        });

        Livewire.on('delayed-block-transfer', (event) => {
            const delay = 0; // Affichage immédiat de la modale
            setTimeout(() => {
                waitForAnimationThenExecute(() => {
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('blockTransfer', event.transactionId, event.stepId, event.stepTitle).then(() => {
                        Livewire.dispatch('show-step-modal');
                    });
                });
            }, delay);
        });

        Livewire.on('start-transfer-progression', (event) => {
            const delay = event.delay || 5000; // Respecter le délai défini côté serveur
            setTimeout(() => {
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('beginTransferProgression');
            }, delay);
        });

        Livewire.on('block-at-first-step', (event) => {
            const delay = 0; // Affichage immédiat de la modale
            setTimeout(() => {
                waitForAnimationThenExecute(() => {
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('blockAtFirstStep', event.stepId, event.stepTitle);
                });
            }, delay);
        });

        Livewire.on('process-next-step-after-delay', (event) => {
            const delay = event.delay || 4000; // Respecter le délai défini côté serveur
            setTimeout(() => {
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('processNextStep');
            }, delay);
        });

        Livewire.on('show-step-modal', () => {
            console.log('DEBUG: Événement show-step-modal reçu');
            console.log('DEBUG: État actuel du composant:', {
                transferStatus: window.Livewire.find('<?php echo e($_instance->getId()); ?>').transferStatus,
                showStepModal: window.Livewire.find('<?php echo e($_instance->getId()); ?>').showStepModal,
                currentStepData: window.Livewire.find('<?php echo e($_instance->getId()); ?>').currentStepData ? 'présent' : 'absent'
            });
            
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('showStepModal').then(() => {
                console.log('DEBUG: Méthode showStepModal terminée, nouvel état:', {
                    showStepModal: window.Livewire.find('<?php echo e($_instance->getId()); ?>').showStepModal
                });
            }).catch((error) => {
                console.error('DEBUG: Erreur lors de l\'appel showStepModal:', error);
            });
        });

        Livewire.on('close-step-modal', () => {
            // Continuer immédiatement la progression après déblocage
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('processNextStep');
        });

        Livewire.on('proceed-to-next-step', (event) => {
            // Progression immédiate sans délai
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('processNextStep');
        });

        Livewire.on('proceed-to-next-step-with-delay', (event) => {
            // Uniquement pour l'affichage de la popup de la prochaine étape
            const delay = 0; // Affichage immédiat de la modale
            setTimeout(() => {
                // Déclencher l'affichage de la prochaine popup d'étape
                if (event.nextStepId && event.nextStepTitle) {
                    waitForAnimationThenExecute(() => {
                        window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('blockTransfer', event.transactionId, event.nextStepId, event.nextStepTitle).then(() => {
                            Livewire.dispatch('show-step-modal');
                        });
                    });
                }
            }, delay);
        });

        window.addEventListener('beforeunload', () => {
            if (progressAnimationFrame) {
                cancelAnimationFrame(progressAnimationFrame);
            }
            clearTimeout(statusUpdateTimeout);
        });
    });

    let lastLogTime = 0;
    function logToConsole(message, data = null) {
        const now = Date.now();
        if (now - lastLogTime >= 100) {
            lastLogTime = now;
            console.log(`[${new Date().toISOString()}] ${message}`, data || '');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const iconPreload = document.createElement('link');
        iconPreload.rel = 'preload';
        iconPreload.as = 'font';
        iconPreload.type = 'font/woff2';
        iconPreload.crossOrigin = 'anonymous';
        document.head.appendChild(iconPreload);
    });
</script><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/livewire/deposit-management/transfer-progress.blade.php ENDPATH**/ ?>
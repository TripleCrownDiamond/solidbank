<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <x-transfer.progress-header />

        

     
        <!-- Start Button -->
        <x-transfer.start-button 
            :transferStatus="$transferStatus" 
            :showStartButton="$showStartButton" 
            :isTransferBlocked="$isTransferBlocked" 
            :showStepModal="$showStepModal"
        />

        
        <!-- Progress Card -->
        @if($showProgressBar)
        <x-transfer.progress-card 
            :progress="$progress" 
            :statusMessage="$statusMessage" 
            :isTransferStarted="$isTransferStarted" 
        />
        @endif

        <!-- Transaction Details Card -->
        <x-transfer.transaction-details :transaction="$transaction" />

       
    </div>
    
    <!-- Gestionnaire de progression Livewire optimisé -->
    <div>
        <!-- Modal de déblocage d'étape optimisée -->
        <x-transfer.unlock-modal 
            :showStepModal="$showStepModal"
            :unlockCode="$unlockCode"
            :unlockError="$unlockError"
            :stepCode="$stepCode"
            :isVerifying="$isVerifying"
            :currentStepData="$currentStepData"
        />
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
                    @this.call('blockTransfer', event.transactionId, event.stepId, event.stepTitle).then(() => {
                        Livewire.dispatch('show-step-modal');
                    });
                });
            }, delay);
        });

        Livewire.on('start-transfer-progression', (event) => {
            const delay = event.delay || 5000; // Respecter le délai défini côté serveur
            setTimeout(() => {
                @this.call('beginTransferProgression');
            }, delay);
        });

        Livewire.on('block-at-first-step', (event) => {
            const delay = 0; // Affichage immédiat de la modale
            setTimeout(() => {
                waitForAnimationThenExecute(() => {
                    @this.call('blockAtFirstStep', event.stepId, event.stepTitle);
                });
            }, delay);
        });

        Livewire.on('process-next-step-after-delay', (event) => {
            const delay = event.delay || 4000; // Respecter le délai défini côté serveur
            setTimeout(() => {
                @this.call('processNextStep');
            }, delay);
        });

        Livewire.on('show-step-modal', () => {
            console.log('DEBUG: Événement show-step-modal reçu');
            console.log('DEBUG: État actuel du composant:', {
                transferStatus: @this.transferStatus,
                showStepModal: @this.showStepModal,
                currentStepData: @this.currentStepData ? 'présent' : 'absent'
            });
            
            @this.call('showStepModal').then(() => {
                console.log('DEBUG: Méthode showStepModal terminée, nouvel état:', {
                    showStepModal: @this.showStepModal
                });
            }).catch((error) => {
                console.error('DEBUG: Erreur lors de l\'appel showStepModal:', error);
            });
        });

        Livewire.on('close-step-modal', () => {
            // Continuer immédiatement la progression après déblocage
            @this.call('processNextStep');
        });

        Livewire.on('proceed-to-next-step', (event) => {
            // Progression immédiate sans délai
            @this.call('processNextStep');
        });

        Livewire.on('proceed-to-next-step-with-delay', (event) => {
            // Uniquement pour l'affichage de la popup de la prochaine étape
            const delay = 0; // Affichage immédiat de la modale
            setTimeout(() => {
                // Déclencher l'affichage de la prochaine popup d'étape
                if (event.nextStepId && event.nextStepTitle) {
                    waitForAnimationThenExecute(() => {
                        @this.call('blockTransfer', event.transactionId, event.nextStepId, event.nextStepTitle).then(() => {
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
</script>
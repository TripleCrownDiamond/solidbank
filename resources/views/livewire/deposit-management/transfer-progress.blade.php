<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <x-transfer.progress-header />

        <!-- Transaction Details Card -->
        <x-transfer.transaction-details :transaction="$transaction" />

        <!-- Start Button -->
        @php
            $progressPercentage = $transaction ? $transaction->progress_percentage : 0;
            $allStepsCompleted = $transaction ? $transaction->areAllStepsCompleted() : false;
        @endphp
        @if(!$allStepsCompleted && $progressPercentage < 100)
        <x-transfer.start-button 
            :transferStatus="$transferStatus" 
            :showStartButton="$showStartButton" 
            :isTransferBlocked="$isTransferBlocked" 
            :transaction="$transaction"
        />
        @endif
        
        <!-- Progress Card -->
        @if($showProgressBar && !$allStepsCompleted && $progressPercentage < 100)
        <x-transfer.progress-card 
            :progress="$progress" 
            :statusMessage="$statusMessage" 
            :isTransferStarted="$isTransferStarted" 
        />
        @endif

        <!-- Success Message when all steps completed or at 100% -->
        @if($allStepsCompleted || $progressPercentage >= 100)
        <x-transfer.completion-message :isCompleted="true" />
        @endif
    </div>
    
    <!-- Gestionnaire de progression Livewire optimisé -->
    <div>
        <!-- Modal de déblocage d'étape optimisée -->
        <x-transfer.unlock-modal 
            :showUnlockModal="$showUnlockModal"
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
                        const offset = 282.6 - (progress * 2.826);
                        progressCircle.style.transition = 'stroke-dashoffset 2.4s ease-in-out';
                        progressCircle.style.strokeDashoffset = offset;
                        const percentageElement = document.querySelector('.progress-percentage');
                        if (percentageElement) {
                            percentageElement.textContent = Math.round(progress) + '%';
                        }
                    }
                });
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
            const delay = Math.min(event.delay || 250, 250); // Réduit encore de moitié
            setTimeout(() => {
                @this.call('blockTransfer', event.transactionId, event.stepId, event.stepTitle).then(() => {
                    Livewire.dispatch('show-step-modal');
                });
            }, delay);
        });

        Livewire.on('start-transfer-progression', (event) => {
            const delay = Math.min(event.delay || 50, 100); // Démarrage immédiat
            setTimeout(() => {
                @this.call('beginTransferProgression');
            }, delay);
        });

        Livewire.on('block-at-first-step', (event) => {
            const delay = Math.min(event.delay || 100, 100); // Démarrage immédiat
            setTimeout(() => {
                @this.call('blockAtFirstStep', event.stepId, event.stepTitle);
            }, delay);
        });

        Livewire.on('process-next-step-after-delay', (event) => {
            const delay = Math.min(event.delay || 100, 200); // Démarrage rapide
            setTimeout(() => {
                @this.call('processNextStep');
            }, delay);
        });

        Livewire.on('show-step-modal', () => {
            console.log('Affichage optimisé de la modale d\'étape');
        });

        Livewire.on('close-step-modal', () => {
            @this.call('$refresh');
        });

        Livewire.on('proceed-to-next-step', (event) => {
            const delay = Math.min(event.delay || 100, 200); // Démarrage rapide
            setTimeout(() => {
                @this.call('processNextStep');
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
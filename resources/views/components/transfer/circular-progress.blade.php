<div class="flex flex-col items-center mb-8" id="transfer-progress-container" wire:init="$refresh">
    <div class="relative w-48 h-48 mb-6" x-data="{ progress: {{ $progress }} }" 
         x-init="
            // Mettre à jour la progression lorsque la propriété Livewire change
            Livewire.on('progress-updated', (event) => {
                // Animation fluide avec GSAP si disponible, sinon avec l'API Web Animations
                const progressCircle = $el.querySelector('.progress-circle');
                if (progressCircle) {
                    const currentOffset = parseFloat(progressCircle.style.strokeDashoffset || '282.6');
                    const targetOffset = 282.6 - (event.progress * 2.826);
                    const currentProgress = progress;
                    const targetProgress = event.progress;
                    
                    if (window.gsap) {
                        gsap.to(progressCircle, {
                            'stroke-dashoffset': targetOffset,
                            duration: 5,
                            ease: 'power2.out'
                        });
                        // Animation synchronisée du pourcentage
                        gsap.to({ value: currentProgress }, {
                            value: targetProgress,
                            duration: 5,
                            ease: 'power2.out',
                            onUpdate: function() {
                                progress = this.targets()[0].value;
                            }
                        });
                    } else if (progressCircle.animate) {
                        progressCircle.animate(
                            [{ 'stroke-dashoffset': currentOffset }, { 'stroke-dashoffset': targetOffset }],
                            { duration: 5000, easing: 'ease-out', fill: 'forwards' }
                        );
                        // Animation du pourcentage avec requestAnimationFrame
                        const startTime = performance.now();
                        const animateProgress = (currentTime) => {
                            const elapsed = currentTime - startTime;
                            const duration = 5000;
                            const progressRatio = Math.min(elapsed / duration, 1);
                            progress = currentProgress + (targetProgress - currentProgress) * progressRatio;
                            
                            if (progressRatio < 1) {
                                requestAnimationFrame(animateProgress);
                            } else {
                                progress = targetProgress;
                            }
                        };
                        requestAnimationFrame(animateProgress);
                    } else {
                        // Fallback pour les navigateurs plus anciens
                        progressCircle.style.transition = 'stroke-dashoffset 5s ease-out';
                        progressCircle.style.strokeDashoffset = targetOffset;
                        // Animation simple du pourcentage
                        const startTime = Date.now();
                        const animateProgress = () => {
                            const elapsed = Date.now() - startTime;
                            const duration = 5000;
                            const progressRatio = Math.min(elapsed / duration, 1);
                            progress = currentProgress + (targetProgress - currentProgress) * progressRatio;
                            
                            if (progressRatio < 1) {
                                setTimeout(animateProgress, 16);
                            } else {
                                progress = targetProgress;
                            }
                        };
                        animateProgress();
                    }
                }
            });
        ">
        <!-- Background Circle -->
        <svg class="w-48 h-48 transform -rotate-90" viewBox="0 0 100 100">
            <circle
                cx="50"
                cy="50"
                r="45"
                stroke="currentColor"
                stroke-width="8"
                fill="none"
                class="text-gray-200 dark:text-gray-700"
            />
            <!-- Progress Circle -->
            <circle
                cx="50"
                cy="50"
                r="45"
                stroke="currentColor"
                stroke-width="8"
                fill="none"
                stroke-linecap="round"
                class="progress-circle text-blue-600 dark:text-blue-400 transition-all duration-1000 ease-in-out"
                style="stroke-dasharray: 282.6; stroke-dashoffset: {{ 282.6 - (min($progress, 100) * 2.826) }};"
            />
        </svg>
        
        <!-- Percentage Text - Synchronized with circle animation -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center">
                <div class="text-4xl font-bold text-gray-900 dark:text-white progress-percentage">
                    <span x-text="Math.round(progress)">{{ $progress }}</span>%
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ __('transfers.progress_label') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Status Message -->
    <div class="text-xl font-semibold text-gray-800 dark:text-gray-200 text-center mb-4 min-h-12">
        <span class="status-message">{{ $statusMessage }}</span>
    </div>
</div>
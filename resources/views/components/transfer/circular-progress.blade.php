<div class="flex flex-col items-center mb-8" id="transfer-progress-container" wire:init="$refresh">
    <div class="relative w-48 h-48 mb-6" x-data="{ progress: {{ $progress }}, animatedProgress: 0, isLoading: true }" 
        x-init="
            // Animation initiale au chargement
            const animateToProgress = (targetProgress) => {
                const progressCircle = $el.querySelector('.progress-circle');
                const targetOffset = 282.6 - (targetProgress * 2.826);
                const currentOffset = parseFloat(progressCircle.style.strokeDashoffset || '282.6');
                
                $data.isLoading = true;

                if (progressCircle) {
                    if (window.gsap) {
                        gsap.to(progressCircle, {
                            'stroke-dashoffset': targetOffset,
                            duration: 2,
                            ease: 'power2.out',
                            onComplete: () => { $data.isLoading = false; }
                        });

                        gsap.to($data, {
                            animatedProgress: targetProgress,
                            duration: 2,
                            ease: 'power2.out'
                        });
                    } else if (progressCircle.animate) {
                        progressCircle.animate(
                            [{ 'strokeDashoffset': currentOffset }, { 'strokeDashoffset': targetOffset }],
                            { duration: 2000, easing: 'ease-out', fill: 'forwards' }
                        );

                        const start = $data.animatedProgress;
                        const diff = targetProgress - start;
                        const duration = 2000;
                        const stepTime = 50;
                        let elapsed = 0;

                        const interval = setInterval(() => {
                            elapsed += stepTime;
                            $data.animatedProgress = Math.min(targetProgress, Math.round(start + (diff * (elapsed / duration))));
                            if (elapsed >= duration) {
                                clearInterval(interval);
                                $data.isLoading = false;
                            }
                        }, stepTime);
                    } else {
                        progressCircle.style.transition = 'stroke-dashoffset 2s ease-out';
                        progressCircle.style.strokeDashoffset = targetOffset;

                        const start = $data.animatedProgress;
                        const diff = targetProgress - start;
                        const duration = 2000;
                        const stepTime = 50;
                        let elapsed = 0;

                        const interval = setInterval(() => {
                            elapsed += stepTime;
                            $data.animatedProgress = Math.min(targetProgress, Math.round(start + (diff * (elapsed / duration))));
                            if (elapsed >= duration) {
                                clearInterval(interval);
                                $data.isLoading = false;
                            }
                        }, stepTime);
                    }
                }
            };

            // Démarrer l'animation avec la valeur initiale
            setTimeout(() => animateToProgress(progress), 100);

            // Écouter les mises à jour de progression
            Livewire.on('progress-updated', (event) => {
                animateToProgress(event.progress);
            });
        "
        >
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
        
        <!-- Percentage Text -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center">
                 <div class="text-4xl font-bold text-gray-900 dark:text-white">
                     <span x-show="!isLoading" x-text="Math.round(animatedProgress) + '%'"></span>
                     <span x-show="isLoading" class="text-lg">Chargement...</span>
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
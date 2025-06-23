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
                    
                    if (window.gsap) {
                        gsap.to(progressCircle, {
                            'stroke-dashoffset': targetOffset,
                            duration: 2,
                            ease: 'power2.out',
                            onUpdate: function() {
                                progress = event.progress;
                            }
                        });
                    } else if (progressCircle.animate) {
                        progressCircle.animate(
                            [{ 'stroke-dashoffset': currentOffset }, { 'stroke-dashoffset': targetOffset }],
                            { duration: 2000, easing: 'ease-out', fill: 'forwards' }
                        );
                        progress = event.progress;
                    } else {
                        // Fallback pour les navigateurs plus anciens
                        progressCircle.style.transition = 'stroke-dashoffset 2s ease-out';
                        progressCircle.style.strokeDashoffset = targetOffset;
                        progress = event.progress;
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
        
        <!-- Percentage Text -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center">
                <div class="text-4xl font-bold text-gray-900 dark:text-white">
                    {{ $progress }}%
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

    <!-- Unlock Button (shown when blocked) -->
    <div class="text-center mb-4" style="display: none;" data-unlock-button>
        <button 
            wire:click="reopenModal"
            class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center mx-auto"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
            </svg>
            {{ __('transfers.unlock_step') }}
        </button>
    </div>
</div>
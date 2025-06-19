<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Progression du Transfert
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Veuillez patienter pendant le traitement de votre transfert
            </p>
        </div>

        <!-- Progress Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 mb-8">
            <!-- Circular Progress -->
            <div class="flex flex-col items-center mb-8">
                <div class="relative w-48 h-48 mb-6">
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
                            id="progress-circle"
                            cx="50"
                            cy="50"
                            r="45"
                            stroke="currentColor"
                            stroke-width="8"
                            fill="none"
                            stroke-linecap="round"
                            class="text-blue-600 dark:text-blue-400 transition-all duration-300 ease-out"
                            style="stroke-dasharray: 314; stroke-dashoffset: 314;"
                        />
                    </svg>
                    
                    <!-- Percentage Text -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div id="progress-percentage" class="text-4xl font-bold text-gray-900 dark:text-white">
                                0%
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Progression
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Message -->
                <div id="progress-message" class="text-xl font-semibold text-gray-800 dark:text-gray-200 text-center mb-4">
                    {{ $statusMessage }}
                </div>

                <!-- Step Description -->
                @if(isset($dynamicSteps[$currentStep]))
                    <div class="text-center text-gray-600 dark:text-gray-400 mb-4">
                        {{ $dynamicSteps[$currentStep]['description'] ?? '' }}
                    </div>
                @endif
            </div>

            <!-- Transfer Details -->
            @if(!empty($transferData))
                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Détails du Transfert
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                Montant
                            </label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ number_format($transferData['amount'] ?? 0, 2) }} €
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                Bénéficiaire
                            </label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $transferData['recipient_name'] ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                Compte Destinataire
                            </label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $transferData['recipient_account'] ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                Description
                            </label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $transferData['description'] ?? 'Aucune description' }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Completion Message -->
        @if($isCompleted)
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-6 text-center">
                <div class="w-16 h-16 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-green-800 dark:text-green-200 mb-2">
                    Transfert Terminé avec Succès
                </h3>
                <p class="text-green-600 dark:text-green-400 mb-6">
                    Votre transfert a été traité avec succès.
                </p>
                <button 
                    wire:click="redirectToTransactions"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl"
                >
                    Retour aux Transactions
                </button>
            </div>
        @endif
    </div>

    <!-- Code Verification Modal -->
    @if(isset($showCodeModal) && $showCodeModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        Vérification Requise
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Veuillez saisir votre code de vérification à 6 chiffres
                    </p>
                </div>

                <div class="mb-6">
                    <input 
                        type="text" 
                        wire:model="verificationCode"
                        placeholder="000000"
                        maxlength="6"
                        class="w-full text-center text-2xl font-mono tracking-widest py-4 px-4 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                        autofocus
                    >
                </div>

                <div class="flex space-x-3">
                    <button 
                        wire:click="closeCodeModal"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200"
                    >
                        Annuler
                    </button>
                    <button 
                        wire:click="verifyCode"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200"
                        :disabled="verificationCode.length !== 6"
                    >
                        Vérifier
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Récupérer les données dynamiques depuis PHP
        const dynamicSteps = @json($dynamicSteps);
        const stepThresholds = @json($stepThresholds);
        const maxSteps = {{ $maxSteps }};
        
        // Créer le loader avec les données dynamiques
        const progressLoader = new TransferProgressLoader(stepThresholds, maxSteps);
        
        // Écouter les événements d'étapes atteintes
        progressLoader.onStepReached = function(step) {
            @this.call('handleStepReached', step);
        };
        
        // Écouter l'événement de fin de transfert
        progressLoader.onComplete = function() {
            document.getElementById('progress-message').textContent = 'Transfert terminé avec succès!';
        };
        
        // Écouter l'événement de continuation automatique
        Livewire.on('auto-continue-progress', () => {
            setTimeout(() => {
                progressLoader.resume();
            }, 2000);
        });
        
        // Écouter l'événement de continuation manuelle
        Livewire.on('continue-progress', () => {
            progressLoader.resume();
        });
        
        // Démarrer automatiquement le loader au chargement de la page
        setTimeout(() => {
            progressLoader.start();
        }, 500); // Petit délai pour s'assurer que tout est initialisé
    });
</script>
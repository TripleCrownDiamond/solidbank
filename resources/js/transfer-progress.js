// Gestion de la progression des transferts en JavaScript pur
class TransferProgress {
    constructor() {
        this.progress = 0;
        this.statusMessage = window.translations?.transfers?.processing_transfer_in_progress || 'Traitement du transfert en cours...';
        this.showModal = false;
        this.progressStarted = false;
        this.progressInterval = null;
        this.totalSteps = 4; // Valeur par défaut, sera mise à jour dynamiquement
        this.percentagePerStep = 25; // Valeur par défaut, sera mise à jour dynamiquement
        this.progressAnimationFrame = null; // Pour gérer l'animation
        
        this.init();
    }

    init() {
        console.log('Gestionnaire de progression du transfert initialisé');
        
        // Attendre que le DOM soit chargé
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupElements());
        } else {
            this.setupElements();
        }
        
        // Écoute des événements Livewire si disponible
        this.setupLivewireListeners();
    }

    setupElements() {
        // Initialiser les éléments de progression
        this.progressCircle = document.getElementById('progress-circle');
        this.progressPercentage = document.getElementById('progress-percentage');
        this.statusElement = document.querySelector('[data-status-message]');
        this.modalElement = document.querySelector('[data-modal]');
        this.startButton = document.querySelector('[data-start-progress]');
        
        // Configurer les événements
        if (this.startButton) {
            this.startButton.addEventListener('click', () => this.startProgress());
        }
        
        // Configurer les boutons de la modale
        const verifyButton = document.querySelector('[data-verify-code]');
        const cancelButton = document.querySelector('[data-cancel-transfer]');
        
        if (verifyButton) {
            verifyButton.addEventListener('click', () => this.verifyCode());
        }
        
        if (cancelButton) {
            cancelButton.addEventListener('click', () => this.cancelTransfer());
        }
        
        // Fermer la modale avec Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.showModal) {
                this.hideModal();
            }
        });
        
        this.updateUI();
    }

    setupLivewireListeners() {
        // Attendre que Livewire soit disponible
        const checkLivewire = () => {
            if (typeof Livewire !== 'undefined') {
                // Récupérer les données de progression depuis le composant Livewire
                this.loadTransferStepsData();
                
                Livewire.on('transfer-blocked', () => {
                    this.showModal = true;
                    this.updateUI();
                    console.log(window.translations?.transfers?.transfer_blocked_showing_modal || 'Transfert bloqué, affichage de la modale');
                });

                Livewire.on('transferBlocked', () => {
                    console.log('Transfer blocked event received');
                    this.setStatusMessage(window.translations?.transfers?.transaction_blocked || 'Transaction bloquée');
                    this.showUnlockButton();
                });

                Livewire.on('transfer-completed', () => {
                    this.progress = 100;
                    this.statusMessage = window.translations?.transfers?.transfer_submitted_successfully || 'Soumis avec succès';
                    this.updateUI();
                    console.log(window.translations?.transfers?.transfer_completed || 'Transfert complété');
                });
                
                Livewire.on('transfer-progress-updated', (progressData) => {
                    console.log(window.translations?.transfers?.progress_update_received || 'Mise à jour de la progression reçue:', progressData);
                    if (progressData && progressData.totalSteps > 0) {
                        this.totalSteps = progressData.totalSteps;
                        this.percentagePerStep = progressData.percentagePerStep;
                        
                        // Utiliser l'animation fluide au lieu de définir directement
                        const targetProgress = progressData.currentProgress || 0;
                        this.animateProgressTo(targetProgress);
                        
                        // Mettre à jour l'interface utilisateur
                        this.updateUI();
                        
                        console.log(`Progression mise à jour: ${targetProgress}% (${progressData.completedSteps}/${progressData.totalSteps} étapes)`);
                    }
                });
                
                Livewire.on('transferCompleted', () => {
                    console.log(window.translations?.transfers?.transfer_completed_event_received || 'Transfer completed event received');
                    this.setProgress(100);
                    this.setStatusMessage(window.translations?.transfers?.transfer_submitted_successfully || 'Soumis avec succès');
                    this.hideModal();
                    this.hideUnlockButton();
                });
                
                // Écouter l'événement progressDataLoaded émis depuis mount()
                Livewire.on('progressDataLoaded', (progressData) => {
                    console.log(window.translations?.transfers?.progress_data_received_from_mount || 'Données de progression reçues depuis mount:', progressData);
                    if (progressData && progressData.totalSteps > 0) {
                        this.totalSteps = progressData.totalSteps;
                        this.percentagePerStep = progressData.percentagePerStep;
                        
                        // Utiliser l'animation fluide pour le chargement initial avec une durée plus courte
                        const targetProgress = progressData.currentProgress || 0;
                        this.animateProgressTo(targetProgress, 1000);
                        
                        console.log(`Données initialisées: ${this.totalSteps} étapes, ${this.percentagePerStep}% par étape, progression: ${targetProgress}%`);
                        
                        // Mettre à jour l'interface utilisateur
                        this.updateUI();
                        
                        // Afficher le bouton de déblocage si nécessaire
                        if (progressData.isBlocked && progressData.currentBlockedStep) {
                            this.showUnlockButton();
                        } else {
                            this.hideUnlockButton();
                        }
                    } else {
                        console.log(window.translations?.transfers?.no_valid_progress_data_received || 'Aucune donnée de progression valide reçue');
                    }
                });
            } else {
                setTimeout(checkLivewire, 100);
            }
        };
        checkLivewire();
    }

    loadTransferStepsData() {
        // Récupérer les données depuis le composant Livewire
        const livewireComponent = document.querySelector('[wire\\:id]');
        if (livewireComponent && typeof $wire !== 'undefined') {
            try {
                // Récupérer les données de progression depuis le composant Livewire
                $wire.call('getProgressData').then(progressData => {
                    if (progressData && progressData.totalSteps > 0) {
                        this.totalSteps = progressData.totalSteps;
                        this.percentagePerStep = progressData.percentagePerStep;
                        this.progress = progressData.currentProgress || 0;
                        console.log(`Données de progression chargées: ${this.totalSteps} étapes, ${this.percentagePerStep}% par étape, progression actuelle: ${this.progress}%`);
                        
                        // Mettre à jour l'interface utilisateur avec les nouvelles données
                        this.updateUI();
                        if (this.progressCircle && this.progressPercentage) {
                            const radius = 45;
                            const circumference = 2 * Math.PI * radius;
                            this.updateProgressCircle(circumference);
                        }
                    } else {
                        console.warn(window.translations?.transfers?.no_transfer_steps_found_using_defaults || 'Aucune étape de transfert trouvée, utilisation des valeurs par défaut');
                    }
                }).catch(error => {
                    console.error(window.translations?.transfers?.error_loading_progress_data || 'Erreur lors du chargement des données de progression:', error);
                    console.log('Utilisation des valeurs par défaut');
                });
            } catch (error) {
                console.error(window.translations?.transfers?.error_calling_get_progress_data || 'Erreur lors de l\'appel à getProgressData:', error);
                console.log('Utilisation des valeurs par défaut');
            }
        } else {
            console.log(window.translations?.transfers?.livewire_component_not_found || 'Composant Livewire non trouvé, utilisation des valeurs par défaut');
        }
    }

    startProgress() {
        if (this.progressStarted) return;

        console.log(window.translations?.transfers?.starting_transfer_process || 'Démarrage du processus de transfert...');
        this.progressStarted = true;
        
        if (!this.progressCircle || !this.progressPercentage) {
            console.error(window.translations?.transfers?.progress_elements_not_found || 'Éléments de progression non trouvés');
            return;
        }

        const radius = 45;
        const circumference = 2 * Math.PI * radius;
        this.progressCircle.style.strokeDasharray = circumference;
        this.progressCircle.style.strokeDashoffset = circumference;

        // Récupérer la vraie progression depuis le serveur
        if (typeof $wire !== 'undefined') {
            $wire.call('getProgressData').then(progressData => {
                if (progressData) {
                    this.progress = progressData.currentProgress || 0;
                    this.updateUI();
                    this.updateProgressCircle(circumference);
                    console.log(`Progression réelle affichée: ${this.progress}%`);
                }
            });
        }

        // Appeler la méthode start() du composant Livewire pour démarrer le processus réel
        if (typeof $wire !== 'undefined') {
            $wire.call('start').then(() => {
                console.log(window.translations?.transfers?.transfer_process_started_successfully || 'Processus de transfert démarré avec succès');
            }).catch(error => {
                console.error(window.translations?.transfers?.error_starting_transfer || 'Erreur lors du démarrage du transfert:', error);
                this.statusMessage = 'Erreur lors du démarrage du transfert';
                this.updateUI();
            });
        } else if (typeof Livewire !== 'undefined' && Livewire.find) {
            // Alternative pour Livewire
            const component = Livewire.find(document.querySelector('[wire\\:id]')?.getAttribute('wire:id'));
            if (component) {
                component.call('start');
            }
        }
        
        this.updateUI();
    }

    updateProgressCircle(circumference) {
        if (this.progressCircle && this.progressPercentage) {
            const offset = circumference - (this.progress / 100) * circumference;
            this.progressCircle.style.strokeDashoffset = offset;
            
            // Afficher le pourcentage en incréments de +1 pendant l'animation
            const displayValue = Math.round(this.progress);
            
            this.progressPercentage.textContent = `${displayValue}%`;
        }
    }

    updateUI() {
        // Mettre à jour le message de statut
        if (this.statusElement) {
            this.statusElement.textContent = this.statusMessage;
        }
        
        // Afficher/masquer la modale
        if (this.modalElement) {
            if (this.showModal) {
                this.modalElement.style.display = 'flex';
                this.modalElement.classList.remove('opacity-0', 'scale-95');
                this.modalElement.classList.add('opacity-100', 'scale-100');
            } else {
                this.modalElement.classList.remove('opacity-100', 'scale-100');
                this.modalElement.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    this.modalElement.style.display = 'none';
                }, 200);
            }
        }
        
        // Afficher/masquer le bouton de démarrage
        if (this.startButton) {
            if (this.progress === 0 && !this.progressStarted) {
                this.startButton.style.display = 'block';
            } else {
                this.startButton.style.display = 'none';
            }
        }
    }

    showModal() {
        this.showModal = true;
        this.updateUI();
    }

    hideModal() {
        this.showModal = false;
        this.updateUI();
    }

    showUnlockButton() {
        const unlockButton = document.querySelector('[data-unlock-button]');
        if (unlockButton) {
            unlockButton.style.display = 'block';
        }
        
        // Masquer le bouton de démarrage
        const startButton = document.querySelector('[data-start-progress]');
        if (startButton && startButton.parentElement) {
            startButton.parentElement.style.display = 'none';
        }
    }

    hideUnlockButton() {
        const unlockButton = document.querySelector('[data-unlock-button]');
        if (unlockButton) {
            unlockButton.style.display = 'none';
        }
        
        // Afficher le bouton de démarrage si pas encore terminé
        const startButton = document.querySelector('[data-start-progress]');
        if (startButton && startButton.parentElement) {
            startButton.parentElement.style.display = 'block';
        }
    }

    verifyCode() {
        if (typeof $wire !== 'undefined') {
            $wire.call('verifyStepCode').then(() => {
                console.log(window.translations?.transfers?.verification_code_sent_successfully || 'Code de vérification envoyé avec succès');
                this.hideModal();
            }).catch(error => {
                console.error(window.translations?.transfers?.error_verifying_code || 'Erreur lors de la vérification du code:', error);
            });
        } else if (typeof Livewire !== 'undefined' && Livewire.find) {
            // Alternative pour Livewire
            const component = Livewire.find(document.querySelector('[wire\\:id]')?.getAttribute('wire:id'));
            if (component) {
                component.call('verifyStepCode');
                this.hideModal();
            }
        }
    }

    cancelTransfer() {
        if (typeof $wire !== 'undefined') {
            $wire.cancelTransfer();
            console.log(window.translations?.transfers?.transfer_cancelled || 'Transfert annulé');
        } else if (typeof Livewire !== 'undefined' && Livewire.find) {
            // Alternative pour Livewire
            const component = Livewire.find(document.querySelector('[wire\\:id]')?.getAttribute('wire:id'));
            if (component) {
                component.call('cancelTransfer');
            }
        }
        this.hideModal();
    }

    // Méthodes publiques pour l'interaction externe
    setProgress(value) {
        this.progress = Math.max(0, Math.min(100, value));
        if (this.progressCircle && this.progressPercentage) {
            const radius = 45;
            const circumference = 2 * Math.PI * radius;
            this.updateProgressCircle(circumference);
        }
    }

    // Animation fluide de la progression
    animateProgressTo(targetValue, duration = 2000) {
        const startValue = this.progress;
        const endValue = Math.max(0, Math.min(100, targetValue));
        const startTime = performance.now();
        
        // Arrêter toute animation en cours
        if (this.progressAnimationFrame) {
            cancelAnimationFrame(this.progressAnimationFrame);
        }
        
        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Fonction d'easing pour une animation plus fluide
            const easeOutCubic = 1 - Math.pow(1 - progress, 3);
            
            // Calculer la valeur actuelle
            const currentValue = startValue + (endValue - startValue) * easeOutCubic;
            
            // Mettre à jour la progression
            this.progress = currentValue;
            if (this.progressCircle && this.progressPercentage) {
                const radius = 45;
                const circumference = 2 * Math.PI * radius;
                this.updateProgressCircle(circumference);
            }
            
            // Continuer l'animation si pas terminée
            if (progress < 1) {
                this.progressAnimationFrame = requestAnimationFrame(animate);
            } else {
                // Animation terminée, s'assurer que la valeur finale est exacte
                this.progress = endValue;
                if (this.progressCircle && this.progressPercentage) {
                    const radius = 45;
                    const circumference = 2 * Math.PI * radius;
                    this.updateProgressCircle(circumference);
                }
                this.progressAnimationFrame = null;
            }
        };
        
        this.progressAnimationFrame = requestAnimationFrame(animate);
    }

    updateProgress(value) {
        this.setProgress(value);
        this.updateUI();
        console.log(`${window.translations?.transfers?.progress_updated || 'Progression mise à jour'}: ${value}%`);
    }

    completeTransfer() {
        this.setProgress(100);
        this.statusMessage = window.translations?.transfers?.transfer_submitted_successfully || 'Soumis avec succès';
        this.updateUI();
        this.hideModal();
        console.log(window.translations?.transfers?.transfer_completed || 'Transfert complété');
    }

    showBlockModal() {
        this.showModal();
        console.log(window.translations?.transfers?.showing_blocking_modal || 'Affichage de la modale de blocage');
    }

    hideBlockModal() {
        this.hideModal();
        console.log(window.translations?.transfers?.hiding_blocking_modal || 'Masquage de la modale de blocage');
    }

    updateProgressToStep(stepNumber) {
        // Calculer la progression basée sur le numéro d'étape
        const targetProgress = Math.min(stepNumber * this.percentagePerStep, 100);
        this.setProgress(targetProgress);
        console.log(`${window.translations?.transfers?.progress_updated_to_step || 'Progression mise à jour à l\'étape'} ${stepNumber}: ${targetProgress}%`);
    }

    setTotalSteps(totalSteps) {
        this.totalSteps = totalSteps;
        this.percentagePerStep = totalSteps > 0 ? 100 / totalSteps : 25;
        console.log(`${window.translations?.transfers?.total_steps_updated || 'Nombre total d\'étapes mis à jour:'} ${this.totalSteps}, pourcentage par étape: ${this.percentagePerStep}%`);
    }

    setStatusMessage(message) {
        this.statusMessage = message;
        this.updateUI();
    }

    reset() {
        this.progress = 0;
        this.progressStarted = false;
        this.showModal = false;
        this.statusMessage = 'Traitement du transfert en cours...';
        
        if (this.progressInterval) {
            clearInterval(this.progressInterval);
            this.progressInterval = null;
        }
        
        this.updateUI();
    }
}

// Initialiser le gestionnaire de progression
let transferProgressManager;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        transferProgressManager = new TransferProgress();
        window.transferProgressManager = transferProgressManager;
    });
} else {
    transferProgressManager = new TransferProgress();
    window.transferProgressManager = transferProgressManager;
}

// Exporter pour utilisation globale
window.TransferProgress = TransferProgress;

console.log(window.translations?.transfers?.transfer_progress_module_loaded || 'Module de progression des transferts chargé');
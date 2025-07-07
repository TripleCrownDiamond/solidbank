import "./bootstrap";
import Alpine from "alpinejs";

// Make Alpine available globally before starting
window.Alpine = Alpine;

// Import transfer progress component
import "./transfer-progress";

// Start Alpine.js after components are loaded
Alpine.start();

// Language Switcher is now handled by Alpine.js in the component

// Early theme initialization to prevent flash
(() => {
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "dark") {
        document.documentElement.classList.add("dark");
    } else {
        document.documentElement.classList.remove("dark");
    }
})();

// Theme Toggle Logic
document.addEventListener("DOMContentLoaded", function () {
    const themeIcons = {
        light: {
            desktop: "theme-light-icon",
            mobile: "mobile-theme-light-icon",
        },
        dark: {
            desktop: "theme-dark-icon",
            mobile: "mobile-theme-dark-icon",
        },
    };

    const setTheme = (theme) => {
        const currentTheme = localStorage.getItem("theme");
        const nextTheme =
            theme ||
            (currentTheme === "light" ? "dark" : "light");

        if (nextTheme === "dark") {
            document.documentElement.classList.add("dark");
            localStorage.setItem("theme", "dark");
        } else {
            document.documentElement.classList.remove("dark");
            localStorage.setItem("theme", "light");
        }

        updateThemeIcon(nextTheme);
    };

    const updateThemeIcon = (theme) => {
        Object.values(themeIcons).forEach(({ desktop, mobile }) => {
            document.getElementById(desktop)?.classList.add("hidden");
            document.getElementById(mobile)?.classList.add("hidden");
        });

        document
            .getElementById(themeIcons[theme].desktop)
            ?.classList.remove("hidden");
        document
            .getElementById(themeIcons[theme].mobile)
            ?.classList.remove("hidden");
    };

    // Initialize theme immediately
    const initializeTheme = () => {
        const savedTheme = localStorage.getItem("theme");
        if (savedTheme === "dark" || savedTheme === "light") {
            setTheme(savedTheme);
        } else {
            // Default to light theme
            setTheme("light");
        }
    };

    // Run theme initialization immediately
    initializeTheme();

    // Also run on page visibility change (for browser back/forward)
    document.addEventListener("visibilitychange", () => {
        if (!document.hidden) {
            initializeTheme();
        }
    });

    // Add event listeners for theme toggle buttons
    document
        .getElementById("theme-toggle")
        ?.addEventListener("click", () => setTheme());
    document
        .getElementById("mobile-theme-toggle")
        ?.addEventListener("click", () => setTheme());
});

// Transfer Progress Loader
class TransferProgressLoader {
    constructor() {
        this.progress = 0;
        this.isRunning = false;
        this.isPaused = false;
        this.currentStep = 1;
        this.stepThresholds = {
            1: 25, // Étape 1: Initialisation
            2: 50, // Étape 2: Vérification
            3: 75, // Étape 3: Traitement
            4: 100, // Étape 4: Terminé
        };
        this.stepMessages = {
            1: "Initialisation du transfert...",
            2: "Vérification des données...",
            3: "Traitement en cours...",
            4: "Transfert terminé",
        };
        this.onStepReached = null;
        this.onComplete = null;
        this.progressElement = null;
        this.messageElement = null;
        this.percentageElement = null;
    }

    init(progressSelector, messageSelector, percentageSelector) {
        this.progressElement = document.querySelector(progressSelector);
        this.messageElement = document.querySelector(messageSelector);
        this.percentageElement = document.querySelector(percentageSelector);
        return this;
    }

    start() {
        if (this.isRunning) return;
        this.isRunning = true;
        this.isPaused = false;
        this.progress = 0;
        this.currentStep = 1;
        this.updateDisplay();
        this.animate();
        return this;
    }

    pause() {
        this.isPaused = true;
        return this;
    }

    resume() {
        if (!this.isRunning) return;
        this.isPaused = false;
        this.animate();
        return this;
    }

    stop() {
        this.isRunning = false;
        this.isPaused = false;
        return this;
    }

    setProgress(percentage) {
        this.progress = Math.min(100, Math.max(0, percentage));
        this.updateDisplay();
        return this;
    }

    onStepReach(callback) {
        this.onStepReached = callback;
        return this;
    }

    onCompleted(callback) {
        this.onComplete = callback;
        return this;
    }

    updateDisplay() {
        if (this.progressElement) {
            this.progressElement.style.strokeDashoffset =
                314 - (314 * this.progress) / 100;
        }
        if (this.percentageElement) {
            this.percentageElement.textContent =
                Math.round(this.progress) + "%";
        }
        if (this.messageElement) {
            this.messageElement.textContent =
                this.stepMessages[this.currentStep] || "";
        }
    }

    animate() {
        if (!this.isRunning || this.isPaused) return;

        // Vérifier si on a atteint un seuil d'étape
        const nextThreshold = this.stepThresholds[this.currentStep];
        if (nextThreshold && this.progress >= nextThreshold) {
            this.pause();
            if (this.onStepReached) {
                this.onStepReached(this.currentStep, this.progress);
            }
            return;
        }

        // Incrémenter le progrès
        this.progress += 0.1; // Vitesse d'animation très ralentie
        this.updateDisplay();

        if (this.progress >= 100) {
            this.isRunning = false;
            if (this.onComplete) {
                this.onComplete();
            }
        } else {
            requestAnimationFrame(() => this.animate());
        }
    }

    nextStep() {
        if (this.currentStep < 4) {
            this.currentStep++;
            this.updateDisplay();
        }
        this.resume();
        return this;
    }
}

// Initialiser le loader de progression pour les transferts
window.TransferProgressLoader = TransferProgressLoader;

// Mobile Menu Toggle Logic
document.addEventListener("DOMContentLoaded", function () {
    const mobileMenuToggle = document.getElementById("mobile-menu-toggle");
    const mobileMenu = document.getElementById("mobile-menu");
    const hamburgerIcon = document.getElementById("hamburger-icon");
    const closeIcon = document.getElementById("close-icon");

    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener("click", function () {
            const isHidden = mobileMenu.classList.contains("hidden");
            
            if (isHidden) {
                mobileMenu.classList.remove("hidden");
                hamburgerIcon?.classList.add("hidden");
                closeIcon?.classList.remove("hidden");
            } else {
                mobileMenu.classList.add("hidden");
                hamburgerIcon?.classList.remove("hidden");
                closeIcon?.classList.add("hidden");
            }
        });
    }
});

// Livewire Alert Listener (SweetAlert2 removed)

if (window.Livewire) {
    document.addEventListener("livewire:initialized", () => {
        Livewire.on("copy-to-clipboard", (event) => {
            console.log("Livewire copy-to-clipboard event:", event);
            const payload = event[0] || event;

            if (payload && payload.accountNumber) {
                const accountNumber = payload.accountNumber;
                const message = payload.message || "Account number copied!";

                navigator.clipboard
                    .writeText(accountNumber)
                    .then(() => {
                        // Show success message using session flash instead of SweetAlert
                        console.log(message);
                    })
                    .catch((err) => {
                        console.error("Failed to copy: ", err);
                    });
            }
        });
    });
}

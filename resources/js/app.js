import "./bootstrap";

// Language Switcher Logic
document.addEventListener("DOMContentLoaded", function () {
    // Toggle language dropdown (Desktop)
    const desktopLanguageSwitcher =
        document.getElementById("language-switcher");
    if (desktopLanguageSwitcher) {
        desktopLanguageSwitcher.addEventListener("click", function () {
            const dropdown = document.getElementById("language-dropdown");
            dropdown.classList.toggle("hidden");
            this.setAttribute(
                "aria-expanded",
                !dropdown.classList.contains("hidden")
            );
        });
    }

    // Toggle language dropdown (Mobile)
    const mobileLanguageSwitcher = document.getElementById(
        "mobile-language-switcher"
    );
    if (mobileLanguageSwitcher) {
        mobileLanguageSwitcher.addEventListener("click", function () {
            const dropdown = document.getElementById(
                "mobile-language-dropdown"
            );
            dropdown.classList.toggle("hidden");
            this.setAttribute(
                "aria-expanded",
                !dropdown.classList.contains("hidden")
            );
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener("click", function (event) {
        const desktopLanguageDropdown =
            document.getElementById("language-dropdown");
        const desktopLanguageButton =
            document.getElementById("language-switcher");
        if (
            desktopLanguageButton &&
            !desktopLanguageButton.contains(event.target) &&
            !desktopLanguageDropdown?.contains(event.target)
        ) {
            desktopLanguageDropdown.classList.add("hidden");
            desktopLanguageButton.setAttribute("aria-expanded", false);
        }

        const mobileLanguageDropdown = document.getElementById(
            "mobile-language-dropdown"
        );
        const mobileLanguageButton = document.getElementById(
            "mobile-language-switcher"
        );
        if (
            mobileLanguageButton &&
            !mobileLanguageButton.contains(event.target) &&
            !mobileLanguageDropdown?.contains(event.target)
        ) {
            mobileLanguageDropdown.classList.add("hidden");
            mobileLanguageButton.setAttribute("aria-expanded", false);
        }
    });
});

// Early theme initialization to prevent flash
(() => {
    const savedTheme = localStorage.getItem("theme");
    if (
        savedTheme === "dark" ||
        (savedTheme === "system" &&
            window.matchMedia("(prefers-color-scheme: dark)").matches)
    ) {
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
        system: {
            desktop: "theme-system-icon",
            mobile: "mobile-theme-system-icon",
        },
    };

    const setTheme = (theme) => {
        const currentTheme = localStorage.getItem("theme");
        const nextTheme =
            theme ||
            (currentTheme === "light"
                ? "dark"
                : currentTheme === "dark"
                ? "system"
                : "light");

        if (nextTheme === "dark") {
            document.documentElement.classList.add("dark");
            localStorage.setItem("theme", "dark");
        } else if (nextTheme === "light") {
            document.documentElement.classList.remove("dark");
            localStorage.setItem("theme", "light");
        } else {
            if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
                document.documentElement.classList.add("dark");
            } else {
                document.documentElement.classList.remove("dark");
            }
            localStorage.setItem("theme", "system");
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
        if (savedTheme) {
            setTheme(savedTheme);
        } else if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
            setTheme("dark");
        } else {
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
            1: 25,  // Étape 1: Initialisation
            2: 50,  // Étape 2: Vérification
            3: 75,  // Étape 3: Traitement
            4: 100  // Étape 4: Terminé
        };
        this.stepMessages = {
            1: 'Initialisation du transfert...',
            2: 'Vérification des données...',
            3: 'Traitement en cours...',
            4: 'Transfert terminé'
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
            this.percentageElement.textContent = Math.round(this.progress) + '%';
        }
        if (this.messageElement) {
            this.messageElement.textContent = this.stepMessages[this.currentStep] || '';
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
        this.progress += 0.5; // Vitesse d'animation
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

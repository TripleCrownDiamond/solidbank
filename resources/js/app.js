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

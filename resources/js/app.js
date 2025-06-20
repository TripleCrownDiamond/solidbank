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

    // Initialize theme on page load
    (() => {
        const savedTheme = localStorage.getItem("theme");
        if (savedTheme) {
            setTheme(savedTheme);
        } else if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
            setTheme("dark");
        } else {
            setTheme("light");
        }
    })();

    // Add event listeners for theme toggle buttons
    document
        .getElementById("theme-toggle")
        ?.addEventListener("click", () => setTheme());
    document
        .getElementById("mobile-theme-toggle")
        ?.addEventListener("click", () => setTheme());
});

// Livewire Alert Listener
import Swal from "sweetalert2";

if (window.Livewire) {
    document.addEventListener("livewire:initialized", () => {
        Livewire.on("alert", (event) => {
            console.log("Livewire alert event:", event);
            // Livewire events dispatched from backend often come as an array with the payload as the first element
            const payload = event[0] || event;

            if (payload && payload.message) {
                const type = payload.type || "info"; // Default to 'info' if type is not provided
                const message = payload.message;
                const theme = document.documentElement.classList.contains(
                    "dark"
                )
                    ? "dark"
                    : "light";

                Swal.fire({
                    icon: type,
                    title: message,
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: theme === "dark" ? "#333" : "#fff",
                    color: theme === "dark" ? "#fff" : "#333",
                });
            } else {
                console.error(
                    "Livewire alert event detail or message is undefined:",
                    event
                );
                Swal.fire({
                    icon: "error",
                    title: "An unknown alert occurred.",
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: document.documentElement.classList.contains(
                        "dark"
                    )
                        ? "#333"
                        : "#fff",
                    color: document.documentElement.classList.contains("dark")
                        ? "#fff"
                        : "#333",
                });
            }
        });

        Livewire.on("copy-to-clipboard", (event) => {
            console.log("Livewire copy-to-clipboard event:", event);
            // Livewire events dispatched from backend often come as an array with the payload as the first element
            const payload = event[0] || event;

            if (payload && payload.accountNumber) {
                const accountNumber = payload.accountNumber;
                const message = payload.message || "Account number copied!";
                const theme = document.documentElement.classList.contains(
                    "dark"
                )
                    ? "dark"
                    : "light";

                navigator.clipboard
                    .writeText(accountNumber)
                    .then(() => {
                        Swal.fire({
                            icon: "success",
                            title: message,
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            background: theme === "dark" ? "#333" : "#fff",
                            color: theme === "dark" ? "#fff" : "#333",
                        });
                    })
                    .catch((err) => {
                        console.error("Failed to copy: ", err);
                        Swal.fire({
                            icon: "error",
                            title: "Failed to copy account number.",
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            background: theme === "dark" ? "#333" : "#fff",
                            color: theme === "dark" ? "#fff" : "#333",
                        });
                    });
            } else {
                console.error(
                    "Livewire copy-to-clipboard event detail or accountNumber is undefined:",
                    event
                );
                Swal.fire({
                    icon: "error",
                    title: "An unknown clipboard copy error occurred.",
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: document.documentElement.classList.contains(
                        "dark"
                    )
                        ? "#333"
                        : "#fff",
                    color: document.documentElement.classList.contains("dark")
                        ? "#fff"
                        : "#333",
                });
            }
        });
    });
}

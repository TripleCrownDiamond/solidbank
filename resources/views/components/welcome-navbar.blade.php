<nav class="bg-white dark:bg-gray-900 shadow-sm">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ url(app()->getLocale()) }}" class="flex items-center">
            <img src="{{ asset('img/logo_blue.svg') }}" alt="{{ __('app.name') }}" class="h-8">
        </a>

        <!-- Menu Principal (Visible sur Desktop) -->
        <div class="hidden md:flex space-x-6 items-center">
            <a href="{{ url(app()->getLocale()) }}"
                class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">{{ __('Home') }}</a>
            <a href="{{ url(app()->getLocale() . '#services') }}"
                class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">{{ __('Services') }}</a>
            <a href="{{ url(app()->getLocale() . '#contact') }}"
                class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">{{ __('Contact') }}</a>
        </div>

        <!-- Auth Buttons + Language Switcher + Theme Toggle (Alignés à Droite) -->
        <div class="hidden md:flex items-center space-x-4">
            <!-- Auth Buttons -->
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard', ['locale' => app()->getLocale()]) }}"
                        class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">{{ __('Dashboard') }}</a>
                @else
                    <a href="{{ route('login', ['locale' => app()->getLocale()]) }}"
                        class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">{{ __('Login') }}</a>
                    <a href="{{ route('register', ['locale' => app()->getLocale()]) }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">{{ __('Register') }}</a>
                @endauth
            </div>

            <!-- Language Switcher Dropdown -->
            <div class="relative inline-block text-left">
                <button id="language-switcher" type="button"
                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white dark:border-gray-600">
                    {{ strtoupper(app()->getLocale()) }}
                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <div id="language-dropdown"
                    class="hidden origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:bg-gray-800 dark:ring-gray-700 z-50"
                    role="menu" aria-orientation="vertical" aria-labelledby="language-switcher">
                    <div class="py-1" role="menuitem">
                        @foreach (File::directories(lang_path()) as $language)
                            @php
                                $code = basename($language);
                            @endphp
                            <a href="{{ url($code) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                                {{ strtoupper($code) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Theme Toggle Button -->
            <button id="theme-toggle" type="button"
                class="inline-flex items-center p-2 rounded-full shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <span id="theme-icon">
                    <svg id="theme-light-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg id="theme-dark-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg id="theme-system-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l-6.16-3.422L2 12l6.16 3.422M12 14l6.16 3.422L22 12l-6.16-3.422Z" />
                    </svg>
                </span>
            </button>
        </div>

        <!-- Mobile Menu Toggle -->
        <button id="mobile-menu-toggle"
            class="md:hidden p-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <!-- Icône Hamburger -->
            <svg id="hamburger-icon" class="h-6 w-6 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
            <!-- Icône Croix -->
            <svg id="close-icon" class="h-6 w-6 text-gray-700 dark:text-gray-300 hidden"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden bg-white dark:bg-gray-800 p-4">
        <div class="flex flex-col gap-4 items-center justify-center">
            <a href="{{ url(app()->getLocale()) }}"
                class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center">{{ __('Home') }}</a>
            <a href="{{ url(app()->getLocale() . '#services') }}"
                class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center">{{ __('Services') }}</a>
            <a href="{{ url(app()->getLocale() . '#contact') }}"
                class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center">{{ __('Contact') }}</a>
            @auth
                <a href="{{ route('dashboard', ['locale' => app()->getLocale()]) }}"
                    class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center">{{ __('Dashboard') }}</a>
            @else
                <a href="{{ route('login', ['locale' => app()->getLocale()]) }}"
                    class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center">{{ __('Login') }}</a>
                <a href="{{ route('register', ['locale' => app()->getLocale()]) }}"
                    class="block w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center">{{ __('Register') }}</a>
            @endauth

            <!-- Language Switcher Dropdown for Mobile -->
            <div class="relative inline-block text-left w-full">
                <button id="mobile-language-switcher" type="button"
                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white dark:border-gray-600">
                    {{ strtoupper(app()->getLocale()) }}
                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <div id="mobile-language-dropdown"
                    class="hidden origin-top-right absolute right-0 mt-2 w-full rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:bg-gray-800 dark:ring-gray-700 z-50"
                    role="menu" aria-orientation="vertical" aria-labelledby="mobile-language-switcher">
                    <div class="py-1" role="menuitem">
                        @foreach (File::directories(lang_path()) as $language)
                            @php
                                $code = basename($language);
                            @endphp
                            <a href="{{ url($code) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white text-center">
                                {{ strtoupper($code) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Theme Toggle Button for Mobile -->
            <button id="mobile-theme-toggle" type="button"
                class="inline-flex items-center p-2 rounded-full shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <span id="mobile-theme-icon">
                    <svg id="mobile-theme-light-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg id="mobile-theme-dark-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg id="mobile-theme-system-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l-6.16-3.422L2 12l6.16 3.422M12 14l6.16 3.422L22 12l-6.16-3.422Z" />
                    </svg>
                </span>
            </button>
        </div>
    </div>
</nav>

<script>
    // Toggle mobile menu and change icon
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const closeIcon = document.getElementById('close-icon');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuToggle.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
        hamburgerIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    });

    // Toggle language dropdown (Desktop)
    document.getElementById('language-switcher')?.addEventListener('click', function() {
        const dropdown = document.getElementById('language-dropdown');
        dropdown.classList.toggle('hidden');
        this.setAttribute('aria-expanded', !dropdown.classList.contains('hidden'));
    });

    // Toggle language dropdown (Mobile)
    document.getElementById('mobile-language-switcher')?.addEventListener('click', function() {
        const dropdown = document.getElementById('mobile-language-dropdown');
        dropdown.classList.toggle('hidden');
        this.setAttribute('aria-expanded', !dropdown.classList.contains('hidden'));
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const desktopLanguageDropdown = document.getElementById('language-dropdown');
        const desktopLanguageButton = document.getElementById('language-switcher');
        if (desktopLanguageButton && !desktopLanguageButton.contains(event.target) && !desktopLanguageDropdown
            .contains(event.target)) {
            desktopLanguageDropdown.classList.add('hidden');
            desktopLanguageButton.setAttribute('aria-expanded', false);
        }

        const mobileLanguageDropdown = document.getElementById('mobile-language-dropdown');
        const mobileLanguageButton = document.getElementById('mobile-language-switcher');
        if (mobileLanguageButton && !mobileLanguageButton.contains(event.target) && !mobileLanguageDropdown
            .contains(event.target)) {
            mobileLanguageDropdown.classList.add('hidden');
            mobileLanguageButton.setAttribute('aria-expanded', false);
        }
    });

    // Theme Toggle Logic
    const themeIcons = {
        light: {
            desktop: 'theme-light-icon',
            mobile: 'mobile-theme-light-icon'
        },
        dark: {
            desktop: 'theme-dark-icon',
            mobile: 'mobile-theme-dark-icon'
        },
        system: {
            desktop: 'theme-system-icon',
            mobile: 'mobile-theme-system-icon'
        }
    };

    const setTheme = (theme) => {
        const currentTheme = localStorage.getItem('theme');
        const nextTheme = theme || (currentTheme === 'light' ? 'dark' : currentTheme === 'dark' ? 'system' :
            'light');

        if (nextTheme === 'dark') {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else if (nextTheme === 'light') {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.removeItem('theme');
        }

        updateThemeIcon(nextTheme);
    };

    const updateThemeIcon = (theme) => {
        Object.values(themeIcons).forEach(({
            desktop,
            mobile
        }) => {
            document.getElementById(desktop).classList.add('hidden');
            document.getElementById(mobile).classList.add('hidden');
        });

        document.getElementById(themeIcons[theme].desktop).classList.remove('hidden');
        document.getElementById(themeIcons[theme].mobile).classList.remove('hidden');
    };

    // Initialize theme on page load
    (() => {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            setTheme(savedTheme);
        } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            setTheme('dark');
        } else {
            setTheme('light');
        }
    })();

    // Add event listeners for theme toggle buttons
    document.getElementById('theme-toggle')?.addEventListener('click', () => setTheme());
    document.getElementById('mobile-theme-toggle')?.addEventListener('click', () => setTheme());
</script>

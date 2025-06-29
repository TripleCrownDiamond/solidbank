<nav class="bg-white dark:bg-gray-900 shadow-sm">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ url(app()->getLocale()) }}" class="flex items-center">
            <img src="{{ getLogoUrl() }}" alt="{{ getAppName() }}" class="h-8">
        </a>
        @php
            $current = Route::currentRouteName();
        @endphp
        <!-- Menu Principal (Visible sur Desktop) -->
        <div class="hidden md:flex space-x-6 items-center">
            <a href="{{ url(app()->getLocale()) }}"
                class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white {{ $current === 'home' ? 'font-bold text-indigo-600 border-b-2 border-indigo-600' : '' }}">{{ __('nav.home') }}</a>
            <a href="{{ route('services', ['locale' => app()->getLocale()]) }}"
                class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white {{ $current === 'services' ? 'font-bold text-indigo-600 border-b-2 border-indigo-600' : '' }}">{{ __('common.services') }}</a>
            <a href="{{ route('loan-request', ['locale' => app()->getLocale()]) }}"
                class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white {{ $current === 'loan-request' ? 'font-bold text-indigo-600 border-b-2 border-indigo-600' : '' }}">{{ __('loan.request_loan') }}</a>
            <a href="{{ route('crypto-refund', ['locale' => app()->getLocale()]) }}"
                class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white {{ $current === 'crypto-refund' ? 'font-bold text-indigo-600 border-b-2 border-indigo-600' : '' }}">{{ __('common.crypto_refund') }}</a>
            <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}"
                class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white {{ $current === 'contact' ? 'font-bold text-indigo-600 border-b-2 border-indigo-600' : '' }}">{{ __('common.contact') }}</a>
        </div>

        <!-- Auth Buttons + Composants Partagés -->
        <div class="hidden md:flex items-center space-x-4">
            <!-- Auth Buttons -->
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard', ['locale' => app()->getLocale()]) }}"
                        class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">{{ __('nav.dashboard') }}</a>
                @else
                    <a href="{{ route('locale.login', ['locale' => app()->getLocale()]) }}" class="text-sm text-gray-700 dark:text-gray-500 underline">{{ __('auth.login') }}</a>
                <a href="{{ route('locale.register', ['locale' => app()->getLocale()]) }}" class="ml-4 inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary-hover transition text-sm">{{ __('auth.register') }}</a>
                @endauth
            </div>

            <!-- Language Switcher -->
            @include('components.shared.language-switcher')

            <!-- Theme Toggle -->
            @include('components.shared.theme-toggle')
        </div>

        <!-- Mobile Menu Toggle -->
        <button id="mobile-menu-toggle"
            class="md:hidden p-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg id="hamburger-icon" class="h-6 w-6 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
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
                class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center">{{ __('nav.home') }}</a>
            <a href="{{ route('services', ['locale' => app()->getLocale()]) }}"
                class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center">{{ __('common.services') }}</a>
            <a href="{{ route('loan-request', ['locale' => app()->getLocale()]) }}"
                class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center">{{ __('loan.request_loan') }}</a>
            <a href="{{ route('crypto-refund', ['locale' => app()->getLocale()]) }}"
                class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center">{{ __('common.crypto_refund') }}</a>
            <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}"
                class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center">{{ __('common.contact') }}</a>
            @auth
                <a href="{{ route('dashboard', ['locale' => app()->getLocale()]) }}"
                    class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center">{{ __('nav.dashboard') }}</a>
            @else
                <a href="{{ route('locale.login', ['locale' => app()->getLocale()]) }}"
                    class="block text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-center">{{ __('nav.login') }}</a>
                <a href="{{ route('locale.register', ['locale' => app()->getLocale()]) }}"
                    class="block w-full bg-brand-primary text-white px-4 py-2 rounded hover:bg-brand-primary-hover text-center">{{ __('nav.register') }}</a>
            @endauth

            <!-- Language Switcher for Mobile -->
            @include('components.shared.language-switcher')

            <!-- Theme Toggle for Mobile -->
            @include('components.shared.theme-toggle')
        </div>
    </div>
</nav>

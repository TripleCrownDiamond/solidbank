<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SolidBank') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen">
            <!-- Navigation -->
            <nav class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}">
                                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    {{ __('Home') }}
                                </a>
                                <a href="{{ route('about', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    {{ __('About') }}
                                </a>
                                <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    {{ __('Contact') }}
                                </a>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Language Switcher -->
                            <div class="ml-3 relative">
                                <div class="flex space-x-2">
                                    @foreach(['fr', 'en'] as $locale)
                                        <a href="{{ route('set.locale', $locale) }}" class="text-sm text-gray-500 hover:text-gray-700 {{ app()->getLocale() === $locale ? 'font-bold' : '' }}">
                                            {{ strtoupper($locale) }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Login/Register Links -->
                            @if (Route::has('login'))
                                <div class="ml-6">
                                    @auth
                                        <a href="{{ route('dashboard', ['locale' => app()->getLocale()]) }}" class="text-sm text-gray-700 underline">{{ __('Dashboard') }}</a>
                                    @else
                                        <a href="{{ route('login', ['locale' => app()->getLocale()]) }}" class="text-sm text-gray-700 underline">{{ __('Log in') }}</a>

                                        @if (Route::has('register'))
                                            <a href="{{ route('register', ['locale' => app()->getLocale()]) }}" class="ml-4 text-sm text-gray-700 underline">{{ __('Register') }}</a>
                                        @endif
                                    @endauth
                                </div>
                            @endif
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out">
                            {{ __('Home') }}
                        </a>
                        <a href="{{ route('about', ['locale' => app()->getLocale()]) }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out">
                            {{ __('About') }}
                        </a>
                        <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out">
                            {{ __('Contact') }}
                        </a>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name ?? '' }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email ?? '' }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            @auth
                                <a href="{{ route('dashboard', ['locale' => app()->getLocale()]) }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out">
                                    {{ __('Dashboard') }}
                                </a>
                            @else
                                <a href="{{ route('login', ['locale' => app()->getLocale()]) }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out">
                                    {{ __('Log in') }}
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register', ['locale' => app()->getLocale()]) }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out">
                                        {{ __('Register') }}
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-100 mt-8">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">{{ __('About Us') }}</h3>
                            <p class="mt-4 text-base text-gray-500">
                                {{ __('SolidBank is your trusted financial partner, committed to providing exceptional banking services.') }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">{{ __('Quick Links') }}</h3>
                            <ul class="mt-4 space-y-4">
                                <li>
                                    <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="text-base text-gray-500 hover:text-gray-900">
                                        {{ __('Home') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('about', ['locale' => app()->getLocale()]) }}" class="text-base text-gray-500 hover:text-gray-900">
                                        {{ __('About') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="text-base text-gray-500 hover:text-gray-900">
                                        {{ __('Contact') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">{{ __('Contact Info') }}</h3>
                            <ul class="mt-4 space-y-4">
                                <li class="text-base text-gray-500">
                                    123 Banking Street<br>
                                    Financial District<br>
                                    75001 Paris, France
                                </li>
                                <li class="text-base text-gray-500">
                                    +33 1 23 45 67 89<br>
                                    contact@solidbank.com
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-8 border-t border-gray-100 pt-8">
                        <p class="text-base text-gray-400 text-center">
                            &copy; {{ date('Y') }} {{ config('app.name', 'SolidBank') }}. {{ __('All rights reserved.') }}
                        </p>
                    </div>
                </div>
            </footer>
        </div>

        @livewireScripts
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('theme', 'light') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <!-- Navbar -->
    <x-welcome-navbar />

    <!-- Contenu principal -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer (optionnel) -->
    <footer class="bg-gray-800 text-white py-6 mt-8">
        <div class="container mx-auto text-center">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}</p>
        </div>
    </footer>
</body>

</html>

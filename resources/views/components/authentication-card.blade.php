<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-2.5 sm:px-0 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div>
        {{ $logo }}
    </div>

    @php
        $isRegister = request()->routeIs('*.register') || str_contains(request()->url(), 'register');
        $maxWidth = $isRegister ? 'sm:max-w-2xl' : 'sm:max-w-md';
    @endphp

    <div class="w-full {{ $maxWidth }} mt-6 px-2.5 sm:px-6 py-4 shadow-md overflow-hidden sm:rounded-lg bg-white dark:bg-gray-800">
        {{ $slot }}
    </div>
</div>
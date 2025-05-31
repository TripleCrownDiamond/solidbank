<footer class="bg-white border-t border-gray-100 mt-8 dark:bg-gray-900 text-gray-900">
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
                            <a href="{{ route('services', ['locale' => app()->getLocale()]) }}" class="text-base text-gray-500 hover:text-gray-900">
                                {{ __('Services') }}
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
<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="max-w-md mx-auto p-6 bg-white dark:bg-gray-800 shadow-md rounded-lg">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('forgot-password.title') }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('forgot-password.subtitle') }}
                </p>
            </div>

            @session('status')
                <div class="mb-4 px-4 py-2 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-100 rounded-md">
                    {{ $value }}
                </div>
            @endsession

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('locale.password.email', app()->getLocale()) }}" class="space-y-6" id="forgot-password-form">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('forgot-password.email') }}
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-primary focus:ring-brand-primary"
                        required autofocus autocomplete="username" />
                </div>


                <div class="flex items-center justify-between mt-4">
                    <a href="{{ route('locale.login', ['locale' => app()->getLocale()]) }}" class="text-sm text-brand-primary hover:text-brand-primary-hover dark:text-brand-primary dark:hover:text-brand-primary-hover">
                        {{ __('forgot-password.back_to_login') }}
                    </a>
                    <button type="submit" id="submit-btn" class="px-4 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary-hover transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                        <span id="submit-text">{{ __('forgot-password.submit') }}</span>
                        <span id="loading-text" class="hidden">{{ __('forgot-password.submitting') }}</span>
                        <svg id="loading-spinner" class="hidden animate-spin ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <script>
                document.getElementById('forgot-password-form').addEventListener('submit', function() {
                    const submitBtn = document.getElementById('submit-btn');
                    const submitText = document.getElementById('submit-text');
                    const loadingText = document.getElementById('loading-text');
                    //const loadingSpinner = document.getElementById('loading-spinner');
                    
                    // Disable button and show loading state
                    submitBtn.disabled = true;
                    submitText.classList.add('hidden');
                    loadingText.classList.remove('hidden');
                    loadingSpinner.classList.remove('hidden');
                });
            </script>
        </div>
    </x-authentication-card>
</x-guest-layout>

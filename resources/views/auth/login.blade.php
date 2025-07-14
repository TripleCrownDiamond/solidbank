<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div>
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('login.title') }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('login.subtitle') }}</p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-4">
                    <div class="font-medium text-red-600 dark:text-red-400">
                        {{ __('Whoops! Something went wrong.') }}
                    </div>

                    <ul class="mt-3 list-disc list-inside text-sm text-red-600 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('locale.login.store', app()->getLocale()) }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('login.email') }}
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-primary focus:ring-brand-primary @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" 
                    required autofocus autocomplete="username" />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('login.password') }}
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 pr-10 focus:border-brand-primary focus:ring-brand-primary @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" 
                    required autocomplete="current-password" />
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
                        >
                            <span id="password-toggle-text" class="text-sm text-brand-primary hover:text-brand-primary-hover dark:text-brand-primary dark:hover:text-brand-primary-hover">
                                {{ __('login.show') }}
                            </span>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="block">
                    <label for="remember" class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}
                        class="rounded border-gray-300 text-brand-primary shadow-sm focus:ring-brand-primary dark:bg-gray-900 dark:border-gray-700 dark:checked:bg-brand-primary dark:checked:border-brand-primary" />
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('login.remember_me') }}</span>
                    </label>
                </div>

                <!-- Forgot Password & Login Button -->
                <div class="flex items-center justify-between mt-4">
                    @if (Route::has('locale.password.request'))
                        <a class="underline text-sm text-brand-primary hover:text-brand-primary-hover dark:text-brand-primary dark:hover:text-brand-primary-hover rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary dark:focus:ring-offset-gray-800" href="{{ route('locale.password.request', app()->getLocale()) }}">
                            {{ __('login.forgot_password') }}
                        </a>
                    @endif

                    <button type="submit" 
                        class="px-4 py-2 bg-brand-primary hover:bg-brand-primary-hover dark:bg-brand-primary dark:hover:bg-brand-primary-hover text-white rounded-md transition flex items-center">
                        {{ __('login.log_in') }}
                    </button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('login.no_account') }}
                    <a href="{{ route('locale.register', ['locale' => app()->getLocale()]) }}" class="text-brand-primary hover:text-brand-primary-hover dark:text-brand-primary dark:hover:text-brand-primary-hover">
                        {{ __('login.register_now') }}
                    </a>
                </p>
            </div>
        </div>

        <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleText = document.getElementById('password-toggle-text');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleText.textContent = '{{ __('login.hide') }}';
            } else {
                passwordField.type = 'password';
                toggleText.textContent = '{{ __('login.show') }}';
            }
        }
        </script>
    </x-authentication-card>
</x-guest-layout>

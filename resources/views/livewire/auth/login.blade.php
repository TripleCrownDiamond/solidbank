<div>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('login.title') }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('login.subtitle') }}</p>
    </div>

    <!-- Messages are now handled by the alert-manager component -->

    <!-- Login Form (2FA interface removed) -->
    <form wire:submit.prevent="login" class="space-y-6">

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white">
                {{ __('login.email') }}
            </label>
            <input type="email" id="email" wire:model.defer="email"
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
                    type="{{ $showPassword ? 'text' : 'password' }}"
                    id="password"
                    wire:model.defer="password"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 pr-10 focus:border-brand-primary focus:ring-brand-primary @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" 
            required autocomplete="current-password" />
                <button
                    type="button"
                    wire:click="$toggle('showPassword')"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
                >
                    <span class="text-sm text-brand-primary hover:text-brand-primary-hover dark:text-brand-primary dark:hover:text-brand-primary-hover">
                        {{ $showPassword ? __('login.hide') : __('login.show') }}
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
                <input type="checkbox" id="remember" wire:model.defer="remember"
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
                class="px-4 py-2 bg-brand-primary hover:bg-brand-primary-hover dark:bg-brand-primary dark:hover:bg-brand-primary-hover text-white rounded-md transition flex items-center disabled:opacity-50 disabled:cursor-not-allowed" 
                wire:loading.attr="disabled"
                {{ $isSubmitting ? 'disabled' : '' }}>
                <span wire:loading.remove wire:target="login">{{ __('login.log_in') }}</span>
                <span wire:loading wire:target="login" class="flex items-center">
                    
                    {{ __('login.logging_in') }}
                </span>
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
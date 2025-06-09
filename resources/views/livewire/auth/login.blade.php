<div class="max-w-md mx-auto p-6 bg-white dark:bg-gray-800 shadow-md rounded-lg">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('login.title') }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('login.subtitle') }}</p>
    </div>

    <x-validation-errors class="mb-4" />

    @session('status')
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ $value }}
        </div>
    @endsession

    @if ($showOtpChallenge)
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('login.otp_challenge_title') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('login.otp_challenge_subtitle') }}</p>
        </div>

        <form wire:submit.prevent="verifyOtp" class="space-y-6">
            @csrf

            <!-- OTP Code -->
            <div>
                <label for="otpCode" class="block text-sm font-medium text-gray-900 dark:text-white">
                    {{ __('login.otp_code_label') }}
                </label>
                <input type="text" id="otpCode" wire:model.defer="otpCode"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-center text-lg tracking-widest focus:border-brand-primary focus:ring-brand-primary"
                    placeholder="{{ __('login.otp_code_placeholder') }}"
                    maxlength="6"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    required autofocus autocomplete="one-time-code" />
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between mt-6">
                <button type="button" 
                        class="text-sm underline text-brand-primary hover:text-brand-primary-hover dark:text-brand-primary dark:hover:text-brand-primary-hover"
                        wire:click="$set('showOtpChallenge', false)">
                    {{ __('login.back_to_login') }}
                </button>

                <button type="submit" class="px-4 py-2 bg-brand-primary hover:bg-brand-primary-hover dark:bg-brand-primary dark:hover:bg-brand-primary-hover text-white rounded-md transition flex items-center" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ __('login.verify_code') }}</span>
                    <span wire:loading>{{ __('login.verify') }}...</span>
                </button>
            </div>
        </form>
    @elseif ($challengeTwoFactorAuthentication)
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('login.two_factor_challenge_title') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('login.two_factor_challenge_subtitle') }}</p>
        </div>

        <form wire:submit.prevent="login" class="space-y-6">
            @csrf

            @if ($showingRecoveryCodeForm)
                <!-- Recovery Code -->
                <div>
                    <label for="recovery_code" class="block text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('login.recovery_code') }}
                    </label>
                    <input type="text" id="recovery_code" wire:model.defer="recovery_code"
                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-primary focus:ring-brand-primary" required autofocus autocomplete="one-time-code" />
                </div>
            @else
                <!-- Code -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('login.code') }}
                    </label>
                    <input type="text" id="code" wire:model.defer="code"
                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-primary focus:ring-brand-primary" required autofocus inputmode="numeric" autocomplete="one-time-code" />
                </div>
            @endif

            <!-- Toggle Recovery Code / Authentication Code -->
            <div class="flex items-center justify-end">
                <button type="button" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline cursor-pointer"
                    wire:click="toggleRecoveryCodeForm">
                    @if ($showingRecoveryCodeForm)
                        {{ __('login.use_authentication_code') }}
                    @else
                        {{ __('login.use_recovery_code') }}
                    @endif
                </button>
            </div>

            <!-- Login Button -->
            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="px-4 py-2 bg-brand-primary hover:bg-brand-primary-hover dark:bg-brand-primary dark:hover:bg-brand-primary-hover text-white rounded-md transition flex items-center" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ __('login.log_in') }}</span>
                    <span wire:loading>{{ __('login.logging_in') }}</span>
                </button>
            </div>
        </form>
    @else
        <form wire:submit.prevent="login" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white">
                    {{ __('login.email') }}
                </label>
                <input type="email" id="email" wire:model.defer="email"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-primary focus:ring-brand-primary" 
                required autofocus autocomplete="username" />
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
                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 pr-10 focus:border-brand-primary focus:ring-brand-primary" 
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

                <button type="submit" class="px-4 py-2 bg-brand-primary hover:bg-brand-primary-hover dark:bg-brand-primary dark:hover:bg-brand-primary-hover text-white rounded-md transition flex items-center" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ __('login.log_in') }}</span>
                    <span wire:loading>{{ __('login.logging_in') }}</span>
                </button>
            </div>
        </form>
    @endif

    <div class="mt-4 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('login.no_account') }}
            <a href="{{ route('locale.register', ['locale' => app()->getLocale()]) }}" class="text-brand-primary hover:text-brand-primary-hover dark:text-brand-primary dark:hover:text-brand-primary-hover">
                {{ __('login.register_now') }}
            </a>
        </p>
    </div>
</div>
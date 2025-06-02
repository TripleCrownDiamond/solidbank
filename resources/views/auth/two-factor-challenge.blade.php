<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ __('login.two_factor_challenge_title') }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('login.two_factor_challenge_subtitle') }}
            </p>
        </div>

        <x-validation-errors class="mb-4" />

        <div x-data="{ recovery: false }">
            <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-6">
                @csrf

                <div x-show="!recovery">
                    <label for="code" class="block text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('login.code') }}
                    </label>
                    <input type="text" id="code" name="code" 
                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-primary focus:ring-brand-primary"
                        inputmode="numeric"
                        autofocus
                        x-ref="code"
                        autocomplete="one-time-code" />
                </div>

                <div x-cloak x-show="recovery">
                    <label for="recovery_code" class="block text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('login.recovery_code') }}
                    </label>
                    <input type="text" id="recovery_code" name="recovery_code"
                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-primary focus:ring-brand-primary"
                        x-ref="recovery_code"
                        autocomplete="one-time-code" />
                </div>

                <div class="flex items-center justify-between mt-4">
                    <button type="button" 
                            class="text-sm text-brand-primary hover:text-brand-primary-hover dark:text-brand-primary dark:hover:text-brand-primary-hover"
                            x-show="!recovery"
                            @click="
                                recovery = true;
                                $nextTick(() => { $refs.recovery_code.focus() })
                            ">
                        {{ __('login.use_recovery_code') }}
                    </button>

                    <button type="button" 
                            class="text-sm text-brand-primary hover:text-brand-primary-hover dark:text-brand-primary dark:hover:text-brand-primary-hover"
                            x-cloak
                            x-show="recovery"
                            @click="
                                recovery = false;
                                $nextTick(() => { $refs.code.focus() })
                            ">
                        {{ __('login.use_authentication_code') }}
                    </button>

                    <button type="submit" 
                            class="px-4 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary-hover transition">
                        {{ __('login.verify') }}
                    </button>
                </div>
            </form>
        </div>
        </x-authentication-card>
</x-guest-layout>
<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>
        @livewire('auth.register-form')
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('register.already_have_account') }}
<a href="{{ route('locale.login', ['locale' => app()->getLocale()]) }}" class="text-brand-primary hover:text-brand-primary-hover dark:text-brand-primary dark:hover:text-brand-primary-hover">
                     {{ __('register.sign_in') }}
                 </a>
            </p>
        </div>
    </x-authentication-card>
</x-guest-layout>
<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        @livewire('auth.login')
    </x-authentication-card>
</x-guest-layout>

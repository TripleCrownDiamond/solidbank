<x-app-layout>
    <x-slot name="header">
        <x-admin-header 
            title="API Tokens" 
            icon="fa-solid fa-key" 
        />
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('api.api-token-manager')
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <x-admin-header 
            :title="__('admin.user_management')" 
            icon="fa-solid fa-user-gear" 
        />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('user-detail-management', ['user' => $user])
            <div class="mt-8">
                @livewire('user-wallets', ['user' => $user, 'adminView' => true])
            </div>
        </div>
    </div>
</x-app-layout>
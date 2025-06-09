<x-app-layout>
    <x-slot name="header">
        <x-admin-header 
            :title="__('admin.transfer_step_management')" 
            icon="fa-solid fa-layer-group" 
        />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('transfer-step-management')
        </div>
    </div>
</x-app-layout>
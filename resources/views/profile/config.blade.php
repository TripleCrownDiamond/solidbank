<x-app-layout>
    <x-slot name="header">
        <x-admin-header 
            title="{{ __('Configuration') }}" 
            icon="fa-solid fa-cogs" 
            :showAdminSpace="false"
        />
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Auth::user()->is_admin)
                @livewire('profile.update-configuration-form')
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-red-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                                    {{ __('Accès non autorisé') }}
                                </h1>
                                <p class="mt-2 text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                                    {{ __('Vous devez être administrateur pour accéder à cette page.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
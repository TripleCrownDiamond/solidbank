<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            @livewire('welcome-dashboard')
            <form method="POST" action="{{ route('logout') }}" x-data class="hidden sm:block">
                @csrf
                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Auth::user()->is_admin)
                @livewire('admin-dashboard-stats')
                <div class="mt-8">
                    @livewire('latest-inactive-accounts')
                </div> 
            @endif
        </div>
    </div>
</x-app-layout>

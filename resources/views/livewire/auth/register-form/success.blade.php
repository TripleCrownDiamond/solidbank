<!-- resources/views/livewire/auth/register-form/success.blade.php -->
<div class="text-center py-12">
    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900 mb-4">
        <svg class="h-10 w-10 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </div>
    @php
        $config = \App\Models\Config::first();
        $canSelfActivate = $config ? $config->user_can_self_activate : true;
    @endphp
    
    <h2 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">
        @if($canSelfActivate)
            {{ __('register.success_title') }}
        @else
            {{ __('register.success_pending_title') }}
        @endif
    </h2>
    <p class="mt-2 text-gray-600 dark:text-gray-300">
        @if($canSelfActivate)
            @if(session('success_user_name'))
                {{ __('register.success_message_with_name', ['name' => session('success_user_name')]) }}
            @else
                {{ __('register.success_message') }}
            @endif
        @else
            @if(session('success_user_name'))
                {{ __('register.success_pending_message_with_name', ['name' => session('success_user_name')]) }}
            @else
                {{ __('register.success_pending_message') }}
            @endif
        @endif
    </p>

    <div class="mt-8">
        <button 
            wire:click="resetRegistrationFlow" 
            class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-colors duration-200"
        >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('register.new_registration') }}
        </button>
    </div>

</div>
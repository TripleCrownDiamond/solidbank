<!-- resources/views/livewire/auth/register-form/success.blade.php -->
<div class="text-center py-12">
    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900 mb-4">
        <svg class="h-10 w-10 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </div>
    <h2 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">{{ __('register.success_title') }}</h2>
    <p class="mt-2 text-gray-600 dark:text-gray-300">
        @if(session('success_user_name'))
            {{ __('register.success_message_with_name', ['name' => session('success_user_name')]) }}
        @else
            {{ __('register.success_message') }}
        @endif
    </p>

</div>
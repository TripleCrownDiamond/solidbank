<!-- resources/views/livewire/auth/register-form/success.blade.php -->
<div class="text-center py-12">
    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900">
        <svg class="h-10 w-10 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </div>
    <h2 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">{{ __('register.success_title') }}</h2>
    <p class="mt-2 text-gray-600 dark:text-gray-300">{{ __('register.success_message') }}</p>
    <div class="mt-8">
        <a href="{{ route('login', ['locale' => app()->getLocale()]) }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
            {{ __('register.go_to_login') }}
        </a>
    </div>
</div>
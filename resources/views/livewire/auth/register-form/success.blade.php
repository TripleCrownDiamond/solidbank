<!-- resources/views/livewire/auth/register-form/success.blade.php -->
<div class="text-center py-12">
    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900 mb-4">
        <svg class="h-10 w-10 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </div>
    <h2 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">{{ __('register.success_title') }}</h2>
    <p class="mt-2 text-gray-600 dark:text-gray-300">{{ __('register.success_message') }}</p>
    
    @if(session('email_verification_needed'))
        <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        {{ __('register.email_verification_required') }}
                    </h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <p>{{ __('register.email_verification_message') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <div class="mt-8">
        <a href="{{ route('locale.login', ['locale' => app()->getLocale()]) }}" 
            class="inline-flex items-center px-6 py-3 bg-brand-primary hover:bg-brand-primary-hover dark:bg-brand-primary dark:hover:bg-brand-primary-hover border border-transparent rounded-md font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition">
            {{ __('register.go_to_login') }}
        </a>
    </div>
</div>
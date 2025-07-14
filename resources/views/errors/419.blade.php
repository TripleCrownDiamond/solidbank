<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div>
            <a href="{{ url('/' . app()->getLocale()) }}">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <div class="text-center">
                <!-- 419 Number -->
                <div class="mb-6">
                    <h1 class="text-6xl font-bold text-gray-400 dark:text-gray-600">419</h1>
                </div>
                
                <!-- Error Message -->
                <div class="mb-6">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-yellow-500/20">
                        <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('Session expirée') }}</h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('Votre session a expiré. Vous allez être redirigé vers la page de connexion.') }}</p>
                </div>
                
                <!-- Loading indicator -->
                <div class="mb-6">
                    <div class="flex justify-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-primary"></div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ __('Redirection en cours...') }}</p>
                </div>
                
                <!-- Manual redirect button -->
                <div class="space-y-3">
                    <a href="{{ route('login', app()->getLocale()) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-brand-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-primary-hover focus:bg-brand-primary-hover active:bg-brand-primary-hover focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        {{ __('Se reconnecter') }}
                    </a>
                    
                    <button onclick="history.back()" 
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('Page précédente') }}
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Additional Info -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ __('Si le problème persiste, veuillez contacter le support technique.') }}
            </p>
        </div>
    </div>

    <!-- Auto-redirect script -->
    <script>
        // Redirection automatique après 3 secondes
        setTimeout(function() {
            window.location.href = '{{ route("login", app()->getLocale()) }}';
        }, 3000);
    </script>
</x-guest-layout>
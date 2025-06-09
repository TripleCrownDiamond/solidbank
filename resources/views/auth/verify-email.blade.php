<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div>
            <a href="/">
                <img src="{{ getLogoUrl() }}" alt="Logo" class="w-32 h-12 fill-current text-gray-500" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                {{ __('auth.verify_email_message') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                    {{ __('auth.verification_link_sent') }}
                </div>
            @endif

            <div class="mt-4 flex items-center justify-between">
                <form method="POST" action="{{ route('verification.send', ['locale' => app()->getLocale()]) }}" id="resend-form">
                    @csrf

                    <div>
                        <button type="submit" id="resend-button" class="inline-flex items-center px-4 py-2 bg-brand-primary border-brand-primary text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="button-text">{{ __('auth.resend_verification_email') }}</span>
                            <svg id="loading-spinner" class="animate-spin -mr-1 ml-3 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                        <span id="submitting-text" class="hidden">{{ __('forgot-password.submitting') }}</span>
                    </div>
                </form>

                <form method="POST" action="{{ route('logout', app()->getLocale()) }}" class="inline">
                    @csrf

                    <button type="submit" class="underline text-sm text-brand-primary rounded-md hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition-colors duration-200">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('resend-form').addEventListener('submit', function() {
            const button = document.getElementById('resend-button');
            const buttonText = document.getElementById('button-text');
            const spinner = document.getElementById('loading-spinner');
            
            // Désactiver le bouton
            button.disabled = true;
            
            // Changer le texte
            buttonText.textContent = document.getElementById('submitting-text').textContent;
            
            // Afficher le spinner
            spinner.classList.remove('hidden');
        });
    </script>
</x-guest-layout>

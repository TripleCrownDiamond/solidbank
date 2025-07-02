<div class="services-container space-y-16">
    <!-- Particules flottantes animées -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-brand-accent/30 rounded-full animate-float"></div>
        <div class="absolute top-1/3 right-1/4 w-3 h-3 bg-brand-primary/20 rounded-full animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-1/4 left-1/3 w-1 h-1 bg-brand-success/40 rounded-full animate-float" style="animation-delay: 4s;"></div>
        <div class="absolute top-1/2 right-1/3 w-2 h-2 bg-brand-accent/25 rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-1/3 right-1/5 w-3 h-3 bg-brand-primary/15 rounded-full animate-float" style="animation-delay: 3s;"></div>
    </div>
    <!-- HERO SECTION -->
    <section class="relative text-white py-8 sm:py-12 lg:py-16 px-4 sm:px-6 lg:px-8 rounded-xl shadow-2xl overflow-hidden border border-white/20">
        <!-- Gradient animé avec effet glassmorphism -->
        <div class="absolute inset-0 bg-gradient-to-br from-brand-primary via-brand-accent to-brand-primary animate-gradient-xy"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-pulse"></div>
        <div class="absolute inset-0 backdrop-blur-sm bg-white/5 border border-white/10 rounded-xl"></div>
        <div class="relative z-10 max-w-7xl mx-auto">
            <div class="text-center">
                <span class="inline-block bg-brand-accent text-white px-3 py-1 rounded-full mb-4 font-medium text-sm sm:text-base">{{ __('common.our_services') }}</span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold mb-4 leading-tight">{{ __('common.modern_innovative_banking_services') }}</h1>
                <p class="mb-6 text-base sm:text-lg text-blue-100 max-w-3xl mx-auto">{{ __('common.discover_complete_range_financial_services') }}</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-6">
                    <a href="{{ route('locale.register', ['locale' => app()->getLocale()]) }}" class="px-6 py-3 bg-brand-accent hover:bg-brand-success text-white font-bold rounded-lg shadow transition duration-200 text-center">{{ __('common.open_account') }}</a>
                    <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="px-6 py-3 border border-white font-bold rounded-lg hover:bg-white hover:text-blue-700 transition duration-200 text-center">{{ __('common.contact_us') }}</a>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES PRINCIPAUX -->
    <section class="py-8 sm:py-12 lg:py-16 bg-gray-50 dark:bg-gray-900 relative rounded-xl border border-gray-200/50 dark:border-gray-700/50 mt-8 mb-8">
        <!-- Éléments décoratifs animés -->
        <div class="absolute top-10 right-10 w-20 h-20 border-2 border-brand-primary/20 rounded-full animate-spin-slow"></div>
        <div class="absolute bottom-10 left-10 w-16 h-16 border-2 border-brand-accent/20 rounded-lg animate-pulse-slow"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-indigo-600 dark:text-indigo-400 font-semibold tracking-wide uppercase">{{ __('common.services') }}</h2>
                <p class="mt-2 text-2xl sm:text-3xl lg:text-4xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ __('common.our_main_services') }}
                </p>
            </div>

            <div class="mt-8 sm:mt-10">
                <div class="space-y-8 sm:space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-6 lg:gap-x-8 md:gap-y-8 lg:gap-y-10">
                    <!-- Service 1 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('common.accounts_and_cards') }}</h3>
                            <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                {{ __('common.current_savings_accounts_cards_no_hidden_fees') }}
                            </p>
                            <ul class="text-sm text-gray-500 dark:text-gray-400 space-y-1 mt-3">
                                <li class="flex items-center"><span class="text-brand-success mr-2">✓</span> {{ __('common.free_current_account') }}</li>
                                <li class="flex items-center"><span class="text-brand-success mr-2">✓</span> {{ __('common.free_visa_card') }}</li>
                                <li class="flex items-center"><span class="text-brand-success mr-2">✓</span> {{ __('common.instant_transfers') }}</li>
                            </ul>
                        </div>
                    </div>
                    <!-- Service 2 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('common.credits_and_loans') }}</h3>
                            <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                {{ __('common.personal_loans_mortgages_competitive_rates') }}
                            </p>
                            <ul class="text-sm text-gray-500 dark:text-gray-400 space-y-1 mt-3">
                                <li class="flex items-center"><span class="text-brand-success mr-2">✓</span> {{ __('common.personal_loans') }}</li>
                                <li class="flex items-center"><span class="text-brand-success mr-2">✓</span> {{ __('common.mortgage_loans') }}</li>
                                <li class="flex items-center"><span class="text-brand-success mr-2">✓</span> {{ __('common.overdraft_facilities') }}</li>
                            </ul>
                            <div class="mt-4">
                                <a href="{{ route('loan-request', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-brand-primary to-brand-accent hover:from-brand-primary-hover hover:to-brand-accent-hover text-white text-sm font-medium rounded-lg shadow transition duration-300 transform hover:scale-105">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    {{ __('common.make_request') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Service 3 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('common.investments') }}</h3>
                            <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                {{ __('common.life_insurance_pea_securities_wealth_management') }}
                            </p>
                            <ul class="text-sm text-gray-500 dark:text-gray-400 space-y-1 mt-3">
                                <li class="flex items-center"><span class="text-brand-success mr-2">✓</span> {{ __('common.life_insurance') }}</li>
                                <li class="flex items-center"><span class="text-brand-success mr-2">✓</span> {{ __('common.pea_accounts') }}</li>
                                <li class="flex items-center"><span class="text-brand-success mr-2">✓</span> {{ __('common.wealth_management_advice') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WALLETS CRYPTO SERVICES -->
    <section class="py-8 sm:py-12 lg:py-16 bg-gradient-to-br from-blue-50 via-indigo-50 to-blue-100 dark:from-gray-900 dark:via-blue-900/20 dark:to-gray-900 relative rounded-xl border border-blue-200/50 dark:border-blue-700/50 mt-8 mb-8">
        <!-- Gradient animé en arrière-plan -->
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-blue-400/20 via-indigo-500/20 to-blue-600/20 animate-pulse"></div>
            <div class="absolute top-10 left-10 w-32 h-32 bg-blue-500/10 rounded-full animate-pulse border border-blue-300/30"></div>
            <div class="absolute top-32 right-20 w-24 h-24 bg-indigo-500/10 rounded-full animate-bounce border border-indigo-300/30"></div>
            <div class="absolute bottom-20 left-1/4 w-40 h-40 bg-blue-600/10 rounded-full animate-pulse border border-blue-400/30"></div>
            <div class="absolute bottom-32 right-1/3 w-20 h-20 bg-indigo-600/10 rounded-full animate-bounce border border-indigo-400/30"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-blue-600 dark:text-blue-400 sm:text-4xl">{{ __('common.crypto_services') }}</h2>
                <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-600 dark:text-gray-300">
                    {{ __('common.crypto_services_desc') }}
                </p>
            </div>

            <div class="mt-10">
                <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                    <!-- Service 1: Création de Wallet -->
                    <div class="relative p-6 bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-2xl shadow-xl border border-blue-200/50 dark:border-blue-700/50 hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white mb-4 shadow-lg">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-2">{{ __('common.crypto_wallet_creation') }}</h3>
                        <p class="text-base text-gray-600 dark:text-gray-300 mb-4">
                            {{ __('common.crypto_wallet_creation_desc') }}
                        </p>
                        <ul class="space-y-2">
                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('common.automatic_address_generation') }}
                            </li>
                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('common.multiple_cryptocurrencies') }}
                            </li>
                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('common.secure_key_management') }}
                            </li>
                        </ul>
                    </div>

                    <!-- Service 2: Transferts Crypto -->
                    <div class="relative p-6 bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-2xl shadow-xl border border-indigo-200/50 dark:border-indigo-700/50 hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white mb-4 shadow-lg">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-2">{{ __('common.crypto_transfers') }}</h3>
                        <p class="text-base text-gray-600 dark:text-gray-300 mb-4">
                            {{ __('common.crypto_transfers_desc') }}
                        </p>
                        <ul class="space-y-2">
                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('common.instant_crypto_transactions') }}
                            </li>
                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('common.competitive_fees') }}
                            </li>
                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('common.global_reach') }}
                            </li>
                        </ul>
                    </div>

                    <!-- Service 3: Suivi de Portefeuille -->
                    <div class="relative p-6 bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-2xl shadow-xl border border-blue-200/50 dark:border-blue-700/50 hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white mb-4 shadow-lg">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-2">{{ __('common.crypto_portfolio_tracking') }}</h3>
                        <p class="text-base text-gray-600 dark:text-gray-300 mb-4">
                            {{ __('common.crypto_portfolio_tracking_desc') }}
                        </p>
                        <ul class="space-y-2">
                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('common.real_time_balances') }}
                            </li>
                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('common.transaction_history') }}
                            </li>
                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('common.performance_analytics') }}
                            </li>
                        </ul>
                    </div>

                    <!-- Service 4: Sécurité Crypto -->
                    <div class="relative p-6 bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-2xl shadow-xl border border-indigo-200/50 dark:border-indigo-700/50 hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white mb-4 shadow-lg">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-2">{{ __('common.crypto_security') }}</h3>
                        <p class="text-base text-gray-600 dark:text-gray-300 mb-4">
                            {{ __('common.crypto_security_desc') }}
                        </p>
                        <ul class="space-y-2">
                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('common.bank_grade_security') }}
                            </li>
                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('common.two_factor_authentication') }}
                            </li>
                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('common.encrypted_storage') }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CRYPTO RECOVERY PROGRAM SECTION -->
    <section class="py-8 sm:py-12 lg:py-16 relative overflow-hidden rounded-xl border border-blue-200/50 dark:border-blue-700/50 mt-8 mb-8">
        <!-- Gradient animé en arrière-plan -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-indigo-50 to-blue-100 dark:from-blue-900/20 dark:via-indigo-900/20 dark:to-blue-800/20 animate-gradient-xy"></div>
        
        <!-- Éléments décoratifs animés -->
        <div class="absolute top-10 left-10 w-16 h-16 border-2 border-blue-400/30 rounded-full animate-spin-slow backdrop-blur-sm"></div>
        <div class="absolute bottom-10 right-10 w-20 h-20 border-2 border-indigo-400/30 rounded-2xl animate-pulse-slow backdrop-blur-sm"></div>
        <div class="absolute top-1/2 right-1/4 w-12 h-12 bg-blue-400/10 rounded-full animate-float backdrop-blur-sm"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-8">
                <h2 class="text-base text-blue-600 dark:text-blue-400 font-semibold tracking-wide uppercase">{{ __('common.crypto_recovery_program') }}</h2>
                <p class="mt-2 text-2xl sm:text-3xl lg:text-4xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ __('common.crypto_recovery_program') }}
                </p>
                <p class="mt-4 max-w-2xl text-lg text-gray-600 dark:text-gray-300 lg:mx-auto">
                    {{ __('common.crypto_recovery_desc') }}
                </p>
            </div>

            <!-- Recovery Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 text-center border border-gray-200 dark:border-gray-700">
                    <div class="text-3xl font-bold text-brand-primary mb-2">98.5%</div>
                    <div class="text-gray-600 dark:text-gray-300 text-sm">{{ __('common.recovery_success_rate') }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 text-center border border-gray-200 dark:border-gray-700">
                    <div class="text-3xl font-bold text-brand-success mb-2">€15M+</div>
                    <div class="text-gray-600 dark:text-gray-300 text-sm">{{ __('common.funds_recovered') }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 text-center border border-gray-200 dark:border-gray-700">
                    <div class="text-3xl font-bold text-brand-accent mb-2">2,450+</div>
                    <div class="text-gray-600 dark:text-gray-300 text-sm">{{ __('common.successful_recoveries') }}</div>
                </div>
            </div>
            
            <!-- Recovery Wallets Table Animé -->
            <livewire:crypto-recovery-table />
        </div>
    </section>

  <!-- CRYPTO REFUND CTA SECTION - Completely separate from Livewire component -->
    <section class="py-8 sm:py-12 lg:py-16 bg-gray-50 dark:bg-gray-900 relative rounded-xl border border-gray-200/50 dark:border-gray-700/50 mt-8 mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-2xl shadow-xl border border-blue-200/50 dark:border-blue-700/50 p-6 max-w-md mx-auto">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('common.need_crypto_recovery_help') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">{{ __('common.expert_team_recovery_support') }}</p>
                    <a href="{{ route('crypto-refund', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-brand-accent to-brand-success hover:from-brand-accent-hover hover:to-brand-success-hover text-white font-medium rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        {{ __('common.request_refund') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- AVANTAGES SERVICES -->
    <section class="max-w-6xl mx-auto py-8 sm:py-12 lg:py-16 bg-white dark:bg-gray-800 rounded-xl border border-gray-200/50 dark:border-gray-700/50 mt-8 mb-8 px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">{{ __('common.service_advantages') }}</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col items-center transform hover:scale-105 transition-all duration-300 animate-slide-up hover:shadow-2xl border border-transparent hover:border-brand-primary/20">
                <svg class="w-10 h-10 text-brand-primary mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div class="font-bold mb-2 text-brand-primary text-center">{{ __('common.maximum_security') }}</div>
                <div class="text-gray-500 dark:text-gray-300 text-sm text-center">{{ __('common.advanced_data_transaction_protection') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col items-center transform hover:scale-105 transition-all duration-300 animate-slide-up hover:shadow-2xl border border-transparent hover:border-brand-primary/20" style="animation-delay: 0.1s;">
                <svg class="w-10 h-10 text-brand-primary mb-3 animate-bounce-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                <div class="font-bold mb-2 text-brand-primary text-center">{{ __('common.100_percent_digital') }}</div>
                <div class="text-gray-500 dark:text-gray-300 text-sm text-center">{{ __('common.manage_everything_mobile_computer_no_paperwork') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col items-center transform hover:scale-105 transition-all duration-300 animate-slide-up hover:shadow-2xl border border-transparent hover:border-brand-primary/20" style="animation-delay: 0.2s;">
                <svg class="w-10 h-10 text-brand-primary mb-3 animate-pulse-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
                <div class="font-bold mb-2 text-brand-primary text-center">{{ __('common.support_24_7') }}</div>
                <div class="text-gray-500 dark:text-gray-300 text-sm text-center">{{ __('common.team_listening_any_time') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col items-center transform hover:scale-105 transition-all duration-300 animate-slide-up hover:shadow-2xl border border-transparent hover:border-brand-primary/20" style="animation-delay: 0.3s;">
                <svg class="w-10 h-10 text-brand-primary mb-3 animate-float" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                <div class="font-bold mb-2 text-brand-primary text-center">{{ __('common.total_transparency') }}</div>
                <div class="text-gray-500 dark:text-gray-300 text-sm text-center">{{ __('common.no_hidden_fees_everything_clear_displayed') }}</div>
            </div>
        </div>
    </section>

  

    <!-- LOAN REQUEST CTA SECTION -->
    <section class="py-8 sm:py-12 lg:py-16 bg-gradient-to-br from-green-600 via-emerald-700 to-teal-800 text-white relative overflow-hidden rounded-xl border border-emerald-400/30 mt-8 mb-8">
        <!-- Particules flottantes animées -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-emerald-400/30 rounded-full animate-float"></div>
            <div class="absolute top-1/3 right-1/4 w-3 h-3 bg-white/20 rounded-full animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-1/4 left-1/3 w-1 h-1 bg-emerald-400/40 rounded-full animate-float" style="animation-delay: 4s;"></div>
            <div class="absolute top-1/2 right-1/3 w-2 h-2 bg-white/25 rounded-full animate-float" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-1/3 right-1/5 w-3 h-3 bg-emerald-400/15 rounded-full animate-float" style="animation-delay: 3s;"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-400/20 rounded-full mb-6">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold mb-4">{{ __('loan.request_title') }}</h2>
                <p class="text-lg sm:text-xl text-green-100 mb-8 max-w-3xl mx-auto">{{ __('loan.request_description') }}</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('loan-request', ['locale' => app()->getLocale()]) }}" 
                       class="inline-flex items-center px-8 py-4 bg-emerald-400 text-green-900 font-semibold rounded-lg hover:bg-emerald-300 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        {{ __('loan.request_loan') }}
                    </a>
                    <div class="flex items-center text-green-100">
                        <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <span class="text-sm">{{ __('loan.fast_approval') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ SERVICES (Alpine.js) -->
    <section class="py-16 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200/50 dark:border-gray-700/50 mt-8 mb-8">
        <div class="max-w-4xl mx-auto px-4">
            <h2 class="text-2xl font-bold text-brand-primary dark:text-blue-300 mb-4">{{ __('common.frequently_asked_questions_services') }}</h2>
            <div x-data="{open:null}" class="space-y-2">
                <div class="bg-white dark:bg-gray-800 rounded shadow-lg">
                    <button @click="open===1?open=null:open=1" class="w-full text-left px-6 py-4 font-bold text-brand-primary dark:text-blue-300 flex justify-between items-center">{{ __('common.how_to_open_account_bank', ['bank_name' => getAppName()]) }} <span x-text="open===1?'−':'+'"></span></button>
                    <div x-show="open===1" x-transition class="px-6 pb-4 text-gray-600 dark:text-gray-300">{{ __('common.online_few_minutes_100_digital_no_travel') }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded shadow-lg">
                    <button @click="open===2?open=null:open=2" class="w-full text-left px-6 py-4 font-bold text-brand-primary dark:text-blue-300 flex justify-between items-center">{{ __('common.what_types_credits_offer') }} <span x-text="open===2?'−':'+'"></span></button>
                    <div x-show="open===2" x-transition class="px-6 pb-4 text-gray-600 dark:text-gray-300">{{ __('common.personal_loan_auto_credit_solutions_individuals_professionals') }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded shadow-lg">
                    <button @click="open===3?open=null:open=3" class="w-full text-left px-6 py-4 font-bold text-brand-primary dark:text-blue-300 flex justify-between items-center">{{ __('common.how_to_contact_support') }} <span x-text="open===3?'−':'+'"></span></button>
                    <div x-show="open===3" x-transition class="px-6 pb-4 text-gray-600 dark:text-gray-300">{{ __('common.via_chat_phone_email_whatsapp_24_7') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative overflow-hidden rounded-2xl border border-white/20 mt-8 mb-8">
        <!-- Gradient animé avec effet glassmorphism -->
        <div class="absolute inset-0 bg-gradient-to-r from-brand-primary to-brand-accent animate-gradient-x"></div>
        <div class="absolute inset-0 backdrop-blur-sm bg-white/10 border border-white/20 rounded-2xl"></div>
        <div class="relative z-20 max-w-4xl mx-auto text-center py-12 sm:py-16 lg:py-20 px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white">
                <span class="block">{{ __('common.need_advice_personalized_service') }}</span>
            </h2>
            <p class="mt-4 text-base sm:text-lg leading-6 text-white/90 max-w-2xl mx-auto">
                {{ __('common.advisors_listening_accompany_projects') }}
            </p>
            <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="mt-6 sm:mt-8 w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-brand-primary bg-white hover:bg-gray-50 hover:text-brand-primary-hover transition duration-200">
                {{ __('common.make_appointment') }}
            </a>
        </div>
    </section>
</div>

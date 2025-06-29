<div class="home-container space-y-16 relative overflow-hidden">
    <!-- Particules flottantes animées -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-brand-accent/30 rounded-full animate-float"></div>
        <div class="absolute top-1/3 right-1/4 w-3 h-3 bg-brand-primary/20 rounded-full animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-1/4 left-1/3 w-1 h-1 bg-brand-success/40 rounded-full animate-float" style="animation-delay: 4s;"></div>
        <div class="absolute top-1/2 right-1/3 w-2 h-2 bg-brand-accent/25 rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-1/3 right-1/5 w-3 h-3 bg-brand-primary/15 rounded-full animate-float" style="animation-delay: 3s;"></div>
    </div>
    <!-- HERO SECTION -->
    <section class="relative text-white py-8 sm:py-12 lg:py-16 px-4 sm:px-6 lg:px-8 rounded-lg shadow-lg overflow-hidden">
        <!-- Gradient animé avec effet glassmorphism -->
        <div class="absolute inset-0 bg-gradient-to-r from-brand-primary to-brand-accent animate-gradient-x"></div>
        <div class="absolute inset-0 backdrop-blur-sm bg-white/5 border border-white/10 rounded-lg"></div>
        <div class="relative z-10 max-w-7xl mx-auto">
            <!-- Mobile Layout (Single Column) -->
            <div class="flex flex-col items-center text-center lg:hidden">
                <span class="inline-block bg-brand-accent text-white px-3 py-1 rounded-full mb-4 font-medium text-sm sm:text-base">{{ __('common.welcome_offer') }}</span>
                <h1 class="text-3xl sm:text-4xl font-extrabold mb-4 leading-tight">{{ getAppName() }}&nbsp;: <span class="text-blue-100">{{ __('common.banking_future') }}</span></h1>
                <p class="mb-6 text-base sm:text-lg text-blue-100">{{ __('common.revolutionary_banking_experience') }}</p>

                
                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <a href="{{ route('register', ['locale' => app()->getLocale()]) }}" class="px-6 py-3 bg-gradient-to-r from-brand-primary to-brand-accent hover:from-brand-primary-hover hover:to-brand-accent-hover text-white font-bold rounded-lg shadow-lg transition duration-300 transform hover:scale-105 text-center">{{ __('common.open_account') }}</a>
                    <a href="{{ route('services', ['locale' => app()->getLocale()]) }}" class="px-6 py-3 bg-gradient-to-r from-brand-accent to-brand-success hover:from-brand-accent-hover hover:to-brand-success-hover text-white font-bold rounded-lg shadow-lg transition duration-300 transform hover:scale-105 text-center">{{ __('common.learn_more') }}</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 text-sm sm:text-base">
                    <div class="text-center"><span class="font-bold block">{{ __('common.zero_euro') }}</span><span class="text-blue-200 text-xs sm:text-sm">{{ __('common.opening_fees') }}</span></div>
                    <div class="text-center"><span class="font-bold block">{{ __('common.two_minutes') }}</span><span class="text-blue-200 text-xs sm:text-sm">{{ __('common.quick_opening') }}</span></div>
                    <div class="text-center"><span class="font-bold block">{{ __('common.24_7') }}</span><span class="text-blue-200 text-xs sm:text-sm">{{ __('common.customer_support') }}</span></div>
                </div>
                <!-- Card Slider Component for Mobile -->
                <div class="w-full max-w-sm">
                    <x-card-slider />
                </div>
            </div>
            
            <!-- Desktop Layout (Two Columns) -->
            <div class="hidden lg:flex lg:items-center lg:gap-12">
                <!-- Left Column: Content -->
                <div class="flex-1 text-left pr-6">
                    <span class="inline-block bg-brand-accent text-white px-3 py-1 rounded-full mb-4 font-medium text-base">{{ __('common.welcome_offer') }}</span>
                    <h1 class="text-5xl font-extrabold mb-4 leading-tight">{{ getAppName() }}&nbsp;: <span class="text-blue-100">{{ __('common.banking_future') }}</span></h1>
                    <p class="mb-6 text-lg text-blue-100">{{ __('common.revolutionary_banking_experience') }}</p>
                    

                    
                    <!-- CTA Buttons Side by Side -->
                    <div class="flex gap-4 mb-8">
                        <a href="{{ route('register', ['locale' => app()->getLocale()]) }}" class="px-6 py-3 bg-gradient-to-r from-brand-primary to-brand-accent hover:from-brand-primary-hover hover:to-brand-accent-hover text-white font-bold rounded-lg shadow-lg transition duration-300 transform hover:scale-105">{{ __('common.open_account') }}</a>
                        <a href="{{ route('services', ['locale' => app()->getLocale()]) }}" class="px-6 py-3 bg-gradient-to-r from-brand-accent to-brand-success hover:from-brand-accent-hover hover:to-brand-success-hover text-white font-bold rounded-lg shadow-lg transition duration-300 transform hover:scale-105">{{ __('common.learn_more') }}</a>
                    </div>
                    
                    <!-- Stats Side by Side -->
                    <div class="grid grid-cols-3 gap-6 text-center">
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                            <div class="text-2xl font-bold text-white">50K+</div>
                            <div class="text-blue-200 text-sm">{{ __('common.satisfied_clients') }}</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                            <div class="text-2xl font-bold text-white">€2.5M+</div>
                            <div class="text-blue-200 text-sm">{{ __('common.transactions_processed') }}</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                            <div class="text-2xl font-bold text-white">99.9%</div>
                            <div class="text-blue-200 text-sm">{{ __('common.uptime_guarantee') }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column: Card -->
                <div class="flex-1 flex justify-center">
                    <div class="w-full max-w-md">
                        <x-card-slider />
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="py-8 sm:py-12 lg:py-16 bg-gray-50 dark:bg-gray-900 relative">
        <!-- Éléments décoratifs animés -->
        <div class="absolute top-10 right-10 w-20 h-20 border-2 border-brand-primary/20 rounded-full animate-spin-slow"></div>
        <div class="absolute bottom-10 left-10 w-16 h-16 border-2 border-brand-accent/20 rounded-lg animate-pulse-slow"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-indigo-600 dark:text-indigo-400 font-semibold tracking-wide uppercase">{{ __('common.features') }}</h2>
                <p class="mt-2 text-2xl sm:text-3xl lg:text-4xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ __('common.everything_you_need') }}
                </p>
            </div>

            <div class="mt-8 sm:mt-10">
                <div class="space-y-8 sm:space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-6 lg:gap-x-8 md:gap-y-8 lg:gap-y-10">
                    <!-- Feature 1 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('common.secure_banking') }}</h3>
                            <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                {{ __('common.secure_banking_desc') }}
                            </p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('common.fast_transactions') }}</h3>
                            <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                {{ __('common.fast_transactions_desc') }}
                            </p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('common.support_24_7') }}</h3>
                            <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                {{ __('common.support_24_7_desc') }}
                            </p>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('common.investment_solutions') }}</h3>
                            <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                {{ __('common.investment_solutions_desc') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative overflow-hidden rounded-2xl">
        <!-- Gradient animé avec effet glassmorphism -->
        <div class="absolute inset-0 bg-gradient-to-br from-brand-primary to-brand-accent animate-gradient-x"></div>
        <div class="absolute inset-0 backdrop-blur-sm bg-white/10 border border-white/20 rounded-2xl"></div>
        <div class="relative z-20 max-w-4xl mx-auto text-center py-12 sm:py-16 lg:py-20 px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white">
                <span class="block">{{ __('common.ready_to_get_started') }}</span>
                <span class="block">{{ __('common.join_bank_today', ['bank_name' => getAppName()]) }}</span>
            </h2>
            <p class="mt-4 text-base sm:text-lg leading-6 text-white/90 max-w-2xl mx-auto">
                {{ __('common.experience_future_banking') }}
            </p>
            <a href="{{ route('register', ['locale' => app()->getLocale()]) }}" class="mt-6 sm:mt-8 w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-brand-primary bg-white hover:bg-gray-50 hover:text-brand-primary-hover transition duration-200">
                {{ __('common.sign_up_free') }}
            </a>
        </div>
    </section>

    <!-- CHIFFRES CLÉS -->
    <section class="max-w-6xl mx-auto">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8 text-center bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 sm:p-8">
            <div class="p-4 transform hover:scale-105 transition-all duration-300">
                <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-brand-primary animate-pulse-slow">+100K</div>
                <div class="text-sm sm:text-base text-gray-500 dark:text-gray-300 mt-1">{{ __('common.satisfied_customers') }}</div>
            </div>
            <div class="p-4 transform hover:scale-105 transition-all duration-300 animate-slide-up" style="animation-delay: 0.1s;">
                <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-brand-primary animate-bounce-slow">{{ __('common.24_7') }}</div>
                <div class="text-sm sm:text-base text-gray-500 dark:text-gray-300 mt-1">{{ __('common.support') }}</div>
            </div>
            <div class="p-4 transform hover:scale-105 transition-all duration-300 animate-slide-up" style="animation-delay: 0.2s;">
                <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-brand-primary animate-float">{{ __('common.zero_euro') }}</div>
                <div class="text-sm sm:text-base text-gray-500 dark:text-gray-300 mt-1">{{ __('common.opening_fees') }}</div>
            </div>
            <div class="p-4 transform hover:scale-105 transition-all duration-300 animate-slide-up" style="animation-delay: 0.3s;">
                <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-brand-primary animate-pulse-slow">99,99%</div>
                <div class="text-sm sm:text-base text-gray-500 dark:text-gray-300 mt-1">{{ __('common.platform_availability') }}</div>
            </div>
        </div>
    </section>

    <!-- AVANTAGES -->
    <section class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col items-center transform hover:scale-105 transition-all duration-300 animate-slide-up hover:shadow-2xl border border-transparent hover:border-brand-primary/20">
                <svg class="w-10 h-10 text-brand-primary mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div class="font-bold mb-2 text-brand-primary text-center">{{ __('common.advanced_security') }}</div>
                <div class="text-gray-500 dark:text-gray-300 text-sm text-center">{{ __('common.advanced_security_desc') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col items-center transform hover:scale-105 transition-all duration-300 animate-slide-up hover:shadow-2xl border border-transparent hover:border-brand-primary/20" style="animation-delay: 0.1s;">
                <svg class="w-10 h-10 text-brand-primary mb-3 animate-bounce-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                <div class="font-bold mb-2 text-brand-primary text-center">{{ __('common.instant_transactions') }}</div>
                <div class="text-gray-500 dark:text-gray-300 text-sm text-center">{{ __('common.instant_transactions_desc') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col items-center transform hover:scale-105 transition-all duration-300 animate-slide-up hover:shadow-2xl border border-transparent hover:border-brand-primary/20" style="animation-delay: 0.2s;">
                <svg class="w-10 h-10 text-brand-primary mb-3 animate-pulse-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
                <div class="font-bold mb-2 text-brand-primary text-center">{{ __('common.human_support_24_7') }}</div>
                <div class="text-gray-500 dark:text-gray-300 text-sm text-center">{{ __('common.human_support_24_7_desc') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col items-center transform hover:scale-105 transition-all duration-300 animate-slide-up hover:shadow-2xl border border-transparent hover:border-brand-primary/20" style="animation-delay: 0.3s;">
                <svg class="w-10 h-10 text-brand-primary mb-3 animate-float" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                <div class="font-bold mb-2 text-brand-primary text-center">{{ __('common.investment_solutions') }}</div>
                <div class="text-gray-500 dark:text-gray-300 text-sm text-center">{{ __('common.investment_solutions_desc') }}</div>
            </div>
        </div>
    </section>

    <!-- WALLETS CRYPTO SECTION -->
    <section class="py-8 sm:py-12 lg:py-16 relative overflow-hidden">
        <!-- Gradient animé en arrière-plan -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-indigo-50 to-blue-100 dark:from-blue-900/20 dark:via-indigo-900/20 dark:to-blue-800/20 animate-gradient-xy"></div>
        
        <!-- Éléments décoratifs animés -->
        <div class="absolute top-10 left-10 w-16 h-16 border-2 border-blue-400/30 rounded-full animate-spin-slow backdrop-blur-sm"></div>
        <div class="absolute bottom-10 right-10 w-20 h-20 border-2 border-indigo-400/30 rounded-2xl animate-pulse-slow backdrop-blur-sm"></div>
        <div class="absolute top-1/2 right-1/4 w-12 h-12 bg-blue-400/10 rounded-full animate-float backdrop-blur-sm"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h2 class="text-base text-blue-600 dark:text-blue-400 font-semibold tracking-wide uppercase">{{ __('common.crypto_wallets') }}</h2>
                <p class="mt-2 text-2xl sm:text-3xl lg:text-4xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ __('common.manage_crypto_assets') }}
                </p>
                <p class="mt-4 max-w-2xl text-lg text-gray-600 dark:text-gray-300 lg:mx-auto">
                    {{ __('common.secure_crypto_wallet_management') }}
                </p>
            </div>

            <div class="mt-8 sm:mt-10">
                <div class="space-y-8 sm:space-y-10 md:space-y-0 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-x-6 lg:gap-x-8 md:gap-y-8 lg:gap-y-10">
                    <!-- Crypto Feature 1 -->
                    <div class="relative bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-2xl p-6 border border-blue-200/50 dark:border-blue-700/50 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg mb-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-2">{{ __('common.multi_crypto_support') }}</h3>
                            <p class="text-base text-gray-600 dark:text-gray-300 mb-4">
                                {{ __('common.multi_crypto_support_desc') }}
                            </p>
                            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.638 14.904c-1.602 6.43-8.113 10.34-14.542 8.736C2.67 22.05-1.244 15.525.362 9.105 1.962 2.67 8.475-1.243 14.9.358c6.43 1.605 10.342 8.115 8.738 14.546z" />
                                        <path d="M17.45 11.8c.2-1.338-.82-2.058-2.213-2.535l.452-1.815-1.105-.275-.44 1.767c-.29-.072-.588-.14-.885-.207l.443-1.776-1.104-.275-.452 1.815c-.24-.055-.475-.109-.704-.167l.001-.004-1.525-.381-.294 1.18s.82.188.803.2c.448.112.529.408.515.643l-.515 2.067c.031.008.071.019.115.035l-.117-.029-.723 2.9c-.055.136-.194.34-.507.263.011.016-.803-.2-.803-.2l-.549 1.265 1.44.359c.268.067.53.137.788.202l-.456 1.830 1.104.275.452-1.815c.299.081.589.156.873.226l-.451 1.807 1.105.275.456-1.827c1.88.356 3.293.212 3.89-1.494.48-1.37-.024-2.16-1.014-2.677.722-.166 1.266-.64 1.412-1.618z" />
                                    </svg>
                                    {{ __('common.bitcoin_support') }}
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.944 17.97L4.58 13.62 11.943 24l7.37-10.38-7.372 4.35h.003zM12.056 0L4.69 12.223l7.365 4.354 7.365-4.35L12.056 0z" />
                                    </svg>
                                    {{ __('common.ethereum_support') }}
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    {{ __('common.other_altcoins') }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Crypto Feature 2 -->
                    <div class="relative bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-2xl p-6 border border-blue-200/50 dark:border-blue-700/50 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-lg mb-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-2">{{ __('common.secure_storage') }}</h3>
                            <p class="text-base text-gray-600 dark:text-gray-300 mb-4">
                                {{ __('common.secure_storage_desc') }}
                            </p>
                            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    {{ __('common.cold_storage') }}
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    {{ __('common.multi_signature') }}
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                    {{ __('common.private_keys_control') }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Crypto Feature 3 -->
                    <div class="relative bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-2xl p-6 border border-blue-200/50 dark:border-blue-700/50 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-lg mb-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-2">{{ __('common.instant_crypto_transfers') }}</h3>
                            <p class="text-base text-gray-600 dark:text-gray-300 mb-4">
                                {{ __('common.instant_crypto_transfers_desc') }}
                            </p>
                            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    {{ __('common.lightning_fast') }}
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('common.low_fees') }}
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('common.global_transfers') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA pour les wallets crypto -->
            <div class="mt-8 sm:mt-10 text-center">
                <a href="{{ route('register', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-8 py-4 border border-blue-200/30 dark:border-blue-700/30 text-base font-medium rounded-2xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 shadow-xl hover:shadow-2xl backdrop-blur-sm bg-white/10 dark:bg-gray-800/10 hover:scale-105">
                    {{ __('common.start_crypto_journey') }}
                    <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- CRYPTO RECOVERY PROGRAM SECTION -->
    <section class="py-8 sm:py-12 lg:py-16 relative overflow-hidden">
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
    <section class="py-8 sm:py-12 lg:py-16 bg-gray-50 dark:bg-gray-900 relative">
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

    <!-- LOAN REQUEST CTA SECTION -->
    <section class="py-8 sm:py-12 lg:py-16 bg-gradient-to-br from-green-600 via-emerald-700 to-teal-800 text-white relative overflow-hidden">
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

    <!-- TABLEAU TARIFS -->
    <section class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-brand-primary mb-4">{{ __('common.our_rates') }}</h2>
        <div class="overflow-x-auto rounded-xl shadow-lg">
            <table class="min-w-full bg-white dark:bg-gray-800 rounded-xl overflow-hidden">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                        <th class="py-3 px-4 text-left text-white">{{ __('common.cards_and_services') }}</th>
                        <th class="py-3 px-4 text-left text-white">{{ __('common.price_euro') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                        <td class="py-3 px-4 text-brand-primary dark:text-blue-300">{{ __('common.online_account_management') }}</td>
                        <td class="py-3 px-4 font-bold text-brand-success">{{ __('common.free') }}</td>
                    </tr>
                    <tr class="hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                        <td class="py-3 px-4 text-brand-primary dark:text-blue-300">{{ __('common.email_alerts') }}</td>
                        <td class="py-3 px-4 font-bold text-brand-success">{{ __('common.free') }}</td>
                    </tr>
                    <tr class="hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                        <td class="py-3 px-4 text-brand-primary dark:text-blue-300">{{ __('common.visa_card') }}</td>
                        <td class="py-3 px-4 font-bold text-brand-success">{{ __('common.free') }}</td>
                    </tr>
                    <tr class="hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                        <td class="py-3 px-4 text-brand-primary dark:text-blue-300">{{ __('common.mastercard') }}</td>
                        <td class="py-3 px-4 font-bold text-brand-success">{{ __('common.free') }}</td>
                    </tr>
                    <tr class="hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                        <td class="py-3 px-4 text-brand-primary dark:text-blue-300">{{ __('common.american_express') }}</td>
                        <td class="py-3 px-4 font-bold text-brand-success">{{ __('common.free') }}</td>
                    </tr>
                    <tr class="hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                        <td class="py-3 px-4 text-brand-primary dark:text-blue-300">{{ __('common.atm_withdrawal_eurozone') }}</td>
                        <td class="py-3 px-4 font-bold text-brand-success">{{ __('common.free') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-right mt-2">
            <a href="{{ route('services', ['locale' => app()->getLocale()]) }}" class="text-brand-primary hover:underline">{{ __('common.see_all_rates') }}</a>
        </div>
    </section>


    <!-- FAQ (Accordéon Alpine.js) -->
    <section class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-brand-primary mb-4">{{ __('common.frequently_asked_questions') }}</h2>
        <div x-data="{open:null}" class="space-y-2">
            <div class="bg-white dark:bg-gray-800 rounded shadow-lg">
                <button @click="open===1?open=null:open=1" class="w-full text-left px-6 py-4 font-bold text-brand-primary dark:text-blue-300 flex justify-between items-center">{{ __('common.how_to_open_account') }} <span x-text="open===1?'−':'+'"></span></button>
                <div x-show="open===1" x-transition class="px-6 pb-4 text-gray-600 dark:text-gray-300">{{ __('common.how_to_open_account_answer') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded shadow-lg">
                <button @click="open===2?open=null:open=2" class="w-full text-left px-6 py-4 font-bold text-brand-primary dark:text-blue-300 flex justify-between items-center">{{ __('common.is_card_really_free') }} <span x-text="open===2?'−':'+'"></span></button>
                <div x-show="open===2" x-transition class="px-6 pb-4 text-gray-600 dark:text-gray-300">{{ __('common.is_card_really_free_answer') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded shadow-lg">
                <button @click="open===3?open=null:open=3" class="w-full text-left px-6 py-4 font-bold text-brand-primary dark:text-blue-300 flex justify-between items-center">{{ __('common.how_to_contact_support') }} <span x-text="open===3?'−':'+'"></span></button>
                <div x-show="open===3" x-transition class="px-6 pb-4 text-gray-600 dark:text-gray-300">{{ __('common.how_to_contact_support_answer') }}</div>
            </div>
        </div>
    </section>

    <!-- NEWSLETTER & APPEL À L'ACTION -->
    <section class="relative max-w-7xl mx-auto rounded-2xl shadow-lg overflow-hidden min-h-[300px]">
        <!-- Gradient animé avec effet glassmorphism -->
        <div class="absolute inset-0 bg-gradient-to-r from-brand-primary to-brand-accent animate-gradient-x"></div>
        <div class="absolute inset-0 backdrop-blur-sm bg-white/10 border border-white/20 rounded-2xl"></div>
        <div class="relative z-20 p-12 text-white text-center flex flex-col justify-center min-h-[300px]">
            <h2 class="text-3xl font-bold mb-4">{{ __('common.stay_informed_news', ['bank_name' => getAppName()]) }}</h2>
            <p class="mb-6 text-white/90 text-lg">{{ __('common.receive_exclusive_offers') }}</p>
            <livewire:newsletter-subscription />
        </div>
    </section>
</div>
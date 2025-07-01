<div class="contact-container space-y-8">
    <!-- Particules flottantes animées -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-brand-accent/30 rounded-full animate-float"></div>
        <div class="absolute top-1/3 right-1/4 w-3 h-3 bg-brand-primary/20 rounded-full animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-1/4 left-1/3 w-1 h-1 bg-brand-success/40 rounded-full animate-float" style="animation-delay: 4s;"></div>
        <div class="absolute top-1/2 right-1/3 w-2 h-2 bg-brand-accent/25 rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-1/3 right-1/5 w-3 h-3 bg-brand-primary/15 rounded-full animate-float" style="animation-delay: 3s;"></div>
    </div>
    
    <!-- HERO SECTION CONTACT -->
    <section class="relative text-white py-8 sm:py-12 lg:py-16 px-4 sm:px-6 lg:px-8 rounded-xl shadow-2xl overflow-hidden border border-white/20">
        <!-- Gradient animé avec effet glassmorphism -->
        <div class="absolute inset-0 bg-gradient-to-br from-brand-primary via-brand-accent to-brand-primary animate-gradient-xy"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-pulse"></div>
        <div class="absolute inset-0 backdrop-blur-sm bg-white/5 border border-white/10 rounded-xl"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto">
            <!-- Mobile Layout (Single Column) -->
            <div class="flex flex-col items-center text-center lg:hidden">
                <span class="inline-block bg-brand-accent text-white px-3 py-1 rounded-full mb-4 font-medium text-sm sm:text-base">{{ __('common.contact_us') }}</span>
                <h1 class="text-3xl sm:text-4xl font-extrabold mb-4 leading-tight">{{ __('common.contact_us') }}&nbsp;: <span class="text-blue-100">{{ config('app.name', 'Bred Fin') }}</span></h1>
                <p class="mb-6 text-base sm:text-lg text-blue-100">{{ __('common.get_in_touch_desc') }}</p>
                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <a href="#contact-info" class="px-6 py-3 bg-brand-accent hover:bg-brand-success text-white font-bold rounded-lg shadow transition duration-200 text-center">{{ __('common.contact_info') }}</a>
                    <a href="#formulaire-contact" class="px-6 py-3 border border-white font-bold rounded-lg hover:bg-white hover:text-blue-700 transition duration-200 text-center">{{ __('common.write_message') }}</a>
                </div>
            </div>
            
            <!-- Desktop Layout (Two Columns) -->
            <div class="hidden lg:flex lg:items-center lg:gap-12">
                <!-- Left Column: Content -->
                <div class="flex-1 text-left pr-6">
                    <span class="inline-block bg-brand-accent text-white px-3 py-1 rounded-full mb-4 font-medium text-base">{{ __('common.contact_us') }}</span>
                    <h1 class="text-5xl font-extrabold mb-4 leading-tight">{{ __('common.contact_us') }}&nbsp;: <span class="text-blue-100">{{ config('app.name', 'Bred Fin') }}</span></h1>
                    <p class="mb-6 text-lg text-blue-100">{{ __('common.get_in_touch_desc') }}</p>
                    
                    <!-- CTA Buttons Side by Side -->
                    <div class="flex gap-4 mb-8">
                        <a href="#contact-info" class="px-6 py-3 bg-brand-accent hover:bg-brand-success text-white font-bold rounded-lg shadow transition duration-200">{{ __('common.contact_info') }}</a>
                        <a href="#formulaire-contact" class="px-6 py-3 border border-white font-bold rounded-lg hover:bg-white hover:text-blue-700 transition duration-200">{{ __('common.write_message') }}</a>
                    </div>
                </div>
                
                <!-- Right Column: Icon -->
                <div class="flex-1 flex justify-center">
                    <div class="w-full max-w-md flex justify-center">
                        <div class="w-32 h-32 bg-gradient-to-r from-brand-accent to-brand-primary rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- INFORMATIONS DE CONTACT -->
    <section class="py-8 sm:py-12 lg:py-16 bg-gray-50 dark:bg-gray-900 relative rounded-xl border border-gray-200/50 dark:border-gray-700/50 mt-8 mb-8" id="contact-info">
        <!-- Éléments décoratifs animés -->
        <div class="absolute top-10 right-10 w-20 h-20 border-2 border-brand-primary/20 rounded-full animate-spin-slow"></div>
        <div class="absolute bottom-10 left-10 w-16 h-16 border-2 border-brand-accent/20 rounded-lg animate-pulse-slow"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-brand-primary dark:text-brand-accent font-semibold tracking-wide uppercase">{{ __('common.contact_info') }}</h2>
                <p class="mt-2 text-2xl sm:text-3xl lg:text-4xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ __('common.contact_info_desc') }}
                </p>
            </div>

            <div class="mt-8 sm:mt-10">
                <div class="space-y-8 sm:space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-6 lg:gap-x-8">
                    <!-- Email -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-brand-primary text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('common.email') }}</h3>
                            <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                {{ __('common.send_email_anytime') }}
                            </p>
                            <a href="mailto:{{ \App\Models\Config::first()?->bank_email ?? 'contact@Bred Fin.com' }}" class="text-brand-primary hover:text-brand-accent font-medium break-all">{{ \App\Models\Config::first()?->bank_email ?? 'contact@Bred Fin.com' }}</a>
                        </div>
                    </div>
                    
                    <!-- Adresse -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-brand-primary text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('common.address') }}</h3>
                            <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                {{ __('common.visit_us') }}
                            </p>
                            <p class="text-brand-primary hover:text-brand-accent font-medium">{{ \App\Models\Config::first()?->bank_address ?? '123 Banking Street, Financial District, City 12345' }}</p>
                        </div>
                    </div>
                    
                    <!-- Horaires -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-brand-primary text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('common.opening_hours') }}</h3>
                            <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                {{ __('common.our_schedule') }}
                            </p>
                            <div class="text-brand-primary font-medium">
                                <p>{{ __('common.monday_friday') }}: 9:00 - 17:00</p>
                                <p>{{ __('common.saturday') }}: 9:00 - 13:00</p>
                                <p>{{ __('common.sunday') }}: {{ __('common.closed') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative overflow-hidden rounded-xl mx-4 sm:mx-6 lg:mx-8 mt-8 mb-8 border border-white/20">
        <!-- Gradient animé avec effet glassmorphism -->
        <div class="absolute inset-0 bg-gradient-to-br from-brand-primary to-brand-accent animate-gradient-x"></div>
        <div class="absolute inset-0 backdrop-blur-sm bg-white/10 border border-white/20 rounded-2xl"></div>
        <div class="relative z-20 max-w-4xl mx-auto text-center py-12 sm:py-16 lg:py-20 px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white">
                <span class="block">{{ __('common.ready_to_contact') }}</span>
                <span class="block">{{ __('common.get_in_touch_today') }}</span>
            </h2>
            <p class="mt-4 text-base sm:text-lg leading-6 text-white/90 max-w-2xl mx-auto">
                {{ __('common.contact_cta_desc') }}
            </p>
            <a href="#formulaire-contact" class="mt-6 sm:mt-8 w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-brand-primary bg-white hover:bg-gray-50 hover:text-brand-primary-hover transition duration-200">
                {{ __('common.send_message') }}
            </a>
        </div>
    </section>

    <!-- FORMULAIRE DE CONTACT -->
    <section class="py-8 sm:py-12 lg:py-16 bg-gray-50 dark:bg-gray-900 relative rounded-xl border border-gray-200/50 dark:border-gray-700/50 mt-8 mb-8" id="formulaire-contact">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-brand-primary dark:text-brand-accent font-semibold tracking-wide uppercase">{{ __('common.contact_form') }}</h2>
                <p class="mt-2 text-2xl sm:text-3xl lg:text-4xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ __('common.contact_form_desc') }}
                </p>
            </div>

            <div class="mt-8 sm:mt-10">
            
            <div class="max-w-4xl mx-auto">
                <div class="relative bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300">
                    <livewire:contact-form />
                </div>
            </div>
        </div>
    </section>

  
</div>

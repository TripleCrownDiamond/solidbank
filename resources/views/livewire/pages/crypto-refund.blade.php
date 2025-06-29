<div>
    <!-- Particules flottantes -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-10 left-10 w-4 h-4 bg-brand-primary/20 rounded-full animate-float"></div>
        <div class="absolute top-20 right-20 w-6 h-6 bg-brand-accent/30 rounded-full animate-float-delayed"></div>
        <div class="absolute bottom-20 left-20 w-3 h-3 bg-brand-primary/25 rounded-full animate-float"></div>
        <div class="absolute bottom-10 right-10 w-5 h-5 bg-brand-accent/20 rounded-full animate-float-delayed"></div>
        <div class="absolute top-1/2 left-1/4 w-2 h-2 bg-brand-primary/30 rounded-full animate-float"></div>
        <div class="absolute top-1/3 right-1/3 w-4 h-4 bg-brand-accent/25 rounded-full animate-float-delayed"></div>
    </div>

    <!-- HERO SECTION -->
    <section class="relative text-white py-8 sm:py-12 lg:py-16 px-4 sm:px-6 lg:px-8 rounded-lg shadow-lg overflow-hidden">
        <!-- Gradient animé avec effet glassmorphism -->
        <div class="absolute inset-0 bg-gradient-to-r from-brand-primary to-brand-accent animate-gradient-x"></div>
        <div class="absolute inset-0 backdrop-blur-sm bg-white/5 border border-white/10 rounded-lg"></div>
        <div class="relative z-10 max-w-7xl mx-auto">
            <div class="text-center">
                <span class="inline-block bg-brand-accent text-white px-3 py-1 rounded-full mb-4 font-medium text-sm sm:text-base">{{ __('crypto.refund_title') }}</span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold mb-4 leading-tight">{{ __('crypto.refund_title') }}</h1>
                <p class="mb-6 text-base sm:text-lg text-blue-100 max-w-3xl mx-auto">{{ __('crypto.refund_description') }}</p>
            </div>
        </div>
    </section>
    <!-- FORMULAIRE DE DEMANDE -->
    <section class="py-8 sm:py-12 lg:py-16 bg-white dark:bg-gray-800 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-base text-brand-primary dark:text-brand-accent font-semibold tracking-wide uppercase">{{ __('crypto.form_title') }}</h2>
                <p class="mt-2 text-2xl sm:text-3xl lg:text-4xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ __('crypto.refund_title') }}
                </p>
            </div>

            <div class="max-w-4xl mx-auto">
                @if(session()->has('success'))
                    <div class="bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session()->has('error'))
                    <div class="bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if(!$success)
                <div class="relative bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300">
                    <form wire:submit="submit">
                        <!-- Informations personnelles -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('crypto.personal_information') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('crypto.first_name') }} *</label>
                                    <input type="text" wire:model="full_name" id="full_name" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                           placeholder="{{ __('crypto.full_name_placeholder') }}">
                                    @error('full_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('crypto.email') }} *</label>
                                    <input type="email" wire:model="email" id="email" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                           placeholder="{{ __('crypto.email_placeholder') }}">
                                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('crypto.phone') }} *</label>
                                    <input type="tel" wire:model="phone" id="phone" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                           placeholder="{{ __('crypto.phone_placeholder') }}">
                                    @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('crypto.country') }} *</label>
                                    <select wire:model="country" id="country" 
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        <option value="">{{ __('crypto.select_country') }}</option>
                                        @foreach($countries as $countryOption)
                                            <option value="{{ $countryOption->name }}">{{ $countryOption->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('crypto.address') }} *</label>
                                    <input type="text" wire:model="address" id="address" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                           placeholder="{{ __('crypto.address_placeholder') }}">
                                    @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('crypto.postal_code') }} *</label>
                                    <input type="text" wire:model="postal_code" id="postal_code" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                           placeholder="{{ __('crypto.postal_code_placeholder') }}">
                                    @error('postal_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('crypto.city') }} *</label>
                                    <input type="text" wire:model="city" id="city" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                           placeholder="{{ __('crypto.city_placeholder') }}">
                                    @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informations sur la crypto perdue -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('crypto.crypto_info') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="cryptocurrency_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('crypto.cryptocurrency') }} *</label>
                                    <select wire:model="cryptocurrency_id" id="cryptocurrency_id" 
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        <option value="">{{ __('crypto.select_cryptocurrency') }}</option>
                                        @foreach($cryptocurrencies as $crypto)
                                            <option value="{{ $crypto->id }}">{{ $crypto->name }} ({{ $crypto->symbol }})</option>
                                        @endforeach
                                    </select>
                                    @error('cryptocurrency_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('crypto.amount') }} *</label>
                                    <input type="number" wire:model="amount" id="amount" step="0.00000001"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                           placeholder="{{ __('crypto.amount_placeholder') }}">
                                    @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informations supplémentaires -->
                        <div class="mb-8">
                            <label for="additional_info" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('crypto.additional_info') }}</label>
                            <textarea wire:model="additional_info" id="additional_info" rows="4" 
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                      placeholder="Toute information supplémentaire que vous souhaitez partager..."></textarea>
                            @error('additional_info') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="text-center">
                            <button type="submit" 
                                    class="w-full md:w-auto px-8 py-4 bg-brand-primary hover:bg-brand-primary-hover text-white font-bold rounded-lg shadow-lg transition duration-200 transform hover:scale-105">
                                {{ __('crypto.submit') }}
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>

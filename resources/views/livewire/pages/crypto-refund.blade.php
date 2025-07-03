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
    <section class="relative text-white py-8 sm:py-12 lg:py-16 px-4 sm:px-6 lg:px-8 rounded-xl shadow-2xl overflow-hidden border border-white/20">
        <!-- Gradient animé avec effet glassmorphism -->
        <div class="absolute inset-0 bg-gradient-to-br from-brand-primary via-brand-accent to-brand-primary animate-gradient-xy"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-pulse"></div>
        <div class="absolute inset-0 backdrop-blur-sm bg-white/5 border border-white/10 rounded-xl"></div>
        <div class="relative z-10 max-w-7xl mx-auto">
            <div class="text-center">
                <span class="inline-block bg-brand-accent text-white px-3 py-1 rounded-full mb-4 font-medium text-sm sm:text-base">{{ __('crypto.refund_title') }}</span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold mb-4 leading-tight">{{ __('crypto.refund_title') }}</h1>
                <p class="mb-6 text-base sm:text-lg text-blue-100 max-w-3xl mx-auto">{{ __('crypto.refund_description') }}</p>
            </div>
        </div>
    </section>

    <!-- FORMULAIRE DE DEMANDE -->
    <section class="py-8 sm:py-12 lg:py-16 bg-white dark:bg-gray-800 relative rounded-xl border border-gray-200/50 dark:border-gray-700/50 mt-8 mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-base text-brand-primary dark:text-brand-accent font-semibold tracking-wide uppercase">{{ __('crypto.form_title') }}</h2>
                <p class="mt-2 text-2xl sm:text-3xl lg:text-4xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ __('crypto.refund_title') }}
                </p>
            </div>

            <div class="max-w-4xl mx-auto">



                @if(session()->has('error'))
                    <div x-data="{ show: true }"
                         x-show="show"
                         x-transition
                         x-init="setTimeout(() => show = false, 20000)"
                         class="bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if(!session()->has('success'))
                <div class="relative bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300">
                    <form wire:submit.prevent="submit" class="space-y-8">
                        <!-- Informations personnelles -->
                        <fieldset class="space-y-6 bg-gray-50 dark:bg-gray-800/30 p-6 rounded-lg">
                            <legend class="text-lg font-semibold text-gray-900 dark:text-white px-2">@lang('crypto.personal_information')</legend>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('crypto.first_name') *</label>
                                    <input type="text" wire:model="first_name" id="first_name"
                                           class="w-full px-4 py-3 border @error('first_name') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                           placeholder="{{ __('crypto.first_name_placeholder') }}">
                                    @error('first_name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('crypto.last_name') *</label>
                                    <input type="text" wire:model="last_name" id="last_name"
                                           class="w-full px-4 py-3 border @error('last_name') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                           placeholder="{{ __('crypto.last_name_placeholder') }}">
                                    @error('last_name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('crypto.email') *</label>
                                    <input type="email" wire:model="email" id="email"
                                           class="w-full px-4 py-3 border @error('email') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                           placeholder="@lang('crypto.email_placeholder')">
                                    @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('crypto.phone') *</label>
                                    <input type="tel" wire:model="phone" id="phone"
                                           class="w-full px-4 py-3 border @error('phone') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                           placeholder="@lang('crypto.phone_placeholder')">
                                    @error('phone') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="country_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('crypto.country') *</label>
                                    <select wire:model="country_id" id="country_id"
                                            class="w-full px-4 py-3 border @error('country_id') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        <option value="">@lang('crypto.select_country')</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->english_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('crypto.address') *</label>
                                    <input type="text" wire:model="address" id="address"
                                           class="w-full px-4 py-3 border @error('address') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                           placeholder="@lang('crypto.address_placeholder')">
                                    @error('address') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('crypto.city') *</label>
                                    <input type="text" wire:model="city" id="city"
                                           class="w-full px-4 py-3 border @error('city') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                           placeholder="{{ __('crypto.city_placeholder') }}">
                                    @error('city') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('crypto.postal_code') *</label>
                                    <input type="text" wire:model="postal_code" id="postal_code"
                                           class="w-full px-4 py-3 border @error('postal_code') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                           placeholder="@lang('crypto.postal_code_placeholder')">
                                    @error('postal_code') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <!-- Informations sur la crypto perdue -->
                        <fieldset class="space-y-6 bg-gray-50 dark:bg-gray-800/30 p-6 rounded-lg">
                            <legend class="text-lg font-semibold text-gray-900 dark:text-white px-2">@lang('crypto.crypto_info')</legend>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="cryptocurrency_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('crypto.cryptocurrency') *</label>
                                    <select wire:model="cryptocurrency_id" id="cryptocurrency_id"
                                            class="w-full px-4 py-3 border @error('cryptocurrency_id') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        <option value="">@lang('crypto.select_cryptocurrency')</option>
                                        @foreach($cryptocurrencies as $crypto)
                                            <option value="{{ $crypto->id }}">{{ $crypto->name }} ({{ $crypto->symbol }})</option>
                                        @endforeach
                                    </select>
                                    @error('cryptocurrency_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('crypto.amount') *</label>
                                    <input type="number" wire:model="amount" id="amount" step="0.00000001"
                                           class="w-full px-4 py-3 border @error('amount') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                           placeholder="@lang('crypto.amount_placeholder')">
                                    @error('amount') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <!-- Informations supplémentaires -->
                        <fieldset class="space-y-6 bg-gray-50 dark:bg-gray-800/30 p-6 rounded-lg">
                            <legend class="text-lg font-semibold text-gray-900 dark:text-white px-2">@lang('crypto.additional_info')</legend>
                            <div class="md:col-span-2">
                                <textarea wire:model="additional_info" id="additional_info" rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                          placeholder="@lang('crypto.additional_info_placeholder')"></textarea>
                                @error('additional_info') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </fieldset>

                        <!-- Bouton de soumission -->
                       <div class="text-center">
                            <button type="submit"
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-brand-primary hover:bg-brand-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove>@lang('crypto.submit')</span>
                                <span wire:loading wire:target="submit" class="flex items-center hidden">
                                    @lang('crypto.submitting')
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/30">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('crypto.request_submitted') }}</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            {{ session('success') }}
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary">
                                @lang('common.back_to_home')
                            </a>
                        </div>
                    </div>
                @endif
                
                <!-- Script pour gérer le rechargement après 20 secondes -->
                <script>
                    document.addEventListener('livewire:initialized', () => {
                        Livewire.on('form-submitted', () => {
                            setTimeout(() => {
                                Livewire.dispatch('reset-form');
                            }, 20000);
                        });
                    });
                </script>
            </div>
        </div>
    </section>
</div>
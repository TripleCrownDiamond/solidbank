<div>
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
                <span class="inline-block bg-brand-accent text-white px-3 py-1 rounded-full mb-4 font-medium text-sm sm:text-base">@lang('loan.request_loan')</span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold mb-4 leading-tight">@lang('loan.request_title')</h1>
                <p class="mb-6 text-base sm:text-lg text-blue-100 max-w-3xl mx-auto">@lang('loan.calculator_description')</p>
            </div>
        </div>
    </section>

    <!-- SIMULATEUR DE PRÊT -->
    <section class="py-8 sm:py-12 lg:py-16 bg-gray-50 dark:bg-gray-900 relative rounded-xl border border-gray-200/50 dark:border-gray-700/50 mt-8 mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-base text-brand-primary dark:text-brand-accent font-semibold tracking-wide uppercase">@lang('loan.loan_simulator')</h2>
                <p class="mt-2 text-2xl sm:text-3xl lg:text-4xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white">
                    @lang('loan.calculator_title')
                </p>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="relative bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Montant -->
                        <div>
                            <label for="simulated_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                @lang('loan.loan_amount')
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium">{{ $currency_symbol }}</span>
                                <input type="number" wire:model.live="simulated_amount" id="simulated_amount" 
                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                       placeholder="10000" min="1">
                            </div>
                        </div>
                        
                        <!-- Durée -->
                        <div>
                            <label for="simulated_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                @lang('loan.loan_duration')
                            </label>
                            <input type="number" wire:model.live="simulated_duration" id="simulated_duration" 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                   placeholder="12" min="6" max="360">
                        </div>
                        
                        <!-- Devise -->
                        <div>
                            <label for="simulated_currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                @lang('loan.currency')
                            </label>
                            <select wire:model.live="simulated_currency" id="simulated_currency" 
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="EUR">EUR</option>
                                <option value="GBP">GBP</option>
                                <option value="CHF">CHF</option>
                                <option value="SEK">SEK</option>
                                <option value="NOK">NOK</option>
                                <option value="DKK">DKK</option>
                                <option value="PLN">PLN</option>
                                <option value="CZK">CZK</option>
                                <option value="HUF">HUF</option>
                                <option value="RON">RON</option>
                                <option value="BGN">BGN</option>
                                <option value="HRK">HRK</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Résultats -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-brand-primary/10 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-brand-primary">{{ number_format($monthly_payment, 2) }} {{ $currency_symbol }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">@lang('loan.monthly_payment')</div>
                        </div>
                        <div class="bg-brand-accent/10 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-brand-accent">{{ number_format($total_payment, 2) }} {{ $currency_symbol }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">@lang('loan.total_amount')</div>
                        </div>
                        <div class="bg-brand-success/10 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-brand-success">{{ $loan_rate }}%</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">@lang('loan.interest_rate')</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FORMULAIRE DE DEMANDE -->
    <section class="py-8 sm:py-12 lg:py-16 bg-white dark:bg-gray-800 relative rounded-xl border border-gray-200/50 dark:border-gray-700/50 mt-8 mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-base text-brand-primary dark:text-brand-accent font-semibold tracking-wide uppercase">@lang('loan.form_title')</h2>
                <p class="mt-2 text-2xl sm:text-3xl lg:text-4xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white">
                    @lang('loan.request_loan')
                </p>
                <p class="mt-3 max-w-2xl mx-auto text-base text-gray-500 dark:text-gray-400">
                    @lang('loan.form_description')
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
                
                <div class="relative bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300">
                    @if(!session()->has('success'))
                    <form wire:submit.prevent="submit" class="space-y-8">
                        <!-- Informations personnelles -->
                        <fieldset class="space-y-6 bg-gray-50 dark:bg-gray-800/30 p-6 rounded-lg">
                            <legend class="text-lg font-semibold text-gray-900 dark:text-white px-2">@lang('loan.personal_information')</legend>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.first_name') *</label>
                                    <input type="text" wire:model="first_name" id="first_name" 
                                           class="w-full px-4 py-3 border @error('first_name') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                           placeholder="@lang('loan.first_name_placeholder')" required>
                                    @error('first_name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.last_name') *</label>
                                    <input type="text" wire:model="last_name" id="last_name" 
                                        class="w-full px-4 py-3 border @error('last_name') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                        placeholder="@lang('loan.last_name_placeholder')">
                                    @error('last_name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.email') *</label>
                                    <input type="email" wire:model="email" id="email" 
                                        class="w-full px-4 py-3 border @error('email') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                        placeholder="@lang('loan.email_placeholder')">
                                    @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.phone') *</label>
                                    <input type="tel" wire:model="phone" id="phone" 
                                        class="w-full px-4 py-3 border @error('phone') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                        placeholder="@lang('loan.phone_placeholder')">
                                    @error('phone') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.birth_date') *</label>
                                    <input type="date" wire:model="birth_date" id="birth_date" 
                                        class="w-full px-4 py-3 border @error('birth_date') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                                    @error('birth_date') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="marital_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.marital_status') *</label>
                                    <select wire:model="marital_status" id="marital_status" 
                                            class="w-full px-4 py-3 border @error('marital_status') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        <option value="">@lang('loan.select')</option>
                                        @foreach(trans('loan.marital_status_options') as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('marital_status') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="country_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.country') *</label>
                                    <select wire:model="country_id" id="country_id" 
                                            class="w-full px-4 py-3 border @error('country_id') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        <option value="">@lang('loan.select')</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->english_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.address') *</label>
                                    <input type="text" wire:model="address" id="address" 
                                        class="w-full px-4 py-3 border @error('address') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                        placeholder="@lang('loan.address_placeholder')">
                                    @error('address') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.city') *</label>
                                    <input type="text" wire:model="city" id="city" 
                                        class="w-full px-4 py-3 border @error('city') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                        placeholder="@lang('loan.city_placeholder')">
                                    @error('city') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.postal_code') *</label>
                                    <input type="text" wire:model="postal_code" id="postal_code" 
                                        class="w-full px-4 py-3 border @error('postal_code') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                        placeholder="@lang('loan.postal_code_placeholder')">
                                    @error('postal_code') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.currency') *</label>
                                    <select wire:model="currency" id="currency" 
                                            class="w-full px-4 py-3 border @error('currency') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        <option value="">@lang('loan.select_currency')</option>
                                        <option value="EUR">EUR</option>
                                        <option value="GBP">GBP</option>
                                        <option value="CHF">CHF</option>
                                        <option value="SEK">SEK</option>
                                        <option value="NOK">NOK</option>
                                        <option value="DKK">DKK</option>
                                        <option value="PLN">PLN</option>
                                        <option value="CZK">CZK</option>
                                        <option value="HUF">HUF</option>
                                        <option value="RON">RON</option>
                                        <option value="BGN">BGN</option>
                                        <option value="HRK">HRK</option>
                                    </select>
                                    @error('currency') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="loan_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.loan_amount') *</label>
                                    <input type="number" wire:model="loan_amount" id="loan_amount" 
                                        class="w-full px-4 py-3 border @error('loan_amount') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                        placeholder="@lang('loan.loan_amount_placeholder')" min="1">
                                    @error('loan_amount') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="loan_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.loan_duration') *</label>
                                    <input type="number" wire:model="loan_duration" id="loan_duration" 
                                        class="w-full px-4 py-3 border @error('loan_duration') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                        placeholder="@lang('loan.loan_duration_placeholder')" min="6" max="360">
                                    @error('loan_duration') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                            </div>
                        </fieldset>

                        <!-- Détails du prêt -->
                        <fieldset class="space-y-6 bg-gray-50 dark:bg-gray-800/30 p-6 rounded-lg">
                            <legend class="text-lg font-semibold text-gray-900 dark:text-white px-2">@lang('loan.loan_details')</legend>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="loan_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.loan_amount') *</label>
                                    <input type="number" wire:model="loan_amount" id="loan_amount" 
                                        class="w-full px-4 py-3 border @error('loan_amount') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                        placeholder="@lang('loan.loan_amount_placeholder')" min="1" required>
                                    @error('loan_amount') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="loan_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.loan_duration') * (mois)</label>
                                    <input type="number" wire:model="loan_duration" id="loan_duration" 
                                        class="w-full px-4 py-3 border @error('loan_duration') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                        placeholder="@lang('loan.loan_duration_placeholder')" min="6" max="360" required>
                                    @error('loan_duration') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="loan_purpose" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.purpose') *</label>
                                    <textarea wire:model="loan_purpose" id="loan_purpose" rows="3" 
                                            class="w-full px-4 py-3 border @error('loan_purpose') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                            placeholder="@lang('loan.purpose_placeholder')" required></textarea>
                                    @error('loan_purpose') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="monthly_income" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.income') *</label>
                                    <input type="number" wire:model="monthly_income" id="monthly_income" 
                                        class="w-full px-4 py-3 border @error('monthly_income') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                        placeholder="@lang('loan.monthly_income_placeholder')" min="1" required>
                                    @error('monthly_income') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="employment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('loan.employment_status') *</label>
                                    <select wire:model="employment_status" id="employment_status" 
                                            class="w-full px-4 py-3 border @error('employment_status') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white" required>
                                        <option value="">@lang('loan.select')</option>
                                        @foreach(trans('loan.employment_status_options') as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('employment_status') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <!-- Bouton de soumission -->
                        <button type="submit"
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-brand-primary hover:bg-brand-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="submit" class="flex items-center">{{ __('loan.submit_button.default') }}</span>
                            <span wire:loading wire:target="submit" class="flex items-center hidden">                                
                                {{ __('loan.submit_button.loading') }}
                            </span>
                        </button>
                    </form>
                    @else
                    <div class="text-center py-12">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/30">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">@lang('loan.request_submitted')</h3>
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
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Script pour gérer le rechargement après 20 secondes -->
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('reset-form-after-delay', () => {
            setTimeout(() => {
                Livewire.dispatch('reset-form');
            }, 20000);
        });
    });
</script>
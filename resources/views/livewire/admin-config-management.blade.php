<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
    <!-- Header -->
    <div class="bg-gradient-to-r from-brand-primary to-brand-accent p-6">
        <h2 class="text-xl font-semibold text-white mb-2">{{ __('admin.system_configuration') }}</h2>
        <div class="w-16 h-1 bg-white/30 rounded-full"></div>
    </div>

    <div class="p-6">
        <form wire:submit.prevent="updateConfig" class="space-y-8">
            
            <!-- Configuration IBAN -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    <i class="fa-solid fa-university mr-2 text-brand-primary"></i>
                    {{ __('admin.iban_configuration') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.iban_country_code') }}
                        </label>
                        <input type="text" wire:model="iban_country_code" maxlength="2" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('iban_country_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.iban_bank_code') }}
                        </label>
                        <input type="text" wire:model="iban_bank_code" maxlength="10" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('iban_bank_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.iban_branch_code') }}
                        </label>
                        <input type="text" wire:model="iban_branch_code" maxlength="10" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('iban_branch_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.iban_account_length') }}
                        </label>
                        <input type="number" wire:model="iban_account_length" min="1" max="50" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('iban_account_length') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.iban_prefix') }}
                        </label>
                        <input type="text" wire:model="iban_prefix" maxlength="10" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('iban_prefix') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Informations Bancaires -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    <i class="fa-solid fa-building mr-2 text-brand-primary"></i>
                    {{ __('admin.bank_information') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.bank_name') }}
                        </label>
                        <input type="text" wire:model="bank_name" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('bank_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.bank_swift') }}
                        </label>
                        <input type="text" wire:model="bank_swift" maxlength="11" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('bank_swift') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.bank_country') }}
                        </label>
                        <input type="text" wire:model="bank_country" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('bank_country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.bank_phone') }}
                        </label>
                        <input type="text" wire:model="bank_phone" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('bank_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.bank_email') }}
                        </label>
                        <input type="email" wire:model="bank_email" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('bank_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.bank_website') }}
                        </label>
                        <input type="url" wire:model="bank_website" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('bank_website') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.bank_address') }}
                        </label>
                        <textarea wire:model="bank_address" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100"></textarea>
                        @error('bank_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Images et Branding -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    <i class="fa-solid fa-image mr-2 text-brand-primary"></i>
                    {{ __('admin.branding_images') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Logo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.logo') }}
                        </label>
                        @if($logo_url)
                            <div class="mb-3 p-3 bg-white dark:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-500">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Logo actuel :</p>
                                <img src="{{ asset($logo_url) }}" alt="Logo" class="h-16 w-auto object-contain mx-auto">
                            </div>
                        @else
                            <div class="mb-3 p-3 bg-gray-100 dark:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-500">
                                <p class="text-xs text-gray-500 dark:text-gray-400 text-center">Aucun logo configuré</p>
                            </div>
                        @endif
                        <input type="file" wire:model="logo" accept="image/*" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('logo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <div wire:loading wire:target="logo" class="text-sm text-gray-500 mt-1">
                            {{ __('admin.uploading') }}...
                        </div>
                    </div>

                    <!-- Icône -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.icon') }}
                        </label>
                        @if($icon_url)
                            <div class="mb-3 p-3 bg-white dark:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-500">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Icône actuelle :</p>
                                <img src="{{ asset($icon_url) }}" alt="Icon" class="h-12 w-12 object-contain mx-auto">
                            </div>
                        @else
                            <div class="mb-3 p-3 bg-gray-100 dark:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-500">
                                <p class="text-xs text-gray-500 dark:text-gray-400 text-center">Aucune icône configurée</p>
                            </div>
                        @endif
                        <input type="file" wire:model="icon" accept="image/*" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('icon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <div wire:loading wire:target="icon" class="text-sm text-gray-500 mt-1">
                            {{ __('admin.uploading') }}...
                        </div>
                    </div>

                    <!-- Favicon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.favicon') }}
                        </label>
                        @if($favicon_url)
                            <div class="mb-3 p-3 bg-white dark:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-500">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Favicon actuel :</p>
                                <img src="{{ asset($favicon_url) }}" alt="Favicon" class="h-8 w-8 object-contain mx-auto">
                            </div>
                        @else
                            <div class="mb-3 p-3 bg-gray-100 dark:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-500">
                                <p class="text-xs text-gray-500 dark:text-gray-400 text-center">Aucun favicon configuré</p>
                            </div>
                        @endif
                        <input type="file" wire:model="favicon" accept="image/*" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('favicon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <div wire:loading wire:target="favicon" class="text-sm text-gray-500 mt-1">
                            {{ __('admin.uploading') }}...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Couleurs de Marque -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    <i class="fa-solid fa-palette mr-2 text-brand-primary"></i>
                    {{ __('admin.brand_colors') }}
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.brand_primary') }}
                        </label>
                        <input type="color" wire:model="brand_color" 
                               class="w-full h-10 border border-gray-300 dark:border-gray-600 rounded-lg">
                        @error('brand_color') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.brand_secondary') }}
                        </label>
                        <input type="color" wire:model="brand_secondary" 
                               class="w-full h-10 border border-gray-300 dark:border-gray-600 rounded-lg">
                        @error('brand_secondary') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.brand_accent') }}
                        </label>
                        <input type="color" wire:model="brand_accent" 
                               class="w-full h-10 border border-gray-300 dark:border-gray-600 rounded-lg">
                        @error('brand_accent') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.brand_success') }}
                        </label>
                        <input type="color" wire:model="brand_success" 
                               class="w-full h-10 border border-gray-300 dark:border-gray-600 rounded-lg">
                        @error('brand_success') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.brand_warning') }}
                        </label>
                        <input type="color" wire:model="brand_warning" 
                               class="w-full h-10 border border-gray-300 dark:border-gray-600 rounded-lg">
                        @error('brand_warning') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.brand_error') }}
                        </label>
                        <input type="color" wire:model="brand_error" 
                               class="w-full h-10 border border-gray-300 dark:border-gray-600 rounded-lg">
                        @error('brand_error') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Configuration Système -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    <i class="fa-solid fa-cogs mr-2 text-brand-primary"></i>
                    {{ __('admin.system_settings') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.notification_email') }}
                        </label>
                        <input type="email" wire:model="notification_email" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('notification_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.transaction_validation_method') }}
                        </label>
                        <select wire:model="transaction_validation_method" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                            <option value="">{{ __('admin.select_method') }}</option>
                            <option value="manual">{{ __('admin.manual_validation') }}</option>
                            <option value="automatic">{{ __('admin.automatic_validation') }}</option>
                        </select>
                        @error('transaction_validation_method') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.account_prefix') }}
                        </label>
                        <input type="text" wire:model="account_prefix" maxlength="10" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('account_prefix') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.account_length') }}
                        </label>
                        <input type="number" wire:model="account_length" min="1" max="50" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                        @error('account_length') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="two_factor_auth" 
                                   class="rounded border-gray-300 text-brand-primary shadow-sm focus:ring-brand-primary">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ __('admin.enable_two_factor_auth') }}
                            </span>
                        </label>
                        @error('two_factor_auth') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <br>
            <!-- Bouton de sauvegarde -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-brand-primary hover:bg-brand-primary-dark text-white font-medium rounded-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="updateConfig">
                        <i class="fa-solid fa-save mr-2"></i>
                        {{ __('admin.save_configuration') }}
                    </span>
                    <span wire:loading wire:target="updateConfig">
                        <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                        {{ __('admin.saving') }}...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
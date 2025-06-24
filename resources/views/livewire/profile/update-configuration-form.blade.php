<x-form-section submit="updateConfiguration">
    <x-slot name="title">
        {{ __("Configuration du Système") }}
    </x-slot>

    <x-slot name="description">
        {{ __("Gérez les paramètres de configuration de l'application, les logos et les couleurs de marque.") }}
    </x-slot>

    <x-slot name="form">
        <!-- Logo -->
        <div x-data="{logoName: null, logoPreview: null}" class="col-span-6 sm:col-span-4">
            <input type="file" id="logo" class="hidden"
                        wire:model.live="logo"
                        x-ref="logo"
                        x-on:change="
                                logoName = $refs.logo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    logoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.logo.files[0]);
                        " />

            <x-label for="logo" value="{{ __("Logo de l'application") }}" />

            <div class="mt-2" x-show="! logoPreview">
                @if($this->config->logo_url)
                    <img src="{{ Storage::url($this->config->logo_url) }}" alt="Logo" class="h-20 w-auto object-contain">
                @else
                    <div class="h-20 w-20 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
            </div>

            <div class="mt-2" x-show="logoPreview" style="display: none;">
                <img x-bind:src="logoPreview" class="h-20 w-auto object-contain">
            </div>

            <button type="button" class="mt-2 me-2 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150" x-on:click.prevent="$refs.logo.click()" wire:loading.attr="disabled" wire:target="logo">
                <span wire:loading.remove wire:target="logo">{{ __("Sélectionner un logo") }}</span>
                <span wire:loading wire:target="logo" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __("Chargement...") }}
                </span>
            </button>

            @if ($this->config->logo_url)
                <button type="button" class="mt-2 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150" wire:click="deleteLogo" wire:confirm="{{ __("Êtes-vous sûr de vouloir supprimer ce logo ?") }}" wire:loading.attr="disabled" wire:target="deleteLogo">
                    <span wire:loading.remove wire:target="deleteLogo">{{ __("Supprimer le logo") }}</span>
                    <span wire:loading wire:target="deleteLogo" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __("Suppression...") }}
                    </span>
                </button>
            @endif

            <x-input-error for="logo" class="mt-2" />
        </div>

        <!-- Icon -->
        <div x-data="{iconName: null, iconPreview: null}" class="col-span-6 sm:col-span-4">
            <input type="file" id="icon" class="hidden"
                        wire:model.live="icon"
                        x-ref="icon"
                        x-on:change="
                                iconName = $refs.icon.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    iconPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.icon.files[0]);
                        " />

            <x-label for="icon" value="{{ __("Icône de l'application") }}" />

            <div class="mt-2" x-show="! iconPreview">
                @if($this->config->icon_url)
                    <img src="{{ Storage::url($this->config->icon_url) }}" alt="Icon" class="h-16 w-16 object-contain">
                @else
                    <div class="h-16 w-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
            </div>

            <div class="mt-2" x-show="iconPreview" style="display: none;">
                <img x-bind:src="iconPreview" class="h-16 w-16 object-contain">
            </div>

            <button type="button" class="mt-2 me-2 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150" x-on:click.prevent="$refs.icon.click()" wire:loading.attr="disabled" wire:target="icon">
                <span wire:loading.remove wire:target="icon">{{ __("Sélectionner une icône") }}</span>
                <span wire:loading wire:target="icon" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __("Chargement...") }}
                </span>
            </button>

            @if ($this->config->icon_url)
                <button type="button" class="mt-2 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150" wire:click="deleteIcon" wire:confirm="{{ __("Êtes-vous sûr de vouloir supprimer cette icône ?") }}" wire:loading.attr="disabled" wire:target="deleteIcon">
                    <span wire:loading.remove wire:target="deleteIcon">{{ __("Supprimer l'icône") }}</span>
                    <span wire:loading wire:target="deleteIcon" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __("Suppression...") }}
                    </span>
                </button>
            @endif

            <x-input-error for="icon" class="mt-2" />
        </div>

        <!-- Favicon -->
        <div x-data="{faviconName: null, faviconPreview: null}" class="col-span-6 sm:col-span-4">
            <input type="file" id="favicon" class="hidden"
                        wire:model.live="favicon"
                        x-ref="favicon"
                        x-on:change="
                                faviconName = $refs.favicon.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    faviconPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.favicon.files[0]);
                        " />

            <x-label for="favicon" value="{{ __("Favicon") }}" />

            <div class="mt-2" x-show="! faviconPreview">
                @if($this->config->favicon_url)
                    <img src="{{ Storage::url($this->config->favicon_url) }}" alt="Favicon" class="h-8 w-8 object-contain">
                @else
                    <div class="h-8 w-8 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
            </div>

            <div class="mt-2" x-show="faviconPreview" style="display: none;">
                <img x-bind:src="faviconPreview" class="h-8 w-8 object-contain">
            </div>

            <button type="button" class="mt-2 me-2 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150" x-on:click.prevent="$refs.favicon.click()" wire:loading.attr="disabled" wire:target="favicon">
                <span wire:loading.remove wire:target="favicon">{{ __("Sélectionner un favicon") }}</span>
                <span wire:loading wire:target="favicon" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __("Chargement...") }}
                </span>
            </button>

            @if ($this->config->favicon_url)
                <button type="button" class="mt-2 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150" wire:click="deleteFavicon" wire:confirm="{{ __("Êtes-vous sûr de vouloir supprimer ce favicon ?") }}" wire:loading.attr="disabled" wire:target="deleteFavicon">
                    <span wire:loading.remove wire:target="deleteFavicon">{{ __("Supprimer le favicon") }}</span>
                    <span wire:loading wire:target="deleteFavicon" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __("Suppression...") }}
                    </span>
                </button>
            @endif

            <x-input-error for="favicon" class="mt-2" />
        </div>

        <!-- Bank Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="bank_name" value="{{ __("Nom de la banque") }}" />
            <x-input id="bank_name" type="text" class="mt-1 block w-full" wire:model="state.bank_name" required />
            <x-input-error for="state.bank_name" class="mt-2" />
        </div>

        <!-- Bank Swift -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="bank_swift" value="{{ __("Code SWIFT") }}" />
            <x-input id="bank_swift" type="text" class="mt-1 block w-full" wire:model="state.bank_swift" />
            <x-input-error for="state.bank_swift" class="mt-2" />
        </div>

        <!-- Bank Country -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="bank_country" value="{{ __("Pays de la banque") }}" />
            <x-input id="bank_country" type="text" class="mt-1 block w-full" wire:model="state.bank_country" />
            <x-input-error for="state.bank_country" class="mt-2" />
        </div>

        <!-- Bank Address -->
        <div class="col-span-6">
            <x-label for="bank_address" value="{{ __("Adresse de la banque") }}" />
            <textarea id="bank_address" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-brand-primary dark:focus:border-brand-primary focus:ring-brand-primary dark:focus:ring-brand-primary rounded-md shadow-sm" rows="3" wire:model="state.bank_address"></textarea>
            <x-input-error for="state.bank_address" class="mt-2" />
        </div>

        <!-- Bank Phone -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="bank_phone" value="{{ __("Téléphone de la banque") }}" />
            <x-input id="bank_phone" type="text" class="mt-1 block w-full" wire:model="state.bank_phone" />
            <x-input-error for="state.bank_phone" class="mt-2" />
        </div>

        <!-- Bank Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="bank_email" value="{{ __("Email de la banque") }}" />
            <x-input id="bank_email" type="email" class="mt-1 block w-full" wire:model="state.bank_email" />
            <x-input-error for="state.bank_email" class="mt-2" />
        </div>

        <!-- Bank Website -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="bank_website" value="{{ __("Site web de la banque") }}" />
            <x-input id="bank_website" type="url" class="mt-1 block w-full" wire:model="state.bank_website" />
            <x-input-error for="state.bank_website" class="mt-2" />
        </div>

        <!-- IBAN Configuration -->
        <div class="col-span-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __("Configuration IBAN") }}</h3>
        </div>

        <!-- IBAN Country Code -->
        <div class="col-span-6 sm:col-span-2">
            <x-label for="iban_country_code" value="{{ __("Code pays IBAN") }}" />
            <x-input id="iban_country_code" type="text" class="mt-1 block w-full" wire:model="state.iban_country_code" maxlength="2" />
            <x-input-error for="state.iban_country_code" class="mt-2" />
        </div>

        <!-- IBAN Bank Code -->
        <div class="col-span-6 sm:col-span-2">
            <x-label for="iban_bank_code" value="{{ __("Code banque IBAN") }}" />
            <x-input id="iban_bank_code" type="text" class="mt-1 block w-full" wire:model="state.iban_bank_code" />
            <x-input-error for="state.iban_bank_code" class="mt-2" />
        </div>

        <!-- IBAN Branch Code -->
        <div class="col-span-6 sm:col-span-2">
            <x-label for="iban_branch_code" value="{{ __("Code agence IBAN") }}" />
            <x-input id="iban_branch_code" type="text" class="mt-1 block w-full" wire:model="state.iban_branch_code" />
            <x-input-error for="state.iban_branch_code" class="mt-2" />
        </div>

        <!-- Account Configuration -->
        <div class="col-span-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 mt-6">{{ __("Configuration des Comptes") }}</h3>
        </div>

        <!-- Account Prefix -->
        <div class="col-span-6 sm:col-span-3">
            <x-label for="account_prefix" value="{{ __("Préfixe des comptes") }}" />
            <x-input id="account_prefix" type="text" class="mt-1 block w-full" wire:model="state.account_prefix" />
            <x-input-error for="state.account_prefix" class="mt-2" />
        </div>

        <!-- Account Length -->
        <div class="col-span-6 sm:col-span-3">
            <x-label for="account_length" value="{{ __("Longueur des comptes") }}" />
            <x-input id="account_length" type="number" class="mt-1 block w-full" wire:model="state.account_length" min="1" max="50" />
            <x-input-error for="state.account_length" class="mt-2" />
        </div>

        <!-- Notification Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="notification_email" value="{{ __("Email de notification") }}" />
            <x-input id="notification_email" type="email" class="mt-1 block w-full" wire:model="state.notification_email" />
            <x-input-error for="state.notification_email" class="mt-2" />
        </div>

        <!-- Two Factor Auth -->
        <div class="col-span-6 sm:col-span-4">
            <div class="flex items-center">
                <input id="two_factor_auth" type="checkbox" class="rounded border-gray-300 text-brand-primary shadow-sm focus:ring-brand-primary" wire:model="state.two_factor_auth">
                <label for="two_factor_auth" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                    {{ __("Authentification à deux facteurs activée") }}
                </label>
            </div>
            <x-input-error for="state.two_factor_auth" class="mt-2" />
        </div>

        <!-- Transaction Validation Method -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="transaction_validation_method" value="{{ __("Méthode de validation des transactions") }}" />
            <select id="transaction_validation_method" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-brand-primary dark:focus:border-brand-primary focus:ring-brand-primary dark:focus:ring-brand-primary rounded-md shadow-sm" wire:model="state.transaction_validation_method">
                <option value="email">{{ __("Email") }}</option>
                <option value="sms">{{ __("SMS") }}</option>
                <option value="both">{{ __("Email et SMS") }}</option>
            </select>
            <x-input-error for="state.transaction_validation_method" class="mt-2" />
        </div>

        <!-- Brand Colors -->
        <div class="col-span-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 mt-6">{{ __("Couleurs de Marque") }}</h3>
        </div>

        <!-- Brand Primary Color -->
        <div class="col-span-6 sm:col-span-3">
            <x-label for="brand_color" value="{{ __("Couleur primaire") }}" />
            <div class="flex items-center space-x-2">
                <input id="brand_color" type="color" class="mt-1 h-10 w-20 border-gray-300 rounded-md" wire:model="state.brand_color">
                <x-input id="brand_color_text" type="text" class="mt-1 block flex-1" wire:model="state.brand_color" placeholder="#000000" />
            </div>
            <x-input-error for="state.brand_color" class="mt-2" />
        </div>

        <!-- Brand Secondary Color -->
        <div class="col-span-6 sm:col-span-3">
            <x-label for="brand_secondary" value="{{ __("Couleur secondaire") }}" />
            <div class="flex items-center space-x-2">
                <input id="brand_secondary" type="color" class="mt-1 h-10 w-20 border-gray-300 rounded-md" wire:model="state.brand_secondary">
                <x-input id="brand_secondary_text" type="text" class="mt-1 block flex-1" wire:model="state.brand_secondary" placeholder="#000000" />
            </div>
            <x-input-error for="state.brand_secondary" class="mt-2" />
        </div>

        <!-- Brand Success Color -->
        <div class="col-span-6 sm:col-span-3">
            <x-label for="brand_success" value="{{ __("Couleur de succès") }}" />
            <div class="flex items-center space-x-2">
                <input id="brand_success" type="color" class="mt-1 h-10 w-20 border-gray-300 rounded-md" wire:model="state.brand_success">
                <x-input id="brand_success_text" type="text" class="mt-1 block flex-1" wire:model="state.brand_success" placeholder="#10B981" />
            </div>
            <x-input-error for="state.brand_success" class="mt-2" />
        </div>

        <!-- Brand Warning Color -->
        <div class="col-span-6 sm:col-span-3">
            <x-label for="brand_warning" value="{{ __("Couleur d'avertissement") }}" />
            <div class="flex items-center space-x-2">
                <input id="brand_warning" type="color" class="mt-1 h-10 w-20 border-gray-300 rounded-md" wire:model="state.brand_warning">
                <x-input id="brand_warning_text" type="text" class="mt-1 block flex-1" wire:model="state.brand_warning" placeholder="#F59E0B" />
            </div>
            <x-input-error for="state.brand_warning" class="mt-2" />
        </div>

        <!-- Brand Error Color -->
        <div class="col-span-6 sm:col-span-3">
            <x-label for="brand_error" value="{{ __("Couleur d'erreur") }}" />
            <div class="flex items-center space-x-2">
                <input id="brand_error" type="color" class="mt-1 h-10 w-20 border-gray-300 rounded-md" wire:model="state.brand_error">
                <x-input id="brand_error_text" type="text" class="mt-1 block flex-1" wire:model="state.brand_error" placeholder="#EF4444" />
            </div>
            <x-input-error for="state.brand_error" class="mt-2" />
        </div>

        <!-- Brand Accent Color -->
        <div class="col-span-6 sm:col-span-3">
            <x-label for="brand_accent" value="{{ __("Couleur d'accent") }}" />
            <div class="flex items-center space-x-2">
                <input id="brand_accent" type="color" class="mt-1 h-10 w-20 border-gray-300 rounded-md" wire:model="state.brand_accent">
                <x-input id="brand_accent_text" type="text" class="mt-1 block flex-1" wire:model="state.brand_accent" placeholder="#8B5CF6" />
            </div>
            <x-input-error for="state.brand_accent" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __("Sauvegardé.") }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="logo,icon,favicon,updateConfiguration">
            <span wire:loading.remove wire:target="updateConfiguration">{{ __("Sauvegarder") }}</span>
            <span wire:loading wire:target="updateConfiguration" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ __("Sauvegarde...") }}
            </span>
        </x-button>
    </x-slot>
</x-form-section>
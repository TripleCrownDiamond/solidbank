<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __("Profile Information") }}
    </x-slot>

    <x-slot name="description">
        {{ __("Update your account's profile information and email address.") }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __("Photo") }}" />

                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full size-20 object-cover">
                </div>

                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <button type="button" class="mt-2 me-2 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150" x-on:click.prevent="$refs.photo.click()" wire:loading.attr="disabled" wire:target="photo">
                    <span wire:loading.remove wire:target="photo">{{ __("Select A New Photo") }}</span>
                    <span wire:loading wire:target="photo" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __("Loading...") }}
                    </span>
                </button>

                @if ($this->user->profile_photo_path)
                    <button type="button" class="mt-2 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150" wire:click="deleteProfilePhoto" wire:confirm="{{ __('profile.confirm_delete_photo') }}" wire:loading.attr="disabled" wire:target="deleteProfilePhoto">
                        <span wire:loading.remove wire:target="deleteProfilePhoto">{{ __("Remove Photo") }}</span>
                        <span wire:loading wire:target="deleteProfilePhoto" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __("Removing...") }}
                        </span>
                    </button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __("Name") }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- First Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="first_name" value="{{ __("First Name") }}" />
            <x-input id="first_name" type="text" class="mt-1 block w-full" wire:model="state.first_name" autocomplete="given-name" />
            <x-input-error for="first_name" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="last_name" value="{{ __("Last Name") }}" />
            <x-input id="last_name" type="text" class="mt-1 block w-full" wire:model="state.last_name" autocomplete="family-name" />
            <x-input-error for="last_name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __("Email") }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2 dark:text-white">
                    {{ __("Your email address is unverified.") }}

                    <button type="button" class="underline text-sm text-brand-primary dark:text-brand-primary hover:text-brand-secondary dark:hover:text-brand-secondary rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary dark:focus:ring-offset-gray-800" wire:click.prevent="sendEmailVerification" wire:loading.attr="disabled" wire:target="sendEmailVerification">
                        <span wire:loading.remove wire:target="sendEmailVerification">{{ __("Click here to re-send the verification email.") }}</span>
                        <span wire:loading wire:target="sendEmailVerification" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-3 w-3 text-brand-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __("Sending...") }}
                        </span>
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ __("A new verification link has been sent to your email address.") }}
                    </p>
                @endif
            @endif
        </div>



        <!-- Birth Date -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="birth_date" value="{{ __("Birth Date") }}" />
            <x-input id="birth_date" type="date" class="mt-1 block w-full" wire:model="state.birth_date" />
            <x-input-error for="birth_date" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="phone_number" value="{{ __("Phone Number") }}" />
            <x-input id="phone_number" type="tel" class="mt-1 block w-full" wire:model="state.phone_number" autocomplete="tel" />
            <x-input-error for="phone_number" class="mt-2" />
        </div>



        <!-- Address -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="address" value="{{ __("Address") }}" />
            <x-input id="address" type="text" class="mt-1 block w-full" wire:model="state.address" autocomplete="street-address" />
            <x-input-error for="address" class="mt-2" />
        </div>

        <!-- City -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="city" value="{{ __("City") }}" />
            <x-input id="city" type="text" class="mt-1 block w-full" wire:model="state.city" autocomplete="address-level2" />
            <x-input-error for="city" class="mt-2" />
        </div>

        <!-- Postal Code -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="postal_code" value="{{ __("Postal Code") }}" />
            <x-input id="postal_code" type="text" class="mt-1 block w-full" wire:model="state.postal_code" autocomplete="postal-code" />
            <x-input-error for="postal_code" class="mt-2" />
        </div>







        <!-- Identity Document -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="identityDocument" value="{{ __("Identity Document") }}" />
            <div class="relative">
                <input id="identityDocument" type="file" class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-brand-primary focus:ring focus:ring-brand-primary focus:ring-opacity-50 rounded-md shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 dark:file:bg-gray-100 file:text-black dark:file:text-black hover:file:bg-gray-200 dark:hover:file:bg-gray-200" wire:model="identityDocument" accept=".pdf,.jpg,.jpeg,.png" />
                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-data="{ hasFile: false }" x-init="$watch('$wire.identityDocument', value => hasFile = !!value)">
                    <span x-show="!hasFile">{{ __("No file chosen") }}</span>
                </div>
            </div>
            <x-input-error for="identityDocument" class="mt-2" />
            @if($this->user->identity_document_url)
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    {{ __("Current document:") }} 
                    <a href="{{ Storage::url($this->user->identity_document_url) }}" target="_blank" class="text-brand-primary hover:text-brand-secondary underline">
                        {{ __("View Document") }}
                    </a>
                </p>
            @endif
        </div>

        <!-- Address Document -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="addressDocument" value="{{ __("Address Document") }}" />
            <div class="relative">
                <input id="addressDocument" type="file" class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-brand-primary focus:ring focus:ring-brand-primary focus:ring-opacity-50 rounded-md shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 dark:file:bg-gray-100 file:text-black dark:file:text-black hover:file:bg-gray-200 dark:hover:file:bg-gray-200" wire:model="addressDocument" accept=".pdf,.jpg,.jpeg,.png" />
                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-data="{ hasFile: false }" x-init="$watch('$wire.addressDocument', value => hasFile = !!value)">
                    <span x-show="!hasFile">{{ __("No file chosen") }}</span>
                </div>
            </div>
            <x-input-error for="addressDocument" class="mt-2" />
            @if($this->user->address_document_url)
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    {{ __("Current document:") }} 
                    <a href="{{ Storage::url($this->user->address_document_url) }}" target="_blank" class="text-brand-primary hover:text-brand-secondary underline">
                        {{ __("View Document") }}
                    </a>
                </p>
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __("Saved.") }}
        </x-action-message>

        <button type="submit" class="px-4 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary-hover transition flex items-center" wire:loading.attr="disabled" wire:target="updateProfileInformation,photo,identityDocument,addressDocument">
            <span wire:loading.remove wire:target="updateProfileInformation,photo,identityDocument,addressDocument">{{ __("Save") }}</span>
            <span wire:loading wire:target="updateProfileInformation,photo,identityDocument,addressDocument" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ __("Saving...") }}
            </span>
        </button>
    </x-slot>
</x-form-section>
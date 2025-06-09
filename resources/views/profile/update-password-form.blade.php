<x-form-section submit="updatePassword">
    <x-slot name="title">
        {{ __('profile.update_password') }}
    </x-slot>

    <x-slot name="description">
        {{ __('profile.password_security_message') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-label for="current_password" value="{{ __('profile.current_password') }}" />
            <x-input id="current_password" type="password" class="mt-1 block w-full" wire:model="state.current_password" autocomplete="current-password" />
            <x-input-error for="current_password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4" x-data="{ showPassword: false }">
            <x-label for="password" value="{{ __('profile.new_password') }}" />
            <div class="relative">
                <x-input id="password" type="password" class="mt-1 block w-full pr-20" wire:model="state.password" autocomplete="new-password" x-bind:type="showPassword ? 'text' : 'password'" />
                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm font-medium text-brand-primary hover:text-brand-primary/80" x-on:click="showPassword = !showPassword">
                    <span x-show="!showPassword">Afficher</span>
                    <span x-show="showPassword" style="display: none;">Masquer</span>
                </button>
            </div>
            <x-input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4" x-data="{ showPasswordConfirmation: false }">
            <x-label for="password_confirmation" value="{{ __('profile.confirm_password') }}" />
            <div class="relative">
                <x-input id="password_confirmation" type="password" class="mt-1 block w-full pr-20" wire:model="state.password_confirmation" autocomplete="new-password" x-bind:type="showPasswordConfirmation ? 'text' : 'password'" />
                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm font-medium text-brand-primary hover:text-brand-primary/80" x-on:click="showPasswordConfirmation = !showPasswordConfirmation">
                    <span x-show="!showPasswordConfirmation">Afficher</span>
                    <span x-show="showPasswordConfirmation" style="display: none;">Masquer</span>
                </button>
            </div>
            <x-input-error for="password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('profile.saved') }}
        </x-action-message>

        <button type="submit" class="px-4 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary-hover transition flex items-center" wire:loading.attr="disabled" wire:target="updatePassword">
            <span wire:loading.remove wire:target="updatePassword">{{ __('profile.save') }}</span>
            <span wire:loading wire:target="updatePassword" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ __('profile.saving') }}
            </span>
        </button>
    </x-slot>
</x-form-section>

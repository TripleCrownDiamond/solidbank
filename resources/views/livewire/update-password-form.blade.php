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
            <input id="current_password" type="password" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" wire:model="state.current_password" autocomplete="current-password" />
            <x-input-error for="state.current_password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="password" value="{{ __('profile.new_password') }}" />
            <input id="password" type="password" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" wire:model="state.password" autocomplete="new-password" />
            <x-input-error for="state.password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="password_confirmation" value="{{ __('profile.confirm_password') }}" />
            <input id="password_confirmation" type="password" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" wire:model="state.password_confirmation" autocomplete="new-password" />
            <x-input-error for="state.password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        @if ($showSuccess)
            <div class="me-3 text-sm text-green-600 dark:text-green-400" 
                 x-data="{ show: @entangle('showSuccess') }" 
                 x-show="show" 
                 x-init="setTimeout(() => { show = false; $wire.set('showSuccess', false); }, 5000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                {{ $successMessage }}
            </div>
        @endif
        
        <x-action-message class="me-3" on="saved">
            {{ __("profile.saved") }}
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

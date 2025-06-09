<x-action-section>
    <x-slot name="title">
        {{ __('profile.delete_account') }}
    </x-slot>

    <x-slot name="description">
        {{ __('profile.permanently_delete_account') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            {{ __('profile.delete_account_warning') }}
        </div>

        <div class="mt-5">
            <x-danger-button wire:click="deleteUser" wire:confirm="{{ __('profile.delete_account_confirmation') }}" wire:loading.attr="disabled">
                {{ __('profile.delete_account') }}
            </x-danger-button>
        </div>
    </x-slot>
</x-action-section>

<button type="button" 
        wire:click="confirmTransfer"
        @disabled(!$isOtpValid)
        wire:loading.attr="disabled"
        class="px-6 py-2 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
    <x-loader-spinner
        text="{{ __('common.processing') }}"
        position="left"
    >
        {{ __('transfers.confirm_transfer') }}
    </x-loader-spinner>
</button>
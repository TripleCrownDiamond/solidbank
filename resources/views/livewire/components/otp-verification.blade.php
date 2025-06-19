<div class="text-center py-8">
    @if (session()->has('otp_message'))
        <p class="text-green-500 text-sm mb-4">{{ session('otp_message') }}</p>
    @endif

    @if (!$otpVerified)
        <div>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">{{ __('transfers.otp_sent_to_email') }}</p>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('transfers.enter_otp') }}</label>
            <input type="text" wire:model.live="otp" 
                class="w-full max-w-xs mx-auto px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-center text-xl tracking-widest focus:outline-none focus:ring-2 focus:ring-brand-primary dark:bg-gray-700 dark:text-white"
                placeholder="______" maxlength="6">
            @error('otp')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
            
            <div class="mt-4 space-y-2">
                <button type="button" wire:click="verifyOtp" 
                    class="px-6 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    {{ strlen($otp) !== 6 ? 'disabled' : '' }}>
                    <x-loader-spinner
                        target="verifyOtp"
                        text="{{ __('common.processing') }}"
                        position="left"
                    >
                        {{ __('transfers.verify_otp') }}
                    </x-loader-spinner>
                </button>
                <button type="button" wire:click="resendOtp" class="text-brand-primary hover:underline text-sm ml-4">
                    <x-loader-spinner
                        target="resendOtp"
                        text="{{ __('common.processing') }}"
                        position="left"
                    >
                        {{ __('transfers.resend_otp') }}
                    </x-loader-spinner>
                </button>
            </div>
        </div>
    @else
        <!-- Feedback de vérification réussie -->
        <div class="text-center">
            <div class="mb-4">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full mb-4">
                    <i class="fa-solid fa-check text-2xl text-green-600 dark:text-green-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('transfers.otp_verified_title') }}</h3>
                <p class="text-green-600 dark:text-green-400 mb-4">{{ $otpMessage }}</p>
                
                <!-- Bouton de confirmation du transfert -->
                <button type="button" wire:click="confirmTransfer" 
                    class="px-6 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled"
                    wire:target="confirmTransfer"
                    id="confirmTransferBtn">
                    <span wire:loading.remove wire:target="confirmTransfer">
                        {{ __('transfers.confirm_transfer') }}
                    </span>
                    <span wire:loading wire:target="confirmTransfer" class="flex items-center">
                        <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                        {{ __('common.processing') }}
                    </span>
                </button>
                
                <script>
                    document.addEventListener('livewire:initialized', () => {
                        Livewire.on('transfer-confirmed', () => {
                            console.log('Transfer confirmed');
                            
                            // Désactiver le bouton pour éviter les clics multiples
                            const btn = document.querySelector('[wire\\:click="confirmTransfer"]');
                            if (btn) {
                                btn.disabled = true;
                                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.processing') }}';
                            }
                        });
                    });
                </script>
            </div>
        </div>
    @endif
</div>

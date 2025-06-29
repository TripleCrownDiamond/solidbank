<div>
    @if($message)
        <div class="mb-4 p-4 rounded-lg animate-fade-in {{ $messageType === 'success' ? 'bg-white/95 text-green-800 border-2 border-green-400 shadow-lg backdrop-blur-sm' : 'bg-white/95 text-red-800 border-2 border-red-400 shadow-lg backdrop-blur-sm' }}">
            <div class="flex items-center">
                @if($messageType === 'success')
                    <svg class="w-6 h-6 mr-3 text-green-600 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                @else
                    <svg class="w-6 h-6 mr-3 text-red-600 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                @endif
                <span class="font-semibold">{{ $message }}</span>
            </div>
        </div>
    @endif

    @if(!$isSubscribed)
        <form wire:submit="subscribe" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input 
                    type="email" 
                    wire:model="email"
                    placeholder="{{ __('common.enter_email') }}"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brand-primary focus:border-transparent text-gray-900 placeholder-gray-500"
                    required
                >
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button 
                type="submit" 
                class="px-6 py-3 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors duration-200 font-medium whitespace-nowrap"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
            >
                <span wire:loading.remove>{{ __('common.subscribe') }}</span>
                <span wire:loading class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('common.subscribing') }}
                </span>
            </button>
        </form>
    @endif
</div>

@if($success)
        <div class="bg-brand-success/10 border border-brand-success/20 text-brand-success px-4 py-3 rounded-lg mb-6 animate-pulse" id="success-message">
            {{ __('common.contact_success_message') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg mb-6">
            {{ __('common.contact_error_message') }}
        </div>
    @endif

    <form wire:submit="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold mb-2 text-gray-900 dark:text-white">{{ __('common.your_name') }}</label>
                <input 
                    type="text" 
                    wire:model="name"
                    class="w-full rounded-lg px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent transition duration-200 @error('name') border-red-500 @enderror" 
                    placeholder="{{ __('common.name_placeholder') }}"
                    required
                >
                @error('name') 
                    <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</span> 
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-bold mb-2 text-gray-900 dark:text-white">{{ __('common.your_email') }}</label>
                <input 
                    type="email" 
                    wire:model="email"
                    class="w-full rounded-lg px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror" 
                    placeholder="{{ __('common.email_placeholder') }}"
                    required
                >
                @error('email') 
                    <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</span> 
                @enderror
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-bold mb-2 text-gray-900 dark:text-white">{{ __('common.subject') }}</label>
            <input 
                type="text" 
                wire:model="subject"
                class="w-full rounded-lg px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent transition duration-200 @error('subject') border-red-500 @enderror" 
                placeholder="{{ __('common.subject_placeholder') }}"
                required
            >
            @error('subject') 
                <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</span> 
            @enderror
        </div>
        
        <div>
            <label class="block text-sm font-bold mb-2 text-gray-900 dark:text-white">{{ __('common.your_message') }}</label>
            <textarea 
                wire:model="message"
                class="w-full rounded-lg px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent transition duration-200 @error('message') border-red-500 @enderror" 
                rows="6" 
                placeholder="{{ __('common.message_placeholder') }}"
                required
            ></textarea>
            @error('message') 
                <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</span> 
            @enderror
        </div>
        
        <div class="flex justify-center">
            <button 
                type="submit" 
                class="bg-gradient-to-r from-brand-primary to-brand-accent hover:from-brand-primary-hover hover:to-brand-accent-hover text-white font-bold px-8 py-3 rounded-lg shadow-lg transform hover:scale-105 transition duration-200 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                wire:loading.attr="disabled"
                wire:target="submit"
            >
                <span>{{ __('common.send_message') }}</span>
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('hide-success-message', () => {
                setTimeout(() => {
                    // Réinitialiser la propriété success dans le composant Livewire
                    @this.set('success', false);
                }, 20000);
            });
        });
    </script>
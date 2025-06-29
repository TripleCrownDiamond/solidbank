@if($success)
        <div class="bg-brand-success/10 border border-brand-success/20 text-brand-success px-4 py-3 rounded-lg mb-6 animate-pulse" id="success-message">
            <strong>Succès!</strong> Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.
        </div>
    @endif

    @if(session()->has('error'))
        <div class="bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg mb-6">
            <strong>Erreur!</strong> {{ session('error') }}
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
                    placeholder="Votre nom complet"
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
                    placeholder="votre@email.com"
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
                placeholder="Sujet de votre message"
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
                placeholder="Décrivez votre demande en détail..."
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
            >
                <span wire:loading.remove>{{ __('common.send_message') }}</span>
                <span wire:loading>Envoi en cours...</span>
                
                <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
                
                <svg wire:loading class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
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
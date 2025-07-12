<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('common.my_accounts_ribs') }}
            </h3>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $accounts->count() }} {{ __('common.accounts') }}
            </div>
        </div>

        @if($user && !$user->is_admin)
            @if($accounts->count() > 0)
                <div class="space-y-4">
                    @foreach($accounts as $account)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <!-- Account Header -->
                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 cursor-pointer" 
                                 wire:click="toggleAccount({{ $account->id }})">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                                                <svg class="w-4 h-4 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ __('common.account') }} {{ $account->account_number }}
                                            </h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                {{ __('register.' . strtolower($account->type)) }} • 
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    @if($account->status === 'ACTIVE') bg-brand-success/10 text-brand-success dark:bg-brand-success/20 dark:text-brand-success
                                    @elseif($account->status === 'INACTIVE') bg-brand-warning/10 text-brand-warning dark:bg-brand-warning/20 dark:text-brand-warning
                                    @else bg-brand-danger/10 text-brand-danger dark:bg-brand-danger/20 dark:text-brand-danger @endif">
                                    {{ __('common.' . strtolower($account->status)) }}
                                </span>
                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ number_format($account->balance, 2) }} {{ $account->currency }}
                            </div>
                            @if($account->rib)
                                <div class="text-xs text-brand-success dark:text-brand-success">
                                    {{ __('common.rib_available') }}
                                </div>
                            @else
                                <div class="text-xs text-brand-danger dark:text-brand-danger">
                                    {{ __('common.no_rib') }}
                                </div>
                            @endif
                                        </div>
                                        <div class="transform transition-transform duration-200 {{ $this->isAccountExpanded($account->id) ? 'rotate-180' : '' }}">
                                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Details (Collapsible) -->
                            @if($this->isAccountExpanded($account->id))
                                <div class="px-4 py-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                                    @if($account->rib)
                                        <div class="space-y-6">
                                            <!-- Account Number -->
                                            <div class="group">
                                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">
                                                    {{ __('common.account_number') }}
                                                </label>
                                                <div class="relative flex items-center bg-gradient-to-r from-gray-100/50 to-gray-200/50 dark:from-gray-700 dark:to-gray-600 px-4 py-3 rounded-lg border border-gray-300/50 dark:border-gray-600 shadow-sm hover:shadow-md transition-all duration-200">
                                                    <div class="text-sm text-gray-800 dark:text-white font-mono flex-1 tracking-wide">
                                                        {{ $account->account_number }}
                                                    </div>
                                                    <button x-data="{ copied: false }" x-on:click="navigator.clipboard.writeText('{{ $account->account_number }}').then(() => { copied = true; setTimeout(() => copied = false, 2000); })" title="{{ __('common.copy_account_number') }}" class="ml-3 p-1.5 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-all duration-200">
                                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="copied" x-cloak>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!copied">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- IBAN and SWIFT -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="group">
                                                <label class="block text-xs font-semibold text-brand-primary dark:text-brand-primary-light uppercase tracking-wider mb-2">
                                                    {{ __('common.iban') }}
                                                </label>
                                                <div class="relative flex items-center bg-gradient-to-r from-brand-primary/5 to-brand-primary/10 dark:from-gray-700 dark:to-gray-600 px-4 py-3 rounded-lg border border-brand-primary/30 dark:border-gray-600 shadow-sm hover:shadow-md transition-all duration-200">
                                                    <div class="text-sm text-gray-800 dark:text-white font-mono flex-1 tracking-wide">
                                                        {{ $account->rib->iban }}
                                                    </div>
                                                    <button x-data="{ copied: false }" x-on:click="navigator.clipboard.writeText('{{ $account->rib->iban }}').then(() => { copied = true; setTimeout(() => copied = false, 2000); })" title="{{ __('common.copy_iban') }}" class="ml-3 p-1.5 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-all duration-200">
                                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="copied" x-cloak>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!copied">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label class="block text-xs font-semibold text-brand-secondary dark:text-brand-secondary uppercase tracking-wider mb-2">
                                                    {{ __('common.swift') }}
                                                </label>
                                                <div class="relative flex items-center bg-gradient-to-r from-brand-secondary/5 to-brand-secondary/10 dark:from-gray-700 dark:to-gray-600 px-4 py-3 rounded-lg border border-brand-secondary/30 dark:border-gray-600 shadow-sm hover:shadow-md transition-all duration-200">
                                                    <div class="text-sm text-gray-800 dark:text-white font-mono flex-1 tracking-wide">
                                                        {{ $account->rib->swift }}
                                                    </div>
                                                    <button x-data="{ copied: false }" x-on:click="navigator.clipboard.writeText('{{ $account->rib->swift }}').then(() => { copied = true; setTimeout(() => copied = false, 2000); })" title="{{ __('common.copy_swift') }}" class="ml-3 p-1.5 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-all duration-200">
                                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="copied" x-cloak>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!copied">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Bank Name -->
                                            <div class="group">
                                                <label class="block text-xs font-semibold text-brand-accent dark:text-brand-accent uppercase tracking-wider mb-2">
                                                    {{ __('common.bank_name') }}
                                                </label>
                                                <div class="relative flex items-center bg-gradient-to-r from-brand-accent/5 to-brand-accent/10 dark:from-gray-700 dark:to-gray-600 px-4 py-3 rounded-lg border border-brand-accent/30 dark:border-gray-600 shadow-sm hover:shadow-md transition-all duration-200">
                                                    <div class="text-sm text-gray-800 dark:text-white flex-1 font-medium">
                                                        {{ $account->rib->bank_name }}
                                                    </div>
                                                    <button x-data="{ copied: false }" x-on:click="navigator.clipboard.writeText('{{ $account->rib->bank_name }}').then(() => { copied = true; setTimeout(() => copied = false, 2000); })" title="{{ __('common.copy_bank_name') }}" class="ml-3 p-1.5 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-all duration-200">
                                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="copied" x-cloak>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!copied">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-6 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border-l-4 border-brand-primary">
                                            <div class="flex items-center text-xs text-gray-600 dark:text-gray-300">
                                                <svg class="w-4 h-4 mr-2 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="font-medium">{{ __('common.rib_created_at') }}:</span>
                                                <span class="ml-1">{{ $account->rib->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-8">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ __('common.no_rib_available') }}
                                            </h3>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                {{ __('common.rib_will_be_generated') }}
                                            </p>
                                        </div>
                                    @endif
                                    
                                    <!-- Suspension Details -->
                                    @if($account->status === 'SUSPENDED' && ($account->suspension_reason || $account->suspension_instructions))
                                        <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                            <div class="flex items-center mb-3">
                                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                                <h4 class="text-sm font-semibold text-red-800 dark:text-red-200">
                                                    {{ __('common.account_suspended') }}
                                                </h4>
                                            </div>
                                            
                                            @if($account->suspension_reason)
                                                <div class="mb-3">
                                                    <h5 class="text-xs font-medium text-red-700 dark:text-red-300 uppercase tracking-wide mb-1">
                                                        {{ __('common.suspension_reason') }}
                                                    </h5>
                                                    <p class="text-sm text-red-700 dark:text-red-300 leading-relaxed">
                                                        {{ $account->suspension_reason }}
                                                    </p>
                                                </div>
                                            @endif
                                            
                                            @if($account->suspension_instructions)
                                                <div>
                                                    <h5 class="text-xs font-medium text-red-700 dark:text-red-300 uppercase tracking-wide mb-1">
                                                        {{ __('common.suspension_instructions') }}
                                                    </h5>
                                                    <p class="text-sm text-red-700 dark:text-red-300 leading-relaxed">
                                                        {{ $account->suspension_instructions }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ __('common.no_accounts') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('common.contact_admin_for_account') }}
                    </p>
                </div>
            @endif
        @else
            <div class="text-center py-8">
                <p class="text-gray-500 dark:text-gray-400">
                    {{ __('common.admin_no_personal_accounts') }}
                </p>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('start-copy-timer', (event) => {
                setTimeout(() => {
                    @this.removeCopiedState(event.value);
                }, 2000);
            });
        });
    </script>
</div>
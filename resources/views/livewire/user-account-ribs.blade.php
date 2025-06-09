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
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-brand-primary/20">
                                                <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ __('common.account') }} {{ $account->account_number }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ __('register.' . strtolower($account->type)) }} • 
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                    @if($account->status === 'ACTIVE') bg-green-100 text-green-800 dark:bg-green-800 dark:text-white
                                                    @elseif($account->status === 'INACTIVE') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                                    @else bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @endif">
                                                    {{ __('common.' . strtolower($account->status)) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ number_format($account->balance, 2) }} {{ $account->currency }}
                                            </div>
                                            @if($account->rib)
                                                <div class="text-xs text-green-600 dark:text-green-400">
                                                    {{ __('common.rib_available') }}
                                                </div>
                                            @else
                                                <div class="text-xs text-red-600 dark:text-red-400">
                                                    {{ __('common.no_rib') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="transform transition-transform duration-200 {{ $this->isAccountExpanded($account->id) ? 'rotate-180' : '' }}">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                                                    {{ __('common.iban') }}
                                                </label>
                                                <div class="flex items-center bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded border">
                                                    <div class="text-sm text-black dark:text-white font-mono flex-1">
                                                        {{ $account->rib->iban }}
                                                    </div>
                                                    <button x-data x-on:click="$wire.copyRibDetail('{{ $account->rib->iban }}', '{{ __('common.iban') }}')" title="{{ __('common.copy_iban') }}" class="ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                                                    {{ __('common.swift') }}
                                                </label>
                                                <div class="flex items-center bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded border">
                                                    <div class="text-sm text-black dark:text-white font-mono flex-1">
                                                        {{ $account->rib->swift }}
                                                    </div>
                                                    <button x-data x-on:click="$wire.copyRibDetail('{{ $account->rib->swift }}', '{{ __('common.swift') }}')" title="{{ __('common.copy_swift') }}" class="ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                                                    {{ __('common.bank_name') }}
                                                </label>
                                                <div class="flex items-center bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded border">
                                                    <div class="text-sm text-black dark:text-white flex-1">
                                                        {{ $account->rib->bank_name }}
                                                    </div>
                                                    <button x-data x-on:click="$wire.copyRibDetail('{{ $account->rib->bank_name }}', '{{ __('common.bank_name') }}')" title="{{ __('common.copy_bank_name') }}" class="ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('common.rib_created_at') }}: {{ $account->rib->created_at->format('d/m/Y H:i') }}
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
</div>
<div>

    <!-- Filtres et recherche -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Recherche -->
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-search mr-1"></i>{{ __('admin.search_placeholder') }}
                </label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       id="search"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                       placeholder="{{ __('admin.search_placeholder') }}">
            </div>

            <!-- Filtre par statut -->
            <div>
                <label for="statusFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-filter mr-1"></i>{{ __('common.status') }}
                </label>
                <select wire:model.live="statusFilter" 
                        id="statusFilter"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="all">{{ __('admin.all_statuses') }}</option>
                    <option value="active">{{ __('common.active') }}</option>
                    <option value="inactive">{{ __('common.inactive') }}</option>
                    <option value="suspended">{{ __('common.suspended') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Liste des utilisateurs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-brand-primary to-brand-accent p-6">
            <h2 class="text-xl font-semibold text-white mb-2">
                <i class="fas fa-users mr-2"></i>{{ __('admin.user_management') }}
            </h2>
            <div class="w-16 h-1 bg-gray-200 dark:bg-gray-300 rounded-full"></div>
        </div>
        <div class="p-6">
        @if($users->count() > 0)
            <!-- Informations des résultats -->
            <div class="mb-4 flex items-center justify-between">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    {{ __('admin.showing_results', ['first' => $users->firstItem() ?? 0, 'last' => $users->lastItem() ?? 0, 'total' => $users->total()]) }}
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-700 dark:text-gray-300">{{ __('admin.per_page') }}</label>
                    <select wire:model.live="perPage" 
                            class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <!-- Table responsive -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" 
                                wire:click="sortBy('name')">
                                <div class="flex items-center space-x-1">
                                    <span>{{ __('admin.name') }}</span>
                                    @if($sortField === 'name')
                                        <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fa-solid fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" 
                                wire:click="sortBy('email')">
                                <div class="flex items-center space-x-1">
                                    <span>{{ __('admin.email') }}</span>
                                    @if($sortField === 'email')
                                        <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fa-solid fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.account_number') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('admin.accounts') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" 
                                wire:click="sortBy('created_at')">
                                <div class="flex items-center space-x-1">
                                    <span>{{ __('admin.registration') }}</span>
                                    @if($sortField === 'created_at')
                                        <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fa-solid fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    @if($user->accounts->count() > 0)
                                        <div class="flex items-center space-x-3">
                                            <div class="w-2 h-2 bg-brand-accent rounded-full"></div>
                                            <span data-account-number="{{ $user->accounts->first()->account_number }}" class="font-mono">{{ $user->accounts->first()->account_number }}</span>
                                            <button x-data="{ copied: false }" 
                                                    x-on:click="navigator.clipboard.writeText('{{ $user->accounts->first()->account_number }}').then(() => { copied = true; setTimeout(() => copied = false, 2000); })"
                                                    title="{{ __('common.copy_account_number') }}"
                                                    class="text-brand-secondary hover:text-brand-primary dark:text-gray-400 dark:hover:text-brand-accent transition-colors duration-200">
                                                <i class="fa-solid fa-copy w-4 h-4" x-show="!copied"></i>
                                                <i class="fa-solid fa-check w-4 h-4 text-green-500" x-show="copied" x-cloak></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500 text-xs">{{ __('admin.no_accounts') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($user->accounts->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($user->accounts as $account)
                                                @if($account->status === 'ACTIVE')
                                                    <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-brand-success/10 text-brand-success border border-brand-success/20">
                                                        <i class="fa-solid fa-check-circle w-3 h-3"></i>
                                                        <span>{{ __('common.active') }}</span>
                                                    </span>
                                                @elseif($account->status === 'INACTIVE')
                                                    <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-brand-warning/10 text-brand-warning border border-brand-warning/20">
                                                        <i class="fa-solid fa-clock w-3 h-3"></i>
                                                        <span>{{ __('common.inactive') }}</span>
                                                    </span>
                                                @elseif($account->status === 'SUSPENDED')
                                                    <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-brand-error/10 text-brand-error border border-brand-error/20">
                                                        <i class="fa-solid fa-pause-circle w-3 h-3"></i>
                                                        <span>{{ __('common.suspended') }}</span>
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-600">
                                                        <i class="fa-solid fa-question-circle w-3 h-3"></i>
                                                        <span>{{ $account->status }}</span>
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500 text-xs">{{ __('admin.no_accounts') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('users.manage', [app()->getLocale(), $user->id]) }}" 
                           title="{{ __('admin.manage_user') }}" 
                           class="p-2 rounded-lg bg-blue-600/10 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-200 hover:scale-105">
                            <i class="fa-solid fa-cog w-4 h-4"></i>
                        </a>
                                        @php
                                            $hasActiveAccounts = $user->accounts->where('status', 'ACTIVE')->count() > 0;
                                            $hasSuspendedAccounts = $user->accounts->where('status', 'SUSPENDED')->count() > 0;
                                            $allAccountsActive = $user->accounts->count() > 0 && $user->accounts->where('status', 'ACTIVE')->count() === $user->accounts->count();
                                            $allAccountsSuspended = $user->accounts->count() > 0 && $user->accounts->where('status', 'SUSPENDED')->count() === $user->accounts->count();
                                        @endphp
                                        <button wire:click="activateUser({{ $user->id }})" 
                                                wire:confirm="{{ __('messages.confirm_activate_user') }}"
                                                title="{{ __('common.activate') }}" 
                                                class="p-2 rounded-lg bg-green-600/10 text-green-600 hover:bg-green-600 hover:text-white transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed" 
                                                @if($allAccountsActive) disabled @endif>
                                            <i class="fa-solid fa-check-circle w-4 h-4"></i>
                                        </button>
                                        <button wire:click="suspendUser({{ $user->id }})" 
                                                title="{{ __('common.suspend') }}" 
                                                class="p-2 rounded-lg bg-yellow-600/10 text-yellow-600 hover:bg-yellow-600 hover:text-white transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                                @if($allAccountsSuspended) disabled @endif>
                                            <i class="fa-solid fa-pause-circle w-4 h-4"></i>
                                        </button>
                                        <button wire:click="deleteUser({{ $user->id }})" 
                                                wire:confirm="{{ __('messages.confirm_delete_user') }}"
                                                title="{{ __('common.delete') }}" 
                                                class="p-2 rounded-lg bg-red-600/10 text-red-600 hover:bg-red-600 hover:text-white transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <i class="fa-solid fa-trash-can w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 pt-4 border-t border-brand-secondary/20 dark:border-brand-secondary/30">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Suspension Modal -->
    @if($showSuspensionModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $suspensionUserId === 'bulk' ? __('admin.suspend_selected_users') : __('admin.suspend_user') }}
                        </h3>
                        <button wire:click="cancelSuspension" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled" wire:target="cancelSuspension">
                            <span wire:loading.remove wire:target="cancelSuspension">
                                <i class="fa-solid fa-times"></i>
                            </span>
                            <span wire:loading wire:target="cancelSuspension">
                                <i class="fa-solid fa-spinner fa-spin text-gray-800 dark:text-white"></i>
                            </span>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.suspension_reason') }}</label>
                            <textarea wire:model="suspensionReason" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100 @error('suspensionReason') border-red-500 @enderror" 
                                      rows="3" 
                                      placeholder="{{ __('admin.enter_suspension_reason') }}"></textarea>
                            @error('suspensionReason')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.suspension_instructions') }}</label>
                            <textarea wire:model="suspensionInstructions" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100 @error('suspensionInstructions') border-red-500 @enderror" 
                                      rows="3" 
                                      placeholder="{{ __('admin.enter_suspension_instructions') }}"></textarea>
                            @error('suspensionInstructions')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button wire:click="cancelSuspension" 
                                class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 bg-gray-100 dark:bg-gray-700 rounded-lg transition-colors">
                            {{ __('common.cancel') }}
                        </button>
                        <button wire:click="confirmSuspension" 
                                class="px-4 py-2 bg-brand-warning text-white rounded-lg hover:bg-brand-warning/90 dark:bg-brand-warning dark:hover:bg-brand-warning/80 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="confirmSuspension">
                            <span wire:loading.remove wire:target="confirmSuspension">{{ __('admin.confirm_suspension') }}</span>
                            <span wire:loading wire:target="confirmSuspension">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('admin.suspending') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal RIB Manuel -->
    @if($showRibModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-university mr-2 text-brand-primary"></i>
                            {{ __('admin.manual_rib_entry') }}
                        </h3>
                        <button wire:click="closeRibModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        {{ __('admin.manual_rib_description') }}
                    </p>
                    
                    <div class="space-y-4">
                        <!-- IBAN -->
                        <div>
                            <label for="ribIban" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('auth.iban_label') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="ribIban" 
                                   id="ribIban"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                   placeholder="{{ __('admin.iban_placeholder') }}">
                            @error('ribIban')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- SWIFT -->
                        <div>
                            <label for="ribSwift" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('auth.swift_label') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="ribSwift" 
                                   id="ribSwift"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                   placeholder="{{ __('admin.swift_placeholder') }}">
                            @error('ribSwift')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Bank Name -->
                        <div>
                            <label for="ribBankName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('auth.bank_name_label') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="ribBankName" 
                                   id="ribBankName"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                   placeholder="{{ __('admin.bank_name_placeholder') }}">
                            @error('ribBankName')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button wire:click="closeRibModal" 
                                class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 bg-gray-100 dark:bg-gray-700 rounded-lg transition-colors">
                            {{ __('common.cancel') }}
                        </button>
                        <button wire:click="processActivateUserWithManualRib" 
                                class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="processActivateUserWithManualRib">
                            <span wire:loading.remove wire:target="processActivateUserWithManualRib">
                                <i class="fas fa-check mr-2"></i>{{ __('admin.activate_with_rib') }}
                            </span>
                            <span wire:loading wire:target="processActivateUserWithManualRib">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('admin.activating') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
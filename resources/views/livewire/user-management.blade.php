<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
    <!-- Header -->
    <div class="bg-gradient-to-r from-brand-primary to-brand-accent p-6">
        <h2 class="text-xl font-semibold dark:text-white mb-2">{{ __('admin.user_management') }}</h2>
        <div class="w-16 h-1 bg-gray-200 dark:bg-gray-300 rounded-full"></div>
    </div>

    <div class="p-6">

        <!-- Search and Filters -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <input wire:model.live.debounce.300ms="search" type="text" 
                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary" 
                       placeholder="{{ __('admin.search_placeholder') }}">
            </div>

            <!-- Status Filter -->
            <div>
                <select wire:model.live="statusFilter" 
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary">
                    <option value="all">{{ __('admin.all_statuses') }}</option>
                    <option value="active">{{ __('common.active') }}</option>
                    <option value="inactive">{{ __('common.inactive') }}</option>
                    <option value="suspended">{{ __('common.suspended') }}</option>
                </select>
            </div>
        </div>

        <!-- Bulk Actions -->
        @if(count($selectedUsers) > 0)
            <div class="mb-4 p-4 rounded-lg border bg-brand-primary/10 border-brand-primary/30">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-brand-primary">
                        {{ __('admin.selected_users', ['count' => count($selectedUsers)]) }}
                    </span>
                    <div class="flex space-x-2">
                        <button wire:click="bulkActivate" 
                                wire:confirm="{{ __('messages.confirm_bulk_activate_users', ['count' => count($selectedUsers)]) }}"
                                class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="bulkActivate">
                            <span wire:loading.remove wire:target="bulkActivate">
                                <i class="fa-solid fa-check-circle mr-1"></i> {{ __('common.activate') }}
                            </span>
                            <span wire:loading wire:target="bulkActivate">
                                <i class="fa-solid fa-spinner fa-spin mr-1"></i> {{ __('common.activate') }}
                            </span>
                        </button>
                        <button wire:click="bulkSuspend" 
                                class="px-3 py-1 bg-yellow-600 text-white text-sm rounded hover:bg-yellow-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="bulkSuspend">
                            <span wire:loading.remove wire:target="bulkSuspend">
                                <i class="fa-solid fa-pause-circle mr-1"></i> {{ __('common.suspend') }}
                            </span>
                            <span wire:loading wire:target="bulkSuspend">
                                <i class="fa-solid fa-spinner fa-spin mr-1"></i> {{ __('common.suspend') }}
                            </span>
                        </button>
                        <button wire:click="bulkDelete" 
                                wire:confirm="{{ __('messages.confirm_bulk_delete_users', ['count' => count($selectedUsers)]) }}"
                                class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="bulkDelete">
                            <span wire:loading.remove wire:target="bulkDelete">
                                <i class="fa-solid fa-trash-can mr-1"></i> {{ __('common.delete') }}
                            </span>
                            <span wire:loading wire:target="bulkDelete">
                                <i class="fa-solid fa-spinner fa-spin mr-1"></i> {{ __('common.delete') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Results Info -->
        <div class="mb-4 flex items-center justify-between">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                {{ __('admin.showing_results', ['first' => $users->firstItem() ?? 0, 'last' => $users->lastItem() ?? 0, 'total' => $users->total()]) }}
            </div>
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-700 dark:text-gray-300">{{ __('admin.per_page') }}</label>
                <select wire:model.live="perPage" 
                        class="px-2 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        @if ($users->isEmpty())
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 bg-brand-secondary/10 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-users text-2xl text-brand-secondary"></i>
                </div>
                <p class="text-brand-secondary font-medium">{{ __('admin.no_users_found') }}</p>
            </div>
        @else
            <!-- Users Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th scope="col" class="px-6 py-4 text-left">
                                <input type="checkbox" wire:model.live="selectAll" 
                                       class="rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider cursor-pointer" 
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
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider cursor-pointer" 
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
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider">{{ __('common.account_number') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider">{{ __('admin.accounts') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider cursor-pointer" 
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
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->id !== auth()->id())
                                        <input type="checkbox" wire:model.live="selectedUsers" value="{{ $user->id }}" 
                                               class="rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 bg-brand-accent rounded-full"></div>
                                        <span>{{ $user->name }}</span>
                                        @if($user->id === auth()->id())
                                            <span class="text-xs px-2 py-1 rounded-full bg-brand-primary/20 text-brand-primary">{{ __('admin.you') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $user->email }}</td>
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
                                        <span class="text-gray-400 text-xs">{{ __('admin.no_accounts') }}</span>
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
                                                    <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                                        <i class="fa-solid fa-question-circle w-3 h-3"></i>
                                                        <span>{{ $account->status }}</span>
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">{{ __('admin.no_accounts') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <button wire:click="manageUser({{ $user->id }})" 
                                                title="{{ __('admin.manage_user') }}" 
                                                class="p-2 rounded-lg bg-brand-primary/10 text-brand-primary hover:bg-brand-primary hover:text-white transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                                wire:loading.attr="disabled" 
                                                wire:target="manageUser({{ $user->id }})">
                                            <div wire:loading.remove wire:target="manageUser({{ $user->id }})">
                                                <i class="fa-solid fa-cog w-4 h-4"></i>
                                            </div>
                                            <div wire:loading wire:target="manageUser({{ $user->id }})" class="animate-spin">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </button>
                                        @php
                                            $hasActiveAccounts = $user->accounts->where('status', 'ACTIVE')->count() > 0;
                                            $hasSuspendedAccounts = $user->accounts->where('status', 'SUSPENDED')->count() > 0;
                                            $allAccountsActive = $user->accounts->count() > 0 && $user->accounts->where('status', 'ACTIVE')->count() === $user->accounts->count();
                                            $allAccountsSuspended = $user->accounts->count() > 0 && $user->accounts->where('status', 'SUSPENDED')->count() === $user->accounts->count();
                                        @endphp
                                        <button wire:click="activateUser({{ $user->id }})" 
                                                wire:confirm="{{ __('messages.confirm_activate_user') }}"
                                                title="{{ __('common.activate') }}" 
                                                class="p-2 rounded-lg bg-brand-success/10 text-brand-success hover:bg-brand-success hover:text-white transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed" 
                                                wire:loading.attr="disabled" 
                                                wire:target="activateUser({{ $user->id }})"
                                                @if($allAccountsActive) disabled @endif>
                                            <div wire:loading.remove wire:target="activateUser({{ $user->id }})">
                                                <i class="fa-solid fa-check-circle w-4 h-4"></i>
                                            </div>
                                            <div wire:loading wire:target="activateUser({{ $user->id }})" class="animate-spin">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </button>
                                        <button wire:click="suspendUser({{ $user->id }})" 
                                                title="{{ __('common.suspend') }}" 
                                                class="p-2 rounded-lg bg-brand-warning/10 text-brand-warning hover:bg-brand-warning hover:text-white transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                                wire:loading.attr="disabled" 
                                                wire:target="suspendUser({{ $user->id }})"
                                                @if($allAccountsSuspended) disabled @endif>
                                            <div wire:loading.remove wire:target="suspendUser({{ $user->id }})">
                                                <i class="fa-solid fa-pause-circle w-4 h-4"></i>
                                            </div>
                                            <div wire:loading wire:target="suspendUser({{ $user->id }})" class="animate-spin">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </button>
                                        <button wire:click="deleteUser({{ $user->id }})" 
                                                wire:confirm="{{ __('messages.confirm_delete_user') }}"
                                                title="{{ __('common.delete') }}" 
                                                class="p-2 rounded-lg bg-brand-error/10 text-brand-error hover:bg-brand-error hover:text-white transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                                wire:loading.attr="disabled" 
                                                wire:target="deleteUser({{ $user->id }})">
                                            <div wire:loading.remove wire:target="deleteUser({{ $user->id }})">
                                                <i class="fa-solid fa-trash-can w-4 h-4"></i>
                                            </div>
                                            <div wire:loading wire:target="deleteUser({{ $user->id }})" class="animate-spin">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
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
                        <button wire:click="cancelSuspension" class="text-gray-400 hover:text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled" wire:target="cancelSuspension">
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
                                class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                            {{ __('common.cancel') }}
                        </button>
                        <button wire:click="confirmSuspension" 
                                class="px-4 py-2 bg-brand-warning text-white rounded-lg hover:bg-brand-warning/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
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
</div>
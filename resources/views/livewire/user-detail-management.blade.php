<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
    <!-- Header -->
    <div class="bg-gradient-to-r from-brand-primary to-brand-accent p-6">
        <h2 class="text-xl font-semibold dark:text-white mb-2">{{ __('admin.user_details') }}</h2>
        <div class="w-16 h-1 bg-gray-200 dark:bg-gray-300 rounded-full"></div>
    </div>

    <div class="p-6">

        @if($user)
            <!-- User Profile Section -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                <!-- Profile Photo & Basic Info -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-center">
                        <div class="w-24 h-24 mx-auto mb-4 bg-brand-primary/10 rounded-full flex items-center justify-center">
                            @if($user->profile_photo_url)
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover">
                            @else
                                <i class="fa-solid fa-user text-3xl text-brand-primary"></i>
                            @endif
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $user->email }}</p>
                        
                        <!-- Status Badge -->
                        @if($user->accounts->count() > 0)
                            @php
                                $primaryAccount = $user->accounts->first();
                                $status = $primaryAccount->status ?? 'INACTIVE';
                            @endphp
                            @if($status === 'ACTIVE')
                                <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-brand-success/10 text-brand-success border border-brand-success/20">
                                    <i class="fa-solid fa-check-circle w-3 h-3"></i>
                                    <span>{{ __('common.active') }}</span>
                                </span>
                            @elseif($status === 'SUSPENDED')
                                <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-brand-error/10 text-brand-error border border-brand-error/20">
                                    <i class="fa-solid fa-pause-circle w-3 h-3"></i>
                                    <span>{{ __('common.suspended') }}</span>
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-brand-warning/10 text-brand-warning border border-brand-warning/20">
                                    <i class="fa-solid fa-clock w-3 h-3"></i>
                                    <span>{{ __('common.inactive') }}</span>
                                </span>
                            @endif
                        @else
                            <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                <i class="fa-solid fa-user-slash w-3 h-3"></i>
                                <span>{{ __('admin.no_accounts') }}</span>
                            </span>
                        @endif
                        
                        @if($user->is_admin)
                            <div class="mt-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-brand-primary/20 text-brand-primary">
                                    <i class="fa-solid fa-crown mr-1"></i>{{ __('admin.administrator') }}
                                </span>
                            </div>
                        @endif
                        
                        <!-- Action Buttons -->
                        @if(!$user->is_admin)
                            <div class="mt-4 grid grid-cols-3 gap-2">
                                @php
                                    $hasActiveAccounts = $user->accounts->where('status', 'ACTIVE')->count() > 0;
                                    $hasSuspendedAccounts = $user->accounts->where('status', 'SUSPENDED')->count() > 0;
                                    $allAccountsActive = $user->accounts->count() > 0 && $user->accounts->where('status', 'ACTIVE')->count() === $user->accounts->count();
                                    $allAccountsSuspended = $user->accounts->count() > 0 && $user->accounts->where('status', 'SUSPENDED')->count() === $user->accounts->count();
                                @endphp
                                
                                <button wire:click="activateUser" 
                                        wire:confirm="{{ __('messages.confirm_activate_user') }}"
                                        class="px-2 py-2 bg-brand-success/10 text-brand-success hover:bg-brand-success hover:text-white transition-all duration-200 rounded-lg text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed" 
                                        wire:loading.attr="disabled" 
                                        wire:target="activateUser"
                                        @if($allAccountsActive) disabled @endif>
                                    <span wire:loading.remove wire:target="activateUser">
                                        <i class="fa-solid fa-check-circle mr-1"></i>{{ __('common.activate') }}
                                    </span>
                                    <span wire:loading wire:target="activateUser">
                                        <i class="fa-solid fa-spinner fa-spin mr-1"></i>{{ __('admin.activating') }}
                                    </span>
                                </button>
                                
                                <button wire:click="suspendUser" 
                                        class="px-2 py-2 bg-brand-warning/10 text-brand-warning hover:bg-brand-warning hover:text-white transition-all duration-200 rounded-lg text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                        wire:loading.attr="disabled" 
                                        wire:target="suspendUser"
                                        @if($allAccountsSuspended) disabled @endif>
                                    <span wire:loading.remove wire:target="suspendUser">
                                        <i class="fa-solid fa-pause-circle mr-1"></i>{{ __('common.suspend') }}
                                    </span>
                                    <span wire:loading wire:target="suspendUser">
                                        <i class="fa-solid fa-spinner fa-spin mr-1"></i>{{ __('admin.suspending') }}
                                    </span>
                                </button>
                                
                                <button wire:click="deleteUser" 
                                        wire:confirm="{{ __('messages.confirm_delete_user') }}"
                                        class="px-2 py-2 bg-brand-error/10 text-brand-error hover:bg-brand-error hover:text-white transition-all duration-200 rounded-lg text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                        wire:loading.attr="disabled" 
                                        wire:target="deleteUser">
                                    <span wire:loading.remove wire:target="deleteUser">
                                        <i class="fa-solid fa-trash-can mr-1"></i>{{ __('common.delete') }}
                                    </span>
                                    <span wire:loading wire:target="deleteUser">
                                        <i class="fa-solid fa-spinner fa-spin mr-1"></i>{{ __('admin.deleting') }}
                                    </span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="lg:col-span-3">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fa-solid fa-user mr-2 text-brand-primary"></i>{{ __('admin.personal_information') }}
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.first_name') }}</label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border">{{ $user->first_name ?? __('common.not_specified') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.last_name') }}</label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border">{{ $user->last_name ?? __('common.not_specified') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.gender') }}</label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border">{{ $user->gender ?? __('common.not_specified') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.birth_date') }}</label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border">{{ $user->birth_date ?? __('common.not_specified') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.marital_status') }}</label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border">{{ $user->marital_status ?? __('common.not_specified') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.profession') }}</label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border">{{ $user->profession ?? __('common.not_specified') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fa-solid fa-phone mr-2 text-brand-primary"></i>{{ __('admin.contact_information') }}
                    </h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.email') }}</label>
                            <div class="flex items-center space-x-2">
                                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border flex-1">{{ $user->email }}</p>
                                @if($user->email_verified_at)
                                    <span class="text-brand-success" title="{{ __('admin.email_verified') }}">
                                        <i class="fa-solid fa-check-circle"></i>
                                    </span>
                                @else
                                    <span class="text-brand-error" title="{{ __('admin.email_not_verified') }}">
                                        <i class="fa-solid fa-exclamation-circle"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.phone_number') }}</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border">{{ $user->phone_number ?? __('common.not_specified') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fa-solid fa-map-marker-alt mr-2 text-brand-primary"></i>{{ __('admin.address_information') }}
                    </h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.country') }}</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border">{{ $user->country->name ?? __('common.not_specified') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.region') }}</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border">{{ $user->region ?? __('common.not_specified') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.city') }}</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border">{{ $user->city ?? __('common.not_specified') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.postal_code') }}</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border">{{ $user->postal_code ?? __('common.not_specified') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.address') }}</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border">{{ $user->address ?? __('common.not_specified') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    <i class="fa-solid fa-file-alt mr-2 text-brand-primary"></i>{{ __('admin.documents') }}
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.identity_document') }}</label>
                        @if($user->identity_document_url)
                            <div class="flex items-center space-x-2">
                                <span class="text-brand-success"><i class="fa-solid fa-check-circle"></i></span>
                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ __('admin.document_uploaded') }}</span>
                                <a href="{{ Storage::url($user->identity_document_url) }}" target="_blank" class="text-brand-primary hover:text-brand-primary/80">
                                    <i class="fa-solid fa-external-link-alt"></i>
                                </a>
                            </div>
                        @else
                            <div class="flex items-center space-x-2">
                                <span class="text-brand-error"><i class="fa-solid fa-times-circle"></i></span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('admin.no_document') }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.address_document') }}</label>
                        @if($user->address_document_url)
                            <div class="flex items-center space-x-2">
                                <span class="text-brand-success"><i class="fa-solid fa-check-circle"></i></span>
                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ __('admin.document_uploaded') }}</span>
                                <a href="{{ Storage::url($user->address_document_url) }}" target="_blank" class="text-brand-primary hover:text-brand-primary/80">
                                    <i class="fa-solid fa-external-link-alt"></i>
                                </a>
                            </div>
                        @else
                            <div class="flex items-center space-x-2">
                                <span class="text-brand-error"><i class="fa-solid fa-times-circle"></i></span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('admin.no_document') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Accounts Section -->
            @if($user->accounts->count() > 0)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fa-solid fa-university mr-2 text-brand-primary"></i>{{ __('admin.accounts') }} ({{ $user->accounts->count() }})
                    </h4>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider">{{ __('common.account_number') }}</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider">{{ __('common.status') }}</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider">{{ __('admin.balance') }}</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider">{{ __('admin.created_at') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-600">
                                @foreach($user->accounts as $account)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-2 h-2 bg-brand-accent rounded-full"></div>
                                                <span class="font-mono">{{ $account->account_number }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            @if($account->status === 'ACTIVE')
                                                <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-brand-success/10 text-brand-success border border-brand-success/20">
                                                    <i class="fa-solid fa-check-circle w-3 h-3"></i>
                                                    <span>{{ __('common.active') }}</span>
                                                </span>
                                            @elseif($account->status === 'SUSPENDED')
                                                <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-brand-error/10 text-brand-error border border-brand-error/20">
                                                    <i class="fa-solid fa-pause-circle w-3 h-3"></i>
                                                    <span>{{ __('common.suspended') }}</span>
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-brand-warning/10 text-brand-warning border border-brand-warning/20">
                                                    <i class="fa-solid fa-clock w-3 h-3"></i>
                                                    <span>{{ __('common.inactive') }}</span>
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-mono">
                                            {{ number_format($account->balance ?? 0, 2) }} €
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $account->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- RIB Section -->
            @if($user->accounts->count() > 0)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            <i class="fa-solid fa-credit-card mr-2 text-brand-primary"></i>{{ __('admin.rib_information') }}
                        </h4>
                    </div>
                    
                    @php
                        $ribs = collect();
                        foreach($user->accounts as $account) {
                            if($account->rib) {
                                $ribs->push($account->rib);
                            }
                        }
                    @endphp
                    
                    @if($ribs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider">{{ __('admin.account_number') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider">{{ __('admin.iban') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider">{{ __('admin.swift') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-brand-primary dark:text-brand-accent uppercase tracking-wider">{{ __('admin.bank_name') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-600">
                                    @foreach($ribs as $rib)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <span class="font-mono">{{ $rib->account->account_number }}</span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-mono">
                                                {{ $rib->iban ?? __('common.not_specified') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $rib->swift ?? __('common.not_specified') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $rib->bank_name ?? __('common.not_specified') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 mx-auto mb-4 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-credit-card text-xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400">{{ __('admin.no_rib_found') }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Card Requests Section -->
            @php
                $cardRequests = $user->accounts->flatMap(function($account) {
                    return $account->cardRequests()->with(['account', 'processedBy'])->get();
                });
            @endphp
            @if($cardRequests->count() > 0)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                    <div class="flex items-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            <i class="fa-solid fa-clock mr-2 text-brand-primary"></i>{{ __('common.card_requests') }} ({{ $cardRequests->count() }})
                        </h4>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($cardRequests as $request)
                            <div class="bg-white dark:bg-gray-600 rounded-lg p-4 border border-gray-200 dark:border-gray-500">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-brand-primary/10 rounded-full flex items-center justify-center">
                                                <i class="fa-solid fa-credit-card text-brand-primary"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $request->card_type }}</h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $request->phone_number }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">{{ $request->created_at->format('d/m/Y H:i') }}</p>
                                                @if($request->processedBy)
                                                    <p class="text-xs text-gray-500 dark:text-gray-500">{{ __('admin.processed_by') }}: {{ $request->processedBy->name }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if($request->message)
                                            <div class="mt-2 ml-14">
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $request->message }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-right flex items-center space-x-2">
                                        @if($request->status === 'PENDING')
                                            <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                <i class="fa-solid fa-clock w-3 h-3"></i>
                                                <span>{{ __('common.pending') }}</span>
                                            </span>
                                            <button wire:click="deleteCardRequest({{ $request->id }})" 
                                                    wire:confirm="{{ __('messages.confirm_delete_card_request') }}"
                                                    class="px-2 py-1 bg-red-100 hover:bg-red-200 text-red-800 rounded-md transition-colors duration-200 text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                                    wire:loading.attr="disabled" 
                                                    wire:target="deleteCardRequest">
                                                <span wire:loading.remove wire:target="deleteCardRequest">
                                                    <i class="fa-solid fa-trash mr-1"></i>{{ __('common.delete') }}
                                                </span>
                                                <span wire:loading wire:target="deleteCardRequest">
                                                     <i class="fa-solid fa-spinner fa-spin mr-1"></i>{{ __('admin.deleting') }}
                                                 </span>
                                            </button>
                                        @elseif($request->status === 'APPROVED')
                                            <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                <i class="fa-solid fa-check-circle w-3 h-3"></i>
                                                <span>{{ __('common.approved') }}</span>
                                            </span>
                                        @else
                                            <span class="px-3 py-1 inline-flex items-center space-x-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                <i class="fa-solid fa-times-circle w-3 h-3"></i>
                                                <span>{{ __('common.rejected') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Cards Section -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fa-solid fa-credit-card mr-2 text-brand-primary"></i>{{ __('admin.cards') }} ({{ $user->cards->count() }})
                    </h4>
                    <button wire:click="openAddCardModal" 
                            class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors duration-200 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled"
                            wire:target="openAddCardModal"
                            @if(!$hasActiveAccounts) disabled title="{{ __('admin.account_must_be_active') }}" @endif>
                        <span wire:loading.remove wire:target="openAddCardModal">
                            <i class="fa-solid fa-plus mr-2"></i>{{ __('admin.add_card') }}
                        </span>
                        <span wire:loading wire:target="openAddCardModal">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('admin.add_card') }}
                        </span>
                    </button>
                </div>
                
                @if($user->cards->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($user->cards as $card)
                            <x-financial-card 
                                :item="$card" 
                                type="card"
                                :show-details="isset($showCardDetails[$card->id])" 
                                :admin-view="true" 
                            />
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 mx-auto mb-4 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-credit-card text-xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">{{ __('admin.no_cards_found') }}</p>
                        <button wire:click="openAddCardModal"
                                class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors duration-200 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled"
                                wire:target="openAddCardModal"
                                @if(!$hasActiveAccounts) disabled title="{{ __('admin.account_must_be_active') }}" @endif>
                            <span wire:loading.remove wire:target="openAddCardModal">
                                <i class="fa-solid fa-plus mr-2"></i>{{ __('admin.add_first_card') }}
                            </span>
                            <span wire:loading wire:target="openAddCardModal">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('admin.add_first_card') }}
                            </span>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Wallets Section -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fa-solid fa-wallet mr-2 text-brand-primary"></i>{{ __('admin.wallets') }} ({{ $user->wallets->count() }})
                    </h4>
                    <button wire:click="openAddWalletModal" 
                            class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors duration-200 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled"
                            wire:target="openAddWalletModal"
                            @if(!$hasActiveAccounts) disabled title="{{ __('admin.account_must_be_active') }}" @endif>
                        <span wire:loading.remove wire:target="openAddWalletModal">
                            <i class="fa-solid fa-plus mr-2"></i>{{ __('admin.add_wallet') }}
                        </span>
                        <span wire:loading wire:target="openAddWalletModal">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('admin.add_wallet') }}
                        </span>
                    </button>
                </div>
                
                @if($user->wallets->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($user->wallets as $wallet)
                            <x-financial-card 
                                :item="$wallet" 
                                type="wallet"
                                :show-details="isset($showWalletDetails[$wallet->id])" 
                                :admin-view="true" 
                            />
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 mx-auto mb-4 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-wallet text-xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">{{ __('admin.no_wallets_found') }}</p>
                        <button wire:click="openAddWalletModal"
                                class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors duration-200 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled"
                                wire:target="openAddWalletModal"
                                @if(!$hasActiveAccounts) disabled title="{{ __('admin.account_must_be_active') }}" @endif>
                            <span wire:loading.remove wire:target="openAddWalletModal">
                                <i class="fa-solid fa-plus mr-2"></i>{{ __('admin.add_first_wallet') }}
                            </span>
                            <span wire:loading wire:target="openAddWalletModal">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('admin.add_first_wallet') }}
                            </span>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Registration Information -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    <i class="fa-solid fa-calendar mr-2 text-brand-primary"></i>{{ __('admin.registration_information') }}
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.registration_date') }}</label>
                        <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.last_update') }}</label>
                        <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($user->email_verified_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.email_verified_at') }}</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 px-3 py-2 rounded border">{{ $user->email_verified_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif

                </div>
            </div>
            
            <!-- Transfer Groups Section -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fa-solid fa-exchange-alt mr-2 text-brand-primary"></i>{{ __('admin.transfer_groups') }}
                    </h4>
                    <button wire:click="openTransferGroupModal('account', null)"
                            class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors duration-200 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                            @if(!$hasActiveAccounts) disabled title="{{ __('admin.account_must_be_active') }}" @endif>
                        <i class="fa-solid fa-plus mr-2"></i>{{ __('admin.apply_transfer_group') }}
                    </button>
                </div>
                
                <!-- Accounts with Transfer Groups -->
                @if($user->accounts->count() > 0)
                    <div class="mb-6">
                        <h5 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">{{ __('admin.accounts_transfer_groups') }}</h5>
                        <div class="space-y-3">
                            @foreach($user->accounts as $account)
                                <div class="bg-white dark:bg-gray-600 rounded-lg p-4 border border-gray-200 dark:border-gray-500">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h6 class="font-medium text-gray-900 dark:text-gray-100">{{ $account->account_number }}</h6>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $account->type ?? __('common.standard') }} - {{ $account->currency ?? 'EUR' }}</p>
                                        </div>
                                        <div class="text-right">
                                            @if($account->transferStepGroups && $account->transferStepGroups->count() > 0)
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($account->transferStepGroups as $group)
                                                        <div class="flex items-center gap-1">
                                                            <span class="px-2 py-1 text-xs rounded-full bg-brand-primary/20 text-brand-primary">
                                                                {{ $group->name }}
                                                            </span>
                                                            <button 
                                                                wire:click="removeSpecificTransferGroup('account', {{ $account->id }}, {{ $group->id }})"
                                                                wire:confirm="{{ __('messages.confirm_remove_transfer_group') }}"
                                                                wire:loading.attr="disabled"
                                                                wire:target="removeSpecificTransferGroup"
                                                                class="text-red-500 hover:text-red-700 text-xs p-1 rounded transition-colors duration-200"
                                                                title="{{ __('admin.remove_transfer_group') }}"
                                                            >
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin.no_transfer_groups') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Wallets with Transfer Groups -->
                @if($user->wallets->count() > 0)
                    <div>
                        <h5 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">{{ __('admin.wallets_transfer_groups') }}</h5>
                        <div class="space-y-3">
                            @foreach($user->wallets as $wallet)
                                <div class="bg-white dark:bg-gray-600 rounded-lg p-4 border border-gray-200 dark:border-gray-500">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h6 class="font-medium text-gray-900 dark:text-gray-100">{{ strtoupper($wallet->coin) }} {{ __('admin.wallet') }}</h6>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 font-mono">{{ substr($wallet->address, 0, 10) }}...{{ substr($wallet->address, -10) }}</p>
                                        </div>
                                        <div class="text-right">
                                            @if($wallet->transferStepGroups && $wallet->transferStepGroups->count() > 0)
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($wallet->transferStepGroups as $group)
                                                        <div class="flex items-center gap-1">
                                                            <span class="px-2 py-1 text-xs rounded-full bg-brand-primary/20 text-brand-primary">
                                                                {{ $group->name }}
                                                            </span>
                                                            <button 
                                                                wire:click="removeSpecificTransferGroup('wallet', {{ $wallet->id }}, {{ $group->id }})"
                                                                wire:confirm="{{ __('messages.confirm_remove_transfer_group') }}"
                                                                wire:loading.attr="disabled"
                                                                wire:target="removeSpecificTransferGroup"
                                                                class="text-red-500 hover:text-red-700 text-xs p-1 rounded transition-colors duration-200"
                                                                title="{{ __('admin.remove_transfer_group') }}"
                                                            >
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin.no_transfer_groups') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 bg-brand-secondary/10 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-user-slash text-2xl text-brand-secondary"></i>
                </div>
                <p class="text-brand-secondary font-medium">{{ __('admin.user_not_found') }}</p>
            </div>
        @endif
    </div>
    
    <!-- Transfer Group Modal -->
    @if($showTransferGroupModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('admin.apply_transfer_group') }}</h3>
                        <button wire:click="closeTransferGroupModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Type Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.apply_to') }}</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" wire:model.live="transferGroupType" value="account" class="mr-2 text-brand-primary focus:ring-brand-primary">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('admin.account') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" wire:model.live="transferGroupType" value="wallet" class="mr-2 text-brand-primary focus:ring-brand-primary">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('admin.wallet') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Loading State -->
                        <div wire:loading wire:target="transferGroupType" class="flex items-center justify-center py-4">
                            <i class="fa-solid fa-spinner fa-spin text-brand-primary mr-2"></i>
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('admin.loading') }}...</span>
                        </div>

                        <!-- Account Selection -->
                        <div wire:loading.remove wire:target="transferGroupType">
                            @if($transferGroupType === 'account')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.select_account') }}</label>
                                    <select wire:model="selectedAccountId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                        <option value="">{{ __('admin.select_account') }}</option>
                                        @foreach($user->accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_number }} ({{ $account->type ?? __('common.standard') }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Wallet Selection -->
                            @if($transferGroupType === 'wallet')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.select_wallet') }}</label>
                                    <select wire:model="selectedWalletId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                        <option value="">{{ __('admin.select_wallet') }}</option>
                                        @foreach($user->wallets as $wallet)
                                            <option value="{{ $wallet->id }}">{{ strtoupper($wallet->coin) }} - {{ substr($wallet->address, 0, 10) }}...{{ substr($wallet->address, -6) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.select_transfer_group') }}</label>
                            <select wire:model="selectedTransferGroupId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                <option value="">{{ __('admin.select_transfer_group') }}</option>
                                @if(class_exists('\App\Models\TransferStepGroup'))
                                    @foreach(\App\Models\TransferStepGroup::where('is_active', true)->get() as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center mt-6">
                        <button wire:click="closeTransferGroupModal" 
                                class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                            {{ __('common.cancel') }}
                        </button>
                        
                        <button wire:click="applyTransferGroup" 
                                class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="applyTransferGroup">
                            <span wire:loading.remove wire:target="applyTransferGroup">{{ __('admin.apply') }}</span>
                            <span wire:loading wire:target="applyTransferGroup">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('admin.applying') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Suspension Modal -->
    @if($showSuspensionModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('admin.suspend_user') }}</h3>
                        <button wire:click="cancelSuspension" class="text-gray-400 hover:text-gray-600">
                            <i class="fa-solid fa-times"></i>
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

    <!-- Add Card Modal -->
    @if($showAddCardModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('admin.add_card') }}</h3>
                    <button wire:click="closeAddCardModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    @if($user->cardRequests()->pending()->count() > 0)
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                            <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-3">{{ __('admin.existing_card_requests') }}</h4>
                            <div class="space-y-2">
                                @foreach($user->cardRequests()->pending()->get() as $request)
                                    <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded border">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <input type="radio" 
                                                       wire:model="selectedCardRequest" 
                                                       value="{{ $request->id }}"
                                                       id="request_{{ $request->id }}"
                                                       class="text-brand-primary focus:ring-brand-primary">
                                                <label for="request_{{ $request->id }}" class="flex-1 cursor-pointer">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $request->card_type }} - {{ $request->phone_number }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ __('admin.requested_on') }}: {{ $request->created_at->format('d/m/Y H:i') }}
                                                    </div>
                                                    @if($request->message)
                                                        <div class="text-xs text-gray-600 dark:text-gray-300 mt-1">
                                                            {{ $request->message }}
                                                        </div>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3 text-xs text-blue-700 dark:text-blue-300">
                                {{ __('admin.select_request_to_process') }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                <i class="fa-solid fa-clock text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400">{{ __('admin.no_pending_card_requests') }}</p>
                        </div>
                    @endif
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button wire:click="closeAddCardModal" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                        {{ __('common.cancel') }}
                    </button>
                    <button wire:click="addCard" 
                            class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" 
                            wire:target="addCard">
                        <span wire:loading.remove wire:target="addCard">{{ __('admin.add_card') }}</span>
                        <span wire:loading wire:target="addCard">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('admin.adding') }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Add Wallet Modal -->
    @if($showAddWalletModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('admin.add_wallet') }}</h3>
                    <button wire:click="closeAddWalletModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.cryptocurrency') }}</label>
                        <select wire:model="selectedCryptocurrency" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                            <option value="">{{ __('admin.select_cryptocurrency') }}</option>
                            @foreach($cryptocurrencies as $symbol => $cryptos)
                                <optgroup label="{{ $symbol }}">
                                    @foreach($cryptos as $crypto)
                                        <option value="{{ $crypto->id }}">{{ $crypto->name }} ({{ $crypto->network }})</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Auto-generated Address Info -->
                    <div class="bg-brand-primary/10 dark:bg-brand-primary/20 p-4 rounded-lg border border-brand-primary/20 dark:border-brand-primary/30">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-brand-primary mr-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-2">
                                <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">Adresse générée automatiquement</p>
                                <p class="text-xs text-gray-700 dark:text-gray-200">Une adresse unique sera créée automatiquement selon le type de cryptomonnaie sélectionné</p>
                                @if($selectedCryptocurrency)
                                    @php
                                        $selectedCrypto = $cryptocurrencies->flatten()->firstWhere('id', $selectedCryptocurrency);
                                    @endphp
                                    @if($selectedCrypto)
                                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-2">
                                            <span class="font-medium">Format:</span> {{ $selectedCrypto->address_example }}
                                        </p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button wire:click="closeAddWalletModal" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                        {{ __('common.cancel') }}
                    </button>
                    <button wire:click="addWallet" 
                            class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" 
                            wire:target="addWallet">
                        <span wire:loading.remove wire:target="addWallet">{{ __('admin.add_wallet') }}</span>
                        <span wire:loading wire:target="addWallet">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('admin.adding') }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
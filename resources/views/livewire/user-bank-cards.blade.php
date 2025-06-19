<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

    <!-- Header -->
    <div class="bg-gradient-to-r from-brand-primary to-brand-accent p-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold dark:text-white mb-2">{{ __('common.bank_cards') }}</h2>
                <div class="w-16 h-1 bg-gray-200 dark:bg-gray-300 rounded-full"></div>
            </div>
            @if($cards->count() === 0)
                @if($this->hasInactiveAccounts())
                    <button disabled class="px-4 py-2 bg-gray-400 text-gray-600 rounded-lg cursor-not-allowed opacity-50">
                        <i class="fa-solid fa-ban mr-2"></i>{{ __('common.account_inactive') }}
                    </button>
                @else
                    <button wire:click="requestCard" wire:loading.attr="disabled" wire:target="requestCard" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-200 disabled:opacity-50">
                        <span wire:loading.remove wire:target="requestCard">
                            <i class="fa-solid fa-plus mr-2"></i>{{ __('common.request_card') }}
                        </span>
                        <span wire:loading wire:target="requestCard">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.loading') }}...
                        </span>
                    </button>
                @endif
            @endif
        </div>
    </div>

    <div class="p-6">
        @if($cards->count() > 0)
            @if($dashboardView)
                <!-- Dashboard View - Horizontal Cards -->
                <x-horizontal-card-list :cards="$cards" brand-color="brand-primary" />
            @else
                <!-- Full Page View - Card Design -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($cards as $card)
                        <x-financial-card 
                            :item="$card" 
                            type="card"
                            :show-details="isset($showCardDetails[$card->id])" 
                            :admin-view="false" 
                        />
                    @endforeach
                </div>
            @endif

            @if($cards->count() > 0)
                <!-- Request Additional Card Button -->
                <div class="mt-6 text-center">
                    @if($this->hasInactiveAccounts())
                         <button disabled class="inline-block px-6 py-3 bg-gray-400 text-gray-600 rounded-lg cursor-not-allowed opacity-50">
                             <i class="fa-solid fa-ban mr-2"></i>{{ __('common.account_inactive') }}
                         </button>
                     @else
                         <button wire:click="requestCard" wire:loading.attr="disabled" wire:target="requestCard" class="inline-block px-6 py-3 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-lg transition-all duration-200 disabled:opacity-50">
                             <span wire:loading.remove wire:target="requestCard">
                                 <i class="fa-solid fa-plus mr-2"></i>{{ __('common.request_additional_card') }}
                             </span>
                             <span wire:loading wire:target="requestCard">
                                 <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.loading') }}...
                             </span>
                         </button>
                     @endif
                </div>
            @endif


        @else
            <!-- No Cards State -->
            <div class="text-center py-12">
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-gray-100 dark:bg-gray-700 mb-6">
                    <i class="fa-solid fa-credit-card text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('common.no_cards_yet') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">{{ __('common.no_cards_description') }}</p>
                @if($this->hasInactiveAccounts())
                    <button disabled class="inline-block px-6 py-3 bg-gray-400 text-gray-600 rounded-lg cursor-not-allowed opacity-50">
                        <i class="fa-solid fa-ban mr-2"></i>{{ __('common.account_inactive') }}
                    </button>
                    <p class="text-red-600 dark:text-red-400 mt-3 text-sm">{{ __('common.account_inactive_message') }}</p>
                @else
                    <button wire:click="requestCard" wire:loading.attr="disabled" wire:target="requestCard" class="inline-block px-6 py-3 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-lg transition-all duration-200 disabled:opacity-50">
                        <span wire:loading.remove wire:target="requestCard">
                            <i class="fa-solid fa-plus mr-2"></i>{{ __('common.request_first_card') }}
                        </span>
                        <span wire:loading wire:target="requestCard">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.loading') }}...
                        </span>
                    </button>
                @endif
            </div>
        @endif

        <!-- Card Requests Section -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                <i class="fa-solid fa-clock mr-2 text-brand-primary"></i>{{ __('common.card_requests') }}
            </h3>
            @if($cardRequests->count() > 0)
                <div class="space-y-4">
                    @foreach($cardRequests->take($dashboardView ? 2 : $cardRequests->count()) as $request)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                        <i class="fa-solid fa-credit-card text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($request->card_type) }} {{ __('common.card') }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('common.requested_on') }} {{ $request->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                        @if($request->status === 'PENDING') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @elseif($request->status === 'APPROVED') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @endif">
                                        {{ __('common.' . $request->status_lower) }}
                                    </span>
                                    @if($request->status === 'PENDING')
                                        <button 
                                            wire:click="deleteCardRequest({{ $request->id }})"
                                            wire:confirm="{{ __('messages.confirm_delete_card_request') }}"
                                            wire:loading.attr="disabled"
                                            wire:target="deleteCardRequest({{ $request->id }})"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 disabled:opacity-50"
                                            title="{{ __('common.delete_request') }}">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if($dashboardView && $cardRequests->count() > 2)
                        <div class="text-center mt-4">
                            <a href="{{ route('user.cards', ['locale' => app()->getLocale()]) }}" class="text-brand-primary hover:text-brand-primary/80 text-sm font-medium">
                                {{ __('common.view_all_requests') }} ({{ $cardRequests->count() }})
                                <i class="fa-solid fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-8">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                        <i class="fa-solid fa-clock text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('common.no_card_requests') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Request Card Modal -->
    @if($showRequestCardModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" 
             wire:ignore.self
             x-data="{ show: @entangle('showRequestCardModal') }"
             x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4" wire:click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('common.request_new_card') }}</h3>
                    <button wire:click="closeModal()" class="text-gray-400 hover:text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled" wire:target="closeModal">
                        <span wire:loading.remove wire:target="closeModal">
                            <i class="fa-solid fa-times"></i>
                        </span>
                        <span wire:loading wire:target="closeModal">
                            <i class="fa-solid fa-spinner fa-spin text-gray-800 dark:text-white"></i>
                        </span>
                    </button>
                </div>
                    
                    <form wire:submit.prevent="submitCardRequest">
                        <div class="mb-4">
                            <label for="cardType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('common.card_type') }} *
                            </label>
                            <select 
                                wire:model="cardType" 
                                id="cardType" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-brand-primary focus:border-brand-primary dark:bg-gray-700 dark:text-gray-100"
                            >
                                <option value="">{{ __('common.select_card_type') }}</option>
                                <option value="VISA">VISA</option>
                                <option value="MASTERCARD">MASTERCARD</option>
                                <option value="AMERICAN_EXPRESS">AMERICAN EXPRESS</option>
                            </select>
                            @error('cardType') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="phoneNumber" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('common.phone_number') }} *
                            </label>
                            <input 
                                type="tel" 
                                wire:model="phoneNumber" 
                                id="phoneNumber" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-brand-primary focus:border-brand-primary dark:bg-gray-700 dark:text-gray-100"
                                placeholder="{{ __('common.phone_number_placeholder') }}"
                            >
                            @error('phoneNumber') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="requestMessage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('common.request_message') }}
                            </label>
                            <textarea 
                                wire:model="requestMessage" 
                                id="requestMessage" 
                                rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-brand-primary focus:border-brand-primary dark:bg-gray-700 dark:text-gray-100"
                                placeholder="{{ __('common.request_message_placeholder') }}"
                            ></textarea>
                            @error('requestMessage') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" wire:click="closeModal()" wire:loading.attr="disabled" wire:target="submitCardRequest" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md transition-colors duration-200 disabled:opacity-50">
                                {{ __('common.cancel') }}
                            </button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="submitCardRequest" class="px-4 py-2 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-md transition-colors duration-200 disabled:opacity-50">
                                <span wire:loading.remove wire:target="submitCardRequest">
                                    {{ __('common.send_request') }}
                                </span>
                                <span wire:loading wire:target="submitCardRequest">
                                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ __('common.sending') }}...
                                </span>
                            </button>
                        </div>
                    </form>
            </div>
        </div>
    @endif
</div>
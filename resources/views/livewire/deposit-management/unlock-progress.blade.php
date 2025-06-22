<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                {{ __('transfers.unlock_step') }}
            </h1>
            <p class="text-gray-600">
                {{ __('transfers.unlock_step_description') }}
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>{{ __('transfers.progress') }}</span>
                <span>{{ number_format($progressPercentage, 1) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-300" 
                     style="width: {{ $progressPercentage }}%"></div>
            </div>
        </div>

        <!-- Transaction Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                {{ __('transfers.transaction_details') }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">{{ __('transfers.transaction_id') }}</span>
                    <p class="font-medium text-gray-900">#{{ $transaction->id }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">{{ __('transfers.amount') }}</span>
                    <p class="font-medium text-gray-900">{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">{{ __('transfers.recipient') }}</span>
                    <p class="font-medium text-gray-900">{{ $transaction->recipient_name ?? $transaction->recipient_account_number }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">{{ __('transfers.status') }}</span>
                    <p class="font-medium text-orange-600">{{ __('transfers.blocked_pending_verification') }}</p>
                </div>
            </div>
        </div>

        @if($currentStep)
        <!-- Unlock Step Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-start space-x-4">
                <!-- Step Icon -->
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        @if($currentStep->type === 'document')
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        @elseif($currentStep->type === 'verification')
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @elseif($currentStep->type === 'confirmation')
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        @endif
                    </div>
                </div>

                <!-- Step Content -->
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        {{ $currentStep->title }}
                    </h3>
                    <p class="text-gray-600 mb-6">
                        {{ $currentStep->description }}
                    </p>

                    <!-- Code Input Form -->
                    <form wire:submit.prevent="submitCode" class="space-y-4">
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('transfers.enter_unlock_code') }}
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="code"
                                    wire:model="enteredCode"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('enteredCode') border-red-500 @enderror"
                                    placeholder="{{ __('transfers.enter_code_placeholder') }}"
                                    maxlength="20"
                                    {{ $isProcessing ? 'disabled' : '' }}
                                >
                                @if($isProcessing)
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            @if($codeError)
                                <p class="mt-2 text-sm text-red-600">{{ $codeError }}</p>
                            @endif
                        </div>

                        <div class="flex space-x-4">
                            <button 
                                type="submit"
                                class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-3 px-6 rounded-lg font-medium hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ $isProcessing ? 'disabled' : '' }}
                            >
                                @if($isProcessing)
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ __('transfers.processing') }}
                                @else
                                    {{ __('transfers.unlock_step_button') }}
                                @endif
                            </button>
                            
                            <a href="{{ route('transactions', ['locale' => app()->getLocale()]) }}"
                               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                                {{ __('common.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @else
        <!-- No Step Found -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                {{ __('transfers.no_step_to_unlock') }}
            </h3>
            <p class="text-gray-600 mb-6">
                {{ __('transfers.no_step_to_unlock_description') }}
            </p>
            <a href="{{ route('transactions', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                {{ __('transfers.back_to_transactions') }}
            </a>
        </div>
        @endif
    </div>
</div>
<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-700 text-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">
                        <i class="fa-solid fa-credit-card mr-2"></i>
                        {{ __('common.bank_cards') }}
                    </h1>
                </div>
                <div class="text-right">
                    <p class="text-blue-100 text-sm font-medium">
                        <i class="fa-solid fa-user mr-1"></i>
                        {{ __('common.user_space') }}
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(!Auth::user()->hasVerifiedEmail())
                <div class="max-w-3xl mx-auto bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-700 p-8 rounded-xl shadow-lg mb-8">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-800 mb-6">
                            <i class="fas fa-envelope-open-text text-2xl text-blue-600 dark:text-blue-300"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-blue-900 dark:text-blue-100 mb-4">{{ __('common.email_verification_required_title') }}</h2>
                        <p class="text-blue-800 dark:text-blue-200 mb-6 leading-relaxed">
                            {{ __('common.email_verification_dashboard_message') }}
                        </p>
                        <div class="space-y-4">
                            <form method="POST" action="{{ route('verification.send', ['locale' => app()->getLocale()]) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 dark:from-blue-500 dark:to-indigo-500 dark:hover:from-blue-600 dark:hover:to-indigo-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    {{ __('common.resend_verification_email') }}
                                </button>
                            </form>
                            <p class="text-sm text-blue-600 dark:text-blue-300">
                                {{ __('common.check_spam_folder') }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                @php
                    $suspendedAccount = Auth::user()->accounts()->where('status', 'SUSPENDED')->first();
                @endphp

                @if($suspendedAccount && ($suspendedAccount->suspension_reason || $suspendedAccount->suspension_instructions))
                    <div class="max-w-3xl mx-auto bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 p-6 rounded-xl shadow-md mb-8">
                        <h2 class="text-2xl font-bold text-red-700 dark:text-red-300 mb-6">{{ __('common.account_suspended') }}</h2>

                        @if($suspendedAccount->suspension_reason)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-2">
                                    {{ __('common.suspension_reason') }}
                                </h3>
                                <p class="text-red-800 dark:text-red-200 leading-relaxed">
                                    {{ $suspendedAccount->suspension_reason }}
                                </p>
                            </div>
                        @endif

                        @if($suspendedAccount->suspension_instructions)
                            <div>
                                <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-2">
                                    {{ __('common.suspension_instructions') }}
                                </h3>
                                <p class="text-red-800 dark:text-red-200 leading-relaxed">
                                    {{ $suspendedAccount->suspension_instructions }}
                                </p>
                            </div>
                        @endif
                    </div>
                @else
                    @livewire('user-bank-cards')
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
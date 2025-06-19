<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-700 text-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">
                        <i class="fa-solid fa-paper-plane mr-2"></i>{{ __('transfers.transfer_in_progress') }}
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
                        </div>
                    </div>
                </div>
            @else
                <!-- Transfer Progress Component -->
                @livewire('deposit-management.transfer-progress')
            @endif
        </div>
    </div>
</x-app-layout>
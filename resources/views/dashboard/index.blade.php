<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-700 text-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">{{ __('common.welcome_name', ['name' => Auth::user()->name]) }}</h1>
                    @if(!Auth::user()->is_admin)
                        @php
                            $account = Auth::user()->accounts()->first();
                        @endphp
                        @if ($account)
                            <div class="mt-2">
                                @if ($account->status === 'INACTIVE')
                                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
                                        {{ __('common.account_creation_under_review') }}
                                    </span>
                                @elseif ($account->status === 'ACTIVE')
                                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                        {{ __('common.active') }}
                                    </span>
                                @elseif ($account->status === 'SUSPENDED')
                                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">
                                        {{ __('common.suspended') }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="mt-2 text-blue-100">
                                {{ __('common.no_account_found') }}
                            </div>
                        @endif
                    @endif
                </div>
                @if(!Auth::user()->is_admin)
                    <div class="text-right">
                        <p class="text-blue-100 text-sm font-medium">
                            <i class="fa-solid fa-user mr-1"></i>
                            {{ __('common.user_space') }}
                        </p>
                    </div>
                @else
                    <div class="text-right">
                        <p class="text-blue-100 text-sm font-medium">
                            <i class="fa-solid fa-shield-halved mr-1"></i>
                            {{ __('admin.administrator_space') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(Auth::user()->is_admin)
                @livewire('admin-dashboard-stats')
                <div class="mt-8">
                    @livewire('latest-inactive-accounts')
                </div> 
            @else
                @php
                    $suspendedAccount = Auth::user()->accounts()->where('status', 'SUSPENDED')->first();
                @endphp

                @if(!Auth::user()->hasVerifiedEmail())
                    <div class="max-w-3xl mx-auto bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-700 p-8 rounded-xl shadow-lg">
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
                @elseif($suspendedAccount && ($suspendedAccount->suspension_reason || $suspendedAccount->suspension_instructions))
                    <div class="max-w-3xl mx-auto bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 p-6 rounded-xl shadow-md">
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
                    @php
                        $blockedTransactions = Auth::user()->transactions()
                            ->where('status', 'BLOCKED')
                            ->get();
                    @endphp
                    
                    @if($blockedTransactions->count() > 0)
                        <div class="max-w-3xl mx-auto bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 border border-orange-200 dark:border-orange-700 p-6 rounded-xl shadow-lg mb-8">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-orange-900 dark:text-orange-100 mb-2">
                                        {{ __('transfers.blocked_transactions_alert') }}
                                    </h3>
                                    <p class="text-orange-800 dark:text-orange-200 mb-4">
                                        @if($blockedTransactions->count() == 1)
                                            Vous avez une transaction bloquée qui nécessite votre attention.
                                        @else
                                            Vous avez {{ $blockedTransactions->count() }} transactions bloquées qui nécessitent votre attention.
                                        @endif
                                    </p>
                                    <div class="space-y-2">
                                        @foreach($blockedTransactions as $transaction)
                                            <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-3 rounded-lg border border-orange-200 dark:border-orange-700">
                                                <div>
                                                    <p class="font-medium text-gray-900 dark:text-white">
                                                        {{ $transaction->reference }}
                                                    </p>
                                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                                        {{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
                                                    </p>
                                                </div>
                                                <a href="{{ route('dashboard.transfer-progress', ['locale' => app()->getLocale(), 'ref' => $transaction->reference]) }}" 
                                                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-medium rounded-lg transition-all duration-200">
                                                    <i class="fas fa-arrow-right mr-2"></i>
                                                    Continuer
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @livewire('user-dashboard-stats')
                    <div class="mt-8">
                        @livewire('user-account-ribs')
                    </div>
                    <div class="mt-8">
                        @livewire('user-bank-cards', ['dashboardView' => true, 'maxCards' => 4])
                    </div>
                    <div class="mt-8">
                        @livewire('user-wallets', ['dashboardView' => true, 'maxWallets' => 4])
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>

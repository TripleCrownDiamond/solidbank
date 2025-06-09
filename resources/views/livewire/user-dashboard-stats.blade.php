@php
    $statsCards = [];
    
    // Only show stats for non-admin users
    if (!$user->is_admin) {
        // Account Balance Card (only if account is active)
        if ($account && $account->status === 'ACTIVE') {
            $statsCards[] = [
                'title' => __('common.account_balance'),
                'value' => \App\Helpers\NumberHelper::formatCurrency($accountBalance, $account->currency ?? 'USD', true),
                'exactValue' => number_format($accountBalance, 2) . ' ' . ($account->currency ?? 'USD'),
                'icon' => 'fa-wallet',
                'color' => 'brand-primary',
                'gradient' => 'from-brand-primary to-brand-primary-light',
                'isBalance' => true
            ];
        }
        
        // Latest Transaction Card (only if account is active and has transactions)
        if ($account && $account->status === 'ACTIVE' && $latestTransaction) {
            $transactionType = $latestTransaction->from_account_id === $account->id ? 'Sent' : 'Received';
            $transactionAmount = ($latestTransaction->from_account_id === $account->id ? '-' : '+') . \App\Helpers\NumberHelper::formatCompact($latestTransaction->amount, 1);
            
            $statsCards[] = [
                'title' => __('common.latest_transaction'),
                'value' => $transactionAmount . ' ' . ($latestTransaction->currency ?: ($account->currency ?? 'USD')),
                'subtitle' => $transactionType . ' • ' . $latestTransaction->created_at->diffForHumans(),
                'icon' => $latestTransaction->from_account_id === $account->id ? 'fa-arrow-up' : 'fa-arrow-down',
                'color' => $latestTransaction->from_account_id === $account->id ? 'brand-error' : 'brand-success',
                'gradient' => $latestTransaction->from_account_id === $account->id ? 'from-brand-error to-brand-warning' : 'from-brand-success to-brand-accent',
                'isTransaction' => true
            ];
        }
        
        // Cards - Show individual cards if they have balance, otherwise show total count
        if ($cards->count() > 0 && $cards->where('balance', '>', 0)->count() > 0) {
            foreach ($cards->where('balance', '>', 0) as $card) {
                $statsCards[] = [
                    'title' => __('common.card') . ' (' . $card->type . ')',
                    'value' => \App\Helpers\NumberHelper::formatCurrency($card->balance, $card->currency ?? 'USD', true),
                    'subtitle' => '**** ' . substr($card->card_number, -4),
                    'icon' => 'fa-credit-card',
                    'color' => 'brand-accent',
                    'gradient' => 'from-brand-accent to-brand-success',
                    'isCard' => true
                ];
            }
        } else {
            $statsCards[] = [
                'title' => __('common.total_cards'),
                'value' => $cards->count(),
                'icon' => 'fa-credit-card',
                'color' => 'brand-accent',
                'gradient' => 'from-brand-accent to-brand-success'
            ];
        }
        
        // Crypto Wallets - Show individual wallets if they have balance, otherwise show total count
        if ($wallets->count() > 0 && $wallets->where('balance', '>', 0)->count() > 0) {
            foreach ($wallets->where('balance', '>', 0) as $wallet) {
                $statsCards[] = [
                    'title' => __('common.crypto_wallet') . ' (' . strtoupper($wallet->coin) . ')',
                    'value' => \App\Helpers\NumberHelper::formatCrypto($wallet->balance),
                    'subtitle' => substr($wallet->address, 0, 10) . '...' . substr($wallet->address, -6),
                    'icon' => 'fa-bitcoin-sign',
                    'color' => 'brand-warning',
                    'gradient' => 'from-brand-warning to-brand-error',
                    'isCrypto' => true
                ];
            }
        } else {
            $statsCards[] = [
                'title' => __('common.crypto_wallets'),
                'value' => $wallets->count(),
                'icon' => 'fa-bitcoin-sign',
                'color' => 'brand-warning',
                'gradient' => 'from-brand-warning to-brand-error'
            ];
        }
    }
@endphp

@if(!$user->is_admin && !empty($statsCards))
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('common.my_stats') }}</h2>
        <div class="flex items-center space-x-4">
            <div class="h-1 w-16 bg-gradient-to-r from-brand-primary to-brand-accent rounded-full"></div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($statsCards as $card)
            <div class="group relative bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-xl hover:shadow-{{ $card['color'] }}/20 hover:-translate-y-1 transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:border-{{ $card['color'] }}/30 overflow-hidden cursor-pointer">
                <!-- Gradient top border -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r {{ $card['gradient'] }}"></div>
                
                <!-- Card content -->
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <!-- Icon container -->
                        <div class="p-3 bg-{{ $card['color'] }}/10 group-hover:bg-{{ $card['color'] }}/20 rounded-lg transition-all duration-300 group-hover:scale-110">
                            <i class="fa-solid {{ $card['icon'] }} text-{{ $card['color'] }} text-xl group-hover:text-{{ $card['color'] }}-hover transition-colors duration-300"></i>
                        </div>
                        
                        <!-- Value container -->
                        <div class="text-right">
                            @if(isset($card['isBalance']) && $card['isBalance'])
                                <p class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-{{ $card['color'] }} transition-colors duration-300 leading-tight">
                                    {{ $card['value'] }}
                                    @if(isset($card['exactValue']))
                                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 group-hover:text-{{ $card['color'] }}/70 mt-1">{{ $card['exactValue'] }}</span>
                                    @endif
                                </p>
                            @elseif(isset($card['isTransaction']) && $card['isTransaction'])
                                <p class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-{{ $card['color'] }} transition-colors duration-300 leading-tight">
                                    {{ $card['value'] }}
                                    @if(isset($card['subtitle']))
                                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 group-hover:text-{{ $card['color'] }}/70">{{ $card['subtitle'] }}</span>
                                    @endif
                                </p>
                            @elseif(isset($card['isCard']) && $card['isCard'])
                                <p class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-{{ $card['color'] }} transition-colors duration-300 leading-tight">
                                    {{ $card['value'] }}
                                    @if(isset($card['subtitle']))
                                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 group-hover:text-{{ $card['color'] }}/70">{{ $card['subtitle'] }}</span>
                                    @endif
                                </p>
                            @elseif(isset($card['isCrypto']) && $card['isCrypto'])
                                <p class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-{{ $card['color'] }} transition-colors duration-300 leading-tight">
                                    {{ $card['value'] }}
                                    @if(isset($card['subtitle']))
                                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 group-hover:text-{{ $card['color'] }}/70">{{ $card['subtitle'] }}</span>
                                    @endif
                                </p>
                            @else
                                <p class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-{{ $card['color'] }} transition-colors duration-300">{{ $card['value'] }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Title -->
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-{{ $card['color'] }}/80 uppercase tracking-wide transition-colors duration-300">{{ $card['title'] }}</h3>
                </div>
                
                <!-- Hover glow effect -->
                <div class="absolute inset-0 bg-gradient-to-r {{ $card['gradient'] }} opacity-0 group-hover:opacity-5 transition-opacity duration-300 pointer-events-none"></div>
            </div>
        @endforeach
    </div>
</div>
@elseif(!$user->is_admin)
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('common.my_stats') }}</h2>
        <div class="flex items-center space-x-4">
            <div class="h-1 w-16 bg-gradient-to-r from-brand-primary to-brand-accent rounded-full"></div>
        </div>
    </div>
    
    <div class="text-center py-12">
        <div class="w-16 h-16 mx-auto mb-4 bg-brand-secondary/10 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-chart-line text-2xl text-brand-secondary"></i>
        </div>
        <p class="text-brand-secondary font-medium">{{ __('common.no_active_account_stats') }}</p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ __('common.activate_account_to_view_stats') }}</p>
    </div>
</div>
@endif
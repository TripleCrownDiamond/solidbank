@php
    $statsCards = [
        [
            'title' => __('common.total_accounts'),
            'value' => $totalAccounts,
            'icon' => 'fa-money-bill-transfer',
            'color' => 'brand-primary',
            'gradient' => 'from-brand-primary to-brand-primary-light'
        ],
        [
            'title' => __('common.active_accounts'),
            'value' => $activeAccounts,
            'icon' => 'fa-check-circle',
            'color' => 'brand-accent',
            'gradient' => 'from-brand-accent to-brand-success'
        ],
        [
            'title' => __('common.inactive_accounts_pending_review'),
            'value' => $inactiveAccounts,
            'icon' => 'fa-clock',
            'color' => 'brand-warning',
            'gradient' => 'from-brand-warning to-brand-error'
        ],
        [
            'title' => __('common.total_users'),
            'value' => $totalUsers,
            'icon' => 'fa-users',
            'color' => 'brand-secondary',
            'gradient' => 'from-brand-secondary to-brand-primary-dark'
        ],
        [
            'title' => __('common.top_country_by_users'),
            'value' => $topCountryUsers ? $topCountryUsers->country->name . ' (' . $topCountryUsers->user_count . ')' : 'N/A',
            'icon' => 'fa-globe',
            'color' => 'brand-primary',
            'gradient' => 'from-brand-primary to-brand-accent',
            'isCountry' => true
        ]
    ];
@endphp

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('common.stats') }}</h2>
        <div class="flex items-center space-x-4">
            <div class="h-1 w-16 bg-gradient-to-r from-brand-primary to-brand-accent rounded-full"></div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
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
                            @if(isset($card['isCountry']) && $card['isCountry'])
                                <p class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-{{ $card['color'] }} transition-colors duration-300 leading-tight">
                                    @if ($topCountryUsers)
                                        {{ $topCountryUsers->country->name }}
                                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 group-hover:text-{{ $card['color'] }}/70">({{ $topCountryUsers->user_count }})</span>
                                    @else
                                        <span class="text-gray-400">N/A</span>
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
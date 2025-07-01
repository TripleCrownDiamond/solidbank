@props([]) <!-- Fermeture correcte -->

@php
$currencySymbol = __('common.currency_symbol');

$cards = [
    [
        'type' => 'visa',
        'name' => 'Visa Classic',
        'number' => '4532 XXXX XXXX 9012',
        'balance' => '2,450.75',
        'currency' => $currencySymbol
    ],
    [
        'type' => 'mastercard',
        'name' => 'MasterCard Gold',
        'number' => '5555 XXXX XXXX 2222',
        'balance' => '8,920.30',
        'currency' => $currencySymbol
    ],
    [
        'type' => 'amex',
        'name' => 'Amex Platinum',
        'number' => '3782 XXXX XXXX 1005',
        'balance' => '15,675.90',
        'currency' => $currencySymbol
    ]
];
@endphp

<div class="relative" x-data="{
    currentSlide: 0,
    cards: {{ json_encode($cards) }},
    init() {
        setInterval(() => {
            this.nextSlide();
        }, 4000);
    },
    nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.cards.length;
    },
    prevSlide() {
        this.currentSlide = this.currentSlide === 0 ? this.cards.length - 1 : this.currentSlide - 1;
    },
    goToSlide(index) {
        this.currentSlide = index;
    },
    getCardIcon(type) {
        switch(type) {
            case 'visa': return 'fa-cc-visa';
            case 'mastercard': return 'fa-cc-mastercard';
            case 'amex': return 'fa-cc-amex';
            default: return 'fa-credit-card';
        }
    },
    getCardGradient(type) {
        switch(type) {
            case 'visa': return 'from-blue-500 to-blue-700';
            case 'mastercard': return 'from-red-500 to-orange-600';
            case 'amex': return 'from-green-500 to-teal-600';
            default: return 'from-gray-500 to-gray-700';
        }
    }
}" 
@mouseenter="stopAutoSlide()" 
@mouseleave="startAutoSlide()">
    
    <!-- Slider Container -->
    <div class="relative w-80 h-48 mx-auto overflow-hidden rounded-2xl shadow-2xl">
        <!-- Cards Container -->
        <div class="flex transition-transform duration-500 ease-in-out"
             :style="`transform: translateX(-${currentSlide * 100}%)`">
            <template x-for="(card, index) in cards" :key="index">
                <div class="w-full h-48 flex-shrink-0 overflow-hidden rounded-2xl">

                    <!-- Card Design -->
                    <div class="w-full h-full bg-white/20 dark:bg-gray-800/20 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/30 dark:border-gray-600/30 flex flex-col justify-between p-6 text-gray-900 dark:text-white"
                         :class="getCardGradient(card.type)">

                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                    <span x-text="card.type"></span>
                                </span>
                                <h3 class="text-lg font-bold mt-1 text-gray-900 dark:text-white">
                                    <span x-text="card.name"></span>
                                </h3>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                    {{ __('common.balance') }}
                                </span>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">
                                    <span x-text="card.balance + ' ' + card.currency"></span>
                                </p>
                            </div>
                        </div>

                        <!-- Card Number -->
                        <div class="text-gray-900 dark:text-white">
                            <div class="text-base font-mono tracking-wider" x-text="card.number"></div>
                        </div>

                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Navigation Arrows -->
    <button @click="prevSlide()" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/80 dark:bg-white/30 text-gray-800 dark:text-white p-2 rounded-full shadow-lg hover:bg-white dark:hover:bg-white/50 focus:outline-none z-10 transition-colors duration-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>
    <button @click="nextSlide()" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/80 dark:bg-white/30 text-gray-800 dark:text-white p-2 rounded-full shadow-lg hover:bg-white dark:hover:bg-white/50 focus:outline-none z-10 transition-colors duration-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>
</div>

<!-- Navigation Dots -->
<div class="mt-8 flex justify-center space-x-3">
    <template x-for="(_, index) in cards" :key="index">
        <button @click="goToSlide(index)" :class="{'bg-blue-600 dark:bg-blue-500': currentSlide === index, 'bg-gray-400 dark:bg-gray-300': currentSlide !== index}" class="w-4 h-4 rounded-full transition-colors duration-200 hover:bg-blue-500 dark:hover:bg-blue-400"></button>
    </template>
</div>

<script>
// Helper function for translations (fallback)
if (typeof $t === 'undefined') {
    window.$t = function(key) {
        const translations = {
            'common.mobile_payment': '{{ __('common.mobile_payment') }}',
            'common.free_checkbook': '{{ __('common.free_checkbook') }}',
            'common.premium_insurance': '{{ __('common.premium_insurance') }}',
            'common.worldwide_assistance': '{{ __('common.worldwide_assistance') }}',
            'common.exclusive_rewards': '{{ __('common.exclusive_rewards') }}',
            'common.concierge_service': '{{ __('common.concierge_service') }}'
        };
        return translations[key] || key;
    };
}
</script>
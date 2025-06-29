@props([
    'cards' => [
        [
            'type' => 'visa',
            'name' => 'Visa',
            'number' => '4532 1234 5678 9012',
            'balance' => '2,450',
            'currency' => 'EUR'
        ],
        [
            'type' => 'mastercard',
            'name' => 'MasterCard',
            'number' => '5555 4444 3333 2222',
            'balance' => '5,780',
            'currency' => 'EUR'
        ],
        [
            'type' => 'amex',
            'name' => 'American Express',
            'number' => '3782 8224 6322 1005',
            'balance' => '12,340',
            'currency' => 'EUR'
        ]
    ]
])

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
                
                <!-- Card Design - Matching financial-card.blade.php compact style -->
                <div class="w-full h-full bg-white/20 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/30 flex flex-col justify-between p-6 text-white"
                     :class="getCardGradient(card.type)">
                    
                    <!-- Header -->
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-white/80">{{ getAppName() }}</p>
                            <span class="text-lg font-bold" x-text="card.name"></span>
                        </div>
                    </div>
                    
                    <!-- Balance -->
                    <div class="text-right">
                        <p class="text-white/80 text-sm">{{ __('common.balance') }}</p>
                        <div class="text-2xl font-bold" x-text="card.balance + ' ' + card.currency"></div>
                    </div>
                    
                    <!-- Card Number -->
                    <div class="mb-4">
                        <p class="text-white/80 text-sm mb-1">{{ __('common.card_number') }}</p>
                        <div class="text-lg font-mono tracking-wider" x-text="card.number"></div>
                    </div>
                    

                </div>
                </div>
            </template>
        </div>
    </div>

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
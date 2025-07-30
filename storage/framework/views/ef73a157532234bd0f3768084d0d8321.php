<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
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
?>

<div class="relative"
     x-data="cardSlider()"
     x-init="init()"
     @mouseenter="stopAutoSlide()"
     @mouseleave="startAutoSlide()">

    <!-- Slider Container -->
    <div class="relative w-80 h-48 mx-auto overflow-hidden rounded-2xl shadow-2xl">
        <!-- Cards -->
        <div class="flex transition-transform duration-500 ease-in-out"
             :style="`transform: translateX(-${currentSlide * 100}%)`">

            <template x-for="(card, index) in cards" :key="index">
                <div class="w-full h-48 flex-shrink-0">
                    <div class="w-full h-full bg-gradient-to-r rounded-2xl shadow-2xl border border-white/30 p-6 flex flex-col justify-between text-white"
                         :class="getCardGradient(card.type)">

                        <!-- Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="text-xs font-medium uppercase tracking-wider opacity-80" x-text="card.type"></span>
                                <h3 class="text-lg font-bold mt-1" x-text="card.name"></h3>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-medium uppercase tracking-wider opacity-80">
                                    <?php echo e(__('common.balance')); ?>

                                </span>
                                <p class="text-xl font-bold">
                                    <span x-text="card.balance + ' ' + card.currency"></span>
                                </p>
                            </div>
                        </div>

                        <!-- Card Number -->
                        <div class="font-mono tracking-wider text-lg" x-text="card.number"></div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Navigation Arrows -->
    <button @click="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-gray-700 text-gray-800 dark:text-white p-2 rounded-full shadow hover:bg-white focus:outline-none z-10">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>

    <button @click="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-gray-700 text-gray-800 dark:text-white p-2 rounded-full shadow hover:bg-white focus:outline-none z-10">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>
</div>

<!-- Dots -->
<div class="mt-4 flex justify-center space-x-2">
    <template x-for="(_, index) in cards" :key="index">
        <button @click="goToSlide(index)"
                :class="currentSlide === index ? 'bg-blue-600' : 'bg-gray-400'"
                class="w-3 h-3 rounded-full transition-colors"></button>
    </template>
</div>

<!-- Alpine.js Component -->
<script>
function cardSlider() {
    return {
        currentSlide: 0,
        cards: <?php echo json_encode($cards, 15, 512) ?>,
        interval: null,

        init() {
            this.startAutoSlide();
        },
        startAutoSlide() {
            this.interval = setInterval(() => this.nextSlide(), 4000);
        },
        stopAutoSlide() {
            clearInterval(this.interval);
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
        getCardGradient(type) {
            switch (type) {
                case 'visa': return 'from-blue-500 to-blue-700';
                case 'mastercard': return 'from-red-500 to-orange-600';
                case 'amex': return 'from-green-500 to-teal-600';
                default: return 'from-gray-500 to-gray-700';
            }
        }
    }
}
</script>
<?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/components/card-slider.blade.php ENDPATH**/ ?>
<section class="relative w-full h-[700px] overflow-hidden mb-0">
    <div x-data="heroSlider()" x-init="init()" class="relative w-full h-full rounded-b-lg">
        <!-- Slides -->
        <template x-for="(slide, index) in slides" :key="index">
            <div 
                x-show="activeSlide === index" 
                x-transition:enter="transition ease-out duration-1000 transform -translate-x-full" 
                x-transition:enter-start="opacity-0" 
                x-transition:enter-end="opacity-100 translate-x-0" 
                x-transition:leave="transition ease-in duration-1000 transform translate-x-0" 
                x-transition:leave-start="opacity-100" 
                x-transition:leave-end="opacity-0 -translate-x-full" 
                class="absolute inset-0 w-full h-full bg-cover bg-center rounded-xl"
                :style="`background-image: url('/img/${slide.image}');`"
            >
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black bg-opacity-70 rounded-xl"></div> <!-- Optionnel : garder l'overlay arrondi aussi -->

                <div class="relative z-10 flex flex-col items-center justify-center h-full text-white text-center px-4 sm:px-6 lg:px-8">
                    <span class="inline-block bg-blue-500 text-white px-3 py-1 rounded-full mb-4 font-medium text-sm sm:text-base" x-text="slide.offer"></span>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold mb-4 leading-tight" x-html="slide.title"></h1>
                    <p class="mb-6 text-base sm:text-lg lg:text-xl text-blue-100" x-text="slide.description"></p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-8">
                        <a 
                            :href="slide.cta1Link" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-400 text-white font-bold rounded-lg shadow-lg transition duration-300 transform hover:scale-105 text-center" 
                            x-text="slide.cta1Text"
                        ></a>
                        <a 
                            :href="slide.cta2Link" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-400 text-white font-bold rounded-lg shadow-lg transition duration-300 transform hover:scale-105 text-center" 
                            x-text="slide.cta2Text"
                        ></a>
                    </div>

                    <!-- Stats Section -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 text-sm sm:text-base">
                        <div class="text-center">
                            <span class="font-bold block" x-text="slide.stat1Value"></span>
                            <span class="text-blue-200 text-xs sm:text-sm" x-text="slide.stat1Label"></span>
                        </div>
                        <div class="text-center">
                            <span class="font-bold block" x-text="slide.stat2Value"></span>
                            <span class="text-blue-200 text-xs sm:text-sm" x-text="slide.stat2Label"></span>
                        </div>
                        <div class="text-center">
                            <span class="font-bold block" x-text="slide.stat3Value"></span>
                            <span class="text-blue-200 text-xs sm:text-sm" x-text="slide.stat3Label"></span>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Navigation Dots -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-3 z-20">
            <template x-for="(slide, index) in slides" :key="index">
                <button 
                    @click="goToSlide(index)" 
                    :class="{'bg-white': activeSlide === index, 'bg-gray-400': activeSlide !== index}" 
                    class="w-3 h-3 rounded-full focus:outline-none"
                ></button>
            </template>
        </div>
    </div>
</section>
<script>
    function heroSlider() {
        return {
            activeSlide: 0,
            slides: [
                {
                    image: 'slide1.jpg',
                    offer: '{{ __('common.welcome_offer') }}',
                    title: '{{ getAppName() }}&nbsp;: <span class="text-blue-100">{{ __('common.banking_future') }}</span>',
                    description: '{{ __('common.revolutionary_banking_experience') }}',
                    cta1Text: '{{ __('common.open_account') }}',
                    cta1Link: '{{ route('locale.register', ['locale' => app()->getLocale()]) }}',
                    cta2Text: '{{ __('common.learn_more') }}',
                    cta2Link: '{{ route('services', ['locale' => app()->getLocale()]) }}',
                    stat1Value: '{{ __('common.zero_euro') }}',
                    stat1Label: '{{ __('common.opening_fees') }}',
                    stat2Value: '{{ __('common.two_minutes') }}',
                    stat2Label: '{{ __('common.quick_opening') }}',
                    stat3Value: '{{ __('common.24_7') }}',
                    stat3Label: '{{ __('common.customer_support') }}',
                },
                {
                    image: 'slide2.jpg',
                    offer: '{{ __('common.secure_and_fast') }}',
                    title: '{{ getAppName() }}&nbsp;: <span class="text-blue-100">{{ __('common.your_finances_secured') }}</span>',
                    description: '{{ __('common.advanced_security_and_instant_transactions') }}',
                    cta1Text: '{{ __('common.open_account') }}',
                    cta1Link: '{{ route('locale.register', ['locale' => app()->getLocale()]) }}',
                    cta2Text: '{{ __('common.explore_features') }}',
                    cta2Link: '{{ route('services', ['locale' => app()->getLocale()]) }}',
                    stat1Value: '50K+',
                    stat1Label: '{{ __('common.satisfied_clients') }}',
                    stat2Value: '€2.5M+',
                    stat2Label: '{{ __('common.transactions_processed') }}',
                    stat3Value: '99.9%',
                    stat3Label: '{{ __('common.uptime_guarantee') }}',
                },
                {
                    image: 'slide3.jpg',
                    offer: '{{ __('common.global_reach') }}',
                    title: '{{ getAppName() }}&nbsp;: <span class="text-blue-100">{{ __('common.banking_without_borders') }}</span>',
                    description: '{{ __('common.manage_your_money_anywhere_anytime') }}',
                    cta1Text: '{{ __('common.open_account') }}',
                    cta1Link: '{{ route('locale.register', ['locale' => app()->getLocale()]) }}',
                    cta2Text: '{{ __('common.contact_us') }}',
                    cta2Link: '{{ route('contact', ['locale' => app()->getLocale()]) }}',
                    stat1Value: '100+',
                    stat1Label: '{{ __('common.countries_supported') }}',
                    stat2Value: '24/7',
                    stat2Label: '{{ __('common.multilingual_support') }}',
                    stat3Value: 'Fast',
                    stat3Label: '{{ __('common.international_transfers') }}',
                },
            ],
            init() {
                setInterval(() => {
                    this.nextSlide();
                }, 10000); // Change slide every 5 seconds
            },
            nextSlide() {
                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
            },
            goToSlide(index) {
                this.activeSlide = index;
            }
        }
    }
</script>

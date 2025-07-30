<footer class="bg-white border-t border-gray-100 mt-8 dark:bg-gray-900 text-gray-900">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">{{ __('common.about_us') }}</h3>
                    <p class="mt-4 text-base text-gray-500">
                        {{ __('common.about_us_description', ['app_name' => $config->app_name ?? bank_config('bank_name', 'DBQIC')]) }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">{{ __('common.quick_links') }}</h3>
                    <ul class="mt-4 space-y-4">
                        <li>
                            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="text-base text-gray-500 hover:text-brand-primary transition-colors duration-200">
                                {{ __('nav.home') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('services', ['locale' => app()->getLocale()]) }}" class="text-base text-gray-500 hover:text-brand-primary transition-colors duration-200">
                                {{ __('common.services') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('loan-request', ['locale' => app()->getLocale()]) }}" class="text-base text-gray-500 hover:text-brand-primary transition-colors duration-200">
                                {{ __('loan.request_loan') }}
                            </a>
                        </li>
                        @if(isCryptoEnabled())
                        <li>
                            <a href="{{ route('crypto-refund', ['locale' => app()->getLocale()]) }}" class="text-base text-gray-500 hover:text-brand-primary transition-colors duration-200">
                                {{ __('common.crypto_refund') }}
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="text-base text-gray-500 hover:text-brand-primary transition-colors duration-200">
                                {{ __('common.contact') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">{{ __('common.contact_info') }}</h3>
                    <ul class="mt-4 space-y-4">
                        @php
                            $config = \App\Models\Config::first();
                        @endphp
                        <li class="text-base text-gray-500">
                            {{ __('common.footer_address_line1', ['bank_address' => $config->bank_address ?? '']) }}
                {{ __('common.footer_address_line2', ['bank_address_line2' => $config->bank_address_line2 ?? '']) }}
                {{ __('common.footer_address_line3', ['bank_address_line3' => $config->bank_address_line3 ?? '']) }}
                        </li>
                        <li class="text-base text-gray-500">
                            
                            {{ __('common.footer_email', ['bank_email' => $config->bank_email ?? '']) }}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-100 pt-8">
                <p class="text-base text-gray-400 text-center">
                &copy; {{ date('Y') }} {{ $config->app_name ?? bank_config('bank_name', 'DBQIC') }}. {{ __('All rights reserved.') }}
            </p>
        </div>
    </div>
</footer>
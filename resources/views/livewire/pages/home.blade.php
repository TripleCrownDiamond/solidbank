<div class="home-container space-y-16">
    <!-- HERO SECTION -->
    <section class="relative bg-gradient-to-br from-indigo-700 via-blue-600 to-blue-400 text-white py-16 px-6 rounded-3xl shadow-xl overflow-hidden">
        <div class="absolute inset-0 bg-[url('/images/hero-bg.svg')] opacity-10"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-xl">
                <span class="inline-block bg-yellow-400 text-indigo-900 px-3 py-1 rounded-full mb-4 animate-bounce">Offre de bienvenue</span>
                <h1 class="text-5xl font-extrabold mb-4">Solid Bank&nbsp;: <span class="text-yellow-300">L'avenir de la banque</span></h1>
                <p class="mb-6 text-lg">Découvrez une expérience bancaire révolutionnaire avec votre carte Visa gratuite et nos services innovants.</p>
                <div class="flex gap-4">
                    <a href="#" class="px-6 py-3 bg-yellow-400 text-indigo-900 font-bold rounded-lg shadow hover:bg-yellow-300 transition">Ouvrir un compte</a>
                    <a href="#" class="px-6 py-3 border border-white font-bold rounded-lg hover:bg-white hover:text-indigo-700 transition">En savoir plus</a>
                </div>
                <div class="flex gap-8 mt-8 text-lg">
                    <div><span class="font-bold">0€</span><br><span class="text-gray-200 text-sm">Frais d'ouverture</span></div>
                    <div><span class="font-bold">2min</span><br><span class="text-gray-200 text-sm">Ouverture rapide</span></div>
                    <div><span class="font-bold">24/7</span><br><span class="text-gray-200 text-sm">Support client</span></div>
                </div>
            </div>
            <div class="flex-1 flex justify-center">
                <!-- Carte bancaire effet glassmorphism -->
                <div class="relative w-80 h-48 bg-white/20 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/30 flex flex-col justify-between p-6 animate-fade-in">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-white">Carte Visa Classic</span>
                        <svg class="w-10 h-10 text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="2" /></svg>
                    </div>
                    <div class="text-3xl font-extrabold text-white">0€</div>
                    <div class="text-xs text-white/80">100% Gratuite à vie</div>
                    <div class="flex gap-2 mt-2">
                        <span class="bg-green-400/80 text-xs px-2 py-1 rounded">Paiement mobile</span>
                        <span class="bg-blue-400/80 text-xs px-2 py-1 rounded">Chéquier offert</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="py-12 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
                    <span class="block">{{ __('Welcome to') }}</span>
                    <span class="block text-indigo-600 dark:text-indigo-400">SolidBank</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 dark:text-gray-300 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    {{ __('Your trusted financial partner for a secure and prosperous future.') }}
                </p>
                <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                    <div class="rounded-md shadow">
                        <a href="{{ route('register', ['locale' => app()->getLocale()]) }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                            {{ __('Get Started') }}
                        </a>
                    </div>
                    <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                        <a href="{{ route('services', ['locale' => app()->getLocale()]) }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                            {{ __('Our Services') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-indigo-600 dark:text-indigo-400 font-semibold tracking-wide uppercase">{{ __('Features') }}</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                    {{ __('Everything you need for your financial success') }}
                </p>
            </div>

            <div class="mt-10">
                <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                    <!-- Feature 1 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('Secure Banking') }}</h3>
                            <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                {{ __('State-of-the-art security measures to protect your assets and personal information.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('Fast Transactions') }}</h3>
                            <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                {{ __('Lightning-fast transactions and real-time updates for all your banking needs.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('24/7 Support') }}</h3>
                            <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                {{ __('Round-the-clock customer support to assist you with any banking needs.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('Investment Solutions') }}</h3>
                            <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                {{ __('Expert investment advice and solutions to help grow your wealth.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-indigo-700">
        <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">{{ __('Ready to get started?') }}</span>
                <span class="block">{{ __('Join SolidBank today.') }}</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-indigo-200">
                {{ __('Experience the future of banking with our innovative solutions and exceptional service.') }}
            </p>
            <a href="{{ route('register', ['locale' => app()->getLocale()]) }}" class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 sm:w-auto">
                {{ __('Sign up for free') }}
            </a>
        </div>
    </section>

    <!-- CHIFFRES CLÉS -->
    <section class="flex flex-wrap justify-around gap-8 text-center">
        <div>
            <div class="text-4xl font-bold text-indigo-700">+100K</div>
            <div class="text-gray-500">Clients satisfaits</div>
        </div>
        <div>
            <div class="text-4xl font-bold text-indigo-700">24/7</div>
            <div class="text-gray-500">Support</div>
        </div>
        <div>
            <div class="text-4xl font-bold text-indigo-700">0€</div>
            <div class="text-gray-500">Frais d'ouverture</div>
        </div>
        <div>
            <div class="text-4xl font-bold text-indigo-700">99,99%</div>
            <div class="text-gray-500">Disponibilité plateforme</div>
        </div>
    </section>

    <!-- AVANTAGES -->
    <section class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 dark:bg-gray-900">
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center hover:scale-105 transition">
            <svg class="w-10 h-10 text-indigo-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div class="font-bold mb-1 text-indigo-700">Sécurité avancée</div>
            <div class="text-gray-500 text-sm text-center">Vos données et transactions sont protégées par les dernières technologies.</div>
        </div>
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center hover:scale-105 transition">
            <svg class="w-10 h-10 text-indigo-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            <div class="font-bold mb-1 text-indigo-700">Transactions instantanées</div>
            <div class="text-gray-500 text-sm text-center">Virements et paiements en temps réel, partout dans le monde.</div>
        </div>
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center hover:scale-105 transition">
            <svg class="w-10 h-10 text-indigo-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
            <div class="font-bold mb-1 text-indigo-700">Support humain 24/7</div>
            <div class="text-gray-500 text-sm text-center">Une équipe disponible à tout moment pour répondre à vos besoins.</div>
        </div>
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center hover:scale-105 transition">
            <svg class="w-10 h-10 text-indigo-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
            <div class="font-bold mb-1 text-indigo-700">Solutions d'investissement</div>
            <div class="text-gray-500 text-sm text-center">Faites fructifier votre argent avec nos offres personnalisées.</div>
        </div>
    </section>

    <!-- SIMULATEUR DE PRÊT (Livewire) -->
    <section class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-indigo-700 mb-4">Simulateur de prêt personnel</h2>
        <livewire:loan-simulator />
    </section>

    <!-- TABLEAU TARIFS -->
    <section class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-indigo-700 mb-4">Nos tarifs</h2>
        <div class="overflow-x-auto rounded-xl shadow-lg">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-indigo-600 text-white">
                        <th class="py-3 px-4 text-left text-white">Cartes et services</th>
                        <th class="py-3 px-4 text-left text-white">Prix (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-indigo-50 transition">
                        <td class="py-3 px-4 text-indigo-700">Abonnement gestion compte internet & appli</td>
                        <td class="py-3 px-4 font-bold text-indigo-700">Gratuit</td>
                    </tr>
                    <tr class="hover:bg-indigo-50 transition">
                        <td class="py-3 px-4 text-indigo-700">Alertes SMS</td>
                        <td class="py-3 px-4 font-bold text-indigo-700">Gratuit</td>
                    </tr>
                    <tr class="hover:bg-indigo-50 transition">
                        <td class="py-3 px-4 text-indigo-700">Carte Visa Classic</td>
                        <td class="py-3 px-4 font-bold text-indigo-700">Gratuit</td>
                    </tr>
                    <tr class="hover:bg-indigo-50 transition">
                        <td class="py-3 px-4 text-indigo-700">Carte Visa Premier</td>
                        <td class="py-3 px-4 font-bold text-indigo-700">Gratuit</td>
                    </tr>
                    <tr class="hover:bg-indigo-50 transition">
                        <td class="py-3 px-4 text-indigo-700">Retrait DAB zone euro</td>
                        <td class="py-3 px-4 font-bold text-indigo-700">Gratuit</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-right mt-2">
            <a href="" class="text-indigo-600 hover:underline">Voir tous nos tarifs</a>
        </div>
    </section>


    <!-- FAQ (Accordéon Alpine.js) -->
    <section class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-indigo-700 mb-4">Questions fréquentes</h2>
        <div x-data="{open:null}" class="space-y-2">
            <div class="bg-white rounded shadow">
                <button @click="open===1?open=null:open=1" class="w-full text-left px-6 py-4 font-bold text-indigo-700 flex justify-between items-center">Comment ouvrir un compte ? <span x-text="open===1?'−':'+'"></span></button>
                <div x-show="open===1" x-transition class="px-6 pb-4 text-gray-600">En ligne en 2 minutes, il suffit de cliquer sur "Ouvrir un compte" et suivre les étapes.</div>
            </div>
            <div class="bg-white rounded shadow">
                <button @click="open===2?open=null:open=2" class="w-full text-left px-6 py-4 font-bold text-indigo-700 flex justify-between items-center">La carte est-elle vraiment gratuite à vie ? <span x-text="open===2?'−':'+'"></span></button>
                <div x-show="open===2" x-transition class="px-6 pb-4 text-gray-600">Oui, aucun frais caché, aucun engagement.</div>
            </div>
            <div class="bg-white rounded shadow">
                <button @click="open===3?open=null:open=3" class="w-full text-left px-6 py-4 font-bold text-indigo-700 flex justify-between items-center">Comment contacter le support ? <span x-text="open===3?'−':'+'"></span></button>
                <div x-show="open===3" x-transition class="px-6 pb-4 text-gray-600">Par chat, téléphone ou email 24/7, ou via le bouton WhatsApp en bas de page.</div>
            </div>
        </div>
    </section>

    <!-- NEWSLETTER & APPEL À L'ACTION -->
    <section class="max-w-3xl mx-auto bg-indigo-700 rounded-2xl shadow-lg p-8 text-white text-center">
        <h2 class="text-2xl font-bold mb-2">Restez informé des nouveautés Solid Bank</h2>
        <p class="mb-4">Recevez nos offres exclusives et conseils pour mieux gérer vos finances.</p>
        <form class="flex flex-col md:flex-row gap-4 justify-center">
            <input type="email" placeholder="Votre e-mail" class="rounded px-4 py-2 text-indigo-900 focus:outline-none" required>
            <button type="submit" class="bg-yellow-400 text-indigo-900 font-bold px-6 py-2 rounded hover:bg-yellow-300 transition">S'abonner</button>
        </form>
    </section>
</div> 
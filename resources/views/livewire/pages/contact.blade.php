<div class="contact-container space-y-16">
    <!-- HERO SECTION CONTACT -->
    <section class="relative bg-gradient-to-br from-indigo-700 via-blue-600 to-blue-400 text-white py-16 px-6 rounded-3xl shadow-xl overflow-hidden">
        <div class="absolute inset-0 bg-[url('/images/contact-bg.svg')] opacity-10"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-xl">
                <span class="inline-block bg-yellow-400 text-indigo-900 px-3 py-1 rounded-full mb-4 animate-bounce">Contact</span>
                <h1 class="text-5xl font-extrabold mb-4">Contactez <span class="text-yellow-300">Solid Bank</span></h1>
                <p class="mb-6 text-lg">Une question, un projet ? Notre équipe est à votre écoute 24/7 pour vous accompagner.</p>
                <div class="flex gap-4">
                    <a href="#formulaire-contact" class="px-6 py-3 bg-yellow-400 text-indigo-900 font-bold rounded-lg shadow hover:bg-yellow-300 transition">Écrire un message</a>
                    <a href="tel:+3221234567" class="px-6 py-3 border border-white font-bold rounded-lg hover:bg-white hover:text-indigo-700 transition">Appeler</a>
                </div>
            </div>
            <div class="flex-1 flex justify-center">
                <!-- Illustration ou icône contact -->
                <div class="relative w-80 h-48 bg-white/20 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/30 flex flex-col justify-center items-center p-6 animate-fade-in">
                    <svg class="w-20 h-20 text-yellow-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10.5a8.38 8.38 0 01-7.5 7.5A8.38 8.38 0 013 10.5C3 6.36 7.03 3 12 3s9 3.36 9 7.5z" /></svg>
                    <span class="text-lg font-bold text-white">Support 24/7</span>
                </div>
            </div>
        </div>
    </section>

    <!-- FORMULAIRE DE CONTACT -->
    <section id="formulaire-contact" class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-indigo-700 mb-4">Envoyez-nous un message</h2>
        <form class="space-y-4">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-bold mb-1">Nom</label>
                    <input type="text" class="w-full rounded px-4 py-2 border focus:outline-none" required>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-bold mb-1">E-mail</label>
                    <input type="email" class="w-full rounded px-4 py-2 border focus:outline-none" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Sujet</label>
                <input type="text" class="w-full rounded px-4 py-2 border focus:outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Message</label>
                <textarea class="w-full rounded px-4 py-2 border focus:outline-none" rows="5" required></textarea>
            </div>
            <button type="submit" class="bg-indigo-700 text-white font-bold px-6 py-2 rounded hover:bg-indigo-800 transition">Envoyer</button>
        </form>
    </section>

    <!-- INFOS DE CONTACT & CARTE -->
    <section class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <div class="bg-indigo-50 rounded-xl shadow p-8">
            <h3 class="text-xl font-bold text-indigo-700 mb-2">Nos coordonnées</h3>
            <div class="mb-2 text-indigo-700"><span class="font-bold">Adresse :</span> Square de Meeûs 38/42, 1000 Bruxelles, Belgique</div>
            <div class="mb-2 text-indigo-700"><span class="font-bold">Téléphone :</span> <a href="tel:+3221234567" class="text-indigo-600 hover:underline">+32 2 123 45 67</a></div>
            <div class="mb-2 text-indigo-700"><span class="font-bold">E-mail :</span> <a href="mailto:contact@solidbank.com" class="text-indigo-600 hover:underline">contact@solidbank.com</a></div>
            <div class="text-indigo-700"><span class="font-bold">Support :</span> 24h/24 et 7j/7</div>
        </div>
        <div class="rounded-xl overflow-hidden shadow">
            <!-- Carte Google Maps intégrée -->
            <iframe src="https://www.google.com/maps?q=Square+de+Meeûs+38/42,+1000+Bruxelles,+Belgique&output=embed" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

    <!-- FAQ CONTACT (Alpine.js) -->
    <section class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-indigo-700 mb-4">Questions fréquentes</h2>
        <div x-data="{open:null}" class="space-y-2">
            <div class="bg-white rounded shadow">
                <button @click="open===1?open=null:open=1" class="w-full text-left px-6 py-4 font-bold text-indigo-700 flex justify-between items-center">Comment puis-je suivre ma demande ? <span x-text="open===1?'−':'+'"></span></button>
                <div x-show="open===1" x-transition class="px-6 pb-4 text-gray-600">Vous recevrez un e-mail de confirmation et un conseiller vous contactera rapidement.</div>
            </div>
            <div class="bg-white rounded shadow">
                <button @click="open===2?open=null:open=2" class="w-full text-left px-6 py-4 font-bold text-indigo-700 flex justify-between items-center">Quel est le délai de réponse ? <span x-text="open===2?'−':'+'"></span></button>
                <div x-show="open===2" x-transition class="px-6 pb-4 text-gray-600">Nous répondons à toutes les demandes sous 24h maximum.</div>
            </div>
            <div class="bg-white rounded shadow">
                <button @click="open===3?open=null:open=3" class="w-full text-left px-6 py-4 font-bold text-indigo-700 flex justify-between items-center">Puis-je prendre rendez-vous en agence ? <span x-text="open===3?'−':'+'"></span></button>
                <div x-show="open===3" x-transition class="px-6 pb-4 text-gray-600">Oui, contactez-nous pour convenir d'un rendez-vous selon vos disponibilités.</div>
            </div>
        </div>
    </section>
</div>

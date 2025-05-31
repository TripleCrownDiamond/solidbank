<div>
    <div class="flex flex-col md:flex-row gap-4 items-center mb-4">
        <div>
            <label class="block text-sm font-bold mb-1 text-indigo-700">Montant (€)</label>
            <input type="range" min="1000" max="75000" step="500" wire:model="amount" class="w-64">
            <div class="text-indigo-700 font-bold">{{ number_format($amount, 0, ',', ' ') }} €</div>
        </div>
        <div>
            <label class="block text-sm font-bold mb-1 text-indigo-700">Durée (mois)</label>
            <input type="range" min="12" max="120" step="1" wire:model="duration" class="w-64">
            <div class="text-indigo-700 font-bold">{{ $duration }} mois</div>
        </div>
    </div>
    <div class="bg-indigo-50 rounded p-4 text-center">
        <div class="text-lg">Mensualité estimée :</div>
        <div class="text-3xl font-extrabold text-indigo-700 mb-2">{{ $monthly }} €</div>
        <div class="text-sm text-gray-500">TAEG fixe {{ $rate }}% - Simulation indicative</div>
    </div>
</div>
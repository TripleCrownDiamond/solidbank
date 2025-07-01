<div>
    <div class="flex flex-col md:flex-row gap-4 items-center mb-4">
        <div>
            <label class="block text-sm font-bold mb-1 text-indigo-700">{{ __('loan.currency') }}</label>
            <select wire:model="currency" class="w-64 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="EUR">EUR (€)</option>
                <option value="GBP">GBP (£)</option>
                <option value="CHF">CHF</option>
                <option value="SEK">SEK (kr)</option>
                <option value="NOK">NOK (kr)</option>
                <option value="DKK">DKK (kr)</option>
                <option value="PLN">PLN (zł)</option>
                <option value="CZK">CZK (Kč)</option>
                <option value="HUF">HUF (Ft)</option>
                <option value="RON">RON (lei)</option>
                <option value="BGN">BGN (лв)</option>
                <option value="HRK">HRK (kn)</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-bold mb-1 text-indigo-700">{{ __('loan.amount') }}</label>
            <input type="range" min="1000" max="75000" step="500" wire:model="amount" class="w-64">
            <div class="text-indigo-700 font-bold">{{ number_format($amount, 0, ',', ' ') }} {{ $currencySymbol }}</div>
        </div>
        <div>
            <label class="block text-sm font-bold mb-1 text-indigo-700">{{ __('loan.duration_months') }}</label>
            <input type="range" min="12" max="120" step="1" wire:model="duration" class="w-64">
            <div class="text-indigo-700 font-bold">{{ $duration }} {{ __('loan.months') }}</div>
        </div>
    </div>
    <div class="bg-indigo-50 rounded p-4 text-center">
        <div class="text-lg">{{ __('loan.estimated_monthly') }} :</div>
        <div class="text-3xl font-extrabold text-indigo-700 mb-2">{{ $monthly }} {{ $currencySymbol }}</div>
        <div class="text-sm text-gray-500">{{ __('loan.fixed_apr') }} {{ $rate }}% - {{ __('loan.indicative_simulation') }}</div>
    </div>
</div>
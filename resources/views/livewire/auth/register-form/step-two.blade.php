<!-- Step Header with Number and Title -->
<div class="text-center mb-8">
    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-brand-primary dark:bg-brand-primary mb-3">
        <span class="text-xl font-bold text-white dark:text-white">2</span>
    </div>
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('register.step2') }}</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('register.step', ['step' => 2]) }}</p>
</div>

<form wire:submit.prevent="nextStep" class="space-y-6">
    <!-- Country Field -->
    <div>
        <label for="country_id" class="block text-sm font-medium text-gray-900 dark:text-white">
            {{ __('register.country') }}
        </label>
        <select id="country_id" wire:model.live="country_id"
            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-primary focus:ring-brand-primary">
            <option value="">{{ __('register.select_country') }}</option>
            @foreach ($countries as $country)
                <option value="{{ $country->id }}">{{ $country->name }}</option>
            @endforeach
        </select>
        @error('country_id')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <!-- Phone Number Field -->
    <div>
        <label for="phone_number" class="block text-sm font-medium text-gray-900 dark:text-white">
            {{ __('register.phone_number') }}
        </label>
        <div class="flex items-center mt-1">
            @if ($this->selectedCountry && !empty($this->selectedCountry->dial_code))
                <span class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-l-md bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm border-r-0">
                    {{ $this->selectedCountry->dial_code }}
                </span>
                <input type="text" id="phone_number" wire:model.defer="phone_number"
                    placeholder="123456789"
                    class="flex-1 rounded-r-md rounded-l-none border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-brand-primary focus:ring-brand-primary" />
            @else
                <input type="text" id="phone_number" wire:model.defer="phone_number"
                    placeholder="{{ __('register.phone_placeholder') }}"
                    class="flex-1 rounded-md border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-brand-primary focus:ring-brand-primary" />
            @endif
        </div>
        @if ($this->selectedCountry && !empty($this->selectedCountry->dial_code))
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                {{ __('register.phone_hint') }}: {{ $this->selectedCountry->dial_code }}123456789
            </p>
        @endif
        @error('phone_number')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <!-- Other fields in 2-column grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Region -->
        <div>
            <label for="region" class="block text-sm font-medium text-gray-900 dark:text-white">
                {{ __('register.region') }}
            </label>
            <input type="text" id="region" wire:model.defer="region"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-primary focus:ring-brand-primary" />
            @error('region')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <!-- City -->
        <div>
            <label for="city" class="block text-sm font-medium text-gray-900 dark:text-white">
                {{ __('register.city') }}
            </label>
            <input type="text" id="city" wire:model.defer="city"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-primary focus:ring-brand-primary" />
            @error('city')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <!-- Postal Code -->
        <div>
            <label for="postal_code" class="block text-sm font-medium text-gray-900 dark:text-white">
                {{ __('register.postal_code') }}
            </label>
            <input type="text" id="postal_code" wire:model.defer="postal_code"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-primary focus:ring-brand-primary" />
            @error('postal_code')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <!-- Address -->
        <div>
            <label for="address" class="block text-sm font-medium text-gray-900 dark:text-white">
                {{ __('register.address') }}
            </label>
            <input type="text" id="address" wire:model.defer="address"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-brand-primary focus:ring-brand-primary" />
            @error('address')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="flex justify-between pt-4">
        <button type="button" wire:click="prevStep"
            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition"
            wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="prevStep">{{ __('register.previous') }}</span>
            <span wire:loading wire:target="prevStep">
                {{ __('register.loading') }}
            </span>
        </button>

        <button type="submit" class="px-4 py-2 bg-brand-primary hover:bg-brand-primary-hover dark:bg-brand-primary dark:hover:bg-brand-primary-hover text-white rounded-md transition flex items-center" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="nextStep">{{ __('register.next') }}</span>
            <span wire:loading wire:target="nextStep">{{ __('register.submitting') }}</span>
        </button>
    </div>
</form>
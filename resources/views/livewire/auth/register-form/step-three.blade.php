<div class="text-center mb-8">
    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900 mb-3">
        <span class="text-xl font-bold text-blue-600 dark:text-blue-300">3</span>
    </div>
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('register.step3') }}</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('register.step', ['step' => 3]) }}</p>
</div>

<form wire:submit.prevent="submit" class="space-y-6">
    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white">
            {{ __('register.email') }}
        </label>
        <input type="email" id="email" wire:model.defer="email"
            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500" />
        @error('email')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <!-- Password Field -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-900 dark:text-white">
            {{ __('register.password') }}
        </label>
        <div class="relative">
            <input
                type="{{ $showPassword ? 'text' : 'password' }}"
                id="password"
                wire:model.defer="password"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 pr-10"
            />
            <button
                type="button"
                wire:click="$toggle('showPassword')"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
            >
                <span class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                    {{ $showPassword ? __('register.hide') : __('register.show') }}
                </span>
            </button>
        </div>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('register.help.password') }}</p>
        @error('password')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <!-- Confirm Password Field -->
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-900 dark:text-white">
            {{ __('register.password_confirmation') }}
        </label>
        <div class="relative">
            <input
                type="{{ $showPasswordConfirmation ? 'text' : 'password' }}"
                id="password_confirmation"
                wire:model.defer="password_confirmation"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 pr-10"
            />
            <button
                type="button"
                wire:click="$toggle('showPasswordConfirmation')"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
            >
                <span class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                    {{ $showPasswordConfirmation ? __('register.hide') : __('register.show') }}
                </span>
            </button>
        </div>
        @error('password_confirmation')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <!-- Currency and Account Type -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Currency -->
        <div>
            <label for="currency" class="block text-sm font-medium text-gray-900 dark:text-white">
                {{ __('register.currency') }}
            </label>
            <select id="currency" wire:model.defer="currency"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                <option value="">{{ __('register.select') }}</option>
                <option value="EUR">{{ __('register.eur') }}</option>
                <option value="USD">{{ __('register.usd') }}</option>
                <option value="GBP">{{ __('register.gbp') }}</option>
                <option value="CAD">{{ __('register.cad') }}</option>
                <option value="CHF">{{ __('register.chf') }}</option>
            </select>
            @error('currency')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <!-- Account Type -->
        <div>
            <label for="type" class="block text-sm font-medium text-gray-900 dark:text-white">
                {{ __('register.account_type') }}
            </label>
            <select id="type" wire:model.defer="type"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                <option value="">{{ __('register.select') }}</option>
                <option value="SAVINGS">{{ __('register.savings') }}</option>
                <option value="CHECKING">{{ __('register.checking') }}</option>

            </select>
            @error('type')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Identity Document Upload -->
    <div>
        <label for="identity_document" class="block text-sm font-medium text-gray-900 dark:text-white">
            {{ __('register.identity_document') }}
        </label>
        <input type="file" id="identity_document" wire:model="identity_document" class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100"/>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('register.help.identity_document') }}</p>
        @if ($identity_document)
            <div class="mt-2 flex items-center justify-between align-center">
                <a href="{{ $identity_document->temporaryUrl() }}" target="_blank" class="text-blue-600 hover:underline text-sm">{{ __('register.download') }}</a>
                <button type="button" wire:click="removeIdentityDocument" class="text-red-600 hover:underline text-sm">{{ __('register.cancel') }}</button>
            </div>
        @endif
        @error('identity_document')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <!-- Address Document Upload -->
    <div>
        <label for="address_document" class="block text-sm font-medium text-gray-900 dark:text-white">
            {{ __('register.address_document') }}
        </label>
        <input type="file" id="address_document" wire:model="address_document" class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100"/>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('register.help.address_document') }}</p>
        @if ($address_document)
            <div class="mt-2 flex items-center justify-between align-center">
                <a href="{{ $address_document->temporaryUrl() }}" target="_blank" class="text-blue-600 hover:underline text-sm">{{ __('register.download') }}</a>
                <button type="button" wire:click="removeAddressDocument" class="text-red-600 hover:underline text-sm">{{ __('register.cancel') }}</button>
            </div>
        @endif
        @error('address_document')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <!-- Navigation Buttons -->
    <div class="flex justify-between pt-4">
        <button type="button" wire:click="prevStep" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition" wire:loading.attr="disabled">
            <span wire:loading.remove>{{ __('register.previous') }}</span>
            <span wire:loading>{{ __('register.loading') }}</span>
        </button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition" wire:loading.attr="disabled">
            <span wire:loading.remove>{{ __('register.submit') }}</span>
            <span wire:loading>{{ __('register.loading') }}</span>
        </button>
    </div>
</form>

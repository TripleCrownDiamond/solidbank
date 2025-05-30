<!-- Step Header with Number and Title -->
<div class="text-center mb-8">
    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900 mb-3">
        <span class="text-xl font-bold text-blue-600 dark:text-blue-300">1</span>
    </div>
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('register.step1') }}</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('register.step', ['step' => 1]) }}</p>
</div>

<form wire:submit.prevent="nextStep" class="space-y-6 mt-6">
    <!-- Name Group -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="first_name" class="block text-sm font-medium text-gray-900 dark:text-white">
                {{ __('register.first_name') }}
            </label>
            <input type="text" id="first_name" wire:model.defer="first_name"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100" />
            @error('first_name')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="last_name" class="block text-sm font-medium text-gray-900 dark:text-white">
                {{ __('register.last_name') }}
            </label>
            <input type="text" id="last_name" wire:model.defer="last_name"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100" />
            @error('last_name')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Gender and Birth Date Group -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="gender" class="block text-sm font-medium text-gray-900 dark:text-white">
                {{ __('register.gender') }}
            </label>
            <select id="gender" wire:model.defer="gender"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                <option value="">{{ __('register.select') }}</option>
                <option value="MALE">{{ __('register.male') }}</option>
                <option value="FEMALE">{{ __('register.female') }}</option>
                <option value="OTHER">{{ __('register.other') }}</option>
            </select>
            @error('gender')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="birth_date" class="block text-sm font-medium text-gray-900 dark:text-white">
                {{ __('register.birth_date') }}
            </label>
            <input type="date" id="birth_date" wire:model.defer="birth_date"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100" />
            @error('birth_date')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Marital Status and Profession Group -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="marital_status" class="block text-sm font-medium text-gray-900 dark:text-white">
                {{ __('register.marital_status') }}
            </label>
            <select id="marital_status" wire:model.defer="marital_status"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                <option value="">{{ __('register.select') }}</option>
                <option value="single">{{ __('register.single') }}</option>
                <option value="married">{{ __('register.married') }}</option>
                <option value="divorced">{{ __('register.divorced') }}</option>
                <option value="widowed">{{ __('register.widowed') }}</option>
            </select>
            @error('marital_status')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="profession" class="block text-sm font-medium text-gray-900 dark:text-white">
                {{ __('register.profession') }}
            </label>
            <input type="text" id="profession" wire:model.defer="profession"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100" />
            @error('profession')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end pt-4">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center" wire:loading.attr="disabled">
            <span wire:loading.remove>{{ __('register.next') }}</span>
            <span wire:loading wire:target="nextStep">
                {{ __('register.submitting') }}
            </span>
        </button>
    </div>
</form>
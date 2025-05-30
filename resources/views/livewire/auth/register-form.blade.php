<!-- resources/views/livewire/auth/register-form.blade.php -->
<div class="max-w-4xl mx-auto p-6">

    @error('form_error')
        <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-md">
            <div class="flex justify-between items-center">
                <p class="text-red-800 dark:text-red-200">{{ $message }}</p>
                <button type="button" @click="show = false" class="text-red-500 hover:text-red-700 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    @enderror

    @if ($step === 1)
        @include('livewire.auth.register-form.step-one')
    @elseif ($step === 2)
        @include('livewire.auth.register-form.step-two')
    @elseif ($step === 3)
        @include('livewire.auth.register-form.step-three')
    @elseif ($step === 4)
        @include('livewire.auth.register-form.success')
    @endif
</div>
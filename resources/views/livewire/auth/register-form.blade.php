<!-- resources/views/livewire/auth/register-form.blade.php -->
<div class="max-w-4xl mx-auto p-6">

    <!-- Messages are now handled by the alert-manager component -->

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
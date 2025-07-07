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

<script>
    document.addEventListener('livewire:init', () => {
        // Écouter l'événement de mise à jour d'URL
        Livewire.on('update-url', (event) => {
            const step = event.step || event[0]?.step;
            if (step) {
                const newUrl = new URL(window.location);
                newUrl.searchParams.set('step', step);
                window.history.replaceState({}, '', newUrl.toString());
            }
        });
        
        // Écouter l'événement de succès d'inscription
        Livewire.on('registration-success', () => {
            // Optionnel: ajouter des animations ou effets visuels
            console.log('Registration completed successfully!');
        });
    });
</script>
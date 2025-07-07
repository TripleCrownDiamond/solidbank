<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ getFaviconUrl() }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        @livewireScripts

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
        <x-banner />

        <div class="min-h-screen">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

        @stack('modals')
        
        <!-- Alert Manager -->
        @livewire('alert-manager')
        
        <!-- Custom Scripts -->
        @stack('scripts')

        <script>
            document.addEventListener('livewire:init', () => {
                // Gestion de l'événement refresh-page
                Livewire.on('refresh-page', () => {
                    setTimeout(() => {
                        window.location.reload();
                    }, 100);
                });
                
                // Gestion de l'événement copy-to-clipboard
                Livewire.on('copy-to-clipboard', (...args) => {
                    console.log('Arguments reçus:', args);
                    console.log('Nombre d\'arguments:', args.length);
                    
                    // Gérer différents formats d'événement Livewire
                    let textToCopy, message;
                    
                    if (args.length > 0) {
                        const eventData = args[0];
                        console.log('Premier argument:', eventData);
                        
                        if (typeof eventData === 'object' && eventData !== null) {
                            // Si c'est un objet direct
                            textToCopy = eventData.accountNumber;
                            message = eventData.message || 'Copié dans le presse-papiers';
                        } else if (typeof eventData === 'string') {
                            // Si c'est juste une chaîne
                            textToCopy = eventData;
                            message = 'Copié dans le presse-papiers';
                        }
                    }
                    
                    console.log('Texte à copier:', textToCopy);
                    console.log('Message:', message);
                    
                    if (textToCopy) {
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            navigator.clipboard.writeText(textToCopy).then(() => {
                                console.log('Texte copié avec succès:', textToCopy);
                                
                                // Afficher une notification de succès
                                window.Livewire.dispatch('alert', {
                                    type: 'success',
                                    message: message
                                });
                            }).catch(err => {
                                console.error('Erreur lors de la copie:', err);
                                fallbackCopy(textToCopy, message);
                            });
                        } else {
                            // Fallback pour les navigateurs plus anciens
                            fallbackCopy(textToCopy, message);
                        }
                    } else {
                        console.error('Aucun texte à copier trouvé dans l\'événement');
                    }
                });
                
                // Fonction de fallback pour la copie
                function fallbackCopy(text, message) {
                    try {
                        const textArea = document.createElement('textarea');
                        textArea.value = text;
                        textArea.style.position = 'fixed';
                        textArea.style.opacity = '0';
                        document.body.appendChild(textArea);
                        textArea.select();
                        const successful = document.execCommand('copy');
                        document.body.removeChild(textArea);
                        
                        if (successful) {
                            console.log('Texte copié avec fallback:', text);
                            window.Livewire.dispatch('alert', {
                                type: 'success',
                                message: message
                            });
                        } else {
                            throw new Error('Commande de copie échouée');
                        }
                    } catch (err) {
                        console.error('Erreur lors de la copie fallback:', err);
                        window.Livewire.dispatch('alert', {
                            type: 'error',
                            message: 'Erreur lors de la copie'
                        });
                    }
                }
            });
        </script>

    </body>
</html>

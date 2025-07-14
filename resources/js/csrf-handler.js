// CSRF Token Handler for Livewire and Axios

// Configuration globale d'Axios pour inclure le token CSRF
if (window.axios) {
    // Récupérer le token CSRF depuis la meta tag
    const token = document.querySelector('meta[name="csrf-token"]');
    
    if (token) {
        // Configurer Axios pour inclure automatiquement le token CSRF
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
    } else {
        console.error('Token CSRF non trouvé. Assurez-vous que la meta tag csrf-token est présente.');
    }
    
    // Intercepteur pour gérer les erreurs 419 (Token Mismatch)
    window.axios.interceptors.response.use(
        response => response,
        error => {
            if (error.response && error.response.status === 419) {
                console.warn('Token CSRF expiré, rechargement de la page...');
                
                // Afficher une alerte avant de recharger
                if (window.Livewire) {
                    window.Livewire.dispatch('alert', {
                        type: 'warning',
                        message: 'Session expirée, rechargement de la page...'
                    });
                }
                
                // Recharger la page après un court délai
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
            return Promise.reject(error);
        }
    );
}

// Gestion spécifique pour Livewire
document.addEventListener('livewire:init', () => {
    // Intercepteur pour les requêtes Livewire
    Livewire.hook('request', ({ uri, options, payload, respond, succeed, fail }) => {
        // S'assurer que le token CSRF est inclus dans les requêtes Livewire
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            if (!options.headers) {
                options.headers = {};
            }
            options.headers['X-CSRF-TOKEN'] = token.getAttribute('content');
        }
    });
    
    // Gestion des erreurs de session expirée
    Livewire.hook('request.exception', ({ exception, component, cleanup }) => {
        if (exception.status === 419) {
            console.warn('Session expirée détectée dans Livewire');
            
            // Afficher une alerte
            window.Livewire.dispatch('alert', {
                type: 'error',
                message: 'Session expirée. Veuillez vous reconnecter.'
            });
            
            // Rediriger vers la page de connexion après un délai
            setTimeout(() => {
                const locale = document.documentElement.lang || 'fr';
                window.location.href = `/${locale}/login`;
            }, 2000);
        }
    });
    
    // Rafraîchir le token CSRF périodiquement (toutes les 30 minutes)
    setInterval(() => {
        refreshCsrfToken();
    }, 30 * 60 * 1000); // 30 minutes
});

// Fonction pour rafraîchir le token CSRF
function refreshCsrfToken() {
    if (window.axios) {
        window.axios.get('/csrf-token')
            .then(response => {
                if (response.data.csrf_token) {
                    // Mettre à jour la meta tag
                    const metaTag = document.querySelector('meta[name="csrf-token"]');
                    if (metaTag) {
                        metaTag.setAttribute('content', response.data.csrf_token);
                    }
                    
                    // Mettre à jour la configuration Axios
                    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = response.data.csrf_token;
                    
                    console.log('Token CSRF rafraîchi avec succès');
                }
            })
            .catch(error => {
                console.error('Erreur lors du rafraîchissement du token CSRF:', error);
            });
    }
}

// Fonction utilitaire pour vérifier la validité du token
function validateCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    if (!token || !token.getAttribute('content')) {
        console.error('Token CSRF manquant ou invalide');
        return false;
    }
    return true;
}

// Exporter les fonctions pour utilisation globale
window.csrfHandler = {
    refresh: refreshCsrfToken,
    validate: validateCsrfToken
};

// Vérification initiale du token au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    validateCsrfToken();
});
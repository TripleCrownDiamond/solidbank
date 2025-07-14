// Gestionnaire CSRF avancé pour Laravel - Version Production
class CSRFHandler {
    constructor() {
        this.token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.retryCount = 0;
        this.maxRetries = 2; // Réduit pour éviter les boucles infinies
        this.retryDelay = 1500; // Augmenté à 1.5 secondes
        this.isRefreshing = false;
        this.failedRequests = [];
        this.init();
    }

    init() {
        this.setupAxiosInterceptors();
        this.setupFetchInterceptor();
        this.setupFormSubmissionHandler();
        this.setupLivewireErrorHandler();
        this.setupPageVisibilityHandler();
        this.refreshTokenPeriodically();
        this.setupStorageListener();
    }

    // Configuration des intercepteurs Axios
    setupAxiosInterceptors() {
        if (typeof axios !== 'undefined') {
            // Intercepteur de requête
            axios.interceptors.request.use((config) => {
                if (this.token) {
                    config.headers['X-CSRF-TOKEN'] = this.token;
                }
                return config;
            });

            // Intercepteur de réponse
            axios.interceptors.response.use(
                (response) => response,
                (error) => this.handleCSRFError(error)
            );
        }
    }

    // Intercepteur pour fetch API
    setupFetchInterceptor() {
        const originalFetch = window.fetch;
        window.fetch = async (...args) => {
            const [url, options = {}] = args;
            
            // Ajouter le token CSRF aux en-têtes
            if (this.token && options.method && options.method.toUpperCase() !== 'GET') {
                options.headers = {
                    ...options.headers,
                    'X-CSRF-TOKEN': this.token
                };
            }

            try {
                const response = await originalFetch(url, options);
                
                if (response.status === 419) {
                    return this.handleCSRFError({ response });
                }
                
                return response;
            } catch (error) {
                return this.handleCSRFError(error);
            }
        };
    }

    // Gestionnaire pour les soumissions de formulaire
    setupFormSubmissionHandler() {
        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (form.method.toLowerCase() !== 'get') {
                const tokenInput = form.querySelector('input[name="_token"]');
                if (tokenInput && this.token) {
                    tokenInput.value = this.token;
                }
            }
        });
    }

    // Gestionnaire spécifique pour Livewire
    setupLivewireErrorHandler() {
        document.addEventListener('livewire:request', (event) => {
            if (this.token) {
                event.detail.options.headers = {
                    ...event.detail.options.headers,
                    'X-CSRF-TOKEN': this.token
                };
            }
        });

        document.addEventListener('livewire:response', (event) => {
            if (event.detail.response.status === 419) {
                this.handleCSRFError({ response: event.detail.response });
            }
        });

        // Gestionnaire pour les erreurs Livewire spécifiques
        document.addEventListener('livewire:error', (event) => {
            if (event.detail.status === 419) {
                event.preventDefault();
                this.handleCSRFError({ response: { status: 419 } });
            }
        });
    }

    // Gestionnaire de visibilité de la page
    setupPageVisibilityHandler() {
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden && this.token) {
                // Vérifier le token quand l'utilisateur revient sur la page
                this.verifyTokenValidity();
            }
        });
    }

    // Écouter les changements de stockage local
    setupStorageListener() {
        window.addEventListener('storage', (event) => {
            if (event.key === 'csrf_token_updated') {
                this.token = event.newValue;
                this.updateTokenInDOM(this.token);
            }
        });
    }

    // Vérification de la validité du token
    async verifyTokenValidity() {
        try {
            const response = await fetch('/csrf-token', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.token !== this.token) {
                    this.updateToken(data.token);
                }
            }
        } catch (error) {
            console.warn('Impossible de vérifier la validité du token:', error);
        }
    }

    // Gestion des erreurs CSRF
    async handleCSRFError(error) {
        console.warn('Erreur CSRF détectée:', error);
        
        // Éviter les tentatives multiples simultanées
        if (this.isRefreshing) {
            return new Promise((resolve, reject) => {
                this.failedRequests.push({ resolve, reject });
            });
        }
        
        if (this.retryCount < this.maxRetries) {
            this.retryCount++;
            this.isRefreshing = true;
            
            try {
                await this.refreshToken();
                
                // Traiter les requêtes en attente
                this.failedRequests.forEach(({ resolve }) => resolve());
                this.failedRequests = [];
                
                // Attendre avant de réessayer
                await new Promise(resolve => setTimeout(resolve, this.retryDelay));
                
                this.isRefreshing = false;
                
                // Pour les erreurs 419, afficher un message et recharger
                if (error.response?.status === 419) {
                    this.showCSRFMessage('Session expirée. Rechargement automatique...');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                    return;
                }
                
                return Promise.reject(error);
            } catch (refreshError) {
                this.isRefreshing = false;
                this.failedRequests.forEach(({ reject }) => reject(refreshError));
                this.failedRequests = [];
                
                console.error('Impossible de rafraîchir le token CSRF:', refreshError);
                this.forceReload('Erreur de session. Rechargement nécessaire...');
            }
        } else {
            this.forceReload('Session expirée. Rechargement de la page...');
        }
    }

    // Rafraîchissement du token CSRF
    async refreshToken() {
        try {
            const response = await fetch('/csrf-token', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
            
            if (response.ok) {
                const data = await response.json();
                this.updateToken(data.token);
                this.retryCount = 0; // Reset du compteur en cas de succès
                
                // Notifier les autres onglets
                localStorage.setItem('csrf_token_updated', data.token);
                localStorage.removeItem('csrf_token_updated');
            } else {
                throw new Error(`HTTP ${response.status}: Impossible de récupérer un nouveau token`);
            }
        } catch (error) {
            console.error('Erreur lors du rafraîchissement du token:', error);
            throw error;
        }
    }

    // Mise à jour du token
    updateToken(newToken) {
        this.token = newToken;
        this.updateTokenInDOM(newToken);
        console.log('Token CSRF mis à jour');
    }

    // Mise à jour du token dans le DOM
    updateTokenInDOM(newToken) {
        // Mettre à jour la meta tag
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            metaTag.setAttribute('content', newToken);
        }
        
        // Mettre à jour tous les champs cachés _token
        document.querySelectorAll('input[name="_token"]').forEach(input => {
            input.value = newToken;
        });
        
        // Mettre à jour window.Laravel si présent
        if (window.Laravel && window.Laravel.csrfToken) {
            window.Laravel.csrfToken = newToken;
        }
    }

    // Rafraîchissement périodique du token
    refreshTokenPeriodically() {
        // Rafraîchir le token toutes les 25 minutes (avant expiration)
        setInterval(() => {
            if (!this.isRefreshing) {
                this.refreshToken().catch(error => {
                    console.warn('Rafraîchissement périodique du token échoué:', error);
                });
            }
        }, 25 * 60 * 1000);
    }

    // Affichage d'un message d'information
    showCSRFMessage(text = 'Session expirée. Rechargement de la page...') {
        // Éviter les messages multiples
        if (document.querySelector('.csrf-message')) {
            return;
        }
        
        const message = document.createElement('div');
        message.className = 'csrf-message';
        message.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #e74c3c;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            z-index: 10000;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            max-width: 300px;
            animation: slideIn 0.3s ease-out;
        `;
        
        // Ajouter l'animation CSS
        if (!document.querySelector('#csrf-animation-style')) {
            const style = document.createElement('style');
            style.id = 'csrf-animation-style';
            style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(style);
        }
        
        message.textContent = text;
        document.body.appendChild(message);
        
        setTimeout(() => {
            if (message.parentNode) {
                message.style.animation = 'slideIn 0.3s ease-out reverse';
                setTimeout(() => {
                    if (message.parentNode) {
                        message.parentNode.removeChild(message);
                    }
                }, 300);
            }
        }, 3000);
    }

    // Rechargement forcé de la page
    forceReload(message = 'Session expirée. Rechargement de la page...') {
        this.showCSRFMessage(message);
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    }
}

// Initialisation automatique
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.csrfHandler = new CSRFHandler();
    });
} else {
    window.csrfHandler = new CSRFHandler();
}

// Export pour utilisation dans d'autres modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CSRFHandler;
}
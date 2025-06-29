<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($stats as $stat)
            <div class="stat-card bg-{{ $stat['color'] }}-50 dark:bg-{{ $stat['color'] }}-900/20 rounded-xl p-6 border border-{{ $stat['color'] }}-200 dark:border-{{ $stat['color'] }}-700 hover:shadow-lg transition-all duration-300 hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-{{ $stat['color'] }}-100 dark:bg-{{ $stat['color'] }}-800 rounded-lg">
                        <i class="{{ $stat['icon'] }} text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400 text-xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400 counter" 
                             data-target="{{ $stat['value'] }}" 
                             data-suffix="{{ $stat['suffix'] }}" 
                             data-prefix="{{ $stat['prefix'] }}" 
                             id="counter-{{ $stat['id'] }}">
                            {{ $stat['prefix'] }}0{{ $stat['suffix'] }}
                        </div>
                        <div class="text-sm text-{{ $stat['color'] }}-700 dark:text-{{ $stat['color'] }}-300 font-medium">
                            {{ $stat['label'] }}
                        </div>
                    </div>
                </div>
                
                <!-- Barre de progression animée -->
                <div class="w-full bg-{{ $stat['color'] }}-200 dark:bg-{{ $stat['color'] }}-800 rounded-full h-2">
                    <div class="progress-bar bg-{{ $stat['color'] }}-500 h-2 rounded-full transition-all duration-1000 ease-out" 
                         style="width: 0%" 
                         data-width="{{ min(100, ($stat['value'] / max($stat['value'], 1000)) * 100) }}%"></div>
                </div>
            </div>
        @endforeach
    </div>

<script>
    document.addEventListener('livewire:init', () => {
        // Fonction pour animer un compteur
        function animateCounter(element, target, duration = 2000) {
            const start = 0;
            const increment = target / (duration / 16);
            let current = start;
            const suffix = element.dataset.suffix || '';
            const prefix = element.dataset.prefix || '';
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                // Formatage du nombre
                let displayValue = Math.floor(current);
                if (displayValue >= 1000000) {
                    displayValue = (displayValue / 1000000).toFixed(1) + 'M';
                } else if (displayValue >= 1000) {
                    displayValue = (displayValue / 1000).toFixed(1) + 'K';
                } else {
                    displayValue = displayValue.toLocaleString();
                }
                
                element.textContent = prefix + displayValue + suffix;
            }, 16);
        }
        
        // Fonction pour animer les barres de progression
        function animateProgressBars() {
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach((bar, index) => {
                setTimeout(() => {
                    bar.style.width = bar.dataset.width;
                }, index * 200);
            });
        }
        
        // Fonction pour démarrer toutes les animations
        function startAnimations() {
            const counters = document.querySelectorAll('.counter');
            counters.forEach((counter, index) => {
                setTimeout(() => {
                    const target = parseInt(counter.dataset.target);
                    animateCounter(counter, target);
                }, index * 300);
            });
            
            setTimeout(() => {
                animateProgressBars();
            }, 500);
        }
        
        // Observer pour détecter quand les stats entrent dans la vue
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    startAnimations();
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.5
        });
        
        // Observer le premier élément stat
        const firstStatCard = document.querySelector('.stat-card');
        if (firstStatCard) {
            observer.observe(firstStatCard);
        }
        
        // Écouter les mises à jour des stats
        Livewire.on('stats-updated', (stats) => {
            stats.forEach(stat => {
                const counter = document.getElementById(`counter-${stat.id}`);
                if (counter) {
                    const currentValue = parseInt(counter.textContent.replace(/[^0-9]/g, ''));
                    const newValue = stat.value;
                    
                    if (newValue > currentValue) {
                        animateCounter(counter, newValue, 1000);
                    }
                }
            });
        });
        
        // Mise à jour périodique des stats (toutes les 10 secondes)
        setInterval(() => {
            @this.updateStats();
        }, 10000);
    });
</script>

<style>
    .stat-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .counter {
        font-family: 'Courier New', monospace;
        letter-spacing: 1px;
    }
    
    .progress-bar {
        background: linear-gradient(90deg, currentColor 0%, rgba(255,255,255,0.3) 50%, currentColor 100%);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }
    
    @keyframes pulse-glow {
        0%, 100% {
            box-shadow: 0 0 5px rgba(59, 130, 246, 0.5);
        }
        50% {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.8);
        }
    }
    
    .stat-card:nth-child(1) { animation-delay: 0s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.4s; }
    .stat-card:nth-child(4) { animation-delay: 0.6s; }
</style>
</div>
@props([
    'title' => 'Visa Classic',
    'price' => '0€',
    'subtitle' => 'Gratuite à vie',
    'features' => [],
    'gradient' => 'from-blue-600 to-indigo-600',
    'darkGradient' => 'dark:from-blue-700 dark:to-indigo-700'
])

<!-- Carte bancaire effet glassmorphism pour sections HERO -->
<div class="relative w-80 h-48 bg-white/20 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/30 flex flex-col justify-between p-6 hover:scale-105 transition-transform duration-300">
    <!-- Header avec titre et icône -->
    <div class="flex justify-between items-center">
        <span class="text-lg font-bold text-white">{{ $title }}</span>
        <div class="w-10 h-10 rounded-lg flex items-center justify-center shadow-lg border border-white/30" style="background-color: var(--brand-accent);">
            <i class="fa-solid fa-credit-card text-white text-sm"></i>
        </div>
    </div>
    
    <!-- Prix principal -->
    <div class="text-3xl font-extrabold text-white">{{ $price }}</div>
    
    <!-- Sous-titre -->
    <div class="text-xs text-white/80">{{ $subtitle }}</div>
    
    <!-- Features/Tags -->
    @if(count($features) > 0)
        <div class="flex gap-2 mt-2 flex-wrap">
            @foreach($features as $feature)
                <span class="bg-brand-success/80 text-xs px-2 py-1 rounded text-white whitespace-nowrap">{{ $feature }}</span>
            @endforeach
        </div>
    @endif
    
    <!-- Effet de brillance -->
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 ease-in-out"></div>
</div>
@props(['title', 'subtitle', 'backgroundClass' => 'bg-gradient-to-br from-brand-primary via-brand-secondary to-brand-accent animate-gradient-x'])

<section class="relative {{ $backgroundClass }} text-white py-20 overflow-hidden">
    <!-- Particules flottantes -->
    <div class="absolute inset-0 pointer-events-none z-0">
        <div class="absolute top-10 left-10 w-2 h-2 bg-white/20 rounded-full animate-float"></div>
        <div class="absolute top-20 right-20 w-3 h-3 bg-white/30 rounded-full animate-float" style="animation-delay: 0.5s;"></div>
        <div class="absolute bottom-20 left-20 w-1 h-1 bg-white/40 rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-10 right-10 w-2 h-2 bg-white/25 rounded-full animate-float" style="animation-delay: 1.5s;"></div>
        <div class="absolute top-1/2 left-1/4 w-1 h-1 bg-white/35 rounded-full animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/3 right-1/3 w-2 h-2 bg-white/20 rounded-full animate-float" style="animation-delay: 2.5s;"></div>
    </div>

    <!-- Éléments décoratifs -->
    <div class="absolute top-20 right-20 w-32 h-32 border border-white/10 rounded-full animate-spin-slow"></div>
    <div class="absolute bottom-20 left-20 w-24 h-24 bg-white/5 rounded-lg animate-pulse-slow"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-slide-up">
                {{ $title }}
            </h1>
            <p class="text-xl md:text-2xl text-white/90 animate-slide-up" style="animation-delay: 0.2s;">
                {{ $subtitle }}
            </p>
        </div>
    </div>
</section>
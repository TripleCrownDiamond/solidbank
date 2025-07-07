@props(['title', 'subtitle' => '', 'items' => [], 'columns' => 'md:grid-cols-3'])

<section class="py-20 bg-brand-secondary/5">
    <div class="container mx-auto px-4">
        @if($title)
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-black dark:text-white mb-4 animate-slide-up">
                {{ $title }}
            </h2>
            @if($subtitle)
            <p class="text-xl text-brand-secondary dark:text-brand-secondary/80 max-w-3xl mx-auto animate-slide-up" style="animation-delay: 0.1s;">
                {{ $subtitle }}
            </p>
            @endif
        </div>
        @endif

        <div class="grid grid-cols-1 {{ $columns }} gap-8">
            {{ $slot }}
        </div>
    </div>
</section>
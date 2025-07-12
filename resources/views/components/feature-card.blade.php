@props(['icon', 'title', 'description', 'delay' => '0s', 'iconAnimation' => 'animate-bounce-slow'])

<div class="bg-white/80 backdrop-blur-sm rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105 border border-gray-200 hover:border-blue-600/20 animate-slide-up group" style="animation-delay: {{ $delay }};">
    <div class="text-blue-600 mb-6 {{ $iconAnimation }} group-hover:scale-110 transition-transform duration-300">
        {!! $icon !!}
    </div>
    <h3 class="text-2xl font-bold text-gray-900 mb-4">
        {{ $title }}
    </h3>
    <p class="text-gray-600 leading-relaxed">
        {{ $description }}
    </p>
</div>
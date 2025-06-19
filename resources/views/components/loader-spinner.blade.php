@props([
    'text' => '',
    'defaultText' => '',
    'size' => 'md',
    'position' => 'left',
    'target' => null,
    'loadingClass' => ''
])

<span {{ $attributes->merge(['class' => 'inline-flex items-center']) }}>
    <span wire:loading.remove {{ $target ? "wire:target=\"$target\"" : '' }}>
        {{ $defaultText ?: $slot }}
    </span>
    <span wire:loading {{ $target ? "wire:target=\"$target\"" : '' }} class="inline-flex items-center {{ $loadingClass }}">
        @if ($position === 'left')
            <i class="fa-solid fa-spinner fa-spin {{ $size === 'sm' ? 'text-sm' : ($size === 'lg' ? 'text-lg' : '') }} mr-2 text-gray-800 dark:text-white"></i>
            <span>{{ $text ?: $slot }}</span>
        @else
            <span>{{ $text ?: $slot }}</span>
            <i class="fa-solid fa-spinner fa-spin {{ $size === 'sm' ? 'text-sm' : ($size === 'lg' ? 'text-lg' : '') }} ml-2 text-gray-800 dark:text-white"></i>
        @endif
    </span>
</span>
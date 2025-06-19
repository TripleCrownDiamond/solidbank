<div {{ $attributes->merge(['class' => 'inline-flex items-center']) }}>
    @if ($loading)
        @if ($position === 'left')
            <i class="fa-solid fa-spinner fa-spin {{ $size === 'sm' ? 'text-sm' : ($size === 'lg' ? 'text-lg' : '') }} mr-2"></i>
            <span>{{ $text }}</span>
        @else
            <span>{{ $text }}</span>
            <i class="fa-solid fa-spinner fa-spin {{ $size === 'sm' ? 'text-sm' : ($size === 'lg' ? 'text-lg' : '') }} ml-2"></i>
        @endif
    @else
        <span>{{ $defaultText }}</span>
    @endif
</div>
@props([
    'type' => 'info', // success, error, warning, info
    'message' => '',
    'dismissible' => true,
    'id' => 'alert-' . uniqid()
])

@php
    $alertClasses = [
        'success' => 'border-green-500 text-green-700 bg-green-50 dark:bg-green-900/20 dark:text-green-400 dark:border-green-400',
        'error' => 'border-red-500 text-red-700 bg-red-50 dark:bg-red-900/20 dark:text-red-400 dark:border-red-400',
        'warning' => 'border-yellow-500 text-yellow-700 bg-yellow-50 dark:bg-yellow-900/20 dark:text-yellow-400 dark:border-yellow-400',
        'info' => 'border-blue-500 text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-400'
    ];
    
    $iconClasses = [
        'success' => 'text-green-500 dark:text-green-400',
        'error' => 'text-red-500 dark:text-red-400',
        'warning' => 'text-yellow-500 dark:text-yellow-400',
        'info' => 'text-blue-500 dark:text-blue-400'
    ];
@endphp

<div id="{{ $id }}" 
     class="fixed top-4 right-4 z-50 max-w-sm w-full border-l-4 p-4 rounded-lg shadow-lg backdrop-blur-sm {{ $alertClasses[$type] ?? $alertClasses['info'] }} transition-all duration-300 transform translate-x-0"
     style="background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);"
     x-data="{ show: true }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-x-full"
     x-transition:enter-end="opacity-100 transform translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-x-0"
     x-transition:leave-end="opacity-0 transform translate-x-full">
    
    <div class="flex items-start">
        <!-- Icon -->
        <div class="flex-shrink-0">
            @if($type === 'success')
                <svg class="w-5 h-5 {{ $iconClasses[$type] }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            @elseif($type === 'error')
                <svg class="w-5 h-5 {{ $iconClasses[$type] }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            @elseif($type === 'warning')
                <svg class="w-5 h-5 {{ $iconClasses[$type] }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            @else
                <svg class="w-5 h-5 {{ $iconClasses[$type] }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            @endif
        </div>
        
        <!-- Message -->
        <div class="ml-3 flex-1">
            <p class="text-sm font-medium">
                {{ $message }}
            </p>
            @if($slot->isNotEmpty())
                <div class="mt-1 text-sm">
                    {{ $slot }}
                </div>
            @endif
        </div>
        
        <!-- Close button -->
        @if($dismissible)
            <div class="ml-4 flex-shrink-0">
                <button type="button" 
                        class="inline-flex rounded-md p-1.5 hover:bg-black/5 dark:hover:bg-white/5 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors"
                        @click="show = false; setTimeout(() => document.getElementById('{{ $id }}').remove(), 200)">
                    <span class="sr-only">{{ __('common.close') }}</span>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @endif
    </div>
</div>

@if($dismissible)
<script>
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        const alert = document.getElementById('{{ $id }}');
        if (alert && alert.querySelector('[x-data]').__x) {
            alert.querySelector('[x-data]').__x.$data.show = false;
            setTimeout(() => alert.remove(), 200);
        }
    }, 5000);
</script>
@endif
@extends('layouts.guest')

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br {{ $gradientColors ?? 'from-brand-primary to-brand-primary-dark' }}">
    <div class="max-w-md w-full mx-auto">
        <div class="text-center">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ getLogoUrl() }}" alt="{{ getAppName() }}" class="h-16 mx-auto">
            </div>
            
            <!-- Error Code -->
            <div class="mb-6">
                <h1 class="text-9xl font-bold text-white opacity-20">{{ $code ?? '???' }}</h1>
            </div>
            
            <!-- Error Message -->
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                <div class="mb-6">
                    <div class="w-20 h-20 {{ $iconBgColor ?? 'bg-brand-warning/20' }} rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 {{ $iconColor ?? 'text-brand-warning' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $icon ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>' !!}
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">{{ $title ?? __('Erreur') }}</h2>
                    <p class="text-white/80 text-lg">{{ $message ?? __('Une erreur inattendue s\'est produite.') }}</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="space-y-4">
                    <a href="{{ route('locale.dashboard', ['locale' => app()->getLocale()]) }}" 
                       class="w-full inline-flex items-center justify-center px-6 py-3 bg-brand-accent hover:bg-brand-success text-white font-semibold rounded-lg transition-all duration-200 hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        {{ __('Retour au tableau de bord') }}
                    </a>
                    
                    @if($showBackButton ?? true)
                    <button onclick="history.back()" 
                            class="w-full inline-flex items-center justify-center px-6 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg transition-all duration-200 border border-white/30">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('Page précédente') }}
                    </button>
                    @endif
                    
                    @if($showRefreshButton ?? false)
                    <button onclick="window.location.reload()" 
                            class="w-full inline-flex items-center justify-center px-6 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg transition-all duration-200 border border-white/30">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        {{ __('Réessayer') }}
                    </button>
                    @endif
                </div>
            </div>
            
            <!-- Additional Info -->
            <div class="mt-8 text-center">
                <p class="text-white/60 text-sm">
                    {{ $additionalInfo ?? __('Si le problème persiste, contactez notre support.') }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Floating Elements for Visual Appeal -->
<div class="fixed inset-0 overflow-hidden pointer-events-none">
    <div class="absolute top-1/4 left-1/4 w-64 h-64 {{ $floatingColor1 ?? 'bg-brand-accent/10' }} rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 {{ $floatingColor2 ?? 'bg-brand-warning/10' }} rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    <div class="absolute top-3/4 left-1/3 w-48 h-48 {{ $floatingColor3 ?? 'bg-brand-success/10' }} rounded-full blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
</div>
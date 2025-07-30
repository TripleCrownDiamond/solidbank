@extends('emails.layout')

@section('content')
@php
    $config = getBrandingConfig();
    $brandPrimary = $config && $config->brand_color ? $config->brand_color : '#2563eb';
    $textPrimary = '#1f2937';     // couleur équivalente à var(--text-primary)
    $textSecondary = '#6b7280';   // couleur équivalente à var(--text-secondary)
    $bgSecondary = '#f3f4f6';     // couleur équivalente à var(--bg-secondary)
@endphp

<div style="text-align: center; max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif;">

    <h1 style="color: {{ $textPrimary }}; font-size: 24px; font-weight: normal; margin: 0 0 32px 0;">
        {{ __('auth.account_activation_title') }}
    </h1>

    <p style="color: {{ $textPrimary }}; font-size: 16px; line-height: 1.6; margin: 0 0 24px;">
        {{ __('auth.greeting_activation', ['name' => e($user->first_name)]) }}
    </p>

    <p style="color: {{ $textPrimary }}; font-size: 16px; line-height: 1.6; margin: 0 0 24px;">
        {{ __('auth.account_activation_message') }}
    </p>

    <!-- Bouton d'activation -->
    <div style="text-align: center; margin: 32px 0;">
        <a href="{{ $activationUrl }}" 
           style="display: inline-block; background-color: {{ $brandPrimary }}; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 4px; font-weight: 600; font-size: 16px;">
            {{ __('auth.activate_account_button') }}
        </a>
    </div>

    <!-- Bloc fonctionnalités -->
    <div style="background-color: {{ $bgSecondary }}; padding: 20px; border-radius: 8px; margin: 24px 0;">
        <p style="color: var(--text-primary); font-size: 16px; line-height: 1.6; margin: 0 0 16px; font-weight: 600;">
                Fonctionnalités de votre compte :
            </p>
        <p style="color: {{ $textSecondary }}; font-size: 14px; line-height: 1.6; margin: 0;">
            {{ __('auth.activation_benefits') }}
        </p>
    </div>

    <!-- Note de sécurité -->
    <div style="border-left: 4px solid {{ $brandPrimary }}; padding-left: 16px; margin: 24px 0; text-align: left;">
            <p style="color: {{ $textSecondary }}; font-size: 14px; line-height: 1.6; margin: 0;">
                {{ __('auth.activation_security_note') }}
            </p>
        </div>

</div>
@endsection

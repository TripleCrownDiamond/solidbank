@extends('emails.layout')

@section('content')
@php
    $config = getBrandingConfig();
    $brandPrimary = $config && $config->brand_color ? $config->brand_color : '#2563eb';
    $textPrimary = '#1f2937';     // équivalent var(--text-primary)
    $textSecondary = '#6b7280';   // équivalent var(--text-secondary)
    $bgSecondary = '#f3f4f6';     // équivalent var(--bg-secondary)
@endphp

<div style="text-align:center; max-width:600px; margin:0 auto; font-family:Arial,sans-serif;">

    <h1 style="color:{{ $textPrimary }}; font-size:24px; font-weight:normal; margin:0 0 32px 0;">
        {{ __('auth.account_pending_activation_title') }}
    </h1>

    <p style="color:{{ $textPrimary }}; font-size:16px; line-height:1.6; margin:0 0 24px;">
        {{ __('auth.greeting_pending', ['name' => e($user->first_name)]) }}
    </p>

    <p style="color:{{ $textPrimary }}; font-size:16px; line-height:1.6; margin:0 0 24px;">
        {{ __('auth.account_pending_message') }}
    </p>

    <!-- Bloc statut en attente -->
    <div style="background-color:#fef3c7; padding:20px; border-radius:8px; margin:24px 0; border-left:4px solid #f59e0b; text-align:left;">
        <p style="color:{{ $textPrimary }}; font-size:16px; line-height:1.6; margin:0 0 16px; font-weight:600;">
            ⏳ {{ __('auth.pending_status_title') }}
        </p>
        <p style="color:{{ $textSecondary }}; font-size:14px; line-height:1.6; margin:0;">
            {{ __('auth.pending_status_message') }}
        </p>
    </div>

    <!-- Bloc avantages -->
    <div style="background-color:{{ $bgSecondary }}; padding:20px; border-radius:8px; margin:24px 0; text-align:left;">
        <p style="color:{{ $textPrimary }}; font-size:16px; line-height:1.6; margin:0 0 16px; font-weight:600;">
            {{ __('auth.pending_benefits_title') }}
        </p>
        <p style="color:{{ $textSecondary }}; font-size:14px; line-height:1.6; margin:0;">
            {{ __('auth.pending_benefits_message') }}
        </p>
    </div>

    <!-- Note sécurité -->
    <div style="border-left:4px solid {{ $brandPrimary }}; padding-left:16px; margin:24px 0; text-align:left;">
        <p style="color:{{ $textSecondary }}; font-size:14px; line-height:1.6; margin:0;">
            {{ __('auth.pending_security_note') }}
        </p>
    </div>

    <p style="color:{{ $textPrimary }}; font-size:16px; line-height:1.6; margin:24px 0 0;">
        {{ __('auth.pending_thank_you') }}
    </p>

</div>
@endsection

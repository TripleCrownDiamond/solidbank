@extends('emails.layout')

@section('content')
    @php
        $config = getBrandingConfig();
        $brandPrimary = $config && $config->brand_color ? $config->brand_color : '#2563eb';
        $textPrimary = '#1f2937';
        $textSecondary = '#6b7280';
    @endphp
    <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="color: {{ $textPrimary }}; font-size: 24px; font-weight: normal; margin: 0;">
            {{ __('auth.account_activation_title') }}
        </h1>
    </div>

    <p style="color: {{ $textPrimary }}; font-size: 16px; line-height: 1.6; margin: 0 0 24px;">
        {{ __('auth.greeting_activation', ['name' => $user->first_name]) }}
    </p>

    <p style="color: {{ $textPrimary }}; font-size: 16px; line-height: 1.6; margin: 0 0 24px;">
        {{ __('auth.account_activation_message') }}
    </p>

    <div style="text-align: center; margin: 32px 0;">
        <a href="{{ $activationUrl }}" 
           style="display: inline-block; background-color: {{ $brandPrimary }}; color: white !important; text-decoration: none; padding: 12px 24px; border-radius: 4px; font-weight: normal; font-size: 16px; border: none;">
            {{ __('auth.activate_account_button') }}
        </a>
    </div>

    <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; margin: 24px 0;">
        <p style="color: {{ $textPrimary }}; font-size: 16px; line-height: 1.6; margin: 0 0 16px; font-weight: 500;">
            🎉 Avantages de votre compte :
        </p>
        <p style="color: {{ $textSecondary }}; font-size: 14px; line-height: 1.6; margin: 0;">
            {{ __('auth.activation_benefits') }}
        </p>
    </div>

    <div style="border-left: 4px solid {{ $brandPrimary }}; padding-left: 16px; margin: 24px 0;">
        <p style="color: {{ $textSecondary }}; font-size: 14px; line-height: 1.6; margin: 0;">
            🔒 {{ __('auth.activation_security_note') }}
        </p>
    </div>


@endsection
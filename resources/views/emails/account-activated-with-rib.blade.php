@extends('emails.layout')

@section('title', __('auth.account_activated_subject'))

@section('content')
@php
    $brandingConfig = getBrandingConfig();
    $brandPrimary = $brandingConfig['primary_color'] ?? '#3b82f6';
    $textPrimary = $brandingConfig['text_primary'] ?? '#1f2937';
    $textSecondary = $brandingConfig['text_secondary'] ?? '#6b7280';
@endphp

<div style="text-align:center; max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif;">

    <h1 style="color: {{ $textPrimary }}; margin-bottom: 20px;">
        {{ __('auth.account_activated_title') }}
    </h1>

    <p style="font-size:16px; line-height:1.6; margin-bottom:25px; color: {{ $textPrimary }};">
        {{ __('auth.account_activated_greeting', ['name' => e($user->name)]) }}
    </p>

    <!-- Bloc confirmation compte -->
    <div style="background:#dcfce7; padding: 30px; border-radius: 12px; margin: 30px 0; border-left: 4px solid #16a34a;">
        <p style="font-size:18px; font-weight:600; margin: 0 0 15px 0; color:#15803d;">
            {{ __('auth.account_activated_message') }}
        </p>
        <p style="margin:0; color:#15803d; font-weight:600;">
            <strong>{{ __('common.account_number') }}:</strong> {{ e($account->account_number) }}
        </p>
    </div>

    <!-- Bloc RIB -->
    <div style="background:#f8fafc; padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid {{ $brandPrimary }};">
        <h3 style="margin: 0 0 20px 0; color: {{ $textPrimary }}; font-size:18px; font-weight:600;">
            {{ __('auth.rib_information_title') }}
        </h3>

        @foreach ([['label'=>__('auth.iban_label'),'value'=>$rib->iban],
                   ['label'=>__('auth.swift_label'),'value'=>$rib->swift],
                   ['label'=>__('auth.bank_name_label'),'value'=>$rib->bank_name]] as $item)
            <div style="background:white; padding:15px; border-radius:8px; border-left:3px solid {{ $brandPrimary }}; margin-bottom: 15px;">
                <p style="margin:0 0 5px 0; color: {{ $textSecondary }}; font-size:14px; font-weight:600; text-transform:uppercase;">
                    {{ $item['label'] }}
                </p>
                <p style="margin:0; color: {{ $textPrimary }}; font-size:16px; font-family:monospace; font-weight:600;">
                    {{ e($item['value']) }}
                </p>
            </div>
        @endforeach
    </div>

    <!-- Instructions connexion -->
    <div style="background:#dbeafe; padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid {{ $brandPrimary }};">
        <h3 style="margin: 0 0 15px 0; color: {{ $textPrimary }}; font-size:18px; font-weight:600;">
            {{ __('auth.login_instructions_title') }}
        </h3>
        <p style="margin: 0 0 20px 0; color: {{ $textPrimary }}; line-height:1.6;">
            {{ __('auth.login_instructions_message') }}
        </p>

        <div style="text-align:center; margin:20px 0;">
            <a href="{{ $loginUrl }}"
               style="display:inline-block; background:{{ $brandPrimary }}; color:white; padding:15px 30px; text-decoration:none; border-radius:8px; font-weight:600; font-size:16px;">
                {{ __('auth.login_button') }}
            </a>
        </div>

        <div style="background:white; padding:15px; border-radius:8px; margin-top:20px; text-align:left;">
            <p style="margin:0 0 10px 0; color: {{ $textSecondary }}; font-size:14px; font-weight:600;">
                {{ __('auth.login_credentials_title') }}
            </p>
            <p style="margin:0 0 5px 0; color: {{ $textPrimary }}; font-size:14px;">
                <strong>{{ __('auth.email_label') }}:</strong> {{ e($user->email) }}
            </p>
            <p style="margin:0; color: {{ $textPrimary }}; font-size:14px;">
                <strong>{{ __('auth.password_label') }}:</strong> {{ __('auth.password_you_created') }}
            </p>
        </div>
    </div>

    <!-- Note sécurité -->
    <div style="background:#fef3c7; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #f59e0b;">
        <p style="margin:0; color:#d97706; font-size:14px; line-height:1.5;">
            <strong>{{ __('auth.security_note_title') }}:</strong> {{ __('auth.security_note_message') }}
        </p>
    </div>

</div>
@endsection

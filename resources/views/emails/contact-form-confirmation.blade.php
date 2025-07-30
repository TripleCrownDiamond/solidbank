@extends('emails.layout')

@section('title')
    {{ __('common.contact_confirmation.title') }}
@endsection

@section('content')
@php
    $brandingConfig = getBrandingConfig() ?? [];
    $brandPrimary = data_get($brandingConfig, 'brand_color', '#2563eb');
    $textPrimary = '#1f2937';
    $textSecondary = '#6b7280';
@endphp

<div style="text-align:center; max-width:600px; margin:0 auto; font-family:Arial,sans-serif;">
    <p style="font-size:16px; line-height:1.5; color:{{ $textPrimary }};">
        {{ __('common.contact_confirmation.greeting', ['name' => e($contactData['name'])]) }}
    </p>

    <p style="font-size:16px; line-height:1.5; color:{{ $textPrimary }};">
        {{ __('common.contact_confirmation.message') }}
    </p>

    {{-- Récapitulatif du message --}}
    <div style="background-color:#f3f4f6; padding:15px; border-radius:8px; margin:20px 0;">
        <h3 style="font-size:18px; color:{{ $brandPrimary }}; margin:0 0 10px;">
            {{ __('common.contact_confirmation.summary') }}
        </h3>
        <ul style="list-style:none; padding:0; margin:0;">
            <li style="margin-bottom:8px;">
                <strong>{{ __('common.subject') }} :</strong> {{ e($contactData['subject']) }}
            </li>
            <li style="margin-bottom:8px;">
                <strong>{{ __('common.your_email') }} :</strong> {{ e($contactData['email']) }}
            </li>
        </ul>

        <h4 style="font-size:16px; color:{{ $brandPrimary }}; margin:15px 0 8px;">
            {{ __('common.your_message') }} :
        </h4>
        <div style="background-color:#ffffff; padding:10px; border-radius:4px; border-left:4px solid {{ $brandPrimary }};">
            <p style="margin:0; font-size:14px; line-height:1.4; color:{{ $textSecondary }};">
                {{ e($contactData['message']) }}
            </p>
        </div>
    </div>

    {{-- Prochaines étapes --}}
    <div style="background-color:#dbeafe; padding:15px; border-radius:8px; margin:20px 0;">
        <h3 style="font-size:18px; color:{{ $brandPrimary }}; margin:0 0 10px;">
            {{ __('common.contact_confirmation.next_steps') }}
        </h3>
        <ul style="margin:0; padding-left:20px; text-align:left;">
            <li style="margin-bottom:8px;">{{ __('common.contact_confirmation.step1') }}</li>
            <li style="margin-bottom:8px;">{{ __('common.contact_confirmation.step2') }}</li>
            <li style="margin-bottom:8px;">{{ __('common.contact_confirmation.step3') }}</li>
        </ul>
    </div>

    <p style="font-size:16px; line-height:1.5; color:{{ $textPrimary }};">
        {{ __('common.contact_confirmation.thank_you') }}
    </p>
</div>
@endsection

@extends('emails.layout')

@section('title', $subject)

@section('content')
@php
    $brandingConfig = getBrandingConfig() ?? [];
    $brandPrimary = data_get($brandingConfig, 'primary_color', '#3b82f6');
    $textPrimary = data_get($brandingConfig, 'text_primary', '#1f2937');
    $textSecondary = data_get($brandingConfig, 'text_secondary', '#6b7280');

    $verificationUrl = function () use ($user) {
        $expires = now()->addMinutes(60)->timestamp;
        $baseUrl = route('verification.verify', [
            'locale' => app()->getLocale(),
            'id' => $user->id,
            'hash' => sha1($user->email)
        ]);
        $signature = hash_hmac('sha256', $baseUrl . '?expires=' . $expires, (string) config('app.key'));
        return $baseUrl . '?expires=' . $expires . '&signature=' . $signature;
    };
@endphp

<div style="text-align:center; max-width:600px; margin:0 auto; font-family:Arial,sans-serif;">
    <h1 style="margin-bottom:32px;">{{ $subject }}</h1>

    <p style="font-size:16px; line-height:1.6; margin-bottom:25px; color:{{ $textPrimary }};">
        @switch($actionType)
            @case('activated')
                {{ __('common.greeting_activation', ['name' => e($user->name)]) }}
                @break
            @case('suspended')
                {{ __('common.greeting_suspension', ['name' => e($user->name)]) }}
                @break
            @case('deleted')
                {{ __('common.greeting_deletion', ['name' => e($user->name)]) }}
                @break
            @case('email_updated')
                {{ __('common.greeting_email_updated', ['name' => e($user->name)]) }}
                @break
            @case('welcome_verification')
                {{ __('common.greeting_welcome', ['name' => e($user->name)]) }}
                @break
            @default
                {{ __('common.welcome_name', ['name' => e($user->name)]) }}
        @endswitch
    </p>

    <div style="background:linear-gradient(135deg,#f8fafc,#e2e8f0); padding:30px; border-radius:12px; margin:30px 0; border-left:4px solid {{ $brandPrimary }};">
        <p style="font-size:18px; font-weight:600; margin:0; color:{{ $textPrimary }};">
            {{ $emailMessage }}
        </p>
    </div>

    {{-- Bloc activation --}}
    @if($actionType === 'activated' && !empty($account))
        <div style="background:#dcfce7; padding:25px; border-radius:12px; margin:25px 0; border:2px solid #16a34a;">
            <p style="margin:0; color:#15803d; font-weight:600;">
                <strong>{{ __('common.account_number') }}:</strong> {{ e($account->account_number) }}
            </p>
        </div>
    @endif

    {{-- Bloc suspension --}}
    @if($actionType === 'suspended' && !empty($account) && ($account->suspension_reason || $account->suspension_instructions))
        <div style="background:#fef3c7; padding:25px; border-radius:12px; margin:25px 0; border:2px solid #f59e0b;">
            @if($account->suspension_reason)
                <p style="margin:0; color:#d97706; font-weight:600;">
                    <strong>{{ __('common.suspension_reason') }}:</strong>
                </p>
                <p style="margin:5px 0 15px 0; color:#d97706;">{{ e($account->suspension_reason) }}</p>
            @endif
            @if($account->suspension_instructions)
                <p style="margin:0; color:#d97706; font-weight:600;">
                    <strong>{{ __('common.suspension_instructions') }}:</strong>
                </p>
                <p style="margin:5px 0 0 0; color:#d97706;">{{ e($account->suspension_instructions) }}</p>
            @endif
        </div>
    @endif

    {{-- Email mis à jour --}}
    @if($actionType === 'email_updated')
        <div style="background:#dbeafe; padding:25px; border-radius:12px; margin:25px 0; border:2px solid {{ $brandPrimary }};">
            <p style="margin:0 0 15px 0; color:{{ $textPrimary }}; font-weight:600;">
                {{ __('common.email_verification_required') }}
            </p>
            <p style="margin:0 0 20px 0; color:{{ $textPrimary }};">{{ __('common.email_verification_instructions') }}</p>
            <div style="text-align:center; margin:20px 0;">
                <a href="{{ $verificationUrl() }}" 
                   style="display:inline-block; background:{{ $brandPrimary }}; color:#fff !important; padding:12px 30px; text-decoration:none; border-radius:8px; font-weight:600;">
                    {{ __('common.verify_email_button') }}
                </a>
            </div>
        </div>
    @endif

    {{-- Email verification --}}
    @if($actionType === 'email_verification')
        <div style="background:#dbeafe; border-left:4px solid {{ $brandPrimary }}; padding:20px; border-radius:8px; margin:20px 0;">
            <p style="margin:0 0 16px; color:{{ $textPrimary }};">{{ __('common.email_verification_instructions') }}</p>
            <div style="text-align:center; margin:24px 0;">
                <a href="{{ $verificationUrl() }}" 
                   style="display:inline-block; background-color:{{ $brandPrimary }}; color:#fff !important; padding:12px 24px; text-decoration:none; border-radius:6px; font-weight:600;">
                    {{ __('common.verify_email_button') }}
                </a>
            </div>
            <p style="margin:16px 0 0; color:{{ $textSecondary }}; font-size:14px;">{{ __('common.email_verification_note') }}</p>
        </div>
    @endif

    {{-- Welcome verification --}}
    @if($actionType === 'welcome_verification')
        <div style="background:#dbeafe; border-left:4px solid {{ $brandPrimary }}; padding:20px; border-radius:8px; margin:20px 0;">
            <p style="margin:0 0 16px; color:{{ $textPrimary }};">{{ __('common.welcome_verification_instructions') }}</p>
            <div style="text-align:center; margin:24px 0;">
                <a href="{{ $verificationUrl() }}" 
                   style="display:inline-block; background-color:{{ $brandPrimary }}; color:#fff !important; padding:12px 24px; text-decoration:none; border-radius:6px; font-weight:600;">
                    {{ __('common.verify_email_button') }}
                </a>
            </div>
            <p style="margin:16px 0 0; color:{{ $textSecondary }}; font-size:14px;">{{ __('common.welcome_verification_note') }}</p>
            <p style="margin:16px 0 0; color:{{ $textSecondary }}; font-size:14px;">{{ __('common.welcome_verification_resend_info') }}</p>
        </div>
    @endif

</div>
@endsection

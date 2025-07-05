@extends('emails.layout')

@section('title', $subject)

@section('content')
@php
    $brandingConfig = getBrandingConfig();
    $brandPrimary = $brandingConfig['primary_color'] ?? '#3b82f6';
    $textPrimary = $brandingConfig['text_primary'] ?? '#1f2937';
    $textSecondary = $brandingConfig['text_secondary'] ?? '#6b7280';
@endphp
    <h1>{{ $subject }}</h1>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: {{ $textPrimary }};">
        @if($actionType === 'activated')
            {{ __('common.greeting_activation', ['name' => $user->name]) }}
        @elseif($actionType === 'suspended')
            {{ __('common.greeting_suspension', ['name' => $user->name]) }}
        @elseif($actionType === 'deleted')
            {{ __('common.greeting_deletion', ['name' => $user->name]) }}
        @elseif($actionType === 'email_updated')
            {{ __('common.greeting_email_updated', ['name' => $user->name]) }}
        @elseif($actionType === 'welcome_verification')
            {{ __('common.greeting_welcome', ['name' => $user->name]) }}
        @else
            {{ __('common.welcome_name', ['name' => $user->name]) }}
        @endif
    </p>
    
    <div style="background: linear-gradient(135deg, #f8fafc, #e2e8f0); padding: 30px; border-radius: 12px; margin: 30px 0; border-left: 4px solid {{ $brandPrimary }};">
        <p style="font-size: 18px; font-weight: 600; margin: 0; color: {{ $textPrimary }};">
            {{ $emailMessage }}
        </p>
    </div>
    

    
    @if($actionType === 'activated')
        <div style="background: linear-gradient(135deg, #dcfce7, #dcfce7); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid #16a34a;">
            <p style="margin: 0; color: #15803d; font-weight: 600;">
                <strong>{{ __('common.account_number') }}:</strong> {{ $account->account_number }}
            </p>
        </div>
    @endif
    
    @if($actionType === 'suspended' && ($account->suspension_reason || $account->suspension_instructions))
        <div style="background: linear-gradient(135deg, #fef3c7, #fef3c7); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid #f59e0b;">
            @if($account->suspension_reason)
                <div style="margin-bottom: 15px;">
                    <p style="margin: 0; color: #d97706; font-weight: 600;">
                        <strong>{{ __('common.suspension_reason') }}:</strong>
                    </p>
                    <p style="margin: 5px 0 0 0; color: #d97706; line-height: 1.5;">
                        {{ $account->suspension_reason }}
                    </p>
                </div>
            @endif
            
            @if($account->suspension_instructions)
                <div>
                    <p style="margin: 0; color: #d97706; font-weight: 600;">
                        <strong>{{ __('common.suspension_instructions') }}:</strong>
                    </p>
                    <p style="margin: 5px 0 0 0; color: #d97706; line-height: 1.5;">
                        {{ $account->suspension_instructions }}
                    </p>
                </div>
            @endif
        </div>
    @endif
    
    @if($actionType === 'email_updated')
        <div style="background: linear-gradient(135deg, #dbeafe, #dbeafe); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid {{ $brandPrimary }};">
            <p style="margin: 0 0 15px 0; color: {{ $textPrimary }}; font-weight: 600;">
                {{ __('common.email_verification_required') }}
            </p>
            <p style="margin: 0 0 20px 0; color: {{ $textPrimary }}; line-height: 1.5;">
                {{ __('common.email_verification_instructions') }}
            </p>
            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ route('verification.verify', ['locale' => app()->getLocale(), 'id' => $user->id, 'hash' => sha1($user->email)]) }}?expires={{ now()->addMinutes(60)->timestamp }}&signature={{ hash_hmac('sha256', route('verification.verify', ['locale' => app()->getLocale(), 'id' => $user->id, 'hash' => sha1($user->email)]) . '?expires=' . now()->addMinutes(60)->timestamp, config('app.key')) }}" 
                   style="display: inline-block; background: {{ $brandPrimary }}; color: white !important; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; border: none;">
                    {{ __('common.verify_email_button') }}
                </a>
            </div>
        </div>
    @endif

    @if($actionType === 'email_verification')
        <div style="background: linear-gradient(135deg, #dbeafe, #dbeafe); border-left: 4px solid {{ $brandPrimary }}; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0 0 16px; color: {{ $textPrimary }}; line-height: 1.6;">
                {{ __('common.email_verification_instructions') }}
            </p>
            <div style="text-align: center; margin: 24px 0;">
                <a href="{{ route('verification.verify', ['locale' => app()->getLocale(), 'id' => $user->id, 'hash' => sha1($user->email)]) }}?expires={{ now()->addMinutes(60)->timestamp }}&signature={{ hash_hmac('sha256', route('verification.verify', ['locale' => app()->getLocale(), 'id' => $user->id, 'hash' => sha1($user->email)]) . '?expires=' . now()->addMinutes(60)->timestamp, config('app.key')) }}" 
                   style="display: inline-block; background-color: {{ $brandPrimary }}; color: white !important; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px; border: none;">
                    {{ __('common.verify_email_button') }}
                </a>
            </div>
            <p style="margin: 16px 0 0; color: {{ $textSecondary }}; font-size: 14px; line-height: 1.5;">
                {{ __('common.email_verification_note') }}
            </p>
        </div>
    @endif

    @if($actionType === 'welcome_verification')
        <div style="background: linear-gradient(135deg, #dbeafe, #dbeafe); border-left: 4px solid {{ $brandPrimary }}; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0 0 16px; color: {{ $textPrimary }}; line-height: 1.6;">
                {{ __('common.welcome_verification_instructions') }}
            </p>
            <div style="text-align: center; margin: 24px 0;">
                <a href="{{ route('verification.verify', ['locale' => app()->getLocale(), 'id' => $user->id, 'hash' => sha1($user->email)]) }}?expires={{ now()->addMinutes(60)->timestamp }}&signature={{ hash_hmac('sha256', route('verification.verify', ['locale' => app()->getLocale(), 'id' => $user->id, 'hash' => sha1($user->email)]) . '?expires=' . now()->addMinutes(60)->timestamp, config('app.key')) }}" 
                   style="display: inline-block; background-color: {{ $brandPrimary }}; color: white !important; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px; border: none;">
                    {{ __('common.verify_email_button') }}
                </a>
            </div>
            <p style="margin: 16px 0 0; color: {{ $textSecondary }}; font-size: 14px; line-height: 1.5;">
                {{ __('common.welcome_verification_note') }}
            </p>
            <p style="margin: 16px 0 0; color: {{ $textSecondary }}; font-size: 14px; line-height: 1.5;">
                {{ __('common.welcome_verification_resend_info') }}
            </p>
        </div>
    @endif
    
@endsection
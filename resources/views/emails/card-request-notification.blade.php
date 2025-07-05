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
        {{ $emailMessage }}
    </p>
        
    <div style="background: linear-gradient(135deg, #f8fafc, #e2e8f0); padding: 30px; border-radius: 12px; margin: 30px 0; border-left: 4px solid {{ $brandPrimary }};">
        <h3 style="color: {{ $textPrimary }}; margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">{{ __('common.card_request_details') }}</h3>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
            <span style="font-weight: bold; color: {{ $textSecondary }};">{{ __('common.request_id') }}:</span>
            <span style="color: {{ $textPrimary }};">#{{ $cardRequest->id }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
            <span style="font-weight: bold; color: {{ $textSecondary }};">{{ __('common.card_type') }}:</span>
            <span style="color: {{ $textPrimary }};">{{ $cardRequest->card_type }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
            <span style="font-weight: bold; color: {{ $textSecondary }};">{{ __('common.status') }}:</span>
            <span style="color: {{ $textPrimary }};">
                @if(strtolower($cardRequest->status) === 'pending')
                    <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; background: #fef3c7; color: #d97706;">
                        {{ __('common.' . strtolower($cardRequest->status)) }}
                    </span>
                @elseif(strtolower($cardRequest->status) === 'cancelled')
                    <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; background: #fee2e2; color: #dc2626;">
                        {{ __('common.' . strtolower($cardRequest->status)) }}
                    </span>
                @else
                    <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; background: #dbeafe; color: #2563eb;">
                        {{ __('common.' . strtolower($cardRequest->status)) }}
                    </span>
                @endif
            </span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
            <span style="font-weight: bold; color: {{ $textSecondary }};">{{ __('common.phone_number') }}:</span>
            <span style="color: {{ $textPrimary }};">{{ $cardRequest->phone_number }}</span>
        </div>
        @if($cardRequest->message)
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
            <span style="font-weight: bold; color: {{ $textSecondary }};">{{ __('common.message') }}:</span>
            <span style="color: {{ $textPrimary }};">{{ $cardRequest->message }}</span>
        </div>
        @endif
        <div style="display: flex; justify-content: space-between; margin-bottom: 0; padding-bottom: 0;">
            <span style="font-weight: bold; color: {{ $textSecondary }};">{{ __('common.requested_date') }}:</span>
            <span style="color: {{ $textPrimary }};">{{ $cardRequest->created_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>
        
    <div style="background: linear-gradient(135deg, var(--info-bg), var(--info-bg)); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid var(--brand-primary-light);">
        <h3 style="color: var(--brand-primary-dark); margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">{{ __('common.customer_information') }}</h3>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-bg);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">{{ __('common.full_name') }}:</span>
            <span style="color: var(--brand-primary-dark);">{{ $user->first_name }} {{ $user->last_name }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-bg);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">{{ __('common.email') }}:</span>
            <span style="color: var(--brand-primary-dark);">{{ $user->email }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-bg);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">{{ __('common.account_number') }}:</span>
            <span style="color: var(--brand-primary-dark);">{{ $cardRequest->account->account_number }}</span>
        </div>
        @if($user->date_of_birth)
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-bg);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">{{ __('common.date_of_birth') }}:</span>
            <span style="color: var(--brand-primary-dark);">{{ $user->date_of_birth->format('d/m/Y') }}</span>
        </div>
        @endif
        @if($user->address)
        <div style="display: flex; justify-content: space-between; margin-bottom: 0; padding-bottom: 0;">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">{{ __('common.address') }}:</span>
            <span style="color: var(--brand-primary-dark);">{{ $user->address }}</span>
        </div>
        @endif
    </div>
    
    @if($actionType === 'new_request')
    <div style="background: linear-gradient(135deg, var(--warning-bg), var(--warning-bg)); padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid var(--warning-border);">
        <p style="margin: 0; color: var(--warning-text); font-size: 16px; line-height: 1.5;">
            <strong>{{ __('common.action_required') }}:</strong> {{ __('common.please_review_card_request') }}
        </p>
    </div>
    @endif
    
@endsection
@extends('emails.layout')

@section('title')
    {{ __('common.new_contact_message') }}
@endsection

@section('content')
    <div style="text-align: center; max-width: 100%; margin: 0 auto;">
        <h1 style="color: var(--brand-primary); font-size: 28px; margin-bottom: 20px;">{{ __('common.new_contact_message') }}</h1>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">
        {{ __('common.contact_message_received') }}
    </p>
    
    <div style="background: linear-gradient(135deg, var(--info-bg), var(--info-bg)); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid var(--brand-primary);">
        <h3 style="color: var(--brand-primary-dark); margin-top: 0; margin-bottom: 20px;">{{ __('common.message_details') }}</h3>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-border);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">{{ __('common.name') }} :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ $contactData['name'] }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-border);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">{{ __('common.email') }} :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ $contactData['email'] }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-border);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">{{ __('common.subject') }} :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ $contactData['subject'] }}</span>
        </div>
        
        <div style="margin-bottom: 0; padding-bottom: 0;">
            <span style="font-weight: bold; color: var(--brand-primary-dark); display: block; margin-bottom: 10px;">{{ __('common.message') }} :</span>
            <div style="padding: 15px 0; color: var(--brand-primary-dark); white-space: pre-wrap;">
                {{ $contactData['message'] }}
            </div>
        </div>
    </div>
    
        <p style="font-size: 16px; line-height: 1.6; margin-top: 25px; color: var(--text-primary);">
            {{ __('common.contact_form_notice') }}
        </p>
    </div>
@endsection
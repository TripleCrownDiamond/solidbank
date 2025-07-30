@extends('emails.layout')

@section('title')
    @php
        if (isset($locale)) {
            App::setLocale($locale);
        }
    @endphp
    {{ __('loan.confirmation.subject') }}
@endsection

@section('content')
    @php
        if (isset($locale)) {
            App::setLocale($locale);
        }
    @endphp
    <h1 style="color: var(--brand-primary); font-size: 28px; margin-bottom: 20px;">{{ __('loan.confirmation.title') }}</h1>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">
        {{ __('loan.confirmation.greeting', ['name' => $loanData['first_name'] . ' ' . $loanData['last_name']]) }}
    </p>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">
        {{ __('loan.confirmation.message') }}
    </p>
    
    <!-- Récapitulatif de la demande -->
    <div style="background: linear-gradient(135deg, var(--info-bg), var(--info-bg)); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid var(--brand-primary);">
        <h3 style="color: var(--brand-primary-dark); margin-top: 0; margin-bottom: 20px;">{{ __('loan.confirmation.summary') }}</h3>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-border);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">{{ __('loan.loan_amount') }} :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ number_format($loanData['loan_amount'], 2) }} {{ $loanData['currency'] }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-border);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">{{ __('loan.loan_duration') }} :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ $loanData['loan_duration'] }} {{ __('loan.months') }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-border);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">{{ __('loan.loan_purpose') }} :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ $loanData['loan_purpose'] }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-border);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">{{ __('loan.monthly_income') }} :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ number_format($loanData['monthly_income'], 2) }} {{ $loanData['currency'] }}</span>
        </div>
        
        <div style="margin-bottom: 10px;">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">{{ __('loan.employment_status') }} :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ __('loan.employment_statuses.' . $loanData['employment_status']) }}</span>
        </div>
    </div>
    
    <!-- Prochaines étapes -->
    <div style="background: linear-gradient(135deg, var(--success-bg), var(--success-bg)); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid var(--success-border);">
        <h3 style="color: var(--success-text); margin-top: 0; margin-bottom: 20px;">{{ __('loan.confirmation.next_steps') }}</h3>
        
        <ul style="color: var(--text-primary); line-height: 1.8; margin: 0; padding-left: 20px;">
            <li>{{ __('loan.confirmation.step1') }}</li>
            <li>{{ __('loan.confirmation.step2') }}</li>
            <li>{{ __('loan.confirmation.step3') }}</li>
            <li>{{ __('loan.confirmation.step4') }}</li>
        </ul>
    </div>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">
        {{ __('loan.confirmation.contact_info') }}
    </p>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">
        {{ __('loan.confirmation.thanks') }}
    </p>
@endsection
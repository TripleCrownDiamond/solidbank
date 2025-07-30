@extends('emails.layout')

@section('title', __('common.transaction_cancelled_title'))

@section('content')
    <h1 style="color: var(--error-border); font-size: 28px; margin-bottom: 20px;">{{ __('common.transaction_cancelled_title') }}</h1>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">
        {!! __('common.transaction_cancelled_greeting') !!} <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>,
    </p>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">
        {{ __('common.transaction_cancelled_notification') }}
    </p>
    
    <div style="background: linear-gradient(135deg, var(--error-bg), var(--error-bg)); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid var(--error-border);">
        <h3 style="color: var(--error-text); margin-top: 0; margin-bottom: 20px;">{{ __('common.cancelled_transaction_details') }}</h3>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--error-border);">
            <span style="font-weight: bold; color: var(--error-text);">{{ __('common.reference') }} :</span>
            <span style="color: var(--error-text);">{{ $transaction->reference }}</span>
        </div>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--error-border);">
            <span style="font-weight: bold; color: var(--error-text);">{{ __('common.type') }} :</span>
            <span style="color: var(--error-text);">
                @if($transaction->type === 'DEPOSIT')
                    {{ __('common.deposit_type_transaction') }}
                @elseif($transaction->type === 'WITHDRAWAL')
                    {{ __('common.withdrawal_type') }}
                @else
                    {{ $transaction->type }}
                @endif
            </span>
        </div>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--error-border);">
            <span style="font-weight: bold; color: var(--error-text);">{{ __('common.amount') }} :</span>
            <span style="font-size: 1.2em; font-weight: bold; color: var(--error-text);">{{ $amount }} {{ $transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR')) }}</span>
        </div>
        
        @if($transaction->account_id)
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--error-border);">
                <span style="font-weight: bold; color: var(--error-text);">{{ __('common.account') }} :</span>
                <span style="color: var(--error-text);">{{ $transaction->account->account_number ?? 'N/A' }}</span>
            </div>
        @endif
        
        @if($transaction->wallet_id)
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--error-border);">
                <span style="font-weight: bold; color: var(--error-text);">{{ __('common.wallet') }} :</span>
                <span style="color: var(--error-text);">{{ $transaction->wallet->coin ?? 'N/A' }} - {{ substr($transaction->wallet->address ?? '', 0, 10) }}...{{ substr($transaction->wallet->address ?? '', -6) }}</span>
            </div>
        @endif
        
        @if($transaction->description)
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--error-border);">
                <span style="font-weight: bold; color: var(--error-text);">{{ __('common.reason') }} :</span>
                <span style="color: var(--error-text);">{{ $transaction->description }}</span>
            </div>
        @endif
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 0; padding-bottom: 0;">
            <span style="font-weight: bold; color: var(--error-text);">{{ __('common.cancellation_date') }} :</span>
            <span style="color: var(--error-text);">{{ $transaction->cancelled_at ? $transaction->cancelled_at->format('d/m/Y à H:i') : now()->format('d/m/Y à H:i') }}</span>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, var(--warning-bg), var(--warning-bg)); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid var(--warning-border);">
        <p style="margin: 0; color: var(--warning-text); font-weight: 600;"><strong>{{ __('common.important_notice') }} :</strong></p>
        <p style="margin: 5px 0 0 0; color: var(--warning-text); line-height: 1.5;">{{ __('common.transaction_cancelled_warning') }}</p>
    </div>
    
    @if($transaction->type === 'DEPOSIT')
        <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">{!! __('common.deposit_not_completed', ['amount' => $amount . ' ' . ($transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR')))]) !!}</p>
        @elseif($transaction->type === 'WITHDRAWAL')
        <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">{!! __('common.withdrawal_not_completed', ['amount' => $amount . ' ' . ($transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR')))]) !!}</p>
    @endif
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">{{ __('common.new_transaction_instructions') }}</p>
@endsection